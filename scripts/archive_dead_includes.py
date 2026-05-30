import os
import shutil
from pathlib import Path

def main():
    root = Path('c:/ServBay/www/servbay/lupopedia')
    includes_dir = root / 'includes'
    archive_dir = root / 'archive' / 'includes-archive'
    
    archive_dir.mkdir(parents=True, exist_ok=True)
    
    dead_dirs = [
        'Quantum', 'src', 'Dialog', 'DialogChannelMigration', 
        'EmotionalGeometry', 'HistoryReconciliation', 'KIP', 
        'MigrationOrchestrator', 'Pack', 'agents', 'exceptions', 
        'models', 'system'
    ]
    
    moved = 0
    for d in dead_dirs:
        source = includes_dir / d
        target = archive_dir / d
        if source.exists():
            print(f"Moving {d} to archive...")
            shutil.move(str(source), str(target))
            moved += 1
        else:
            print(f"Skipping {d} (not found)")
            
    print(f"Done! Moved {moved} directories.")

if __name__ == '__main__':
    main()
