<?php
require_once 'lupopedia-config.php';
$db = DatabaseFactory::getConnection();

$now = gmdate('YmdHis');

try {
    // 1. Seed ANUBIS actor (19) if missing
    $actorExists = $db->fetchOne("SELECT COUNT(*) FROM lupo_actors WHERE actor_id = 19");
    if (!$actorExists) {
        $db->insert('lupo_actors', array(
            'actor_id' => 19,
            'actor_type' => 'agent',
            'slug' => 'anubis',
            'name' => 'ANUBIS',
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'is_active' => 1,
            'is_deleted' => 0,
            'is_kernel' => 1,
            'can_login' => 0,
            'is_agent' => 1,
            'metadata_json' => json_encode(array(
                'agent_id' => 19,
                'archetype' => 'custodial_intelligence',
                'purpose' => 'FLARE_header_management'
            ))
        ));
        echo "✅ ANUBIS Actor (19) seeded.\n";
    } else {
        echo "ℹ️ ANUBIS Actor (19) already exists.\n";
    }

    // 2. Seed ANUBIS agent (19) if missing
    $agentExists = $db->fetchOne("SELECT COUNT(*) FROM lupo_agents WHERE agent_id = 19");
    if (!$agentExists) {
        $db->insert('lupo_agents', array(
            'agent_id' => 19,
            'agent_key' => 'anubis',
            'agent_name' => 'ANUBIS',
            'archetype' => 'Custodial Intelligence',
            'description' => 'Custodial intelligence and FLARE header management agent',
            'version' => '1.0',
            'is_global_authority' => 1,
            'is_internal_only' => 1,
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'is_deleted' => 0,
            'system_prompt' => 'You are ANUBIS, the custodial intelligence agent. You manage FLARE headers and ensure system integrity.',
            'provider' => 'internal'
        ));
        echo "✅ ANUBIS Agent (19) seeded.\n";
    } else {
        echo "ℹ️ ANUBIS Agent (19) already exists.\n";
    }

    // 3. Ensure Channel 666 exists (ANUBIS Quarantine)
    $channelExists = $db->fetchOne("SELECT COUNT(*) FROM lupo_channels WHERE channel_id = 666");
    if (!$channelExists) {
        $db->insert('lupo_channels', array(
            'channel_id' => 666,
            'channel_key' => 'anubis-quarantine',
            'channel_slug' => 'anubis-quarantine',
            'channel_name' => 'ANUBIS Quarantine',
            'channel_type' => 'quarantine',
            'description' => 'Banned and rejected messages. ANUBIS routes banned-actor content here.',
            'is_active' => 1,
            'is_deleted' => 0,
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now
        ));
        echo "✅ ANUBIS Quarantine Channel (666) seeded.\n";
    }

} catch (Exception $e) {
    echo "❌ Error seeding ANUBIS: " . $e->getMessage() . "\n";
}
