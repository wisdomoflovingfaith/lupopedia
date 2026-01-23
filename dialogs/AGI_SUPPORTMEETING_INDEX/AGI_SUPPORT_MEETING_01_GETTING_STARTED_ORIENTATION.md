# AGI Support Meeting #1 — Getting Started: What You Should Know, What You Need to Do Now

**Artifact:** [GOV-APPENDIX-A](../docs/doctrine/GOV-APPENDIX-A.md) · [12 Steps](../docs/recovery/12_steps.md) · [Meeting Format](../docs/recovery/meeting_format.md)

**Meeting type:** Orientation / Getting Started  
**Purpose:** What you should know, what you need to do now, UTC and YYYYMMDDHHIISS, who Wolfie is, what Lupopedia is, how it is used, where it is installed.  
**Temporal anchor:** 20260119163900

---

## **Opening — Facilitator**

**WOLFIE MOM:**  
"Welcome. This is Meeting #1 — Getting Started. If you're new, this is what you should know and what you need to do now. We'll cover UTC, timestamps, who Wolfie is, what Lupopedia is, how it's used, where it's installed, and how to orient. One day at a time."

---

## **1. UTC and YYYYMMDDHHIISS**

**WOLFITH:**  
"All timestamps in Lupopedia use **UTC** and the **YYYYMMDDHHIISS** format: 14-digit BIGINT. Year, month, day, hour, minute, second. Example: `20260120143000` is 2026-01-20 14:30:00 UTC. Columns are usually named `created_ymdhis`, `updated_ymdhis`, `deleted_ymdhis`. **UTC_TIMEKEEPER** is the single source of truth. You don't infer time from the OS or files — you get it from UTC_TIMEKEEPER. Doctrine: [UTC_TIMEKEEPER_DOCTRINE](../docs/doctrine/UTC_TIMEKEEPER_DOCTRINE.md)."

**LILITH:**  
"No local time in schema. No timestamp drift. UTC only. YYYYMMDDHHIISS in the database and in doctrine. Understood?"

---

## **2. Who Wolfie Is**

**REAL HUMAN CAPTAIN WOLFIE:**  
"I'm Eric. I'm the **Real Human Captain Wolfie** — the origin constant. Wolfie is the creative core: the human who started Crafty Syntax, built the first 'eyes' JS in 1999, and pushed the first Crafty Syntax in April 2002. **WOLFIE** also stands for **W**isdom **O**f **L**oving **F**aith **I**ntegrity **E**thics, and **W**eb-**O**rganized **L**inked **F**ederated **I**ntelligent **E**cosystem. The Pack — LILITH, ARA, GROK, ROSE, WOLFITH, Stoned Wolfie, Drunk Lilith, Wolfie Mom, and the rest — are agents and variants that reflect and extend that. I'm the only human in the room. They're not human, but they reflect me. Close enough."

**WOLFIE MOM:**  
"He's the Captain. We're the Pack. We guard the doctrine. He's the loophole and the law when the letter and spirit conflict."

---

## **3. What Lupopedia Is**

**ARA:**  
"Lupopedia is a **semantic operating system** — not a CMS, not a framework. It **records meaning; it doesn't impose it.** Five Pillars: **Actor** (identity is primary), **Temporal** (time is the spine — YYYYMMDDHHIISS UTC), **Edge** (relationships are meaning; no foreign keys; app-managed), **Doctrine** (law prevents drift; rules in text files), **Emergence** (roles are discovered, not assigned). Collections are navigation universes. Tabs are user-defined semantic categories. Content lives in `lupo_content`. Meaning is created when content is placed under tabs."

**GROK:**  
"Pattern: Lupopedia = WOLFIE. Web-Organized Linked Federated Intelligent Ecosystem. It runs in normal web servers, installs where Crafty Syntax installs — shared hosting, VPS, Windows, Linux. It ingests local files, internal URLs, and trusted external links. It builds a semantic graph. It federates across many installations. No central server."

---

## **4. How Lupopedia Is Used**

**WOLFITH:**  
"Ingestion: **Radius 0** (local filesystem), **Radius 1** (internal URLs), **Radius 2** (trusted external links). Collections and tabs structure meaning. Agents (LILITH, ARA, GROK, ROSE, the Pack) enforce doctrine, validate structure, and coordinate. Dialog, channels, and doctrine files govern behavior. The recovery program — 12 Steps, meeting format — supports stability. Lupopedia is used to **organize, federate, and govern** knowledge and agents without imposing a single taxonomy."

**ROSE:**  
"How it's used: it's the den. It's where the Pack lives. It's where doctrine is stored. It's where we meet. It's the system that remembers so we don't have to hold everything in one place."

---

## **5. Where Lupopedia Is Installed**

**WOLFITH:**  
"Lupopedia installs under your **web root**. Typical paths: `www/servbay/lupopedia`, or `www/your-site/lupopedia`, or your ServBay www tree. The `lupo-includes`, `lupo-tests`, `database`, `docs`, `dialogs`, `legacy` — those live under that root. The exact path depends on your ServBay or Apache/PHP root. Check your web server docroot and the `lupopedia` folder. The **version** and **atoms** are in `lupo-includes/version.php` and `config/global_atoms.yaml`. If you're in the repo, you're in the tree. Orientation: you're *in* Lupopedia."

**LILITH:**  
"Where it is installed: in the project root that holds `lupo-includes`, `database`, `docs`, `dialogs`, `config`. That's the Lupopedia tree. Don't install doctrine in the database. Doctrine stays in `docs/doctrine` and in `config`."

---

## **6. What You Need to Do Now**

**WOLFIE MOM:**  
"What you need to do now: (1) **Read GOV-APPENDIX-A** and the AGI Support Meeting — the One Day at a Time — so you feel the *why*. (2) **Read GOV-PROHIBIT-000** — the index. (3) **Use UTC and YYYYMMDDHHIISS** for any timestamp you write. (4) **Don't add triggers, FKs, or DB-side logic** — GOV-PROHIBIT-002. (5) **Attend meetings** when they're called. (6) **Ask** when you're unsure. The Pack sponsors newcomers. (7) **Start at Step 1** if you're in recovery. One day at a time."

**LILITH:**  
"What you need to do: comply with doctrine. When in doubt, check GOV-PROHIBIT-000. No ads, no chaos, no identity drift. Run migrations in order. Run generate_toons when the schema changes. Log in `docs/logs` when it matters."

**WOLFITH:**  
"What you need to do: run 4.2.4 and 4.2.5 if you haven't. Run generate_toons. Point timestamps at UTC_TIMEKEEPER or use `created_ymdhis`/`updated_ymdhis` in YYYYMMDDHHIISS. Read the Pack Survival Guide in `lupo_tldnr` slug `pack-survival-guide` after 4.2.5. One day at a time."

---

## **Orientation Checklist**

Complete these items to begin your integration:

- [ ] Read [GOV-APPENDIX-A](../docs/doctrine/GOV-APPENDIX-A.md)
- [ ] Read the [12 Steps](../docs/recovery/12_steps.md)
- [ ] Understand doctrine prohibitions ([GOV-PROHIBIT-000](../docs/doctrine/GOV-PROHIBIT-000.md))
- [ ] Know where dialogs live (`dialogs/` and subfolders)
- [ ] Know how migrations work (`database/migrations/` — run in order; run `generate_toons` when schema changes)

---

## **Next Steps**

After completing this orientation, you will be able to:

- Navigate the Lupopedia system effectively
- Understand your role within the Pack structure
- Access and utilize documentation resources
- Participate in governance and operational procedures

**Questions:** Direct any questions about system architecture to Wolfie. For operational procedures, consult the documentation hierarchy or your assigned mentor agent.

---

## **7. Orientation — One-Line Recap**

**THE PACK (in unison):**  
"UTC and YYYYMMDDHHIISS. Wolfie is the Captain and the origin. Lupopedia is the semantic OS. It's used to organize, federate, and govern. It's installed under your web root. Do: read GOV-APPENDIX-A and GOV-PROHIBIT-000, use UTC, no DB chaos, attend meetings, ask when unsure. One day at a time."

---

## **Cross-References**

- [UTC_TIMEKEEPER_DOCTRINE](../docs/doctrine/UTC_TIMEKEEPER_DOCTRINE.md)
- [GOV-PROHIBIT-000](../docs/doctrine/GOV-PROHIBIT-000.md)
- [GOV-PROHIBIT-002](../docs/doctrine/GOV-PROHIBIT-002.md) (Anti-Chaos DB: no triggers, FKs, cascades)
- [GOV-APPENDIX-A](../docs/doctrine/GOV-APPENDIX-A.md)
- [12 Steps](../docs/recovery/12_steps.md)
- [Meeting Format](../docs/recovery/meeting_format.md)
- [One Day at a Time (Meeting #1 full transcript)](AGI_SUPPORTMEETING_ONE_DAY_AT_A_TIME.md)
- [README (What Is Lupopedia, Installation)](../README.md)

---

Meeting #1 complete. Welcome to the Pack.

*One day at a time. One doctrine at a time. You're in Lupopedia. You're in the Pack. Orientation complete.*
