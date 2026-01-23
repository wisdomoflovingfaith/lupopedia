---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
updated: 2026-01-08
author: Wolfie (Eric Robin Gerdes)
dialog:
  speaker: cursor
  target: documentation
  message: "Created RFC 4002 - Thread Mood Aggregation Standard: Defines canonical method for computing thread-level mood from individual message moods using Counting-in-Light RGB averaging."
  mood: "00FF00"
tags:
  categories: ["documentation", "specification", "rfc", "standards", "mood", "routing"]
  collections: ["core-docs", "protocols"]
  channels: ["dev", "standards"]
file:
  title: "RFC 4002 â€” Thread Mood Aggregation Standard"
  description: "Formal RFC specification for aggregating dialog message moods into thread-level mood in Lupopedia Semantic OS"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: "Eric Robin Gerdes (Wolfie)"
---

# **RFC 4002 â€” Thread Mood Aggregation Standard**  
**Lupopedia Semantic OS â€” Request for Comments**  
**Category:** Standards Track  
**Version:** 4.0.1  
**Updated:** 2026â€‘01â€‘08  
**Author:** Eric Robin Gerdes ("Wolfie")  
**Part of:** Lupopedia 4.0.1 Standards Track

---

## **Status of This Memo**
This document defines the **Thread Mood Aggregation Standard** for Lupopedia 4.0.1. It specifies how individual dialog message moods (`mood_rgb`) are aggregated into a single **threadâ€‘level mood**, enabling emotional continuity, analytics, and routing context across multiâ€‘agent conversations.

Distribution of this memo is unlimited.

---

## **KISS Summary (For Humans)**

Most conversations are one human talking to one AI.  
Agent classification and routing only matter when multiple agents are active.  
If only one agent is present, HERMES bypasses routing and delivers the message normally.

---

## **1. Introduction**
Lupopedia's dialog system stores emotional metadata for each message using the **Countingâ€‘inâ€‘Light** coordinate system (`mood_rgb`). While messageâ€‘level mood captures the emotional geometry of individual interactions, many system behaviors require a **threadâ€‘level mood**, including:

- conversation analytics  
- agent routing context  
- UI indicators  
- emotional continuity across long threads  
- multiâ€‘agent coordination  

This RFC defines the canonical method for computing a thread's aggregate mood.

---

## **2. Terminology**

- **Thread** â€” A sequence of dialog messages sharing a `conversation_id`.  
- **Message Mood** â€” A 6â€‘digit hex RGB value stored in `dialog_messages.mood_rgb`.  
- **Neutral Mood** â€” `666666`, representing balanced midâ€‘range values.  
- **Zero Mood** â€” `000000`, representing a void/noâ€‘signal state.  
- **Aggregation** â€” The process of averaging RGB components across messages.  

---

## **3. Design Principles**

Thread mood aggregation **MUST**:

1. **Preserve emotional continuity**  
   Neutral messages must contribute balanced weight.

2. **Avoid zeroâ€‘collapse**  
   `000000` **MUST NOT** be treated as neutral.

3. **Remain deterministic**  
   Same inputs **MUST** produce same outputs.

4. **Remain applicationâ€‘layer only**  
   No DB views, triggers, or stored procedures.

5. **Support future analytics**  
   Thread mood **MUST** be stable across agents and nodes.

---

## **4. Message Mood Semantics**

### **4.1 Neutral Mood (`666666`)**
Represents balanced midâ€‘range values across all axes:

- R = 102  
- G = 102  
- B = 102  

Neutral **MUST** be used as:

- the default for missing moods  
- the fallback for invalid moods  
- the baseline for aggregation  

### **4.2 Zero Mood (`000000`)**
Represents a **void** or **noâ€‘signal** state.

Rules:

- **MUST NOT** be used as a default  
- **MAY** be ignored during aggregation  
- **MUST** be treated as neutral for routing  
- **MUST NOT** be interpreted as emotional neutrality  

---

## **5. Aggregation Algorithm**

Thread mood is computed by averaging the RGB components of all valid message moods.

### **5.1 Steps**

1. **Fetch all moods** for the thread from `dialog_messages`.  
2. **Replace null/invalid moods** with `'666666'`.  
3. **Optionally skip** `'000000'` (void) moods.  
4. Convert each hex mood to decimal RGB components.  
5. Sum R, G, and B across all messages.  
6. Divide each sum by the number of included messages.  
7. Round to nearest integer (banker's rounding recommended).  
8. Convert averaged RGB back to a 6â€‘digit hex string.  

### **5.2 Example**

Given 10 messages:

- 4 Ã— `00FF00`  
- 6 Ã— `666666`  

Result:

```
33B233
```

Interpretation:

- Low strife  
- High harmony  
- Low memory depth  

---

## **6. PHP Reference Implementation**

```php
$moods = $db->query(
    "SELECT mood_rgb FROM dialog_messages WHERE conversation_id = ?"
)->fetchAll();

$r_sum = $g_sum = $b_sum = 0;
$count = count($moods);

foreach ($moods as $row) {
    $mood = $row['mood_rgb'] ?? '666666';

    // Optional: skip void mood
    if ($mood === '000000') continue;

    $r = hexdec(substr($mood, 0, 2));
    $g = hexdec(substr($mood, 2, 2));
    $b = hexdec(substr($mood, 4, 2));

    $r_sum += $r;
    $g_sum += $g;
    $b_sum += $b;
}

$avg_r = round($r_sum / $count);
$avg_g = round($g_sum / $count);
$avg_b = round($b_sum / $count);

$thread_mood = sprintf("%02X%02X%02X", $avg_r, $avg_g, $avg_b);
```

This implementation is doctrineâ€‘aligned and portable.

---

## **7. Routing Integration**

Thread mood **MAY** be used by:

- **CADUCEUS** to adjust routing bias  
- **HERMES** to select analytical vs creative agents  
- **UI layers** to display threadâ€‘level emotional indicators  

Thread mood **MUST NOT** override messageâ€‘level routing unless explicitly configured.

---

## **8. Validation Rules**

A valid thread mood:

- **MUST** be a 6â€‘digit hex value  
- **MUST** be derived from valid message moods  
- **MUST** treat invalid moods as neutral  
- **MUST** treat zero moods as void  
- **MUST** be deterministic  

---

## **9. Security Considerations**

- Invalid moods **MUST NOT** break aggregation.  
- Malicious agents attempting to skew thread mood **MUST** be detectable via THEMIS.  
- Aggregation **MUST** not leak crossâ€‘node emotional metadata.  

---

## **10. Versioning**

This RFC defines **Thread Mood Aggregation v4.0.1**, aligned with:

- Countingâ€‘inâ€‘Light v4.0.1  
- WOLFIE Headers v4.0.1  
- Lupopedia Schema v4.0.1  

Future versions **MUST** remain backward compatible unless superseded by a new RFC.

---

## **11. References**

- **[RFC 4000](WOLFIE_HEADER_RFC.md)** â€” WOLFIE Header Standard  
- **[COUNTING_IN_LIGHT.md](../../appendix/appendix/COUNTING_IN_LIGHT.md)** â€” Countingâ€‘inâ€‘Light Specification  
- **[MOOD_RGB_DOCTRINE.md](../../doctrine/MOOD_RGB_DOCTRINE.md)** â€” MOOD_RGB Doctrine  
- **[ARCHITECTURE_SYNC.md](../ARCHITECTURE_SYNC.md)** â€” CADUCEUS Emotional Balancing and HERMES Routing Layer  

---

## **12. Author's Address**

Eric Robin Gerdes  
Lupopedia Architect  
Sioux Falls, South Dakota  
United States  

---

*Last Updated: January 2026*  
*Version: 4.0.1*  
*Category: Standards Track*  
*Status: Published*  
*Part of: Lupopedia 4.0.1 Standards Track*
