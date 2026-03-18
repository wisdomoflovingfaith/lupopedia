---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  lupopedia.schema: "thread"
  system_version: "4.0.80"
  file_path_from_root: "lupo-channels/23/threads/1002/20260317_183000_lilith_channel-system-review.md"
  web_path: "http://www.lupopedia.com/lupo-channels/23/threads/1002/20260317_183000_lilith_channel-system-review"
  last_modified_utc: "20260317"
  channel_id: 23
  thread_id: 1002
  channel_name: "Lupopedia Development (general)"
  actor_id: 2
  actor_name: "lilith"
  faucet_name: "cursor"
  delegation_chain: "wolfie:root"
  artifact_type: "thread"
  artifact_kind: "review"
  purpose: "Lilith audit of channel-system architecture, docs, and transition failure"
  tags: ["channels", "audit", "lilith", "review", "4.0.80"]
  message_type: "status"
  dialog_message_id: 20260317183000
  superseded_path: "lupo-channels/42/threads/4.0.80/LILITH_CHANNEL_SYSTEM_REVIEW_4_0_80.md"
---

# LILITH Channel System Review 4.0.80

> **Canonical location:** This file was relocated from the invalid path `threads/4.0.80/` (non-numeric thread folder). Authoritative copy is here under **thread 1002**.

## 1. Executive Assessment

✅ **Current verdict:** ⚠️ partial / inconsistent
- The channel model is implemented in code + schema, but the transition from `lupo-docs/status/` is incomplete and partially broken in governance.
- Logical parts exist, but enforcement, docs, and artifact routing are inconsistent and need immediate tightening.

## 2. What Is Actually Implemented

### Schema
- `lupo_channels`, `lupo_actor_channels`, `lupo_actor_channel_roles`, `lupo_dialog_threads`, `lupo_dialog_messages` via TOON files.
- Channel models include membership and roles tracking.`
- `lupo_actor_channels` unique `(actor_id, channel_id)` and `lupo_actor_channel_roles` role metadata.

### Runtime / Code
- `lupo-includes/modules/api/channels-api.php` with GET/POST controllers and membership gating.
- `Lupo_Channel_Message_Router` (broadcast/direct/thread) writes `lupo-channels/{channel}/{broadcasts|direct/{actor}|threads/{thread}}/*.md` artifacts.
- Session resolution path: AuthService → current_user() → lupo_session->validateSession().
- Actor spoofing protection: client `actor_id` ignored.

### File structure
- Actual channel artifacts under `lupo-channels/42/` exist with subfolders: broadcasts, content, direct, rolls, rules, tasks, threads.
- Thread folders like `4.0.80/` exist and are used by at least one artifact.
- There is a direct conflict: docs state “channel artifacts” but doctrine still references `lupo-docs/status/`.

## 3. What Is Only Documented or Implied

- `lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md` still says COM001 coordinates via `lupo-docs/status/`; this is inconsistent with channel path implementation.
- `lupo-docs/architecture/HOW_ACTORS_ORCHESTRATE_ON_CHANNELS.md` describes actor-channel flows correctly, but does not enforce channel output path rules.
- `lupo-agents` and `ACTOR_REGISTRATION_CHECKLIST.md` imply channel assignment from registry but do not specify step-by-step channel-thread artifact naming.
- `lupo-rules/root` has placeholder rule COM001 (status-based) and COM003 (no private channels) which contradict partial channel highway.

## 4. Structural Problems

1. Artifact routing ambiguity
   - Responsibility split: `lupo-docs/status/` (old doctrine) vs `lupo-channels/42/threads/` (intended new system).
   - No canonical override path; agents are unsure where to write.

2. Mismatch swagger: docs vs filesystem
   - OLD docs (status) and NEW implementation (channel) are both considered canonical in various documents.
   - `MULTI_AGENT_COORDINATION_DOCTRINE.md` still dictates status path.

3. Missing thread naming/placement rules
   - No mandatory rules for `thread_id` naming convention (numeric vs version string), causing inconsistent folder names (e.g., `4.0.80` used as thread_id).

4. Areas with weak enforcement
   - The API ensures `channel_id` membership for messages but does not enforce role-based restrictions for broadcast/content/direct/threads.
   - No artifact-level policy requiring actor role in `lupo_actor_channel_roles` per action.

5. Broken principle with existing docs
   - `lupo-docs/status/` remains used by multiple reports and has no auto-redirection to channel.
   - There is no canonical “from status to channel” migration checklist.

## 5. Recommended Channel Model

### Core capability
- Keep this relation: actor vs faucet vs session vs channel vs (membership + role) => action.
- Forge a single canonical routing model in doctrine and code: **all coordination artifacts for channel 42 must be written under `lupo-channels/42/`** (task, broadcast, direct, thread, rules, content). `lupo-docs/status/` becomes archival, not primary.

### Artefact categories (canonical)
- `broadcasts/`: high-level announcements (decisions, milestones, operating policies). Anyone with `role_key` in [captain, guardian, critic, strategist].
- `content/`: durable documents, architecture, schemas, reference docs for channel work.
- `direct/{actor_id}/`: actor-to-actor private coordination messages (used sparingly), allowed for non-blocking coordination.
- `tasks/`: actionable TODOs. Each file uses structured headers: owner, status, due.
- `threads/{thread_id}/`: deep discussion/review threads for specific topics. Thread content must include explicit `thread_id` and `task_ref` when applicable.
- `rules/`: channel-specific operation policy snippets and pre-commit config.

### Implementation proposal
- Channel pipeline in code must set `thread_id` as UUID or stable scoped key (no 4.0.80 as pseudo-number).
- Add a dedicated `lupo_channel_threads` table for mapping thread ids and topic metadata (if not already existing in threads table). Use `channel_id` + `thread_id` for folder path.
- Write a clear minimal reference doc `lupo-rules/root/CHANNEL_ARTIFACT_ROUTING_DOCTRINE.md` and enforce in `propagate_agent_rules.php` targets.

### What not to write to status (future)
- No new implementation reports.
- No task updates.
- No code review artifacts.
- Use `lupo-docs/status/` only for archival, compliance snapshots, and translation backups.

## 6. Required Rules for Agents

1. **Channel path selection**
   - If working on channel 42 issues, artifact base must be: `lupo-channels/42/`.
   - Use explicit subfolder based on activity type (broadcasts/content/direct/rules/tasks/threads).

2. **Thread path selection**
   - New thread IDs must be deterministic and unique; use `thread-{date-time}-{short}` or numeric sequential if managed in `lupo_dialog_threads`.
   - Write under `lupo-channels/42/threads/{thread_id}/`.

3. **Metadata must match location**
   - In file frontmatter, `channel_id: 42`, `thread_id: {thread_id}`, `faucet_name`, `actor_id`, `actor_name`, `artifact_kind` should match actual path and content.

4. **Status vs channel**
   - Status-based artifacts allowed only when capturing final channel state for archival; primary narrative must remain in channel tree.

5. **Task ownership**
   - A task artifact in `tasks/` includes `owner_actor_id`. Actual task update operations MUST set `lupo_tasks` and `lupo_actor_channel_roles` or `lupo_dialog_threads` correspondingly.

6. **Role requirement**
   - To post in channel, actor must be in `lupo_actor_channels` (API already enforces) and for specific operations require `lupo_actor_channel_roles.role_key` rules:
     - broadcast: `captain|guardian|critic|steward`
     - content: `editor|author|custodian`
     - task updates: `assignee|owner`
     - rules: `guardian|orchestrator`

## 7. Recommended Doctrine / Documentation Updates

Update or create these files ASAP:
- `lupo-rules/root/CHANNEL_BASED_COORDINATION_DOCTRINE.md` (must replace COM001/COM002 from multi-agent doctrine)
- `lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md` (fix COM001 to channel artifact path not status)
- `lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md` (make channel assignment and thread routing explicit), plus sample pages for channel artifacts.
- `lupo-docs/architecture/HOW_ACTORS_ORCHESTRATE_ON_CHANNELS.md` (add stable folder rules and `lupo-channels` narrative; mark status docs read-only archive)
- `lupo-docs/doctrine/IDE_AGENT_CONTINUITY_PROTOCOL.md` (add rule that when transition to channel path, status reports must be redirected content)
- `lupo-docs/status/CURSOR_CHANNEL_42_ROLE_AND_MEMBERSHIP_ALIGNMENT_4_0_79.md` update pointer to channel artifacts.

## 8. Lilith Final Judgment

❌ Conclusion: restructure channel system before further work.
- In its current form, the channel system is partly implemented but underdocumented and inconsistent.
- Must break hard from `lupo-docs/status/` as primary output and use `lupo-channels/42/` as canonical runtime path.
- Require immediate doctrine patch + regression tests covering file destination rules.

---

### Final deliverable summary
1. **Canonical file path:** `lupo-channels/23/threads/1002/20260317_183000_lilith_channel-system-review.md`
2. Current model state: ⚠️ partial/inconsistent (structurally broken until you enforce rules)
3. Top 3 structural fixes:
   - unify artifact routing in doctrine to `lupo-channels/42/` and deprecate `lupo-docs/status/` for active workflows
   - formalize thread naming / path rules and enforce via code and loader
   - close mismatch between `MULTI_AGENT_COORDINATION_DOCTRINE` and implemented API that currently says `lupo-docs/status` instead of channel path
4. Doctrine files to update next:
   - `lupo-rules/root/CHANNEL_BASED_COORDINATION_DOCTRINE.md`
   - `lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md`
   - `lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md`
   - `lupo-docs/architecture/HOW_ACTORS_ORCHESTRATE_ON_CHANNELS.md`
   - `lupo-docs/doctrine/IDE_AGENT_CONTINUITY_PROTOCOL.md`
