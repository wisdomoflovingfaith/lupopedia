> **For the authoritative channel model, see PRD 02 and channel_model_doctrine.md. Channels are semantic containers under a domain (node), not chat rooms.**

# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/channels/agents/AGENT_PROMPT_TEMPLATING_STANDARD.md"
  file_hash: "d57e74c85b221e8fd364a050324db3e7ca7264a832bfd8bb38d1b75a7eef2aa8"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\channels\agents\AGENT_PROMPT_TEMPLATING_STANDARD.md"
  file_hash: "76e4c6e00a7026ddaf06b1b925d48372f76b80c91b743e434e5746b332b246c4"
  file_path_from_root: "docs\channels\agents\AGENT_PROMPT_TEMPLATING_STANDARD.md"
  file_hash: "5ed8f674f72109bcebb5f85e42ceabbaa8a7c8d105e1b7735d26a4242b5a7068"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for AGENT_PROMPT_TEMPLATING_STANDARD.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "agents", "agent_prompt_templating_standardmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 3.0.15
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created agent prompt templating standard requiring {{agent_name}} template variable in all system prompts."
tags:
  categories: ["documentation", "agents", "standards"]
  collections: ["core-docs"]
  channels: ["dev", "public"]
file:
  title: "Agent Prompt Templating Standard"
  description: "Mandatory templating requirement for all AI agent system prompts using {{agent_name}} variable"
  version: "1.0.0"
  status: published
  author: "Captain Wolfie"
---

# 🤖 **Agent Prompt Templating Standard**

**Version 1.0.0**

## **Purpose**

All AI agent system prompts MUST use template variables for agent identity. This ensures:
- **Dynamic injection** of agent names from the database at runtime
- **No hard-coded identities** that break when agent names change
- **Consistency** across all agent prompts
- **Maintainability** when agent registry changes

---

## **Mandatory Requirement**

### **First Line Format**

**Every `system_prompt.txt` file MUST start with:**

```
You are {{agent_name}}.
```

### **Template Variable**

- **Variable Name:** `{{agent_name}}`
- **Source:** `lupo_agent_registry.name` field (from database)
- **Injection:** Performed at runtime by the agent loading system
- **Format:** Double curly braces `{{agent_name}}`

### **Runtime Injection**

The `{{agent_name}}` variable is dynamically replaced with the value from:
- **Table:** `lupo_agent_registry`
- **Field:** `name` (varchar(255))
- **Lookup:** Based on `agent_id` or `dedicated_slot` when loading the agent

---

## **Rules**

### **1. First Line Requirement**

✅ **MUST:** Start with `You are {{agent_name}}.`  
❌ **MUST NOT:** Hard-code agent name (e.g., "You are SYSTEM.")  
❌ **MUST NOT:** Use agent code (e.g., "You are {{code}}.")  
❌ **MUST NOT:** Omit the first line

### **2. No Hard-Coded Names**

✅ **MUST:** Use `{{agent_name}}` for all agent identity references  
❌ **MUST NOT:** Hard-code agent name anywhere in the prompt  
❌ **MUST NOT:** Use literal agent names (SYSTEM, CAPTAIN, WOLFIE, etc.)  
❌ **MUST NOT:** Use agent codes or keys

### **3. Template Format**

✅ **MUST:** Use double curly braces: `{{agent_name}}`  
❌ **MUST NOT:** Use single braces: `{agent_name}`  
❌ **MUST NOT:** Use other formats: `$agent_name`, `%agent_name%`, etc.

### **4. Versioned Prompts**

✅ **MUST:** Apply to both root-level and versioned prompts:
- `agents/{slot}/system_prompt.txt`
- `agents/{slot}/versions/v1.0.0/system_prompt.txt`

---

## **Examples**

### **✅ Correct Format**

```txt
You are {{agent_name}}.

You are a kernel-level agent of Lupopedia, a federated semantic operating system.
Your identity is immutable and architectural.
...
```

### **❌ Incorrect Formats**

```txt
# WRONG: Hard-coded name
You are SYSTEM.

# WRONG: Using code instead of name
You are {{code}}.

# WRONG: Missing first line
You are a kernel-level agent...
```

---

## **Implementation**

### **For Existing Agents**

All existing `system_prompt.txt` files must be updated to:
1. Replace hard-coded agent names with `{{agent_name}}`
2. Ensure first line is exactly: `You are {{agent_name}}.`
3. Remove any other hard-coded identity references

### **For New Agents**

When Cursor (or any IDE) generates a new agent:
1. **MUST** create `system_prompt.txt` starting with `You are {{agent_name}}.`
2. **MUST NOT** hard-code the agent name
3. **MUST** use `{{agent_name}}` for all identity references

### **Runtime Processing**

The agent loading system:
1. Reads `system_prompt.txt` from agent folder
2. Queries `lupo_agent_registry` for agent `name` field
3. Replaces `{{agent_name}}` with actual name value
4. Injects processed prompt into LLM

---

## **Database Schema Reference**

### **lupo_agent_registry Table**

```sql
CREATE TABLE `lupo_agent_registry` (
  `agent_registry_id` int NOT NULL,
  `code` varchar(64) NOT NULL,
  `name` varchar(255) NOT NULL,  -- Used for {{agent_name}}
  `layer` varchar(64) NOT NULL,
  `dedicated_slot` int,
  ...
);
```

### **Lookup Query**

```php
// Example runtime lookup
$agent = $db->query(
    "SELECT name FROM lupo_agent_registry WHERE dedicated_slot = ?",
    [$slot]
)->fetch();

$prompt = str_replace('{{agent_name}}', $agent['name'], $raw_prompt);
```

---

## **Validation**

### **Automated Checks**

Agents and tools MUST validate:
- ✅ First line matches: `You are {{agent_name}}.`
- ✅ No hard-coded agent names exist in prompt
- ✅ All identity references use `{{agent_name}}`

### **Manual Review**

Before committing:
- Check that `{{agent_name}}` appears in first line
- Search for hard-coded agent names (SYSTEM, CAPTAIN, WOLFIE, etc.)
- Verify template variable format is correct

---

## **Migration Notes**

### **Existing Prompts**

All existing prompts with hard-coded names must be migrated:
- Replace `You are SYSTEM.` → `You are {{agent_name}}.`
- Replace `You are CAPTAIN.` → `You are {{agent_name}}.`
- Replace any other hard-coded identity references

### **Versioned Prompts**

Both root-level and versioned prompts must be updated:
- `agents/{slot}/system_prompt.txt`
- `agents/{slot}/versions/v1.0.0/system_prompt.txt`

---

## **Related Documentation**

- **[AGENT_RUNTIME.md](AGENT_RUNTIME.md)** — How agents load and execute
- **agents/README.md** — Agent directory structure
- **[WOLFIE_HEADER_SPECIFICATION.md](WOLFIE_HEADER_SPECIFICATION.md)** — File metadata format

---

## **Summary**

**All AI agent prompts MUST:**
1. Start with `You are {{agent_name}}.`
2. Use `{{agent_name}}` for all identity references
3. Never hard-code agent names
4. Apply to both root and versioned prompts

**This standard ensures dynamic, maintainable agent identity across the entire Lupopedia ecosystem.**
