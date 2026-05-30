// src/lupopedia/commands/ForceOffline.ts

import * as vscode from 'vscode';

export async function forceOfflineMode() {
    const selection = await vscode.window.showQuickPick(
        ['Offline Mode (MD only)', 'Hybrid Mode (DB + MD)', 'Online Mode (DB only)'],
        { placeHolder: 'Select Lupopedia operation mode' }
    );

    if (selection) {
        const modeMap: Record<string, string> = {
            'Offline Mode (MD only)': 'offline',
            'Hybrid Mode (DB + MD)': 'hybrid',
            'Online Mode (DB only)': 'online'
        };

        await vscode.workspace.getConfiguration().update(
            'lupopedia.mode',
            modeMap[selection],
            vscode.ConfigurationTarget.Workspace
        );

        vscode.window.showInformationMessage(
            `Lupopedia mode set to: ${selection}`
        );
    }
}
