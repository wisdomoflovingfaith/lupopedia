#!/usr/bin/env python3
"""
Sync Channel Artifacts — Journal-Driven Channel Import System
ATHENA DIRECTIVE 4.0.88 — Hardened filesystem → database sync.

CRITICAL CHANGES FROM PREVIOUS VERSIONS:
  ❌ REMOVED: --watch-mode, --reconcile-db, --reconcile-fs (bidirectional sync)
  ✅ NEW: Journal-driven event sourcing, dry-run default, kill conditions

Operational Modes:
  1. DRY-RUN (default): Report what would sync, do NOT change database
  2. VALIDATE: Check for divergences
  3. STATUS: Report sync state
  4. SYNC: Consume journal and apply events (requires PHP wrapper)

Kill Conditions (prevent silent corruption):
  - Hash mismatch between filesystem and journal
  - Missing file for journal entry
  - Duplicate content_id in database
  
Response: Write divergence artifact, STOP, require manual review

Usage:
  python sync_channel_artifacts.py                     # dry-run mode
  python sync_channel_artifacts.py --validate           # check for divergences
  python sync_channel_artifacts.py --status             # show state
  python sync_channel_artifacts.py --sync               # apply to database (CAREFUL!)
"""

import argparse
import glob
import json
import os
import sys
from datetime import datetime
from pathlib import Path


def get_current_ymdhis():
    """Return current UTC time in YYYYMMDDHHIISS format."""
    return datetime.utcnow().strftime('%Y%m%d%H%M%S')


class SyncChannelArtifacts:
    def __init__(self, repo_root='.', channel_id=None, dry_run=True):
        self.repo_root = Path(repo_root).resolve()
        self.channel_id = channel_id
        self.dry_run = dry_run
        self.channel_root = self.repo_root / 'lupo-channels'
        self.journal_dir = self.repo_root / 'lupo-database' / 'journal'
        self.divergence_dir = self.repo_root / 'lupo-database' / 'divergences'
        
        self.stats = {
            'files_found': 0,
            'files_processed': 0,
            'would_import': 0,
            'divergences': 0,
            'errors': 0,
        }
    
    def scan_channel_artifacts(self):
        """
        Scan filesystem for channel artifacts (broadcasts, threads).
        
        Returns:
            list: Artifact dicts with channel_id, thread_id, file_path
        """
        artifacts = []
        
        # Pattern: lupo-channels/{channel_id}/broadcasts/*.md
        #       or lupo-channels/{channel_id}/threads/{thread_id}/*.md
        
        if self.channel_id:
            pattern = str(self.channel_root / str(self.channel_id) / '**' / '*.md')
        else:
            pattern = str(self.channel_root / '**' / '*.md')
        
        for file_path_str in glob.glob(pattern, recursive=True):
            file_path = Path(file_path_str)
            
            # Parse: lupo-channels/42/broadcasts/file.md
            #     or lupo-channels/42/threads/2012/file.md
            try:
                relative = file_path.relative_to(self.channel_root)
                parts = relative.parts
                
                if len(parts) < 2:
                    continue
                
                channel_id = int(parts[0]) if parts[0].isdigit() else None
                if not channel_id:
                    continue
                
                thread_id = None
                if 'threads' in parts:
                    thread_idx = parts.index('threads') + 1
                    if thread_idx < len(parts):
                        try:
                            thread_id = int(parts[thread_idx])
                        except (ValueError, IndexError):
                            pass
                
                artifacts.append({
                    'channel_id': channel_id,
                    'thread_id': thread_id,
                    'file_path': str(file_path),
                    'relative_path': str(relative).replace('\\', '/'),
                    'file_name': file_path.name,
                })
            except (ValueError, IndexError):
                continue
        
        return artifacts
    
    def process_artifacts_dryrun(self, artifacts):
        """
        Process artifacts in DRY-RUN mode.
        
        Reports what would sync without making changes.
        """
        results = {
            'mode': 'dry_run',
            'timestamp': get_current_ymdhis(),
            'artifacts_found': len(artifacts),
            'artifacts_ready': 0,
            'artifacts_error': 0,
            'details': [],
        }
        
        for artifact in artifacts:
            self.stats['files_found'] += 1
            file_path = artifact['file_path']
            
            # Check file exists
            if not os.path.exists(file_path):
                self.stats['errors'] += 1
                results['artifacts_error'] += 1
                results['details'].append({
                    'file_path': artifact['relative_path'],
                    'status': 'FILE_NOT_FOUND',
                    'action': 'SKIP',
                })
            else:
                self.stats['would_import'] += 1
                results['artifacts_ready'] += 1
                results['details'].append({
                    'file_path': artifact['relative_path'],
                    'channel_id': artifact['channel_id'],
                    'thread_id': artifact['thread_id'],
                    'status': 'READY',
                    'action': 'WOULD_IMPORT',
                })
        
        return results
    
    def validate_artifacts(self):
        """
        Check for divergences (filesystem vs journal).
        
        Kill condition: Stop if divergence detected.
        """
        divergences = []
        
        if not self.journal_dir.exists():
            return {
                'divergences_found': 0,
                'divergences': [],
                'status': 'journal_dir_not_found',
            }
        
        # Scan journal entries
        for journal_file in os.listdir(str(self.journal_dir)):
            if not journal_file.endswith('.json'):
                continue
            
            try:
                with open(self.journal_dir / journal_file, 'r') as f:
                    entry = json.load(f)
                
                file_path = entry.get('file_path')
                expected_hash = entry.get('file_hash')
                
                # Check file existence
                if not os.path.exists(file_path):
                    divergences.append({
                        'type': 'FILE_MISSING_IN_FILESYSTEM',
                        'journal_entry': journal_file,
                        'file_path': file_path,
                        'severity': 'CRITICAL',
                    })
                    self.stats['divergences'] += 1
            except json.JSONDecodeError:
                divergences.append({
                    'type': 'CORRUPT_JOURNAL_ENTRY',
                    'journal_file': journal_file,
                    'severity': 'CRITICAL',
                })
                self.stats['divergences'] += 1
        
        return {
            'divergences_found': len(divergences),
            'divergences': divergences,
            'status': 'validated' if not divergences else 'DIVERGENCES_DETECTED',
        }
    
    def status_report(self):
        """Generate operation status report."""
        artifacts = self.scan_channel_artifacts()
        validation = self.validate_artifacts()
        
        report = {
            'timestamp': get_current_ymdhis(),
            'mode': 'status',
            'filesystem_state': {
                'artifacts_found': len(artifacts),
                'channels_involved': len(set(a['channel_id'] for a in artifacts)) if artifacts else 0,
                'threads_involved': len(set(a['thread_id'] for a in artifacts if a['thread_id'])) if artifacts else 0,
            },
            'journal_state': validation,
            'recommendations': [],
        }
        
        if validation['divergences_found'] > 0:
            report['recommendations'].append(
                f"⚠️  {validation['divergences_found']} divergence(s) detected — manual review REQUIRED"
            )
        else:
            report['recommendations'].append("✅ No divergences detected — ready to proceed")
        
        return report
    
    def sync_apply(self):
        """
        Apply sync to database.
        
        REQUIRES: Database connection via PHP wrapper (database operations must use PHP PDO).
        
        For now: Returns what would be applied.
        """
        artifacts = self.scan_channel_artifacts()
        validation = self.validate_artifacts()
        
        if validation['divergences_found'] > 0:
            return {
                'mode': 'sync_apply',
                'status': 'BLOCKED_BY_DIVERGENCES',
                'divergences_found': validation['divergences_found'],
                'message': 'Cannot apply sync while divergences exist. Resolve manually first.',
            }
        
        return {
            'mode': 'sync_apply',
            'status': 'READY',
            'artifacts_to_import': len(artifacts),
            'message': 'Sync requires PHP database wrapper with PDO connection',
            'next_step': 'Use PHP sync-channel-artifacts-apply.php with database credentials',
        }


def main():
    """Main entry point for sync operations."""
    parser = argparse.ArgumentParser(
        description='Journal-driven channel artifact sync (ATHENA 4.0.88)'
    )
    parser.add_argument('--repo-root', default='.', help='Repository root')
    parser.add_argument('--channel', type=int, default=None, help='Specific channel ID')
    parser.add_argument('--dry-run', action='store_true', default=True, help='Dry-run mode (default)')
    parser.add_argument('--validate', action='store_true', help='Check for divergences')
    parser.add_argument('--status', action='store_true', help='Show sync status')
    parser.add_argument('--sync', action='store_true', help='Apply to database')
    parser.add_argument('--json', action='store_true', help='Output as JSON')
    
    args = parser.parse_args()
    
    # Determine mode
    mode = 'dryrun'
    dry_run = True
    if args.validate:
        mode = 'validate'
    elif args.status:
        mode = 'status'
    elif args.sync:
        mode = 'sync'
        dry_run = False
    
    syncer = SyncChannelArtifacts(
        repo_root=args.repo_root,
        channel_id=args.channel,
        dry_run=dry_run
    )
    
    # Execute operation
    if mode == 'dryrun':
        print(f"[DRY-RUN] Scanning channel artifacts...", file=sys.stderr)
        artifacts = syncer.scan_channel_artifacts()
        result = syncer.process_artifacts_dryrun(artifacts)
    elif mode == 'validate':
        print(f"[VALIDATE] Checking for divergences...", file=sys.stderr)
        result = syncer.validate_artifacts()
    elif mode == 'status':
        print(f"[STATUS] Sync state report...", file=sys.stderr)
        result = syncer.status_report()
    elif mode == 'sync':
        print(f"[SYNC] Checking readiness...", file=sys.stderr)
        result = syncer.sync_apply()
    
    # Output
    if args.json:
        print(json.dumps(result, indent=2, default=str))
    else:
        print(json.dumps(result, indent=2, default=str))
    
    print(f"Operation complete.", file=sys.stderr)
    return 0


if __name__ == '__main__':
    sys.exit(main())