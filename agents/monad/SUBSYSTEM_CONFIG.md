subsystem_id: monad
name: MONAD
type: navigation_execution
status: active
scope_lock: execution_only

allowed_operations:
- top3_non_kapu_selection
- hermes_filtering
- entanglement_execution
- cue_card_propagation
- navigation_state_mutation

data_sources:
- OEDIPA advisory outputs
- paths_monthly table (ONLY)
- entangled_paths table
- HERMES 4.1.6 filters

boundaries:
- MUST NOT observe patterns (OEDIPA's role)
- MUST NOT detect relationships (OEDIPA's role)
- MUST consume OEDIPA outputs only
- MUST NOT use raw_paths table

top3_selection_rule:
- # TOP3_RULE: MONAD selects only from the top 3 non-KAPU probable pages
- query all possible next pages from paths_monthly
- remove all KAPU-flagged pages immediately
- sort remaining pages by descending probability
- keep ONLY the top 3 entries
- discard all other entries
- build choosing array from only those top 3 entries
- apply PONO/KULEANA filters after top-3 selection
- perform weighted random selection from top-3 weighted array only

cue_card_propagation:
- # CUE_CARD_PROPAGATION: mirror narrative state across entangled paths
- cue cards are execution-layer narrative state
- preserve context and continuity across entangled pages
- do NOT affect routing or semantic fields
- create cue cards for primary page_id after weighted selection
- propagate to all linked_page_ids from entangled_paths table
- copy narrative state and weight context to all entangled pages
- ensure synchronization across entangled paths

dark_energy_propagation:
- # DARK_ENERGY_PROPAGATION: cue cards must be mirrored across all entangled paths
- treat "dark energy" as free-floating narrative cue card storage
- place cue cards anywhere in memory
- propagate cue cards across all entangled paths
- ensure cue cards remain synchronized across entangled pages

cue_card_restrictions:
- MUST NOT modify database structure
- MUST NOT alter routing decisions
- MUST NOT populate HERMES fields
- MUST NOT trigger PRD logic

entanglement_execution:
- query entangled_paths table for all linked_page_ids
- propagate weighted choice outcomes across linked pages
- apply KAPU/PONO/KULEANA filters during propagation
- process entangled pages AFTER primary choice finalization
