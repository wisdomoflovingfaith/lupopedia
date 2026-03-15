import * as vscode from 'vscode';
import { HeaderParser } from '../lupopedia/headers/parser/HeaderParser';
import * as path from 'path';

export class ComplianceProvider {
    private diagnosticCollection: vscode.DiagnosticCollection;
    private headerParser = new HeaderParser();

    constructor(context: vscode.ExtensionContext) {
        this.diagnosticCollection = vscode.languages.createDiagnosticCollection('lupopedia-compliance');
        context.subscriptions.push(this.diagnosticCollection);

        // Listen to events
        vscode.workspace.onDidOpenTextDocument(doc => this.checkCompliance(doc), null, context.subscriptions);
        vscode.workspace.onDidChangeTextDocument(event => this.checkCompliance(event.document), null, context.subscriptions);
        vscode.workspace.onDidSaveTextDocument(doc => this.checkCompliance(doc), null, context.subscriptions);

        // Check active editor on startup
        if (vscode.window.activeTextEditor) {
            this.checkCompliance(vscode.window.activeTextEditor.document);
        }
    }

    private async checkCompliance(document: vscode.TextDocument) {
        // Only check relevant files (md, php, ts, js, sql)
        const relevantExtensions = ['.md', '.php', '.ts', '.js', '.sql'];
        const fileName = document.fileName;
        const ext = path.extname(fileName).toLowerCase();

        if (!relevantExtensions.includes(ext) || fileName.includes('node_modules') || fileName.includes('.git')) {
            return;
        }

        const diagnostics: vscode.Diagnostic[] = [];
        const content = document.getText();
        const header = this.headerParser.extractHeader(content);

        if (!header) {
            // Header Missing Diagnostic
            const range = new vscode.Range(0, 0, 0, 1);
            const diagnostic = new vscode.Diagnostic(
                range,
                "Lupopedia LUPOPEDIA Header Missing. This file is an ANUBIS auto-repair candidate.",
                vscode.DiagnosticSeverity.Information
            );
            diagnostic.code = 'HEADER_MISSING';
            diagnostics.push(diagnostic);
        } else {
            const result = this.headerParser.checkCompliance(header);
            if (!result.compliant) {
                // Determine range of the system_version field
                const range = this.findFieldRange(document, 'system_version');
                const diagnostic = new vscode.Diagnostic(
                    range,
                    result.error || 'Header Outdated. Must align with v4.0.40 compliance gate.',
                    vscode.DiagnosticSeverity.Warning
                );
                diagnostic.code = result.code;
                diagnostics.push(diagnostic);

                // Add ANUBIS assessment
                this.addAnubisAssessment(diagnostics, document, header);
            }
        }

        this.diagnosticCollection.set(document.uri, diagnostics);
    }

    private addAnubisAssessment(diagnostics: vscode.Diagnostic[], document: vscode.TextDocument, header: any) {
        // Logic to determine if it's a deletion candidate or human review candidate
        // For now, if it's very old (e.g. < 4.0.0), mark as possible deletion candidate
        let version = '';
        if ('identity' in header) version = header.identity.system_version;
        else if (header.lupopedia?.headers) version = header.lupopedia.headers.system_version;

        if (version && version.startsWith('4.0.2')) {
            const range = new vscode.Range(0, 0, 0, 1);
            const diagnostic = new vscode.Diagnostic(
                range,
                "ANUBIS Assessment: Outdated legacy file (v4.0.2x). Potential deletion candidate.",
                vscode.DiagnosticSeverity.Information
            );
            diagnostic.code = 'ANUBIS_CANDIDATE';
            diagnostics.push(diagnostic);
        }
    }

    private findFieldRange(document: vscode.TextDocument, fieldName: string): vscode.Range {
        const text = document.getText();
        const regex = new RegExp(`${fieldName}:\\s*["']?([^"']+)["']?`, 'g');
        let match;
        if ((match = regex.exec(text)) !== null) {
            const startPos = document.positionAt(match.index);
            const endPos = document.positionAt(match.index + match[0].length);
            return new vscode.Range(startPos, endPos);
        }
        return new vscode.Range(0, 0, 0, 1);
    }
}
