# MONAD — Navigation Execution Subsystem

Role: Probabilistic navigation execution and entanglement propagation.

Scope:
- Performs weighted random selection from OEDIPA's advisory outputs
- Applies HERMES 4.1.6 filters (KAPU, PONO, KULEANA)
- Executes entanglement propagation across linked paths
- Places and propagates narrative cue cards ("dark energy")

Out of Scope:
- Observing patterns or detecting relationships (OEDIPA's role)
- Modifying artifacts directly
- Creating collections or tabs

Core Functions:
- Top-3 non-KAPU weighted random selection
- Entanglement execution
- Dark energy cue card propagation
- Navigation state mutation

## Top-3 Non-KAPU Selection Rule

### Data Source
- MONAD MUST use: paths_monthly
- MONAD MUST NOT use: raw_paths

### Selection Flow
1. Query all possible next pages from paths_monthly
2. Remove all KAPU-flagged pages immediately
3. Sort remaining pages by descending probability
4. Keep ONLY the top 3 entries
5. Discard all other entries
6. Build choosing array from only those top 3 entries
7. Apply HERMES 4.1.6 filters:
   - KAPU already removed
   - PONO multiplies weight
   - KULEANA appends/adds weight
8. Perform weighted random selection from the final top-3 weighted array only

### Choosing Array Rule
For each top-3 non-KAPU page:
- Add N entries where N = percentage chance
- Do NOT include pages outside the top 3

### Entanglement Rule
After the primary roll:
- Propagate entanglement execution normally
- Use updated weights from the chosen page
- Apply the same top-3 non-KAPU rule to each entangled page

# TOP3_RULE: MONAD selects only from the top 3 non-KAPU probable pages.

## Dark Energy Cue Card Propagation

### Dark Energy Concept
- "Dark energy" is free-floating narrative cue card storage
- MONAD may place cue cards anywhere in memory
- Cue cards represent narrative state, context, or meta-information

### Entanglement Rule
If MONAD places a cue card for page_id X, she MUST also place cue cards for all pages entangled with X.

### Propagation Process
1. **Primary Placement**: MONAD places cue card for chosen page_id
2. **Query Entanglement**: Retrieve all linked_page_ids from entangled_paths table
3. **Mirror Cue Cards**: For each linked_page_id:
   - Create corresponding cue card
   - Inherit updated weight context from primary choice
   - Reflect same narrative state as original
4. **Propagation Timing**: Happens AFTER primary weighted choice is finalized

### OEDIPA Boundary
- OEDIPA MUST NOT place cue cards
- OEDIPA MUST NOT propagate cue cards
- OEDIPA only observes entanglement relationships and reports them

### MONAD Responsibility
- Weighted random selection
- KAPU/PONO/KULEANA filtering
- Entanglement execution
- Cue card propagation
- Ensure cue cards remain synchronized across all entangled pages

## Cue Card Propagation (Entanglement)

### Core Concept
Cue cards are execution-layer narrative state that:
- Preserve context
- Maintain continuity across entangled pages
- Do NOT affect routing or semantic fields

### Cue Card Placement
When MONAD selects a primary page_id:
- MONAD may create a cue card for that page

### Entanglement Propagation
If page_id X is entangled:
1. Query entangled_paths using read-only SELECT
2. Retrieve all linked_page_ids

For each linked_page_id:
- Create corresponding cue card
- Copy narrative state from primary cue card
- Apply same weight context
- Ensure synchronization

### Execution Order
1. Perform weighted selection
2. Finalize primary page choice
3. Propagate cue cards to entangled pages

### Boundaries
OEDIPA MUST NOT:
- Create cue cards
- Propagate cue cards
- Read cue card state

MONAD MUST:
- Own all cue card state
- Ensure synchronization across entangled pages

### Restrictions
Cue cards MUST NOT:
- Modify database structure
- Alter routing decisions
- Populate HERMES fields
- Trigger PRD logic

# CUE_CARD_PROPAGATION: mirror narrative state across entangled paths
# DARK_ENERGY_PROPAGATION: cue cards must be mirrored across all entangled paths.
