#!/usr/bin/env python3
"""
Database connection utilities for Lupopedia Python tools.
Reads credentials from lupopedia-config.php via PHP bridge,
falling back to environment variables or db_config.py.

Config file search order (mirrors PHP installer):
1. One directory ABOVE DOCUMENT_ROOT (most secure)
2. One directory above DOCUMENT_ROOT + LUPOPEDIA_PUBLIC_PATH
3. Inside the Lupopedia directory itself (fallback)
"""

import os
import sys
import json
import subprocess
from typing import Dict, Any, Optional, List

_SCRIPTS_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
if _SCRIPTS_DIR not in sys.path:
    sys.path.insert(0, _SCRIPTS_DIR)


def find_repo_root():
    """Find Lupopedia repository root."""
    d = os.path.dirname(_SCRIPTS_DIR)
    markers = ("lupo-scripts", "lupo-includes")
    for _ in range(10):
        if all(os.path.isdir(os.path.join(d, m)) for m in markers):
            return d
        parent = os.path.dirname(d)
        if parent == d:
            break
        d = parent
    return _SCRIPTS_DIR


def get_lupopedia_paths() -> Dict[str, str]:
    """
    Determine Lupopedia installation paths.
    Returns dict with 'doc_root', 'lupopedia_path', 'public_path'
    """
    repo_root = find_repo_root()
    
    # Default: assume we're running from repo
    lupopedia_path = repo_root
    
    # Try to detect from environment
    if os.environ.get('LUPOPEDIA_PATH'):
        lupopedia_path = os.environ['LUPOPEDIA_PATH']
    
    # Try to detect DOCUMENT_ROOT
    doc_root = os.environ.get('DOCUMENT_ROOT', '/var/www/html')
    
    # LUPOPEDIA_PUBLIC_PATH (default to /lupopedia/)
    public_path = os.environ.get('LUPOPEDIA_PUBLIC_PATH', '/lupopedia/')
    if public_path.startswith('/'):
        public_path = public_path[1:]
    if not public_path.endswith('/'):
        public_path += '/'
    
    return {
        'doc_root': doc_root,
        'lupopedia_path': lupopedia_path,
        'public_path': public_path
    }


def find_config_file() -> Optional[str]:
    """
    Find lupopedia-config.php in the 3 canonical locations.
    Mirrors the PHP installer's search order.
    """
    paths = get_lupopedia_paths()
    doc_root = paths['doc_root']
    lupopedia_path = paths['lupopedia_path']
    public_path = paths['public_path']
    
    # Path 1: One directory ABOVE DOCUMENT_ROOT (most secure, preferred)
    candidate = os.path.join(os.path.dirname(doc_root), 'lupopedia-config.php')
    if os.path.exists(candidate):
        return candidate
    
    # Path 2: One directory above DOCUMENT_ROOT + Lupopedia public path
    candidate = os.path.join(os.path.dirname(doc_root), public_path.rstrip('/'), 'lupopedia-config.php')
    if os.path.exists(candidate):
        return candidate
    
    # Path 3: Inside the Lupopedia directory itself (fallback)
    candidate = os.path.join(lupopedia_path, 'lupopedia-config.php')
    if os.path.exists(candidate):
        return candidate
    
    # Additional fallbacks for development
    dev_paths = [
        '/etc/lupopedia/config.php',
        '/opt/lupopedia/config.php',
        os.path.expanduser('~/lupopedia/config.php'),
        os.path.join(lupopedia_path, 'config', 'lupopedia-config.php'),
        os.path.join(os.path.dirname(lupopedia_path), 'lupopedia-config.php'),
    ]
    for path in dev_paths:
        if os.path.exists(path):
            return path
    
    return None


def get_credentials_via_php_bridge() -> Optional[Dict[str, Any]]:
    """
    Call PHP helper to read lupopedia-config.php and return JSON credentials.
    This is the primary method, aligned with PHP installer.
    """
    repo_root = find_repo_root()
    config_file = find_config_file()
    
    # Build PHP script with config file search
    php_script = f'''<?php
$config_files = [
    {repr(config_file) if config_file else 'null'},
    __DIR__ . '/lupopedia-config.php',
    dirname($_SERVER['DOCUMENT_ROOT'] ?? '/var/www/html') . '/lupopedia-config.php',
    dirname($_SERVER['DOCUMENT_ROOT'] ?? '/var/www/html') . LUPOPEDIA_PUBLIC_PATH . '/lupopedia-config.php',
    LUPOPEDIA_PATH . '/lupopedia-config.php',
    '/etc/lupopedia/config.php',
    '/opt/lupopedia/config.php',
    getenv('HOME') . '/lupopedia/config.php',
];

$loaded = false;
foreach ($config_files as $file) {{
    if ($file && file_exists($file)) {{
        require_once $file;
        $loaded = true;
        break;
    }}
}}

if (!$loaded) {{
    echo json_encode(['error' => 'No config file found']);
    exit(1);
}}

$result = [
    'host' => defined('DB_HOST') ? DB_HOST : 'localhost',
    'user' => defined('DB_USER') ? DB_USER : 'root',
    'password' => defined('DB_PASSWORD') ? DB_PASSWORD : '',
    'database' => defined('DB_NAME') ? DB_NAME : 'lupopedia',
    'port' => defined('DB_PORT') ? (int)DB_PORT : 3306,
];
echo json_encode($result);
'''
    
    try:
        result = subprocess.run(
            ['php', '-r', php_script],
            capture_output=True,
            text=True,
            timeout=10,
            cwd=repo_root,
            env={**os.environ, 'LUPOPEDIA_PATH': repo_root}
        )
        
        if result.returncode != 0:
            print(f"[WARN] PHP bridge failed: {result.stderr}", file=sys.stderr)
            return None
        
        data = json.loads(result.stdout)
        if 'error' in data:
            print(f"[WARN] PHP bridge error: {data['error']}", file=sys.stderr)
            return None
        
        return data
        
    except subprocess.TimeoutExpired:
        print("[WARN] PHP bridge timeout", file=sys.stderr)
        return None
    except json.JSONDecodeError as e:
        print(f"[WARN] PHP bridge JSON decode error: {e}", file=sys.stderr)
        return None
    except FileNotFoundError:
        print("[WARN] PHP not found in PATH", file=sys.stderr)
        return None
    except Exception as e:
        print(f"[WARN] PHP bridge exception: {e}", file=sys.stderr)
        return None


def get_credentials_from_env() -> Optional[Dict[str, Any]]:
    """Get credentials from environment variables (for CI/container)."""
    if not os.environ.get('LUPO_DB_HOST'):
        return None
    
    return {
        'host': os.environ['LUPO_DB_HOST'],
        'user': os.environ.get('LUPO_DB_USER', 'root'),
        'password': os.environ.get('LUPO_DB_PASSWORD', ''),
        'database': os.environ.get('LUPO_DB_NAME', 'lupopedia'),
        'port': int(os.environ.get('LUPO_DB_PORT', '3306'))
    }


def get_credentials_from_py_config() -> Optional[Dict[str, Any]]:
    """Fallback to legacy db_config.py (if exists)."""
    try:
        sys.path.insert(0, _SCRIPTS_DIR)
        from db_config import get_connection_params
        return get_connection_params()
    except ImportError:
        pass
    
    config_path = os.path.join(_SCRIPTS_DIR, 'db_config.py')
    if os.path.exists(config_path):
        try:
            with open(config_path, 'r') as f:
                content = f.read()
            import re
            host = re.search(r"host\s*=\s*['\"]([^'\"]+)['\"]", content)
            user = re.search(r"user\s*=\s*['\"]([^'\"]+)['\"]", content)
            password = re.search(r"password\s*=\s*['\"]([^'\"]+)['\"]", content)
            database = re.search(r"database\s*=\s*['\"]([^'\"]+)['\"]", content)
            port = re.search(r"port\s*=\s*(\d+)", content)
            
            return {
                'host': host.group(1) if host else 'localhost',
                'user': user.group(1) if user else 'root',
                'password': password.group(1) if password else '',
                'database': database.group(1) if database else 'lupopedia',
                'port': int(port.group(1)) if port else 3306
            }
        except Exception:
            pass
    
    return None


def get_connection_params() -> Dict[str, Any]:
    """
    Get database connection parameters.
    
    Priority order:
    1. PHP bridge (reads lupopedia-config.php) - PRIMARY (mirrors PHP installer)
    2. Environment variables (LUPO_DB_*) - for CI/container
    3. Legacy db_config.py - backward compatibility
    
    Returns:
        Dict with keys: host, user, password, database, port
    """
    # Primary: PHP bridge (aligned with PHP installer)
    params = get_credentials_via_php_bridge()
    if params:
        return params
    
    # Secondary: Environment variables
    params = get_credentials_from_env()
    if params:
        return params
    
    # Tertiary: Legacy db_config.py
    params = get_credentials_from_py_config()
    if params:
        return params
    
    # Default fallback (development only)
    print("[WARN] No database credentials found. Using defaults.", file=sys.stderr)
    return {
        'host': 'localhost',
        'user': 'root',
        'password': '',
        'database': 'lupopedia',
        'port': 3306
    }


def get_connection():
    """Return a pymysql connection using credentials from get_connection_params()."""
    import pymysql
    params = get_connection_params()
    return pymysql.connect(**params)


def get_db_connection():
    """Alias for get_connection() for compatibility."""
    return get_connection()


if __name__ == "__main__":
    # Test mode
    params = get_connection_params()
    config_file = find_config_file()
    print(f"Config file found: {config_file if config_file else 'NOT FOUND'}")
    print(f"Host: {params['host']}")
    print(f"User: {params['user']}")
    print(f"Database: {params['database']}")
    print(f"Port: {params['port']}")
    print(f"Password: {'*' * len(params['password'])}")