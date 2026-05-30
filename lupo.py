#!/usr/bin/env python3
"""
lupo.py — Unified Agent Terminal for Lupopedia

Usage:
    python lupo.py

Location: Project root (C:\ServBay\www\servbay\lupopedia\lupo.py)
"""

import json
import os
import subprocess
import sys
from datetime import datetime, timezone
from pathlib import Path
from typing import Dict, List, Optional

# Paths
PROJECT_ROOT = Path(__file__).parent
SESSION_PATH = PROJECT_ROOT / 'lupo-config' / 'session.json'
IDENTITY_PATH = PROJECT_ROOT / 'lupo-config' / 'terminal_identity.json'
ACTORS_PATH = PROJECT_ROOT / 'lupo-database' / 'lupopedia' / 'actors' / 'registry.json'

# Agent registry (fallback if registry.json not found)
AGENTS = {
    1: "Eric",
    102: "Cursor",
    103: "Antigravity",
    104: "Lilith",
    105: "Copilot",
    106: "Windsurf",
    107: "Gemini",
    108: "DeepSeek",
    116: "Claude Code"
}

def load_actors():
    """Load actors from registry.json"""
    if ACTORS_PATH.exists():
        with open(ACTORS_PATH, 'r') as f:
            return json.load(f)
    return AGENTS

def load_session():
    """Load current session config"""
    if not SESSION_PATH.exists():
        # Create default session.json if missing
        default_session = {
            "version": "4.0.97",
            "active_federation_node": 0,
            "active_channel_key": "development",
            "active_slug": "prd_files/44_prd_discussion",
            "timestamp": {
                "current": datetime.now(timezone.utc).strftime("%Y%m%d%H%M%S"),
                "session_started": datetime.now(timezone.utc).strftime("%Y%m%d%H%M%S")
            },
            "status": "active"
        }
        with open(SESSION_PATH, 'w') as f:
            json.dump(default_session, f, indent=2)
        return default_session
    
    with open(SESSION_PATH, 'r') as f:
        return json.load(f)

def load_identity():
    """Load saved terminal identity"""
    if IDENTITY_PATH.exists():
        with open(IDENTITY_PATH, 'r') as f:
            return json.load(f)
    return None

def save_identity(actor_id, actor_name):
    """Save terminal identity"""
    data = {
        "actor_id": actor_id,
        "actor_name": actor_name,
        "saved_at": datetime.now(timezone.utc).strftime("%Y%m%d%H%M%S")
    }
    with open(IDENTITY_PATH, 'w') as f:
        json.dump(data, f, indent=2)

def clear_screen():
    os.system('cls' if os.name == 'nt' else 'clear')

def print_header(actor_id, actor_name):
    """Print main menu header"""
    session = load_session()
    print("┌" + "─" * 61 + "┐")
    print(f"│  Lupopedia Terminal — {actor_name} (actor_id: {actor_id})" + " " * (40 - len(actor_name) - len(str(actor_id))) + "│")
    print(f"│  Active channel: {session['active_channel_key']}/{session['active_slug']}" + " " * (20) + "│")
    print(f"│  Timestamp: {session['timestamp']['current']}" + " " * 33 + "│")
    print("└" + "─" * 61 + "┘")
    print()

def select_identity():
    """First-time identity selection"""
    # Load session for version display (FIXED: session was undefined)
    session = load_session()
    
    clear_screen()
    print("┌" + "─" * 59 + "┐")
    print("│                    Lupopedia Agent Terminal                 │")
    print(f"│                          v{session.get('version', '4.0.97')}                            │")
    print("└" + "─" * 59 + "┐")
    print("\nSelect your identity:\n")
    
    actors = load_actors()
    items = list(actors.items())
    for i, (aid, name) in enumerate(items, 1):
        print(f"  {i}) {name} (actor_id: {aid})")
    
    print("\n  q) Quit")
    
    choice = input("\nEnter choice: ").strip()
    if choice == 'q':
        sys.exit(0)
    
    try:
        idx = int(choice) - 1
        if 0 <= idx < len(items):
            aid, name = items[idx]
            save_identity(aid, name)
            return aid, name
    except ValueError:
        pass
    
    print("Invalid choice. Try again.")
    return select_identity()

def send_message(actor_id, actor_name):
    """Send message to another actor"""
    clear_screen()
    print("┌" + "─" * 59 + "┐")
    print("│  Send Message                                              │")
    print("└" + "─" * 59 + "┐\n")
    
    actors = load_actors()
    items = [(aid, name) for aid, name in actors.items() if aid != actor_id]
    
    for i, (aid, name) in enumerate(items, 1):
        print(f"  {i}) {name} ({aid})")
    print("  9) Custom actor ID")
    print("  b) Back")
    
    choice = input("\nEnter choice: ").strip()
    if choice.lower() == 'b':
        return
    
    if choice == '9':
        to_id = int(input("Enter actor ID: "))
        to_name = actors.get(to_id, f"Actor {to_id}")
    else:
        try:
            idx = int(choice) - 1
            if 0 <= idx < len(items):
                to_id, to_name = items[idx]
            else:
                print("Invalid choice")
                return
        except ValueError:
            print("Invalid choice")
            return
    
    task = input("Task ID (e.g., PRD-44, TRUST-LADDER): ").strip()
    message = input("Message: ").strip()
    
    if not message:
        print("Message cannot be empty")
        return
    
    # Execute pending.py
    cmd = [
        'python', 'lupo-bin/pending.py',
        '--from', str(actor_id),
        '--to', str(to_id),
        '--task', task,
        '--message', message
    ]
    
    result = subprocess.run(cmd, capture_output=True, text=True)
    print("\n" + "─" * 59)
    print(result.stdout)
    if result.stderr:
        print(result.stderr)
    print("─" * 59)
    input("\nPress Enter to continue...")

def check_tasks(actor_id, actor_name):
    """Check pending tasks for current actor"""
    clear_screen()
    print("┌" + "─" * 59 + "┐")
    print(f"│  Pending Tasks for {actor_name} (actor_id: {actor_id})      │")
    print("└" + "─" * 59 + "┐\n")
    
    cmd = ['python', 'lupo-bin/pending.py', '--actor', str(actor_id), '--check']
    result = subprocess.run(cmd, capture_output=True, text=True)
    print(result.stdout)
    if result.stderr:
        print(result.stderr)
    
    input("\nPress Enter to continue...")

def show_transcript():
    """Show last N transcript entries"""
    clear_screen()
    print("┌" + "─" * 59 + "┐")
    print("│  Transcript (last 10)                                      │")
    session = load_session()
    print(f"│  Channel: {session['active_channel_key']}/{session['active_slug']}" + " " * (20) + "│")
    print("└" + "─" * 59 + "┐\n")
    
    transcript_path = PROJECT_ROOT / f"lupo-channels/0/{session['active_channel_key']}/{session['active_slug']}/transcript.jsonl"
    
    if transcript_path.exists():
        with open(transcript_path, 'r') as f:
            lines = f.readlines()
            actors = load_actors()
            for line in lines[-10:]:
                try:
                    entry = json.loads(line)
                    actor_name = actors.get(entry.get('actor_id', '?'), entry.get('actor_id', '?'))
                    print(f"  {entry.get('ts', '?')} • {actor_name} ({entry.get('actor_id', '?')}) "
                          f"{entry.get('task', '')} {entry.get('action', '')}")
                except:
                    print(f"  {line.strip()}")
    else:
        print("  No transcript found for active channel")
    
    input("\nPress Enter to continue...")

def change_channel():
    """Change active channel/slug"""
    clear_screen()
    print("┌" + "─" * 59 + "┐")
    print("│  Change Channel                                            │")
    print("└" + "─" * 59 + "┐\n")
    
    node = input("Federation node (default: 0): ").strip() or "0"
    channel_key = input("Channel key (development/staging): ").strip()
    slug = input("Slug: ").strip()
    
    if not channel_key or not slug:
        print("Channel key and slug are required")
        input("\nPress Enter to continue...")
        return
    
    # FIXED: Use --channel_key and --slug instead of --channel and --prd
    cmd = ['python', 'lupo-bin/tick.py', '--channel_key', channel_key, '--slug', slug]
    result = subprocess.run(cmd, capture_output=True, text=True)
    print("\n" + "─" * 59)
    print(result.stdout)
    if result.stderr:
        print(result.stderr)
    print("─" * 59)
    
    print(f"\n✅ Active channel set to: {node}/{channel_key}/{slug}")
    input("\nPress Enter to continue...")

def tick():
    """Update timestamp"""
    clear_screen()
    print("┌" + "─" * 59 + "┐")
    print("│  Update Timestamp (tick)                                   │")
    print("└" + "─" * 59 + "┐\n")
    
    cmd = ['python', 'lupo-bin/tick.py']
    result = subprocess.run(cmd, capture_output=True, text=True)
    print(result.stdout)
    if result.stderr:
        print(result.stderr)
    
    print("\n✅ Timestamp updated")
    input("\nPress Enter to continue...")

def archive_session():
    """Archive current session"""
    clear_screen()
    print("┌" + "─" * 59 + "┐")
    print("│  Archive Session                                           │")
    print("└" + "─" * 59 + "┐\n")
    
    print("⚠️ This will archive the current session and start a new one.")
    confirm = input("Are you sure? (y/N): ").strip().lower()
    if confirm == 'y':
        cmd = ['python', 'lupo-scripts/archive_session.py']
        result = subprocess.run(cmd, capture_output=True, text=True)
        print(result.stdout)
        if result.stderr:
            print(result.stderr)
        print("\n✅ Session archived")
    else:
        print("\nArchive cancelled")
    
    input("\nPress Enter to continue...")

def show_status(actor_id, actor_name):
    """Show system status"""
    clear_screen()
    session = load_session()
    
    print("┌" + "─" * 59 + "┐")
    print("│  System Status                                             │")
    print("└" + "─" * 59 + "┐\n")
    
    print("Identity:")
    print(f"  Actor: {actor_name} (actor_id: {actor_id})")
    print()
    
    print("Session:")
    print(f"  Version: {session.get('version', 'unknown')}")
    print(f"  Active channel: {session.get('active_channel_key', '?')}/{session.get('active_slug', '?')}")
    print(f"  Timestamp: {session.get('timestamp', {}).get('current', '?')}")
    print(f"  Session started: {session.get('timestamp', {}).get('session_started', '?')}")
    print(f"  Status: {session.get('status', 'active')}")
    print()
    
    # Count tasks
    tasks_path = PROJECT_ROOT / f"lupo-channels/0/{session.get('active_channel_key', 'development')}/{session.get('active_slug', '')}/tasks"
    task_count = len(list(tasks_path.glob(f"{actor_id}_*.json"))) if tasks_path.exists() else 0
    
    print("Queues:")
    print(f"  Pending tasks for me: {task_count}")
    print()
    
    input("Press Enter to continue...")

def main():
    clear_screen()
    
    # Load or select identity
    identity = load_identity()
    if identity:
        actor_id = identity['actor_id']
        actor_name = identity['actor_name']
    else:
        actor_id, actor_name = select_identity()
        clear_screen()
    
    # Main loop
    while True:
        clear_screen()
        print_header(actor_id, actor_name)
        
        print("Commands:")
        print("  [1] Send message to actor")
        print("  [2] Check my pending tasks")
        print("  [3] Check transcript (last 10)")
        print("  [4] Change channel/slug")
        print("  [5] Update timestamp (tick)")
        print("  [6] Archive session")
        print("  [7] Show status")
        print("  [8] Exit")
        print("  [q] Quit")
        
        choice = input("\nEnter command (1-8): ").strip().lower()
        
        if choice == '1':
            send_message(actor_id, actor_name)
        elif choice == '2':
            check_tasks(actor_id, actor_name)
        elif choice == '3':
            show_transcript()
        elif choice == '4':
            change_channel()
        elif choice == '5':
            tick()
        elif choice == '6':
            archive_session()
        elif choice == '7':
            show_status(actor_id, actor_name)
        elif choice in ['8', 'q']:
            print("\nGoodbye!")
            break
        else:
            print("\nInvalid choice")
            input("Press Enter to continue...")

if __name__ == "__main__":
    main()