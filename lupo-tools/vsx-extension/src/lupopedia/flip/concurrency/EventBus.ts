import * as vscode from 'vscode';

export interface SemanticEvent {
    type: 'intent_to_edit' | 'semantic_conflict' | 'collection_update' | 'graph_edge_change';
    actor_id: number;
    file_path: string;
    metadata?: any;
}

export class SemanticEventBus {
    private static _instance: SemanticEventBus;
    private _emitter = new vscode.EventEmitter<SemanticEvent>();

    public readonly onEvent = this._emitter.event;

    private constructor() { }

    public static getInstance(): SemanticEventBus {
        if (!this._instance) {
            this._instance = new SemanticEventBus();
        }
        return this._instance;
    }

    public emit(event: SemanticEvent) {
        console.log(`[SemanticEventBus] Emitting ${event.type} for ${event.file_path}`);
        this._emitter.fire(event);

        // In a real multi-agent sync, this would also broadcast to Channel 42 or a Redis/Socket bus.
        // For VSX extension, we log it to the output channel.
    }

    public dispose() {
        this._emitter.dispose();
    }
}
