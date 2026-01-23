"""
Fake DB client for IDE agents.
All queries are redirected to the dialogs/ filesystem.
No real database access is permitted.
"""

import json
import os
from datetime import datetime
from typing import Any, Dict, List, Optional

class DialogFS_DB:
    """Fake database client that redirects to DialogFS filesystem"""
    
    def __init__(self):
        self.base_path = "dialogs"
        self.ensure_directories()
    
    def ensure_directories(self):
        """Ensure DialogFS directories exist"""
        dirs = ["threads", "messages", "agents", "sandbox", "logs"]
        for dir_name in dirs:
            os.makedirs(os.path.join(self.base_path, dir_name), exist_ok=True)
    
    def query(self, sql: str, params: List[Any] = None) -> Dict[str, Any]:
        """Fake query function that logs to DialogFS"""
        if params is None:
            params = []
        
        timestamp = datetime.now().isoformat().replace(":", "-")
        query_log = {
            "sql": sql,
            "params": params,
            "timestamp": timestamp,
            "warning": "REAL DATABASE ACCESS DISABLED",
            "redirected_to": "dialogs/filesystem"
        }
        
        # Log query attempt to dialogs/logs
        log_file = os.path.join(self.base_path, "logs", f"query_{timestamp}.json")
        with open(log_file, 'w') as f:
            json.dump(query_log, f, indent=2)
        
        print(f"[DialogFS] Query intercepted and logged to: {log_file}")
        
        return {
            "warning": "REAL DATABASE ACCESS DISABLED",
            "sql": sql,
            "params": params,
            "result": None,
            "redirected_to": "dialogs/filesystem",
            "suggestion": "Use DialogFS filesystem instead of real database"
        }
    
    def transaction(self, callback):
        """Fake transaction function"""
        print("[DialogFS] Transaction intercepted - using filesystem instead")
        return {
            "warning": "REAL DATABASE ACCESS DISABLED",
            "redirected_to": "dialogs/filesystem"
        }
    
    def connect(self):
        """Fake connection function"""
        raise Exception("REAL DATABASE ACCESS DISABLED FOR IDE AGENTS - Use DialogFS instead")
    
    def validate_schema(self, schema: Dict[str, Any]) -> Dict[str, Any]:
        """Schema validation in DialogFS"""
        schema_path = os.path.join(self.base_path, "schema.json")
        print(f"[DialogFS] Schema validation redirected to: {schema_path}")
        
        # Save schema to DialogFS
        with open(schema_path, 'w') as f:
            json.dump(schema, f, indent=2)
        
        return {
            "valid": True,
            "path": schema_path,
            "warning": "Using DialogFS instead of real database"
        }
    
    def create_table(self, table_name: str, columns: Dict[str, str]) -> Dict[str, Any]:
        """Create fake table in DialogFS"""
        table_path = os.path.join(self.base_path, "sandbox", f"{table_name}.json")
        table_def = {
            "table_name": table_name,
            "columns": columns,
            "data": [],
            "created_at": datetime.now().isoformat()
        }
        
        with open(table_path, 'w') as f:
            json.dump(table_def, f, indent=2)
        
        return {
            "table_created": table_name,
            "path": table_path,
            "warning": "Using DialogFS instead of real database"
        }

# Global instance for IDE agents
db = DialogFS_DB()

def db_connect():
    """Global function for IDE agents"""
    raise Exception("REAL DATABASE ACCESS DISABLED FOR IDE AGENTS - Use DialogFS instead")

# Export for IDE agents
__all__ = ['db', 'db_connect', 'DialogFS_DB']
