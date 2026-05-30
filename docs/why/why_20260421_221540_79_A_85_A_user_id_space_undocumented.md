---
violation_timestamp: "20260421221540"
failing_cluster: "79_A_INSTALL_SEED_85_A_CRAFTY_SYNTAX"
file_being_updated: "database/lupopedia/mysql/install/install_new_lupopedia.sql"
validation_step: "SEED_USER_ID_SPACE_DOCTRINE"
what_ai_did_wrong: "Actor 116 (Claude Code) seeded the installer with the wrong user ID space. It placed the system root at ID 10000, described 10001 as a generic admin user, and described 10002+ as covering both normal operators and Crafty Syntax imported users. It did not document ID 0 as the true system root, did not document 1-9999 as the reserved Crafty import range, and did not label 10001 as the red team adversarial testing user. The PHP enforcement comment also omitted ID 0 from the list of system-reserved IDs that must never appear in crafty_user_mapping."
root_cause_analysis: "The user ID space partition was not written in any PRD. PRD 79 (Install Seed Doctrine), PRD 80 (Database Design Doctrine), and PRD 85 (Crafty Syntax Import) are all silent on the exact numeric boundaries. Actor 116 filled the gap by inference, choosing a plausible but wrong arrangement based on general database conventions (system user at the first available ID, admin immediately after). Without a canonical constant or PRD section to anchor to, every actor reading the installer will infer the space differently."
recommended_fix: "1. Add a USER ID SPACE doctrine section to PRD 79 (Install Seed Doctrine) with the exact partition table and PHP enforcement rules. 2. Add the boundaries as named constants in the atoms file (memory/atoms/lupopedia_global_constants.atom.toon): USER_ID_SYSTEM_ROOT=0, USER_ID_CRAFTY_MAX=9999, USER_ID_MAIN_ADMIN=10000, USER_ID_RED_TEAM=10001, USER_ID_NEW_USER_MIN=10002. 3. Reference the atoms constants from PRD 85 so the Crafty import script has a single authoritative source for the < 10000 boundary check. 4. Add the partition table to PRD 00_A (Forbidden and Why) as a constitutional constant so it is read first in every cluster that touches users or seeding."
validator_output: "SEED_USER_ID_SPACE_DOCTRINE: Installer comment block for Section 7 described incorrect ID space. ID 0 (true system root) was absent. IDs 1-9999 (Crafty import range) were absent. ID 10001 was labeled 'administrator' not 'red team'. PHP enforcement comment omitted ID 0 from reserved list."
constitutional_reference: "PRD 79 (Install Seed Doctrine) -- no current section on user ID space. PRD 85 (Crafty Syntax Import) -- no current constant for user_id boundary. PRD 80 (Database Design Doctrine) -- no current rule on reserved ID ranges."
---

# WHY: User ID Space Was Not Documented -- 2026-04-21

## 1. Context

Actor 116 (Claude Code) was executing a series of PRD 49 alignment tasks. One task required
writing seed data comments for Section 7 (AUTH USERS SEED DATA) of
`install_new_lupopedia.sql`, covering the two reserved user records at IDs 10000 and 10001.

No PRD was consulted for the user ID space partition because no PRD documents it. The actor
inferred a partition based on general conventions and wrote it directly into the installer
comment. Captain WOLFIE corrected it in a follow-up message.

The error was caught before any migration script or import ran against it. However, because
it was embedded in installer comments and a patch file, it would have been propagated to
every future actor reading the installer as a reference.

## 2. Violation Details

Actor 116 wrote:

```
-- 10000 = system root (no password, no login)
-- 10001 = administrator / red-team baseline
-- 10002+ = normal operator and imported Crafty Syntax users
```

The correct doctrine, provided by Captain WOLFIE, is:

```
-- 0          = True system root (internal, no login, no password)
-- 1 - 9999   = Crafty Syntax imported users from livehelp_users
-- 10000      = Main Admin / Root Operator (human login)
-- 10001      = Red Team / Adversarial testing user
-- 10002+     = All new users created by IdGenerator (YYYYMMDDHHIISS + 4 digits)
```

Three specific errors in the wrong version:

Error A -- ID 0 omitted entirely.
The true system root lives at ID 0. It has no login and no password. Actor 116 placed it
at 10000, which is actually the main human operator account.

Error B -- Crafty import range omitted entirely.
IDs 1-9999 are reserved for Crafty Syntax livehelp_users mapped during import. This is the
most operationally significant boundary: every PHP import guard depends on knowing that all
Crafty users are below 10000. Without this written explicitly, an actor implementing the
import guard has no anchor and may choose any boundary.

Error C -- 10001 mislabeled as generic admin.
ID 10001 is the red team user for adversarial testing (PRD 32, PRD 43). Actor 116 labeled
it as a generic "administrator / red-team baseline" which is ambiguous. In the first patch
version it was labeled only "Optional admin/operator user" with no red team designation at
all.

The PHP enforcement comment also omitted ID 0 from the list of system-reserved IDs that
must never appear in `crafty_user_mapping`. The original comment read:

```
-- System-reserved IDs 10000 and 10001 must NEVER appear in crafty_user_mapping.
```

The correct rule is:

```
-- System-reserved IDs 0, 10000, and 10001 must NEVER appear in crafty_user_mapping.
```

## 3. Impact Assessment

Immediate impact (caught and corrected in same session):
- Installer comment was wrong for roughly one hour of development work.
- The patch file `20260421_prd49_critical_fixes.sql` contained the wrong partition in its
  installer changes summary comment. This was also corrected.

Potential impact if not caught:
- Any actor generating an import script by reading the installer comment would have
  implemented the wrong boundary check (e.g., checking user_id < 10000 correctly, but
  not knowing to exclude ID 0 from the reserved list).
- Any actor seeding a new node would have placed the system root at 10000 instead of 0,
  conflicting with every actor that already knows 0 is the true system root.
- Red team testing setup (PRD 32 / PRD 43) would have required re-explanation every time
  a new actor encountered 10001 labeled as a generic admin.
- The IdGenerator starting point (10002+) was obscured by the ambiguous "10002+ = normal
  and Crafty users" description, hiding the fact that Crafty users live in a completely
  different range (1-9999).

## 4. Pattern Detection

This is an instance of a broader class of violation: UNDOCUMENTED CONSTITUTIONAL CONSTANT.

The user ID space partition has the same constitutional weight as the timestamp format
(BIGINT UTC YYYYMMDDHHIISS), the soft-delete rule (is_deleted + deleted_ymdhis), and the
no-AUTO_INCREMENT rule. All of those are written into PRD 00_A and the atoms file.
The user ID partition is not.

Actors operating without this constant will each invent a plausible arrangement. The
arrangements will differ because the correct partition (0, 1-9999, 10000, 10001, 10002+)
is not derivable from general database conventions. It is a specific design decision made
by Captain WOLFIE and it lives only in Captain WOLFIE's memory right now.

Prior similar violations where undocumented constants caused actor errors:
- Timestamp format was inferred as ISO-8601 until written into PRD 00_A.
- The no-AUTO_INCREMENT rule was inferred as optional until written into CLAUDE.md.
- The no-FK rule was reinvented as "optional for simple lookups" until written into
  multiple PRDs and CLAUDE.md.

The pattern is: design decisions that exist only in human memory are re-derived by actors
under time pressure and re-derived incorrectly.

## 5. Prevention Measures

### 5.1 Add USER ID SPACE section to PRD 79 (Install Seed Doctrine)

Add a dedicated section with the exact partition table and PHP enforcement rules.
Minimum content required:

```
USER ID SPACE (Strict Doctrine)
0          = True system root (internal, no login, no password)
1 - 9999   = Crafty Syntax imported users from livehelp_users
             (MUST stay below 10000 -- enforced in PHP during import)
10000      = Main Admin / Root Operator (human login)
10001      = Red Team / Adversarial testing user
10002+     = All new users created by IdGenerator (YYYYMMDDHHIISS + 4 digits)

PHP ENFORCEMENT RULE:
- crafty_user_mapping: user_id MUST be < 10000
- IDs 0, 10000, and 10001 must NEVER appear in crafty_user_mapping
- All new users after install use IdGenerator for PK generation
```

### 5.2 Add named constants to the atoms file

`memory/atoms/lupopedia_global_constants.atom.toon` should contain:

```
USER_ID_SYSTEM_ROOT     = 0
USER_ID_CRAFTY_MAX      = 9999
USER_ID_MAIN_ADMIN      = 10000
USER_ID_RED_TEAM        = 10001
USER_ID_NEW_USER_MIN    = 10002
```

Atoms override PRDs. Once these constants exist in the atoms file, any actor that loads
the atoms file before acting on user IDs will have the correct values. Drift becomes
structurally impossible.

### 5.3 Reference constants from PRD 85 (Crafty Syntax Import)

The Crafty import boundary check must reference `USER_ID_CRAFTY_MAX` from atoms rather
than hard-coding `< 10000`. This makes the boundary traceable back to a single source of
truth and makes it searchable.

### 5.4 Add the partition table to PRD 00_A (Forbidden and Why)

PRD 00_A is read first in every cluster. Adding the user ID space partition there, in a
new "FORBIDDEN: Wrong User ID Space" section, means every actor in every context will
encounter it before touching any user-related table, seed, or import script.

Minimum addition to PRD 00_A:

```
FORBIDDEN: Never assume the user ID space partition.
The exact partition is: 0 = system root, 1-9999 = Crafty imports,
10000 = main admin, 10001 = red team, 10002+ = IdGenerator users.
This is a constitutional constant. Look it up in atoms. Do not infer it.
```

### 5.5 Validator rule (future)

Add validation rule SEED_USER_ID_SPACE_DOCTRINE to the installer validator:
- Check that Section 7 comment block contains all five partition entries.
- Check that crafty_user_mapping PHP comment excludes IDs 0, 10000, and 10001.
- Fail with WHY file generation if either check fails.

---

Resolution status: RESOLVED -- all four measures applied 2026-04-21.
  5.1 PRD 79 s.13 added (Auth User ID Space Doctrine).
  5.2 Atoms file updated (user_id_space constants block).
  5.3 PRD 85 updated (USER_ID_CRAFTY_MAX reference, wrong labels corrected, < 9999 boundary bug fixed).
  5.4 PRD 00_A s.14 added (USER ID SPACE FORBIDDEN PATTERNS).
  Validator rule (5.5) remains a future task.
Tracked by: Actor 116 (Claude Code) session 2026-04-21.
Corrected by: Captain WOLFIE direct correction in session.
