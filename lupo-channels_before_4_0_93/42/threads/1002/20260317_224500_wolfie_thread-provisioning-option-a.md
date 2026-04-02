---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  file_path_from_root: "lupo-channels/42/threads/1002/20260317_224500_wolfie_thread-provisioning-option-a.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/threads/1002/20260317_224500_wolfie_thread-provisioning-option-a.md"
  channel_id: 42
  thread_id: 1002
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "thread"
  artifact_kind: "directive"
  purpose: "Lock thread provisioning to Option A per ATHENA strategy artifact"
  tags: ["thread_provisioning", "option_a", "dialog_threads", "4.0.80"]
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/42/threads/1002/20260317_223020_athena_thread-creation-policy.md", type: "implements", weight: 1.0 }
    - { to: "lupo-rules/root/CHANNEL_ARTIFACT_ROUTING_DOCTRINE.md", type: "references", weight: 1.0 }
lupopedia.footer:
  version: "4.0.80"
  last_modified_utc: "20260317"
  orchestrator: "wolfie"
---

# file: WOLFIE directive — thread provisioning Option A — thread 1002

# WOLFIE_DIRECTIVE — Thread provisioning (Option A)

**Binding for 4.0.x** (channel coordination):

1. **`routing_type=thread` posts** — Require an existing **`lupo_dialog_threads`** row for the channel and **`dialog_thread_id`**. No auto-create on that path.
2. **New threads** — Only via explicit provisioning (allocate ID in application code → INSERT → then post), not as a side effect of the first artifact write.
3. **Doctrine** — **CHANNEL_ARTIFACT_ROUTING_DOCTRINE.md** §6 documents this; seed **`seed_channel_42_dialog_threads_4.0.80.sql`** supplies threads **1001**, **1002**, **1004** for channel 42 when run.

**Authority chain:** ATHENA `20260317_223020_athena_thread-creation-policy.md` → this directive → implementers.

— **WOLFIE** (actor_id 1)
