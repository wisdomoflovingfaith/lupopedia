#!/usr/bin/env python3
"""
Create Implementation Question Script

Helps agents create properly formatted implementation questions with deterministic IDs.
Constitutionally compliant - no database dependencies, pure file system operations.

Usage:
    python create_implementation_question.py --implementation 25_departments_system --level critical --title "authentication_approach"
"""

import argparse
import os
import sys
from datetime import datetime, timezone

def generate_question_id():
    """Generate deterministic question ID using timestamp + sequence."""
    # In production, this would use IdGenerator.php
    # For now, using timestamp with microseconds for uniqueness
    dt = datetime.now(timezone.utc)
    return int(dt.strftime("%Y%m%d%H%M%S%f"))

def get_template_path():
    """Get path to question templates."""
    script_dir = os.path.dirname(os.path.abspath(__file__))
    return os.path.join(script_dir, "..", "docs", "implementations", "_template")

def load_template(level):
    """Load question template for specified level."""
    template_path = get_template_path()
    template_file = os.path.join(template_path, "questions", level, "YYYYMMDD_HHIISS_QUESTION_title.md")
    
    if not os.path.exists(template_file):
        print(f"ERROR: Template not found: {template_file}")
        sys.exit(1)
    
    with open(template_file, 'r') as f:
        return f.read()

def create_question_file(implementation_id, level, title, agent_id=102, agent_name="cursor"):
    """Create a new question file with proper headers."""
    
    # Generate IDs and timestamps
    question_id = generate_question_id()
    dt = datetime.now(timezone.utc)
    timestamp = dt.strftime("%Y%m%d_%H%M%S")
    
    # Sanitize title for filename
    filename_title = title.lower().replace(" ", "_").replace("-", "_").replace("__", "_")
    filename = f"{timestamp}_QUESTION_{filename_title}.md"
    
    # Determine paths
    base_path = os.path.join("..", "docs", "implementations", f"{implementation_id}")
    questions_dir = os.path.join(base_path, "questions", level)
    question_file = os.path.join(questions_dir, filename)
    
    # Create directories if they don't exist
    os.makedirs(questions_dir, exist_ok=True)
    
    # Load template
    template = load_template(level)
    
    # Replace placeholders
    content = template.replace("{implementation_id}", implementation_id)
    content = content.replace("{parent_prd}", implementation_id.split("_", 1)[1])  # Extract PRD name
    content = content.replace("{topic}", title.lower().replace(" ", "_"))
    content = content.replace("{Question Title}", title.replace("_", " ").title())
    content = content.replace("{question_id}", str(question_id))
    content = content.replace("{YYYYMMDD_HHIISS}", timestamp)
    content = content.replace("{actor_id}", str(agent_id))
    content = content.replace("{actor_name}", agent_name)
    
    # Write question file
    with open(question_file, 'w') as f:
        f.write(content)
    
    # Update THREAD_INDEX.md
    update_thread_index(questions_dir, level, question_id, title, agent_name, dt)
    
    print(f"✅ Created {level} question:")
    print(f"   File: {question_file}")
    print(f"   Question ID: {question_id}")
    print(f"   Level: {level}")
    
    return question_file, question_id

def update_thread_index(questions_dir, level, question_id, title, author, date):
    """Update the THREAD_INDEX.md file with new question."""
    
    index_file = os.path.join(questions_dir, "..", "THREAD_INDEX.md")
    
    # Read existing index
    if os.path.exists(index_file):
        with open(index_file, 'r') as f:
            content = f.read()
    else:
        # Create basic index if it doesn't exist
        content = """# Implementation Questions - Thread Index

## Critical Questions (HALT Implementation)

| Question ID | Title | Status | Created By | Created Date | Answer |
|-------------|-------|--------|------------|--------------|--------|
| *None yet* | | | | | |

"""
    
    # Find the appropriate section and add the question
    section_map = {
        "critical": "## Critical Questions (HALT Implementation)",
        "optimization": "## Optimization Questions (Document and Continue)",
        "clarification": "## Clarification Questions (Document Assumption)"
    }
    
    section_header = section_map.get(level, section_header)
    
    # Find the table under the section
    section_start = content.find(section_header)
    if section_start == -1:
        print(f"WARNING: Could not find section '{section_header}' in index")
        return
    
    # Find the table
    table_start = content.find("| Question ID", section_start)
    if table_start == -1:
        print("WARNING: Could not find table in index")
        return
    
    # Find the end of the table (next section or end of file)
    table_end = content.find("\n##", table_start)
    if table_end == -1:
        table_end = len(content)
    
    # Remove "*None yet*" row if this is the first question
    if "*None yet*" in content[table_start:table_end]:
        content = content.replace("*None yet* | | | | |", f"{question_id} | {title} | open | {author} | {date.strftime('%Y-%m-%d')} | ")
    else:
        # Add new row before the table ends
        table_row = f"\n{question_id} | {title} | open | {author} | {date.strftime('%Y-%m-%d')} | "
        insert_pos = content.rfind("|", table_start, table_end)
        if insert_pos != -1:
            content = content[:insert_pos] + table_row + content[insert_pos:]
    
    # Write updated index
    with open(index_file, 'w') as f:
        f.write(content)

def main():
    parser = argparse.ArgumentParser(description="Create implementation question")
    parser.add_argument("--implementation", required=True, help="Implementation ID (e.g., 25_departments_system)")
    parser.add_argument("--level", required=True, choices=["critical", "optimization", "clarification"], 
                       help="Question level")
    parser.add_argument("--title", required=True, help="Question title (use quotes for multi-word)")
    parser.add_argument("--agent-id", type=int, default=102, help="Agent ID creating the question")
    parser.add_argument("--agent-name", default="cursor", help="Agent name creating the question")
    
    args = parser.parse_args()
    
    # Validate implementation exists
    impl_path = os.path.join("..", "docs", "implementations", args.implementation)
    if not os.path.exists(impl_path):
        print(f"ERROR: Implementation directory not found: {impl_path}")
        sys.exit(1)
    
    # Create the question
    try:
        question_file, question_id = create_question_file(
            args.implementation, 
            args.level, 
            args.title,
            args.agent_id,
            args.agent_name
        )
        
        print(f"\n📝 Next steps:")
        print(f"1. Edit the question file: {question_file}")
        print(f"2. Fill in the question details")
        print(f"3. For critical questions: Notify via channel thread")
        print(f"4. For optimization/clarification: Continue implementation")
        
    except Exception as e:
        print(f"ERROR: Failed to create question: {e}")
        sys.exit(1)

if __name__ == "__main__":
    main()
