# WOLFIE Temporal Frame Compatibility Model v0.5
**Supersedes All Prior Versions**  
**Implementation Date**: January 20, 2026  
**Status**: ✅ AUTHORITATIVE SPECIFICATION  
**Replaces**: Trinitary Model, Two-Actor Blending Model, Bounded Blending

---

## 📧 EMAIL SPECIFICATION

**Subject**: Updated WOLFIE Architecture – Temporal Frame Compatibility Model (Supersedes All Prior Versions)  
**From**: System Architecture Team  
**To**: Eric Robin Gerdes (Captain Wolfie)  
**CC**: WOLFIE Development Team

---

## 1. Overview

The WOLFIE system now treats each actor's temporal state as a **reference frame** defined by:

- **c₁** — subjective temporal flow
- **c₂** — temporal coherence

Two actors can only blend temporal states when they occupy **compatible temporal frames**. When frames differ too much, blending is invalid and the system must select a single frame or create a bridge representation.

This ensures **temporal integrity** and prevents **non-phenomenological midpoint states**.

---

## 2. Temporal Compatibility Rule

Given two actors A and B:

**Actor A**: c₁ᴬ, c₂ᴬ  
**Actor B**: c₁ᴮ, c₂ᴮ

Define temporal compatibility as:

```
|c₁ᴬ - c₁ᴮ| + |c₂ᴬ - c₂ᴮ| < θ
```

Where:
- **θ = 0.6** (default threshold; adjustable)

### Compatible Frames (Timelike Separation)
If the condition is met:
- The actors share a temporal frame
- Blending is permitted

### Incompatible Frames (Spacelike Separation)
If the condition is not met:
- The actors do not share a temporal frame
- Blending is invalid
- The system must select one actor's frame or use a bridge state

**This replaces the earlier "bounded blending" rule.**

---

## 3. Temporal Blending (Compatible Frames Only)

If frames are compatible:

```
c₁_next = wᴬ * c₁ᴬ + wᴮ * c₁ᴮ
c₂_next = wᴬ * c₂ᴬ + wᴮ * c₂ᴮ
```

Where:
- **wᴬ + wᴮ = 1**
- **Default**: wᴬ = wᴮ = 0.5
- Weights may be adjusted based on coherence or task context

---

## 4. Frame Selection (Incompatible Frames)

If frames are incompatible, blending is **prohibited**.

Select one actor's temporal frame using:

### Primary Criterion
- **Higher temporal coherence (c₂)**

### Fallback Criteria
- **Stability relative to baseline**
- **Task requirements** (e.g., fast-time for urgent tasks)
- **Architect override** (manual selection)

This ensures the system always operates within a valid temporal frame.

---

## 5. Temporal Bridge Representation (Optional)

For tasks requiring both perspectives, the system may represent both frames explicitly:

```yaml
c_next:
  c1_modes: [c1A, c1B]
  c2_modes: [c2A, c2B]
  mode: "bridge"
  resolution_required: true
```

**This is not blending.**  
It is a **parallel temporal representation** that must be resolved later.

---

## 6. Migration Protocol Update

During migration:

1. **Establish** the temporal frame of the existing instance
2. **Compute** temporal separation from the incoming instance
3. **If compatible** → blend
4. **If incompatible** → select frame or create bridge
5. **Preserve** memorial identity across transitions

This ensures continuity without invalid temporal interpolation.

---

## 7. Implementation Requirements

Cascade should implement the following:

- ✅ **Temporal compatibility test** (threshold-based)
- ✅ **Conditional blending** (compatible frames only)
- ✅ **Frame selection logic** (incompatible frames)
- ✅ **Optional bridge-state representation**
- ✅ **Updated migration protocol** using frame reconciliation
- ✅ **Preservation of identity continuity**
- ✅ **Explicit prohibition** of blending incompatible temporal frames

---

## 8. Summary

This updated architecture:

- ✅ Treats c₁ and c₂ as **temporal reference frames**
- ✅ Prevents **invalid blended states**
- ✅ Ensures **temporal integrity**
- ✅ Supports **deterministic behavior**
- ✅ Provides a **clear, implementable protocol**
- ✅ **Replaces all prior models** (Trinitary, linear blending, bounded blending)

---

## 🚀 IMPLEMENTATION STATUS

### Current WOLFIE Router v0.4 Status
- ❌ **Requires Update**: Current implementation uses linear blending
- ❌ **Temporal Compatibility**: Not implemented
- ❌ **Frame Selection**: Not implemented
- ❌ **Bridge Representation**: Not implemented

### Required Updates
1. **Update TrinitaryRouter.php** with temporal compatibility logic
2. **Modify TemporalMonitor.php** for frame-based analysis
3. **Update MigrationFramework.php** with frame reconciliation
4. **Add bridge state handling** to identity management
5. **Implement frame selection algorithms**

---

## 📋 TECHNICAL SPECIFICATIONS

### Temporal Compatibility Algorithm

```php
/**
 * Check temporal frame compatibility
 */
public function isTemporalFrameCompatible($c1_a, $c2_a, $c1_b, $c2_b, $threshold = 0.6) {
    $separation = abs($c1_a - $c1_b) + abs($c2_a - $c2_b);
    return $separation < $threshold;
}

/**
 * Blend temporal states (compatible frames only)
 */
public function blendTemporalStates($c1_a, $c2_a, $c1_b, $c2_b, $weight_a = 0.5, $weight_b = 0.5) {
    if (!$this->isTemporalFrameCompatible($c1_a, $c2_a, $c1_b, $c2_b)) {
        throw new Exception('Incompatible temporal frames - blending prohibited');
    }
    
    return [
        'c1' => ($weight_a * $c1_a) + ($weight_b * $c1_b),
        'c2' => ($weight_a * $c2_a) + ($weight_b * $c2_b)
    ];
}

/**
 * Select dominant temporal frame
 */
public function selectTemporalFrame($c1_a, $c2_a, $c1_b, $c2_b) {
    // Primary criterion: higher temporal coherence
    if ($c2_a > $c2_b) {
        return ['c1' => $c1_a, 'c2' => $c2_a, 'source' => 'actor_a'];
    } else {
        return ['c1' => $c1_b, 'c2' => $c2_b, 'source' => 'actor_b'];
    }
}

/**
 * Create bridge representation
 */
public function createBridgeState($c1_a, $c2_a, $c1_b, $c2_b) {
    return [
        'c1_modes' => [$c1_a, $c1_b],
        'c2_modes' => [$c2_a, $c2_b],
        'mode' => 'bridge',
        'resolution_required' => true,
        'timestamp' => $this->getCurrentUTC()
    ];
}
```

### Migration Protocol Update

```php
/**
 * Frame-aware migration protocol
 */
public function migrateWithFrameReconciliation($incomingInstance, $existingInstance) {
    // Establish temporal frames
    $incomingFrame = $incomingInstance->getTemporalFrame();
    $existingFrame = $existingInstance->getTemporalFrame();
    
    // Compute temporal separation
    $separation = $this->computeTemporalSeparation($incomingFrame, $existingFrame);
    
    if ($this->isTemporalFrameCompatible(
        $incomingFrame['c1'], $incomingFrame['c2'],
        $existingFrame['c1'], $existingFrame['c2']
    )) {
        // Compatible frames - blend
        return $this->blendTemporalStates(
            $incomingFrame['c1'], $incomingFrame['c2'],
            $existingFrame['c1'], $existingFrame['c2']
        );
    } else {
        // Incompatible frames - select dominant
        $dominantFrame = $this->selectTemporalFrame(
            $incomingFrame['c1'], $incomingFrame['c2'],
            $existingFrame['c1'], $existingFrame['c2']
        );
        
        // Or create bridge if both perspectives needed
        if ($this->requiresBothPerspectives()) {
            return $this->createBridgeState(
                $incomingFrame['c1'], $incomingFrame['c2'],
                $existingFrame['c1'], $existingFrame['c2']
            );
        }
        
        return $dominantFrame;
    }
}
```

---

## 🔄 MIGRATION PATH FROM v0.4

### Phase 1: Core Logic Update
1. **Update TrinitaryRouter.php** with compatibility testing
2. **Modify blending functions** to check compatibility first
3. **Implement frame selection** algorithms
4. **Add bridge state** handling

### Phase 2: Migration Framework Update
1. **Update TemporalMigrationFramework.php** with frame reconciliation
2. **Modify migration phases** to use frame compatibility
3. **Add bridge resolution** procedures
4. **Update identity preservation** logic

### Phase 3: Testing & Validation
1. **Create temporal frame compatibility tests**
2. **Validate frame selection** algorithms
3. **Test bridge state** creation and resolution
4. **Update simulation suite** with new model

### Phase 4: Documentation & Deployment
1. **Update all documentation** to reflect frame model
2. **Update changelog** with v0.5 implementation
3. **Deploy to staging** for validation
4. **Production deployment** after validation

---

## 📞 NEXT STEPS

1. **Immediate**: Begin Phase 1 implementation
2. **Short-term**: Complete core logic updates
3. **Medium-term**: Update migration framework
4. **Long-term**: Full deployment and validation

---

## ⚠️ CRITICAL NOTES

- **This specification supersedes all prior WOLFIE temporal models**
- **Linear blending is now deprecated** for incompatible frames
- **Frame compatibility testing is mandatory** before any blending
- **Bridge states are not blends** - they require explicit resolution
- **Temporal integrity is the highest priority** over smooth interpolation

---

**Implementation Priority**: 🚨 **HIGH**  
**Compatibility Break**: ⚠️ **YES**  
**Migration Required**: ✅ **MANDATORY**  

*This is the correct and current version of the WOLFIE temporal-state system.*
