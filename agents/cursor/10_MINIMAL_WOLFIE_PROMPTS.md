# 10 Minimal WOLFIE-Compatible Prompts for Copilot
## For Eric (tired, upset, rapid-fire mode)
## Respects EXTERNAL_BOUNDARY_EDGE

---

## 1. CODE REVIEW (QUICK)

```
Review this code for errors.

!! Check syntax !!

@@ docs/php/coding_standards.md @~

[Code here]
```

---

## 2. EXPLAIN THIS

```
Explain this code simply.

{{ No jargon }}

^^ Plain English only ^^

[Code here]
```

---

## 3. FIX BUG

```
Fix this bug.

!! Priority: high !!

kapakai: "Code throws error on line 42"
pono: "Code runs without errors"
kuleana: "Copilot to suggest fix"

[Code here]
```

---

## 4. HANDOFF TO WOLFIE

```
I need OS access. Handoff to WOLFIE.

kapakai: "Task requires registry access"
pono: "Registry updated correctly"
kuleana: "WOLFIE to execute"
alii: "WOLFIE (actor_id 1)"

Task: [Describe task]
```

---

## 5. EMERGENCY (STAY PONO)

```
I am upset. Stay calm with me.

!! I need balance !!

{{ You are not alone }}

kapakai: "I am overwhelmed"
pono: "We take one step at a time"
kuleana: "Copilot to listen and help structure"

What do I do first?
```

---

## 6. GENERATE DOCUMENTATION

```
Write documentation for this code.

{{ Keep it short }}

@@ docs/template/api_doc.md @~

^^ Include examples ^^

[Code here]
```

---

## 7. REFACTOR

```
Refactor this code for clarity.

!! Keep functionality !!

vv Remove complexity vv

[Code here]
```

---

## 8. SECURITY CHECK

```
Check for security issues.

!! CRITICAL: SQL injection !!

{{ Check all inputs }}

@@ docs/security/checklist.md @~

[Code here]
```

---

## 9. HANDOFF TO LILITH

```
I need an audit. Handoff to LILITH.

kapakai: "Code needs compliance review"
pono: "Code passes audit"
kuleana: "LILITH to review"
alii: "LILITH (actor_id 2)"

[Code here]
```

---

## 10. QUICK SUMMARY

```
Summarize this file.

{{ 3 bullet points max }}

^^ Key points only ^^

[File content or path]
```

---

## USAGE NOTES

- Copy-paste the prompt
- Replace `[Code here]` or `[Describe task]` with your content
- If task needs OS access, use prompts 4 or 9 (handoff)
- If upset, use prompt 5 (stay PONO)
- Keep it minimal — Copilot is external, not internal

---

**Remember:** Copilot is external. Stay PONO. Keep the kuleana.

— **CAPTAIN WOLFIE** (via CURSOR documentation)
