#!/usr/bin/env python3
"""
Seed Data Verification Script
Run before fresh install to ensure all required data is seeded
TIMESTAMP FORMAT: YYYYMMDDHHIISS (BIGINT)
"""

import mysql.connector
import sys
from datetime import datetime

# Database connection (adjust as needed)
DB_CONFIG = {
    'host': 'localhost',
    'user': 'root',
    'password': 'ServBay.dev',
    'database': 'lupopedia',
    'charset': 'utf8mb4'
}

# Required actors
REQUIRED_ACTORS = [
    {'actor_id': 1, 'actor_name': 'WOLFIE', 'slug': 'wolfie', 'name': 'WOLFIE', 'actor_type': 'system', 'is_agent': 1},
    {'actor_id': 2, 'actor_name': 'LILITH', 'slug': 'lilith', 'name': 'LILITH', 'actor_type': 'system', 'is_agent': 1},
    {'actor_id': 3, 'actor_name': 'ROSE', 'slug': 'rose', 'name': 'ROSE', 'actor_type': 'system', 'is_agent': 1},
    {'actor_id': 4, 'actor_name': 'ATHENA', 'slug': 'athena', 'name': 'ATHENA', 'actor_type': 'system', 'is_agent': 1},
    {'actor_id': 5, 'actor_name': 'LEXA', 'slug': 'lexa', 'name': 'LEXA', 'actor_type': 'system', 'is_agent': 1},
    {'actor_id': 6, 'actor_name': 'ANUBIS', 'slug': 'anubis', 'name': 'ANUBIS', 'actor_type': 'system', 'is_agent': 1},
    {'actor_id': 7, 'actor_name': 'MAAT', 'slug': 'maat', 'name': 'MAAT', 'actor_type': 'system', 'is_agent': 1},
    {'actor_id': 8, 'actor_name': 'HEIMDALL', 'slug': 'heimdall', 'name': 'HEIMDALL', 'actor_type': 'system', 'is_agent': 1},
    {'actor_id': 9, 'actor_name': 'THEMIS', 'slug': 'themis', 'name': 'THEMIS', 'actor_type': 'system', 'is_agent': 1},
    {'actor_id': 10, 'actor_name': 'SESHAT', 'slug': 'seshat', 'name': 'SESHAT', 'actor_type': 'system', 'is_agent': 1},
    {'actor_id': 11, 'actor_name': 'THOTH', 'slug': 'thoth', 'name': 'THOTH', 'actor_type': 'system', 'is_agent': 1},
    {'actor_id': 12, 'actor_name': 'JANUS', 'slug': 'janus', 'name': 'JANUS', 'actor_type': 'system', 'is_agent': 1},
    {'actor_id': 14, 'actor_name': 'HEPHAESTUS', 'slug': 'hephaestus', 'name': 'HEPHAESTUS', 'actor_type': 'system', 'is_agent': 1},
]

# Required agents
REQUIRED_AGENTS = [
    {'agent_id': 1, 'agent_key': 'wolfie', 'agent_name': 'WOLFIE', 'description': 'Orchestrator - strategic planning, delegation, enforcement'},
    {'agent_id': 2, 'agent_key': 'lilith', 'agent_name': 'LILITH', 'description': 'Critic - non-interfering reviewer, contradiction detection'},
    {'agent_id': 3, 'agent_key': 'rose', 'agent_name': 'ROSE', 'description': 'Emotional dialogue - context, stakeholder needs, human factors'},
    {'agent_id': 4, 'agent_key': 'athena', 'agent_name': 'ATHENA', 'description': 'Wisdom & strategy - strategic analysis, architectural guidance'},
    {'agent_id': 5, 'agent_key': 'lexa', 'agent_name': 'LEXA', 'description': 'Security enforcement - boundary enforcement, policy compliance'},
    {'agent_id': 6, 'agent_key': 'anubis', 'agent_name': 'ANUBIS', 'description': 'Custodian - data integrity, lineage, custody audit'},
    {'agent_id': 7, 'agent_key': 'maat', 'agent_name': 'MAAT', 'description': 'Truth & justice - conflict resolution, fairness, accountability'},
    {'agent_id': 8, 'agent_key': 'heimdall', 'agent_name': 'HEIMDALL', 'description': 'Security guardian - access control, perimeter defense'},
    {'agent_id': 9, 'agent_key': 'themis', 'agent_name': 'THEMIS', 'description': 'Law & compliance - regulatory compliance, binding rules'},
    {'agent_id': 10, 'agent_key': 'seshat', 'agent_name': 'SESHAT', 'description': 'Content review - content quality, documentation accuracy'},
    {'agent_id': 11, 'agent_key': 'thoth', 'agent_name': 'THOTH', 'description': 'Knowledge & records - documentation, record-keeping, provenance'},
    {'agent_id': 12, 'agent_key': 'janus', 'agent_name': 'JANUS', 'description': 'Transitions & gateways - state transitions, boundary management'},
    {'agent_id': 14, 'agent_key': 'hephaestus', 'agent_name': 'HEPHAESTUS', 'description': 'Implementer - code, docs, schema execution'},
]

# Required auth user
REQUIRED_AUTH_USERS = [
    {'auth_user_id': 1000, 'username': 'root', 'email': 'wisdomoflovingfaith@gmail.com', 'display_name': 'root'},
]

def get_current_timestamp():
    """Return current timestamp in YYYYMMDDHHIISS format"""
    return datetime.utcnow().strftime('%Y%m%d%H%M%S')

def verify_actors(cursor):
    """Verify all required actors exist"""
    print("\n=== Verifying Actors ===")
    missing = []
    
    for actor in REQUIRED_ACTORS:
        cursor.execute(
            "SELECT actor_id, actor_name, slug, actor_type, is_agent FROM lupo_actors WHERE actor_id = %s",
            (actor['actor_id'],)
        )
        result = cursor.fetchone()
        if result:
            print(f"✅ Actor {actor['actor_id']}: {actor['actor_name']} found")
        else:
            print(f"❌ Actor {actor['actor_id']}: {actor['actor_name']} MISSING")
            missing.append(actor)
    
    return missing

def verify_agents(cursor):
    """Verify all required agents exist"""
    print("\n=== Verifying Agents ===")
    missing = []
    
    for agent in REQUIRED_AGENTS:
        cursor.execute(
            "SELECT agent_id, agent_key, agent_name FROM lupo_agents WHERE agent_id = %s",
            (agent['agent_id'],)
        )
        result = cursor.fetchone()
        if result:
            print(f"✅ Agent {agent['agent_id']}: {agent['agent_name']} found")
        else:
            print(f"❌ Agent {agent['agent_id']}: {agent['agent_name']} MISSING")
            missing.append(agent)
    
    return missing

def verify_auth_users(cursor):
    """Verify required auth users exist"""
    print("\n=== Verifying Auth Users ===")
    missing = []
    
    for auth_user in REQUIRED_AUTH_USERS:
        cursor.execute(
            "SELECT auth_user_id, username, email FROM lupo_auth_users WHERE auth_user_id = %s",
            (auth_user['auth_user_id'],)
        )
        result = cursor.fetchone()
        if result:
            print(f"✅ Auth User {auth_user['auth_user_id']}: {auth_user['username']} found")
        else:
            print(f"❌ Auth User {auth_user['auth_user_id']}: {auth_user['username']} MISSING")
            missing.append(auth_user)
    
    return missing

def verify_actor_auth_mapping(cursor):
    """Verify root user has actor mapping (optional)"""
    print("\n=== Verifying Actor-Auth Mapping ===")
    cursor.execute(
        "SELECT actor_id, auth_user_id FROM lupo_actor_auth_users WHERE auth_user_id = 1000"
    )
    result = cursor.fetchone()
    if result:
        print(f"✅ Root user mapped to actor {result[0]}")
    else:
        print(f"⚠️ No actor mapping for root user (will be created on first login)")
    return result is not None

def generate_missing_seed_sql(missing_actors, missing_agents, missing_auth_users):
    """Generate SQL for missing seed data using YYYYMMDDHHIISS format"""
    print("\n=== Missing Seed Data SQL ===")
    
    now = get_current_timestamp()
    
    if missing_actors:
        print("\n-- Missing Actors (YYYYMMDDHHIISS timestamps)")
        for actor in missing_actors:
            print(f"INSERT INTO lupo_actors (actor_id, actor_name, slug, name, actor_type, is_agent, created_ymdhis, updated_ymdhis) VALUES")
            print(f"  ({actor['actor_id']}, '{actor['actor_name']}', '{actor['slug']}', '{actor['name']}', '{actor['actor_type']}', {actor['is_agent']}, {now}, {now});")
    
    if missing_agents:
        print("\n-- Missing Agents (YYYYMMDDHHIISS timestamps)")
        for agent in missing_agents:
            desc = agent['description'].replace("'", "\\'")
            print(f"INSERT INTO lupo_agents (agent_id, agent_key, agent_name, description, created_ymdhis, updated_ymdhis) VALUES")
            print(f"  ({agent['agent_id']}, '{agent['agent_key']}', '{agent['agent_name']}', '{desc}', {now}, {now});")
    
    if missing_auth_users:
        print("\n-- Missing Auth Users (YYYYMMDDHHIISS timestamps)")
        for auth in missing_auth_users:
            print(f"INSERT INTO lupo_auth_users (auth_user_id, username, email, display_name, created_ymdhis, updated_ymdhis) VALUES")
            print(f"  ({auth['auth_user_id']}, '{auth['username']}', '{auth['email']}', '{auth['display_name']}', {now}, {now});")

def main():
    try:
        conn = mysql.connector.connect(**DB_CONFIG)
        cursor = conn.cursor()
        
        print("=" * 60)
        print("SEED DATA VERIFICATION")
        print("Timestamp format: YYYYMMDDHHIISS")
        print("=" * 60)
        
        missing_actors = verify_actors(cursor)
        missing_agents = verify_agents(cursor)
        missing_auth_users = verify_auth_users(cursor)
        has_mapping = verify_actor_auth_mapping(cursor)
        
        print("\n" + "=" * 60)
        print("SUMMARY")
        print("=" * 60)
        
        if missing_actors:
            print(f"❌ Missing Actors: {len(missing_actors)}")
            for a in missing_actors:
                print(f"   - {a['actor_name']}")
        else:
            print("✅ All 13 actors present")
        
        if missing_agents:
            print(f"❌ Missing Agents: {len(missing_agents)}")
            for a in missing_agents:
                print(f"   - {a['agent_name']}")
        else:
            print("✅ All 13 agents present")
        
        if missing_auth_users:
            print(f"❌ Missing Auth Users: {len(missing_auth_users)}")
            for a in missing_auth_users:
                print(f"   - {a['username']}")
        else:
            print("✅ Root auth user present")
        
        if not has_mapping:
            print("⚠️ No actor mapping for root user (will be created on first login)")
        
        if missing_actors or missing_agents or missing_auth_users:
            generate_missing_seed_sql(missing_actors, missing_agents, missing_auth_users)
            print("\n⚠️ Run the SQL above to fix missing seed data before install.")
            sys.exit(1)
        else:
            print("\n✅ All seed data is present. Ready for install.")
            sys.exit(0)
            
    except mysql.connector.Error as e:
        print(f"❌ Database error: {e}")
        print("Make sure the database exists and is accessible.")
        sys.exit(1)

if __name__ == "__main__":
    main()
