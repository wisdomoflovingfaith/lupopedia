const fs = require('fs');
const path = require('path');

const srcDir = path.join(__dirname, 'src');

const filesToCreate = {
    'rules/ruleLoader.ts': `import * as vscode from 'vscode';\nexport function loadRules() { return []; }`,
    'rules/ruleEnforcer.ts': `import * as vscode from 'vscode';\nexport function enforceRules() { return true; }`,
    'rules/ruleUI.ts': `import * as vscode from 'vscode';\nexport function showRuleUI() {}`,
    'rules/ruleIdIndex.ts': `export const RULE_IDS = ['DB001','DB002','DB003','DB004','DB005','DB006','DB007','DB008','ARC001','ARC002','ACT001','CTX001','GOV001','GOV002','SEC001'];`,
    'rules/ruleProvenance.ts': `export function displayProvenance() {}`,

    'schema/toonLoader.ts': `export function loadToons() { return { tables: 158 }; }`,
    'schema/tableCount.ts': `import * as vscode from 'vscode';\nexport function checkTableCount(count: number) { return count === 158; }`,
    'schema/driftDetector.ts': `export function detectDrift() {}`,
    'schema/tableCompleter.ts': `import * as vscode from 'vscode';\nexport class TableCompleter implements vscode.CompletionItemProvider { provideCompletionItems() { return []; } }`,
    'schema/validator.ts': `export function validateSql() { return true; }`,

    'actor/registryEditor.ts': `export function openRegistryEditor() {}`,
    'actor/nameGenerator.ts': `export function generateActorName(slug: string, type: string) { return slug + '-ide'; }`,
    'actor/checklistHelper.ts': `import * as vscode from 'vscode';\nexport function openChecklist() { vscode.env.openExternal(vscode.Uri.parse('https://lupopedia.com/ACTOR_REGISTRATION_CHECKLIST')); }`,
    'actor/idAllocator.ts': `export function allocateId() { return 106; }`,
    'actor/pairingHelper.ts': `export function getPairedActor() { return 1000; }`,

    'federation/trustViewer.ts': `export function viewTrust() {}`,
    'federation/nodeDiscovery.ts': `export function viewNodes() {}`,

    'logs/unifiedLogViewer.ts': `export function viewLogs() {}`,
    'logs/anubisMonitor.ts': `export function monitorAnubis() {}`,

    'health/snapshotDashboard.ts': `export function viewHealth() {}`,

    'offline/modeDetector.ts': `export function detectMode() { return 'offline'; }`,
    'offline/csvGenerator.ts': `export function generateCsv() {}`,
    'offline/syncPlanner.ts': `export function planSync() {}`,
};

function ensureDir(dir) {
    if (!fs.existsSync(dir)) {
        fs.mkdirSync(dir, { recursive: true });
    }
}

for (const [relPath, content] of Object.entries(filesToCreate)) {
    const fullPath = path.join(srcDir, relPath);
    ensureDir(path.dirname(fullPath));
    if (!fs.existsSync(fullPath)) {
        fs.writeFileSync(fullPath, content);
    }
}

console.log('Scaffold complete.');
