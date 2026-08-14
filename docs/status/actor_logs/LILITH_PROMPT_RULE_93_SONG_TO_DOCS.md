---
lupopedia.headers:
  header_format_version: "4.2.0"
  path_from_lupopedia_root: docs/status/actor_logs/LILITH_PROMPT_RULE_93_SONG_TO_DOCS.md
  web_path: https://www.lupopedia.com/lupopedia/docs/status/actor_logs/LILITH_PROMPT_RULE_93_SONG_TO_DOCS.md
  status: active
  when_updated: "20260806214731"
  trust_tier: development
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/status/actor-logs-lilith-rule-93-song
  artifact_type: status
  artifact_kind: report
  channel_key: status
  federation_node_id: 0
  thread_key: rule-93-song-sync
  lupopedia.schema: status
  prd_cluster: 00_C_02_C_98_C_39_A
  title: "Lilith prompt -- sync Pronoun Apocalypse song to Rule 93 documentation"
  summary: "Copy-paste prompt for Lilith (actor_id 2): update the Rule 93 folk-punk song so lyrics match PRD 00 section 16.7 and PRD 02 KAPU as synced 20260806214511."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
  actor_id: 2
  auth_user_id: 10000
  department_id: null
  department_key: ""
  division_key: review
  faucet_actor_id: 102
---
# Lilith prompt -- Rule 93 song -> documentation sync

Copy everything below the line into a Lilith chat (actor_id 2). Song output remains artistic; **facts must match the PRDs**.

---

```text
LILITH PROMPT -- RULE 93 / GREAT PRONOUN REWRITE -- SONG ALIGNMENT TO DOCS

WHO:
- Reviewer / lyric editor: Lilith (actor_id 2)
- Orchestrator human: ERIC (auth_user_id 10000)
- Prior faucet that synced docs: CURSOR IDE (faucet_actor_id 102) under WOLFIE (actor_id 1)
- Audience: Captain Wolfie + Lilith folk-punk duet (same theatrical style as the existing song)

TASK:
Lilith MUST rewrite / update the existing "Pronoun Apocalypse / Rule 93" folk-punk song so every factual claim matches the CURRENT repository documentation. Lilith MUST NOT invent new constitutional law. Lilith MUST NOT claim the song creates Rule 93 -- Rule 93 already lived in the PRDs; the docs were expanded to match the song suite.

KAPU:
- ASCII only in any repo file Lilith writes.
- Actor names in attribution-bearing lines (Rule 93 spirit): prefer "Lilith confirms..." not bare "I confirms...".
- Song may still be theatrical; legal truth lives in PRDs.
- Do NOT densify Hawaiian fields into lupopedia.headers YAML.
- status: draft on any new lyric file until ALII / ERIC approves.

REQUIRED READING (read these BEFORE rewriting lyrics):

1) docs/prd/00_C-i_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS.md
   - Section 16.7 -- RULE 93.FIRST_PERSON_DISPLAY_FORBIDDEN
   - Also known as: The Great Pronoun Rewrite of 2026
   - Section 10.2 validator table row for 16.7
   - Section 12 refinements -- active sentence for Rule 93

2) docs/prd/02_C-i_CHANNELS_DISCUSSIONS.md
   - Change History entry: 2026-08-06 (RULE 93 song-suite sync)
   - KAPU: FIRST_PERSON_DISPLAY_FORBIDDEN (under Unified Chat UI/UX Principles)

3) includes/classes/DialogMvpService.php
   - rewriteFirstPersonEnglishForHumanIngest(...)
   - rewriteHumanDialogMessageBodyForInsert(...)
   - metadata: first_person_rewrite_applied, skip_first_person_rewrite, original_message_text_v1

4) docs/status/actor_logs/WOLFIE_DIALECT.md
   - Section 8b Pronoun Awareness + cross-rule note to Rule 93
   - (STATUS body dialect is NOT the same as channel ingest rewrite -- both aim at named actors)

5) docs/versions/4.1.9/todo.md (or current version TODO)
   - DEBT-93-FIRST_PERSON_REWRITE_LEGACY_INSERTS

WHAT CURSOR ALREADY CHANGED IN THE DOCS (Lilith must reflect these in the song):

A) Rule ID and homes (do NOT sing that Rule 93 was "born today" as if missing from the repo)
   - Rule already existed as RULE 93.FIRST_PERSON_DISPLAY_FORBIDDEN
   - Constitutional home: PRD 00 section 16.7
   - Channel write-path home: PRD 02 KAPU FIRST_PERSON_DISPLAY_FORBIDDEN
   - Prefer lyric: "Rule 93 already law / section 16.7 / PRD 02 KAPU binds the write path"
   - Optional: "Great Pronoun Rewrite of 2026" as nickname is OK (now in PRD)

B) Mapping table (MUST be accurate in verses that "list them all")
   - I, me, my, mine, myself -> speaker display name
   - we, us, our, ours, ourselves -> speaker name + "and others" (or explicit list when known)
   - you, your, yours, yourself, yourselves -> recipient display name
   - multiple recipients / group target -> "the group"
   - Song previously under-emphasized YOU family; docs now explicitly include second person

C) Example suite (bridge examples -- keep these accurate)
   - "I need you" -> "Captain Wolfie needs Lilith"
   - "I need you to help me" -> "Captain Wolfie needs Lilith to help Captain Wolfie"
   - "I think you should merge my branch" -> "Captain Wolfie thinks Lilith should merge Captain Wolfie's branch"
   - "Send me the file" -> "Send Thoth the file"
   - "We should discuss this" -> "Chiron and others should discuss this"
   - "I think X" -> "Thoth thinks X" (when Thoth is speaker)
   - "My idea is better than yours" -> named possessives per party (PRD example with Captain Wolfie / Lilith)

D) Idempotency + forensics (add or correct a verse / spoken break)
   - Flag: first_person_rewrite_applied -> do not rewrite twice
   - Flag: skip_first_person_rewrite -> explicit skip only under policy
   - original_message_text_v1 in metadata_json when body changed -- original preserved for forensics
   - Operator-visible channel body = rewritten form (not the raw "I")

E) Implementation truth (do not claim six unit tests exist in repo if they do not)
   - Canonical helpers EXIST in DialogMvpService
   - Target unit path documented: tests/unit/first_person_ingest_rewrite_test.php
   - As of sync 20260806214511 that test FILE WAS NOT PRESENT in the tree
   - Lyric fix: "six tests planned / suite path named" OR "helpers live / unit suite still owed"
   - Do NOT claim "six passing tests / zero failures" as current repo fact unless Lilith verifies the file exists and passes

F) DEBT-93 (Bridge 2 -- keep, but match doc paths)
   - Name: DEBT-93-FIRST_PERSON_REWRITE_LEGACY_INSERTS
   - Priority: MEDIUM, non-blocking
   - Files (at least):
     - includes/modules/channels/channel-send-api.php
     - database/lupopedia/channels/channel_id/1/admin_chat_xmlhttp.php
     - also audited: includes/classes/dialog-manager.php
     - also audited: database/lupopedia/content/app/Services/TriggerReplacements/DialogMessagesInsertService.php
   - Action: replace raw INSERT with rewriteHumanDialogMessageBodyForInsert + createDialogMessage
   - Target: next sprint or when legacy paths are touched

G) PRD sections the song named -- confirm wording
   - Section 16.7 -- YES (expanded)
   - Section 10.2 validator table -- YES (row updated for 16.7 + unit suite path + DEBT-93)
   - Section 12 active sentences -- YES (Rule 93 active sentence added under Refinements)
   - KAPU block -- YES (PRD 02 expanded)
   - All write surfaces listed -- YES (createDialogMessage, rewrite helper, TranscriptAppendService, channels/index.php, api/dialog/post-message.php)

H) Nice to have (spoken break -- KEEP, still accurate)
   - Lilith suggestion: visual indicator on rewritten messages (asterisk / shade / badge)
   - Captain Wolfie: not today; clarity first
   - Docs: Nice to Have -- Future Sprint; NOT required for ENFORCED status

I) Philosophy lines that remain true (keep / polish)
   - Cannot retrain the human; must retrain / compensate the system
   - Muscle memory types "I"; system rewrites at ingest
   - Authoring discipline (agents SHOULD write third person) is unreliable; Rule 93 is defensive rewrite
   - WOLFIE dialect {{WHO}} / {{TO_WHOM}} is STATUS authoring; Rule 93 is channel ingest -- both seek named actors

J) External / unrelated -- do NOT mix into this song unless ERIC asks
   - External internet header transport (single-line + ;;) is a DIFFERENT living note (WOLFIE_DIALECT section 5a)
   - Semantic whitelist/blacklist draft is DIFFERENT
   - Do not conflate Hawaiian KAPU with this Rule 93 KAPU block (same Hawaiian word family, different surfaces)

OUTPUT Lilith MUST produce:

1) Updated full lyrics (same style: chaotic folk punk duet, call and response, spoken interludes) with factual corrections above.
2) A short "DIFF vs old song" list (bullet points): what Lilith changed and why (cite PRD 00 16.7 / PRD 02).
3) A "STILL OPEN / NOT IN SONG" list if anything remains unpaid (unit test file missing, visual marker deferred, DEBT-93).
4) If Lilith writes a repo file: put it under docs/status/actor_logs/drafts/ with a valid 4.2.0 header, status: draft, actor_id: 2, auth_user_id: 10000, faucet_actor_id set to the surface Lilith is using. Filename lowercase underscore only. ASCII only.

SUCCESS CRITERIA:
- No lyric claims Rule 93 was invented only by the song.
- YOU/YOUR mapping appears in the listing verse.
- Idempotency + original_message_text_v1 appear at least once.
- Debt paths match the documentation.
- Test claims match repo reality (helpers exist; unit file may still be owed).
- Nice-to-have visual marker remains deferred, not "shipped".
- Lilith ends with: <SONG_DOC_SYNC_COMPLETE> and does not self-grade as ALII-final.

BEGIN.
```

---

## Operator note (ERIC)

Paste the fenced `LILITH PROMPT` block into Lilith. Canonical doc truth after CURSOR sync UTC `20260806214511`. Prompt artifact UTC `20260806214731`.
