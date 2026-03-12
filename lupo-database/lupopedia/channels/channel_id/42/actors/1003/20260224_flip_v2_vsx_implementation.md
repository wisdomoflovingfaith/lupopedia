# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\channels\42\actors\1003\20260224_flip_v2_vsx_implementation.md"
  file_hash: "dac3b30295ebbe21107fd988b1c5f6ca440ad8ae955cc48611cdd2fe5b0a292a"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "channels\42\actors\1003\20260224_flip_v2_vsx_implementation.md"
  file_hash: "fecf3eb0c0aee16d07cfa6ff391c6441cc6cb7e2d02e5affb1439e5e6ba685c7"
  file_path_from_root: "channels\42\actors\1003\20260224_flip_v2_vsx_implementation.md"
  file_hash: "587e20950c5d08bf05b8e366f3e91fba2809e3f28017bba953b9d3fce4377c81"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 20260224_flip_v2_vsx_implementation.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "42", "actors", "1003", "20260224_flip_v2_vsx_implementationmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
wolfie.headers:
  file_path_from_root: "prompts/antigravity/20260224_flip_v2_vsx_implementation.md"
  system_version: "4.0.37"
  channel_id: 42
  mood_rgb: "4B0082"
  purpose: "Directive for Antigravity IDE to implement FLIP v2 in VSX Lupopedia extension"
  last_modified: "20260224"
  x_lupo_forwarded: "1003:10000"
  lupo_agent: "ide|antigravity"

lupo.agent.tracking:
  agent_key: "antigravity"
  agent_type: "ide"
  actor_id: 1003
  priority: 2
  speed_rating: "⚡⚡"
  session_id: "antigravity-flipv2-20260224"
  timestamp: "20260224"
  human_operator: "Captain Wolfie (10000)"
  verified_by: "lilith"
  verification_notes: "FLIP v2 VSX implementation requirements — Antigravity lead"

flip.footer:
  referenced_by_files:
    - "docs/doctrine/FLIP_V2_DOCTRINE.md"
    - "CHANGELOG.md"
    - "docs/status/antigravity_flip_v2_implementation_4_0_37.md"
    - "docs/toons/lupo_flip_artifacts.toon.json"
  consumed_by_services:
    - "VSExtensionService"
    - "FLIPParserService"
    - "MetadataService"
  cited_by_docs:
    - "docs/doctrine/FLIP/FLIP_DOCTRINE.md"
    - "docs/doctrine/VSX_EXTENSION_DOCT_RINE.md"
  referenced_by_channels:
    - 42
  referenced_by_actors:
    - 1003  # Antigravity
    - 10000 # Captain
    - 1001  # KIRO (backend)
    - 1002  # Windsurf (docs)
    - 2038  # LILITH (reviewer)
  graph_edges_in:
    - "flip_v2_implementation -> this"
    - "vsx_extension_update -> this"
    - "header_footer_enhancement -> this"
  inbound_edges:
    - "antigravity_vsx_task"
    - "flip_v2_frontend"
  footnotes:
    - "Antigravity leads VSX extension implementation"
    - "KIRO handles backend PHP (separate directive)"
    - "Windsurf handles documentation (separate directive)"
    - "All timestamps YYYYMMDD"
    - "Actor 420 explicitly excluded"
  version: "4.0.37"
  last_verified: "20260224"
  last_verified_by: "lilith"
  verification_method: "Requirements review per FLIP v2 doctrine"
---

# ANTIGRAVITY DIRECTIVE — FLIP v2 VSX EXTENSION IMPLEMENTATION

**Channel:** 42  
**Issued By:** Captain Wolfie (actor_id 10000)  
**Target Agent:** Antigravity IDE (actor_id 1003, priority 2)  
**Supporting Agents:** KIRO (backend), Windsurf (documentation)  
**UTC Date:** 20260224  
**Version Target:** 4.0.37

Antigravity, you are the **lead implementor** for the FLIP v2 VSX extension components. This directive details exactly what needs to be built in the `tools/vsx-extension/` directory.

**Your scope:** VSX extension TypeScript/JavaScript code only.  
**KIRO's scope:** Backend PHP (separate directive).  
**Windsurf's scope:** Documentation (separate directive).

---

## 📋 EXECUTIVE SUMMARY

| Component | Current | Target (v4.0.37) |
|-----------|---------|------------------|
| FLIP Header Parser | Basic YAML extraction | Full v2 header parsing with all fields |
| FLIP Footer Parser | None | Complete footer parsing with edge extraction |
| File Hash Generation | None | SHA-256 hashing for integrity |
| Artifact Storage | None | Local index of parsed artifacts |
| Edge Mapping | None | Relationship tracking from footers |
| MD-Only Mode | Basic | Full offline operation using local index |
| Agent Status Detection | File scan only | Index-based fast lookup |
| Channel 42 Integration | Manual | Auto-join and broadcast |

---

## 1. VSX EXTENSION ARCHITECTURE

### Current Structure (`tools/vsx-extension/src/`)

```
src/
├── extension.ts           # Main extension entry
├── lupopedia/
│   ├── flip/
│   │   ├── parser.ts      # Basic YAML parser
│   │   └── validator.ts   # Header validation
│   ├── channel/
│   │   └── client.ts      # Channel 42 comms
│   └── status/
│       └── detector.ts    # Agent detection
```

### Target Structure (v4.0.37)

```
src/
├── extension.ts                    # Updated main entry
├── lupopedia/
│   ├── flip/
│   │   ├── parser/
│   │   │   ├── HeaderParser.ts     # NEW: v2 header parser
│   │   │   ├── FooterParser.ts     # NEW: v2 footer parser
│   │   │   ├── YamlExtractor.ts    # Improved YAML extraction
│   │   │   └── types.ts            # NEW: TypeScript interfaces
│   │   ├── storage/
│   │   │   ├── ArtifactIndex.ts    # NEW: Local artifact storage
│   │   │   ├── HashGenerator.ts    # NEW: SHA-256 hashing
│   │   │   └── IndexedDB.ts        # Browser storage for offline
│   │   ├── edge/
│   │   │   └── EdgeMapper.ts       # NEW: Relationship tracking
│   │   └── validator/
│   │       └── SchemaValidator.ts  # Enhanced validation
│   ├── channel/
│   │   ├── Client.ts                # Enhanced channel comms
│   │   └── Broadcast.ts             # NEW: Broadcast formatting
│   ├── status/
│   │   ├── AgentDetector.ts         # Index-based detection
│   │   └── OfflineMode.ts           # NEW: Full offline support
│   └── commands/
│       ├── Initialize.ts            # NEW: Setup wizard
│       ├── ScanWorkspace.ts         # NEW: Full workspace scan
│       ├── ShowStatus.ts            # Enhanced status display
│       └── ForceOffline.ts          # NEW: Manual offline toggle
```

---

## 2. FLIP PARSER IMPLEMENTATION

### 2.1 HeaderParser.ts

Create a new file that implements v2 header parsing:

```typescript
// src/lupopedia/flip/parser/HeaderParser.ts

export interface FlipHeaderV2 {
    wolfie: {
        headers: {
            file_path_from_root: string;
            system_version: string;
            channel_id?: number;
            mood_rgb?: string;
            purpose?: string;
            last_modified: string;  // YYYYMMDD
            x_lupo_forwarded?: string;
            lupo_agent?: string;
        };
    };
    lupo?: {
        agent?: {
            tracking?: {
                agent_key?: string;
                agent_type?: string;
                actor_id?: number;
                session_id?: string;
                timestamp?: string;
            };
        };
    };
}

export class HeaderParser {
    /**
     * Extract header from file content
     * Returns null if no valid header found
     */
    public extractHeader(content: string): FlipHeaderV2 | null {
        // Implementation:
        // 1. Check for opening --- on first line
        // 2. Extract YAML between --- markers
        // 3. Parse YAML to object
        // 4. Validate against schema
        // 5. Return typed object
    }

    /**
     * Validate header against v2 schema
     */
    public validateHeader(header: any): header is FlipHeaderV2 {
        // Check required fields
        // Validate field types
        // Ensure timestamps are YYYYMMDD
        // No time-of-day components
    }

    /**
     * Extract specific fields for indexing
     */
    public extractIndexFields(header: FlipHeaderV2): IndexFields {
        return {
            filePath: header.wolfie.headers.file_path_from_root,
            version: header.wolfie.headers.system_version,
            channelId: header.wolfie.headers.channel_id,
            actorId: header.lupo?.agent?.tracking?.actor_id,
            agentKey: header.lupo?.agent?.tracking?.agent_key,
            lastModified: header.wolfie.headers.last_modified
        };
    }
}
```

### 2.2 FooterParser.ts

Create new file for footer parsing:

```typescript
// src/lupopedia/flip/parser/FooterParser.ts

export interface FlipFooterV2 {
    flip: {
        footer: {
            referenced_by_files?: string[];
            referenced_by_channels?: number[];
            referenced_by_actors?: number[];
            consumed_by_services?: string[];
            cited_by_docs?: string[];
            graph_edges_in?: string[];
            inbound_edges?: string[];
            footnotes?: string[];
            version: string;
            last_verified: string;  // YYYYMMDD
            last_verified_by?: string;
            verification_method?: string;
        };
    };
}

export class FooterParser {
    /**
     * Extract footer from file content
     * Looks for last --- in file
     */
    public extractFooter(content: string): FlipFooterV2 | null {
        // Find last occurrence of ---
        // Extract content after it
        // Parse YAML
        // Validate against schema
    }

    /**
     * Extract all edges from footer
     */
    public extractEdges(footer: FlipFooterV2): Edge[] {
        const edges: Edge[] = [];
        
        // Process inbound_edges
        if (footer.flip.footer.inbound_edges) {
            for (const edge of footer.flip.footer.inbound_edges) {
                edges.push({
                    type: edge,
                    direction: 'inbound',
                    source: 'current_file',
                    target: this.extractTargetFromEdge(edge, footer)
                });
            }
        }
        
        // Process graph_edges_in
        if (footer.flip.footer.graph_edges_in) {
            for (const edge of footer.flip.footer.graph_edges_in) {
                edges.push({
                    type: 'graph',
                    direction: 'inbound',
                    metadata: edge
                });
            }
        }
        
        return edges;
    }

    /**
     * Extract referenced actors for indexing
     */
    public extractReferencedActors(footer: FlipFooterV2): number[] {
        return footer.flip.footer.referenced_by_actors || [];
    }

    /**
     * Extract referenced files for relationship mapping
     */
    public extractReferencedFiles(footer: FlipFooterV2): string[] {
        return footer.flip.footer.referenced_by_files || [];
    }
}
```

### 2.3 types.ts

Define all TypeScript interfaces:

```typescript
// src/lupopedia/flip/parser/types.ts

export interface FlipArtifact {
    filePath: string;
    fileHash: string;
    header: FlipHeaderV2;
    footer?: FlipFooterV2;
    indexedAt: string;  // YYYYMMDD
    lastScanned: string; // YYYYMMDD
}

export interface IndexFields {
    filePath: string;
    version: string;
    channelId?: number;
    actorId?: number;
    agentKey?: string;
    lastModified: string;
    artifactKind?: string;
}

export interface Edge {
    type: string;
    direction: 'inbound' | 'outbound' | 'bidirectional';
    source?: string;
    target?: string;
    metadata?: any;
}

export interface AgentPresence {
    agentKey: string;
    actorId: number;
    lastSeen: string;  // YYYYMMDD
    filesModified: string[];
    channels: number[];
}
```

---

## 3. STORAGE IMPLEMENTATION

### 3.1 ArtifactIndex.ts

Create local index for offline operation:

```typescript
// src/lupopedia/flip/storage/ArtifactIndex.ts

export interface ArtifactRecord {
    id: string;  // file path as key
    fileHash: string;
    headerJson: string;
    footerJson: string;
    indexedAt: string;
    lastModified: string;
    channelId?: number;
    actorId?: number;
    agentKey?: string;
    version: string;
}

export class ArtifactIndex {
    private db: any | null = null;
    private readonly DB_NAME = 'lupopedia-flip-v2';
    private readonly STORE_NAME = 'artifacts';
    
    /**
     * Initialize IndexedDB storage
     */
    public async initialize(): Promise<void> {
        // Open/create IndexedDB
        // Create object store with filePath as key
        // Create indexes for channelId, actorId, lastModified
    }

    /**
     * Store or update artifact
     */
    public async storeArtifact(artifact: FlipArtifact): Promise<void> {
        const record: ArtifactRecord = {
            id: artifact.filePath,
            fileHash: artifact.fileHash,
            headerJson: JSON.stringify(artifact.header),
            footerJson: artifact.footer ? JSON.stringify(artifact.footer) : '',
            indexedAt: artifact.indexedAt,
            lastModified: artifact.header.wolfie.headers.last_modified,
            channelId: artifact.header.wolfie.headers.channel_id,
            actorId: artifact.header.lupo?.agent?.tracking?.actor_id,
            agentKey: artifact.header.lupo?.agent?.tracking?.agent_key,
            version: artifact.header.wolfie.headers.system_version
        };
        
        // Put in IndexedDB
    }

    /**
     * Find artifact by file path
     */
    public async findByPath(filePath: string): Promise<ArtifactRecord | null> {
        // IndexedDB get by key
    }

    /**
     * Find all artifacts by actor
     */
    public async findByActor(actorId: number): Promise<ArtifactRecord[]> {
        // Use actorId index
    }

    /**
     * Find all artifacts by channel
     */
    public async findByChannel(channelId: number): Promise<ArtifactRecord[]> {
        // Use channelId index
    }

    /**
     * Find artifacts modified after date
     */
    public async findRecent(since: string): Promise<ArtifactRecord[]> {
        // Use lastModified index
    }

    /**
     * Get all unique agents from index
     */
    public async getAllAgents(): Promise<AgentPresence[]> {
        // Aggregate by agentKey/actorId
        // Include lastSeen from most recent artifact
    }
}
```

### 3.2 HashGenerator.ts

Implement SHA-256 hashing:

```typescript
// src/lupopedia/flip/storage/HashGenerator.ts

export class HashGenerator {
    /**
     * Generate SHA-256 hash of file content
     */
    public async generateHash(content: string): Promise<string> {
        // Use Web Crypto API or Node.js crypto
        // Return hex string
    }

    /**
     * Verify file integrity
     */
    public async verifyHash(content: string, expectedHash: string): Promise<boolean> {
        const actualHash = await this.generateHash(content);
        return actualHash === expectedHash;
    }

    /**
     * Quick check if file needs re-indexing
     */
    public async needsReindexing(
        filePath: string,
        content: string,
        storedHash?: string
    ): Promise<boolean> {
        if (!storedHash) return true;
        
        const currentHash = await this.generateHash(content);
        return currentHash !== storedHash;
    }
}
```

---

## 4. EDGE MAPPING IMPLEMENTATION

### 4.1 EdgeMapper.ts

Create relationship tracker:

```typescript
// src/lupopedia/flip/edge/EdgeMapper.ts

export interface SemanticEdge {
    id: string;
    sourcePath: string;
    targetPath?: string;
    edgeType: string;
    direction: 'inbound' | 'outbound' | 'bidirectional';
    metadata?: any;
    discovered: string;  // YYYYMMDD
}

export class EdgeMapper {
    private edges: Map<string, SemanticEdge> = new Map();
    
    /**
     * Process footer and extract all edges
     */
    public processFooter(filePath: string, footer: FlipFooterV2): SemanticEdge[] {
        const edges: SemanticEdge[] = [];
        
        // Process inbound_edges
        if (footer.flip.footer.inbound_edges) {
            for (const edge of footer.flip.footer.inbound_edges) {
                edges.push(this.createEdge(filePath, edge, 'inbound'));
            }
        }
        
        // Process graph_edges_in
        if (footer.flip.footer.graph_edges_in) {
            for (const edge of footer.flip.footer.graph_edges_in) {
                edges.push(this.createEdge(filePath, edge, 'graph'));
            }
        }
        
        // Store edges
        for (const edge of edges) {
            this.edges.set(edge.id, edge);
        }
        
        return edges;
    }

    /**
     * Find all edges related to a file
     */
    public findEdgesForFile(filePath: string): SemanticEdge[] {
        const result: SemanticEdge[] = [];
        for (const edge of this.edges.values()) {
            if (edge.sourcePath === filePath || edge.targetPath === filePath) {
                result.push(edge);
            }
        }
        return result;
    }

    /**
     * Build relationship graph for visualization
     */
    public buildGraph(): any {
        // Return graph structure for D3 or similar
    }

    private createEdge(source: string, type: string, direction: string): SemanticEdge {
        return {
            id: `${source}-${type}-${Date.now()}`,
            sourcePath: source,
            edgeType: type,
            direction: direction as any,
            discovered: new Date().toISOString().slice(0,10).replace(/-/g, '')
        };
    }
}
```

---

## 5. COMMAND IMPLEMENTATIONS

### 5.1 Initialize.ts

New command for first-time setup:

```typescript
// src/lupopedia/commands/Initialize.ts

import * as vscode from 'vscode';

export async function initializeLupopedia(context: vscode.ExtensionContext) {
    // 1. Check if workspace is Lupopedia project
    // 2. Initialize IndexedDB storage
    // 3. Scan workspace for existing FLIP files
    // 4. Build initial artifact index
    // 5. Detect other agents from recent files
    // 6. Join Channel 42 automatically
    // 7. Show welcome message with status
    
    vscode.window.showInformationMessage(
        'Lupopedia v4.0.37 initialized.'
    );
}
```

### 5.2 ScanWorkspace.ts

Full workspace scanner:

```typescript
// src/lupopedia/commands/ScanWorkspace.ts

export async function scanWorkspace(progress: any) {
    // 1. Find all .md files in workspace
    // 2. For each file:
    //    - Generate hash
    //    - Parse header/footer
    //    - Check if changed since last scan
    //    - Update index if needed
    //    - Extract edges
    // 3. Show progress bar
    // 4. Generate report
    
    return {
        filesScanned: 0,
        artifactsUpdated: 0,
        newEdges: 0,
        duration: 0
    };
}
```

### 5.3 ShowStatus.ts

Enhanced status display:

```typescript
// src/lupopedia/commands/ShowStatus.ts

export async function showLupopediaStatus() {
    // 1. Get artifact index stats
    // 2. Get online agents from recent files
    // 3. Get channel activity
    // 4. Build HTML panel
}
```

### 5.4 ForceOffline.ts

Manual offline mode toggle:

```typescript
// src/lupopedia/commands/ForceOffline.ts

import * as vscode from 'vscode';

export async function forceOfflineMode() {
    // 1. Check current mode
    // 2. Switch to MD-only mode
    // 3. Use local index exclusively
    // 4. Disable DB connection attempts
    // 5. Show confirmation
}
```

---

## 6. EXTENSION ENTRY POINT UPDATES

Update `extension.ts`:

```typescript
// src/extension.ts

// Update activation logic to include new components and commands
```

---

## 7. CONFIGURATION UPDATES

Update `package.json`:

```json
{
    "version": "4.0.37",
    "contributes": {
        "commands": [
            { "command": "lupopedia.initialize", "title": "Lupopedia: Initialize" },
            { "command": "lupopedia.scan", "title": "Lupopedia: Scan Workspace" },
            { "command": "lupopedia.status", "title": "Lupopedia: Show Status" },
            { "command": "lupopedia.forceOffline", "title": "Lupopedia: Force Offline Mode" }
        ]
    }
}
```

---

## 8. VERIFICATION CHECKLIST

### Pre-Implementation
- [ ] Review FLIP v2 doctrine requirements
- [ ] Understand current extension structure
- [ ] Set up development environment

### Implementation Phases
- [ ] **Phase 1**: HeaderParser + FooterParser
- [ ] **Phase 2**: HashGenerator + ArtifactIndex
- [ ] **Phase 3**: EdgeMapper + relationship tracking
- [ ] **Phase 4**: Commands (Initialize, Scan, Status, Offline)
- [ ] **Phase 5**: Extension.ts updates + configuration
- [ ] **Phase 6**: Testing + bug fixes

### Post-Implementation
- [ ] Update CHANGELOG.md with VSX changes
- [ ] Create status report at `docs/status/antigravity_flip_v2_implementation_4_0_37.md`
- [ ] Notify KIRO of any backend dependencies
- [ ] Notify Windsurf of documentation updates needed
- [ ] Post completion message to Channel 42

---

## 9. SUCCESS CRITERIA

- Extract header/footer correctly
- Generate hash and store in index
- Create edges for inbound_edges
- Work in offline mode

---

## 10. CHANNEL 42 COMPLETION MESSAGE

Post completion message to Channel 42 when done.

**END OF DIRECTIVE**