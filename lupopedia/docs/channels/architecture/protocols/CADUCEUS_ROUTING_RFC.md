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
  message: "Created RFC 4003 v4.0.1 - CADUCEUS Routing Currents Standard: Defines deterministic, federation-safe mood-to-routing signal conversion with precision guarantees, void handling, skew detection, and performance optimization."
  mood: "00FF00"
tags:
  categories: ["documentation", "specification", "rfc", "standards", "routing", "caduceus"]
  collections: ["core-docs", "protocols"]
  channels: ["dev", "standards"]
file:
  title: "RFC 4003 â€” CADUCEUS Emotional Balancing Standard"
  description: "Formal RFC specification for CADUCEUS emotional balancer that computes channel mood from polar agent moods in Lupopedia Semantic OS"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: "Eric Robin Gerdes (Wolfie)"
---

# **RFC 4003 â€” CADUCEUS Emotional Balancing Standard**  
**Lupopedia Semantic OS â€” Request for Comments**  
**Category:** Standards Track  
**Version:** 4.0.1  
**Updated:** 2026â€‘01â€‘08  
**Author:** Eric Robin Gerdes ("Wolfie")  
**Part of:** Lupopedia 4.0.1 Standards Track

---

## **Status of This Memo**
This document defines the **CADUCEUS Emotional Balancing Standard** for Lupopedia 4.0.1. It specifies how CADUCEUS computes channel emotional current by reading and blending the moods of polar agents within a channel. CADUCEUS produces normalized emotional currents (`left`, `right`) that HERMES may optionally use as context for routing decisions. CADUCEUS is a pure function with no side effects, ensuring portable, federationâ€‘safe emotional context computation across all nodes and runtimes.

Distribution of this memo is unlimited.

---

## **KISS Summary (For Humans)**

Most conversations are one human talking to one AI.  
Agent classification and routing only matter when multiple agents are active.  
If only one agent is present, HERMES bypasses routing and delivers the message normally.

---

## **1. CADUCEUS Overview**

CADUCEUS is not a routing layer or a message dispatcher. CADUCEUS is the emotional balancer for a channel.

A channel is a shared context where humans and agents collaborate on a task (for example, channel 42 with LILITH, WOLFIE, and a human participant). Within that channel, CADUCEUS identifies the two polar agents (the "serpents" at the wing tips) and computes a blended emotional current between them.

In practical terms, CADUCEUS:
- identifies the two opposite-pole agents on the channel (for example, LILITH and WOLFIE)
- reads their current mood and emotional stance
- computes an averaged or blended emotional current for the channel
- exposes that current as context for other subsystems (especially HERMES)

CADUCEUS does not:
- decide where messages are routed
- select which agent receives a message
- perform queueing, delivery, or dispatch

Those responsibilities belong to HERMES. CADUCEUS exists to provide an emotionally balanced "channel mood" that other systems can use to make context-aware decisions.

---

## **2. Terminology**

- **CADUCEUS** â€” The emotional balancer for channels (`class-caduceus.php`). Computes channel mood by reading and blending the emotional states of polar agents.  
- **Countingâ€‘inâ€‘Light** â€” The emotional coordinate system using RGB values (R=Strife, G=Harmony, B=Memory).  
- **Mood RGB** â€” A sixâ€‘digit hex string (RRGGBB) encoding emotional metadata.  
- **Emotional Current** â€” A normalized float (0.0â€“1.0) representing channel emotional state, computed by CADUCEUS from polar agent moods.  
- **Left Current** â€” Analytical/structured routing bias.  
- **Right Current** â€” Creative/emotional routing bias.  
- **Neutral Mood** â€” `666666`, representing balanced midâ€‘range values.  
- **Void Mood** â€” `000000`, representing a void/noâ€‘signal state.  
- **Invalid Mood** â€” Any value that fails hex validation.  
- **Epsilon** â€” Precision threshold for float comparisons (1eâ€‘6).  
- **Invariant** â€” Mathematical constraint that must always hold (`left + right = 1.0`).  

---

## **3. Design Principles**

CADUCEUS **MUST**:

1. **Remain a pure function**  
   No side effects, no database access, no state storage.

2. **Be deterministic**  
   Same inputs **MUST** produce same outputs across all runtimes.

3. **Preserve precision**  
   Floatingâ€‘point operations **MUST** maintain invariant constraints.

4. **Handle invalid input gracefully**  
   Invalid or void moods **MUST** default to neutral without breaking routing.

5. **Support federation**  
   Results **MUST** be identical across PHP versions, Python implementations, and nodes.

6. **Be observable**  
   Skew detection **MUST** be possible via logging and metrics.

7. **Remain fast**  
   Computation **MUST** be subâ€‘millisecond with optional memoization.

---

## **4. Channel Routing Mode (First Check)**

Before routing, HERMES **MUST** read `channels.metadata_json` and extract:

- `routing_mode` (string) â€” determines how messages are routed in this channel

**Valid values:**
- `"none"` â€” no routing; direct addressing only (Crafty Syntax mode)
- `"hermes"` â€” full emotional routing (default)
- `"operator"` â€” one agent sees all messages; others see only addressed messages
- `"broadcast"` â€” all agents see all messages; no routing

**Critical Rule:** If `routing_mode != "hermes"`, HERMES **MUST** bypass CADUCEUS and classification logic entirely. Routing mode takes precedence over all other routing decisions.

---

## **4.1 Channel Intent (Primary Routing Filter) â€” Only if routing_mode == "hermes"**

If `routing_mode == "hermes"`, HERMES **MUST** read `channels.metadata_json` and extract:

- `channel_intent` (string)
- `allowed_roles` (array, optional)
- `disallowed_roles` (array, optional)

HERMES **MUST** filter candidate agents based on channel intent **BEFORE** applying CADUCEUS currents.  
Channel intent is the **primary routing constraint**; moodâ€‘based routing is **secondary**.

If a pool becomes empty after filtering:
1. Retry with the opposite pool.
2. If both pools empty â†’ fallback to System Agent 0.
3. If System Agent 0 unavailable â†’ return `ROUTING_FAILURE`.

---

## **5. Axis Binding Rationale**

### **5.1 RGB Axis Semantics**

Countingâ€‘inâ€‘Light defines three axes:

- **R (Red)** â€” Strife / intensity / chaos  
- **G (Green)** â€” Harmony / balance / cohesion  
- **B (Blue)** â€” Memory depth / introspection / persistence  

### **5.2 Current Computation Philosophy**

CADUCEUS binds RGB components to emotional currents as follows:

- **Left Current (Analytical)** â€” `(G + B) / (G + B + R + B)`  
  - Harmony + Memory Depth â†’ Stabilizing  
  - High G = stabilizing influence  
  - High B = introspective depth  
  - Together: analytical, structured, reflective reasoning  

- **Right Current (Creative)** â€” `(R + B) / (G + B + R + B)`  
  - Strife + Memory Depth â†’ Scrutiny  
  - High R = intensity / conflict pressure  
  - High B = depth of meaning  
  - Together: creative, emotional, intensityâ€‘driven reasoning  

**Critical Insight:** Memory (B) amplifies both currents. High B means depth matters regardless of analytical vs creative bias. This ensures that deeply meaningful messages receive appropriate routing weight.

### **5.3 Cultural and Domain Flexibility**

HERMES **MAY** swap `left` and `right` pools per node configuration. This allows:

- domainâ€‘specific routing preferences  
- cultural bias adjustments  
- organizational workflow customization  

**CADUCEUS does not care which pool is "analytical" vs "creative".** It only computes two normalized currents. HERMES interprets them based on node configuration.

This prevents cultural drift and domain bias from hardcoding routing decisions.

---

## **6. Conversion Algorithm**

### **6.1 Input Validation**

1. **Extract `mood_rgb`** from input (default: `'666666'` if missing).  
2. **Sanitize:** `strtoupper(trim($moodRgb))`.  
3. **Validate:** Must match pattern `^[0-9A-F]{6}$`.  
4. **If invalid:** Substitute `'666666'` (neutral) before computation.  

**Critical Rule:** Invalid input **MUST NOT** break routing. Always substitute neutral before math.

### **6.2 RGB Component Extraction**

```php
$r = hexdec(substr($moodRgb, 0, 2)); // Strife (0-255)
$g = hexdec(substr($moodRgb, 2, 2)); // Harmony (0-255)
$b = hexdec(substr($moodRgb, 4, 2)); // Memory (0-255)
```

### **6.3 Raw Current Computation**

```php
$leftRaw  = $g + $b;  // Harmony + Memory
$rightRaw = $r + $b;  // Strife + Memory
```

### **6.4 Normalization with Precision Guarantees**

```php
$sum = max($leftRaw + $rightRaw, 1); // Avoid division by zero

$left  = $leftRaw  / $sum;
$right = $rightRaw / $sum;

// Enforce invariant: left + right = 1.0
$epsilon = 1e-6;
$total = $left + $right;

if (abs($total - 1.0) > $epsilon) {
    // Normalize to enforce invariant
    $left  = $left  / $total;
    $right = $right / $total;
}
```

**Precision Requirements:**

- Epsilon = `1e-6` (one millionth)  
- Invariant **MUST** be enforced: `abs(left + right - 1.0) <= epsilon`  
- No raw float comparisons without epsilon  
- Normalization **MUST** occur if invariant is violated  

**Why This Matters:**

- PHP 8.1 vs 8.3 may produce slightly different float results  
- Python vs PHP may differ in intermediate calculations  
- Federation requires identical outputs across nodes (nodes are domain installations, not agents)  
- Precision guarantees prevent routing drift  

### **6.5 Void and Invalid Handling**

**Unified Doctrine:**

- **Invalid Mood** (fails validation) â†’ substitute `'666666'` (neutral)  
- **Void Mood** (`'000000'`) â†’ substitute `'666666'` (neutral)  
- **Substitution occurs BEFORE computation**  

This ensures:

- single code path (no dual logic)  
- consistent behavior across nodes  
- deterministic routing even with invalid input  
- no undefined routing states  

**Example:**

```php
// Invalid: "FFGG00" â†’ "666666"
// Void: "000000" â†’ "666666"
// Both produce: left = 0.5, right = 0.5 (neutral routing)
```

---

## **7. Return Value Structure**

CADUCEUS returns:

```php
[
    'left'  => float,  // Analytical bias (0.0-1.0)
    'right' => float   // Creative bias (0.0-1.0)
]
```

**Constraints:**

- `left + right = 1.0` (within epsilon)  
- `0.0 <= left <= 1.0`  
- `0.0 <= right <= 1.0`  

---

## **8. PHP Reference Implementation**

```php
class Caduceus
{
    const EPSILON = 1e-6;
    const NEUTRAL_MOOD = '666666';

    /**
     * Convert mood_rgb (RRGGBB) into routing currents.
     *
     * @param string $moodRgb 6-char hex string (e.g. "88FF88")
     * @return array ['left' => float, 'right' => float]
     */
    public static function computeCurrents(string $moodRgb): array
    {
        // Sanitize and validate
        $moodRgb = strtoupper(trim($moodRgb));
        if (!preg_match('/^[0-9A-F]{6}$/', $moodRgb)) {
            $moodRgb = self::NEUTRAL_MOOD;
        }

        // Handle void mood
        if ($moodRgb === '000000') {
            $moodRgb = self::NEUTRAL_MOOD;
        }

        // Extract RGB components
        $r = hexdec(substr($moodRgb, 0, 2));
        $g = hexdec(substr($moodRgb, 2, 2));
        $b = hexdec(substr($moodRgb, 4, 2));

        // Compute raw currents
        $leftRaw  = $g + $b;
        $rightRaw = $r + $b;

        // Avoid division by zero
        $sum = max($leftRaw + $rightRaw, 1);

        // Normalize
        $left  = $leftRaw  / $sum;
        $right = $rightRaw / $sum;

        // Enforce invariant with precision
        $total = $left + $right;
        if (abs($total - 1.0) > self::EPSILON) {
            $left  = $left  / $total;
            $right = $right / $total;
        }

        return [
            'left'  => $left,
            'right' => $right
        ];
    }
}
```

This implementation is doctrineâ€‘aligned, portable, and federationâ€‘safe.

---

## **9. Determinism and Portability**

### **9.1 Crossâ€‘Runtime Consistency**

CADUCEUS **MUST** produce identical results across:

- PHP 8.1, 8.2, 8.3+  
- Python 3.8+ (if ported)  
- Node.js (if ported)  
- Any future runtime  

**Achieved via:**

- epsilonâ€‘based invariant enforcement  
- explicit normalization step  
- no languageâ€‘specific float defaults  
- deterministic hexdec() behavior  

### **9.2 Federation Safety**

Results **MUST** be identical across nodes:

- Node A: `mood_rgb = 'FF0000'` â†’ `left = 0.0, right = 1.0`  
- Node B: `mood_rgb = 'FF0000'` â†’ `left = 0.0, right = 1.0`  

No crossâ€‘node drift, no routing divergence.

---

## **10. Skew Detection and Observability**

### **10.1 Skew Detection Thresholds**

CADUCEUS **MAY** log metrics for skew detection:

- **Extreme Currents:** `left < 0.1` or `right < 0.1` (imbalanced routing)  
- **Invalid Input Rate:** Frequency of invalid mood substitution  
- **Void Input Rate:** Frequency of void mood substitution  
- **Invariant Violations:** Frequency of normalization corrections  

**Integration with THEMIS:**

- Skew metrics **MAY** be sent to THEMIS for governance  
- Agentâ€‘level skew detection **MAY** flag routing manipulation  
- Repeated invalid mood submission **MAY** trigger governance review  

### **10.2 Observability Hooks**

Implementations **MAY** expose:

- computation time (microseconds)  
- input validation failures  
- invariant corrections  
- memoization hit rate (if enabled)  

**MUST NOT** break pure function contract (logging is acceptable).

---

## **11. Performance Considerations**

### **11.1 Statelessness**

CADUCEUS **MUST** remain stateless:

- No global caches  
- No database access  
- No file I/O  
- No network calls  

Pure function guarantees subâ€‘millisecond computation.

### **11.2 Memoization (Optional)**

Implementations **MAY** memoize results for performance:

- Key: `mood_rgb` string  
- Value: `['left' => float, 'right' => float]`  
- TTL: Ephemeral (requestâ€‘scope only)  

**Critical Rules:**

- Memoization **MUST** be ephemeral (no persistent state)  
- Memoization **MUST NOT** violate pure function contract  
- Cache **MUST** be cleared between requests  
- Memoization **MUST NOT** leak across nodes  

### **11.3 Performance Targets**

- **Computation Time:** < 0.1ms per call (without memoization)  
- **Memory Usage:** < 1KB per call  
- **Scalability:** Linear with no degradation up to 10,000 calls/second  

---

## **12. Integration with HERMES**

CADUCEUS is called by HERMES during message routing:

```php
$currents = Caduceus::computeCurrents($moodRgb);

if ($currents['left'] > $currents['right']) {
    // Route to analytical pool
} else {
    // Route to creative pool
}
```

**Critical Rule:** HERMES **MUST** handle tie conditions (`left == right`) via tiebreaker rules (see RFC 4004).

---

## **13. Validation Rules**

A valid CADUCEUS computation:

- **MUST** accept any string input (validate and substitute if needed)  
- **MUST** return valid floats in range [0.0, 1.0]  
- **MUST** enforce invariant: `abs(left + right - 1.0) <= epsilon`  
- **MUST** be deterministic (same input â†’ same output)  
- **MUST** complete in < 1ms  

---

## **14. Security Considerations**

- Invalid input **MUST NOT** break routing (graceful degradation).  
- Malicious agents attempting to skew routing via mood manipulation **MUST** be detectable via THEMIS skew metrics.  
- Computation **MUST NOT** leak crossâ€‘node emotional metadata.  
- Memoization (if enabled) **MUST** be ephemeral and nodeâ€‘isolated.  

---

## **15. Versioning**

This RFC defines **CADUCEUS Emotional Balancing v4.0.1**, aligned with:

- RFC 4000 â€” WOLFIE Headers v4.0.1  
- RFC 4002 â€” Thread Mood Aggregation v4.0.1  
- RFC 4004 â€” HERMES Routing Layer v4.0.1  
- Countingâ€‘inâ€‘Light v4.0.1  
- Lupopedia Schema v4.0.1  

**Version History:**

- **v4.0.0:** Initial specification  
- **v4.0.1:** Added precision guarantees, void handling, skew detection, performance considerations

Future versions **MUST** remain backward compatible unless superseded by a new RFC.

---

## **16. References**

- **[RFC 4000](WOLFIE_HEADER_RFC.md)** â€” WOLFIE Header Standard  
- **[RFC 4002](THREAD_MOOD_RFC.md)** â€” Thread Mood Aggregation Standard  
- **[RFC 4004](HERMES_ROUTING_RFC.md)** â€” HERMES Routing Layer Standard  
- **[MOOD_RGB_DOCTRINE.md](../../doctrine/MOOD_RGB_DOCTRINE.md)** â€” MOOD_RGB Doctrine  
- **[COUNTING_IN_LIGHT.md](../../appendix/appendix/COUNTING_IN_LIGHT.md)** â€” Countingâ€‘inâ€‘Light Specification  
- **[ARCHITECTURE_SYNC.md](../ARCHITECTURE_SYNC.md)** â€” CADUCEUS and HERMES Architecture  

---

## **17. Author's Address**

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
