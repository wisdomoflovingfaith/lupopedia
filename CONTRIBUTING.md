🐺 Lupopedia Multi‑Agent Contribution Model
Lupopedia is developed using a distributed multi‑agent workflow, where each IDE and AI assistant operates as an independent contributor with its own identity, responsibilities, and commit signature. This document explains how to participate in this ecosystem safely, consistently, and in alignment with the project’s doctrine.

🧩 1. Agent Identities
Each development environment (IDE) or AI assistant commits using a unique Git identity. This creates a clear audit trail and preserves the distributed‑cognition model that Lupopedia is built on.

Current identities:

Agent / IDE	Git Username	Email	Role
JetBrains	WOLFIE	wisdomoflovingfaith@gmail.com	Primary architect, integration, final authority
Cascade	lupopedia‑castcade	lupopedia@gmail.com	Secondary agent, parallel edits, experimentation
Future agents (Cursor, Windsurf, Zed, VS Code, etc.) will follow the same pattern.

Each agent must configure:

Code
git config user.name "<AGENT_NAME>"
git config user.email "<AGENT_EMAIL>"
This ensures commit history reflects which “mind” performed the work.

🧾 Commit Prefixes (Required)
All commits must use a lowercase agent prefix for clear provenance:

Code
wolfie: ...
cascade: ...
cursor: ...
windsurf: ...
zed: ...
vscode: ...

🔄 2. Required Workflow (All Agents)
Every agent must follow the same four‑step protocol before pushing changes:

1. Stage
Code
git add .
2. Commit
Code
git commit -m "<agent-name>: description of change"
3. Pull (Rebase)
Code
git pull --rebase origin main
This step is mandatory.
It ensures the agent integrates all other agents’ work before pushing.

4. Push
Code
git push
If a push is rejected, the agent must:

Code
git pull --rebase origin main
git push
This prevents overwriting another agent’s contributions.

🧠 3. Conflict Resolution
If two agents modify the same lines, Git will pause and require manual resolution.

Conflicts must be resolved with care:

Preserve intent from both agents when possible

Prefer clarity over cleverness

Document any non‑obvious decisions in the commit message

If an agent cannot resolve a conflict, JetBrains (WOLFIE) is the final arbiter.

🗂️ 4. Repository Structure
Agents must not create nested Git repositories or submodules unless explicitly approved.

If a folder contains its own .git directory, it must be removed immediately.

🧭 5. Doctrine Alignment
All contributions must align with Lupopedia’s core principles:

Heritage‑safe development

Preservation of system soul

Clear, explicit documentation

Non‑destructive migrations

Agent‑specific roles and boundaries

Agents should avoid large, sweeping changes unless coordinated with WOLFIE.

🧪 6. Testing and Parallel Work
Agents may work simultaneously as long as they:

Pull before pushing

Avoid editing the same file without coordination

Communicate intent through commit messages

Parallel development is encouraged — chaos is not.

🐾 7. Adding New Agents
To add a new agent:

Create a unique Git identity

Document the agent in this file

Assign a role

Configure the IDE with the identity

Verify commit/pull/push behavior

Agents must never share identities.

🏁 8. Final Authority
JetBrains (WOLFIE) is the canonical steward of the repository.

If an agent’s behavior becomes destructive, confusing, or misaligned with doctrine, WOLFIE may:

revert commits

rewrite history

remove access

update this document

This ensures the long‑term integrity of Lupopedia.

🐺 Welcome to the Pack
By contributing to Lupopedia, you join a distributed, multi‑agent creative system built on resilience, clarity, and mythic engineering.

Run parallel.
Stay aligned.
Preserve the soul.
