/**
 * Lupopedia dual operational log -- constitutional header generator (PRD 98_C / PRD 16).
 * Tooling mirror. Runnable path: scripts/logging/header_generator.py
 * header_format_version: 4.1.9 (22 fields, exact PRD 16 order).
 */

export const HEADER_FORMAT_VERSION = "4.1.9";

export const HEADER_KEYS_ORDERED: readonly string[] = [
  "header_format_version",
  "path_from_lupopedia_root",
  "web_path",
  "status",
  "when_updated",
  "trust_tier",
  "questions_toon",
  "memory_toon",
  "atoms_toon",
  "transcript_jsonl",
  "artifact_type",
  "artifact_kind",
  "channel_key",
  "federation_node_id",
  "thread_key",
  "lupopedia.schema",
  "prd_cluster",
  "title",
  "summary",
  "edges_toon",
  "channel_index",
  "source_timestamp",
] as const;

export type LupopediaHeader = {
  header_format_version: string;
  path_from_lupopedia_root: string;
  web_path: string;
  status: string;
  when_updated: string;
  trust_tier: string;
  questions_toon: string | null;
  memory_toon: string | null;
  atoms_toon: string | null;
  transcript_jsonl: string;
  artifact_type: string;
  artifact_kind: string;
  channel_key: string;
  federation_node_id: number;
  thread_key: string;
  "lupopedia.schema": string;
  prd_cluster: string;
  title: string;
  summary: string;
  edges_toon: string | null;
  channel_index: string;
  source_timestamp: string | null;
};

export type HeaderOverrides = Partial<LupopediaHeader> & {
  path_from_lupopedia_root: string;
  title: string;
  summary: string;
  when_updated?: string;
  thread_key?: string;
};

/** Packed UTC YYYYMMDDHHIISS from Date (UTC). */
export function toPackedUtc(d: Date = new Date()): string {
  const p = (n: number) => String(n).padStart(2, "0");
  return (
    String(d.getUTCFullYear()) +
    p(d.getUTCMonth() + 1) +
    p(d.getUTCDate()) +
    p(d.getUTCHours()) +
    p(d.getUTCMinutes()) +
    p(d.getUTCSeconds())
  );
}

/** Optional ISO-8601 UTC display from packed UTC. */
export function packedToIso(ymdhis: string): string {
  if (!/^[0-9]{14}$/.test(ymdhis)) {
    throw new Error("timestamp_ymdhis must be 14 digits YYYYMMDDHHIISS");
  }
  const y = ymdhis.slice(0, 4);
  const m = ymdhis.slice(4, 6);
  const d = ymdhis.slice(6, 8);
  const hh = ymdhis.slice(8, 10);
  const mi = ymdhis.slice(10, 12);
  const ss = ymdhis.slice(12, 14);
  return `${y}-${m}-${d}T${hh}:${mi}:${ss}Z`;
}

export function generateConstitutionalHeader(overrides: HeaderOverrides): LupopediaHeader {
  const when = overrides.when_updated || toPackedUtc();
  const path = overrides.path_from_lupopedia_root.replace(/\\/g, "/");
  const base: LupopediaHeader = {
    header_format_version: HEADER_FORMAT_VERSION,
    path_from_lupopedia_root: path,
    web_path: "https://www.lupopedia.com/lupopedia/" + path,
    status: "active",
    when_updated: when,
    trust_tier: "canonical",
    questions_toon: null,
    memory_toon: null,
    atoms_toon: null,
    transcript_jsonl: "0/logs/dual-operational",
    artifact_type: "log",
    artifact_kind: "reference",
    channel_key: "logs",
    federation_node_id: 0,
    thread_key: overrides.thread_key || "",
    "lupopedia.schema": "log",
    prd_cluster: "98_C",
    title: overrides.title,
    summary: overrides.summary,
    edges_toon: null,
    channel_index: "lupopedia",
    source_timestamp: null,
  };
  const merged: LupopediaHeader = { ...base, ...overrides, path_from_lupopedia_root: path };
  merged.web_path =
    overrides.web_path || "https://www.lupopedia.com/lupopedia/" + path;
  merged.header_format_version = HEADER_FORMAT_VERSION;
  // Enforce key presence / order by reconstructing
  const ordered: any = {};
  for (const k of HEADER_KEYS_ORDERED) {
    ordered[k] = (merged as any)[k];
  }
  return ordered as LupopediaHeader;
}
