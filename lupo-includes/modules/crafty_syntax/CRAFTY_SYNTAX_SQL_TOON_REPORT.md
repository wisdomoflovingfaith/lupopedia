# Crafty Syntax: SQL Column References vs TOON Schema

**Scope:** All PHP files under `lupo-includes/modules/crafty_syntax/`  
**TOON source:** `docs/toons/*.toon.json`  
**Date:** 2025-02-04

---

## 1. Inventory: SQL column references by file

### visitor-session-helper.php
| Table | Columns referenced |
|-------|--------------------|
| `lupo_sessions` | `session_id`, `actor_id`, `is_deleted`, `last_seen_ymdhis`, `updated_ymdhis`, **`metadata_json`** (variable `$meta_col`) |

### visitor-chat-stream.php
| Table | Columns referenced |
|-------|--------------------|
| `lupo_sessions` | `session_id`, `federation_node_id`, `actor_id`, `ip_address`, `user_agent`, `last_seen_ymdhis`, `created_ymdhis`, `updated_ymdhis` |
| `lupo_dialog_threads` | `channel_id`, `dialog_thread_id`, `is_deleted`, `federation_node_id`, `created_by_actor_id`, `status`, `created_ymdhis`, `updated_ymdhis`, `is_deleted` |
| `lupo_modules` | `config_json`, `module_id`, `is_deleted` |

### livehelp.php
| Table | Columns referenced |
|-------|--------------------|
| `lupo_departments` | `department_id`, `name`, `is_deleted` |
| `lupo_sessions` | `session_id`, `federation_node_id`, `actor_id`, `ip_address`, `user_agent`, `last_seen_ymdhis`, `created_ymdhis`, `updated_ymdhis` |

### visitor-image.php
| Table | Columns referenced |
|-------|--------------------|
| `lupo_sessions` | `session_id`, `last_seen_ymdhis`, `updated_ymdhis`, `session_id`, `federation_node_id`, `actor_id`, `ip_address`, `user_agent`, `last_seen_ymdhis`, `created_ymdhis`, `updated_ymdhis` |
| `lupo_operators` | `department_id`, `is_active`, `availability_status`, `operator_id` |
| `lupo_operator_status` | `operator_id`, `status` |
| `lupo_departments` | `department_id`, `is_deleted` |
| `lupo_department_metadata` | `department_id`, `metadata_json`, `is_deleted` |

### choosedepartment.php
| Table | Columns referenced |
|-------|--------------------|
| `lupo_departments` | `department_id`, `name`, `is_deleted` |
| `lupo_operators` | `department_id`, `is_active`, `availability_status`, `operator_id` |
| `lupo_operator_status` | `operator_id`, `status` |

### livehelp-js.php
| Table | Columns referenced |
|-------|--------------------|
| `lupo_departments` | `department_id`, `is_deleted` |
| `lupo_department_metadata` | `department_id`, `metadata_json`, `is_deleted` |
| `lupo_operators` | `department_id`, `is_active`, `availability_status`, `operator_id` |
| `lupo_operator_status` | `operator_id`, `status` |

### crafty_syntax-controller.php
- No SQL; routing only.

---

## 2. TOON schema columns (relevant tables)

- **lupo_sessions:** session_id, federation_node_id, actor_id, ip_address, user_agent, device_id, device_type, auth_method, auth_provider, security_level, is_active, is_expired, is_revoked, session_data, **metadata**, login_ymdhis, last_seen_ymdhis, expires_ymdhis, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis  
- **lupo_dialog_threads:** dialog_thread_id, federation_node_id, channel_id, project_slug, task_name, created_by_actor_id, summary_text, bg_color, text_color, alt_text_color, status, artifacts, metadata_json, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, escalated_to_operator_id, escalation_reason, escalation_timestamp  
- **lupo_modules:** module_id, module_key, module_name, namespace, version, version_code, minimum_core_version, user_path, admin_path, api_path, route_params, description, author, website, icon, dependencies, conflicts, config_json, is_system, is_active, federation_node_id, settings, installed_ymdhis, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis  
- **lupo_departments:** department_id, federation_node_id, name, description, department_type, default_actor_id, settings_json, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis  
- **lupo_operators:** operator_id, anchor_agent_id, channel_id, metadata_json, auth_user_id, actor_id, department_id, is_active, availability_status, created_ymdhis, updated_ymdhis, pono_score, pilau_score, kapakai_score  
- **lupo_operator_status:** operator_status_id, operator_id, status, last_seen_ymdhis, active_chat_count, max_chat_capacity, created_ymdhis, updated_ymdhis  
- **lupo_department_metadata:** department_metadata_id, department_id, metadata_json, created_ymdhis, updated_ymdhis, is_active, is_deleted, deleted_ymdhis  

---

## 3. Mismatches

| Table | PHP column used | TOON column | File(s) |
|-------|-----------------|-------------|---------|
| **lupo_sessions** | `metadata_json` | **`metadata`** | visitor-session-helper.php |

All other column references match the TOON schema.  
**Note:** `lupo_department_metadata` and `lupo_dialog_threads` use `metadata_json` in TOON; PHP correctly uses `metadata_json` for those tables. Only `lupo_sessions` uses a different name in TOON (`metadata`).

---

## 4. Corrections applied

1. **visitor-session-helper.php**  
   - Replaced all uses of session metadata column name from `metadata_json` to `metadata` to align with `lupo_sessions` TOON.  
   - Affected: variable `$meta_col` and any SQL/result keys that referred to the session metadata column; PHP result key must match the column name, so `$row[$meta_col]` and SET clause use `metadata` (column name).

---

## 5. Summary

- **Total tables referenced:** 7 (sessions, dialog_threads, modules, departments, operators, operator_status, department_metadata).  
- **Mismatches found:** 1 (lupo_sessions metadata column name).  
- **Corrections:** 1 (visitor-session-helper.php: use `metadata` for lupo_sessions).
