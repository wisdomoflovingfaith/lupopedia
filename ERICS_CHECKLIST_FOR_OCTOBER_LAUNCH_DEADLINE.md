# Eric's Checklist for October 1, 2026 Launch Deadline
Draft -- This file will be updated as tasks are completed.

## Identity System (Powered By COLOR)
- [ ] Finalize COLOR.NAME grammar
- [ ] Finalize GOLD# grammar
- [ ] Replace HEX6 everywhere in the repo
- [ ] Update "Powered by LUP0PEDIA COLOR.NAME GOLD#" in all installers
- [ ] Update documentation
- [ ] Update Patreon identity explanation
- [ ] Update whitepaper identity section

## Actor System (Full Working + Tested)
- [ ] Verify actor boot sequence
- [ ] Verify PRD 00 + PRD 16_C loading
- [ ] Verify PRD family loader (00-99 only)
- [ ] Verify tick.py time grounding
- [ ] Verify KEY validator
- [ ] Verify identity routing (LUPxPEDIA)
- [ ] Verify color identity injection
- [ ] Run full multi-actor test suite
- [ ] Fix any actor routing errors
- [ ] Fix any actor panic loops
- [ ] Fix any over-thinking stalls

## Crafty Syntax Auto-Installer Upgrade
- [ ] Audit current installers
- [ ] Map all installer dependencies
- [ ] Write installer PRD (single PRD, not a cluster)
- [ ] Build installer skeleton
- [ ] Add PRD 00 + PRD 16_C fetch logic
- [ ] Add tick.py requirement
- [ ] Add KEY validator
- [ ] Add PRD family loader
- [ ] Add color identity injection
- [ ] Add environment detection
- [ ] Test on Windows
- [ ] Test on macOS
- [ ] Test on Linux
- [ ] Write installer README
- [ ] Write troubleshooting guide

## Captain Logs Sync
- [ ] Identify all Patreon Captain Logs not in content/captains_logs
- [ ] Copy missing logs into repo
- [ ] Normalize filenames
- [ ] Add header_format_version
- [ ] Add prd_cluster
- [ ] Add summary lines

## Final Week (Sept 24-Oct 1)
- [ ] Freeze repo
- [ ] Final testing
- [ ] Final documentation
- [ ] Final Patreon announcement
- [ ] Final whitepaper update
- [ ] Ship v1.0
