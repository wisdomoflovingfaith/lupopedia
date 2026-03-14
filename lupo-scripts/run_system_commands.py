#!/usr/bin/env python3
"""
System Commands Queue Runner
Polls lupo_system_commands table and executes queued commands.
Doctrine-compliant: claim protocol, heartbeats, soft deletes, BIGINT UTC timestamps.
"""

import sys
import os
import time
import json
import subprocess
import hashlib
from datetime import datetime
import signal

# Add parent directory to path for imports
sys.path.insert(0, os.path.dirname(os.path.dirname(os.path.abspath(__file__))))

try:
    import pymysql
    pymysql.install_as_MySQLdb()
except ImportError:
    pass

try:
    import MySQLdb
except ImportError:
    print("ERROR: MySQLdb or pymysql required. Install: pip install mysqlclient or pip install pymysql")
    sys.exit(1)

# Configuration
HEARTBEAT_INTERVAL = 30  # seconds
STALE_JOB_TIMEOUT = 300  # 5 minutes
POLL_INTERVAL = 5  # seconds
MAX_EXECUTION_TIME = 3600  # 1 hour default

# Global state
current_command_id = None
shutdown_requested = False


def signal_handler(signum, frame):
    """Handle shutdown signals gracefully."""
    global shutdown_requested
    print(f"\nReceived signal {signum}, shutting down gracefully...")
    shutdown_requested = True


def get_utc_ymdhis():
    """Get current UTC timestamp in YYYYMMDDHHIISS format."""
    return int(datetime.utcnow().strftime('%Y%m%d%H%M%S'))


def load_config():
    """Load database configuration from lupopedia-config.php."""
    config_paths = [
        os.path.join(os.path.dirname(os.path.dirname(__file__)), 'lupopedia-config.php'),
        os.path.join(os.path.dirname(os.path.dirname(os.path.dirname(__file__))), 'lupopedia-config.php'),
    ]
    
    for config_path in config_paths:
        if os.path.exists(config_path):
            with open(config_path, 'r') as f:
                content = f.read()
                
            # Extract database credentials
            import re
            db_host = re.search(r"define\s*\(\s*['\"]DB_HOST['\"]\s*,\s*['\"]([^'\"]+)['\"]", content)
            db_name = re.search(r"define\s*\(\s*['\"]DB_NAME['\"]\s*,\s*['\"]([^'\"]+)['\"]", content)
            db_user = re.search(r"define\s*\(\s*['\"]DB_USER['\"]\s*,\s*['\"]([^'\"]+)['\"]", content)
            db_pass = re.search(r"define\s*\(\s*['\"]DB_PASSWORD['\"]\s*,\s*['\"]([^'\"]+)['\"]", content)
            table_prefix = re.search(r"define\s*\(\s*['\"]LUPO_TABLE_PREFIX['\"]\s*,\s*['\"]([^'\"]+)['\"]", content)
            
            if all([db_host, db_name, db_user, db_pass]):
                return {
                    'host': db_host.group(1),
                    'database': db_name.group(1),
                    'user': db_user.group(1),
                    'password': db_pass.group(1),
                    'table_prefix': table_prefix.group(1) if table_prefix else 'lupo_'
                }
    
    print("ERROR: Could not find lupopedia-config.php")
    sys.exit(1)


def get_db_connection(config):
    """Create database connection."""
    return MySQLdb.connect(
        host=config['host'],
        user=config['user'],
        passwd=config['password'],
        db=config['database'],
        charset='utf8mb4'
    )


def claim_next_job(conn, config, actor_id=1000, hostname=None):
    """Claim the next queued job using doctrine-compliant claim protocol."""
    if hostname is None:
        import socket
        hostname = socket.gethostname()
    
    table = config['table_prefix'] + 'system_commands'
    cursor = conn.cursor(MySQLdb.cursors.DictCursor)
    
    # Find next queued job
    cursor.execute(f"""
        SELECT command_id, command_type, command_args_json, working_dir, 
               timeout_seconds, attempt_count, max_attempts
        FROM `{table}`
        WHERE status = 'queued' 
          AND is_deleted = 0
          AND scheduled_ymdhis <= %s
        ORDER BY priority DESC, scheduled_ymdhis ASC
        LIMIT 1
    """, (get_utc_ymdhis(),))
    
    job = cursor.fetchone()
    if not job:
        return None
    
    command_id = job['command_id']
    now = get_utc_ymdhis()
    
    # Attempt to claim (doctrine: only proceed if affected_rows = 1)
    cursor.execute(f"""
        UPDATE `{table}`
        SET status = 'running',
            claimed_by_actor_id = %s,
            claimed_by_host = %s,
            process_id = %s,
            started_ymdhis = %s,
            last_heartbeat_ymdhis = %s,
            attempt_count = attempt_count + 1
        WHERE command_id = %s
          AND status = 'queued'
          AND is_deleted = 0
    """, (actor_id, hostname, str(os.getpid()), now, now, command_id))
    
    conn.commit()
    
    if cursor.rowcount == 1:
        return job
    else:
        # Another runner claimed it
        return None


def update_heartbeat(conn, config, command_id):
    """Update job heartbeat."""
    table = config['table_prefix'] + 'system_commands'
    cursor = conn.cursor()
    cursor.execute(f"""
        UPDATE `{table}`
        SET last_heartbeat_ymdhis = %s
        WHERE command_id = %s
    """, (get_utc_ymdhis(), command_id))
    conn.commit()


def complete_job(conn, config, command_id, return_code, output):
    """Mark job as completed."""
    table = config['table_prefix'] + 'system_commands'
    cursor = conn.cursor()
    
    output_sha1 = hashlib.sha1(output.encode('utf-8')).hexdigest() if output else None
    now = get_utc_ymdhis()
    
    cursor.execute(f"""
        UPDATE `{table}`
        SET status = %s,
            finished_ymdhis = %s,
            return_code = %s,
            output_text = %s,
            output_sha1 = %s
        WHERE command_id = %s
    """, ('completed' if return_code == 0 else 'failed', now, return_code, output, output_sha1, command_id))
    conn.commit()


def execute_command(job, config):
    """Execute a command job."""
    command_type = job['command_type']
    args_json = job['command_args_json']
    working_dir = job['working_dir'] or os.path.dirname(os.path.dirname(__file__))
    timeout = job['timeout_seconds'] or MAX_EXECUTION_TIME
    
    try:
        args = json.loads(args_json) if args_json else {}
    except json.JSONDecodeError:
        return 1, f"ERROR: Invalid JSON in command_args_json"
    
    print(f"Executing {command_type} with args: {args}")
    
    if command_type == 'python_import_channels_and_artifacts':
        script = args.get('script', 'lupo-scripts/import_channels_and_artifacts.py')
        script_path = os.path.join(working_dir, script)
        
        if not os.path.exists(script_path):
            return 1, f"ERROR: Script not found: {script_path}"
        
        cmd = [sys.executable, script_path]
        if args.get('mode'):
            cmd.extend(['--mode', args['mode']])
        if args.get('paths'):
            for path in args['paths']:
                cmd.extend(['--path', path])
        
        try:
            result = subprocess.run(
                cmd,
                cwd=working_dir,
                capture_output=True,
                text=True,
                timeout=timeout
            )
            output = result.stdout + result.stderr
            return result.returncode, output
        except subprocess.TimeoutExpired:
            return 1, f"ERROR: Command timed out after {timeout} seconds"
        except Exception as e:
            return 1, f"ERROR: {str(e)}"
    
    else:
        return 1, f"ERROR: Unknown command type: {command_type}"


def reap_stale_jobs(conn, config):
    """Reset stale jobs back to queued status."""
    table = config['table_prefix'] + 'system_commands'
    cursor = conn.cursor()
    
    stale_threshold = get_utc_ymdhis() - (STALE_JOB_TIMEOUT * 100)  # Convert seconds to YMDHIS offset
    
    cursor.execute(f"""
        UPDATE `{table}`
        SET status = 'queued',
            claimed_by_actor_id = NULL,
            claimed_by_host = NULL,
            process_id = NULL,
            started_ymdhis = NULL,
            last_heartbeat_ymdhis = NULL
        WHERE status = 'running'
          AND is_deleted = 0
          AND last_heartbeat_ymdhis < %s
          AND attempt_count < max_attempts
    """, (stale_threshold,))
    
    if cursor.rowcount > 0:
        print(f"Reaped {cursor.rowcount} stale job(s)")
    
    conn.commit()


def main():
    """Main runner loop."""
    global current_command_id, shutdown_requested
    
    # Register signal handlers
    signal.signal(signal.SIGINT, signal_handler)
    signal.signal(signal.SIGTERM, signal_handler)
    
    print("System Commands Queue Runner starting...")
    print(f"Heartbeat interval: {HEARTBEAT_INTERVAL}s")
    print(f"Stale job timeout: {STALE_JOB_TIMEOUT}s")
    print(f"Poll interval: {POLL_INTERVAL}s")
    
    config = load_config()
    print(f"Connected to database: {config['database']}")
    print(f"Table prefix: {config['table_prefix']}")
    
    last_heartbeat = 0
    last_reap = 0
    
    while not shutdown_requested:
        try:
            conn = get_db_connection(config)
            
            # Reap stale jobs every minute
            now = time.time()
            if now - last_reap > 60:
                reap_stale_jobs(conn, config)
                last_reap = now
            
            # Claim next job
            job = claim_next_job(conn, config)
            
            if job:
                current_command_id = job['command_id']
                print(f"\nClaimed job {current_command_id}: {job['command_type']}")
                
                # Execute with heartbeat updates
                last_heartbeat = time.time()
                return_code, output = execute_command(job, config)
                
                # Complete job
                complete_job(conn, config, current_command_id, return_code, output)
                print(f"Job {current_command_id} completed with return code {return_code}")
                
                if output:
                    print(f"Output:\n{output[:500]}")  # First 500 chars
                
                current_command_id = None
            else:
                # No jobs, sleep
                time.sleep(POLL_INTERVAL)
            
            conn.close()
            
        except KeyboardInterrupt:
            break
        except Exception as e:
            print(f"ERROR: {str(e)}")
            time.sleep(POLL_INTERVAL)
    
    print("\nShutdown complete.")


if __name__ == '__main__':
    main()
