---
flare.headers:
  file_path_from_root: "docs/auth/WEB_AUTHENTICATION_AND_ACTOR_SELECTION.md"
  last_modified_utc: "20260307"
  system_version: "4.0.62"
  artifact_type: "documentation"
  purpose: "Web authentication flow and actor selection (Trae IDE 1008, v1.0)"
  tags: ["auth", "actor", "admin", "trae"]
---

# Documentation: Web Authentication and Actor Selection

**Author:** Trae IDE (1008)  
**Date:** 2026-03-07  
**Version:** 1.0

## 1. Introduction

This document explains the dual-identity model in Lupopedia, detailing how identity is established and managed for both IDE agents (via CLI and markdown files) and human users (via the web interface). It provides an overview of the web authentication flow, actor selection, and the underlying services that support this system.

## 2. The Dual Identity Model

- **IDE Agents (CLI/MD):** Identity is determined by local markdown files (`session.md` or `.lupo_actor`), allowing offline-first and CLI operation.
- **Web Interface (`admin.php`):** Identity is established through web authentication. Human users log in; identity is managed via session. Both methods resolve to an actor in `lupo_actors` and are recorded in `lupo_sessions`.

## 3. Web Authentication Flow

1. **Check for authentication** — Protected pages (e.g. `admin.php`) check for a valid session.
2. **Redirect to login** — If not authenticated, redirect to `/login`.
3. **User authentication** — `AuthService` verifies credentials against `lupo_auth_users`.
4. **Session creation** — Valid credentials create a session; user information is stored.
5. **Actor determination** — Active actor is determined by priority rules (Section 5).
6. **Redirect to admin** — User is redirected to `admin.php`, authenticated with an active actor.

## 4. Actor Selection Interface

- **Actor selector** — Admin header includes a dropdown listing actors the user is permitted to act as (from `ActorService::getActorsUserCanActAs()`).
- **Switching actors** — Selecting an actor POSTs to `switch-actor.php`, which updates session active actor and optionally `session.md` for CLI sync.

## 5. Actor Determination Rules

1. **Session actor** — Explicitly selected via the actor selector (`active_actor_id` in session).
2. **User's default actor** — Preferred actor stored in session (`preferred_actor_id`).
3. **User's own human actor** — Actor corresponding to the user's `auth_user_id`.
4. **System default** — Fallback to system actor (actor_id 0).

## 6. Session Management

When an actor is selected in the web interface, `AuthService::setActiveActorId()` updates the session. `switch-actor.php` also writes `session.md` so CLI and web stay synchronized.

## 7. Service Enhancements

- **AuthService:** `getActiveActorId()`, `setActiveActorId()`, `getPreferredActorId()`, `setPreferredActorId()`.
- **ActorService:** `getActorsUserCanActAs($authUserId, $isAdmin)` — list of actors the user may act as (own actor + paired agents, or all actors for admin).

## 8. Admin Interface

- **Admin header** — Actor selector dropdown in `admin_layout.php` (Act as: [dropdown]).
- **Actor management** — `admin.php?section=actors` uses `AdminActorsHandler` to list and manage actors.

## Implementation

- `admin.php` — Requires login; passes `$admin_actor_list` and `$admin_active_actor_id` to layout.
- `switch-actor.php` — POST handler; CSRF check; updates session and `session.md`; redirects back.
- `lupo-includes/themes/default/layouts/admin_layout.php` — Renders actor selector form.
