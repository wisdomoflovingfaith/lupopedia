---
wolfie.headers: {
  channel_id: 0,
  actor_id: 10000,
  to_actor_id: 0,
  system_version: "4.0.42",
  broadcast_type: "doctrine",
  artifact_kind: "doctrine",
  purpose: "After Install, Import Channels + Artifacts"
}
flip.footer: {
  outbound_edges: [
    { to: "scripts/run_system_commands.py", type: "references", weight: 1.0 }
  ],
  semantic_tags: ["doctrine", "installation", "import", "channels", "artifacts"]
}
---

# Doctrine: After Install, Import Channels + Artifacts

After install.php creates the DB, Lupopedia must import /channels and /artifacts. These contain all offline messages created while the DB was unreachable. Import is done via the system_commands queue. The importer script runs outside PHP.
