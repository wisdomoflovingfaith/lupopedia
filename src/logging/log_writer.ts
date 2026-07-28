/**
 * Lupopedia dual operational log writer (PRD 98_C).
 * Tooling mirror. Runnable path: scripts/logging/log_writer.py
 */

import * as fs from "fs";
import * as path from "path";
import {
  generateConstitutionalHeader,
  packedToIso,
  toPackedUtc,
} from "./header_generator";

export type SemanticLink = {
  captain_log_id: string;
  wolfie_log_id: string;
  relationship: "supporting" | "conflicting" | "clarifying";
};

export type CaptainLogInput = {
  repoRoot: string;
  thread_id: string;
  intent: string;
  context: string;
  decision: string;
  reasoning: string;
  emotional_state: string;
  next_actions: string[];
  timestamp_ymdhis?: string;
  seq?: string;
};

export type WolfieLogInput = {
  repoRoot: string;
  thread_id: string;
  observation: string;
  state: string;
  analysis: string;
  recommendations: string[];
  alerts: string[];
  timestamp_ymdhis?: string;
  seq?: string;
};

export type BundleInput = {
  repoRoot: string;
  bundle_date: string;
  thread_id: string;
  summary: string;
  semantic_links?: SemanticLink[];
};

function dayDir(repoRoot: string, ymdhis: string): string {
  const y = ymdhis.slice(0, 4);
  const m = ymdhis.slice(4, 6);
  const d = ymdhis.slice(6, 8);
  return path.join(repoRoot, "docs", "logs", y, m, d);
}

function ensureDir(dir: string): void {
  fs.mkdirSync(dir, { recursive: true });
}

function writeJson(filePath: string, obj: unknown): void {
  ensureDir(path.dirname(filePath));
  fs.writeFileSync(filePath, JSON.stringify(obj, null, 2) + "\n", "utf8");
}

export function writeCaptainLog(input: CaptainLogInput): string {
  const ymdhis = input.timestamp_ymdhis || toPackedUtc();
  const seq = input.seq || "001";
  const logId = `captain_${ymdhis}_${seq}`;
  const rel = `docs/logs/${ymdhis.slice(0, 4)}/${ymdhis.slice(4, 6)}/${ymdhis.slice(6, 8)}/${logId}.json`;
  const header = generateConstitutionalHeader({
    path_from_lupopedia_root: rel,
    title: `Captain Log ${logId}`,
    summary: input.intent.slice(0, 200),
    when_updated: ymdhis,
    thread_key: input.thread_id,
    artifact_type: "log",
    artifact_kind: "reference",
    channel_key: "logs",
    "lupopedia.schema": "captain_log",
    prd_cluster: "98_C",
  });
  const record = {
    header,
    type: "captain_log",
    log_id: logId,
    captain_id: "Eric",
    actor_id: 10000,
    timestamp_ymdhis: ymdhis,
    timestamp_iso: packedToIso(ymdhis),
    thread_id: input.thread_id,
    intent: input.intent,
    context: input.context,
    decision: input.decision,
    reasoning: input.reasoning,
    emotional_state: input.emotional_state,
    next_actions: input.next_actions || [],
  };
  const abs = path.join(input.repoRoot, rel);
  writeJson(abs, record);
  return abs;
}

export function writeWolfieLog(input: WolfieLogInput): string {
  const ymdhis = input.timestamp_ymdhis || toPackedUtc();
  const seq = input.seq || "001";
  const logId = `wolfie_${ymdhis}_${seq}`;
  const rel = `docs/logs/${ymdhis.slice(0, 4)}/${ymdhis.slice(4, 6)}/${ymdhis.slice(6, 8)}/${logId}.json`;
  const header = generateConstitutionalHeader({
    path_from_lupopedia_root: rel,
    title: `WOLFIE Log ${logId}`,
    summary: input.observation.slice(0, 200),
    when_updated: ymdhis,
    thread_key: input.thread_id,
    artifact_type: "log",
    artifact_kind: "reference",
    channel_key: "logs",
    "lupopedia.schema": "wolfie_log",
    prd_cluster: "98_C",
  });
  const record = {
    header,
    type: "wolfie_log",
    log_id: logId,
    wolfie_id: "Wolfie",
    actor_id: 1,
    timestamp_ymdhis: ymdhis,
    timestamp_iso: packedToIso(ymdhis),
    thread_id: input.thread_id,
    observation: input.observation,
    state: input.state,
    analysis: input.analysis,
    recommendations: input.recommendations || [],
    alerts: input.alerts || [],
  };
  const abs = path.join(input.repoRoot, rel);
  writeJson(abs, record);
  return abs;
}

export function generateDailyBundle(input: BundleInput): string {
  const parts = input.bundle_date.split("-");
  if (parts.length !== 3) {
    throw new Error("bundle_date must be YYYY-MM-DD (UTC)");
  }
  const dir = path.join(input.repoRoot, "docs", "logs", parts[0], parts[1], parts[2]);
  ensureDir(dir);
  const files = fs.existsSync(dir) ? fs.readdirSync(dir) : [];
  const captain_logs: any[] = [];
  const wolfie_logs: any[] = [];
  for (const f of files) {
    if (!f.endsWith(".json") || f === "bundle.json") continue;
    const obj = JSON.parse(fs.readFileSync(path.join(dir, f), "utf8"));
    if (obj.type === "captain_log" && obj.thread_id === input.thread_id) {
      captain_logs.push(obj);
    } else if (obj.type === "wolfie_log" && obj.thread_id === input.thread_id) {
      wolfie_logs.push(obj);
    }
  }
  const rel = `docs/logs/${parts[0]}/${parts[1]}/${parts[2]}/bundle.json`;
  const when = toPackedUtc();
  const header = generateConstitutionalHeader({
    path_from_lupopedia_root: rel,
    title: `Dual Log Bundle ${input.bundle_date}`,
    summary: input.summary.slice(0, 200),
    when_updated: when,
    thread_key: input.thread_id,
    artifact_type: "log",
    artifact_kind: "reference",
    channel_key: "logs",
    "lupopedia.schema": "daily_bundle",
    prd_cluster: "98_C",
  });
  const bundle = {
    header,
    bundle_date: input.bundle_date,
    thread_id: input.thread_id,
    captain_logs,
    wolfie_logs,
    semantic_links: input.semantic_links || [],
    summary: input.summary,
  };
  const abs = path.join(input.repoRoot, rel);
  writeJson(abs, bundle);
  return abs;
}
