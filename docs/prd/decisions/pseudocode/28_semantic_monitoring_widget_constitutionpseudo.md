---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/decisions/pseudocode/28_semantic_monitoring_widget_constitutionpseudo.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/decisions/pseudocode/28_semantic_monitoring_widget_constitutionpseudo.md
  status: ''
  when_updated: '20260817092400'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: pseudocode
  channel_key: null
  federation_node_id: 0
  thread_key: pseudocode-28-semantic-monitoring-widget
  lupopedia.schema: documentation
  prd_cluster: null
  title: ''
  summary: ''
---
# PRD 28: PRD: Semantic Monitoring Widget (The Eye) â€” v4.0.94 â€” Shorthand

## Canonical clarification: what a channel is (and is not)

In Lupopedia, a channel is a semantic container inside a domain (node).
It is NOT a Discord room, Slack channel, chat feed, or conversation thread.

**Hierarchy**

```text
Domain (node)
  -> Channel (semantic container)
      -> Thread (artifact)
          -> Messages, memory, atoms, PRDs, and other scoped artifacts
```

**Definition**

- **Channel** = a governed semantic container that defines scope, meaning, and memory boundaries.
- **Thread** = a single conversational artifact inside a channel.
- **Threads do not contain channels. Channels contain threads.**

**Required warning (normative where channels are defined):** Channels are semantic containers, not conversational rooms. They define scope, governance, and meaning for all threads within them.

**Schema keys:** `channel_key`, `channel_id`, and related channel metadata keep their existing names; this section clarifies semantics only (no field renames).

## One-Line Summary

PRD for Semantic Monitoring Widget (The Eye) -- JavaScript page tracking, semantic data collection, floating navigation bar with optional visual effect, color identity display, and lineage indicators

## Color Identity and Lineage (Eye)

PRD-level only. No new DDL from this shorthand. Do not guess HEX6. Color is not a LUP KEY token. HEX6 is six digits without `#`. HEX5 is not a color.

```text
FUNCTION eye_load_page_metadata(page_url, domain_root, lupopedia_public_path):
    payload = GET lupopedia_js.php
        WITH current_url = page_url
        WITH embed context (origin, slug) when cross-origin (PRD 21)
    color = payload.color OR empty
    lineage = payload.lineage OR empty

    DISPLAY badge:
        IF color.hex6 is six digits:
            paint badge with hex6
            DO NOT prefix '#'
        ELSE:
            show pending / GroupColor label only
            DO NOT invent hex6
        SHOW color.group_color
        SHOW color.color_name
        IF handshake known:
            SHOW "lupopedia poweredby [GroupColor] [ColorName]"

    DISPLAY lineage:
        IF lineage.parent_url exists: SHOW parent URL
        IF lineage.child_urls exists: SHOW each child URL
        LINK "View Lineage Tree" -> lineage.view_url OR lupopedia lineage view
        ACTION "Declare Child Page" ->
            parent_path = page_url path RELATIVE TO domain_root
            NOT relative to /lupopedia/
            OPEN lupopedia_public_path + "/?parent=" + parent_path
        ACTION "Find References" -> existing [Ref] lookup

    ACTIONS:
        "Color this Page" -> live help Content / local OS coloring
            NOT the Color Registry homepage (homepage is cross-domain lineage)
        "Declare Child Page" -> as above
        "View Lineage" -> lineage panel / tree
        "Copy Page" -> copy page URL (+ handshake when known)
        "Edit Page" -> local content editor when this is an OS artifact
        "Share Page" -> existing share action

    TRACK (PRD 11), do not invent counts:
        color_identity_viewed
        lineage_viewed
        child_page_created
        parent_page_referenced

    RETURN (do not write database from this widget fetch)
```

## Core Rules

- Auth_users in Department 0 or Department 1 may create new departments.
- Departments 2+ are defined by the installation and its domain scope.
- Departments created by the installation inherit structure from Crafty Syntax import.
- Assigning a user to Department 0 or Department 1 MUST show a warning in the web interface.
- Warnings do NOT block assignment; they inform the user of elevated authority.
- Actors are created in two ways:
- Each actor belongs to exactly one department.
- Auth_users may only select actors that belong to their department.
- Ensures correct separation of authority between Department 0, Department 1, and Departments 2+.
- Prevents contamination of core/system actors by vibe-driven or framework-default patterns.

## Forbidden Patterns

- âŒ NO foreign
- âŒ NO triggers
- âŒ NO `is_public`
- âŒ NO core
- âŒ NO inference

## Required Patterns

- âœ… MUST show a warning in the web interface
- âœ… MUST NOT be inferred unless explicitly defined
- âœ… MUST be provided
- âœ… MUST be explicitly defined in PRDs, database rows, or
- âœ… MUST be documented in PRDs and versioned

## Edge Types

| Edge | Direction | Meaning |
|------|-----------|---------|
| `FROM` | unidirectional | Defined in PRD |

## Constitutional Cross-References

- See PRD 00 for root rules
- See PRD 05 for auth/actor transformation
- See PRD 15 for actor lifecycle
- See PRD 25 for departments
- See PRD 90 for color identity (GroupColor, ColorName, HEX6)
- See PRD 01_B for color registry tables (planning)
- See PRD 04 for lupopedia_js.php payload to The Eye
- See PRD 11 for color/lineage analytics events
- See PRD 21 for navbar color identity display
- See PRD 33 for embed contract (lupopedia_js.php + livehelp_js.php)
- See PRD 38 for memory unification

## Token-Efficient Checklist

- [ ] Read full PRD for complete context
- [ ] Apply core rules above
- [ ] Check forbidden patterns
- [ ] Verify required patterns
- [ ] Cross-reference with related PRDs
- [ ] Color identity: GroupColor, ColorName, HEX6 when known (never guessed)
- [ ] Lineage: parent URL, child URLs, Declare Child Page via ?parent=
- [ ] Metadata via lupopedia_js.php only (no browser DB access)

---
*Auto-generated by `scripts/generate_prd_shorthands.py`*
*Source: `docs/prd/28_semantic_monitoring_widget.md`*
*Last sync: 20260408013734*
