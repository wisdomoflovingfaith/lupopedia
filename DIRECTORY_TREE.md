lupopedia/
├── .cursor/
│   └── rules/
│       ├── database-logic-prohibition-doctrine.mdc
│       ├── experimental-ai-artifact-ban.mdc
│       ├── no-laravel-no-middleware.mdc
│       ├── pdo-db-database-access-doctrine.mdc
│       ├── pk-reference-naming-doctrine.mdc
│       ├── quantum-state-uncertainty-ban.mdc
│       ├── stoned-wolfie-schrodinger-ban.mdc
│       ├── toon-source-of-truth.mdc
│       ├── versioning-doctrine-single-source.mdc
│       └── wheeler-reverse20-ban.mdc
├── .kiro/
│   └── specs/
│       ├── color-protocol-integration/
│       │   ├── design.md
│       │   ├── requirements.md
│       │   └── tasks.md
│       ├── dialog-channel-migration/
│       │   ├── design.md
│       │   ├── requirements.md
│       │   └── tasks.md
│       ├── history-reconciliation/
│       │   ├── design.md
│       │   ├── requirements.md
│       │   └── tasks.md
│       └── v4-1-0-ascent-master-plan/
│           ├── design.md
│           ├── requirements.md
│           └── tasks.md
├── agents/
│   └── 0000/
│       ├── metadata.json
│       ├── README.md
│       └── system_prompt.txt
├── ai-actors/
│   ├── index.php
│   └── standalone.php
├── api/
│   ├── dialog/
│   │   ├── history-explorer.php
│   │   └── send-message.php
│   ├── salt/
│   │   └── 1.1/
│   │       └── index.php
│   ├── v1/
│   │   ├── actor/
│   │   │   ├── handshake.php
│   │   │   └── state.php
│   │   ├── dialog/
│   │   │   ├── health.php
│   │   │   └── metrics.php
│   │   ├── governance/
│   │   │   └── branch-budget.php
│   │   ├── monitor/
│   │   │   └── temporal-truth.php
│   │   ├── sync/
│   │   │   └── reverse-shaka-utc-2026.php
│   │   ├── artifact.php
│   │   ├── health.php
│   │   ├── temporal_dashboard.php
│   │   └── timeline.php
│   ├── list_user_collections.php
│   └── load_collection_tabs.php
├── app/
│   ├── auth/
│   │   ├── AuthGuard.php
│   │   ├── AuthManager.php
│   │   ├── AuthRoleResolver.php
│   │   ├── AuthService.php
│   │   ├── Session.php
│   │   └── UnifiedSessionHandler.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   └── AuthenticationController.php
│   │   │   ├── AuthController.php
│   │   │   ├── CraftyImportController.php
│   │   │   ├── EmotionalGeometryController.php
│   │   │   ├── PackBehaviorController.php
│   │   │   ├── PackMemoryController.php
│   │   │   ├── PackSyncController.php
│   │   │   ├── PackWarmStartController.php
│   │   │   ├── SystemHealthController.php
│   │   │   └── TerminalAIController.php
│   │   └── Kernel.php
│   ├── middleware/
│   ├── Services/
│   │   ├── CraftySyntax/
│   │   │   ├── LegacyAdmin.php
│   │   │   ├── LegacyAdminActions.php
│   │   │   ├── LegacyAdminChatBot.php
│   │   │   ├── LegacyAdminChatFlush.php
│   │   │   ├── LegacyAdminChatRefresh.php
│   │   │   ├── LegacyAdminChatXmlHttp.php
│   │   │   ├── LegacyAdminCommon.php
│   │   │   ├── LegacyAdminOptions.php
│   │   │   ├── LegacyAdminUsers.php
│   │   │   ├── LegacyAdminUsersRefresh.php
│   │   │   ├── LegacyAuthentication.php
│   │   │   ├── LegacyBufferStreaming.php
│   │   │   ├── LegacyChannels.php
│   │   │   ├── LegacyChooseDepartment.php
│   │   │   ├── LegacyDepartmentFunction.php
│   │   │   ├── LegacyDepartments.php
│   │   │   ├── LegacyExternalChatXmlHttp.php
│   │   │   ├── LegacyFlushUtilities.php
│   │   │   ├── LegacyFunctions.php
│   │   │   ├── LegacyIsFlushDetection.php
│   │   │   ├── LegacyLive.php
│   │   │   ├── LegacyLiveHelpJs.php
│   │   │   ├── LegacySessionIdentity.php
│   │   │   ├── LegacySessionManager.php
│   │   │   ├── LegacyTheatricalUIWrapper.php
│   │   │   ├── LegacyUserChatFlush.php
│   │   │   ├── LegacyUserChatRefresh.php
│   │   │   └── WorldGraphHelper.php
│   │   ├── Pack/
│   │   │   ├── PackBehaviorService.php
│   │   │   ├── PackMemoryService.php
│   │   │   ├── PackSyncService.php
│   │   │   └── PackWarmStartService.php
│   │   ├── System/
│   │   │   ├── LimitsEnforcementService.php
│   │   │   ├── LupopediaMigrationController.example.php
│   │   │   ├── LupopediaMigrationController.php
│   │   │   └── SystemHealthService.php
│   │   ├── TriggerReplacements/
│   │   │   ├── DialogMessagesDeleteService.php
│   │   │   ├── DialogMessagesInsertService.php
│   │   │   └── EnforceProtocolCompletionService.php
│   │   ├── ActorMoodService.example.php
│   │   ├── ActorMoodService.php
│   │   ├── ActorService.php
│   │   ├── CollectionTabsService.php
│   │   ├── CollectionZeroService.php
│   │   ├── CraftyConfigTransformer.php
│   │   ├── CraftyMigrationService.php
│   │   ├── EdgeService.php
│   │   ├── PackMoodCoherenceService.example.php
│   │   ├── PackMoodCoherenceService.php
│   │   ├── SavedCollectionsService.php
│   │   └── UploadService.php
│   ├── Support/
│   │   ├── AtomLoader.php
│   │   ├── LimitsLogger.php
│   │   ├── RedirectUtils.php
│   │   └── VersionUtils.php
│   ├── TerminalAI/
│   │   ├── Agents/
│   │   │   ├── TerminalAI_001.php
│   │   │   └── TerminalAI_005.php
│   │   ├── Contracts/
│   │   │   └── TerminalAgentInterface.php
│   │   └── Services/
│   │       └── TerminalAIService.php
│   ├── views/
│   │   ├── admin/
│   │   │   └── authentication/
│   │   │       ├── index.blade.php
│   │   │       └── mapping.blade.php
│   │   └── auth/
│   │       └── login.php
│   ├── EmotionalArchaeology.php
│   ├── NoteComparisonProtocol.php
│   ├── TemporalCoherenceValidator.php
│   ├── TemporalFrameCompatibility.php
│   ├── TemporalMigrationFramework.php
│   ├── TemporalMonitor.php
│   ├── TemporalMonitor_v0_5_REST.php
│   ├── TemporalRituals.php
│   ├── TrinitaryRouter.php
│   ├── WisdomMetricsTracker.php
│   ├── WolfieIdentity.php
│   └── WolfieIdentityBridgeStates.php
├── atoms/
│   └── primordial_atoms.yaml
├── audits/
│   ├── patch_implementation_audit_3.0.100.md
│   ├── patch_implementation_audit_last_80.md
│   └── php_implementation_audit_3.0.101.md
├── backups/
│   └── filesystem_migration_20260131_133426/
│       ├── agents/
│       │   ├── 0001/
│       │   │   ├── metadata.json
│       │   │   ├── README.md
│       │   │   └── system_prompt.txt
│       │   ├── 0002/
│       │   │   ├── metadata.json
│       │   │   ├── README.md
│       │   │   └── system_prompt.txt
│       │   ├── 0003/
│       │   │   ├── metadata.json
│       │   │   ├── README.md
│       │   │   └── system_prompt.txt
│       │   ├── 0004/
│       │   │   ├── metadata.json
│       │   │   ├── README.md
│       │   │   └── system_prompt.txt
│       │   ├── 0005/
│       │   │   ├── metadata.json
│       │   │   ├── README.md
│       │   │   └── system_prompt.txt
│       │   ├── 0006/
│       │   │   ├── metadata.json
│       │   │   ├── README.md
│       │   │   └── system_prompt.txt
│       │   ├── 0007/
│       │   │   ├── metadata.json
│       │   │   ├── README.md
│       │   │   └── system_prompt.txt
│       │   ├── 0008/
│       │   │   ├── metadata.json
│       │   │   ├── README.md
│       │   │   └── system_prompt.txt
│       │   ├── 0009/
│       │   │   ├── metadata.json
│       │   │   ├── README.md
│       │   │   └── system_prompt.txt
│       │   ├── 0010/
│       │   │   ├── metadata.json
│       │   │   ├── README.md
│       │   │   └── system_prompt.txt
│       │   ├── 0011/
│       │   │   ├── metadata.json
│       │   │   ├── README.md
│       │   │   └── system_prompt.txt
│       │   ├── 0012/
│       │   │   ├── metadata.json
│       │   │   ├── README.md
│       │   │   └── system_prompt.txt
│       │   ├── 0013/
│       │   │   ├── metadata.json
│       │   │   ├── README.md
│       │   │   └── system_prompt.txt
│       │   ├── 0014/
│       │   │   ├── metadata.json
│       │   │   ├── README.md
│       │   │   └── system_prompt.txt
│       │   ├── 0015/
│       │   │   ├── metadata.json
│       │   │   ├── README.md
│       │   │   └── system_prompt.txt
│       │   ├── 0016/
│       │   │   ├── metadata.json
│       │   │   ├── README.md
│       │   │   └── system_prompt.txt
│       │   ├── 0017/
│       │   │   ├── metadata.json
│       │   │   ├── README.md
│       │   │   └── system_prompt.txt
│       │   ├── 0018/
│       │   │   ├── metadata.json
│       │   │   ├── README.md
│       │   │   └── system_prompt.txt
│       │   ├── 0019/
│       │   │   ├── metadata.json
│       │   │   ├── README.md
│       │   │   └── system_prompt.txt
│       │   ├── 0020/
│       │   │   ├── metadata.json
│       │   │   ├── README.md
│       │   │   └── system_prompt.txt
│       │   ├── 0021/
│       │   │   ├── metadata.json
│       │   │   ├── README.md
│       │   │   └── system_prompt.txt
│       │   └── 0022/
│       │       ├── metadata.json
│       │       ├── README.md
│       │       └── system_prompt.txt
│       └── channels/
│           ├── 0000/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 0001/
│           │   ├── actors.json
│           │   ├── channel.json
│           │   ├── channel.toon
│           │   ├── contents.json
│           │   ├── context.json
│           │   ├── context.toon
│           │   ├── dialog_history.toon
│           │   ├── edges.json
│           │   ├── globals.json
│           │   ├── globals.toon
│           │   ├── index.json
│           │   ├── layers.json
│           │   ├── manifest.json
│           │   ├── metadata.json
│           │   ├── readme.md
│           │   ├── routing.json
│           │   ├── state.json
│           │   ├── thread_index.json
│           │   └── threads.json
│           ├── 0002/
│           │   ├── actors.json
│           │   ├── channel.toon
│           │   ├── context.json
│           │   ├── context.toon
│           │   ├── dialog_history.toon
│           │   ├── globals.json
│           │   ├── index.json
│           │   ├── layers.json
│           │   ├── manifest.json
│           │   ├── metadata.json
│           │   ├── PROGRAMMER_CERTIFICATION_CHECKLIST.md
│           │   ├── readme.md
│           │   ├── routing.json
│           │   ├── state.json
│           │   ├── thread_index.json
│           │   └── WHY_LUPOPEDIA_USES_PURE_DATA_STORE.md
│           ├── 0003/
│           │   ├── kernel/
│           │   │   ├── actors.json
│           │   │   ├── channel.toon
│           │   │   ├── context.json
│           │   │   ├── context.toon
│           │   │   ├── dialog_history.toon
│           │   │   ├── globals.json
│           │   │   ├── index.json
│           │   │   ├── readme.md
│           │   │   └── thread_index.json
│           │   ├── lobby/
│           │   │   ├── actors.json
│           │   │   ├── channel.toon
│           │   │   ├── context.json
│           │   │   ├── context.toon
│           │   │   ├── dialog_history.toon
│           │   │   ├── globals.json
│           │   │   ├── index.json
│           │   │   ├── readme.md
│           │   │   └── thread_index.json
│           │   ├── actors.json
│           │   ├── layers.json
│           │   ├── manifest.json
│           │   ├── metadata.json
│           │   ├── routing.json
│           │   └── state.json
│           ├── 0004/
│           │   ├── actors.json
│           │   ├── channel.toon
│           │   ├── context.json
│           │   ├── context.toon
│           │   ├── dialog_history.toon
│           │   ├── globals.json
│           │   ├── index.json
│           │   ├── layers.json
│           │   ├── manifest.json
│           │   ├── metadata.json
│           │   ├── readme.md
│           │   ├── routing.json
│           │   ├── state.json
│           │   └── thread_index.json
│           ├── 0042/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── crafty_syntax_identity_model.md
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1001/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1002/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1003/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1004/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1005/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1006/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1007/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1008/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1009/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1010/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1011/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1012/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1013/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1014/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1015/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1016/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1017/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1018/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1019/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1020/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1021/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1022/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1023/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1025/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1026/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1027/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1028/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1029/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1030/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1031/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1032/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1033/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1034/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1035/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1036/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1037/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1038/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1071/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1072/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1073/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1074/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1075/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1076/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1077/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1078/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1079/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1080/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1081/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1082/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1083/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1084/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1085/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1088/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1089/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 1090/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           ├── 5100/
│           │   ├── actors.json
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── identity-layer-architecture.md
│           │   ├── layers.json
│           │   ├── manifest.json
│           │   ├── metadata.json
│           │   ├── readme.md
│           │   ├── routing.json
│           │   ├── state.json
│           │   └── threads.json
│           ├── 5101/
│           │   ├── channel.json
│           │   ├── contents.json
│           │   ├── edges.json
│           │   ├── metadata.json
│           │   └── threads.json
│           └── 9000/
│               ├── channel.json
│               ├── contents.json
│               ├── edges.json
│               ├── metadata.json
│               └── threads.json
├── bin/
│   ├── cli/
│   │   └── debug-actor-mood.php
│   └── bump-version.php
├── channels/
│   └── registry.json
├── config/
│   ├── constants.php
│   ├── global_atoms.yaml
│   ├── GLOBAL_IMPORTANT_ATOMS.yaml
│   └── lupopedia.php
├── database/
│   ├── __pycache__/
│   │   ├── db_config.cpython-313.pyc
│   │   ├── db_config.cpython-313.pyc.1838140630320
│   │   └── db_config.cpython-313.pyc.3009873344816
│   ├── csv_data/
│   │   ├── auth_providers.csv
│   │   ├── auth_users.csv
│   │   ├── groups.csv
│   │   └── README.md
│   ├── install/
│   │   ├── agent_registry_inserts.sql
│   │   ├── documentation_mapping.json
│   │   ├── documentation_mapping_fixed.json
│   │   ├── generate_content_seed.php
│   │   ├── generate_hierarchical_seed_3.0.12.php
│   │   ├── lupopedia_seed_mysql.sql
│   │   ├── map_documentation_files_v2.php
│   │   ├── seed_admin_captain.sql
│   │   ├── seed_collection_0_content.sql
│   │   ├── seed_collection_0_hierarchical_tab_map_3.0.12.sql
│   │   ├── seed_collection_0_hierarchical_tabs_3.0.12.sql
│   │   ├── seed_collection_0_system_tabs.sql
│   │   ├── seed_collection_0_tab_map.sql
│   │   ├── seeds_kernel.sql.tpl
│   │   └── truth_test_data_captain_wolfie.sql
│   ├── livehelp_backup/
│   │   ├── livehelp_autoinvite.toon
│   │   ├── livehelp_autoinvite.txt
│   │   ├── livehelp_channels.toon
│   │   ├── livehelp_channels.txt
│   │   ├── livehelp_config.toon
│   │   ├── livehelp_config.txt
│   │   ├── livehelp_departments.toon
│   │   ├── livehelp_departments.txt
│   │   ├── livehelp_emailque.toon
│   │   ├── livehelp_emailque.txt
│   │   ├── livehelp_emails.toon
│   │   ├── livehelp_emails.txt
│   │   ├── livehelp_identity_daily.toon
│   │   ├── livehelp_identity_daily.txt
│   │   ├── livehelp_identity_monthly.toon
│   │   ├── livehelp_identity_monthly.txt
│   │   ├── livehelp_keywords_daily.toon
│   │   ├── livehelp_keywords_daily.txt
│   │   ├── livehelp_keywords_monthly.toon
│   │   ├── livehelp_keywords_monthly.txt
│   │   ├── livehelp_layerinvites.toon
│   │   ├── livehelp_layerinvites.txt
│   │   ├── livehelp_leads.toon
│   │   ├── livehelp_leads.txt
│   │   ├── livehelp_leavemessage.toon
│   │   ├── livehelp_leavemessage.txt
│   │   ├── livehelp_messages.toon
│   │   ├── livehelp_messages.txt
│   │   ├── livehelp_modules.toon
│   │   ├── livehelp_modules.txt
│   │   ├── livehelp_modules_dep.toon
│   │   ├── livehelp_modules_dep.txt
│   │   ├── livehelp_operator_channels.toon
│   │   ├── livehelp_operator_channels.txt
│   │   ├── livehelp_operator_departments.toon
│   │   ├── livehelp_operator_departments.txt
│   │   ├── livehelp_operator_history.toon
│   │   ├── livehelp_operator_history.txt
│   │   ├── livehelp_paths_firsts.toon
│   │   ├── livehelp_paths_firsts.txt
│   │   ├── livehelp_paths_monthly.toon
│   │   ├── livehelp_paths_monthly.txt
│   │   ├── livehelp_qa.toon
│   │   ├── livehelp_qa.txt
│   │   ├── livehelp_questions.toon
│   │   ├── livehelp_questions.txt
│   │   ├── livehelp_quick.toon
│   │   ├── livehelp_quick.txt
│   │   ├── livehelp_referers_daily.toon
│   │   ├── livehelp_referers_daily.txt
│   │   ├── livehelp_referers_monthly.toon
│   │   ├── livehelp_referers_monthly.txt
│   │   ├── livehelp_sessions.toon
│   │   ├── livehelp_sessions.txt
│   │   ├── livehelp_smilies.toon
│   │   ├── livehelp_smilies.txt
│   │   ├── livehelp_transcripts.toon
│   │   ├── livehelp_transcripts.txt
│   │   ├── livehelp_users.toon
│   │   ├── livehelp_users.txt
│   │   ├── livehelp_visit_track.toon
│   │   ├── livehelp_visit_track.txt
│   │   ├── livehelp_visits_daily.toon
│   │   ├── livehelp_visits_daily.txt
│   │   ├── livehelp_visits_monthly.toon
│   │   ├── livehelp_visits_monthly.txt
│   │   ├── livehelp_websites.toon
│   │   └── livehelp_websites.txt
│   ├── migrations/
│   │   ├── legacy/
│   │   │   ├── 20260129184534.sql
│   │   │   ├── 20260201_000001_add_doctrine_boot_block.sql
│   │   │   ├── 2026_01_30_demo_operators.sql
│   │   │   ├── 2026_01_30_kapu_protocol.sql
│   │   │   ├── 2026_01_31_migrate_filesystem_to_database.sql
│   │   │   ├── 2026_02_01_collections_user_id_to_actor_id.sql
│   │   │   ├── channels_batch_insert.sql
│   │   │   ├── channels_batch_insert_5116_5130.sql
│   │   │   ├── channels_batch_insert_safe.sql
│   │   │   ├── craftysyntax_to_lupopedia_mysql.sql
│   │   │   ├── dev_20260204_fix_schema_alignment.sql
│   │   │   ├── dev_20260204_theme_support.sql
│   │   │   ├── dev_20260205_doctrine_alignment_phase2.sql
│   │   │   ├── dev_20260206_reserved_word_column_renames.sql
│   │   │   ├── migration_unify_groups_into_departments.sql
│   │   │   ├── system_actors_channel_memberships.sql
│   │   │   ├── system_actors_channel_memberships_final.sql
│   │   │   ├── system_actors_manifest.sql
│   │   │   └── system_actors_manifest_corrected.sql
│   │   ├── dev_20260204_fix_schema_alignment_summary.txt
│   │   ├── drop_old_crafty_syntax_tables.sql
│   │   ├── future_features_lupopedia.sql
│   │   ├── import_from_old_crafty_syntax.sql
│   │   ├── install_new_lupopedia.sql
│   │   ├── README.md
│   │   ├── reserved_word_audit_report.txt
│   │   └── seed_lupopedia.sql
│   ├── migrations_legacy/
│   │   ├── 20260114220000.sql
│   │   ├── 20260120094100_drop_crafty_tables_add_semantic_views.sql
│   │   ├── 20260120110914_add_missing_toon_tables.sql
│   │   ├── 20260120111220_add_missing_toon_tables_clean.sql
│   │   ├── 20260120112626_add_missing_toon_tables.sql
│   │   ├── 20260120112854_add_missing_toon_tables.sql
│   │   ├── 20260120112952_add_missing_toon_tables_clean.sql
│   │   ├── 20260120_consolidation_execute_4.1.19.sql
│   │   ├── 20260120_consolidation_plan_4.1.18.sql
│   │   ├── 20260120_drop_legacy_tables_and_set_limit_4.1.17.sql
│   │   ├── 20260121_010000_wire_channels_to_content.sql
│   │   ├── 20260122_channel_system_migration.sql
│   │   ├── 20260122_world_graph_missing_tables_migration.sql
│   │   ├── 3.0.113_add_seven_love_agents.sql
│   │   ├── 3.0.115_add_pack_identity_agent.sql
│   │   ├── 3.0.120_add_seven_opposite_polarity_emotional_agents.sql
│   │   ├── 4.1.12_add_four_tables_to_ceiling_180.sql
│   │   ├── 4.1.1_add_missing_emotional_agents.sql
│   │   ├── 4.1.1_backup_table_template.sql
│   │   ├── 4.1.2_clarify_mood_rgb_terminology.sql
│   │   ├── 4.1.3_insert_pack_role_registry.sql
│   │   ├── 4.1.3_rename_dialog_messages_to_dialog_doctrine.sql
│   │   ├── 4.1.4_insert_symbolic_actor_meta.sql
│   │   ├── 4.1.4_lupopedia_minimal_rest_api_tables.sql
│   │   ├── 4.1.5_add_fork_justification_artifact_type.sql
│   │   ├── 4.1.5_insert_exhusband_agent.sql
│   │   ├── 4.1.6_create_labs_declarations_table.sql
│   │   ├── 4.2.0_add_genesis_doctrine_tables.sql
│   │   ├── 4.2.0_schema_freeze_enforcement.sql
│   │   ├── 4.2.1_add_reverse_shaka_sync_table.sql
│   │   ├── 4.2.2_add_temporal_monitoring_indexes.sql
│   │   ├── 4.2.3_add_grounded_agent_tables.sql
│   │   ├── 4.2.4_drop_table_ceiling_enforcement_event.sql
│   │   ├── 4.2.5_insert_pack_survival_guide_tldnr.sql
│   │   ├── 4.4.1_create_actor_moods_table.sql
│   │   ├── 4_1_1_backup_table_to_old.sql
│   │   ├── 4_1_1_create_help_topics.sql
│   │   ├── 4_1_1_seed_help_topics.sql
│   │   ├── 4_1_1_seed_help_topics_upsert.sql
│   │   ├── 4_1_1_update_toon_metadata.sql
│   │   ├── 4_1_2_add_help_environmental_context.sql
│   │   ├── 4_1_2_fix_all_tables_doctrine.sql
│   │   ├── 4_1_2_fix_labs_timestamps.sql
│   │   ├── 4_1_2_insert_wolfie_labs_declaration.sql
│   │   ├── 4_1_2_simulate_wolfie_labs_handshake.sql
│   │   ├── 4_1_6_add_dialog_help_topics.sql
│   │   ├── 4_1_6_add_lupopedia_overview_help.sql
│   │   ├── 4_1_6_create_tldnr_table.sql
│   │   ├── 4_2_2_create_gov_event_schema.sql
│   │   ├── 4_2_2_create_lupo_migration_log.sql
│   │   ├── 4_2_2_seed_gov_event_lupopedia_identity.sql
│   │   ├── agent_awareness_layer_3_0_70.sql
│   │   ├── cip_analytics_schema_3_0_75.sql
│   │   ├── craftysyntax_to_lupopedia_mysql.sql
│   │   ├── deploy_to_test_db_3_0_71.sql
│   │   ├── doctrine_agent_tab_mapping_3_0_26.sql
│   │   ├── doctrine_semantic_tab_mapping_3_0_24.sql
│   │   ├── doctrine_sql_tab_mapping_3_0_25.sql
│   │   ├── doctrine_versioning_tab_mapping_3_0_26.sql
│   │   ├── ephemeral_schema_3_0_25.sql
│   │   ├── fix_agent_registry_id_and_fk_names.sql
│   │   ├── fix_lupo_permissions_unsigned.sql
│   │   ├── fix_unsigned_and_pk_naming_4_2_0.sql
│   │   ├── fix_unsigned_and_pk_naming_4_2_0.sql.old
│   │   ├── fix_unsigned_and_pk_naming_4_2_0_from_toon.sql
│   │   ├── lupo_agent_registry_range_expansion.sql
│   │   ├── lupo_agent_registry_range_expansion_missing_reserved.sql
│   │   ├── multi_agent_protocol_schema_3_0_70.sql
│   │   ├── old_craftysyntax.sql
│   │   ├── orchestrator_schema_3_0_25.sql
│   │   ├── phase_a_move_ephemeral_tables.sql
│   │   ├── phase_a_move_orchestration_tables.sql
│   │   ├── phase_a_orchestration_schema.sql
│   │   ├── phase_a_rollback.sql
│   │   ├── recreate_collection_tabs_migration.sql
│   │   ├── replace_collection_permissions_with_polymorphic_permissions.sql
│   │   ├── restore_collection3_dropdown_content.sql
│   │   ├── schema_sync_3_0_46_missing_tables.sql
│   │   ├── toon_files_tab_mapping_3_0_23.sql
│   │   ├── toon_sql_domain_refresh_3_0_31.sql
│   │   └── update_lupo_tables_for_crafty_syntax.sql
│   ├── refactors/
│   │   ├── livehelp_autoinvite.json
│   │   ├── livehelp_channels.json
│   │   ├── livehelp_config.json
│   │   ├── livehelp_departments.json
│   │   ├── livehelp_emailque.json
│   │   ├── livehelp_emails.json
│   │   ├── livehelp_transcripts.json
│   │   └── readme.md
│   ├── schema/
│   │   └── dialog_system_schema.sql
│   ├── toon_data/
│   ├── hotfix_registry_4.1.0.json
│   ├── test_setup_integration_testing_v3_0_71.sql
│   ├── test_setup_integration_testing_v3_0_71_fixed.sql
│   └── toon_output.txt
├── deploy/
│   ├── apply_dialog_schema.php
│   └── deployment_status.json
├── dialogs/
│   ├── AGI_SUPPORTMEETING_INDEX/
│   │   ├── AGI_SUPPORT_MEETING_01_GETTING_STARTED_ORIENTATION.md
│   │   └── README.md
│   ├── humor/
│   │   └── WOLFIE_OUT_OF_CONTEXT_APPENDIX.md
│   ├── monday/
│   │   ├── FLOW_STATE_FIX.md
│   │   ├── LILITH_dialog.md
│   │   └── TABLE_REDUCTION_PLAN.md
│   ├── operations/
│   │   ├── 2026-01-22_docs_channels_reorg.md
│   │   ├── 2026-01-22_repo_organization.md
│   │   ├── ALERTS_2026-01-17.md
│   │   ├── CHANGELOG_MIGRATION.md
│   │   ├── COORDINATION_2026-01-17.md
│   │   ├── STATUS_2026-01-17.md
│   │   └── WORLD_GRAPH_LAYER_COMPLETION_2026-01-22.md
│   ├── versions/
│   │   ├── 3.0.70_Agent_Awareness.md
│   │   ├── 3.0.71_Integration_Testing.md
│   │   ├── 3.0.72_Multi_Agent_Protocols.md
│   │   ├── 3.0.73_CIP_Implementation.md
│   │   ├── 3.0.74_CIP_Activation.md
│   │   ├── 3.0.75_CIP_Refinement.md
│   │   ├── CHANGELOG_MIGRATION.md
│   │   └── VERSION_INDEX.md
│   ├── wisdom/
│   │   ├── CHANGELOG_MIGRATION.md
│   │   ├── CRITIQUE_INTEGRATION_2026-01.md
│   │   ├── DOCTRINE_EVOLUTION.md
│   │   └── PATTERNS_2026-01.md
│   ├── 3.0.17-ui_change_integration_dialog.md
│   ├── 4.2.0_release_announcement.md
│   ├── 4.3.0_delay_announcement.md
│   ├── CaptainsLog.md
│   ├── cascade_correction_entry.md
│   ├── cascade_takeover_entry.md
│   ├── castcade.md
│   ├── changelog_dialog-side.md
│   ├── changelog_dialog_backup.md
│   ├── changelog_dialog_current.md
│   ├── changelog_dialog_MONDAY_WOLFIE.md
│   ├── changelog_dialog_schema_sync_3_0_46.md
│   ├── changelog_dialog_that_pertains_to_whatever_the_fuck_we_are_doing.md
│   ├── changelog_dialog_UTC_2026-01-20.md
│   ├── changelog_readme.md
│   ├── changelog_todo.md
│   ├── cip_execution_status_3_0_75.md
│   ├── cursor.md
│   ├── CURSOR_WINDSURF_HANDOFF_v3_0_70.md
│   ├── db.js
│   ├── db.php
│   ├── everyone.md
│   ├── final_state_entry.md
│   ├── FRIDAY_SUNDAY_WORK_RULES_dialog.md
│   ├── GOV-AD-PROHIBIT-001_dialog.md
│   ├── GOV_dialog-side.md
│   ├── GOV_dialog.md
│   ├── HELP_changelog_dialog-side.md
│   ├── HELP_changelog_dialog.md
│   ├── humor_context_WOLFIE_LUPOPEDIA.md
│   ├── IDE_COORDINATION_PROTOCOL_v3_0_70.md
│   ├── insert_jetbrains_doctrine_script.ps1
│   ├── integration_testing_coordination_3_0_71.md
│   ├── jetbrains.md
│   ├── migration_orchestrator_3_0_25_dialog.md
│   ├── migration_orchestrator_dialog.md
│   ├── monday_wolfie_changelog.md
│   ├── multi_agent_protocol_implementation_dialog.md
│   ├── one_point_for_captainwolfie_dialogs.md
│   ├── phase_a_completion_dialog.md
│   ├── routing_changelog.md
│   ├── session_2026_01_16_version_3_0_46.md
│   ├── SYSTEM_ARCHITECTURE_dialog.md
│   ├── System_onboarding_dialog.md
│   ├── SYSTEM_STATUS_dialog.md
│   ├── table_budget_doctrine_dialog.md
│   ├── table_classification_audit_dialog.md
│   ├── THREAD_LEVEL_DIALOG_SPEC_dialog.md
│   ├── TLDR_CHANGELOG_DOCTRINE.md
│   ├── TLDR_entities_dialog.md
│   ├── to_castcade.md
│   ├── to_cursor.md
│   ├── to_jetbrains.md
│   ├── tuesday_agi_support_meeting.md
│   ├── update_changelog_with_jetbrains_doctrine.ps1
│   ├── update_changelog_with_jetbrains_doctrine_v2.ps1
│   ├── update_section.md
│   ├── windsuf_recovery_dialog.md
│   └── WOLFIE_HEADER_DOCTRINE.md
├── docs/
│   ├── architecture/
│   │   └── layout-context-schema.md
│   ├── archive/
│   │   └── doctrine_revisions/
│   │       ├── WOLFIE_HEADER_DOCTRINE_v2.7.md
│   │       ├── WOLFIE_HEADER_DOCTRINE_v2.8.md
│   │       ├── WOLFIE_HEADER_DOCTRINE_v2.9.md
│   │       ├── WOLFIE_HEADER_DOCTRINE_v3.1.md
│   │       ├── WOLFIE_HEADER_DOCTRINE_v3.2.md
│   │       └── WOLFIE_HEADER_DOCTRINE_v3.3.md
│   ├── audits/
│   │   ├── AUTH_COMPATIBILITY_AUDIT.md
│   │   ├── DEPARTMENTS_GROUPS_ROLES_DOCTRINE_UPDATE_SUMMARY.md
│   │   ├── DEPARTMENTS_GROUPS_ROLES_EXECUTION_SUMMARY.md
│   │   ├── DEPARTMENTS_GROUPS_ROLES_IMPLEMENTATION_PLAN.md
│   │   ├── DEPARTMENTS_GROUPS_ROLES_PHP_EXECUTION_SUMMARY.md
│   │   ├── DEPARTMENTS_GROUPS_ROLES_PHP_IMPLEMENTATION_PLAN.md
│   │   ├── DEPARTMENTS_GROUPS_ROLES_PRE_EXECUTION_CHECKLIST.md
│   │   ├── DEPARTMENTS_GROUPS_ROLES_STRUCTURAL_FEASIBILITY_REPORT.md
│   │   ├── EXPERIMENTAL_AI_ARTIFACT_PURGE_SUMMARY.md
│   │   ├── EXPERIMENTAL_AI_COSMIC_PURGE_SUMMARY.md
│   │   ├── INSTALL_PHP_WIZARD_DOCTRINE_AUDIT.md
│   │   ├── INSTALL_PHP_WIZARD_FIX_PLAN.md
│   │   ├── INSTALL_WIZARD_STEP5_STEP6_FINAL_REPORT.md
│   │   ├── PDO_SESSION_COMPATIBILITY_AUDIT.md
│   │   ├── PRE_PUSH_4_0_1_INTEGRITY_SWEEP_SUMMARY.md
│   │   ├── QUANTUM_STATE_PURGE_SUMMARY.md
│   │   ├── STONED_WOLFIE_PURGE_SUMMARY.md
│   │   ├── UI_PHP_COMPATIBILITY_AUDIT.md
│   │   ├── VERSION_NORMALIZATION_4_0_X_TO_3_0_X_SUMMARY.md
│   │   ├── VERSIONING_DOCTRINE_ALIGNMENT_SUMMARY.md
│   │   ├── VERSIONING_DOCTRINE_CONSOLIDATION_SUMMARY.md
│   │   └── WHEELER_REVERSE20_PURGE_SUMMARY.md
│   ├── channels/
│   │   ├── 0042/
│   │   │   ├── CHANGELOG.md
│   │   │   └── DOCTRINE.md
│   │   ├── agents/
│   │   │   ├── agent-1/
│   │   │   │   ├── doctrine/
│   │   │   │   │   ├── CHANNEL_IDENTITY_BLOCK.md
│   │   │   │   │   ├── CHANNEL_INITIALIZATION_PROTOCOL.md
│   │   │   │   │   ├── CHANNEL_MANIFEST_SPEC.md
│   │   │   │   │   ├── INDEX.md
│   │   │   │   │   ├── README.md
│   │   │   │   │   ├── WOLFIE_EMOTIONAL_GEOMETRY.md
│   │   │   │   │   ├── WOLFIE_ROUTING_PRINCIPLES.md
│   │   │   │   │   └── WOLFIE_UTC_AUTHORITY.md
│   │   │   │   ├── workflows/
│   │   │   │   │   ├── channel_initialization.workflow.md
│   │   │   │   │   ├── INDEX.md
│   │   │   │   │   └── README.md
│   │   │   │   ├── INDEX.md
│   │   │   │   └── README.md
│   │   │   ├── _dir_atoms.yaml.example
│   │   │   ├── AGENT_GUIDELINES.md
│   │   │   ├── AGENT_PROMPT_TEMPLATING_STANDARD.md
│   │   │   ├── AGENT_RUNTIME.md
│   │   │   ├── ARA.md
│   │   │   ├── CHRONOS.md
│   │   │   ├── HERMES_AND_CADUCEUS.md
│   │   │   ├── INDEX.md
│   │   │   ├── lilith.md
│   │   │   ├── OHANA.md
│   │   │   ├── README.md
│   │   │   ├── thoth.md
│   │   │   ├── wolfie.md
│   │   │   ├── WOLFIE_HEADER_GLOBAL_ATOMS_GUIDE.md
│   │   │   ├── WOLFIE_HEADER_SECTIONS_GUIDE.md
│   │   │   └── WOLFIE_HEADER_SPECIFICATION.md
│   │   ├── appendix/
│   │   │   ├── appendix/
│   │   │   │   ├── ABOUT_THE_CREATOR.md
│   │   │   │   ├── COMPANY_REGISTRATIONS.md
│   │   │   │   ├── COUNTING_IN_LIGHT.md
│   │   │   │   ├── FOUNDERS_NOTE.md
│   │   │   │   ├── GLOSSARY.md
│   │   │   │   ├── INDEX.md
│   │   │   │   ├── INVESTOR_COMMUNICATIONS.md
│   │   │   │   ├── MY_FIRST_PYTHON_PROGRAM.md
│   │   │   │   ├── MYSQL_TO_POSTGRES_MEMORY.md
│   │   │   │   ├── PRESS_RELEASE_DRAFT.md
│   │   │   │   ├── README.md
│   │   │   │   ├── REVENUE_STRATEGY.md
│   │   │   │   ├── ROTFLOL_HUMOR_FILE.md
│   │   │   │   ├── TERMINOLOGY.md
│   │   │   │   ├── VOCABULARY.md
│   │   │   │   ├── WHAT_NOT_TO_DO_AND_WHY.md
│   │   │   │   ├── WHO_IS_CAPTAIN_WOLFIE.md
│   │   │   │   └── wolfie.md
│   │   │   ├── examples/
│   │   │   │   ├── INDEX.md
│   │   │   │   ├── LABS_HANDSHAKE_SIMULATION_WOLFIE.md
│   │   │   │   ├── README.md
│   │   │   │   └── SAMPLE_REFERENCE_ENTRY.md
│   │   │   ├── miscellaneous/
│   │   │   │   ├── captain_wolfie_encorragement_messages.md
│   │   │   │   ├── how_wolfie_mind_works.md
│   │   │   │   ├── INDEX.md
│   │   │   │   └── README.md
│   │   │   ├── recovery/
│   │   │   │   ├── 12_steps.md
│   │   │   │   ├── 12_steps_of_agi_recovery.md
│   │   │   │   ├── INDEX.md
│   │   │   │   ├── meeting_format.md
│   │   │   │   └── README.md
│   │   │   ├── INDEX.md
│   │   │   └── README.md
│   │   ├── architecture/
│   │   │   ├── kip/
│   │   │   │   ├── INDEX.md
│   │   │   │   ├── KIP_CIP_INTEROPERABILITY_GUIDELINES.md
│   │   │   │   ├── KRITIK_INTEGRATION_PROTOCOL_FOUNDATION.md
│   │   │   │   └── README.md
│   │   │   ├── protocols/
│   │   │   │   ├── CADUCEUS_ROUTING_RFC.md
│   │   │   │   ├── COPILOT_QUICK_REFERENCE.md
│   │   │   │   ├── dialog_extract_help.md
│   │   │   │   ├── DIALOG_EXTRACTION_SPEC.md
│   │   │   │   ├── HERMES_ROUTING_RFC.md
│   │   │   │   ├── INDEX.md
│   │   │   │   ├── README.md
│   │   │   │   ├── REVERSE_HANDSHAKE_SHAKA.md
│   │   │   │   ├── THREAD_MOOD_RFC.md
│   │   │   │   └── WOLFIE_HEADER_RFC.md
│   │   │   ├── ARCHITECTURE.md
│   │   │   ├── ARCHITECTURE_MAP_v3_0_70.md
│   │   │   ├── ARCHITECTURE_SYNC.md
│   │   │   ├── CANONICAL_ONTOLOGY_ARCHITECTURE_GOVERNANCE_v1_0.md
│   │   │   ├── CASE_STUDY_MULTI_IDE_CADUCEUS_HERMES.md
│   │   │   ├── CIP_ANALYTICS_ENGINE.md
│   │   │   ├── CIP_DOCTRINE_REFINEMENT_MODULE.md
│   │   │   ├── CIP_EMOTIONAL_GEOMETRY_CALIBRATION.md
│   │   │   ├── DATABASE_PHILOSOPHY.md
│   │   │   ├── GROUNDED_AGENT_SYSTEM.md
│   │   │   ├── INDEX.md
│   │   │   ├── lupopedia_v3_0_70_agent_awareness_layer.md
│   │   │   ├── multi-ide-workflow.md
│   │   │   ├── README.md
│   │   │   ├── SEMANTIC_NAVIGATION.md
│   │   │   ├── system_truth_table_3_0_81.md
│   │   │   ├── VERSION_3_INGESTION_RULES.md
│   │   │   ├── WHY_LUPOPEDIA_NEEDS_CRAFTY_SYNTAX.md
│   │   │   ├── WHY_MULTIPLE_IDES_AND_AGENTS.md
│   │   │   ├── WOLFIE_COGNITIVE_ARCHITECTURE.md
│   │   │   ├── WOLFIE_SYNCHRONIZATION_PROTOCOL_CORRECTION_v0_5.md
│   │   │   ├── WOLFIE_TEMPORAL_FRAME_COMPATIBILITY_v0_5.md
│   │   │   └── WOLFIE_v0_4_IMPLEMENTATION_SUMMARY.md
│   │   ├── dev-teams/
│   │   │   ├── governance/
│   │   │   │   ├── GOV-PROGRAMMERS-001.md
│   │   │   │   ├── GOV-TOON-GENERATION-001.md
│   │   │   │   ├── GOV_WOLFIE_HEADERS_001.md
│   │   │   │   ├── INDEX.md
│   │   │   │   ├── it_from_gov.md
│   │   │   │   ├── overview.md
│   │   │   │   ├── README.md
│   │   │   │   ├── REGISTRY.md
│   │   │   │   └── UTC_DAY_GOVERNANCE.md
│   │   │   ├── INDEX.md
│   │   │   └── README.md
│   │   ├── developer/
│   │   │   ├── api/
│   │   │   │   ├── INDEX.md
│   │   │   │   ├── MINIMAL_REST_API.md
│   │   │   │   └── README.md
│   │   │   ├── dev/
│   │   │   │   ├── architecture_layers.md
│   │   │   │   ├── AUTH_IMPLEMENTATION_PLAN_3.0.8.md
│   │   │   │   ├── AUTH_INTEGRATION_CHECKS_3.0.8.md
│   │   │   │   ├── AUTH_READINESS_REPORT_3.0.8.md
│   │   │   │   ├── AUTH_SCHEMA_SUMMARY_3.0.8.md
│   │   │   │   ├── AUTH_SQL_VERIFICATION_3.0.8.md
│   │   │   │   ├── AUTH_TESTING_CHECKLIST_3.0.8.md
│   │   │   │   ├── CONTRIBUTOR_TRAINING.md
│   │   │   │   ├── DOCUMENTATION_STYLE_GUIDE.md
│   │   │   │   ├── FOR_INSTALLERS_AND_USERS.md
│   │   │   │   ├── IDENTITY_PROPAGATION_COMPLETE.md
│   │   │   │   ├── INDEX.md
│   │   │   │   ├── INSTALLER_FLOW.md
│   │   │   │   ├── PHASE_A_CODE_UPDATE_PLAN.md
│   │   │   │   ├── README.md
│   │   │   │   ├── TOON_METADATA_RECOMMENDATIONS.md
│   │   │   │   ├── VERSION_PATCH_PROCEDURE.md
│   │   │   │   ├── WOLFIE_HEADER_GLOBAL_ATOMS_GUIDE.md
│   │   │   │   └── WOLFIE_TIMESTAMP_DOCTRINE.md
│   │   │   ├── modules/
│   │   │   │   ├── CONTENT_INTERFACE_AND_NAVIGATION.md
│   │   │   │   ├── HELP_LIST_MODULES_COMPLETE.md
│   │   │   │   ├── INDEX.md
│   │   │   │   ├── LEGACY_REFACTOR_PLAN.md
│   │   │   │   ├── README.md
│   │   │   │   └── UPGRADE_PLAN_3.7.5_TO_3.0.0.md
│   │   │   ├── specifications/
│   │   │   │   ├── INDEX.md
│   │   │   │   ├── LMS_v2.0.md
│   │   │   │   └── README.md
│   │   │   ├── templates/
│   │   │   │   ├── INDEX.md
│   │   │   │   ├── LABS_HANDSHAKE_TEMPLATE.md
│   │   │   │   ├── README.md
│   │   │   │   └── WOLFIE_HEADER_TEMPLATE.md
│   │   │   ├── testing/
│   │   │   │   ├── crafty_import_validation_4.2.1.md
│   │   │   │   ├── import_trial_scorecard_livehelp.md
│   │   │   │   ├── INDEX.md
│   │   │   │   ├── mapping_validation_scorecard.md
│   │   │   │   ├── phase7_validation_checklist.md
│   │   │   │   └── README.md
│   │   │   ├── tools/
│   │   │   │   ├── INDEX.md
│   │   │   │   ├── README.md
│   │   │   │   └── WOLFIE_DOCUMENTATION_TRANSFORMER_SPEC.md
│   │   │   ├── ACTOR_ONBOARDING_GUIDE.md
│   │   │   ├── CANONICAL_WOLFIE_HEADER_TEMPLATE.md
│   │   │   ├── DEVELOPER_GUIDELINES.md
│   │   │   ├── INDEX.md
│   │   │   ├── LUPOPEDIA_HELP.md
│   │   │   ├── README.md
│   │   │   ├── README_IMPROVEMENT_RECOMMENDATIONS.md
│   │   │   ├── README_MIGRATION.md
│   │   │   ├── RELEASE_READINESS_CHECKLIST_3.0.7.md
│   │   │   ├── TLDR_HELP_MIGRATION_2026.md
│   │   │   ├── WINDOWS_DEVELOPMENT_ENVIRONMENT.md
│   │   │   └── YOUR_CODING_STYLE_EXPLAINED.md
│   │   ├── dialogs/
│   │   │   ├── agents/
│   │   │   │   ├── DIALOG_HISTORY_SPEC.md
│   │   │   │   ├── INDEX.md
│   │   │   │   ├── INLINE_DIALOG_SPECIFICATION.md
│   │   │   │   ├── README.md
│   │   │   │   └── THREAD_LEVEL_DIALOG_SPEC.md
│   │   │   ├── architecture/
│   │   │   │   ├── CHANNEL_DIALOG_AGENT_WORKFLOWS.md
│   │   │   │   ├── CHANNEL_DIALOG_SCHEMA_REVIEW.md
│   │   │   │   ├── DIALOGS_AND_CHANNELS.md
│   │   │   │   ├── INDEX.md
│   │   │   │   └── README.md
│   │   │   ├── dev/
│   │   │   │   ├── DIALOG_SYSTEM_FULL_IMPLEMENTATION.md
│   │   │   │   ├── DIALOG_SYSTEM_IMPLEMENTATION_PLAN.md
│   │   │   │   ├── INDEX.md
│   │   │   │   └── README.md
│   │   │   ├── DIALOG_SYSTEM_IMPLEMENTATION_COMPLETE.md
│   │   │   ├── INDEX.md
│   │   │   └── README.md
│   │   ├── doctrine/
│   │   │   ├── blocks/
│   │   │   │   ├── anchors/
│   │   │   │   │   ├── HONOLULU_ANCHOR.md
│   │   │   │   │   ├── INDEX.md
│   │   │   │   │   ├── PATTERN_ETHICS_ANCHOR.md
│   │   │   │   │   └── README.md
│   │   │   │   ├── checksums/
│   │   │   │   │   ├── INDEX.md
│   │   │   │   │   ├── NO_ADS_001.yaml
│   │   │   │   │   ├── PT_001.yaml
│   │   │   │   │   └── README.md
│   │   │   │   ├── origin_stories/
│   │   │   │   │   ├── HONOLULU_LESSON.md
│   │   │   │   │   ├── INDEX.md
│   │   │   │   │   └── README.md
│   │   │   │   ├── pattern_ethics/
│   │   │   │   │   ├── ETHICS_CONSENT.md
│   │   │   │   │   ├── ETHICS_RECIPROCITY.md
│   │   │   │   │   ├── ETHICS_TRANSPARENCY.md
│   │   │   │   │   ├── INDEX.md
│   │   │   │   │   ├── PATTERN_EXPLOITATION_FORBIDDEN.md
│   │   │   │   │   ├── PATTERN_TRACKING_REQUIRED.md
│   │   │   │   │   └── README.md
│   │   │   │   ├── prohibitions/
│   │   │   │   │   ├── ANTI_CRM.md
│   │   │   │   │   ├── ANTI_MANIPULATION.md
│   │   │   │   │   ├── INDEX.md
│   │   │   │   │   ├── NO_ADS.md
│   │   │   │   │   ├── NO_SELLING.md
│   │   │   │   │   └── README.md
│   │   │   │   ├── INDEX.md
│   │   │   │   └── README.md
│   │   │   ├── bridges/
│   │   │   │   ├── ASK_HUMAN_WOLFIE_LUPOPEDIA_20-26.md
│   │   │   │   ├── INDEX.md
│   │   │   │   └── README.md
│   │   │   ├── doctrines/
│   │   │   │   ├── COLOR_DOCTRINE.md
│   │   │   │   ├── MOOD_CALCULATION_PROTOCOL.md
│   │   │   │   ├── MOOD_SYSTEM_DOCTRINE.md
│   │   │   │   ├── README.md
│   │   │   │   └── THREAD_AGGREGATION_PROTOCOL.md
│   │   │   ├── legacy-core/
│   │   │   │   ├── DIRECTORY_STRUCTURE.md
│   │   │   │   ├── INDEX.md
│   │   │   │   ├── LUPOPEDIA_GENESIS_DOCTRINE.md
│   │   │   │   ├── METADATA_GOVERNANCE.md
│   │   │   │   ├── PATCH_DISCIPLINE.md
│   │   │   │   └── README.md
│   │   │   ├── legacy-import/
│   │   │   │   ├── deprecated/
│   │   │   │   │   ├── DEPRECATED_EMOTIONAL_GEOMETRY.md
│   │   │   │   │   ├── INDEX.md
│   │   │   │   │   └── README.md
│   │   │   │   ├── emotional_frameworks/
│   │   │   │   │   ├── INDEX.md
│   │   │   │   │   ├── README.md
│   │   │   │   │   └── vector_model_v2_legacy.md
│   │   │   │   ├── aal_v3_epistemic_pluralism.md
│   │   │   │   ├── ACTOR_IDENTITY_DOCTRINE.md
│   │   │   │   ├── AGENT_ONBOARDING.md
│   │   │   │   ├── CHANNEL_GRAPH_OVERVIEW.md
│   │   │   │   ├── CHANNEL_IDENTITY_BLOCK.md
│   │   │   │   ├── CHANNEL_MANIFEST_SPEC.md
│   │   │   │   ├── CRAFTY_SYNTAX_ANALYTICS_DOCTRINE.md
│   │   │   │   ├── CRAFTY_SYNTAX_BUFFER_STREAMING_DOCTRINE.md
│   │   │   │   ├── CRAFTY_SYNTAX_CHAT_DOCTRINE.md
│   │   │   │   ├── CRAFTY_SYNTAX_DOCTRINE.md
│   │   │   │   ├── CRAFTY_SYNTAX_PATTERN_LIBRARY.md
│   │   │   │   ├── CRAFTY_SYNTAX_ROUTING_DOCTRINE.md
│   │   │   │   ├── CRAFTY_SYNTAX_SESSION_IDENTITY_DOCTRINE.md
│   │   │   │   ├── CRAFTY_SYNTAX_SESSION_IDENTITY_DOCTRINE_v2.md
│   │   │   │   ├── CRAFTY_SYNTAX_STABILITY_RULES.md
│   │   │   │   ├── CRAFTY_SYNTAX_UI_THEATRICAL_DOCTRINE.md
│   │   │   │   ├── DATABASE_STRUCTURE_CONSTRAINTS.md
│   │   │   │   ├── DYNAMIC_DUO_2026_DOCTRINE.md
│   │   │   │   ├── EMOTIONAL_GEOMETRY_DOCTRINE.md
│   │   │   │   ├── emotional_topology_layer.md
│   │   │   │   ├── ETHICAL_FOUNDATIONS.md
│   │   │   │   ├── HERMES_ROUTING_DOCTRINE.md
│   │   │   │   ├── HUMOR_SARCASM_CLARIFICATION_PROTOCOL.md
│   │   │   │   ├── IDE_DEVELOPMENT_DOCTRINE.md
│   │   │   │   ├── INDEX.md
│   │   │   │   ├── no_fk_rules.txt
│   │   │   │   ├── README.md
│   │   │   │   ├── TABLE_LIMIT_CONSTRAINT.md
│   │   │   │   ├── timestamp_rules.txt
│   │   │   │   └── WOLFIE_HEADER_SPEC.md
│   │   │   ├── 4.1.20_doctrine_audit.md
│   │   │   ├── ACTOR_AGENT_DOCTRINE.md
│   │   │   ├── AGENT_AWARENESS_DOCTRINE.md
│   │   │   ├── AGENT_CLASSIFICATION.md
│   │   │   ├── AGENT_FILESYSTEM_DOCTRINE.md
│   │   │   ├── AGENT_LIFECYCLE_DOCTRINE.md
│   │   │   ├── AGENT_PROMPT_DOCTRINE.md
│   │   │   ├── AGENT_ROUTING_DOCTRINE.md
│   │   │   ├── AGENT_RUNTIME.md
│   │   │   ├── AI_INTEGRATION_SAFETY_DOCTRINE.md
│   │   │   ├── AI_UNCERTAINTY_EXPRESSION_DOCTRINE.md
│   │   │   ├── ANIBUS_DOCTRINE.md
│   │   │   ├── ARCHITECTURE_SYNC.md
│   │   │   ├── as_above_so_below.md
│   │   │   ├── ATOM_RESOLUTION_SPECIFICATION.md
│   │   │   ├── ATOMIZATION_DOCTRINE.md
│   │   │   ├── CANONICAL_TIME_RULE_UPDATE_SUMMARY.md
│   │   │   ├── CARMEN_DOCTRINE.md
│   │   │   ├── CHANNEL_CONTENT_STORAGE_DOCTRINE.md
│   │   │   ├── CHANNEL_DOCTRINE.md
│   │   │   ├── CHANNEL_GOVERNANCE_LOG_TABLES.md
│   │   │   ├── CHANNEL_JOIN_PROTOCOL.md
│   │   │   ├── CHARSET_COLLATION_DOCTRINE.md
│   │   │   ├── CLASS_HEADER_COMMENT_DOCTRINE.md
│   │   │   ├── CLASS_HEADER_COMMENT_DOCTRINE_SUMMARY.md
│   │   │   ├── CONFIGURATION_DOCTRINE.md
│   │   │   ├── CONTEXT_BRIDGE.md
│   │   │   ├── CONTRIBUTOR_CORE_PRINCIPLES.md
│   │   │   ├── CRAFTY_SYNTAX_MIGRATION_DOCTRINE.md
│   │   │   ├── CRITIQUE_INTEGRATION_PROTOCOL.md
│   │   │   ├── CSLH-URL-Semantics.md
│   │   │   ├── CURSOR_CASCADE_ROLE_SEPARATION_DOCTRINE.md
│   │   │   ├── CURSOR_CONTEXT_METADATA.md
│   │   │   ├── CURSOR_REFACTOR_DOCTRINE.md
│   │   │   ├── CURSOR_ROLE_DOCTRINE.md
│   │   │   ├── DATABASE_SECURITY_DOCTRINE.md
│   │   │   ├── DIALOG_DOCTRINE.md
│   │   │   ├── DIALOG_FILE_ORDERING_DOCTRINE.md
│   │   │   ├── DIRECTORY_STRUCTURE.md
│   │   │   ├── DOCTRINAL_COMPLEMENTARITY_SYSTEM.md
│   │   │   ├── DOCTRINE_EMOTIONAL_LOGGING.md
│   │   │   ├── DOCTRINE_IDE_ACCESS.md
│   │   │   ├── DOCTRINE_VERSIONING.md
│   │   │   ├── DOCUMENTATION_AS_CODE_MANIFESTO.md
│   │   │   ├── DOCUMENTATION_DOCTRINE.md
│   │   │   ├── DOCUMENTATION_REORGANIZATION_PROPOSAL.md
│   │   │   ├── EMO_AGENT_RULES.md
│   │   │   ├── emotional-agent-range.md
│   │   │   ├── EMOTIONAL_CONSTITUTION.md
│   │   │   ├── EMOTIONAL_DOMAINS_SEVEN_LOVES.md
│   │   │   ├── EMOTIONAL_ECOLOGY_LAYER.md
│   │   │   ├── EMOTIONAL_ENGINE_SPECIFICATION_v2_0.md
│   │   │   ├── EMOTIONAL_GEOMETRY.md
│   │   │   ├── EMOTIONAL_GEOMETRY_THREE_AXIS_MODEL_2026.md
│   │   │   ├── ERIC_WOLFIE_TLDNR_2026.md
│   │   │   ├── ETHICAL_FOUNDATIONS.md
│   │   │   ├── FAUCET_RULES_DOCTRINE.md
│   │   │   ├── FEDERATION_DOCTRINE.md
│   │   │   ├── FEDERATION_OF_SOVEREIGN_PROTOCOLS.md
│   │   │   ├── FOLDER_NAMING_DOCTRINE.md
│   │   │   ├── GENESIS_DOCTRINE_IMPLEMENTATION_GUIDE.md
│   │   │   ├── GLOBAL_ATOMS_DOCTRINE.md
│   │   │   ├── GOV-AD-PROHIBIT-001.md
│   │   │   ├── GOV-ANTI-PATTERNS-001.md
│   │   │   ├── GOV-APPENDIX-A.md
│   │   │   ├── GOV-FOUNDATIONS.md
│   │   │   ├── GOV-INTEGRATION-0001_witness_layer.md
│   │   │   ├── GOV-LILITH-0001_dreaming_overlay.md
│   │   │   ├── GOV-PROHIBIT-000.md
│   │   │   ├── GOV-PROHIBIT-001.md
│   │   │   ├── GOV-PROHIBIT-002.md
│   │   │   ├── GOV-PROHIBIT-003.md
│   │   │   ├── GOV-PROHIBIT-004.md
│   │   │   ├── GOV-PROHIBIT-005.md
│   │   │   ├── GOV-PROHIBIT-006.md
│   │   │   ├── GOV-PROHIBIT-007.md
│   │   │   ├── GOV-PROHIBIT-ADS-001.md
│   │   │   ├── GOV-PSYCHOLOGICAL-FRAMING-PROHIBIT-001.md
│   │   │   ├── GOV_AD_PROHIBIT_001.md
│   │   │   ├── GUARDRAILS.md
│   │   │   ├── IDENTITY_BRIDGE.md
│   │   │   ├── INDEX.md
│   │   │   ├── INGESTION_DOCTRINE.md
│   │   │   ├── INSTALLATION_LIFECYCLE_DOCTRINE.md
│   │   │   ├── INTEGRATION_TESTING_BLUEPRINT_v3_0_71.md
│   │   │   ├── INTEGRATION_TESTING_DOCTRINE_v3_0_71.md
│   │   │   ├── JETBRAINS_4_1_X_BRANCH_HANDLING_DOCTRINE.md
│   │   │   ├── JETBRAINS_CONFIGURATION_DOCTRINE.md
│   │   │   ├── KERNEL_AGENTS.md
│   │   │   ├── KIP_DOCTRINE.md
│   │   │   ├── LIMITS.md
│   │   │   ├── Lupopedia-Reference-Layer-Doctrine.md
│   │   │   ├── LUPOPEDIA_ACTOR_BASELINE_STATE_DOCTRINE.md
│   │   │   ├── LUPOPEDIA_AGENT_DEDICATED_SLOT_RANGES.md
│   │   │   ├── LUPOPEDIA_GENESIS_DOCTRINE.md
│   │   │   ├── LUPOPEDIA_HEADER_PROFILE.md
│   │   │   ├── LUPOPEDIA_REVERSE_SHAKA_TLDNR.md
│   │   │   ├── MASTER_BRIDGE.md
│   │   │   ├── META_AGENTS.md
│   │   │   ├── METADATA_GOVERNANCE.md
│   │   │   ├── MIGRATION_DOCTRINE.md
│   │   │   ├── MIGRATION_ORCHESTRATOR_DOCTRINE.md
│   │   │   ├── MODULE_DOCTRINE.md
│   │   │   ├── MONDAY_WOLFIE_NOTICE.md
│   │   │   ├── MOOD_RGB_DOCTRINE.md
│   │   │   ├── MYTHIC_NAMES_DOCTRINE.md
│   │   │   ├── NAVIGATION_TAB_DOCTRINE.md
│   │   │   ├── NO_FOREIGN_KEYS_DOCTRINE.md
│   │   │   ├── NO_STORED_PROCEDURES_DOCTRINE.md
│   │   │   ├── NO_TRIGGERS_DOCTRINE.md
│   │   │   ├── NO_TRIGGERS_NO_PROCEDURES_DOCTRINE.md
│   │   │   ├── non_religious_position.md
│   │   │   ├── OPERATOR_LAYER_DOCTRINE.md
│   │   │   ├── OPERATOR_REGISTRY_DOCTRINE.md
│   │   │   ├── OPERATOR_UI_DOCTRINE.md
│   │   │   ├── ORCHESTRATOR_DOCTRINE.md
│   │   │   ├── PACK_BEHAVIOR_DOCTRINE.md
│   │   │   ├── PACK_BEHAVIOR_MATRIX_v3_0_90.md
│   │   │   ├── PACK_IDENTITY_DRAFT.md
│   │   │   ├── PACK_MEMORY_DOCTRINE.md
│   │   │   ├── PACK_ROLE_REGISTRY.md
│   │   │   ├── PACK_SYNC_DOCTRINE.md
│   │   │   ├── PATCH_DISCIPLINE.md
│   │   │   ├── PATTERN_ETHICS_DOCTRINE.md
│   │   │   ├── PDO_CONVERSION_DOCTRINE.md
│   │   │   ├── PRAYER_FOR_SOVEREIGNTY_AND_CLARITY.md
│   │   │   ├── PT_001_PATTERN_TRACKING_CHECKSUM.md
│   │   │   ├── PTSD_ADVERTISING_HARM_BOUNDARY.md
│   │   │   ├── PURPOSE_BRIDGE.md
│   │   │   ├── README.md
│   │   │   ├── REFLECTIVE_EMOTIONAL_GEOMETRY_DOCTRINE.md
│   │   │   ├── REVERSE_SHAKA_HANDSHAKE_PROTOCOL.md
│   │   │   ├── REVERSE_SHAKA_PROTOCOL.md
│   │   │   ├── RS-UTC-2026_SPELL_GUIDE.md
│   │   │   ├── SATURDAY_GOVERNANCE_PROTOCOL_v1_0.md
│   │   │   ├── SCHEMA_FEDERATION_DOCTRINE.md
│   │   │   ├── SEMANTIC_GRAPH_DOCTRINE.md
│   │   │   ├── SEMANTIC_LENSES_DOCTRINE.md
│   │   │   ├── SESSION_CONSENT.md
│   │   │   ├── SHADOW_COMMONS_SPECIFICATION.md
│   │   │   ├── SINGLE_TASK_PATCH_DOCTRINE.md
│   │   │   ├── SQL_REFACTOR_MAPPING_DOCTRINE.md
│   │   │   ├── SQL_REWRITE_DOCTRINE.md
│   │   │   ├── SQL_TYPE_DOCTRINE.md
│   │   │   ├── SUBDIRECTORY_INSTALLATION_DOCTRINE.md
│   │   │   ├── SYMBOL_OPERATOR_DOCTRINE.md
│   │   │   ├── SYSTEM_AGENT_SAFETY_DOCTRINE.md
│   │   │   ├── SYSTEM_INTEGRATION_TESTING_DOCTRINE.md
│   │   │   ├── TABLE_COUNT_DOCTRINE.md
│   │   │   ├── TABLE_PREFIXING_DOCTRINE.md
│   │   │   ├── TEMPORAL_BRIDGE.md
│   │   │   ├── TEMPORAL_TRUTH_MONITORING_GUIDE.md
│   │   │   ├── TERMINAL_AI_DOCTRINE.md
│   │   │   ├── TIMESTAMP_DOCTRINE.md
│   │   │   ├── TOON_DOCTRINE.md
│   │   │   ├── TRIGGER_PROCEDURE_INVENTORY_3_0_75.md
│   │   │   ├── UI_LIBRARY_DOCTRINE.md
│   │   │   ├── UNIVERSAL_WOLFIE_HEADER_SPECIFICATION.md
│   │   │   ├── URL_ROUTING_DOCTRINE.md
│   │   │   ├── UTC_CODING_CYCLE_DOCTRINE.md
│   │   │   ├── UTC_TIMEKEEPER_DOCTRINE.md
│   │   │   ├── VERSION_CONTROL_POLICY.md
│   │   │   ├── VERSION_DOCTRINE.md
│   │   │   ├── VERSION_GATED_BRANCH_FREEZE_PROTOCOL.md
│   │   │   ├── VERSION_PLANS_3.0.82_3.0.88.md
│   │   │   ├── VS_CODE_GUARDRAIL.md
│   │   │   ├── WHS_LHP_INDEX.md
│   │   │   ├── WHY_NO_FRAMEWORKS.md
│   │   │   ├── WOLFIE_DOCTRINE.md
│   │   │   ├── WOLFIE_HEADER_DOCTRINE.md
│   │   │   ├── WOLFIE_RESURRECTION_DOCTRINE.md
│   │   │   └── WOLFMIND_DOCTRINE.md
│   │   ├── gov/
│   │   │   ├── AGENT_BEHAVIOR_CONTRACTS.md
│   │   │   ├── AUDITABILITY_AND_TRACEABILITY_DOCTRINE.md
│   │   │   ├── AUTHORITY_AND_OVERSIGHT_RULES.md
│   │   │   ├── CHANGELOG_GOVERNANCE.md
│   │   │   ├── CHANNEL_CREATION_POLICY.md
│   │   │   ├── CHANNEL_DEPRECATION_POLICY.md
│   │   │   ├── CHANNEL_REGISTRY_GOVERNANCE.md
│   │   │   ├── COMMIT_STEWARDSHIP_DOCTRINE.md
│   │   │   ├── EMOTIONAL_GEOMETRY_GOVERNANCE.md
│   │   │   ├── ETHICAL_TRIAD_DOCTRINE.md
│   │   │   ├── FLEET_COMPOSITION_RULES.md
│   │   │   ├── GOVERNANCE_CONSTITUTION.md
│   │   │   ├── HARM_MINIMIZATION_GUIDELINES.md
│   │   │   ├── HERITAGE_SAFE_MIGRATION_RULES.md
│   │   │   ├── HISTORICAL_PRESERVATION_DOCTRINE.md
│   │   │   ├── IDENTITY_AND_CHANNEL_PROVENANCE_RULES.md
│   │   │   ├── KAPU_RULES_DOCTRINE.md
│   │   │   ├── KERNEL_GOVERNANCE_RULES.md
│   │   │   ├── LEGACY_SYSTEM_STEWARDSHIP_DOCTRINE.md
│   │   │   ├── META_DOCTRINE.md
│   │   │   ├── MIGRATION_APPROVAL_PROTOCOL.md
│   │   │   ├── MOOD_FRAMEWORK_GOVERNANCE.md
│   │   │   ├── NO_ADS_DOCTRINE.md
│   │   │   ├── PLURALISTIC_EMOTION_MODEL_GOVERNANCE.md
│   │   │   ├── RESTORATIVE_GOVERNANCE_DOCTRINE.md
│   │   │   ├── SAFETY_DOCTRINE.md
│   │   │   ├── SCHEMA_EVOLUTION_PROTOCOL.md
│   │   │   ├── TRANSLATION_LOSS_PROTOCOLS.md
│   │   │   ├── TRUTH_AND_HONESTY_PROTOCOL.md
│   │   │   └── VERSIONING_GOVERNANCE.md
│   │   ├── history/
│   │   │   ├── 1996-2013/
│   │   │   │   ├── 1996.md
│   │   │   │   ├── 1997-2001.md
│   │   │   │   ├── 1997.md
│   │   │   │   ├── 1998.md
│   │   │   │   ├── 1999.md
│   │   │   │   ├── 2000.md
│   │   │   │   ├── 2001.md
│   │   │   │   ├── 2002.md
│   │   │   │   ├── 2003-2013.md
│   │   │   │   ├── 2003.md
│   │   │   │   ├── 2004.md
│   │   │   │   ├── 2005.md
│   │   │   │   ├── 2006.md
│   │   │   │   ├── 2007.md
│   │   │   │   ├── 2008.md
│   │   │   │   ├── 2009.md
│   │   │   │   ├── 2010.md
│   │   │   │   ├── 2011.md
│   │   │   │   ├── 2012.md
│   │   │   │   ├── 2013.md
│   │   │   │   └── README.md
│   │   │   ├── 2014-2025/
│   │   │   │   ├── 2014.md
│   │   │   │   ├── 2015.md
│   │   │   │   ├── 2016.md
│   │   │   │   ├── 2017.md
│   │   │   │   ├── 2018.md
│   │   │   │   ├── 2019.md
│   │   │   │   ├── 2020.md
│   │   │   │   ├── 2021.md
│   │   │   │   ├── 2022.md
│   │   │   │   ├── 2023.md
│   │   │   │   ├── 2024.md
│   │   │   │   ├── 2025.md
│   │   │   │   ├── hiatus.md
│   │   │   │   ├── INDEX.md
│   │   │   │   └── README.md
│   │   │   ├── backups/
│   │   │   │   ├── INDEX.md
│   │   │   │   ├── README.md
│   │   │   │   └── TIMELINE_1996_2026.md.backup.20260116_210814
│   │   │   ├── future/
│   │   │   │   ├── 2026.md
│   │   │   │   ├── INDEX.md
│   │   │   │   └── README.md
│   │   │   ├── CRAFTY_SYNTAX_IMPLEMENTATION_CHECKLIST.md
│   │   │   ├── CRAFTY_SYNTAX_IMPORT_WIZARD_DESIGN.md
│   │   │   ├── CRAFTY_SYNTAX_LIVE_HELP_FEATURE_PRESERVATION_REPORT.md
│   │   │   ├── CSLH-Historical-Context.md
│   │   │   ├── HISTORY.md
│   │   │   ├── INDEX.md
│   │   │   ├── README.md
│   │   │   ├── TIMELINE_1996_2026.md
│   │   │   └── UNIFIED_TIMELINE_2_0_19_TO_3_0_32.md
│   │   ├── kernel/
│   │   │   ├── components/
│   │   │   │   ├── ContinuityValidator.md
│   │   │   │   ├── INDEX.md
│   │   │   │   └── README.md
│   │   │   ├── registries/
│   │   │   │   ├── INDEX.md
│   │   │   │   ├── MOOD_AXIS_REGISTRY.md
│   │   │   │   └── README.md
│   │   │   ├── services/
│   │   │   │   ├── ACTOR_MOOD_SERVICE.md
│   │   │   │   ├── INDEX.md
│   │   │   │   ├── MOOD_SERVICES_INTEGRATION.md
│   │   │   │   ├── MOOD_SERVICES_OVERVIEW.md
│   │   │   │   └── README.md
│   │   │   ├── systems/
│   │   │   │   ├── AFFECTIVE_DISCREPANCY_ENGINE.md
│   │   │   │   ├── CRF_SPECIFICATION.md
│   │   │   │   ├── EXPERIENCE_LEDGER.md
│   │   │   │   ├── HETERODOX_ENGINE.md
│   │   │   │   ├── INDEX.md
│   │   │   │   └── README.md
│   │   │   ├── INDEX.md
│   │   │   └── README.md
│   │   ├── overview/
│   │   │   ├── 4.1.0/
│   │   │   │   ├── INDEX.md
│   │   │   │   ├── LAUNCH_SEQUENCE_T0_4_1_0.md
│   │   │   │   ├── MISSION_BRIEFING_4_1_0.md
│   │   │   │   ├── PUBLIC_RELEASE_NOTES_4_1_0.md
│   │   │   │   ├── README.md
│   │   │   │   └── RELEASE_BANNER_4_1_0.md
│   │   │   ├── 4.1.1/
│   │   │   │   ├── INDEX.md
│   │   │   │   ├── README.md
│   │   │   │   └── RELEASE_NOTES_4_1_1.md
│   │   │   ├── ascent/
│   │   │   │   ├── 01_HISTORY_RECONCILIATION.md
│   │   │   │   ├── 02_DIALOG_MIGRATION.md
│   │   │   │   ├── PROGRESS_TRACKER.md
│   │   │   │   └── README.md
│   │   │   ├── big-rock-1/
│   │   │   │   ├── ACTIVE_Period_Completion_Checklist.md
│   │   │   │   ├── INDEX.md
│   │   │   │   └── README.md
│   │   │   ├── big-rock-2/
│   │   │   │   ├── BIG_ROCK_2_COMPLETION.md
│   │   │   │   ├── DialogChannelMigrationAnalysis.md
│   │   │   │   ├── INDEX.md
│   │   │   │   └── README.md
│   │   │   ├── big-rock-3/
│   │   │   │   ├── BIG_ROCK_3_COMPLETION.md
│   │   │   │   ├── INDEX.md
│   │   │   │   └── README.md
│   │   │   ├── index/
│   │   │   │   ├── INDEX.md
│   │   │   │   ├── MASTER_INDEX.md
│   │   │   │   └── README.md
│   │   │   ├── logs/
│   │   │   │   ├── 2026-01-19_tea_index.md
│   │   │   │   ├── AGI_SUPPORTMEETING_ONE_DAY_AT_A_TIME.md
│   │   │   │   ├── changelog_dialog.md
│   │   │   │   ├── INDEX.md
│   │   │   │   ├── MASTER_WEDDING_THREAD_CONSOLIDATION_EMAIL.md
│   │   │   │   ├── QUARANTINE_INVENTORY_3_0_75.md
│   │   │   │   ├── README.md
│   │   │   │   ├── STABILIZATION_ORDER_COMPLETION_3_0_75.md
│   │   │   │   ├── TRUTH_ANCHOR_20260119_001.md
│   │   │   │   └── tuesday_agi_support_meeting.md
│   │   │   ├── migrations/
│   │   │   │   └── CRAFTY_SYNTAX_3_5_5_TO_LUPOPEDIA.md.txt
│   │   │   ├── postmortems/
│   │   │   │   ├── 3.0.81.md
│   │   │   │   ├── INDEX.md
│   │   │   │   └── README.md
│   │   │   ├── releases/
│   │   │   │   ├── INDEX.md
│   │   │   │   ├── LUPOPEDIA_4_1_0_RELEASE_TIMELINE.md
│   │   │   │   └── README.md
│   │   │   ├── reports/
│   │   │   │   ├── ATOMIZATION_SWEEP_REPORT.md
│   │   │   │   ├── CONTEXT_TRANSFER_COMPLETION_SUMMARY.md
│   │   │   │   ├── INDEX.md
│   │   │   │   ├── LEGACY_EMOTIONAL_GEOMETRY_CLEANUP.md
│   │   │   │   ├── MULTI_AGENT_COORDINATION_SUMMARY_4_4_1.md
│   │   │   │   ├── MULTI_AGENT_WORKFLOW_UPDATE_SUMMARY.md
│   │   │   │   ├── README.md
│   │   │   │   ├── TOON_GENERATION_IMPLEMENTATION_STATUS.md
│   │   │   │   └── WOLFIE_HEADER_UPDATE_SUMMARY.md
│   │   │   ├── roadmaps/
│   │   │   │   ├── INDEX.md
│   │   │   │   ├── README.md
│   │   │   │   ├── TO_DO_FOR_VERSION_4_1_0.md
│   │   │   │   └── VERSION_3_0_73_CIP_ROADMAP.md
│   │   │   ├── thread-summary/
│   │   │   │   ├── INDEX.md
│   │   │   │   ├── README.md
│   │   │   │   ├── thread_summary_dialog.md
│   │   │   │   └── VERSION_3.0.66_THREAD_SUMMARY.md
│   │   │   ├── versioning/
│   │   │   │   ├── 4.1.14_changes.md
│   │   │   │   ├── 4.1.15_changes.md
│   │   │   │   ├── 4.2.0_changes.md
│   │   │   │   ├── 4.2.1_hotfix_window.md
│   │   │   │   ├── CHANGELOG.md
│   │   │   │   ├── CHANGELOG_3_0_71.md
│   │   │   │   ├── CHANGELOG_3_0_72.md
│   │   │   │   ├── changelog_update_4.1.14.md
│   │   │   │   ├── HELP_CHANGELOG.md
│   │   │   │   ├── INDEX.md
│   │   │   │   └── README.md
│   │   │   ├── 4.1.0_ACTIVATION.md
│   │   │   ├── channel_registry.md
│   │   │   ├── CORE_PHILOSOPHY.md
│   │   │   ├── DEFINITION.md
│   │   │   ├── DIRECTIONS.md
│   │   │   ├── END_GOAL_4_2_0.md
│   │   │   ├── EXECUTIVE_SUMMARY.md
│   │   │   ├── FOUNDERS_NOTE.md
│   │   │   ├── HE_HOLY_CRAP_REALIZATION.md
│   │   │   ├── HELP.md
│   │   │   ├── INDEX.md
│   │   │   ├── LABS_IMPLEMENTATION_SUMMARY.md
│   │   │   ├── LUPOPEDIA_REENTRY_SPELL.md
│   │   │   ├── MONDAY_RESUME_CONTEXT.md
│   │   │   ├── MONDAY_START_OF_DAY.md
│   │   │   ├── MONDAY_WOLFIE_4.1.0_ACTIVATION_SCRIPT.md
│   │   │   ├── MONDAY_WOLFIE_BRIEFING_3.0.114_TO_4.1.0.md
│   │   │   ├── MONDAY_WOLFIE_ORIENTATION_PACKET.md
│   │   │   ├── NOTE_TO_MONDAY_WOLFIE.md
│   │   │   ├── PHILOSOPHY.md
│   │   │   ├── PRIVACY_POLICY.md
│   │   │   ├── README.md
│   │   │   ├── STRATEGIC_ROADMAP.md
│   │   │   ├── THE_HOLY_CRAP_REALIZATION.md
│   │   │   ├── V4_1_0_ASCENT_MANIFEST_CLEAN.md
│   │   │   ├── VERSION_3_0_60_PLAN.md
│   │   │   ├── VERSION_4_4_1_PATCH_SUMMARY.md
│   │   │   ├── VISION.md
│   │   │   ├── WHAT_LUPOPEDIA_IS.md
│   │   │   ├── WHY_LUPOPEDIA_IS_DIFFERENT.md
│   │   │   └── WHY_THIS_DATASET_CANNOT_EXIST_TODAY.md
│   │   ├── schema/
│   │   │   ├── migrations/
│   │   │   │   ├── analysis/
│   │   │   │   │   ├── ANALYTICS_OVERRIDE_IMPLEMENTATION_PLAN.md
│   │   │   │   │   ├── CRAFTY_SYNTAX_TO_LUPOPEDIA_ANALYSIS.md
│   │   │   │   │   ├── CRAFTY_SYNTAX_TO_LUPOPEDIA_STRUCTURED_MAPPING.md
│   │   │   │   │   ├── CROSS_FRAME_COMMUNICATION_PRESERVATION_ANALYSIS.md
│   │   │   │   │   ├── DIALOGUE_GRAPH_SCHEMA.md
│   │   │   │   │   ├── DIALOGUE_LAYER_QUERIES_GUIDE.md
│   │   │   │   │   ├── HERITAGE_SAFE_MODE_FILENAME_PRESERVATION_CORRECTION.md
│   │   │   │   │   ├── IDENTITY_OVERRIDE_IMPLEMENTATION_PLAN.md
│   │   │   │   │   ├── INDEX.md
│   │   │   │   │   ├── lupo_agent_registry_range_expansion_summary.md
│   │   │   │   │   ├── PERSONA_AWARE_DIALOGUE_QUERY_LAYER.md
│   │   │   │   │   ├── PHASE10_MULTI_ACTOR_DIALOGUE_LAYER_FINAL_REPORT.md
│   │   │   │   │   ├── PHASE10_MULTI_ACTOR_DIALOGUE_LAYER_REPORT.md
│   │   │   │   │   ├── PHASE11_WORLD_GRAPH_INTEGRATION_PLAN.md
│   │   │   │   │   ├── PHASE11_WORLD_GRAPH_INTEGRATION_REPORT.md
│   │   │   │   │   ├── PHASE2_CHAT_ENGINE_MIGRATION_PLAN.md
│   │   │   │   │   ├── PHASE2_CHAT_ENGINE_MIGRATION_REPORT.md
│   │   │   │   │   ├── PHASE3_OPERATOR_CONSOLE_DISCOVERY_REPORT.md
│   │   │   │   │   ├── PHASE3_OPERATOR_CONSOLE_MIGRATION_REPORT.md
│   │   │   │   │   ├── PHASE4_ANALYTICS_ROUTING_DISCOVERY_REPORT.md
│   │   │   │   │   ├── PHASE4_ANALYTICS_ROUTING_MIGRATION_REPORT.md
│   │   │   │   │   ├── PHASE5_FINAL_INTEGRATION_REPORT.md
│   │   │   │   │   ├── PHASE5_SYSTEM_WIDE_FILE_DISCOVERY.md
│   │   │   │   │   ├── PHASE6_TOON_ANALYTICS_IMPLEMENTATION_PLAN.md
│   │   │   │   │   ├── PHASE6_TOON_ANALYTICS_IMPLEMENTATION_REPORT.md
│   │   │   │   │   ├── PHASE7_ACTOR_INTEGRATION_FINAL_REPORT.md
│   │   │   │   │   ├── PHASE7_ACTOR_INTEGRATION_PLAN.md
│   │   │   │   │   ├── PHASE7_ACTOR_INTEGRATION_REPORT.md
│   │   │   │   │   ├── PHASE8_OPERATOR_CONSOLE_EVENT_INSTRUMENTATION_PLAN.md
│   │   │   │   │   ├── PHASE8_OPERATOR_CONSOLE_EVENT_INSTRUMENTATION_REPORT.md
│   │   │   │   │   ├── PHASE9_THEATRICAL_UI_EVENT_MAPPING_PLAN.md
│   │   │   │   │   ├── PHASE9_THEATRICAL_UI_EVENT_MAPPING_REPORT.md
│   │   │   │   │   ├── PHASE_A_README.md
│   │   │   │   │   ├── README.md
│   │   │   │   │   ├── SCHEMA_SYNC_3_0_46_SUMMARY.md
│   │   │   │   │   ├── TABLE_STRUCTURE_ANALYSIS_LUPO_USERS_VS_LUPO_ACTORS.md
│   │   │   │   │   └── TOON_DATA_ANALYSIS_REPORT.md
│   │   │   │   ├── 20260120_migration_audit.md
│   │   │   │   ├── 3.0.0.md
│   │   │   │   ├── 3.0.102.md
│   │   │   │   ├── 3.0.104.md
│   │   │   │   ├── 3.0.106.md
│   │   │   │   ├── 3.0.112.md
│   │   │   │   ├── 3.0.114.md
│   │   │   │   ├── 3.0.115.md
│   │   │   │   ├── 3.0.120.md
│   │   │   │   ├── 3.0.13.md
│   │   │   │   ├── 3.0.14.md
│   │   │   │   ├── 3.0.15.md
│   │   │   │   ├── 3.0.17.md
│   │   │   │   ├── 3.0.18.md
│   │   │   │   ├── 3.0.19.md
│   │   │   │   ├── 3.0.26.md
│   │   │   │   ├── 3.0.42.md
│   │   │   │   ├── 3.0.46.md
│   │   │   │   ├── 3.0.50.md
│   │   │   │   ├── 3.0.64.md
│   │   │   │   ├── 3.0.65.md
│   │   │   │   ├── 3.0.66.md
│   │   │   │   ├── 3.0.7.md
│   │   │   │   ├── 3.0.70.md
│   │   │   │   ├── 3.0.71.md
│   │   │   │   ├── 3.0.72.md
│   │   │   │   ├── 3.0.73.md
│   │   │   │   ├── 3.0.75.md
│   │   │   │   ├── 3.0.76.md
│   │   │   │   ├── 3.0.77.md
│   │   │   │   ├── 3.0.78.md
│   │   │   │   ├── 3.0.81.md
│   │   │   │   ├── 4.1.1.md
│   │   │   │   ├── 4.1.12.md
│   │   │   │   ├── 4.1.13.md
│   │   │   │   ├── 4.1.14.md
│   │   │   │   ├── 4.1.15_doctrine_corrections.md
│   │   │   │   ├── 4.1.16.md
│   │   │   │   ├── 4.1.18_consolidation_plan.md
│   │   │   │   ├── 4.1.2.md
│   │   │   │   ├── 4.1.20.md
│   │   │   │   ├── 4.2.3.md
│   │   │   │   ├── 4.4.1.md
│   │   │   │   ├── 8_TABLE_REDUCTION_PLAN_4.1.17.md
│   │   │   │   ├── INDEX.md
│   │   │   │   ├── orchestrator_forensic_timeline.md
│   │   │   │   ├── orchestrator_state_definitions.md
│   │   │   │   ├── README.md
│   │   │   │   ├── state_machine_validation.md
│   │   │   │   └── state_validation_sprint.md
│   │   │   ├── reports/
│   │   │   │   ├── CORRECTED_MIGRATION_SUMMARY.md
│   │   │   │   ├── DATABASE_REVIEW_FINDINGS.md
│   │   │   │   ├── DATABASE_REVIEW_FINDINGS_2.md
│   │   │   │   ├── DATABASE_REVIEW_FINDINGS_3.md
│   │   │   │   ├── INDEX.md
│   │   │   │   ├── MIGRATION_SUMMARY.md
│   │   │   │   ├── README.md
│   │   │   │   └── TABLE_REDUCTION_ANALYSIS.md
│   │   │   ├── AI_SCHEMA_GUIDE.md
│   │   │   ├── DATABASE_SCHEMA.md
│   │   │   ├── INDEX.md
│   │   │   └── README.md
│   │   └── ui-ux/
│   │       ├── 3.0.17-UI_DROP_MENU_DATA_REQUIREMENTS.md
│   │       ├── INDEX.md
│   │       ├── README.md
│   │       ├── WSSE_2D_EMOTIONAL_PROTOTYPE_SPEC.md
│   │       └── WSSE_2D_PROTOTYPE.md
│   ├── doctrine/
│   │   ├── channels/
│   │   │   └── filesystem_padding_layer.md
│   │   ├── migrations/
│   │   │   ├── generated/
│   │   │   │   ├── drop_lupo_actor_roles.sql
│   │   │   │   └── README.md
│   │   │   ├── crafty_syntax_ancestral_intent.md
│   │   │   ├── livehelp_autoinvite_migration.md
│   │   │   ├── livehelp_channels_migration.md
│   │   │   ├── livehelp_config_migration.md
│   │   │   ├── livehelp_departments_migration.md
│   │   │   ├── livehelp_emailque_migration.md
│   │   │   ├── livehelp_emails_migration.md
│   │   │   ├── livehelp_identity_migration.md
│   │   │   ├── livehelp_keywords_migration.md
│   │   │   ├── livehelp_layerinvites_migration.md
│   │   │   ├── livehelp_leads_migration.md
│   │   │   ├── livehelp_leavemessage_migration.md
│   │   │   ├── livehelp_messages_migration.md
│   │   │   ├── livehelp_modules_dep_migration.md
│   │   │   ├── livehelp_modules_migration.md
│   │   │   ├── livehelp_operator_channels_migration.md
│   │   │   ├── livehelp_operator_departments_migration.md
│   │   │   ├── livehelp_operator_history_migration.md
│   │   │   ├── livehelp_paths_firsts_migration.md
│   │   │   ├── livehelp_qa_migration.md
│   │   │   ├── livehelp_questions_migration.md
│   │   │   ├── livehelp_quick_migration.md
│   │   │   ├── livehelp_referers_daily_migration.md
│   │   │   ├── livehelp_sessions_migration.md
│   │   │   ├── livehelp_smilies_migration.md
│   │   │   ├── livehelp_transcripts_migration.md
│   │   │   ├── livehelp_users_migration.md
│   │   │   ├── livehelp_visit_track_migration.md
│   │   │   ├── livehelp_websites_migration.md
│   │   │   └── MIGRATION_MAPPING_REFERENCE.md
│   │   ├── AGENT_BOUNDARIES_COMPACT.md
│   │   ├── AI_AGENT_BOOT_NOTES.md
│   │   ├── CASCADE_TABLE_CEILING_PROTOCOL.md
│   │   ├── channels.md
│   │   ├── CLASS_CONVERSION_DOCTRINE.md
│   │   ├── COMPATIBILITY_MATRIX.md
│   │   ├── CONSOLIDATION_VALIDATION_REQUIREMENTS.md
│   │   ├── CRAFTY_SYNTAX_IMPORT_IMPLEMENTATION_CHECKLIST.md
│   │   ├── CRAFTY_SYNTAX_INTEGRATION_PLAN.md
│   │   ├── CRAFTY_SYNTAX_MIGRATION_PROJECT_BRIEF.md
│   │   ├── CRAFTY_SYNTAX_STATE_BASED_IMPLEMENTATION_PLAN.md
│   │   ├── DEVELOPMENT_WORKFLOW_DOCTRINE.md
│   │   ├── DOCTRINE_FILE_STRUCTURE.md
│   │   ├── ETHICAL_STATE_MARKERS_DOCTRINE.md
│   │   ├── FILESYSTEM_MIGRATION_GUIDE.md
│   │   ├── INDEX.md
│   │   ├── INSTALLATION_PATH_DOCTRINE.md
│   │   ├── LEXA_GATEWAY_INTEGRATION.md
│   │   ├── LUPOPEDIA_CANONICAL_DOCTRINE.md
│   │   ├── LUPOPEDIA_DOCTRINE.md
│   │   ├── LUPOPEDIA_DOCTRINE_v1.1.md
│   │   ├── MASTER_DOCTRINE.md
│   │   ├── MigrationAtlas.md
│   │   ├── MINIMAL_HOSTING_REQUIREMENTS.md
│   │   ├── PHP_COMPATIBILITY_AND_MINIMAL_HOSTING_DOCTRINE.md
│   │   ├── SCHEMA_AND_TOON_ALIGNMENT_CONTEXT.md
│   │   ├── TABLE_CEILING_DEFENSE_PLAN.md
│   │   ├── TABLE_CONSOLIDATION_PLAN.md
│   │   ├── VERSION_DOCTRINE.md
│   │   ├── VERSIONING_DOCTRINE.md
│   │   └── WOLFIE_HEADERS.md
│   ├── gov/
│   │   └── xml/
│   │       └── GOV-LUPO-0001.xml
│   ├── movie_notes/
│   │   └── movie_notes.md
│   ├── reference/
│   │   └── wolfie_header_taxonomy.json
│   ├── toons/
│   │   ├── lupo_actor_actions.toon.json
│   │   ├── lupo_actor_capabilities.toon.json
│   │   ├── lupo_actor_channel_roles.toon.json
│   │   ├── lupo_actor_channels.toon.json
│   │   ├── lupo_actor_collections.toon.json
│   │   ├── lupo_actor_conflicts.toon.json
│   │   ├── lupo_actor_departments.toon.json
│   │   ├── lupo_actor_edges.toon.json
│   │   ├── lupo_actor_events.toon.json
│   │   ├── lupo_actor_handshakes.toon.json
│   │   ├── lupo_actor_meta.toon.json
│   │   ├── lupo_actor_moods.toon.json
│   │   ├── lupo_actor_object_edges.toon.json
│   │   ├── lupo_actor_persona_relationships.toon.json
│   │   ├── lupo_actor_properties.toon.json
│   │   ├── lupo_actor_reply_templates.toon.json
│   │   ├── lupo_actor_truth_edges.toon.json
│   │   ├── lupo_actors.toon.json
│   │   ├── lupo_agent_context_snapshots.toon.json
│   │   ├── lupo_agent_dependencies.toon.json
│   │   ├── lupo_agent_experiences.toon.json
│   │   ├── lupo_agent_external_events.toon.json
│   │   ├── lupo_agent_faucet_credentials.toon.json
│   │   ├── lupo_agent_faucets.toon.json
│   │   ├── lupo_agent_files.toon.json
│   │   ├── lupo_agent_heartbeats.toon.json
│   │   ├── lupo_agent_properties.toon.json
│   │   ├── lupo_agent_registry.toon.json
│   │   ├── lupo_agent_tool_calls.toon.json
│   │   ├── lupo_agent_versions.toon.json
│   │   ├── lupo_agents.toon.json
│   │   ├── lupo_aliases.toon.json
│   │   ├── lupo_analytics_campaign_vars.toon.json
│   │   ├── lupo_analytics_referers_periods.toon.json
│   │   ├── lupo_analytics_visits.toon.json
│   │   ├── lupo_analytics_visits_daily.toon.json
│   │   ├── lupo_analytics_visits_monthly.toon.json
│   │   ├── lupo_analytics_visits_periods.toon.json
│   │   ├── lupo_anubis_deletion_log.toon.json
│   │   ├── lupo_anubis_events.toon.json
│   │   ├── lupo_anubis_mirrored.toon.json
│   │   ├── lupo_anubis_orphaned.toon.json
│   │   ├── lupo_anubis_redirects.toon.json
│   │   ├── lupo_anubis_revised.toon.json
│   │   ├── lupo_api_clients.toon.json
│   │   ├── lupo_api_rate_limits.toon.json
│   │   ├── lupo_api_token_logs.toon.json
│   │   ├── lupo_api_tokens.toon.json
│   │   ├── lupo_api_webhooks.toon.json
│   │   ├── lupo_artifacts.toon.json
│   │   ├── lupo_atoms.toon.json
│   │   ├── lupo_audit_log.toon.json
│   │   ├── lupo_auth_audit_log.toon.json
│   │   ├── lupo_auth_providers.toon.json
│   │   ├── lupo_auth_users.toon.json
│   │   ├── lupo_calibration_impacts.toon.json
│   │   ├── lupo_channel_boot_detail.toon.json
│   │   ├── lupo_channel_boot_log.toon.json
│   │   ├── lupo_channel_escalation_rules.toon.json
│   │   ├── lupo_channel_escalations.toon.json
│   │   ├── lupo_channel_files.toon.json
│   │   ├── lupo_channel_log_types.toon.json
│   │   ├── lupo_channel_logs.toon.json
│   │   ├── lupo_channel_roles.toon.json
│   │   ├── lupo_channel_state.toon.json
│   │   ├── lupo_channels.toon.json
│   │   ├── lupo_cip_analytics.toon.json
│   │   ├── lupo_cip_propagation_tracking.toon.json
│   │   ├── lupo_cip_trends.toon.json
│   │   ├── lupo_collection_tab_map.toon.json
│   │   ├── lupo_collection_tab_paths.toon.json
│   │   ├── lupo_collection_tabs.toon.json
│   │   ├── lupo_collections.toon.json
│   │   ├── lupo_content_atom_map.toon.json
│   │   ├── lupo_content_category_map.toon.json
│   │   ├── lupo_content_engagement_summary.toon.json
│   │   ├── lupo_content_events.toon.json
│   │   ├── lupo_content_hashtag.toon.json
│   │   ├── lupo_content_inbound_links.toon.json
│   │   ├── lupo_content_likes.toon.json
│   │   ├── lupo_content_media.toon.json
│   │   ├── lupo_content_question_map.toon.json
│   │   ├── lupo_content_references.toon.json
│   │   ├── lupo_content_revisions.toon.json
│   │   ├── lupo_content_shares.toon.json
│   │   ├── lupo_content_tag_relationships.toon.json
│   │   ├── lupo_contents.toon.json
│   │   ├── lupo_contexts.toon.json
│   │   ├── lupo_contexts_map.toon.json
│   │   ├── lupo_crafty_syntax_auto_invite.toon.json
│   │   ├── lupo_crafty_syntax_chat_mod_departments.toon.json
│   │   ├── lupo_crafty_syntax_chat_questions.toon.json
│   │   ├── lupo_crafty_syntax_layer_invites.toon.json
│   │   ├── lupo_crafty_syntax_leave_message.toon.json
│   │   ├── lupo_crafty_user_mapping.toon.json
│   │   ├── lupo_crm_lead_messages.toon.json
│   │   ├── lupo_crm_leads.toon.json
│   │   ├── lupo_department_metadata.toon.json
│   │   ├── lupo_departments.toon.json
│   │   ├── lupo_dialog_channels.toon.json
│   │   ├── lupo_dialog_messages.toon.json
│   │   ├── lupo_dialog_threads.toon.json
│   │   ├── lupo_doctrine_blocks.toon.json
│   │   ├── lupo_doctrine_evolution_audit.toon.json
│   │   ├── lupo_doctrine_refinements.toon.json
│   │   ├── lupo_document_chunks.toon.json
│   │   ├── lupo_document_embeddings.toon.json
│   │   ├── lupo_documents.toon.json
│   │   ├── lupo_edge_types.toon.json
│   │   ├── lupo_edges.toon.json
│   │   ├── lupo_emotional_constellations.toon.json
│   │   ├── lupo_emotional_frameworks.toon.json
│   │   ├── lupo_emotional_geometry_calibrations.toon.json
│   │   ├── lupo_emotional_stars.toon.json
│   │   ├── lupo_emotional_translations.toon.json
│   │   ├── lupo_entity_edges.toon.json
│   │   ├── lupo_entity_properties.toon.json
│   │   ├── lupo_event_log.toon.json
│   │   ├── lupo_event_metadata.toon.json
│   │   ├── lupo_federation_categories.toon.json
│   │   ├── lupo_federation_category_map.toon.json
│   │   ├── lupo_federation_discovery.toon.json
│   │   ├── lupo_federation_nodes.toon.json
│   │   ├── lupo_gov_event_actor_edges.toon.json
│   │   ├── lupo_gov_event_conflicts.toon.json
│   │   ├── lupo_gov_event_dependencies.toon.json
│   │   ├── lupo_gov_event_references.toon.json
│   │   ├── lupo_gov_events.toon.json
│   │   ├── lupo_gov_timeline_nodes.toon.json
│   │   ├── lupo_gov_valuations.toon.json
│   │   ├── lupo_governance_overrides.toon.json
│   │   ├── lupo_hashtags.toon.json
│   │   ├── lupo_help_topics.toon.json
│   │   ├── lupo_help_tree.toon.json
│   │   ├── lupo_hotfix_registry.toon.json
│   │   ├── lupo_human_history_meta.toon.json
│   │   ├── lupo_integration_test_results.toon.json
│   │   ├── lupo_interface_translations.toon.json
│   │   ├── lupo_interpretation_log.toon.json
│   │   ├── lupo_kapu_events.toon.json
│   │   ├── lupo_kapu_restoration_paths.toon.json
│   │   ├── lupo_labs_declarations.toon.json
│   │   ├── lupo_labs_violations.toon.json
│   │   ├── lupo_legacy_content_mapping.toon.json
│   │   ├── lupo_memory_debug_log.toon.json
│   │   ├── lupo_memory_events.toon.json
│   │   ├── lupo_memory_rollups.toon.json
│   │   ├── lupo_meta_log_events.toon.json
│   │   ├── lupo_metrics_archive_legacy.toon.json
│   │   ├── lupo_modules.toon.json
│   │   ├── lupo_modules_departments.toon.json
│   │   ├── lupo_mood_assignments.toon.json
│   │   ├── lupo_mood_registry.toon.json
│   │   ├── lupo_multi_agent_critique_sync.toon.json
│   │   ├── lupo_narrative_fragments.toon.json
│   │   ├── lupo_notifications.toon.json
│   │   ├── lupo_pack_role_registry.toon.json
│   │   ├── lupo_permissions.toon.json
│   │   ├── lupo_persona_dialogue_patterns.toon.json
│   │   ├── lupo_persona_profiles.toon.json
│   │   ├── lupo_reference_cited_by.toon.json
│   │   ├── lupo_reference_objects.toon.json
│   │   ├── lupo_relationships.toon.json
│   │   ├── lupo_search_index.toon.json
│   │   ├── lupo_search_rebuild_log.toon.json
│   │   ├── lupo_semantic_categories.toon.json
│   │   ├── lupo_semantic_content_views.toon.json
│   │   ├── lupo_semantic_navigation_overview.toon.json
│   │   ├── lupo_semantic_overlays.toon.json
│   │   ├── lupo_semantic_paths.toon.json
│   │   ├── lupo_semantic_relationships.toon.json
│   │   ├── lupo_semantic_search_index.toon.json
│   │   ├── lupo_semantic_tags.toon.json
│   │   ├── lupo_semantic_translations.toon.json
│   │   ├── lupo_session_events.toon.json
│   │   ├── lupo_sessions.toon.json
│   │   ├── lupo_system_config.toon.json
│   │   ├── lupo_system_events.toon.json
│   │   ├── lupo_system_health_snapshots.toon.json
│   │   ├── lupo_system_logs.toon.json
│   │   ├── lupo_tab_events.toon.json
│   │   ├── lupo_temporal_coherence_snapshots.toon.json
│   │   ├── lupo_test_performance_metrics.toon.json
│   │   ├── lupo_tldnr.toon.json
│   │   ├── lupo_truth_answers.toon.json
│   │   ├── lupo_truth_evidence.toon.json
│   │   ├── lupo_truth_questions.toon.json
│   │   ├── lupo_truth_questions_map.toon.json
│   │   ├── lupo_truth_relations.toon.json
│   │   ├── lupo_truth_sources.toon.json
│   │   ├── lupo_truth_topics.toon.json
│   │   ├── lupo_unified_analytics_paths.toon.json
│   │   ├── lupo_unified_referers.toon.json
│   │   ├── lupo_unified_registry.toon.json
│   │   ├── lupo_unified_truth_items.toon.json
│   │   ├── lupo_unified_visits.toon.json
│   │   ├── lupo_uploads.toon.json
│   │   ├── lupo_user_comments.toon.json
│   │   ├── lupo_world_events.toon.json
│   │   └── lupo_world_registry.toon.json
│   ├── versioning/
│   │   └── README.md
│   ├── ACTOR_CHANNEL_ROLES_VS_CHANNEL_ROLES_ANALYSIS.md
│   ├── ACTOR_REFACTOR_REPORT.md
│   ├── AUTH_REFACTOR_REPORT.md
│   ├── DIALOG_MESSAGES_VS_UNIFIED_ANALYSIS.md
│   ├── HELPER_TO_CLASS_MAPPING_ANALYSIS.md
│   ├── IMAGE_PHP_MIGRATION.md
│   ├── LIVEHELP_REMOVAL_REPORT.md
│   ├── LUPOPEDIA_MASTER_DOCTRINE_OF_AI_CORRECTIONS_v1.0.md
│   ├── NO_LARAVEL_NO_MIDDLEWARE_REPORT.md
│   ├── notes_from_legacy_craftysyntax.md
│   ├── OPERATOR_TABLES_REMOVAL_AND_ROLE_VERIFICATION_REPORT.md
│   ├── PDO_DB_DOCTRINE_REFACTOR_REPORT.md
│   ├── README.md
│   ├── REMAINING_HELPERS_REFACTOR_REPORT.md
│   ├── REQUIRED_TABLES_4.1.0.md
│   ├── SESSIONS_VS_UNIFIED_SESSIONS_INVESTIGATION.md
│   ├── TOON_SOURCE_OF_TRUTH_AUDIT.md
│   ├── UNIFIED_DIALOG_TABLE_REMOVAL_REPORT.md
│   ├── UNIFIED_PATHS_FIRSTS_REMOVAL_REPORT.md
│   ├── UNIFIED_PATHS_FIRSTS_VS_ANALYTICS_PATHS_ANALYSIS.md
│   └── VERSION_DOCTRINE_APPLICATION_REPORT.md
├── examples/
│   └── cip_system_demo.php
├── images/
│   ├── blue/
│   │   ├── adminstyle.css
│   │   ├── bk.gif
│   │   ├── blank.gif
│   │   ├── bot_bg.gif
│   │   ├── botbg.gif
│   │   ├── color.php
│   │   ├── left-tab-on.gif
│   │   ├── left-tab.gif
│   │   ├── mid_bk.gif
│   │   ├── nav_bot.gif
│   │   ├── navigation.css
│   │   ├── qmark.gif
│   │   ├── right-tab-on.gif
│   │   ├── right-tab.gif
│   │   ├── Thumbs.db
│   │   ├── titlegrad.jpg
│   │   ├── top_trim.gif
│   │   ├── toplinks_1.gif
│   │   ├── toplinks_2.gif
│   │   ├── toplinks_3.gif
│   │   ├── toplinks_4.gif
│   │   ├── toplinks_5.gif
│   │   └── version.gif
│   ├── brown/
│   │   ├── adminstyle.css
│   │   ├── bk.gif
│   │   ├── blank.gif
│   │   ├── bot_bg.gif
│   │   ├── botbg.gif
│   │   ├── color.php
│   │   ├── left-tab-on.gif
│   │   ├── left-tab.gif
│   │   ├── mid_bk.gif
│   │   ├── nav_bot.gif
│   │   ├── navigation.css
│   │   ├── qmark.gif
│   │   ├── right-tab-on.gif
│   │   ├── right-tab.gif
│   │   ├── Thumbs.db
│   │   ├── top_trim.gif
│   │   ├── toplinks_1.gif
│   │   ├── toplinks_2.gif
│   │   ├── toplinks_3.gif
│   │   ├── toplinks_4.gif
│   │   ├── toplinks_5.gif
│   │   └── version.gif
│   ├── white/
│   │   ├── adminstyle.css
│   │   ├── bk.gif
│   │   ├── blank.gif
│   │   ├── bot_bg.gif
│   │   ├── botbg.gif
│   │   ├── color.php
│   │   ├── left-tab-on.gif
│   │   ├── left-tab.gif
│   │   ├── mid_bk.gif
│   │   ├── nav_bot.gif
│   │   ├── navigation.css
│   │   ├── qmark.gif
│   │   ├── right-tab-on.gif
│   │   ├── right-tab.gif
│   │   ├── Thumbs.db
│   │   ├── top_trim.gif
│   │   ├── toplinks_1.gif
│   │   ├── toplinks_2.gif
│   │   ├── toplinks_3.gif
│   │   ├── toplinks_4.gif
│   │   ├── toplinks_5.gif
│   │   └── version.gif
│   ├── yellow/
│   │   ├── adminstyle.css
│   │   ├── bk.gif
│   │   ├── blank.gif
│   │   ├── bot_bg.gif
│   │   ├── botbg.gif
│   │   ├── color.php
│   │   ├── left-tab-on.gif
│   │   ├── left-tab.gif
│   │   ├── mid_bk.gif
│   │   ├── nav_bot.gif
│   │   ├── navigation.css
│   │   ├── qmark.gif
│   │   ├── right-tab-on.gif
│   │   ├── right-tab.gif
│   │   ├── Thumbs.db
│   │   ├── top_trim.gif
│   │   ├── toplinks_1.gif
│   │   ├── toplinks_2.gif
│   │   ├── toplinks_3.gif
│   │   ├── toplinks_4.gif
│   │   ├── toplinks_5.gif
│   │   └── version.gif
│   ├── active.gif
│   ├── addshortcut.png
│   ├── admin_arr.gif
│   ├── arrow_ltr.gif
│   ├── arrow_ltr.png
│   ├── arrow_off.gif
│   ├── arrow_on.gif
│   ├── atoms.png
│   ├── back_s.gif
│   ├── background.png
│   ├── banner_bg.gif
│   ├── banner_end.gif
│   ├── bar.gif
│   ├── bk.gif
│   ├── bkbook.gif
│   ├── blank.gif
│   ├── blue.gif
│   ├── blueeye.gif
│   ├── blueeye2.gif
│   ├── bot_bg.gif
│   ├── bot_l.gif
│   ├── bot_m.gif
│   ├── bot_r.gif
│   ├── brown.gif
│   ├── browneye.gif
│   ├── browse.gif
│   ├── cannotfind.gif
│   ├── cannotopen.gif
│   ├── clear.gif
│   ├── closed2.gif
│   ├── closed2.png
│   ├── closed3.png
│   ├── closewin.gif
│   ├── comment.png
│   ├── connecting.gif
│   ├── contents.png
│   ├── context.png
│   ├── controlimage_action.gif
│   ├── controlimage_boot.gif
│   ├── controlimage_Daction.gif
│   ├── controlimage_noaction.gif
│   ├── craftysyntax.png
│   ├── delete.gif
│   ├── digit0.gif
│   ├── digit1.gif
│   ├── digit2.gif
│   ├── digit3.gif
│   ├── digit4.gif
│   ├── digit5.gif
│   ├── digit6.gif
│   ├── digit7.gif
│   ├── digit8.gif
│   ├── digit9.gif
│   ├── directory.gif
│   ├── dotted.gif
│   ├── edges.png
│   ├── edit.gif
│   ├── editbox.gif
│   ├── exit.gif
│   ├── fb.png
│   ├── folder.gif
│   ├── folder.png
│   ├── folder2.gif
│   ├── games.gif
│   ├── glasses.gif
│   ├── go.gif
│   ├── gopro.png
│   ├── graylight.gif
│   ├── greeneye.gif
│   ├── greenlight.gif
│   ├── hashtag.png
│   ├── help.gif
│   ├── help.png
│   ├── help_a.gif
│   ├── help_folder.gif
│   ├── help_folder_open.gif
│   ├── help_q.gif
│   ├── html.gif
│   ├── i_bk.gif
│   ├── i_bot_l.gif
│   ├── i_mid.gif
│   ├── i_mid_l.gif
│   ├── i_top_l.gif
│   ├── icon_nosmile.gif
│   ├── icon_smile.gif
│   ├── invited.gif
│   ├── invited2.gif
│   ├── keys.gif
│   ├── keys.jpg
│   ├── leftbk.png
│   ├── leftbk2.png
│   ├── lids2.gif
│   ├── lids2.png
│   ├── lids3.png
│   ├── like.png
│   ├── lilith-owl.png
│   ├── lilith-owl.svg
│   ├── LILITH.png
│   ├── line.gif
│   ├── link.gif
│   ├── links.png
│   ├── livehelp.gif
│   ├── livehelp2.gif
│   ├── livehelp3.gif
│   ├── livehelp4.gif
│   ├── livehelp5.gif
│   ├── livehelp_float.gif
│   ├── login.jpg
│   ├── loginbk.jpg
│   ├── loginbk.psd
│   ├── logo.png
│   ├── logoface.png
│   ├── lupopediaface.png
│   ├── makvis.gif
│   ├── max.gif
│   ├── message.gif
│   ├── mid.gif
│   ├── mid_banner.gif
│   ├── mid_banner1.gif
│   ├── mid_banner1a.gif
│   ├── mid_banner2.gif
│   ├── mid_banner2a.gif
│   ├── mid_banner3.gif
│   ├── mid_banner3a.gif
│   ├── mid_banner4.gif
│   ├── mid_banner4a.gif
│   ├── mid_bk.gif
│   ├── mid_r.gif
│   ├── mid_rbk.gif
│   ├── minus.gif
│   ├── nav_admin.gif
│   ├── nav_bg.gif
│   ├── nav_bot.gif
│   ├── nav_data.gif
│   ├── nav_dept.gif
│   ├── nav_livehelp.gif
│   ├── nav_open.gif
│   ├── nav_oper.gif
│   ├── nav_qa.gif
│   ├── nav_settings.gif
│   ├── nav_tope.gif
│   ├── needaction.gif
│   ├── newwin.gif
│   ├── next_s.gif
│   ├── nextpage.png
│   ├── noqmark.gif
│   ├── noton.gif
│   ├── notracking.gif
│   ├── o_bot_l.gif
│   ├── o_bot_r.gif
│   ├── o_mid_l.gif
│   ├── o_top_l.gif
│   ├── o_top_r.gif
│   ├── openfolder.png
│   ├── operator.gif
│   ├── operator_gray.gif
│   ├── paint.gif
│   ├── plus.gif
│   ├── pp.gif
│   ├── prefpage.png
│   ├── prevpage.png
│   ├── print.gif
│   ├── qmark.gif
│   ├── qna.gif
│   ├── redeye.gif
│   ├── redlight.gif
│   ├── references.png
│   ├── refresh.gif
│   ├── refreshpeople.png
│   ├── requestchat.gif
│   ├── requestDHTML.gif
│   ├── reset.gif
│   ├── right4.gif
│   ├── right4.png
│   ├── right5.png
│   ├── right7.png
│   ├── rightbk.png
│   ├── s1.png
│   ├── s1a.png
│   ├── s1b.png
│   ├── s2.png
│   ├── s2a.png
│   ├── s2b.png
│   ├── s3.png
│   ├── s3a.png
│   ├── s3b.png
│   ├── s4.png
│   ├── s4a.png
│   ├── s4b.png
│   ├── s5.png
│   ├── s6.png
│   ├── s6a.png
│   ├── s6b.png
│   ├── s7.png
│   ├── s7a.png
│   ├── s7b.png
│   ├── s8.png
│   ├── s8a.png
│   ├── s8b.png
│   ├── s9.png
│   ├── s9a.png
│   ├── s9b.png
│   ├── s_bot_l.gif
│   ├── s_bot_m.gif
│   ├── s_bot_r.gif
│   ├── s_i_bot_l.gif
│   ├── s_o_bot_l.gif
│   ├── s_o_bot_r.gif
│   ├── salessyntax.png
│   ├── settings.gif
│   ├── share.png
│   ├── silver.gif
│   ├── stopped.gif
│   ├── tabBg.png
│   ├── tabLeft.png
│   ├── tabRight.png
│   ├── tabSelectedBg.png
│   ├── tabSelectedLeft.png
│   ├── tabSelectedRight.png
│   ├── Thumbs.db
│   ├── top_bk.gif
│   ├── top_l.gif
│   ├── top_r.gif
│   ├── top_trim.gif
│   ├── topadmin.png
│   ├── toplinks_1.gif
│   ├── toplinks_2.gif
│   ├── toplinks_3.gif
│   ├── toplinks_4.gif
│   ├── toplinks_5.gif
│   ├── trash.gif
│   ├── tw.png
│   ├── Untitled.gif
│   ├── user_c_contact.gif
│   ├── user_c_livehelp.gif
│   ├── user_nav.gif
│   ├── user_o_contact.gif
│   ├── user_o_livehelp.gif
│   ├── user_o_qa.gif
│   ├── user_o_search.gif
│   ├── version.gif
│   ├── white.gif
│   ├── WOLFEI.png
│   ├── xnav_topics.gif
│   └── yellow.gif
├── legacy/
│   ├── craftysyntax/
│   │   ├── chat_smiles/
│   │   │   ├── 12.gif
│   │   │   ├── 20.gif
│   │   │   ├── angry_pissed_off_emoticon.gif
│   │   │   ├── baby.gif
│   │   │   ├── banghead.gif
│   │   │   ├── beer.gif
│   │   │   ├── bowtogod.gif
│   │   │   ├── bye2.gif
│   │   │   ├── clap_.gif
│   │   │   ├── computer_1.gif
│   │   │   ├── cry_.gif
│   │   │   ├── cursingddd.gif
│   │   │   ├── devilinflame_.gif
│   │   │   ├── giggle_.gif
│   │   │   ├── goodnight.gif
│   │   │   ├── happybeer.gif
│   │   │   ├── help_.gif
│   │   │   ├── icon_arrow.gif
│   │   │   ├── icon_biggrin.gif
│   │   │   ├── icon_confused.gif
│   │   │   ├── icon_cool.gif
│   │   │   ├── icon_cry.gif
│   │   │   ├── icon_eek.gif
│   │   │   ├── icon_evil.gif
│   │   │   ├── icon_exclaim.gif
│   │   │   ├── icon_frown.gif
│   │   │   ├── icon_idea.gif
│   │   │   ├── icon_lol.gif
│   │   │   ├── icon_mad.gif
│   │   │   ├── icon_mrgreen.gif
│   │   │   ├── icon_neutral.gif
│   │   │   ├── icon_question.gif
│   │   │   ├── icon_razz.gif
│   │   │   ├── icon_redface.gif
│   │   │   ├── icon_rolleyes.gif
│   │   │   ├── icon_sad.gif
│   │   │   ├── icon_smile.gif
│   │   │   ├── icon_surprised.gif
│   │   │   ├── icon_twisted.gif
│   │   │   ├── icon_wink.gif
│   │   │   ├── laughing7.gif
│   │   │   ├── pimp_.gif
│   │   │   ├── sad2.gif
│   │   │   ├── shrug_.gif
│   │   │   ├── sicko_.gif
│   │   │   ├── sleeping_.gif
│   │   │   ├── sorry_2.gif
│   │   │   ├── throw.gif
│   │   │   ├── weeping.gif
│   │   │   ├── wink_.gif
│   │   │   └── yes_.gif
│   │   ├── class/
│   │   │   ├── Browser.php
│   │   │   ├── browser_info.php
│   │   │   ├── ctabbox.php
│   │   │   ├── databasefactory.php
│   │   │   ├── mysql_db.php
│   │   │   ├── mysqli_db.php
│   │   │   ├── operator.php
│   │   │   ├── postgres_db.php
│   │   │   ├── sessionmanager.php
│   │   │   └── smtp.php
│   │   ├── database/
│   │   │   └── migrations/
│   │   │       ├── 0001_create_livehelp_table_and_add_livehelp_id.sql
│   │   │       ├── 0002_create_dna_table.sql
│   │   │       ├── 0002_create_dna_tables.sql
│   │   │       ├── 0003_create_agents_table.sql
│   │   │       ├── 0004_create_craftysyntax_agent.sql
│   │   │       ├── 0005_add_api_key_and_agent_id.sql
│   │   │       ├── 0006_create_biological_tables.sql
│   │   │       └── 0007_create_evolutionary_genome_table.sql
│   │   ├── functions/
│   │   │   └── dna_session.php
│   │   ├── header_images/
│   │   │   ├── blank.gif
│   │   │   ├── companyname.gif
│   │   │   ├── customersupport.png
│   │   │   ├── customersupports.png
│   │   │   ├── eyestop.gif
│   │   │   ├── grayback.gif
│   │   │   ├── linedback.gif
│   │   │   └── topclouds.gif
│   │   ├── helping/
│   │   │   ├── .php.error.log
│   │   │   ├── index.php
│   │   │   ├── salessyntax.com-Oct-2023
│   │   │   └── salessyntax.com-Oct-2023.gz
│   │   ├── images/
│   │   │   ├── blue/
│   │   │   │   ├── adminstyle.css
│   │   │   │   ├── bk.gif
│   │   │   │   ├── blank.gif
│   │   │   │   ├── bot_bg.gif
│   │   │   │   ├── botbg.gif
│   │   │   │   ├── color.php
│   │   │   │   ├── left-tab-on.gif
│   │   │   │   ├── left-tab.gif
│   │   │   │   ├── mid_bk.gif
│   │   │   │   ├── nav_bot.gif
│   │   │   │   ├── navigation.css
│   │   │   │   ├── qmark.gif
│   │   │   │   ├── right-tab-on.gif
│   │   │   │   ├── right-tab.gif
│   │   │   │   ├── Thumbs.db
│   │   │   │   ├── titlegrad.jpg
│   │   │   │   ├── top_trim.gif
│   │   │   │   ├── toplinks_1.gif
│   │   │   │   ├── toplinks_2.gif
│   │   │   │   ├── toplinks_3.gif
│   │   │   │   ├── toplinks_4.gif
│   │   │   │   ├── toplinks_5.gif
│   │   │   │   └── version.gif
│   │   │   ├── brown/
│   │   │   │   ├── adminstyle.css
│   │   │   │   ├── bk.gif
│   │   │   │   ├── blank.gif
│   │   │   │   ├── bot_bg.gif
│   │   │   │   ├── botbg.gif
│   │   │   │   ├── color.php
│   │   │   │   ├── left-tab-on.gif
│   │   │   │   ├── left-tab.gif
│   │   │   │   ├── mid_bk.gif
│   │   │   │   ├── nav_bot.gif
│   │   │   │   ├── navigation.css
│   │   │   │   ├── qmark.gif
│   │   │   │   ├── right-tab-on.gif
│   │   │   │   ├── right-tab.gif
│   │   │   │   ├── Thumbs.db
│   │   │   │   ├── top_trim.gif
│   │   │   │   ├── toplinks_1.gif
│   │   │   │   ├── toplinks_2.gif
│   │   │   │   ├── toplinks_3.gif
│   │   │   │   ├── toplinks_4.gif
│   │   │   │   ├── toplinks_5.gif
│   │   │   │   └── version.gif
│   │   │   ├── white/
│   │   │   │   ├── adminstyle.css
│   │   │   │   ├── bk.gif
│   │   │   │   ├── blank.gif
│   │   │   │   ├── bot_bg.gif
│   │   │   │   ├── botbg.gif
│   │   │   │   ├── color.php
│   │   │   │   ├── left-tab-on.gif
│   │   │   │   ├── left-tab.gif
│   │   │   │   ├── mid_bk.gif
│   │   │   │   ├── nav_bot.gif
│   │   │   │   ├── navigation.css
│   │   │   │   ├── qmark.gif
│   │   │   │   ├── right-tab-on.gif
│   │   │   │   ├── right-tab.gif
│   │   │   │   ├── Thumbs.db
│   │   │   │   ├── top_trim.gif
│   │   │   │   ├── toplinks_1.gif
│   │   │   │   ├── toplinks_2.gif
│   │   │   │   ├── toplinks_3.gif
│   │   │   │   ├── toplinks_4.gif
│   │   │   │   ├── toplinks_5.gif
│   │   │   │   └── version.gif
│   │   │   ├── yellow/
│   │   │   │   ├── adminstyle.css
│   │   │   │   ├── bk.gif
│   │   │   │   ├── blank.gif
│   │   │   │   ├── bot_bg.gif
│   │   │   │   ├── botbg.gif
│   │   │   │   ├── color.php
│   │   │   │   ├── left-tab-on.gif
│   │   │   │   ├── left-tab.gif
│   │   │   │   ├── mid_bk.gif
│   │   │   │   ├── nav_bot.gif
│   │   │   │   ├── navigation.css
│   │   │   │   ├── qmark.gif
│   │   │   │   ├── right-tab-on.gif
│   │   │   │   ├── right-tab.gif
│   │   │   │   ├── Thumbs.db
│   │   │   │   ├── top_trim.gif
│   │   │   │   ├── toplinks_1.gif
│   │   │   │   ├── toplinks_2.gif
│   │   │   │   ├── toplinks_3.gif
│   │   │   │   ├── toplinks_4.gif
│   │   │   │   ├── toplinks_5.gif
│   │   │   │   └── version.gif
│   │   │   ├── active.gif
│   │   │   ├── admin_arr.gif
│   │   │   ├── arrow_ltr.gif
│   │   │   ├── arrow_ltr.png
│   │   │   ├── arrow_off.gif
│   │   │   ├── arrow_on.gif
│   │   │   ├── back_s.gif
│   │   │   ├── banner_bg.gif
│   │   │   ├── banner_end.gif
│   │   │   ├── bar.gif
│   │   │   ├── bk.gif
│   │   │   ├── blank.gif
│   │   │   ├── blue.gif
│   │   │   ├── bot_bg.gif
│   │   │   ├── brown.gif
│   │   │   ├── browse.gif
│   │   │   ├── cannotfind.gif
│   │   │   ├── cannotopen.gif
│   │   │   ├── clear.gif
│   │   │   ├── closewin.gif
│   │   │   ├── connecting.gif
│   │   │   ├── controlimage_action.gif
│   │   │   ├── controlimage_boot.gif
│   │   │   ├── controlimage_Daction.gif
│   │   │   ├── controlimage_noaction.gif
│   │   │   ├── delete.gif
│   │   │   ├── digit0.gif
│   │   │   ├── digit1.gif
│   │   │   ├── digit2.gif
│   │   │   ├── digit3.gif
│   │   │   ├── digit4.gif
│   │   │   ├── digit5.gif
│   │   │   ├── digit6.gif
│   │   │   ├── digit7.gif
│   │   │   ├── digit8.gif
│   │   │   ├── digit9.gif
│   │   │   ├── directory.gif
│   │   │   ├── dotted.gif
│   │   │   ├── edit.gif
│   │   │   ├── editbox.gif
│   │   │   ├── exit.gif
│   │   │   ├── fb.png
│   │   │   ├── folder.gif
│   │   │   ├── folder2.gif
│   │   │   ├── games.gif
│   │   │   ├── go.gif
│   │   │   ├── gopro.png
│   │   │   ├── graylight.gif
│   │   │   ├── greenlight.gif
│   │   │   ├── help.gif
│   │   │   ├── help_a.gif
│   │   │   ├── help_folder.gif
│   │   │   ├── help_folder_open.gif
│   │   │   ├── help_q.gif
│   │   │   ├── html.gif
│   │   │   ├── icon_nosmile.gif
│   │   │   ├── icon_smile.gif
│   │   │   ├── invited.gif
│   │   │   ├── invited2.gif
│   │   │   ├── keys.gif
│   │   │   ├── keys.jpg
│   │   │   ├── leftbk.png
│   │   │   ├── leftbk2.png
│   │   │   ├── line.gif
│   │   │   ├── link.gif
│   │   │   ├── livehelp.gif
│   │   │   ├── livehelp2.gif
│   │   │   ├── livehelp3.gif
│   │   │   ├── livehelp4.gif
│   │   │   ├── livehelp5.gif
│   │   │   ├── login.jpg
│   │   │   ├── loginbk.jpg
│   │   │   ├── loginbk.psd
│   │   │   ├── logo.png
│   │   │   ├── makvis.gif
│   │   │   ├── max.gif
│   │   │   ├── message.gif
│   │   │   ├── mid_banner.gif
│   │   │   ├── mid_banner1.gif
│   │   │   ├── mid_banner1a.gif
│   │   │   ├── mid_banner2.gif
│   │   │   ├── mid_banner2a.gif
│   │   │   ├── mid_banner3.gif
│   │   │   ├── mid_banner3a.gif
│   │   │   ├── mid_banner4.gif
│   │   │   ├── mid_banner4a.gif
│   │   │   ├── mid_bk.gif
│   │   │   ├── minus.gif
│   │   │   ├── nav_admin.gif
│   │   │   ├── nav_bg.gif
│   │   │   ├── nav_bot.gif
│   │   │   ├── nav_data.gif
│   │   │   ├── nav_dept.gif
│   │   │   ├── nav_livehelp.gif
│   │   │   ├── nav_open.gif
│   │   │   ├── nav_oper.gif
│   │   │   ├── nav_qa.gif
│   │   │   ├── nav_settings.gif
│   │   │   ├── nav_tope.gif
│   │   │   ├── needaction.gif
│   │   │   ├── newwin.gif
│   │   │   ├── next_s.gif
│   │   │   ├── noqmark.gif
│   │   │   ├── noton.gif
│   │   │   ├── notracking.gif
│   │   │   ├── operator.gif
│   │   │   ├── operator_gray.gif
│   │   │   ├── paint.gif
│   │   │   ├── plus.gif
│   │   │   ├── pp.gif
│   │   │   ├── print.gif
│   │   │   ├── qmark.gif
│   │   │   ├── qna.gif
│   │   │   ├── redlight.gif
│   │   │   ├── refresh.gif
│   │   │   ├── refreshpeople.png
│   │   │   ├── requestchat.gif
│   │   │   ├── requestDHTML.gif
│   │   │   ├── reset.gif
│   │   │   ├── rightbk.png
│   │   │   ├── settings.gif
│   │   │   ├── silver.gif
│   │   │   ├── stopped.gif
│   │   │   ├── tabBg.png
│   │   │   ├── tabLeft.png
│   │   │   ├── tabRight.png
│   │   │   ├── tabSelectedBg.png
│   │   │   ├── tabSelectedLeft.png
│   │   │   ├── tabSelectedRight.png
│   │   │   ├── Thumbs.db
│   │   │   ├── top_bk.gif
│   │   │   ├── top_trim.gif
│   │   │   ├── topadmin.png
│   │   │   ├── toplinks_1.gif
│   │   │   ├── toplinks_2.gif
│   │   │   ├── toplinks_3.gif
│   │   │   ├── toplinks_4.gif
│   │   │   ├── toplinks_5.gif
│   │   │   ├── trash.gif
│   │   │   ├── tw.png
│   │   │   ├── user_c_contact.gif
│   │   │   ├── user_c_livehelp.gif
│   │   │   ├── user_nav.gif
│   │   │   ├── user_o_contact.gif
│   │   │   ├── user_o_livehelp.gif
│   │   │   ├── user_o_qa.gif
│   │   │   ├── user_o_search.gif
│   │   │   ├── version.gif
│   │   │   ├── white.gif
│   │   │   ├── xnav_topics.gif
│   │   │   └── yellow.gif
│   │   ├── iphone/
│   │   │   ├── class/
│   │   │   │   ├── browser_info.php
│   │   │   │   ├── ctabbox.php
│   │   │   │   ├── mysql_db.php
│   │   │   │   ├── mysqli_db.php
│   │   │   │   ├── operator.php
│   │   │   │   ├── sessionmanager.php
│   │   │   │   └── smtp.php
│   │   │   ├── css/
│   │   │   │   └── base.css
│   │   │   ├── images/
│   │   │   │   ├── 3r6l5gw2.gif
│   │   │   │   ├── 4ntxgf0f.png
│   │   │   │   ├── 6w95fo9x.gif
│   │   │   │   ├── answer.jpg
│   │   │   │   ├── blank.gif
│   │   │   │   ├── blue.jpg
│   │   │   │   ├── c22294le.gif
│   │   │   │   ├── can-off.gif
│   │   │   │   ├── can.gif
│   │   │   │   ├── chats-b.jpg
│   │   │   │   ├── chats-lb.jpg
│   │   │   │   ├── color.jpg
│   │   │   │   ├── djz6d97d.gif
│   │   │   │   ├── exit.jpg
│   │   │   │   ├── eye.jpg
│   │   │   │   ├── help.png
│   │   │   │   ├── invite.jpg
│   │   │   │   ├── lightblue.jpg
│   │   │   │   ├── login.png
│   │   │   │   ├── logout.jpg
│   │   │   │   ├── logout.png
│   │   │   │   ├── mid.jpg
│   │   │   │   ├── newwin.jpg
│   │   │   │   ├── password.png
│   │   │   │   ├── red.jpg
│   │   │   │   ├── requests-db.jpg
│   │   │   │   ├── requests-lb.jpg
│   │   │   │   ├── requests-red.jpg
│   │   │   │   ├── requests-rr.jpg
│   │   │   │   ├── send.jpg
│   │   │   │   ├── settings-b.jpg
│   │   │   │   ├── settings-lb.jpg
│   │   │   │   ├── top.jpg
│   │   │   │   ├── top.png
│   │   │   │   ├── visitors-b.jpg
│   │   │   │   └── visitors-lb.jpg
│   │   │   ├── admin.php
│   │   │   ├── chat_color.php
│   │   │   ├── functions.php
│   │   │   ├── index.php
│   │   │   ├── iscroll.js
│   │   │   ├── json.php
│   │   │   ├── layer.php
│   │   │   ├── live.php
│   │   │   ├── login.php
│   │   │   ├── logout.php
│   │   │   ├── lostsheep.php
│   │   │   ├── mobileheader.php
│   │   │   ├── requests.php
│   │   │   ├── settings.php
│   │   │   ├── visitors.php
│   │   │   └── xmlhttp.php
│   │   ├── javascript/
│   │   │   ├── dynapi/
│   │   │   │   ├── js/
│   │   │   │   │   └── dynlayer.js
│   │   │   │   ├── index.html
│   │   │   │   ├── LICENSE
│   │   │   │   ├── README
│   │   │   │   └── REVISION
│   │   │   ├── hideshow.js
│   │   │   ├── old_xmlhttp.js
│   │   │   ├── staticMenu.js
│   │   │   ├── xBrowser.js
│   │   │   ├── xLayer.js
│   │   │   ├── xmlhttp.js
│   │   │   └── xMouse.js
│   │   ├── lang/
│   │   │   ├── lang-.php
│   │   │   ├── lang-Dutch.php
│   │   │   ├── lang-English.php
│   │   │   ├── lang-English_uk.php
│   │   │   ├── lang-French.php
│   │   │   ├── lang-German.php
│   │   │   ├── lang-Greek.php
│   │   │   ├── lang-Greek_gr.php
│   │   │   ├── lang-Italian.php
│   │   │   ├── lang-Polish.php
│   │   │   ├── lang-Portuguese_Brazilian.php
│   │   │   ├── lang-Portuguese_Portugal.php
│   │   │   ├── lang-Spanish.php
│   │   │   └── lang-Swedish.php
│   │   ├── layer_invites/
│   │   │   ├── layer-Help_button.gif
│   │   │   ├── layer-Help_button.txt
│   │   │   ├── layer-Help_buttonoffline.gif
│   │   │   ├── layer-Help_buttonoffline.txt
│   │   │   ├── layer-Man_invite.gif
│   │   │   ├── layer-Man_invite.txt
│   │   │   ├── layer-Phone.gif
│   │   │   ├── layer-Phone.txt
│   │   │   ├── layer-Subsilver.gif
│   │   │   ├── layer-Subsilver.txt
│   │   │   ├── layer-Woman_invite.png
│   │   │   └── layer-Woman_invite.txt
│   │   ├── mobile/
│   │   │   ├── class/
│   │   │   │   ├── browser_info.php
│   │   │   │   ├── ctabbox.php
│   │   │   │   ├── mysql_db.php
│   │   │   │   ├── mysqli_db.php
│   │   │   │   ├── operator.php
│   │   │   │   ├── sessionmanager.php
│   │   │   │   └── smtp.php
│   │   │   ├── css/
│   │   │   │   └── base.css
│   │   │   ├── images/
│   │   │   │   ├── 3r6l5gw2.gif
│   │   │   │   ├── 4ntxgf0f.png
│   │   │   │   ├── 6w95fo9x.gif
│   │   │   │   ├── answer.jpg
│   │   │   │   ├── blank.gif
│   │   │   │   ├── blue.jpg
│   │   │   │   ├── c22294le.gif
│   │   │   │   ├── can-off.gif
│   │   │   │   ├── can.gif
│   │   │   │   ├── chats-b.jpg
│   │   │   │   ├── chats-lb.jpg
│   │   │   │   ├── color.jpg
│   │   │   │   ├── djz6d97d.gif
│   │   │   │   ├── dummy.png
│   │   │   │   ├── exit.jpg
│   │   │   │   ├── eye.jpg
│   │   │   │   ├── help.png
│   │   │   │   ├── invite.jpg
│   │   │   │   ├── lightblue.jpg
│   │   │   │   ├── login.png
│   │   │   │   ├── logout.jpg
│   │   │   │   ├── logout.png
│   │   │   │   ├── mid.jpg
│   │   │   │   ├── newwin.jpg
│   │   │   │   ├── password.png
│   │   │   │   ├── red.jpg
│   │   │   │   ├── requests-db.jpg
│   │   │   │   ├── requests-lb.jpg
│   │   │   │   ├── requests-red.jpg
│   │   │   │   ├── requests-rr.jpg
│   │   │   │   ├── send.jpg
│   │   │   │   ├── settings-b.jpg
│   │   │   │   ├── settings-lb.jpg
│   │   │   │   ├── top.jpg
│   │   │   │   ├── top.png
│   │   │   │   ├── visitors-b.jpg
│   │   │   │   └── visitors-lb.jpg
│   │   │   ├── admin.php
│   │   │   ├── chat_color.php
│   │   │   ├── index.php
│   │   │   ├── iscroll.js
│   │   │   ├── layer.php
│   │   │   ├── live.php
│   │   │   ├── login.php
│   │   │   ├── logout.php
│   │   │   ├── lostsheep.php
│   │   │   ├── mobileheader.php
│   │   │   ├── requests.php
│   │   │   ├── settings.php
│   │   │   ├── visitors.php
│   │   │   └── xmlhttp.php
│   │   ├── onoff_images/
│   │   │   ├── offline1.gif
│   │   │   ├── offline2.gif
│   │   │   ├── offline3.gif
│   │   │   ├── online1.gif
│   │   │   ├── online2.gif
│   │   │   └── online3.gif
│   │   ├── README_FILES/
│   │   │   ├── AUTHORS.txt
│   │   │   ├── block_moodle_LiveHelp.php
│   │   │   ├── CHANGELOG.md
│   │   │   ├── CHANGELOG_OLD.txt
│   │   │   ├── COPYING.txt
│   │   │   ├── gpl.htm
│   │   │   ├── INSTALL.txt
│   │   │   ├── LICENSE.txt
│   │   │   ├── livehelp.php
│   │   │   ├── MOODLE.txt
│   │   │   ├── new_changelog.txt
│   │   │   ├── PHPBB2.txt
│   │   │   ├── README.txt
│   │   │   └── UPGRADE.txt
│   │   ├── sounds/
│   │   │   ├── click_x.mp3
│   │   │   ├── click_x.wav
│   │   │   ├── doorbell2.mp3
│   │   │   ├── doorbell2.wav
│   │   │   ├── doorbell_x.mp3
│   │   │   ├── doorbell_x.wav
│   │   │   ├── fart_z.mp3
│   │   │   ├── fart_z.wav
│   │   │   ├── floop2_x.mp3
│   │   │   ├── floop2_x.wav
│   │   │   ├── floop_sfx.mp3
│   │   │   ├── floop_sfx.wav
│   │   │   ├── insite.mp3
│   │   │   ├── insite.wav
│   │   │   ├── LC2_New_Chats.mp3
│   │   │   ├── LC2_New_Chats.wav
│   │   │   ├── LC2_New_Message.mp3
│   │   │   ├── LC2_New_Message.wav
│   │   │   ├── LC2_New_Visitors.mp3
│   │   │   ├── LC2_New_Visitors.wav
│   │   │   ├── new_chats.mp3
│   │   │   ├── new_chats.wav
│   │   │   ├── new_message.mp3
│   │   │   ├── new_message.wav
│   │   │   ├── new_visitors.mp3
│   │   │   ├── new_visitors.wav
│   │   │   ├── phone_ring2.mp3
│   │   │   ├── phone_ring2.wav
│   │   │   ├── phone_ring_old.mp3
│   │   │   ├── phone_ring_old.wav
│   │   │   ├── silence.mp3
│   │   │   ├── silence.wav
│   │   │   ├── someone_wants_to_chat.mp3
│   │   │   ├── someone_wants_to_chat.wav
│   │   │   ├── sound.mp3
│   │   │   ├── sound.wav
│   │   │   ├── typing.mp3
│   │   │   ├── typing.wav
│   │   │   ├── youve_got_visitors.mp3
│   │   │   └── youve_got_visitors.wav
│   │   ├── themes/
│   │   │   ├── 2025_modern/
│   │   │   │   ├── botframe.css
│   │   │   │   ├── chatbubble.css
│   │   │   │   ├── chatframe.css
│   │   │   │   ├── chatwindow.php
│   │   │   │   ├── chatwindow_large.php
│   │   │   │   ├── connecting.php
│   │   │   │   ├── leavemessage.css
│   │   │   │   ├── operator.jpg
│   │   │   │   ├── style.css
│   │   │   │   ├── theme-options.php
│   │   │   │   └── windowsize.php
│   │   │   ├── basic/
│   │   │   │   ├── background.png
│   │   │   │   ├── bg_button_a.gif
│   │   │   │   ├── bg_button_span.gif
│   │   │   │   ├── botframe.css
│   │   │   │   ├── chat_bubble_bg.png
│   │   │   │   ├── chatbubble.css
│   │   │   │   ├── chatbubble.jpg
│   │   │   │   ├── chatbubble_large.jpg
│   │   │   │   ├── chatframe-css.php
│   │   │   │   ├── chatframe.css
│   │   │   │   ├── chatwindow.php
│   │   │   │   ├── chatwindow.php.bak
│   │   │   │   ├── chatwindow_large.php
│   │   │   │   ├── clear.gif
│   │   │   │   ├── connecting.gif
│   │   │   │   ├── connecting.php
│   │   │   │   ├── exit.gif
│   │   │   │   ├── grayomi.png
│   │   │   │   ├── leavemessage.css
│   │   │   │   ├── left-tab-on.gif
│   │   │   │   ├── left-tab.gif
│   │   │   │   ├── operator.jpg
│   │   │   │   ├── patch.png
│   │   │   │   ├── print.gif
│   │   │   │   ├── redlight.gif
│   │   │   │   ├── refresh.gif
│   │   │   │   ├── send.png
│   │   │   │   ├── style.css
│   │   │   │   ├── theme-options.php
│   │   │   │   ├── topbubble.gif
│   │   │   │   └── windowsize.php
│   │   │   ├── bubble_box/
│   │   │   │   ├── bg_button_a.gif
│   │   │   │   ├── bg_button_span.gif
│   │   │   │   ├── botframe.css
│   │   │   │   ├── button.jpg
│   │   │   │   ├── chat_bubble_bg.png
│   │   │   │   ├── chatbubble.css
│   │   │   │   ├── chatbubble.jpg
│   │   │   │   ├── chatbubble_large.jpg
│   │   │   │   ├── chatframe.css
│   │   │   │   ├── chatwindow.php
│   │   │   │   ├── chatwindow_large.php
│   │   │   │   ├── clear.gif
│   │   │   │   ├── connecting.gif
│   │   │   │   ├── connecting.php
│   │   │   │   ├── exit.gif
│   │   │   │   ├── leavemessage.css
│   │   │   │   ├── operator.jpg
│   │   │   │   ├── print.gif
│   │   │   │   ├── refresh.gif
│   │   │   │   ├── style.css
│   │   │   │   ├── theme-options.php
│   │   │   │   └── windowsize.php
│   │   │   ├── bubble_window/
│   │   │   │   ├── bg_button_a.gif
│   │   │   │   ├── bg_button_span.gif
│   │   │   │   ├── botframe.css
│   │   │   │   ├── button.jpg
│   │   │   │   ├── chat_bubble_bg.png
│   │   │   │   ├── chatbubble.css
│   │   │   │   ├── chatbubble.jpg
│   │   │   │   ├── chatbubble_large.jpg
│   │   │   │   ├── chatframe.css
│   │   │   │   ├── chatwindow.php
│   │   │   │   ├── chatwindow_large.php
│   │   │   │   ├── clear.gif
│   │   │   │   ├── connecting.gif
│   │   │   │   ├── connecting.php
│   │   │   │   ├── eric.jpg
│   │   │   │   ├── exit.gif
│   │   │   │   ├── leavemessage.css
│   │   │   │   ├── operator.jpg
│   │   │   │   ├── print.gif
│   │   │   │   ├── refresh.gif
│   │   │   │   ├── style.css
│   │   │   │   ├── theme-options.php
│   │   │   │   └── windowsize.php
│   │   │   ├── classic/
│   │   │   │   ├── bg_button_a.gif
│   │   │   │   ├── bg_button_span.gif
│   │   │   │   ├── blueomi.png
│   │   │   │   ├── botbubble.gif
│   │   │   │   ├── botframe.css
│   │   │   │   ├── chat_bubble_bg.png
│   │   │   │   ├── chatbubble.css
│   │   │   │   ├── chatbubble.jpg
│   │   │   │   ├── chatbubble_large.jpg
│   │   │   │   ├── chatframe-css.php
│   │   │   │   ├── chatframe.css
│   │   │   │   ├── chatwindow.php
│   │   │   │   ├── chatwindow.php.bak
│   │   │   │   ├── chatwindow_large.php
│   │   │   │   ├── chatwindow_large.php.bak
│   │   │   │   ├── clear.gif
│   │   │   │   ├── connecting.gif
│   │   │   │   ├── connecting.php
│   │   │   │   ├── exit.gif
│   │   │   │   ├── grayomi.png
│   │   │   │   ├── greenlight.gif
│   │   │   │   ├── leavemessage.css
│   │   │   │   ├── left-tab-on.gif
│   │   │   │   ├── left-tab.gif
│   │   │   │   ├── operator.jpg
│   │   │   │   ├── patch.png
│   │   │   │   ├── patchyellow.png
│   │   │   │   ├── print.gif
│   │   │   │   ├── redlight.gif
│   │   │   │   ├── refresh.gif
│   │   │   │   ├── right-tab-on.gif
│   │   │   │   ├── right-tab.gif
│   │   │   │   ├── softblueomi.png
│   │   │   │   ├── softyellowomi.png
│   │   │   │   ├── style.css
│   │   │   │   ├── theme-options.php
│   │   │   │   ├── topbar.png
│   │   │   │   ├── topbubble.gif
│   │   │   │   ├── windowsize.php
│   │   │   │   └── yellowomi.png
│   │   │   ├── classic_notabs/
│   │   │   │   ├── bg_button_a.gif
│   │   │   │   ├── bg_button_span.gif
│   │   │   │   ├── blueomi.png
│   │   │   │   ├── botbubble.gif
│   │   │   │   ├── botframe.css
│   │   │   │   ├── chat_bubble_bg.png
│   │   │   │   ├── chatbubble.css
│   │   │   │   ├── chatbubble.jpg
│   │   │   │   ├── chatbubble_large.jpg
│   │   │   │   ├── chatframe-css.php
│   │   │   │   ├── chatframe.css
│   │   │   │   ├── chatwindow.php
│   │   │   │   ├── chatwindow.php.bak
│   │   │   │   ├── chatwindow_large.php
│   │   │   │   ├── chatwindow_large.php.bak
│   │   │   │   ├── clear.gif
│   │   │   │   ├── connecting.gif
│   │   │   │   ├── connecting.php
│   │   │   │   ├── exit.gif
│   │   │   │   ├── grayomi.png
│   │   │   │   ├── greenlight.gif
│   │   │   │   ├── leavemessage.css
│   │   │   │   ├── left-tab-on copy.gif
│   │   │   │   ├── left-tab-on.gif
│   │   │   │   ├── left-tab.gif
│   │   │   │   ├── operator.jpg
│   │   │   │   ├── patch.png
│   │   │   │   ├── patchyellow.png
│   │   │   │   ├── print.gif
│   │   │   │   ├── redlight.gif
│   │   │   │   ├── refresh.gif
│   │   │   │   ├── right-tab-on.gif
│   │   │   │   ├── right-tab.gif
│   │   │   │   ├── softblueomi.png
│   │   │   │   ├── style.css
│   │   │   │   ├── theme-options.php
│   │   │   │   ├── topbar.png
│   │   │   │   ├── topbubble.gif
│   │   │   │   ├── windowsize.php
│   │   │   │   └── yellowomi.png
│   │   │   ├── operator_top/
│   │   │   │   ├── bg_button_a.gif
│   │   │   │   ├── bg_button_span.gif
│   │   │   │   ├── blueomi.png
│   │   │   │   ├── botframe.css
│   │   │   │   ├── chat_bubble_bg.png
│   │   │   │   ├── chatbubble.css
│   │   │   │   ├── chatbubble.jpg
│   │   │   │   ├── chatbubble_large.jpg
│   │   │   │   ├── chatframe-css.php
│   │   │   │   ├── chatframe.css
│   │   │   │   ├── chatwindow.php
│   │   │   │   ├── chatwindow.php.bak
│   │   │   │   ├── chatwindow_large.php
│   │   │   │   ├── chatwindow_large.php.bak
│   │   │   │   ├── clear.gif
│   │   │   │   ├── connecting.gif
│   │   │   │   ├── connecting.php
│   │   │   │   ├── exit.gif
│   │   │   │   ├── grayomi.png
│   │   │   │   ├── greenlight.gif
│   │   │   │   ├── leavemessage.css
│   │   │   │   ├── operator.jpg
│   │   │   │   ├── patch.png
│   │   │   │   ├── patchyellow.png
│   │   │   │   ├── print.gif
│   │   │   │   ├── redlight.gif
│   │   │   │   ├── refresh.gif
│   │   │   │   ├── softblueomi.png
│   │   │   │   ├── style.css
│   │   │   │   ├── theme-options.php
│   │   │   │   ├── topbar.png
│   │   │   │   ├── windowsize.php
│   │   │   │   └── yellowomi.png
│   │   │   ├── unlimited/
│   │   │   │   ├── background.png
│   │   │   │   ├── bg_button_a.gif
│   │   │   │   ├── bg_button_span.gif
│   │   │   │   ├── blueomi.png
│   │   │   │   ├── botbubble.gif
│   │   │   │   ├── botframe.css
│   │   │   │   ├── chat_bubble_bg.png
│   │   │   │   ├── chatbubble.css
│   │   │   │   ├── chatbubble.jpg
│   │   │   │   ├── chatbubble_large.jpg
│   │   │   │   ├── chatframe-css.php
│   │   │   │   ├── chatframe.css
│   │   │   │   ├── chatwindow.php
│   │   │   │   ├── chatwindow_large.php
│   │   │   │   ├── clear.gif
│   │   │   │   ├── connecting.gif
│   │   │   │   ├── connecting.php
│   │   │   │   ├── exit.gif
│   │   │   │   ├── grayomi.png
│   │   │   │   ├── greenlight.gif
│   │   │   │   ├── leavemessage.css
│   │   │   │   ├── left-tab-on.gif
│   │   │   │   ├── left-tab.gif
│   │   │   │   ├── operator.jpg
│   │   │   │   ├── patch.png
│   │   │   │   ├── patchyellow.png
│   │   │   │   ├── print.gif
│   │   │   │   ├── redlight.gif
│   │   │   │   ├── refresh.gif
│   │   │   │   ├── right-tab-on.gif
│   │   │   │   ├── right-tab.gif
│   │   │   │   ├── softblueomi.png
│   │   │   │   ├── softyellowomi.png
│   │   │   │   ├── style.css
│   │   │   │   ├── theme-options.php
│   │   │   │   ├── topbar.png
│   │   │   │   ├── topbubble.gif
│   │   │   │   ├── windowsize.php
│   │   │   │   └── yellowomi.png
│   │   │   ├── vanilla/
│   │   │   │   ├── bg_button_a.gif
│   │   │   │   ├── bg_button_span.gif
│   │   │   │   ├── blueomi.png
│   │   │   │   ├── botframe.css
│   │   │   │   ├── chat_bubble_bg.png
│   │   │   │   ├── chatbubble.css
│   │   │   │   ├── chatbubble.jpg
│   │   │   │   ├── chatbubble_large.jpg
│   │   │   │   ├── chatframe-css.php
│   │   │   │   ├── chatframe.css
│   │   │   │   ├── chatwindow.php
│   │   │   │   ├── chatwindow_large.php
│   │   │   │   ├── clear.gif
│   │   │   │   ├── connecting.gif
│   │   │   │   ├── connecting.php
│   │   │   │   ├── exit.gif
│   │   │   │   ├── grayomi.png
│   │   │   │   ├── greenlight.gif
│   │   │   │   ├── leavemessage.css
│   │   │   │   ├── operator.jpg
│   │   │   │   ├── patch.png
│   │   │   │   ├── patchyellow.png
│   │   │   │   ├── print.gif
│   │   │   │   ├── redlight.gif
│   │   │   │   ├── refresh.gif
│   │   │   │   ├── softblueomi.png
│   │   │   │   ├── softyellowomi.png
│   │   │   │   ├── style.css
│   │   │   │   ├── theme-options.php
│   │   │   │   ├── topbar.png
│   │   │   │   ├── windowsize.php
│   │   │   │   └── yellowomi.png
│   │   │   └── README.txt
│   │   ├── admin.php
│   │   ├── admin_actions.php
│   │   ├── admin_chat_bot.php
│   │   ├── admin_chat_flush.php
│   │   ├── admin_chat_refresh.php
│   │   ├── admin_chat_xmlhttp.php
│   │   ├── admin_common-old.php
│   │   ├── admin_common.php
│   │   ├── admin_connect.php
│   │   ├── admin_image.php
│   │   ├── admin_options.php
│   │   ├── admin_rooms.php
│   │   ├── admin_users.php
│   │   ├── admin_users_refresh.php
│   │   ├── admin_users_xmlhttp.php
│   │   ├── adminstyle.css
│   │   ├── agents.php
│   │   ├── auth.php
│   │   ├── autoinvite.php
│   │   ├── autolead.php
│   │   ├── channels.php
│   │   ├── chat_color.php
│   │   ├── choosedepartment.php
│   │   ├── class.browser_info.php
│   │   ├── client_visitors.php
│   │   ├── colorchange.php
│   │   ├── companyname.gif
│   │   ├── config.orig.php
│   │   ├── config.php
│   │   ├── config_cslh.php
│   │   ├── createemail.php
│   │   ├── csv.php
│   │   ├── ctabbox.php
│   │   ├── data.php
│   │   ├── data_clean.php
│   │   ├── data_functions.php
│   │   ├── data_keywords.php
│   │   ├── data_messages.php
│   │   ├── data_paths.php
│   │   ├── data_referers.php
│   │   ├── data_transcripts.php
│   │   ├── data_users.php
│   │   ├── data_visits.php
│   │   ├── debug.txt
│   │   ├── department_function.php
│   │   ├── departments.php
│   │   ├── details.php
│   │   ├── dhtmlimage.gif
│   │   ├── dhtmlimage2.gif
│   │   ├── directions.gif
│   │   ├── directions2.gif
│   │   ├── DO_NOT_MODIFY.txt
│   │   ├── edit_layer.php
│   │   ├── edit_quick.php
│   │   ├── edit_smile.php
│   │   ├── export.php
│   │   ├── external_bot.php
│   │   ├── external_chat_xmlhttp.php
│   │   ├── external_frameset.php
│   │   ├── external_top.php
│   │   ├── eyestop.gif
│   │   ├── file_get_contents.php
│   │   ├── flush.php
│   │   ├── functions.php
│   │   ├── gc.php
│   │   ├── gpl.htm
│   │   ├── graph.php
│   │   ├── grayback.gif
│   │   ├── help.php
│   │   ├── helpwindow.php
│   │   ├── htmltags.php
│   │   ├── image.php
│   │   ├── importleads.php
│   │   ├── index.php
│   │   ├── insite.php
│   │   ├── invite.php
│   │   ├── is_flush.php
│   │   ├── is_xmlhttp.php
│   │   ├── lang-default.php
│   │   ├── layer.php
│   │   ├── layer_utils.php
│   │   ├── leads.php
│   │   ├── leavemessage.gif
│   │   ├── leavemessage.php
│   │   ├── linedback.gif
│   │   ├── live.php
│   │   ├── livehelp.php
│   │   ├── livehelp_js.php
│   │   ├── login.php
│   │   ├── logout.php
│   │   ├── lostsheep.php
│   │   ├── mastersettings.php
│   │   ├── modules.php
│   │   ├── navigation.php
│   │   ├── offline.gif
│   │   ├── offline.php
│   │   ├── online.gif
│   │   ├── operators.php
│   │   ├── operators_history.php
│   │   ├── prefs.php
│   │   ├── prepend.php
│   │   ├── qa.php
│   │   ├── registerit.php
│   │   ├── reset.php
│   │   ├── rules.php
│   │   ├── scratch.php
│   │   ├── security.php
│   │   ├── security_functions.php
│   │   ├── send.php
│   │   ├── sendemail.php
│   │   ├── sendtranscript.php
│   │   ├── settings.php
│   │   ├── setup.php
│   │   ├── smile.php
│   │   ├── sqllog.txt
│   │   ├── style.css
│   │   ├── test.php
│   │   ├── topclouds.gif
│   │   ├── user_bot.php
│   │   ├── user_chat_flush.php
│   │   ├── user_chat_refresh.php
│   │   ├── user_chat_xmlhttp.php
│   │   ├── user_connect.php
│   │   ├── user_qa.php
│   │   ├── user_questions.php
│   │   ├── user_top.php
│   │   ├── view_message.php
│   │   ├── view_transcript.php
│   │   ├── visitor_common.php
│   │   ├── wentaway.php
│   │   └── xmlhttp.php
│   ├── duplicates/
│   │   ├── ASK_HUMAN_WOLFIE_LUPOPEDIA_20-26.md
│   │   └── SYSTEM_INTEGRATION_TESTING_DOCTRINE.md
│   ├── wordpress/
│   │   ├── wp-admin/
│   │   │   ├── css/
│   │   │   │   ├── colors/
│   │   │   │   │   ├── blue/
│   │   │   │   │   │   ├── colors-rtl.css
│   │   │   │   │   │   ├── colors-rtl.min.css
│   │   │   │   │   │   ├── colors.css
│   │   │   │   │   │   ├── colors.min.css
│   │   │   │   │   │   └── colors.scss
│   │   │   │   │   ├── coffee/
│   │   │   │   │   │   ├── colors-rtl.css
│   │   │   │   │   │   ├── colors-rtl.min.css
│   │   │   │   │   │   ├── colors.css
│   │   │   │   │   │   ├── colors.min.css
│   │   │   │   │   │   └── colors.scss
│   │   │   │   │   ├── ectoplasm/
│   │   │   │   │   │   ├── colors-rtl.css
│   │   │   │   │   │   ├── colors-rtl.min.css
│   │   │   │   │   │   ├── colors.css
│   │   │   │   │   │   ├── colors.min.css
│   │   │   │   │   │   └── colors.scss
│   │   │   │   │   ├── light/
│   │   │   │   │   │   ├── colors-rtl.css
│   │   │   │   │   │   ├── colors-rtl.min.css
│   │   │   │   │   │   ├── colors.css
│   │   │   │   │   │   ├── colors.min.css
│   │   │   │   │   │   └── colors.scss
│   │   │   │   │   ├── midnight/
│   │   │   │   │   │   ├── colors-rtl.css
│   │   │   │   │   │   ├── colors-rtl.min.css
│   │   │   │   │   │   ├── colors.css
│   │   │   │   │   │   ├── colors.min.css
│   │   │   │   │   │   └── colors.scss
│   │   │   │   │   ├── modern/
│   │   │   │   │   │   ├── colors-rtl.css
│   │   │   │   │   │   ├── colors-rtl.min.css
│   │   │   │   │   │   ├── colors.css
│   │   │   │   │   │   ├── colors.min.css
│   │   │   │   │   │   └── colors.scss
│   │   │   │   │   ├── ocean/
│   │   │   │   │   │   ├── colors-rtl.css
│   │   │   │   │   │   ├── colors-rtl.min.css
│   │   │   │   │   │   ├── colors.css
│   │   │   │   │   │   ├── colors.min.css
│   │   │   │   │   │   └── colors.scss
│   │   │   │   │   ├── sunrise/
│   │   │   │   │   │   ├── colors-rtl.css
│   │   │   │   │   │   ├── colors-rtl.min.css
│   │   │   │   │   │   ├── colors.css
│   │   │   │   │   │   ├── colors.min.css
│   │   │   │   │   │   └── colors.scss
│   │   │   │   │   ├── _admin.scss
│   │   │   │   │   ├── _mixins.scss
│   │   │   │   │   └── _variables.scss
│   │   │   │   ├── about-rtl.css
│   │   │   │   ├── about-rtl.min.css
│   │   │   │   ├── about.css
│   │   │   │   ├── about.min.css
│   │   │   │   ├── admin-menu-rtl.css
│   │   │   │   ├── admin-menu-rtl.min.css
│   │   │   │   ├── admin-menu.css
│   │   │   │   ├── admin-menu.min.css
│   │   │   │   ├── code-editor-rtl.css
│   │   │   │   ├── code-editor-rtl.min.css
│   │   │   │   ├── code-editor.css
│   │   │   │   ├── code-editor.min.css
│   │   │   │   ├── color-picker-rtl.css
│   │   │   │   ├── color-picker-rtl.min.css
│   │   │   │   ├── color-picker.css
│   │   │   │   ├── color-picker.min.css
│   │   │   │   ├── common-rtl.css
│   │   │   │   ├── common-rtl.min.css
│   │   │   │   ├── common.css
│   │   │   │   ├── common.min.css
│   │   │   │   ├── customize-controls-rtl.css
│   │   │   │   ├── customize-controls-rtl.min.css
│   │   │   │   ├── customize-controls.css
│   │   │   │   ├── customize-controls.min.css
│   │   │   │   ├── customize-nav-menus-rtl.css
│   │   │   │   ├── customize-nav-menus-rtl.min.css
│   │   │   │   ├── customize-nav-menus.css
│   │   │   │   ├── customize-nav-menus.min.css
│   │   │   │   ├── customize-widgets-rtl.css
│   │   │   │   ├── customize-widgets-rtl.min.css
│   │   │   │   ├── customize-widgets.css
│   │   │   │   ├── customize-widgets.min.css
│   │   │   │   ├── dashboard-rtl.css
│   │   │   │   ├── dashboard-rtl.min.css
│   │   │   │   ├── dashboard.css
│   │   │   │   ├── dashboard.min.css
│   │   │   │   ├── deprecated-media-rtl.css
│   │   │   │   ├── deprecated-media-rtl.min.css
│   │   │   │   ├── deprecated-media.css
│   │   │   │   ├── deprecated-media.min.css
│   │   │   │   ├── edit-rtl.css
│   │   │   │   ├── edit-rtl.min.css
│   │   │   │   ├── edit.css
│   │   │   │   ├── edit.min.css
│   │   │   │   ├── farbtastic-rtl.css
│   │   │   │   ├── farbtastic-rtl.min.css
│   │   │   │   ├── farbtastic.css
│   │   │   │   ├── farbtastic.min.css
│   │   │   │   ├── forms-rtl.css
│   │   │   │   ├── forms-rtl.min.css
│   │   │   │   ├── forms.css
│   │   │   │   ├── forms.min.css
│   │   │   │   ├── install-rtl.css
│   │   │   │   ├── install-rtl.min.css
│   │   │   │   ├── install.css
│   │   │   │   ├── install.min.css
│   │   │   │   ├── l10n-rtl.css
│   │   │   │   ├── l10n-rtl.min.css
│   │   │   │   ├── l10n.css
│   │   │   │   ├── l10n.min.css
│   │   │   │   ├── list-tables-rtl.css
│   │   │   │   ├── list-tables-rtl.min.css
│   │   │   │   ├── list-tables.css
│   │   │   │   ├── list-tables.min.css
│   │   │   │   ├── login-rtl.css
│   │   │   │   ├── login-rtl.min.css
│   │   │   │   ├── login.css
│   │   │   │   ├── login.min.css
│   │   │   │   ├── media-rtl.css
│   │   │   │   ├── media-rtl.min.css
│   │   │   │   ├── media.css
│   │   │   │   ├── media.min.css
│   │   │   │   ├── nav-menus-rtl.css
│   │   │   │   ├── nav-menus-rtl.min.css
│   │   │   │   ├── nav-menus.css
│   │   │   │   ├── nav-menus.min.css
│   │   │   │   ├── revisions-rtl.css
│   │   │   │   ├── revisions-rtl.min.css
│   │   │   │   ├── revisions.css
│   │   │   │   ├── revisions.min.css
│   │   │   │   ├── site-health-rtl.css
│   │   │   │   ├── site-health-rtl.min.css
│   │   │   │   ├── site-health.css
│   │   │   │   ├── site-health.min.css
│   │   │   │   ├── site-icon-rtl.css
│   │   │   │   ├── site-icon-rtl.min.css
│   │   │   │   ├── site-icon.css
│   │   │   │   ├── site-icon.min.css
│   │   │   │   ├── themes-rtl.css
│   │   │   │   ├── themes-rtl.min.css
│   │   │   │   ├── themes.css
│   │   │   │   ├── themes.min.css
│   │   │   │   ├── widgets-rtl.css
│   │   │   │   ├── widgets-rtl.min.css
│   │   │   │   ├── widgets.css
│   │   │   │   ├── widgets.min.css
│   │   │   │   ├── wp-admin-rtl.css
│   │   │   │   ├── wp-admin-rtl.min.css
│   │   │   │   ├── wp-admin.css
│   │   │   │   └── wp-admin.min.css
│   │   │   ├── images/
│   │   │   │   ├── about-release-badge.svg
│   │   │   │   ├── about-release-logo.svg
│   │   │   │   ├── about-texture.png
│   │   │   │   ├── align-center-2x.png
│   │   │   │   ├── align-center.png
│   │   │   │   ├── align-left-2x.png
│   │   │   │   ├── align-left.png
│   │   │   │   ├── align-none-2x.png
│   │   │   │   ├── align-none.png
│   │   │   │   ├── align-right-2x.png
│   │   │   │   ├── align-right.png
│   │   │   │   ├── arrows-2x.png
│   │   │   │   ├── arrows.png
│   │   │   │   ├── browser-rtl.png
│   │   │   │   ├── browser.png
│   │   │   │   ├── bubble_bg-2x.gif
│   │   │   │   ├── bubble_bg.gif
│   │   │   │   ├── comment-grey-bubble-2x.png
│   │   │   │   ├── comment-grey-bubble.png
│   │   │   │   ├── contribute-code.svg
│   │   │   │   ├── contribute-main.svg
│   │   │   │   ├── contribute-no-code.svg
│   │   │   │   ├── dashboard-background.svg
│   │   │   │   ├── date-button-2x.gif
│   │   │   │   ├── date-button.gif
│   │   │   │   ├── freedom-1.svg
│   │   │   │   ├── freedom-2.svg
│   │   │   │   ├── freedom-3.svg
│   │   │   │   ├── freedom-4.svg
│   │   │   │   ├── generic.png
│   │   │   │   ├── icons32-2x.png
│   │   │   │   ├── icons32-vs-2x.png
│   │   │   │   ├── icons32-vs.png
│   │   │   │   ├── icons32.png
│   │   │   │   ├── imgedit-icons-2x.png
│   │   │   │   ├── imgedit-icons.png
│   │   │   │   ├── list-2x.png
│   │   │   │   ├── list.png
│   │   │   │   ├── loading.gif
│   │   │   │   ├── marker.png
│   │   │   │   ├── mask.png
│   │   │   │   ├── media-button-2x.png
│   │   │   │   ├── media-button-image.gif
│   │   │   │   ├── media-button-music.gif
│   │   │   │   ├── media-button-other.gif
│   │   │   │   ├── media-button-video.gif
│   │   │   │   ├── media-button.png
│   │   │   │   ├── menu-2x.png
│   │   │   │   ├── menu-vs-2x.png
│   │   │   │   ├── menu-vs.png
│   │   │   │   ├── menu.png
│   │   │   │   ├── no.png
│   │   │   │   ├── post-formats-vs.png
│   │   │   │   ├── post-formats.png
│   │   │   │   ├── post-formats32-vs.png
│   │   │   │   ├── post-formats32.png
│   │   │   │   ├── privacy.svg
│   │   │   │   ├── resize-2x.gif
│   │   │   │   ├── resize-rtl-2x.gif
│   │   │   │   ├── resize-rtl.gif
│   │   │   │   ├── resize.gif
│   │   │   │   ├── se.png
│   │   │   │   ├── sort-2x.gif
│   │   │   │   ├── sort.gif
│   │   │   │   ├── spinner-2x.gif
│   │   │   │   ├── spinner.gif
│   │   │   │   ├── stars-2x.png
│   │   │   │   ├── stars.png
│   │   │   │   ├── w-logo-blue.png
│   │   │   │   ├── w-logo-white.png
│   │   │   │   ├── wheel.png
│   │   │   │   ├── wordpress-logo-white.svg
│   │   │   │   ├── wordpress-logo.png
│   │   │   │   ├── wordpress-logo.svg
│   │   │   │   ├── wpspin_light-2x.gif
│   │   │   │   ├── wpspin_light.gif
│   │   │   │   ├── xit-2x.gif
│   │   │   │   ├── xit.gif
│   │   │   │   └── yes.png
│   │   │   ├── includes/
│   │   │   │   ├── admin-filters.php
│   │   │   │   ├── admin.php
│   │   │   │   ├── ajax-actions.php
│   │   │   │   ├── bookmark.php
│   │   │   │   ├── class-automatic-upgrader-skin.php
│   │   │   │   ├── class-bulk-plugin-upgrader-skin.php
│   │   │   │   ├── class-bulk-theme-upgrader-skin.php
│   │   │   │   ├── class-bulk-upgrader-skin.php
│   │   │   │   ├── class-core-upgrader.php
│   │   │   │   ├── class-custom-background.php
│   │   │   │   ├── class-custom-image-header.php
│   │   │   │   ├── class-file-upload-upgrader.php
│   │   │   │   ├── class-ftp-pure.php
│   │   │   │   ├── class-ftp-sockets.php
│   │   │   │   ├── class-ftp.php
│   │   │   │   ├── class-language-pack-upgrader-skin.php
│   │   │   │   ├── class-language-pack-upgrader.php
│   │   │   │   ├── class-pclzip.php
│   │   │   │   ├── class-plugin-installer-skin.php
│   │   │   │   ├── class-plugin-upgrader-skin.php
│   │   │   │   ├── class-plugin-upgrader.php
│   │   │   │   ├── class-theme-installer-skin.php
│   │   │   │   ├── class-theme-upgrader-skin.php
│   │   │   │   ├── class-theme-upgrader.php
│   │   │   │   ├── class-walker-category-checklist.php
│   │   │   │   ├── class-walker-nav-menu-checklist.php
│   │   │   │   ├── class-walker-nav-menu-edit.php
│   │   │   │   ├── class-wp-ajax-upgrader-skin.php
│   │   │   │   ├── class-wp-application-passwords-list-table.php
│   │   │   │   ├── class-wp-automatic-updater.php
│   │   │   │   ├── class-wp-comments-list-table.php
│   │   │   │   ├── class-wp-community-events.php
│   │   │   │   ├── class-wp-debug-data.php
│   │   │   │   ├── class-wp-filesystem-base.php
│   │   │   │   ├── class-wp-filesystem-direct.php
│   │   │   │   ├── class-wp-filesystem-ftpext.php
│   │   │   │   ├── class-wp-filesystem-ftpsockets.php
│   │   │   │   ├── class-wp-filesystem-ssh2.php
│   │   │   │   ├── class-wp-importer.php
│   │   │   │   ├── class-wp-internal-pointers.php
│   │   │   │   ├── class-wp-links-list-table.php
│   │   │   │   ├── class-wp-list-table-compat.php
│   │   │   │   ├── class-wp-list-table.php
│   │   │   │   ├── class-wp-media-list-table.php
│   │   │   │   ├── class-wp-ms-sites-list-table.php
│   │   │   │   ├── class-wp-ms-themes-list-table.php
│   │   │   │   ├── class-wp-ms-users-list-table.php
│   │   │   │   ├── class-wp-plugin-install-list-table.php
│   │   │   │   ├── class-wp-plugins-list-table.php
│   │   │   │   ├── class-wp-post-comments-list-table.php
│   │   │   │   ├── class-wp-posts-list-table.php
│   │   │   │   ├── class-wp-privacy-data-export-requests-list-table.php
│   │   │   │   ├── class-wp-privacy-data-removal-requests-list-table.php
│   │   │   │   ├── class-wp-privacy-policy-content.php
│   │   │   │   ├── class-wp-privacy-requests-table.php
│   │   │   │   ├── class-wp-screen.php
│   │   │   │   ├── class-wp-site-health-auto-updates.php
│   │   │   │   ├── class-wp-site-health.php
│   │   │   │   ├── class-wp-site-icon.php
│   │   │   │   ├── class-wp-terms-list-table.php
│   │   │   │   ├── class-wp-theme-install-list-table.php
│   │   │   │   ├── class-wp-themes-list-table.php
│   │   │   │   ├── class-wp-upgrader-skin.php
│   │   │   │   ├── class-wp-upgrader-skins.php
│   │   │   │   ├── class-wp-upgrader.php
│   │   │   │   ├── class-wp-users-list-table.php
│   │   │   │   ├── comment.php
│   │   │   │   ├── continents-cities.php
│   │   │   │   ├── credits.php
│   │   │   │   ├── dashboard.php
│   │   │   │   ├── deprecated.php
│   │   │   │   ├── edit-tag-messages.php
│   │   │   │   ├── export.php
│   │   │   │   ├── file.php
│   │   │   │   ├── image-edit.php
│   │   │   │   ├── image.php
│   │   │   │   ├── import.php
│   │   │   │   ├── list-table.php
│   │   │   │   ├── media.php
│   │   │   │   ├── menu.php
│   │   │   │   ├── meta-boxes.php
│   │   │   │   ├── misc.php
│   │   │   │   ├── ms-admin-filters.php
│   │   │   │   ├── ms-deprecated.php
│   │   │   │   ├── ms.php
│   │   │   │   ├── nav-menu.php
│   │   │   │   ├── network.php
│   │   │   │   ├── noop.php
│   │   │   │   ├── options.php
│   │   │   │   ├── plugin-install.php
│   │   │   │   ├── plugin.php
│   │   │   │   ├── post.php
│   │   │   │   ├── privacy-tools.php
│   │   │   │   ├── revision.php
│   │   │   │   ├── schema.php
│   │   │   │   ├── screen.php
│   │   │   │   ├── taxonomy.php
│   │   │   │   ├── template.php
│   │   │   │   ├── theme-install.php
│   │   │   │   ├── theme.php
│   │   │   │   ├── translation-install.php
│   │   │   │   ├── update-core.php
│   │   │   │   ├── update.php
│   │   │   │   ├── upgrade.php
│   │   │   │   ├── user.php
│   │   │   │   └── widgets.php
│   │   │   ├── js/
│   │   │   │   ├── widgets/
│   │   │   │   │   ├── custom-html-widgets.js
│   │   │   │   │   ├── custom-html-widgets.min.js
│   │   │   │   │   ├── media-audio-widget.js
│   │   │   │   │   ├── media-audio-widget.min.js
│   │   │   │   │   ├── media-gallery-widget.js
│   │   │   │   │   ├── media-gallery-widget.min.js
│   │   │   │   │   ├── media-image-widget.js
│   │   │   │   │   ├── media-image-widget.min.js
│   │   │   │   │   ├── media-video-widget.js
│   │   │   │   │   ├── media-video-widget.min.js
│   │   │   │   │   ├── media-widgets.js
│   │   │   │   │   ├── media-widgets.min.js
│   │   │   │   │   ├── text-widgets.js
│   │   │   │   │   └── text-widgets.min.js
│   │   │   │   ├── accordion.js
│   │   │   │   ├── accordion.min.js
│   │   │   │   ├── application-passwords.js
│   │   │   │   ├── application-passwords.min.js
│   │   │   │   ├── auth-app.js
│   │   │   │   ├── auth-app.min.js
│   │   │   │   ├── code-editor.js
│   │   │   │   ├── code-editor.min.js
│   │   │   │   ├── color-picker.js
│   │   │   │   ├── color-picker.min.js
│   │   │   │   ├── comment.js
│   │   │   │   ├── comment.min.js
│   │   │   │   ├── common.js
│   │   │   │   ├── common.min.js
│   │   │   │   ├── custom-background.js
│   │   │   │   ├── custom-background.min.js
│   │   │   │   ├── custom-header.js
│   │   │   │   ├── customize-controls.js
│   │   │   │   ├── customize-controls.min.js
│   │   │   │   ├── customize-nav-menus.js
│   │   │   │   ├── customize-nav-menus.min.js
│   │   │   │   ├── customize-widgets.js
│   │   │   │   ├── customize-widgets.min.js
│   │   │   │   ├── dashboard.js
│   │   │   │   ├── dashboard.min.js
│   │   │   │   ├── edit-comments.js
│   │   │   │   ├── edit-comments.min.js
│   │   │   │   ├── editor-expand.js
│   │   │   │   ├── editor-expand.min.js
│   │   │   │   ├── editor.js
│   │   │   │   ├── editor.min.js
│   │   │   │   ├── farbtastic.js
│   │   │   │   ├── gallery.js
│   │   │   │   ├── gallery.min.js
│   │   │   │   ├── image-edit.js
│   │   │   │   ├── image-edit.min.js
│   │   │   │   ├── inline-edit-post.js
│   │   │   │   ├── inline-edit-post.min.js
│   │   │   │   ├── inline-edit-tax.js
│   │   │   │   ├── inline-edit-tax.min.js
│   │   │   │   ├── iris.min.js
│   │   │   │   ├── language-chooser.js
│   │   │   │   ├── language-chooser.min.js
│   │   │   │   ├── link.js
│   │   │   │   ├── link.min.js
│   │   │   │   ├── media-gallery.js
│   │   │   │   ├── media-gallery.min.js
│   │   │   │   ├── media-upload.js
│   │   │   │   ├── media-upload.min.js
│   │   │   │   ├── media.js
│   │   │   │   ├── media.min.js
│   │   │   │   ├── nav-menu.js
│   │   │   │   ├── nav-menu.min.js
│   │   │   │   ├── password-strength-meter.js
│   │   │   │   ├── password-strength-meter.min.js
│   │   │   │   ├── password-toggle.js
│   │   │   │   ├── password-toggle.min.js
│   │   │   │   ├── plugin-install.js
│   │   │   │   ├── plugin-install.min.js
│   │   │   │   ├── post.js
│   │   │   │   ├── post.min.js
│   │   │   │   ├── postbox.js
│   │   │   │   ├── postbox.min.js
│   │   │   │   ├── privacy-tools.js
│   │   │   │   ├── privacy-tools.min.js
│   │   │   │   ├── revisions.js
│   │   │   │   ├── revisions.min.js
│   │   │   │   ├── set-post-thumbnail.js
│   │   │   │   ├── set-post-thumbnail.min.js
│   │   │   │   ├── site-health.js
│   │   │   │   ├── site-health.min.js
│   │   │   │   ├── site-icon.js
│   │   │   │   ├── site-icon.min.js
│   │   │   │   ├── svg-painter.js
│   │   │   │   ├── svg-painter.min.js
│   │   │   │   ├── tags-box.js
│   │   │   │   ├── tags-box.min.js
│   │   │   │   ├── tags-suggest.js
│   │   │   │   ├── tags-suggest.min.js
│   │   │   │   ├── tags.js
│   │   │   │   ├── tags.min.js
│   │   │   │   ├── theme-plugin-editor.js
│   │   │   │   ├── theme-plugin-editor.min.js
│   │   │   │   ├── theme.js
│   │   │   │   ├── theme.min.js
│   │   │   │   ├── updates.js
│   │   │   │   ├── updates.min.js
│   │   │   │   ├── user-profile.js
│   │   │   │   ├── user-profile.min.js
│   │   │   │   ├── user-suggest.js
│   │   │   │   ├── user-suggest.min.js
│   │   │   │   ├── widgets.js
│   │   │   │   ├── widgets.min.js
│   │   │   │   ├── word-count.js
│   │   │   │   ├── word-count.min.js
│   │   │   │   ├── xfn.js
│   │   │   │   └── xfn.min.js
│   │   │   ├── maint/
│   │   │   │   └── repair.php
│   │   │   ├── network/
│   │   │   │   ├── about.php
│   │   │   │   ├── admin.php
│   │   │   │   ├── contribute.php
│   │   │   │   ├── credits.php
│   │   │   │   ├── edit.php
│   │   │   │   ├── freedoms.php
│   │   │   │   ├── index.php
│   │   │   │   ├── menu.php
│   │   │   │   ├── plugin-editor.php
│   │   │   │   ├── plugin-install.php
│   │   │   │   ├── plugins.php
│   │   │   │   ├── privacy.php
│   │   │   │   ├── profile.php
│   │   │   │   ├── settings.php
│   │   │   │   ├── setup.php
│   │   │   │   ├── site-info.php
│   │   │   │   ├── site-new.php
│   │   │   │   ├── site-settings.php
│   │   │   │   ├── site-themes.php
│   │   │   │   ├── site-users.php
│   │   │   │   ├── sites.php
│   │   │   │   ├── theme-editor.php
│   │   │   │   ├── theme-install.php
│   │   │   │   ├── themes.php
│   │   │   │   ├── update-core.php
│   │   │   │   ├── update.php
│   │   │   │   ├── upgrade.php
│   │   │   │   ├── user-edit.php
│   │   │   │   ├── user-new.php
│   │   │   │   └── users.php
│   │   │   ├── user/
│   │   │   │   ├── about.php
│   │   │   │   ├── admin.php
│   │   │   │   ├── contribute.php
│   │   │   │   ├── credits.php
│   │   │   │   ├── freedoms.php
│   │   │   │   ├── index.php
│   │   │   │   ├── menu.php
│   │   │   │   ├── privacy.php
│   │   │   │   ├── profile.php
│   │   │   │   └── user-edit.php
│   │   │   ├── about.php
│   │   │   ├── admin-ajax.php
│   │   │   ├── admin-footer.php
│   │   │   ├── admin-functions.php
│   │   │   ├── admin-header.php
│   │   │   ├── admin-post.php
│   │   │   ├── admin.php
│   │   │   ├── async-upload.php
│   │   │   ├── authorize-application.php
│   │   │   ├── comment.php
│   │   │   ├── contribute.php
│   │   │   ├── credits.php
│   │   │   ├── custom-background.php
│   │   │   ├── custom-header.php
│   │   │   ├── customize.php
│   │   │   ├── edit-comments.php
│   │   │   ├── edit-form-advanced.php
│   │   │   ├── edit-form-blocks.php
│   │   │   ├── edit-form-comment.php
│   │   │   ├── edit-link-form.php
│   │   │   ├── edit-tag-form.php
│   │   │   ├── edit-tags.php
│   │   │   ├── edit.php
│   │   │   ├── erase-personal-data.php
│   │   │   ├── export-personal-data.php
│   │   │   ├── export.php
│   │   │   ├── freedoms.php
│   │   │   ├── import.php
│   │   │   ├── index.php
│   │   │   ├── install-helper.php
│   │   │   ├── install.php
│   │   │   ├── link-add.php
│   │   │   ├── link-manager.php
│   │   │   ├── link-parse-opml.php
│   │   │   ├── link.php
│   │   │   ├── load-scripts.php
│   │   │   ├── load-styles.php
│   │   │   ├── media-new.php
│   │   │   ├── media-upload.php
│   │   │   ├── media.php
│   │   │   ├── menu-header.php
│   │   │   ├── menu.php
│   │   │   ├── moderation.php
│   │   │   ├── ms-admin.php
│   │   │   ├── ms-delete-site.php
│   │   │   ├── ms-edit.php
│   │   │   ├── ms-options.php
│   │   │   ├── ms-sites.php
│   │   │   ├── ms-themes.php
│   │   │   ├── ms-upgrade-network.php
│   │   │   ├── ms-users.php
│   │   │   ├── my-sites.php
│   │   │   ├── nav-menus.php
│   │   │   ├── network.php
│   │   │   ├── options-discussion.php
│   │   │   ├── options-general.php
│   │   │   ├── options-head.php
│   │   │   ├── options-media.php
│   │   │   ├── options-permalink.php
│   │   │   ├── options-privacy.php
│   │   │   ├── options-reading.php
│   │   │   ├── options-writing.php
│   │   │   ├── options.php
│   │   │   ├── plugin-editor.php
│   │   │   ├── plugin-install.php
│   │   │   ├── plugins.php
│   │   │   ├── post-new.php
│   │   │   ├── post.php
│   │   │   ├── press-this.php
│   │   │   ├── privacy-policy-guide.php
│   │   │   ├── privacy.php
│   │   │   ├── profile.php
│   │   │   ├── revision.php
│   │   │   ├── setup-config.php
│   │   │   ├── site-editor.php
│   │   │   ├── site-health-info.php
│   │   │   ├── site-health.php
│   │   │   ├── term.php
│   │   │   ├── theme-editor.php
│   │   │   ├── theme-install.php
│   │   │   ├── themes.php
│   │   │   ├── tools.php
│   │   │   ├── update-core.php
│   │   │   ├── update.php
│   │   │   ├── upgrade-functions.php
│   │   │   ├── upgrade.php
│   │   │   ├── upload.php
│   │   │   ├── user-edit.php
│   │   │   ├── user-new.php
│   │   │   ├── users.php
│   │   │   ├── widgets-form-blocks.php
│   │   │   ├── widgets-form.php
│   │   │   └── widgets.php
│   │   ├── wp-content/
│   │   │   ├── plugins/
│   │   │   │   ├── akismet/
│   │   │   │   │   ├── _inc/
│   │   │   │   │   │   ├── fonts/
│   │   │   │   │   │   │   └── inter.css
│   │   │   │   │   │   ├── img/
│   │   │   │   │   │   │   ├── akismet-activation-banner-elements.png
│   │   │   │   │   │   │   ├── akismet-refresh-logo.svg
│   │   │   │   │   │   │   ├── akismet-refresh-logo@2x.png
│   │   │   │   │   │   │   ├── arrow-left.svg
│   │   │   │   │   │   │   └── logo-a-2x.png
│   │   │   │   │   │   ├── rtl/
│   │   │   │   │   │   │   ├── akismet-admin-rtl.css
│   │   │   │   │   │   │   └── akismet-rtl.css
│   │   │   │   │   │   ├── akismet-admin.css
│   │   │   │   │   │   ├── akismet-admin.js
│   │   │   │   │   │   ├── akismet-frontend.js
│   │   │   │   │   │   ├── akismet.css
│   │   │   │   │   │   └── akismet.js
│   │   │   │   │   ├── views/
│   │   │   │   │   │   ├── activate.php
│   │   │   │   │   │   ├── compatible-plugins.php
│   │   │   │   │   │   ├── config.php
│   │   │   │   │   │   ├── connect-jp.php
│   │   │   │   │   │   ├── enter.php
│   │   │   │   │   │   ├── get.php
│   │   │   │   │   │   ├── logo.php
│   │   │   │   │   │   ├── notice.php
│   │   │   │   │   │   ├── predefined.php
│   │   │   │   │   │   ├── setup-jetpack.php
│   │   │   │   │   │   ├── setup.php
│   │   │   │   │   │   ├── start.php
│   │   │   │   │   │   └── stats.php
│   │   │   │   │   ├── .htaccess
│   │   │   │   │   ├── akismet.php
│   │   │   │   │   ├── changelog.txt
│   │   │   │   │   ├── class-akismet-compatible-plugins.php
│   │   │   │   │   ├── class.akismet-admin.php
│   │   │   │   │   ├── class.akismet-cli.php
│   │   │   │   │   ├── class.akismet-rest-api.php
│   │   │   │   │   ├── class.akismet-widget.php
│   │   │   │   │   ├── class.akismet.php
│   │   │   │   │   ├── index.php
│   │   │   │   │   ├── LICENSE.txt
│   │   │   │   │   ├── readme.txt
│   │   │   │   │   └── wrapper.php
│   │   │   │   ├── hello.php
│   │   │   │   └── index.php
│   │   │   ├── themes/
│   │   │   │   ├── twentytwentyfive/
│   │   │   │   │   ├── assets/
│   │   │   │   │   │   ├── css/
│   │   │   │   │   │   │   └── editor-style.css
│   │   │   │   │   │   ├── fonts/
│   │   │   │   │   │   │   ├── beiruti/
│   │   │   │   │   │   │   │   └── Beiruti-VariableFont_wght.woff2
│   │   │   │   │   │   │   ├── fira-code/
│   │   │   │   │   │   │   │   └── FiraCode-VariableFont_wght.woff2
│   │   │   │   │   │   │   ├── fira-sans/
│   │   │   │   │   │   │   │   ├── FiraSans-Black.woff2
│   │   │   │   │   │   │   │   ├── FiraSans-BlackItalic.woff2
│   │   │   │   │   │   │   │   ├── FiraSans-Bold.woff2
│   │   │   │   │   │   │   │   ├── FiraSans-BoldItalic.woff2
│   │   │   │   │   │   │   │   ├── FiraSans-ExtraBold.woff2
│   │   │   │   │   │   │   │   ├── FiraSans-ExtraBoldItalic.woff2
│   │   │   │   │   │   │   │   ├── FiraSans-ExtraLight.woff2
│   │   │   │   │   │   │   │   ├── FiraSans-ExtraLightItalic.woff2
│   │   │   │   │   │   │   │   ├── FiraSans-Italic.woff2
│   │   │   │   │   │   │   │   ├── FiraSans-Light.woff2
│   │   │   │   │   │   │   │   ├── FiraSans-LightItalic.woff2
│   │   │   │   │   │   │   │   ├── FiraSans-Medium.woff2
│   │   │   │   │   │   │   │   ├── FiraSans-MediumItalic.woff2
│   │   │   │   │   │   │   │   ├── FiraSans-Regular.woff2
│   │   │   │   │   │   │   │   ├── FiraSans-SemiBold.woff2
│   │   │   │   │   │   │   │   ├── FiraSans-SemiBoldItalic.woff2
│   │   │   │   │   │   │   │   ├── FiraSans-Thin.woff2
│   │   │   │   │   │   │   │   └── FiraSans-ThinItalic.woff2
│   │   │   │   │   │   │   ├── literata/
│   │   │   │   │   │   │   │   ├── Literata72pt-Black.woff2
│   │   │   │   │   │   │   │   ├── Literata72pt-BlackItalic.woff2
│   │   │   │   │   │   │   │   ├── Literata72pt-Bold.woff2
│   │   │   │   │   │   │   │   ├── Literata72pt-BoldItalic.woff2
│   │   │   │   │   │   │   │   ├── Literata72pt-ExtraBold.woff2
│   │   │   │   │   │   │   │   ├── Literata72pt-ExtraBoldItalic.woff2
│   │   │   │   │   │   │   │   ├── Literata72pt-ExtraLight.woff2
│   │   │   │   │   │   │   │   ├── Literata72pt-ExtraLightItalic.woff2
│   │   │   │   │   │   │   │   ├── Literata72pt-Light.woff2
│   │   │   │   │   │   │   │   ├── Literata72pt-LightItalic.woff2
│   │   │   │   │   │   │   │   ├── Literata72pt-Medium.woff2
│   │   │   │   │   │   │   │   ├── Literata72pt-MediumItalic.woff2
│   │   │   │   │   │   │   │   ├── Literata72pt-Regular.woff2
│   │   │   │   │   │   │   │   ├── Literata72pt-RegularItalic.woff2
│   │   │   │   │   │   │   │   ├── Literata72pt-SemiBold.woff2
│   │   │   │   │   │   │   │   └── Literata72pt-SemiBoldItalic.woff2
│   │   │   │   │   │   │   ├── manrope/
│   │   │   │   │   │   │   │   └── Manrope-VariableFont_wght.woff2
│   │   │   │   │   │   │   ├── platypi/
│   │   │   │   │   │   │   │   ├── Platypi-Italic-VariableFont_wght.woff2
│   │   │   │   │   │   │   │   └── Platypi-VariableFont_wght.woff2
│   │   │   │   │   │   │   ├── roboto-slab/
│   │   │   │   │   │   │   │   └── RobotoSlab-VariableFont_wght.woff2
│   │   │   │   │   │   │   ├── vollkorn/
│   │   │   │   │   │   │   │   ├── Vollkorn-Italic-VariableFont_wght.woff2
│   │   │   │   │   │   │   │   └── Vollkorn-VariableFont_wght.woff2
│   │   │   │   │   │   │   └── ysabeau-office/
│   │   │   │   │   │   │       ├── YsabeauOffice-Italic-VariableFont_wght.woff2
│   │   │   │   │   │   │       └── YsabeauOffice-VariableFont_wght.woff2
│   │   │   │   │   │   └── images/
│   │   │   │   │   │       ├── 404-image.webp
│   │   │   │   │   │       ├── agenda-img-4.webp
│   │   │   │   │   │       ├── akaka-falls-state-park-flora.webp
│   │   │   │   │   │       ├── book-image-landing.webp
│   │   │   │   │   │       ├── book-image.webp
│   │   │   │   │   │       ├── botany-flowers-closeup.webp
│   │   │   │   │   │       ├── botany-flowers.webp
│   │   │   │   │   │       ├── campanula-alliariifolia-flower.webp
│   │   │   │   │   │       ├── category-anthuriums.webp
│   │   │   │   │   │       ├── category-cactus.webp
│   │   │   │   │   │       ├── category-sunflowers.webp
│   │   │   │   │   │       ├── coming-soon-bg-image.webp
│   │   │   │   │   │       ├── coral-square.webp
│   │   │   │   │   │       ├── dallas-creek-square.webp
│   │   │   │   │   │       ├── delphinium-flowers.webp
│   │   │   │   │   │       ├── flower-meadow-square.webp
│   │   │   │   │   │       ├── grid-flower-1.webp
│   │   │   │   │   │       ├── grid-flower-2.webp
│   │   │   │   │   │       ├── hero-podcast.webp
│   │   │   │   │   │       ├── link-in-bio-background.webp
│   │   │   │   │   │       ├── link-in-bio-image.webp
│   │   │   │   │   │       ├── location.webp
│   │   │   │   │   │       ├── malibu-plantlife.webp
│   │   │   │   │   │       ├── man-in-hat.webp
│   │   │   │   │   │       ├── marshland-birds-square.webp
│   │   │   │   │   │       ├── northern-buttercups-flowers.webp
│   │   │   │   │   │       ├── nurse.webp
│   │   │   │   │   │       ├── parthenon-square.webp
│   │   │   │   │   │       ├── poster-image-background.webp
│   │   │   │   │   │       ├── red-hibiscus-closeup.webp
│   │   │   │   │   │       ├── ruins-image.webp
│   │   │   │   │   │       ├── services-subscriber-photo.webp
│   │   │   │   │   │       ├── star-thristle-flower.webp
│   │   │   │   │   │       ├── typewriter.webp
│   │   │   │   │   │       ├── vash-gon-square.webp
│   │   │   │   │   │       └── woman-splashing-water.webp
│   │   │   │   │   ├── parts/
│   │   │   │   │   │   ├── footer-columns.html
│   │   │   │   │   │   ├── footer-newsletter.html
│   │   │   │   │   │   ├── footer.html
│   │   │   │   │   │   ├── header-large-title.html
│   │   │   │   │   │   ├── header.html
│   │   │   │   │   │   ├── sidebar.html
│   │   │   │   │   │   └── vertical-header.html
│   │   │   │   │   ├── patterns/
│   │   │   │   │   │   ├── banner-about-book.php
│   │   │   │   │   │   ├── banner-cover-big-heading.php
│   │   │   │   │   │   ├── banner-intro-image.php
│   │   │   │   │   │   ├── banner-intro.php
│   │   │   │   │   │   ├── banner-poster.php
│   │   │   │   │   │   ├── banner-with-description-and-images-grid.php
│   │   │   │   │   │   ├── binding-format.php
│   │   │   │   │   │   ├── comments.php
│   │   │   │   │   │   ├── contact-centered-social-link.php
│   │   │   │   │   │   ├── contact-info-locations.php
│   │   │   │   │   │   ├── contact-location-and-link.php
│   │   │   │   │   │   ├── cta-book-links.php
│   │   │   │   │   │   ├── cta-book-locations.php
│   │   │   │   │   │   ├── cta-centered-heading.php
│   │   │   │   │   │   ├── cta-events-list.php
│   │   │   │   │   │   ├── cta-grid-products-link.php
│   │   │   │   │   │   ├── cta-heading-search.php
│   │   │   │   │   │   ├── cta-newsletter.php
│   │   │   │   │   │   ├── event-3-col.php
│   │   │   │   │   │   ├── event-rsvp.php
│   │   │   │   │   │   ├── event-schedule.php
│   │   │   │   │   │   ├── footer-centered.php
│   │   │   │   │   │   ├── footer-columns.php
│   │   │   │   │   │   ├── footer-newsletter.php
│   │   │   │   │   │   ├── footer-social.php
│   │   │   │   │   │   ├── footer.php
│   │   │   │   │   │   ├── format-audio.php
│   │   │   │   │   │   ├── format-link.php
│   │   │   │   │   │   ├── grid-videos.php
│   │   │   │   │   │   ├── grid-with-categories.php
│   │   │   │   │   │   ├── header-centered.php
│   │   │   │   │   │   ├── header-columns.php
│   │   │   │   │   │   ├── header-large-title.php
│   │   │   │   │   │   ├── header.php
│   │   │   │   │   │   ├── heading-and-paragraph-with-image.php
│   │   │   │   │   │   ├── hero-book.php
│   │   │   │   │   │   ├── hero-full-width-image.php
│   │   │   │   │   │   ├── hero-overlapped-book-cover-with-links.php
│   │   │   │   │   │   ├── hero-podcast.php
│   │   │   │   │   │   ├── hidden-404.php
│   │   │   │   │   │   ├── hidden-blog-heading.php
│   │   │   │   │   │   ├── hidden-search.php
│   │   │   │   │   │   ├── hidden-sidebar.php
│   │   │   │   │   │   ├── hidden-written-by.php
│   │   │   │   │   │   ├── logos.php
│   │   │   │   │   │   ├── media-instagram-grid.php
│   │   │   │   │   │   ├── more-posts.php
│   │   │   │   │   │   ├── overlapped-images.php
│   │   │   │   │   │   ├── page-business-home.php
│   │   │   │   │   │   ├── page-coming-soon.php
│   │   │   │   │   │   ├── page-cv-bio.php
│   │   │   │   │   │   ├── page-landing-book.php
│   │   │   │   │   │   ├── page-landing-event.php
│   │   │   │   │   │   ├── page-landing-podcast.php
│   │   │   │   │   │   ├── page-link-in-bio-heading-paragraph-links-image.php
│   │   │   │   │   │   ├── page-link-in-bio-wide-margins.php
│   │   │   │   │   │   ├── page-link-in-bio-with-tight-margins.php
│   │   │   │   │   │   ├── page-portfolio-home.php
│   │   │   │   │   │   ├── page-shop-home.php
│   │   │   │   │   │   ├── post-navigation.php
│   │   │   │   │   │   ├── pricing-2-col.php
│   │   │   │   │   │   ├── pricing-3-col.php
│   │   │   │   │   │   ├── services-3-col.php
│   │   │   │   │   │   ├── services-subscriber-only-section.php
│   │   │   │   │   │   ├── services-team-photos.php
│   │   │   │   │   │   ├── template-404-vertical-header-blog.php
│   │   │   │   │   │   ├── template-archive-news-blog.php
│   │   │   │   │   │   ├── template-archive-photo-blog.php
│   │   │   │   │   │   ├── template-archive-text-blog.php
│   │   │   │   │   │   ├── template-archive-vertical-header-blog.php
│   │   │   │   │   │   ├── template-home-news-blog.php
│   │   │   │   │   │   ├── template-home-photo-blog.php
│   │   │   │   │   │   ├── template-home-posts-grid-news-blog.php
│   │   │   │   │   │   ├── template-home-text-blog.php
│   │   │   │   │   │   ├── template-home-vertical-header-blog.php
│   │   │   │   │   │   ├── template-home-with-sidebar-news-blog.php
│   │   │   │   │   │   ├── template-page-photo-blog.php
│   │   │   │   │   │   ├── template-page-vertical-header-blog.php
│   │   │   │   │   │   ├── template-query-loop-news-blog.php
│   │   │   │   │   │   ├── template-query-loop-photo-blog.php
│   │   │   │   │   │   ├── template-query-loop-text-blog.php
│   │   │   │   │   │   ├── template-query-loop-vertical-header-blog.php
│   │   │   │   │   │   ├── template-query-loop.php
│   │   │   │   │   │   ├── template-search-news-blog.php
│   │   │   │   │   │   ├── template-search-photo-blog.php
│   │   │   │   │   │   ├── template-search-text-blog.php
│   │   │   │   │   │   ├── template-search-vertical-header-blog.php
│   │   │   │   │   │   ├── template-single-left-aligned-content.php
│   │   │   │   │   │   ├── template-single-news-blog.php
│   │   │   │   │   │   ├── template-single-offset.php
│   │   │   │   │   │   ├── template-single-photo-blog.php
│   │   │   │   │   │   ├── template-single-text-blog.php
│   │   │   │   │   │   ├── template-single-vertical-header-blog.php
│   │   │   │   │   │   ├── testimonials-2-col.php
│   │   │   │   │   │   ├── testimonials-6-col.php
│   │   │   │   │   │   ├── testimonials-large.php
│   │   │   │   │   │   ├── text-faqs.php
│   │   │   │   │   │   └── vertical-header.php
│   │   │   │   │   ├── styles/
│   │   │   │   │   │   ├── blocks/
│   │   │   │   │   │   │   ├── 01-display.json
│   │   │   │   │   │   │   ├── 02-subtitle.json
│   │   │   │   │   │   │   ├── 03-annotation.json
│   │   │   │   │   │   │   └── post-terms-1.json
│   │   │   │   │   │   ├── colors/
│   │   │   │   │   │   │   ├── 01-evening.json
│   │   │   │   │   │   │   ├── 02-noon.json
│   │   │   │   │   │   │   ├── 03-dusk.json
│   │   │   │   │   │   │   ├── 04-afternoon.json
│   │   │   │   │   │   │   ├── 05-twilight.json
│   │   │   │   │   │   │   ├── 06-morning.json
│   │   │   │   │   │   │   ├── 07-sunrise.json
│   │   │   │   │   │   │   └── 08-midnight.json
│   │   │   │   │   │   ├── sections/
│   │   │   │   │   │   │   ├── section-1.json
│   │   │   │   │   │   │   ├── section-2.json
│   │   │   │   │   │   │   ├── section-3.json
│   │   │   │   │   │   │   ├── section-4.json
│   │   │   │   │   │   │   └── section-5.json
│   │   │   │   │   │   ├── typography/
│   │   │   │   │   │   │   ├── typography-preset-1.json
│   │   │   │   │   │   │   ├── typography-preset-2.json
│   │   │   │   │   │   │   ├── typography-preset-3.json
│   │   │   │   │   │   │   ├── typography-preset-4.json
│   │   │   │   │   │   │   ├── typography-preset-5.json
│   │   │   │   │   │   │   ├── typography-preset-6.json
│   │   │   │   │   │   │   └── typography-preset-7.json
│   │   │   │   │   │   ├── 01-evening.json
│   │   │   │   │   │   ├── 02-noon.json
│   │   │   │   │   │   ├── 03-dusk.json
│   │   │   │   │   │   ├── 04-afternoon.json
│   │   │   │   │   │   ├── 05-twilight.json
│   │   │   │   │   │   ├── 06-morning.json
│   │   │   │   │   │   ├── 07-sunrise.json
│   │   │   │   │   │   └── 08-midnight.json
│   │   │   │   │   ├── templates/
│   │   │   │   │   │   ├── 404.html
│   │   │   │   │   │   ├── archive.html
│   │   │   │   │   │   ├── home.html
│   │   │   │   │   │   ├── index.html
│   │   │   │   │   │   ├── page-no-title.html
│   │   │   │   │   │   ├── page.html
│   │   │   │   │   │   ├── search.html
│   │   │   │   │   │   └── single.html
│   │   │   │   │   ├── contributing.txt
│   │   │   │   │   ├── functions.php
│   │   │   │   │   ├── package-lock.json
│   │   │   │   │   ├── package.json
│   │   │   │   │   ├── readme.txt
│   │   │   │   │   ├── screenshot.png
│   │   │   │   │   ├── style.css
│   │   │   │   │   ├── style.min.css
│   │   │   │   │   └── theme.json
│   │   │   │   ├── twentytwentyfour/
│   │   │   │   │   ├── assets/
│   │   │   │   │   │   ├── css/
│   │   │   │   │   │   │   └── button-outline.css
│   │   │   │   │   │   ├── fonts/
│   │   │   │   │   │   │   ├── cardo/
│   │   │   │   │   │   │   │   ├── cardo_italic_400.woff2
│   │   │   │   │   │   │   │   ├── cardo_normal_400.woff2
│   │   │   │   │   │   │   │   ├── cardo_normal_700.woff2
│   │   │   │   │   │   │   │   └── LICENSE.txt
│   │   │   │   │   │   │   ├── instrument-sans/
│   │   │   │   │   │   │   │   ├── InstrumentSans-Italic-VariableFont_wdth,wght.woff2
│   │   │   │   │   │   │   │   ├── InstrumentSans-VariableFont_wdth,wght.woff2
│   │   │   │   │   │   │   │   └── OFL.txt
│   │   │   │   │   │   │   ├── inter/
│   │   │   │   │   │   │   │   ├── Inter-VariableFont_slnt,wght.woff2
│   │   │   │   │   │   │   │   └── LICENSE.txt
│   │   │   │   │   │   │   └── jost/
│   │   │   │   │   │   │       ├── Jost-Italic-VariableFont_wght.woff2
│   │   │   │   │   │   │       ├── Jost-VariableFont_wght.woff2
│   │   │   │   │   │   │       └── OFL.txt
│   │   │   │   │   │   └── images/
│   │   │   │   │   │       ├── abstract-geometric-art.webp
│   │   │   │   │   │       ├── angular-roof.webp
│   │   │   │   │   │       ├── art-gallery.webp
│   │   │   │   │   │       ├── building-exterior.webp
│   │   │   │   │   │       ├── green-staircase.webp
│   │   │   │   │   │       ├── hotel-facade.webp
│   │   │   │   │   │       ├── icon-message.webp
│   │   │   │   │   │       ├── museum.webp
│   │   │   │   │   │       ├── tourist-and-building.webp
│   │   │   │   │   │       └── windows.webp
│   │   │   │   │   ├── parts/
│   │   │   │   │   │   ├── footer.html
│   │   │   │   │   │   ├── header.html
│   │   │   │   │   │   ├── post-meta.html
│   │   │   │   │   │   └── sidebar.html
│   │   │   │   │   ├── patterns/
│   │   │   │   │   │   ├── banner-hero.php
│   │   │   │   │   │   ├── banner-project-description.php
│   │   │   │   │   │   ├── cta-content-image-on-right.php
│   │   │   │   │   │   ├── cta-pricing.php
│   │   │   │   │   │   ├── cta-rsvp.php
│   │   │   │   │   │   ├── cta-services-image-left.php
│   │   │   │   │   │   ├── cta-subscribe-centered.php
│   │   │   │   │   │   ├── footer-centered-logo-nav.php
│   │   │   │   │   │   ├── footer-colophon-3-col.php
│   │   │   │   │   │   ├── footer.php
│   │   │   │   │   │   ├── gallery-full-screen-image.php
│   │   │   │   │   │   ├── gallery-offset-images-grid-2-col.php
│   │   │   │   │   │   ├── gallery-offset-images-grid-3-col.php
│   │   │   │   │   │   ├── gallery-offset-images-grid-4-col.php
│   │   │   │   │   │   ├── gallery-project-layout.php
│   │   │   │   │   │   ├── hidden-404.php
│   │   │   │   │   │   ├── hidden-comments.php
│   │   │   │   │   │   ├── hidden-no-results.php
│   │   │   │   │   │   ├── hidden-portfolio-hero.php
│   │   │   │   │   │   ├── hidden-post-meta.php
│   │   │   │   │   │   ├── hidden-post-navigation.php
│   │   │   │   │   │   ├── hidden-posts-heading.php
│   │   │   │   │   │   ├── hidden-search.php
│   │   │   │   │   │   ├── hidden-sidebar.php
│   │   │   │   │   │   ├── page-about-business.php
│   │   │   │   │   │   ├── page-home-blogging.php
│   │   │   │   │   │   ├── page-home-business.php
│   │   │   │   │   │   ├── page-home-portfolio-gallery.php
│   │   │   │   │   │   ├── page-home-portfolio.php
│   │   │   │   │   │   ├── page-newsletter-landing.php
│   │   │   │   │   │   ├── page-portfolio-overview.php
│   │   │   │   │   │   ├── page-rsvp-landing.php
│   │   │   │   │   │   ├── posts-1-col.php
│   │   │   │   │   │   ├── posts-3-col.php
│   │   │   │   │   │   ├── posts-grid-2-col.php
│   │   │   │   │   │   ├── posts-images-only-3-col.php
│   │   │   │   │   │   ├── posts-images-only-offset-4-col.php
│   │   │   │   │   │   ├── posts-list.php
│   │   │   │   │   │   ├── team-4-col.php
│   │   │   │   │   │   ├── template-archive-blogging.php
│   │   │   │   │   │   ├── template-archive-portfolio.php
│   │   │   │   │   │   ├── template-home-blogging.php
│   │   │   │   │   │   ├── template-home-business.php
│   │   │   │   │   │   ├── template-home-portfolio.php
│   │   │   │   │   │   ├── template-index-blogging.php
│   │   │   │   │   │   ├── template-index-portfolio.php
│   │   │   │   │   │   ├── template-search-blogging.php
│   │   │   │   │   │   ├── template-search-portfolio.php
│   │   │   │   │   │   ├── template-single-portfolio.php
│   │   │   │   │   │   ├── testimonial-centered.php
│   │   │   │   │   │   ├── text-alternating-images.php
│   │   │   │   │   │   ├── text-centered-statement-small.php
│   │   │   │   │   │   ├── text-centered-statement.php
│   │   │   │   │   │   ├── text-faq.php
│   │   │   │   │   │   ├── text-feature-grid-3-col.php
│   │   │   │   │   │   ├── text-project-details.php
│   │   │   │   │   │   └── text-title-left-image-right.php
│   │   │   │   │   ├── styles/
│   │   │   │   │   │   ├── ember.json
│   │   │   │   │   │   ├── fossil.json
│   │   │   │   │   │   ├── ice.json
│   │   │   │   │   │   ├── maelstrom.json
│   │   │   │   │   │   ├── mint.json
│   │   │   │   │   │   ├── onyx.json
│   │   │   │   │   │   └── rust.json
│   │   │   │   │   ├── templates/
│   │   │   │   │   │   ├── 404.html
│   │   │   │   │   │   ├── archive.html
│   │   │   │   │   │   ├── home.html
│   │   │   │   │   │   ├── index.html
│   │   │   │   │   │   ├── page-no-title.html
│   │   │   │   │   │   ├── page-wide.html
│   │   │   │   │   │   ├── page-with-sidebar.html
│   │   │   │   │   │   ├── page.html
│   │   │   │   │   │   ├── search.html
│   │   │   │   │   │   ├── single-with-sidebar.html
│   │   │   │   │   │   └── single.html
│   │   │   │   │   ├── functions.php
│   │   │   │   │   ├── readme.txt
│   │   │   │   │   ├── screenshot.png
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── theme.json
│   │   │   │   ├── twentytwentythree/
│   │   │   │   │   ├── assets/
│   │   │   │   │   │   └── fonts/
│   │   │   │   │   │       ├── dm-sans/
│   │   │   │   │   │       │   ├── DMSans-Bold-Italic.woff2
│   │   │   │   │   │       │   ├── DMSans-Bold.woff2
│   │   │   │   │   │       │   ├── DMSans-Regular-Italic.woff2
│   │   │   │   │   │       │   ├── DMSans-Regular.woff2
│   │   │   │   │   │       │   └── LICENSE.txt
│   │   │   │   │   │       ├── ibm-plex-mono/
│   │   │   │   │   │       │   ├── IBMPlexMono-Bold.woff2
│   │   │   │   │   │       │   ├── IBMPlexMono-Italic.woff2
│   │   │   │   │   │       │   ├── IBMPlexMono-Light.woff2
│   │   │   │   │   │       │   ├── IBMPlexMono-Regular.woff2
│   │   │   │   │   │       │   └── OFL.txt
│   │   │   │   │   │       ├── inter/
│   │   │   │   │   │       │   ├── Inter-VariableFont_slnt,wght.ttf
│   │   │   │   │   │       │   └── LICENSE.txt
│   │   │   │   │   │       └── source-serif-pro/
│   │   │   │   │   │           ├── LICENSE.md
│   │   │   │   │   │           ├── SourceSerif4Variable-Italic.otf.woff2
│   │   │   │   │   │           ├── SourceSerif4Variable-Italic.ttf.woff2
│   │   │   │   │   │           ├── SourceSerif4Variable-Roman.otf.woff2
│   │   │   │   │   │           └── SourceSerif4Variable-Roman.ttf.woff2
│   │   │   │   │   ├── parts/
│   │   │   │   │   │   ├── comments.html
│   │   │   │   │   │   ├── footer.html
│   │   │   │   │   │   ├── header.html
│   │   │   │   │   │   └── post-meta.html
│   │   │   │   │   ├── patterns/
│   │   │   │   │   │   ├── call-to-action.php
│   │   │   │   │   │   ├── footer-default.php
│   │   │   │   │   │   ├── hidden-404.php
│   │   │   │   │   │   ├── hidden-comments.php
│   │   │   │   │   │   ├── hidden-heading.php
│   │   │   │   │   │   ├── hidden-no-results.php
│   │   │   │   │   │   └── post-meta.php
│   │   │   │   │   ├── styles/
│   │   │   │   │   │   ├── aubergine.json
│   │   │   │   │   │   ├── block-out.json
│   │   │   │   │   │   ├── canary.json
│   │   │   │   │   │   ├── electric.json
│   │   │   │   │   │   ├── grapes.json
│   │   │   │   │   │   ├── marigold.json
│   │   │   │   │   │   ├── pilgrimage.json
│   │   │   │   │   │   ├── pitch.json
│   │   │   │   │   │   ├── sherbet.json
│   │   │   │   │   │   └── whisper.json
│   │   │   │   │   ├── templates/
│   │   │   │   │   │   ├── 404.html
│   │   │   │   │   │   ├── archive.html
│   │   │   │   │   │   ├── blank.html
│   │   │   │   │   │   ├── blog-alternative.html
│   │   │   │   │   │   ├── home.html
│   │   │   │   │   │   ├── index.html
│   │   │   │   │   │   ├── page.html
│   │   │   │   │   │   ├── search.html
│   │   │   │   │   │   └── single.html
│   │   │   │   │   ├── readme.txt
│   │   │   │   │   ├── screenshot.png
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── theme.json
│   │   │   │   └── index.php
│   │   │   └── index.php
│   │   ├── wp-includes/
│   │   │   ├── abilities-api/
│   │   │   │   ├── class-wp-abilities-registry.php
│   │   │   │   ├── class-wp-ability-categories-registry.php
│   │   │   │   ├── class-wp-ability-category.php
│   │   │   │   └── class-wp-ability.php
│   │   │   ├── assets/
│   │   │   │   ├── script-loader-packages.min.php
│   │   │   │   ├── script-loader-packages.php
│   │   │   │   ├── script-loader-react-refresh-entry.min.php
│   │   │   │   ├── script-loader-react-refresh-entry.php
│   │   │   │   ├── script-loader-react-refresh-runtime.min.php
│   │   │   │   ├── script-loader-react-refresh-runtime.php
│   │   │   │   ├── script-modules-packages.min.php
│   │   │   │   └── script-modules-packages.php
│   │   │   ├── block-bindings/
│   │   │   │   ├── pattern-overrides.php
│   │   │   │   ├── post-data.php
│   │   │   │   ├── post-meta.php
│   │   │   │   └── term-data.php
│   │   │   ├── block-patterns/
│   │   │   │   ├── query-grid-posts.php
│   │   │   │   ├── query-large-title-posts.php
│   │   │   │   ├── query-medium-posts.php
│   │   │   │   ├── query-offset-posts.php
│   │   │   │   ├── query-small-posts.php
│   │   │   │   ├── query-standard-posts.php
│   │   │   │   └── social-links-shared-background-color.php
│   │   │   ├── block-supports/
│   │   │   │   ├── align.php
│   │   │   │   ├── aria-label.php
│   │   │   │   ├── background.php
│   │   │   │   ├── block-style-variations.php
│   │   │   │   ├── block-visibility.php
│   │   │   │   ├── border.php
│   │   │   │   ├── colors.php
│   │   │   │   ├── custom-classname.php
│   │   │   │   ├── dimensions.php
│   │   │   │   ├── duotone.php
│   │   │   │   ├── elements.php
│   │   │   │   ├── generated-classname.php
│   │   │   │   ├── layout.php
│   │   │   │   ├── position.php
│   │   │   │   ├── settings.php
│   │   │   │   ├── shadow.php
│   │   │   │   ├── spacing.php
│   │   │   │   ├── typography.php
│   │   │   │   └── utils.php
│   │   │   ├── blocks/
│   │   │   │   ├── accordion/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── accordion-heading/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── accordion-item/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── accordion-panel/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── archives/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── audio/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   ├── style.min.css
│   │   │   │   │   ├── theme-rtl.css
│   │   │   │   │   ├── theme-rtl.min.css
│   │   │   │   │   ├── theme.css
│   │   │   │   │   └── theme.min.css
│   │   │   │   ├── avatar/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── block/
│   │   │   │   │   └── block.json
│   │   │   │   ├── button/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── buttons/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── calendar/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── categories/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── code/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   ├── style.min.css
│   │   │   │   │   ├── theme-rtl.css
│   │   │   │   │   ├── theme-rtl.min.css
│   │   │   │   │   ├── theme.css
│   │   │   │   │   └── theme.min.css
│   │   │   │   ├── column/
│   │   │   │   │   └── block.json
│   │   │   │   ├── columns/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── comment-author-name/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── comment-content/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── comment-date/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── comment-edit-link/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── comment-reply-link/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── comment-template/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── comments/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── comments-pagination/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── comments-pagination-next/
│   │   │   │   │   └── block.json
│   │   │   │   ├── comments-pagination-numbers/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   └── editor.min.css
│   │   │   │   ├── comments-pagination-previous/
│   │   │   │   │   └── block.json
│   │   │   │   ├── comments-title/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   └── editor.min.css
│   │   │   │   ├── cover/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── details/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── embed/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   ├── style.min.css
│   │   │   │   │   ├── theme-rtl.css
│   │   │   │   │   ├── theme-rtl.min.css
│   │   │   │   │   ├── theme.css
│   │   │   │   │   └── theme.min.css
│   │   │   │   ├── file/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   ├── style.min.css
│   │   │   │   │   ├── view.asset.php
│   │   │   │   │   ├── view.js
│   │   │   │   │   ├── view.min.asset.php
│   │   │   │   │   └── view.min.js
│   │   │   │   ├── footnotes/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── freeform/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   └── editor.min.css
│   │   │   │   ├── gallery/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   ├── style.min.css
│   │   │   │   │   ├── theme-rtl.css
│   │   │   │   │   ├── theme-rtl.min.css
│   │   │   │   │   ├── theme.css
│   │   │   │   │   └── theme.min.css
│   │   │   │   ├── group/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   ├── style.min.css
│   │   │   │   │   ├── theme-rtl.css
│   │   │   │   │   ├── theme-rtl.min.css
│   │   │   │   │   ├── theme.css
│   │   │   │   │   └── theme.min.css
│   │   │   │   ├── heading/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── home-link/
│   │   │   │   │   └── block.json
│   │   │   │   ├── html/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   └── editor.min.css
│   │   │   │   ├── image/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   ├── style.min.css
│   │   │   │   │   ├── theme-rtl.css
│   │   │   │   │   ├── theme-rtl.min.css
│   │   │   │   │   ├── theme.css
│   │   │   │   │   ├── theme.min.css
│   │   │   │   │   ├── view.asset.php
│   │   │   │   │   ├── view.js
│   │   │   │   │   ├── view.min.asset.php
│   │   │   │   │   └── view.min.js
│   │   │   │   ├── latest-comments/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── latest-posts/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── legacy-widget/
│   │   │   │   │   └── block.json
│   │   │   │   ├── list/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── list-item/
│   │   │   │   │   └── block.json
│   │   │   │   ├── loginout/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── math/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── media-text/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── missing/
│   │   │   │   │   └── block.json
│   │   │   │   ├── more/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   └── editor.min.css
│   │   │   │   ├── navigation/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   ├── style.min.css
│   │   │   │   │   ├── view-modal.asset.php
│   │   │   │   │   ├── view-modal.min.asset.php
│   │   │   │   │   ├── view.asset.php
│   │   │   │   │   ├── view.js
│   │   │   │   │   ├── view.min.asset.php
│   │   │   │   │   └── view.min.js
│   │   │   │   ├── navigation-link/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── navigation-submenu/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   └── editor.min.css
│   │   │   │   ├── nextpage/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   └── editor.min.css
│   │   │   │   ├── page-list/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── page-list-item/
│   │   │   │   │   └── block.json
│   │   │   │   ├── paragraph/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── pattern/
│   │   │   │   │   └── block.json
│   │   │   │   ├── post-author/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── post-author-biography/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── post-author-name/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── post-comments-count/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── post-comments-form/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── post-comments-link/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── post-content/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── post-date/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── post-excerpt/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── post-featured-image/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── post-navigation-link/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── post-template/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── post-terms/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── post-time-to-read/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── post-title/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── preformatted/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── pullquote/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   ├── style.min.css
│   │   │   │   │   ├── theme-rtl.css
│   │   │   │   │   ├── theme-rtl.min.css
│   │   │   │   │   ├── theme.css
│   │   │   │   │   └── theme.min.css
│   │   │   │   ├── query/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── view.asset.php
│   │   │   │   │   ├── view.js
│   │   │   │   │   ├── view.min.asset.php
│   │   │   │   │   └── view.min.js
│   │   │   │   ├── query-no-results/
│   │   │   │   │   └── block.json
│   │   │   │   ├── query-pagination/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── query-pagination-next/
│   │   │   │   │   └── block.json
│   │   │   │   ├── query-pagination-numbers/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   └── editor.min.css
│   │   │   │   ├── query-pagination-previous/
│   │   │   │   │   └── block.json
│   │   │   │   ├── query-title/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── query-total/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── quote/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   ├── style.min.css
│   │   │   │   │   ├── theme-rtl.css
│   │   │   │   │   ├── theme-rtl.min.css
│   │   │   │   │   ├── theme.css
│   │   │   │   │   └── theme.min.css
│   │   │   │   ├── read-more/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── rss/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── search/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   ├── style.min.css
│   │   │   │   │   ├── theme-rtl.css
│   │   │   │   │   ├── theme-rtl.min.css
│   │   │   │   │   ├── theme.css
│   │   │   │   │   ├── theme.min.css
│   │   │   │   │   ├── view.asset.php
│   │   │   │   │   ├── view.js
│   │   │   │   │   ├── view.min.asset.php
│   │   │   │   │   └── view.min.js
│   │   │   │   ├── separator/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   ├── style.min.css
│   │   │   │   │   ├── theme-rtl.css
│   │   │   │   │   ├── theme-rtl.min.css
│   │   │   │   │   ├── theme.css
│   │   │   │   │   └── theme.min.css
│   │   │   │   ├── shortcode/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   └── editor.min.css
│   │   │   │   ├── site-logo/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── site-tagline/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── site-title/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── social-link/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   └── editor.min.css
│   │   │   │   ├── social-links/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── spacer/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── table/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   ├── style.min.css
│   │   │   │   │   ├── theme-rtl.css
│   │   │   │   │   ├── theme-rtl.min.css
│   │   │   │   │   ├── theme.css
│   │   │   │   │   └── theme.min.css
│   │   │   │   ├── tag-cloud/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── template-part/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── theme-rtl.css
│   │   │   │   │   ├── theme-rtl.min.css
│   │   │   │   │   ├── theme.css
│   │   │   │   │   └── theme.min.css
│   │   │   │   ├── term-count/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── term-description/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── term-name/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── term-template/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── terms-query/
│   │   │   │   │   └── block.json
│   │   │   │   ├── text-columns/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── verse/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   └── style.min.css
│   │   │   │   ├── video/
│   │   │   │   │   ├── block.json
│   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   ├── editor.css
│   │   │   │   │   ├── editor.min.css
│   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   ├── style.css
│   │   │   │   │   ├── style.min.css
│   │   │   │   │   ├── theme-rtl.css
│   │   │   │   │   ├── theme-rtl.min.css
│   │   │   │   │   ├── theme.css
│   │   │   │   │   └── theme.min.css
│   │   │   │   ├── widget-group/
│   │   │   │   │   └── block.json
│   │   │   │   ├── accordion-item.php
│   │   │   │   ├── accordion.php
│   │   │   │   ├── archives.php
│   │   │   │   ├── avatar.php
│   │   │   │   ├── block.php
│   │   │   │   ├── blocks-json.php
│   │   │   │   ├── button.php
│   │   │   │   ├── calendar.php
│   │   │   │   ├── categories.php
│   │   │   │   ├── comment-author-name.php
│   │   │   │   ├── comment-content.php
│   │   │   │   ├── comment-date.php
│   │   │   │   ├── comment-edit-link.php
│   │   │   │   ├── comment-reply-link.php
│   │   │   │   ├── comment-template.php
│   │   │   │   ├── comments-pagination-next.php
│   │   │   │   ├── comments-pagination-numbers.php
│   │   │   │   ├── comments-pagination-previous.php
│   │   │   │   ├── comments-pagination.php
│   │   │   │   ├── comments-title.php
│   │   │   │   ├── comments.php
│   │   │   │   ├── cover.php
│   │   │   │   ├── file.php
│   │   │   │   ├── footnotes.php
│   │   │   │   ├── gallery.php
│   │   │   │   ├── heading.php
│   │   │   │   ├── home-link.php
│   │   │   │   ├── image.php
│   │   │   │   ├── index.php
│   │   │   │   ├── latest-comments.php
│   │   │   │   ├── latest-posts.php
│   │   │   │   ├── legacy-widget.php
│   │   │   │   ├── list.php
│   │   │   │   ├── loginout.php
│   │   │   │   ├── media-text.php
│   │   │   │   ├── navigation-link.php
│   │   │   │   ├── navigation-submenu.php
│   │   │   │   ├── navigation.php
│   │   │   │   ├── page-list-item.php
│   │   │   │   ├── page-list.php
│   │   │   │   ├── pattern.php
│   │   │   │   ├── post-author-biography.php
│   │   │   │   ├── post-author-name.php
│   │   │   │   ├── post-author.php
│   │   │   │   ├── post-comments-count.php
│   │   │   │   ├── post-comments-form.php
│   │   │   │   ├── post-comments-link.php
│   │   │   │   ├── post-content.php
│   │   │   │   ├── post-date.php
│   │   │   │   ├── post-excerpt.php
│   │   │   │   ├── post-featured-image.php
│   │   │   │   ├── post-navigation-link.php
│   │   │   │   ├── post-template.php
│   │   │   │   ├── post-terms.php
│   │   │   │   ├── post-time-to-read.php
│   │   │   │   ├── post-title.php
│   │   │   │   ├── query-no-results.php
│   │   │   │   ├── query-pagination-next.php
│   │   │   │   ├── query-pagination-numbers.php
│   │   │   │   ├── query-pagination-previous.php
│   │   │   │   ├── query-pagination.php
│   │   │   │   ├── query-title.php
│   │   │   │   ├── query-total.php
│   │   │   │   ├── query.php
│   │   │   │   ├── read-more.php
│   │   │   │   ├── require-dynamic-blocks.php
│   │   │   │   ├── require-static-blocks.php
│   │   │   │   ├── rss.php
│   │   │   │   ├── search.php
│   │   │   │   ├── shortcode.php
│   │   │   │   ├── site-logo.php
│   │   │   │   ├── site-tagline.php
│   │   │   │   ├── site-title.php
│   │   │   │   ├── social-link.php
│   │   │   │   ├── tag-cloud.php
│   │   │   │   ├── template-part.php
│   │   │   │   ├── term-count.php
│   │   │   │   ├── term-description.php
│   │   │   │   ├── term-name.php
│   │   │   │   ├── term-template.php
│   │   │   │   ├── video.php
│   │   │   │   └── widget-group.php
│   │   │   ├── certificates/
│   │   │   │   └── ca-bundle.crt
│   │   │   ├── css/
│   │   │   │   ├── dist/
│   │   │   │   │   ├── admin-ui/
│   │   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   │   ├── style.css
│   │   │   │   │   │   └── style.min.css
│   │   │   │   │   ├── base-styles/
│   │   │   │   │   │   ├── admin-schemes-rtl.css
│   │   │   │   │   │   ├── admin-schemes-rtl.min.css
│   │   │   │   │   │   ├── admin-schemes.css
│   │   │   │   │   │   └── admin-schemes.min.css
│   │   │   │   │   ├── block-directory/
│   │   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   │   ├── style.css
│   │   │   │   │   │   └── style.min.css
│   │   │   │   │   ├── block-editor/
│   │   │   │   │   │   ├── content-rtl.css
│   │   │   │   │   │   ├── content-rtl.min.css
│   │   │   │   │   │   ├── content.css
│   │   │   │   │   │   ├── content.min.css
│   │   │   │   │   │   ├── default-editor-styles-rtl.css
│   │   │   │   │   │   ├── default-editor-styles-rtl.min.css
│   │   │   │   │   │   ├── default-editor-styles.css
│   │   │   │   │   │   ├── default-editor-styles.min.css
│   │   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   │   ├── style.css
│   │   │   │   │   │   └── style.min.css
│   │   │   │   │   ├── block-library/
│   │   │   │   │   │   ├── classic-rtl.css
│   │   │   │   │   │   ├── classic-rtl.min.css
│   │   │   │   │   │   ├── classic.css
│   │   │   │   │   │   ├── classic.min.css
│   │   │   │   │   │   ├── common-rtl.css
│   │   │   │   │   │   ├── common-rtl.min.css
│   │   │   │   │   │   ├── common.css
│   │   │   │   │   │   ├── common.min.css
│   │   │   │   │   │   ├── editor-elements-rtl.css
│   │   │   │   │   │   ├── editor-elements-rtl.min.css
│   │   │   │   │   │   ├── editor-elements.css
│   │   │   │   │   │   ├── editor-elements.min.css
│   │   │   │   │   │   ├── editor-rtl.css
│   │   │   │   │   │   ├── editor-rtl.min.css
│   │   │   │   │   │   ├── editor.css
│   │   │   │   │   │   ├── editor.min.css
│   │   │   │   │   │   ├── elements-rtl.css
│   │   │   │   │   │   ├── elements-rtl.min.css
│   │   │   │   │   │   ├── elements.css
│   │   │   │   │   │   ├── elements.min.css
│   │   │   │   │   │   ├── reset-rtl.css
│   │   │   │   │   │   ├── reset-rtl.min.css
│   │   │   │   │   │   ├── reset.css
│   │   │   │   │   │   ├── reset.min.css
│   │   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   │   ├── style.css
│   │   │   │   │   │   ├── style.min.css
│   │   │   │   │   │   ├── theme-rtl.css
│   │   │   │   │   │   ├── theme-rtl.min.css
│   │   │   │   │   │   ├── theme.css
│   │   │   │   │   │   └── theme.min.css
│   │   │   │   │   ├── commands/
│   │   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   │   ├── style.css
│   │   │   │   │   │   └── style.min.css
│   │   │   │   │   ├── components/
│   │   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   │   ├── style.css
│   │   │   │   │   │   └── style.min.css
│   │   │   │   │   ├── customize-widgets/
│   │   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   │   ├── style.css
│   │   │   │   │   │   └── style.min.css
│   │   │   │   │   ├── edit-post/
│   │   │   │   │   │   ├── classic-rtl.css
│   │   │   │   │   │   ├── classic-rtl.min.css
│   │   │   │   │   │   ├── classic.css
│   │   │   │   │   │   ├── classic.min.css
│   │   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   │   ├── style.css
│   │   │   │   │   │   └── style.min.css
│   │   │   │   │   ├── edit-site/
│   │   │   │   │   │   ├── posts-rtl.css
│   │   │   │   │   │   ├── posts-rtl.min.css
│   │   │   │   │   │   ├── posts.css
│   │   │   │   │   │   ├── posts.min.css
│   │   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   │   ├── style.css
│   │   │   │   │   │   └── style.min.css
│   │   │   │   │   ├── edit-widgets/
│   │   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   │   ├── style.css
│   │   │   │   │   │   └── style.min.css
│   │   │   │   │   ├── editor/
│   │   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   │   ├── style.css
│   │   │   │   │   │   └── style.min.css
│   │   │   │   │   ├── format-library/
│   │   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   │   ├── style.css
│   │   │   │   │   │   └── style.min.css
│   │   │   │   │   ├── list-reusable-blocks/
│   │   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   │   ├── style.css
│   │   │   │   │   │   └── style.min.css
│   │   │   │   │   ├── nux/
│   │   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   │   ├── style.css
│   │   │   │   │   │   └── style.min.css
│   │   │   │   │   ├── patterns/
│   │   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   │   ├── style.css
│   │   │   │   │   │   └── style.min.css
│   │   │   │   │   ├── preferences/
│   │   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   │   ├── style.css
│   │   │   │   │   │   └── style.min.css
│   │   │   │   │   ├── reusable-blocks/
│   │   │   │   │   │   ├── style-rtl.css
│   │   │   │   │   │   ├── style-rtl.min.css
│   │   │   │   │   │   ├── style.css
│   │   │   │   │   │   └── style.min.css
│   │   │   │   │   └── widgets/
│   │   │   │   │       ├── style-rtl.css
│   │   │   │   │       ├── style-rtl.min.css
│   │   │   │   │       ├── style.css
│   │   │   │   │       └── style.min.css
│   │   │   │   ├── admin-bar-rtl.css
│   │   │   │   ├── admin-bar-rtl.min.css
│   │   │   │   ├── admin-bar.css
│   │   │   │   ├── admin-bar.min.css
│   │   │   │   ├── buttons-rtl.css
│   │   │   │   ├── buttons-rtl.min.css
│   │   │   │   ├── buttons.css
│   │   │   │   ├── buttons.min.css
│   │   │   │   ├── classic-themes.css
│   │   │   │   ├── classic-themes.min.css
│   │   │   │   ├── customize-preview-rtl.css
│   │   │   │   ├── customize-preview-rtl.min.css
│   │   │   │   ├── customize-preview.css
│   │   │   │   ├── customize-preview.min.css
│   │   │   │   ├── dashicons.css
│   │   │   │   ├── dashicons.min.css
│   │   │   │   ├── editor-rtl.css
│   │   │   │   ├── editor-rtl.min.css
│   │   │   │   ├── editor.css
│   │   │   │   ├── editor.min.css
│   │   │   │   ├── jquery-ui-dialog-rtl.css
│   │   │   │   ├── jquery-ui-dialog-rtl.min.css
│   │   │   │   ├── jquery-ui-dialog.css
│   │   │   │   ├── jquery-ui-dialog.min.css
│   │   │   │   ├── media-views-rtl.css
│   │   │   │   ├── media-views-rtl.min.css
│   │   │   │   ├── media-views.css
│   │   │   │   ├── media-views.min.css
│   │   │   │   ├── wp-auth-check-rtl.css
│   │   │   │   ├── wp-auth-check-rtl.min.css
│   │   │   │   ├── wp-auth-check.css
│   │   │   │   ├── wp-auth-check.min.css
│   │   │   │   ├── wp-embed-template-ie.css
│   │   │   │   ├── wp-embed-template-ie.min.css
│   │   │   │   ├── wp-embed-template.css
│   │   │   │   ├── wp-embed-template.min.css
│   │   │   │   ├── wp-empty-template-alert.css
│   │   │   │   ├── wp-empty-template-alert.min.css
│   │   │   │   ├── wp-pointer-rtl.css
│   │   │   │   ├── wp-pointer-rtl.min.css
│   │   │   │   ├── wp-pointer.css
│   │   │   │   └── wp-pointer.min.css
│   │   │   ├── customize/
│   │   │   │   ├── class-wp-customize-background-image-control.php
│   │   │   │   ├── class-wp-customize-background-image-setting.php
│   │   │   │   ├── class-wp-customize-background-position-control.php
│   │   │   │   ├── class-wp-customize-code-editor-control.php
│   │   │   │   ├── class-wp-customize-color-control.php
│   │   │   │   ├── class-wp-customize-cropped-image-control.php
│   │   │   │   ├── class-wp-customize-custom-css-setting.php
│   │   │   │   ├── class-wp-customize-date-time-control.php
│   │   │   │   ├── class-wp-customize-filter-setting.php
│   │   │   │   ├── class-wp-customize-header-image-control.php
│   │   │   │   ├── class-wp-customize-header-image-setting.php
│   │   │   │   ├── class-wp-customize-image-control.php
│   │   │   │   ├── class-wp-customize-media-control.php
│   │   │   │   ├── class-wp-customize-nav-menu-auto-add-control.php
│   │   │   │   ├── class-wp-customize-nav-menu-control.php
│   │   │   │   ├── class-wp-customize-nav-menu-item-control.php
│   │   │   │   ├── class-wp-customize-nav-menu-item-setting.php
│   │   │   │   ├── class-wp-customize-nav-menu-location-control.php
│   │   │   │   ├── class-wp-customize-nav-menu-locations-control.php
│   │   │   │   ├── class-wp-customize-nav-menu-name-control.php
│   │   │   │   ├── class-wp-customize-nav-menu-section.php
│   │   │   │   ├── class-wp-customize-nav-menu-setting.php
│   │   │   │   ├── class-wp-customize-nav-menus-panel.php
│   │   │   │   ├── class-wp-customize-new-menu-control.php
│   │   │   │   ├── class-wp-customize-new-menu-section.php
│   │   │   │   ├── class-wp-customize-partial.php
│   │   │   │   ├── class-wp-customize-selective-refresh.php
│   │   │   │   ├── class-wp-customize-sidebar-section.php
│   │   │   │   ├── class-wp-customize-site-icon-control.php
│   │   │   │   ├── class-wp-customize-theme-control.php
│   │   │   │   ├── class-wp-customize-themes-panel.php
│   │   │   │   ├── class-wp-customize-themes-section.php
│   │   │   │   ├── class-wp-customize-upload-control.php
│   │   │   │   ├── class-wp-sidebar-block-editor-control.php
│   │   │   │   ├── class-wp-widget-area-customize-control.php
│   │   │   │   └── class-wp-widget-form-customize-control.php
│   │   │   ├── fonts/
│   │   │   │   ├── class-wp-font-collection.php
│   │   │   │   ├── class-wp-font-face-resolver.php
│   │   │   │   ├── class-wp-font-face.php
│   │   │   │   ├── class-wp-font-library.php
│   │   │   │   ├── class-wp-font-utils.php
│   │   │   │   ├── dashicons.eot
│   │   │   │   ├── dashicons.svg
│   │   │   │   ├── dashicons.ttf
│   │   │   │   ├── dashicons.woff
│   │   │   │   └── dashicons.woff2
│   │   │   ├── html-api/
│   │   │   │   ├── class-wp-html-active-formatting-elements.php
│   │   │   │   ├── class-wp-html-attribute-token.php
│   │   │   │   ├── class-wp-html-decoder.php
│   │   │   │   ├── class-wp-html-doctype-info.php
│   │   │   │   ├── class-wp-html-open-elements.php
│   │   │   │   ├── class-wp-html-processor-state.php
│   │   │   │   ├── class-wp-html-processor.php
│   │   │   │   ├── class-wp-html-span.php
│   │   │   │   ├── class-wp-html-stack-event.php
│   │   │   │   ├── class-wp-html-tag-processor.php
│   │   │   │   ├── class-wp-html-text-replacement.php
│   │   │   │   ├── class-wp-html-token.php
│   │   │   │   ├── class-wp-html-unsupported-exception.php
│   │   │   │   └── html5-named-character-references.php
│   │   │   ├── ID3/
│   │   │   │   ├── getid3.lib.php
│   │   │   │   ├── getid3.php
│   │   │   │   ├── license.txt
│   │   │   │   ├── module.audio-video.asf.php
│   │   │   │   ├── module.audio-video.flv.php
│   │   │   │   ├── module.audio-video.matroska.php
│   │   │   │   ├── module.audio-video.quicktime.php
│   │   │   │   ├── module.audio-video.riff.php
│   │   │   │   ├── module.audio.ac3.php
│   │   │   │   ├── module.audio.dts.php
│   │   │   │   ├── module.audio.flac.php
│   │   │   │   ├── module.audio.mp3.php
│   │   │   │   ├── module.audio.ogg.php
│   │   │   │   ├── module.tag.apetag.php
│   │   │   │   ├── module.tag.id3v1.php
│   │   │   │   ├── module.tag.id3v2.php
│   │   │   │   ├── module.tag.lyrics3.php
│   │   │   │   └── readme.txt
│   │   │   ├── images/
│   │   │   │   ├── crystal/
│   │   │   │   │   ├── archive.png
│   │   │   │   │   ├── audio.png
│   │   │   │   │   ├── code.png
│   │   │   │   │   ├── default.png
│   │   │   │   │   ├── document.png
│   │   │   │   │   ├── interactive.png
│   │   │   │   │   ├── license.txt
│   │   │   │   │   ├── spreadsheet.png
│   │   │   │   │   ├── text.png
│   │   │   │   │   └── video.png
│   │   │   │   ├── media/
│   │   │   │   │   ├── archive.png
│   │   │   │   │   ├── archive.svg
│   │   │   │   │   ├── audio.png
│   │   │   │   │   ├── audio.svg
│   │   │   │   │   ├── code.png
│   │   │   │   │   ├── code.svg
│   │   │   │   │   ├── default.png
│   │   │   │   │   ├── default.svg
│   │   │   │   │   ├── document.png
│   │   │   │   │   ├── document.svg
│   │   │   │   │   ├── interactive.png
│   │   │   │   │   ├── interactive.svg
│   │   │   │   │   ├── spreadsheet.png
│   │   │   │   │   ├── spreadsheet.svg
│   │   │   │   │   ├── text.png
│   │   │   │   │   ├── text.svg
│   │   │   │   │   ├── video.png
│   │   │   │   │   └── video.svg
│   │   │   │   ├── smilies/
│   │   │   │   │   ├── frownie.png
│   │   │   │   │   ├── icon_arrow.gif
│   │   │   │   │   ├── icon_biggrin.gif
│   │   │   │   │   ├── icon_confused.gif
│   │   │   │   │   ├── icon_cool.gif
│   │   │   │   │   ├── icon_cry.gif
│   │   │   │   │   ├── icon_eek.gif
│   │   │   │   │   ├── icon_evil.gif
│   │   │   │   │   ├── icon_exclaim.gif
│   │   │   │   │   ├── icon_idea.gif
│   │   │   │   │   ├── icon_lol.gif
│   │   │   │   │   ├── icon_mad.gif
│   │   │   │   │   ├── icon_mrgreen.gif
│   │   │   │   │   ├── icon_neutral.gif
│   │   │   │   │   ├── icon_question.gif
│   │   │   │   │   ├── icon_razz.gif
│   │   │   │   │   ├── icon_redface.gif
│   │   │   │   │   ├── icon_rolleyes.gif
│   │   │   │   │   ├── icon_sad.gif
│   │   │   │   │   ├── icon_smile.gif
│   │   │   │   │   ├── icon_surprised.gif
│   │   │   │   │   ├── icon_twisted.gif
│   │   │   │   │   ├── icon_wink.gif
│   │   │   │   │   ├── mrgreen.png
│   │   │   │   │   ├── rolleyes.png
│   │   │   │   │   └── simple-smile.png
│   │   │   │   ├── admin-bar-sprite-2x.png
│   │   │   │   ├── admin-bar-sprite.png
│   │   │   │   ├── arrow-pointer-blue-2x.png
│   │   │   │   ├── arrow-pointer-blue.png
│   │   │   │   ├── blank.gif
│   │   │   │   ├── down_arrow-2x.gif
│   │   │   │   ├── down_arrow.gif
│   │   │   │   ├── icon-pointer-flag-2x.png
│   │   │   │   ├── icon-pointer-flag.png
│   │   │   │   ├── rss-2x.png
│   │   │   │   ├── rss.png
│   │   │   │   ├── spinner-2x.gif
│   │   │   │   ├── spinner.gif
│   │   │   │   ├── toggle-arrow-2x.png
│   │   │   │   ├── toggle-arrow.png
│   │   │   │   ├── uploader-icons-2x.png
│   │   │   │   ├── uploader-icons.png
│   │   │   │   ├── w-logo-blue-white-bg.png
│   │   │   │   ├── w-logo-blue.png
│   │   │   │   ├── wpicons-2x.png
│   │   │   │   ├── wpicons.png
│   │   │   │   ├── wpspin-2x.gif
│   │   │   │   ├── wpspin.gif
│   │   │   │   ├── xit-2x.gif
│   │   │   │   └── xit.gif
│   │   │   ├── interactivity-api/
│   │   │   │   ├── class-wp-interactivity-api-directives-processor.php
│   │   │   │   ├── class-wp-interactivity-api.php
│   │   │   │   └── interactivity-api.php
│   │   │   ├── IXR/
│   │   │   │   ├── class-IXR-base64.php
│   │   │   │   ├── class-IXR-client.php
│   │   │   │   ├── class-IXR-clientmulticall.php
│   │   │   │   ├── class-IXR-date.php
│   │   │   │   ├── class-IXR-error.php
│   │   │   │   ├── class-IXR-introspectionserver.php
│   │   │   │   ├── class-IXR-message.php
│   │   │   │   ├── class-IXR-request.php
│   │   │   │   ├── class-IXR-server.php
│   │   │   │   └── class-IXR-value.php
│   │   │   ├── js/
│   │   │   │   ├── codemirror/
│   │   │   │   │   ├── codemirror.min.css
│   │   │   │   │   ├── codemirror.min.js
│   │   │   │   │   ├── csslint.js
│   │   │   │   │   ├── esprima.js
│   │   │   │   │   ├── fakejshint.js
│   │   │   │   │   ├── htmlhint-kses.js
│   │   │   │   │   ├── htmlhint.js
│   │   │   │   │   └── jsonlint.js
│   │   │   │   ├── crop/
│   │   │   │   │   ├── cropper.css
│   │   │   │   │   ├── cropper.js
│   │   │   │   │   ├── marqueeHoriz.gif
│   │   │   │   │   └── marqueeVert.gif
│   │   │   │   ├── dist/
│   │   │   │   │   ├── development/
│   │   │   │   │   │   ├── react-refresh-entry.js
│   │   │   │   │   │   ├── react-refresh-entry.min.js
│   │   │   │   │   │   ├── react-refresh-runtime.js
│   │   │   │   │   │   └── react-refresh-runtime.min.js
│   │   │   │   │   ├── script-modules/
│   │   │   │   │   │   ├── a11y/
│   │   │   │   │   │   │   ├── index.js
│   │   │   │   │   │   │   └── index.min.js
│   │   │   │   │   │   ├── block-editor/
│   │   │   │   │   │   │   └── utils/
│   │   │   │   │   │   │       ├── fit-text-frontend.js
│   │   │   │   │   │   │       └── fit-text-frontend.min.js
│   │   │   │   │   │   ├── block-library/
│   │   │   │   │   │   │   ├── accordion/
│   │   │   │   │   │   │   │   ├── view.js
│   │   │   │   │   │   │   │   └── view.min.js
│   │   │   │   │   │   │   ├── file/
│   │   │   │   │   │   │   │   ├── view.js
│   │   │   │   │   │   │   │   └── view.min.js
│   │   │   │   │   │   │   ├── form/
│   │   │   │   │   │   │   │   ├── view.js
│   │   │   │   │   │   │   │   └── view.min.js
│   │   │   │   │   │   │   ├── image/
│   │   │   │   │   │   │   │   ├── view.js
│   │   │   │   │   │   │   │   └── view.min.js
│   │   │   │   │   │   │   ├── navigation/
│   │   │   │   │   │   │   │   ├── view.js
│   │   │   │   │   │   │   │   └── view.min.js
│   │   │   │   │   │   │   ├── query/
│   │   │   │   │   │   │   │   ├── view.js
│   │   │   │   │   │   │   │   └── view.min.js
│   │   │   │   │   │   │   └── search/
│   │   │   │   │   │   │       ├── view.js
│   │   │   │   │   │   │       └── view.min.js
│   │   │   │   │   │   ├── interactivity/
│   │   │   │   │   │   │   ├── debug.js
│   │   │   │   │   │   │   ├── debug.min.js
│   │   │   │   │   │   │   ├── index.js
│   │   │   │   │   │   │   └── index.min.js
│   │   │   │   │   │   └── interactivity-router/
│   │   │   │   │   │       ├── index.js
│   │   │   │   │   │       └── index.min.js
│   │   │   │   │   ├── a11y.js
│   │   │   │   │   ├── a11y.min.js
│   │   │   │   │   ├── admin-ui.js
│   │   │   │   │   ├── admin-ui.min.js
│   │   │   │   │   ├── annotations.js
│   │   │   │   │   ├── annotations.min.js
│   │   │   │   │   ├── api-fetch.js
│   │   │   │   │   ├── api-fetch.min.js
│   │   │   │   │   ├── autop.js
│   │   │   │   │   ├── autop.min.js
│   │   │   │   │   ├── base-styles.js
│   │   │   │   │   ├── base-styles.min.js
│   │   │   │   │   ├── blob.js
│   │   │   │   │   ├── blob.min.js
│   │   │   │   │   ├── block-directory.js
│   │   │   │   │   ├── block-directory.min.js
│   │   │   │   │   ├── block-editor.js
│   │   │   │   │   ├── block-editor.min.js
│   │   │   │   │   ├── block-library.js
│   │   │   │   │   ├── block-library.min.js
│   │   │   │   │   ├── block-serialization-default-parser.js
│   │   │   │   │   ├── block-serialization-default-parser.min.js
│   │   │   │   │   ├── blocks.js
│   │   │   │   │   ├── blocks.min.js
│   │   │   │   │   ├── commands.js
│   │   │   │   │   ├── commands.min.js
│   │   │   │   │   ├── components.js
│   │   │   │   │   ├── components.min.js
│   │   │   │   │   ├── compose.js
│   │   │   │   │   ├── compose.min.js
│   │   │   │   │   ├── core-commands.js
│   │   │   │   │   ├── core-commands.min.js
│   │   │   │   │   ├── core-data.js
│   │   │   │   │   ├── core-data.min.js
│   │   │   │   │   ├── customize-widgets.js
│   │   │   │   │   ├── customize-widgets.min.js
│   │   │   │   │   ├── data-controls.js
│   │   │   │   │   ├── data-controls.min.js
│   │   │   │   │   ├── data.js
│   │   │   │   │   ├── data.min.js
│   │   │   │   │   ├── date.js
│   │   │   │   │   ├── date.min.js
│   │   │   │   │   ├── deprecated.js
│   │   │   │   │   ├── deprecated.min.js
│   │   │   │   │   ├── dom-ready.js
│   │   │   │   │   ├── dom-ready.min.js
│   │   │   │   │   ├── dom.js
│   │   │   │   │   ├── dom.min.js
│   │   │   │   │   ├── edit-post.js
│   │   │   │   │   ├── edit-post.min.js
│   │   │   │   │   ├── edit-site.js
│   │   │   │   │   ├── edit-site.min.js
│   │   │   │   │   ├── edit-widgets.js
│   │   │   │   │   ├── edit-widgets.min.js
│   │   │   │   │   ├── editor.js
│   │   │   │   │   ├── editor.min.js
│   │   │   │   │   ├── element.js
│   │   │   │   │   ├── element.min.js
│   │   │   │   │   ├── escape-html.js
│   │   │   │   │   ├── escape-html.min.js
│   │   │   │   │   ├── format-library.js
│   │   │   │   │   ├── format-library.min.js
│   │   │   │   │   ├── hooks.js
│   │   │   │   │   ├── hooks.min.js
│   │   │   │   │   ├── html-entities.js
│   │   │   │   │   ├── html-entities.min.js
│   │   │   │   │   ├── i18n.js
│   │   │   │   │   ├── i18n.min.js
│   │   │   │   │   ├── is-shallow-equal.js
│   │   │   │   │   ├── is-shallow-equal.min.js
│   │   │   │   │   ├── keyboard-shortcuts.js
│   │   │   │   │   ├── keyboard-shortcuts.min.js
│   │   │   │   │   ├── keycodes.js
│   │   │   │   │   ├── keycodes.min.js
│   │   │   │   │   ├── latex-to-mathml.js
│   │   │   │   │   ├── latex-to-mathml.min.js
│   │   │   │   │   ├── list-reusable-blocks.js
│   │   │   │   │   ├── list-reusable-blocks.min.js
│   │   │   │   │   ├── media-utils.js
│   │   │   │   │   ├── media-utils.min.js
│   │   │   │   │   ├── notices.js
│   │   │   │   │   ├── notices.min.js
│   │   │   │   │   ├── nux.js
│   │   │   │   │   ├── nux.min.js
│   │   │   │   │   ├── patterns.js
│   │   │   │   │   ├── patterns.min.js
│   │   │   │   │   ├── plugins.js
│   │   │   │   │   ├── plugins.min.js
│   │   │   │   │   ├── preferences-persistence.js
│   │   │   │   │   ├── preferences-persistence.min.js
│   │   │   │   │   ├── preferences.js
│   │   │   │   │   ├── preferences.min.js
│   │   │   │   │   ├── primitives.js
│   │   │   │   │   ├── primitives.min.js
│   │   │   │   │   ├── priority-queue.js
│   │   │   │   │   ├── priority-queue.min.js
│   │   │   │   │   ├── private-apis.js
│   │   │   │   │   ├── private-apis.min.js
│   │   │   │   │   ├── redux-routine.js
│   │   │   │   │   ├── redux-routine.min.js
│   │   │   │   │   ├── reusable-blocks.js
│   │   │   │   │   ├── reusable-blocks.min.js
│   │   │   │   │   ├── rich-text.js
│   │   │   │   │   ├── rich-text.min.js
│   │   │   │   │   ├── router.js
│   │   │   │   │   ├── router.min.js
│   │   │   │   │   ├── server-side-render.js
│   │   │   │   │   ├── server-side-render.min.js
│   │   │   │   │   ├── shortcode.js
│   │   │   │   │   ├── shortcode.min.js
│   │   │   │   │   ├── style-engine.js
│   │   │   │   │   ├── style-engine.min.js
│   │   │   │   │   ├── token-list.js
│   │   │   │   │   ├── token-list.min.js
│   │   │   │   │   ├── url.js
│   │   │   │   │   ├── url.min.js
│   │   │   │   │   ├── viewport.js
│   │   │   │   │   ├── viewport.min.js
│   │   │   │   │   ├── views.js
│   │   │   │   │   ├── views.min.js
│   │   │   │   │   ├── warning.js
│   │   │   │   │   ├── warning.min.js
│   │   │   │   │   ├── widgets.js
│   │   │   │   │   ├── widgets.min.js
│   │   │   │   │   ├── wordcount.js
│   │   │   │   │   └── wordcount.min.js
│   │   │   │   ├── imgareaselect/
│   │   │   │   │   ├── border-anim-h.gif
│   │   │   │   │   ├── border-anim-v.gif
│   │   │   │   │   ├── imgareaselect.css
│   │   │   │   │   ├── jquery.imgareaselect.js
│   │   │   │   │   └── jquery.imgareaselect.min.js
│   │   │   │   ├── jcrop/
│   │   │   │   │   ├── Jcrop.gif
│   │   │   │   │   ├── jquery.Jcrop.min.css
│   │   │   │   │   └── jquery.Jcrop.min.js
│   │   │   │   ├── jquery/
│   │   │   │   │   ├── ui/
│   │   │   │   │   │   ├── accordion.js
│   │   │   │   │   │   ├── accordion.min.js
│   │   │   │   │   │   ├── autocomplete.js
│   │   │   │   │   │   ├── autocomplete.min.js
│   │   │   │   │   │   ├── button.js
│   │   │   │   │   │   ├── button.min.js
│   │   │   │   │   │   ├── checkboxradio.js
│   │   │   │   │   │   ├── checkboxradio.min.js
│   │   │   │   │   │   ├── controlgroup.js
│   │   │   │   │   │   ├── controlgroup.min.js
│   │   │   │   │   │   ├── core.js
│   │   │   │   │   │   ├── core.min.js
│   │   │   │   │   │   ├── datepicker.js
│   │   │   │   │   │   ├── datepicker.min.js
│   │   │   │   │   │   ├── dialog.js
│   │   │   │   │   │   ├── dialog.min.js
│   │   │   │   │   │   ├── draggable.js
│   │   │   │   │   │   ├── draggable.min.js
│   │   │   │   │   │   ├── droppable.js
│   │   │   │   │   │   ├── droppable.min.js
│   │   │   │   │   │   ├── effect-blind.js
│   │   │   │   │   │   ├── effect-blind.min.js
│   │   │   │   │   │   ├── effect-bounce.js
│   │   │   │   │   │   ├── effect-bounce.min.js
│   │   │   │   │   │   ├── effect-clip.js
│   │   │   │   │   │   ├── effect-clip.min.js
│   │   │   │   │   │   ├── effect-drop.js
│   │   │   │   │   │   ├── effect-drop.min.js
│   │   │   │   │   │   ├── effect-explode.js
│   │   │   │   │   │   ├── effect-explode.min.js
│   │   │   │   │   │   ├── effect-fade.js
│   │   │   │   │   │   ├── effect-fade.min.js
│   │   │   │   │   │   ├── effect-fold.js
│   │   │   │   │   │   ├── effect-fold.min.js
│   │   │   │   │   │   ├── effect-highlight.js
│   │   │   │   │   │   ├── effect-highlight.min.js
│   │   │   │   │   │   ├── effect-puff.js
│   │   │   │   │   │   ├── effect-puff.min.js
│   │   │   │   │   │   ├── effect-pulsate.js
│   │   │   │   │   │   ├── effect-pulsate.min.js
│   │   │   │   │   │   ├── effect-scale.js
│   │   │   │   │   │   ├── effect-scale.min.js
│   │   │   │   │   │   ├── effect-shake.js
│   │   │   │   │   │   ├── effect-shake.min.js
│   │   │   │   │   │   ├── effect-size.js
│   │   │   │   │   │   ├── effect-size.min.js
│   │   │   │   │   │   ├── effect-slide.js
│   │   │   │   │   │   ├── effect-slide.min.js
│   │   │   │   │   │   ├── effect-transfer.js
│   │   │   │   │   │   ├── effect-transfer.min.js
│   │   │   │   │   │   ├── effect.js
│   │   │   │   │   │   ├── effect.min.js
│   │   │   │   │   │   ├── menu.js
│   │   │   │   │   │   ├── menu.min.js
│   │   │   │   │   │   ├── mouse.js
│   │   │   │   │   │   ├── mouse.min.js
│   │   │   │   │   │   ├── progressbar.js
│   │   │   │   │   │   ├── progressbar.min.js
│   │   │   │   │   │   ├── resizable.js
│   │   │   │   │   │   ├── resizable.min.js
│   │   │   │   │   │   ├── selectable.js
│   │   │   │   │   │   ├── selectable.min.js
│   │   │   │   │   │   ├── selectmenu.js
│   │   │   │   │   │   ├── selectmenu.min.js
│   │   │   │   │   │   ├── slider.js
│   │   │   │   │   │   ├── slider.min.js
│   │   │   │   │   │   ├── sortable.js
│   │   │   │   │   │   ├── sortable.min.js
│   │   │   │   │   │   ├── spinner.js
│   │   │   │   │   │   ├── spinner.min.js
│   │   │   │   │   │   ├── tabs.js
│   │   │   │   │   │   ├── tabs.min.js
│   │   │   │   │   │   ├── tooltip.js
│   │   │   │   │   │   └── tooltip.min.js
│   │   │   │   │   ├── jquery-migrate.js
│   │   │   │   │   ├── jquery-migrate.min.js
│   │   │   │   │   ├── jquery.color.min.js
│   │   │   │   │   ├── jquery.form.js
│   │   │   │   │   ├── jquery.form.min.js
│   │   │   │   │   ├── jquery.hotkeys.js
│   │   │   │   │   ├── jquery.hotkeys.min.js
│   │   │   │   │   ├── jquery.js
│   │   │   │   │   ├── jquery.masonry.min.js
│   │   │   │   │   ├── jquery.min.js
│   │   │   │   │   ├── jquery.query.js
│   │   │   │   │   ├── jquery.schedule.js
│   │   │   │   │   ├── jquery.serialize-object.js
│   │   │   │   │   ├── jquery.table-hotkeys.js
│   │   │   │   │   ├── jquery.table-hotkeys.min.js
│   │   │   │   │   ├── jquery.ui.touch-punch.js
│   │   │   │   │   ├── suggest.js
│   │   │   │   │   └── suggest.min.js
│   │   │   │   ├── mediaelement/
│   │   │   │   │   ├── renderers/
│   │   │   │   │   │   ├── vimeo.js
│   │   │   │   │   │   └── vimeo.min.js
│   │   │   │   │   ├── mediaelement-and-player.js
│   │   │   │   │   ├── mediaelement-and-player.min.js
│   │   │   │   │   ├── mediaelement-migrate.js
│   │   │   │   │   ├── mediaelement-migrate.min.js
│   │   │   │   │   ├── mediaelement.js
│   │   │   │   │   ├── mediaelement.min.js
│   │   │   │   │   ├── mediaelementplayer-legacy.css
│   │   │   │   │   ├── mediaelementplayer-legacy.min.css
│   │   │   │   │   ├── mediaelementplayer.css
│   │   │   │   │   ├── mediaelementplayer.min.css
│   │   │   │   │   ├── mejs-controls.png
│   │   │   │   │   ├── mejs-controls.svg
│   │   │   │   │   ├── wp-mediaelement.css
│   │   │   │   │   ├── wp-mediaelement.js
│   │   │   │   │   ├── wp-mediaelement.min.css
│   │   │   │   │   ├── wp-mediaelement.min.js
│   │   │   │   │   ├── wp-playlist.js
│   │   │   │   │   └── wp-playlist.min.js
│   │   │   │   ├── plupload/
│   │   │   │   │   ├── handlers.js
│   │   │   │   │   ├── handlers.min.js
│   │   │   │   │   ├── license.txt
│   │   │   │   │   ├── moxie.js
│   │   │   │   │   ├── moxie.min.js
│   │   │   │   │   ├── plupload.js
│   │   │   │   │   ├── plupload.min.js
│   │   │   │   │   ├── wp-plupload.js
│   │   │   │   │   └── wp-plupload.min.js
│   │   │   │   ├── swfupload/
│   │   │   │   │   ├── handlers.js
│   │   │   │   │   ├── handlers.min.js
│   │   │   │   │   ├── license.txt
│   │   │   │   │   └── swfupload.js
│   │   │   │   ├── thickbox/
│   │   │   │   │   ├── loadingAnimation.gif
│   │   │   │   │   ├── macFFBgHack.png
│   │   │   │   │   ├── thickbox.css
│   │   │   │   │   └── thickbox.js
│   │   │   │   ├── tinymce/
│   │   │   │   │   ├── langs/
│   │   │   │   │   │   └── wp-langs-en.js
│   │   │   │   │   ├── plugins/
│   │   │   │   │   │   ├── charmap/
│   │   │   │   │   │   │   ├── plugin.js
│   │   │   │   │   │   │   └── plugin.min.js
│   │   │   │   │   │   ├── colorpicker/
│   │   │   │   │   │   │   ├── plugin.js
│   │   │   │   │   │   │   └── plugin.min.js
│   │   │   │   │   │   ├── compat3x/
│   │   │   │   │   │   │   ├── css/
│   │   │   │   │   │   │   │   └── dialog.css
│   │   │   │   │   │   │   ├── plugin.js
│   │   │   │   │   │   │   └── plugin.min.js
│   │   │   │   │   │   ├── directionality/
│   │   │   │   │   │   │   ├── plugin.js
│   │   │   │   │   │   │   └── plugin.min.js
│   │   │   │   │   │   ├── fullscreen/
│   │   │   │   │   │   │   ├── plugin.js
│   │   │   │   │   │   │   └── plugin.min.js
│   │   │   │   │   │   ├── hr/
│   │   │   │   │   │   │   ├── plugin.js
│   │   │   │   │   │   │   └── plugin.min.js
│   │   │   │   │   │   ├── image/
│   │   │   │   │   │   │   ├── plugin.js
│   │   │   │   │   │   │   └── plugin.min.js
│   │   │   │   │   │   ├── link/
│   │   │   │   │   │   │   ├── plugin.js
│   │   │   │   │   │   │   └── plugin.min.js
│   │   │   │   │   │   ├── lists/
│   │   │   │   │   │   │   ├── plugin.js
│   │   │   │   │   │   │   └── plugin.min.js
│   │   │   │   │   │   ├── media/
│   │   │   │   │   │   │   ├── plugin.js
│   │   │   │   │   │   │   └── plugin.min.js
│   │   │   │   │   │   ├── paste/
│   │   │   │   │   │   │   ├── plugin.js
│   │   │   │   │   │   │   └── plugin.min.js
│   │   │   │   │   │   ├── tabfocus/
│   │   │   │   │   │   │   ├── plugin.js
│   │   │   │   │   │   │   └── plugin.min.js
│   │   │   │   │   │   ├── textcolor/
│   │   │   │   │   │   │   ├── plugin.js
│   │   │   │   │   │   │   └── plugin.min.js
│   │   │   │   │   │   ├── wordpress/
│   │   │   │   │   │   │   ├── plugin.js
│   │   │   │   │   │   │   └── plugin.min.js
│   │   │   │   │   │   ├── wpautoresize/
│   │   │   │   │   │   │   ├── plugin.js
│   │   │   │   │   │   │   └── plugin.min.js
│   │   │   │   │   │   ├── wpdialogs/
│   │   │   │   │   │   │   ├── plugin.js
│   │   │   │   │   │   │   └── plugin.min.js
│   │   │   │   │   │   ├── wpeditimage/
│   │   │   │   │   │   │   ├── plugin.js
│   │   │   │   │   │   │   └── plugin.min.js
│   │   │   │   │   │   ├── wpemoji/
│   │   │   │   │   │   │   ├── plugin.js
│   │   │   │   │   │   │   └── plugin.min.js
│   │   │   │   │   │   ├── wpgallery/
│   │   │   │   │   │   │   ├── plugin.js
│   │   │   │   │   │   │   └── plugin.min.js
│   │   │   │   │   │   ├── wplink/
│   │   │   │   │   │   │   ├── plugin.js
│   │   │   │   │   │   │   └── plugin.min.js
│   │   │   │   │   │   ├── wptextpattern/
│   │   │   │   │   │   │   ├── plugin.js
│   │   │   │   │   │   │   └── plugin.min.js
│   │   │   │   │   │   └── wpview/
│   │   │   │   │   │       ├── plugin.js
│   │   │   │   │   │       └── plugin.min.js
│   │   │   │   │   ├── skins/
│   │   │   │   │   │   ├── lightgray/
│   │   │   │   │   │   │   ├── fonts/
│   │   │   │   │   │   │   │   ├── tinymce-small.eot
│   │   │   │   │   │   │   │   ├── tinymce-small.svg
│   │   │   │   │   │   │   │   ├── tinymce-small.ttf
│   │   │   │   │   │   │   │   ├── tinymce-small.woff
│   │   │   │   │   │   │   │   ├── tinymce.eot
│   │   │   │   │   │   │   │   ├── tinymce.svg
│   │   │   │   │   │   │   │   ├── tinymce.ttf
│   │   │   │   │   │   │   │   └── tinymce.woff
│   │   │   │   │   │   │   ├── img/
│   │   │   │   │   │   │   │   ├── anchor.gif
│   │   │   │   │   │   │   │   ├── loader.gif
│   │   │   │   │   │   │   │   ├── object.gif
│   │   │   │   │   │   │   │   └── trans.gif
│   │   │   │   │   │   │   ├── content.inline.min.css
│   │   │   │   │   │   │   ├── content.min.css
│   │   │   │   │   │   │   └── skin.min.css
│   │   │   │   │   │   └── wordpress/
│   │   │   │   │   │       ├── images/
│   │   │   │   │   │       │   ├── audio.png
│   │   │   │   │   │       │   ├── dashicon-edit.png
│   │   │   │   │   │       │   ├── dashicon-no.png
│   │   │   │   │   │       │   ├── embedded.png
│   │   │   │   │   │       │   ├── gallery-2x.png
│   │   │   │   │   │       │   ├── gallery.png
│   │   │   │   │   │       │   ├── more-2x.png
│   │   │   │   │   │       │   ├── more.png
│   │   │   │   │   │       │   ├── pagebreak-2x.png
│   │   │   │   │   │       │   ├── pagebreak.png
│   │   │   │   │   │       │   ├── playlist-audio.png
│   │   │   │   │   │       │   ├── playlist-video.png
│   │   │   │   │   │       │   ├── script.svg
│   │   │   │   │   │       │   ├── style.svg
│   │   │   │   │   │       │   └── video.png
│   │   │   │   │   │       └── wp-content.css
│   │   │   │   │   ├── themes/
│   │   │   │   │   │   ├── inlite/
│   │   │   │   │   │   │   ├── theme.js
│   │   │   │   │   │   │   └── theme.min.js
│   │   │   │   │   │   └── modern/
│   │   │   │   │   │       ├── theme.js
│   │   │   │   │   │       └── theme.min.js
│   │   │   │   │   ├── utils/
│   │   │   │   │   │   ├── editable_selects.js
│   │   │   │   │   │   ├── form_utils.js
│   │   │   │   │   │   ├── mctabs.js
│   │   │   │   │   │   └── validate.js
│   │   │   │   │   ├── license.txt
│   │   │   │   │   ├── tiny_mce_popup.js
│   │   │   │   │   ├── tinymce.min.js
│   │   │   │   │   ├── wp-tinymce.js
│   │   │   │   │   └── wp-tinymce.php
│   │   │   │   ├── admin-bar.js
│   │   │   │   ├── admin-bar.min.js
│   │   │   │   ├── api-request.js
│   │   │   │   ├── api-request.min.js
│   │   │   │   ├── autosave.js
│   │   │   │   ├── autosave.min.js
│   │   │   │   ├── backbone.js
│   │   │   │   ├── backbone.min.js
│   │   │   │   ├── clipboard.js
│   │   │   │   ├── clipboard.min.js
│   │   │   │   ├── colorpicker.js
│   │   │   │   ├── colorpicker.min.js
│   │   │   │   ├── comment-reply.js
│   │   │   │   ├── comment-reply.min.js
│   │   │   │   ├── customize-base.js
│   │   │   │   ├── customize-base.min.js
│   │   │   │   ├── customize-loader.js
│   │   │   │   ├── customize-loader.min.js
│   │   │   │   ├── customize-models.js
│   │   │   │   ├── customize-models.min.js
│   │   │   │   ├── customize-preview-nav-menus.js
│   │   │   │   ├── customize-preview-nav-menus.min.js
│   │   │   │   ├── customize-preview-widgets.js
│   │   │   │   ├── customize-preview-widgets.min.js
│   │   │   │   ├── customize-preview.js
│   │   │   │   ├── customize-preview.min.js
│   │   │   │   ├── customize-selective-refresh.js
│   │   │   │   ├── customize-selective-refresh.min.js
│   │   │   │   ├── customize-views.js
│   │   │   │   ├── customize-views.min.js
│   │   │   │   ├── heartbeat.js
│   │   │   │   ├── heartbeat.min.js
│   │   │   │   ├── hoverintent-js.min.js
│   │   │   │   ├── hoverIntent.js
│   │   │   │   ├── hoverIntent.min.js
│   │   │   │   ├── imagesloaded.min.js
│   │   │   │   ├── json2.js
│   │   │   │   ├── json2.min.js
│   │   │   │   ├── masonry.min.js
│   │   │   │   ├── mce-view.js
│   │   │   │   ├── mce-view.min.js
│   │   │   │   ├── media-audiovideo.js
│   │   │   │   ├── media-audiovideo.min.js
│   │   │   │   ├── media-editor.js
│   │   │   │   ├── media-editor.min.js
│   │   │   │   ├── media-grid.js
│   │   │   │   ├── media-grid.min.js
│   │   │   │   ├── media-models.js
│   │   │   │   ├── media-models.min.js
│   │   │   │   ├── media-views.js
│   │   │   │   ├── media-views.min.js
│   │   │   │   ├── quicktags.js
│   │   │   │   ├── quicktags.min.js
│   │   │   │   ├── shortcode.js
│   │   │   │   ├── shortcode.min.js
│   │   │   │   ├── swfobject.js
│   │   │   │   ├── swfobject.min.js
│   │   │   │   ├── tw-sack.js
│   │   │   │   ├── tw-sack.min.js
│   │   │   │   ├── twemoji.js
│   │   │   │   ├── twemoji.min.js
│   │   │   │   ├── underscore.js
│   │   │   │   ├── underscore.min.js
│   │   │   │   ├── utils.js
│   │   │   │   ├── utils.min.js
│   │   │   │   ├── wp-ajax-response.js
│   │   │   │   ├── wp-ajax-response.min.js
│   │   │   │   ├── wp-api.js
│   │   │   │   ├── wp-api.min.js
│   │   │   │   ├── wp-auth-check.js
│   │   │   │   ├── wp-auth-check.min.js
│   │   │   │   ├── wp-backbone.js
│   │   │   │   ├── wp-backbone.min.js
│   │   │   │   ├── wp-custom-header.js
│   │   │   │   ├── wp-custom-header.min.js
│   │   │   │   ├── wp-embed-template.js
│   │   │   │   ├── wp-embed-template.min.js
│   │   │   │   ├── wp-embed.js
│   │   │   │   ├── wp-embed.min.js
│   │   │   │   ├── wp-emoji-loader.js
│   │   │   │   ├── wp-emoji-loader.min.js
│   │   │   │   ├── wp-emoji-release.min.js
│   │   │   │   ├── wp-emoji.js
│   │   │   │   ├── wp-emoji.min.js
│   │   │   │   ├── wp-list-revisions.js
│   │   │   │   ├── wp-list-revisions.min.js
│   │   │   │   ├── wp-lists.js
│   │   │   │   ├── wp-lists.min.js
│   │   │   │   ├── wp-pointer.js
│   │   │   │   ├── wp-pointer.min.js
│   │   │   │   ├── wp-sanitize.js
│   │   │   │   ├── wp-sanitize.min.js
│   │   │   │   ├── wp-util.js
│   │   │   │   ├── wp-util.min.js
│   │   │   │   ├── wpdialog.js
│   │   │   │   ├── wpdialog.min.js
│   │   │   │   ├── wplink.js
│   │   │   │   ├── wplink.min.js
│   │   │   │   ├── zxcvbn-async.js
│   │   │   │   ├── zxcvbn-async.min.js
│   │   │   │   └── zxcvbn.min.js
│   │   │   ├── l10n/
│   │   │   │   ├── class-wp-translation-controller.php
│   │   │   │   ├── class-wp-translation-file-mo.php
│   │   │   │   ├── class-wp-translation-file-php.php
│   │   │   │   ├── class-wp-translation-file.php
│   │   │   │   └── class-wp-translations.php
│   │   │   ├── php-compat/
│   │   │   │   └── readonly.php
│   │   │   ├── PHPMailer/
│   │   │   │   ├── DSNConfigurator.php
│   │   │   │   ├── Exception.php
│   │   │   │   ├── OAuth.php
│   │   │   │   ├── OAuthTokenProvider.php
│   │   │   │   ├── PHPMailer.php
│   │   │   │   ├── POP3.php
│   │   │   │   └── SMTP.php
│   │   │   ├── pomo/
│   │   │   │   ├── entry.php
│   │   │   │   ├── mo.php
│   │   │   │   ├── plural-forms.php
│   │   │   │   ├── po.php
│   │   │   │   ├── streams.php
│   │   │   │   └── translations.php
│   │   │   ├── Requests/
│   │   │   │   ├── library/
│   │   │   │   │   └── Requests.php
│   │   │   │   └── src/
│   │   │   │       ├── Auth/
│   │   │   │       │   └── Basic.php
│   │   │   │       ├── Cookie/
│   │   │   │       │   └── Jar.php
│   │   │   │       ├── Exception/
│   │   │   │       │   ├── Http/
│   │   │   │       │   │   ├── Status304.php
│   │   │   │       │   │   ├── Status305.php
│   │   │   │       │   │   ├── Status306.php
│   │   │   │       │   │   ├── Status400.php
│   │   │   │       │   │   ├── Status401.php
│   │   │   │       │   │   ├── Status402.php
│   │   │   │       │   │   ├── Status403.php
│   │   │   │       │   │   ├── Status404.php
│   │   │   │       │   │   ├── Status405.php
│   │   │   │       │   │   ├── Status406.php
│   │   │   │       │   │   ├── Status407.php
│   │   │   │       │   │   ├── Status408.php
│   │   │   │       │   │   ├── Status409.php
│   │   │   │       │   │   ├── Status410.php
│   │   │   │       │   │   ├── Status411.php
│   │   │   │       │   │   ├── Status412.php
│   │   │   │       │   │   ├── Status413.php
│   │   │   │       │   │   ├── Status414.php
│   │   │   │       │   │   ├── Status415.php
│   │   │   │       │   │   ├── Status416.php
│   │   │   │       │   │   ├── Status417.php
│   │   │   │       │   │   ├── Status418.php
│   │   │   │       │   │   ├── Status428.php
│   │   │   │       │   │   ├── Status429.php
│   │   │   │       │   │   ├── Status431.php
│   │   │   │       │   │   ├── Status500.php
│   │   │   │       │   │   ├── Status501.php
│   │   │   │       │   │   ├── Status502.php
│   │   │   │       │   │   ├── Status503.php
│   │   │   │       │   │   ├── Status504.php
│   │   │   │       │   │   ├── Status505.php
│   │   │   │       │   │   ├── Status511.php
│   │   │   │       │   │   └── StatusUnknown.php
│   │   │   │       │   ├── Transport/
│   │   │   │       │   │   └── Curl.php
│   │   │   │       │   ├── ArgumentCount.php
│   │   │   │       │   ├── Http.php
│   │   │   │       │   ├── InvalidArgument.php
│   │   │   │       │   └── Transport.php
│   │   │   │       ├── Proxy/
│   │   │   │       │   └── Http.php
│   │   │   │       ├── Response/
│   │   │   │       │   └── Headers.php
│   │   │   │       ├── Transport/
│   │   │   │       │   ├── Curl.php
│   │   │   │       │   └── Fsockopen.php
│   │   │   │       ├── Utility/
│   │   │   │       │   ├── CaseInsensitiveDictionary.php
│   │   │   │       │   ├── FilteredIterator.php
│   │   │   │       │   └── InputValidator.php
│   │   │   │       ├── Auth.php
│   │   │   │       ├── Autoload.php
│   │   │   │       ├── Capability.php
│   │   │   │       ├── Cookie.php
│   │   │   │       ├── Exception.php
│   │   │   │       ├── HookManager.php
│   │   │   │       ├── Hooks.php
│   │   │   │       ├── IdnaEncoder.php
│   │   │   │       ├── Ipv6.php
│   │   │   │       ├── Iri.php
│   │   │   │       ├── Port.php
│   │   │   │       ├── Proxy.php
│   │   │   │       ├── Requests.php
│   │   │   │       ├── Response.php
│   │   │   │       ├── Session.php
│   │   │   │       ├── Ssl.php
│   │   │   │       └── Transport.php
│   │   │   ├── rest-api/
│   │   │   │   ├── endpoints/
│   │   │   │   │   ├── class-wp-rest-abilities-v1-categories-controller.php
│   │   │   │   │   ├── class-wp-rest-abilities-v1-list-controller.php
│   │   │   │   │   ├── class-wp-rest-abilities-v1-run-controller.php
│   │   │   │   │   ├── class-wp-rest-application-passwords-controller.php
│   │   │   │   │   ├── class-wp-rest-attachments-controller.php
│   │   │   │   │   ├── class-wp-rest-autosaves-controller.php
│   │   │   │   │   ├── class-wp-rest-block-directory-controller.php
│   │   │   │   │   ├── class-wp-rest-block-pattern-categories-controller.php
│   │   │   │   │   ├── class-wp-rest-block-patterns-controller.php
│   │   │   │   │   ├── class-wp-rest-block-renderer-controller.php
│   │   │   │   │   ├── class-wp-rest-block-types-controller.php
│   │   │   │   │   ├── class-wp-rest-blocks-controller.php
│   │   │   │   │   ├── class-wp-rest-comments-controller.php
│   │   │   │   │   ├── class-wp-rest-controller.php
│   │   │   │   │   ├── class-wp-rest-edit-site-export-controller.php
│   │   │   │   │   ├── class-wp-rest-font-collections-controller.php
│   │   │   │   │   ├── class-wp-rest-font-faces-controller.php
│   │   │   │   │   ├── class-wp-rest-font-families-controller.php
│   │   │   │   │   ├── class-wp-rest-global-styles-controller.php
│   │   │   │   │   ├── class-wp-rest-global-styles-revisions-controller.php
│   │   │   │   │   ├── class-wp-rest-menu-items-controller.php
│   │   │   │   │   ├── class-wp-rest-menu-locations-controller.php
│   │   │   │   │   ├── class-wp-rest-menus-controller.php
│   │   │   │   │   ├── class-wp-rest-navigation-fallback-controller.php
│   │   │   │   │   ├── class-wp-rest-pattern-directory-controller.php
│   │   │   │   │   ├── class-wp-rest-plugins-controller.php
│   │   │   │   │   ├── class-wp-rest-post-statuses-controller.php
│   │   │   │   │   ├── class-wp-rest-post-types-controller.php
│   │   │   │   │   ├── class-wp-rest-posts-controller.php
│   │   │   │   │   ├── class-wp-rest-revisions-controller.php
│   │   │   │   │   ├── class-wp-rest-search-controller.php
│   │   │   │   │   ├── class-wp-rest-settings-controller.php
│   │   │   │   │   ├── class-wp-rest-sidebars-controller.php
│   │   │   │   │   ├── class-wp-rest-site-health-controller.php
│   │   │   │   │   ├── class-wp-rest-taxonomies-controller.php
│   │   │   │   │   ├── class-wp-rest-template-autosaves-controller.php
│   │   │   │   │   ├── class-wp-rest-template-revisions-controller.php
│   │   │   │   │   ├── class-wp-rest-templates-controller.php
│   │   │   │   │   ├── class-wp-rest-terms-controller.php
│   │   │   │   │   ├── class-wp-rest-themes-controller.php
│   │   │   │   │   ├── class-wp-rest-url-details-controller.php
│   │   │   │   │   ├── class-wp-rest-users-controller.php
│   │   │   │   │   ├── class-wp-rest-widget-types-controller.php
│   │   │   │   │   └── class-wp-rest-widgets-controller.php
│   │   │   │   ├── fields/
│   │   │   │   │   ├── class-wp-rest-comment-meta-fields.php
│   │   │   │   │   ├── class-wp-rest-meta-fields.php
│   │   │   │   │   ├── class-wp-rest-post-meta-fields.php
│   │   │   │   │   ├── class-wp-rest-term-meta-fields.php
│   │   │   │   │   └── class-wp-rest-user-meta-fields.php
│   │   │   │   ├── search/
│   │   │   │   │   ├── class-wp-rest-post-format-search-handler.php
│   │   │   │   │   ├── class-wp-rest-post-search-handler.php
│   │   │   │   │   ├── class-wp-rest-search-handler.php
│   │   │   │   │   └── class-wp-rest-term-search-handler.php
│   │   │   │   ├── class-wp-rest-request.php
│   │   │   │   ├── class-wp-rest-response.php
│   │   │   │   └── class-wp-rest-server.php
│   │   │   ├── SimplePie/
│   │   │   │   ├── library/
│   │   │   │   │   ├── SimplePie/
│   │   │   │   │   │   ├── Cache/
│   │   │   │   │   │   │   ├── Base.php
│   │   │   │   │   │   │   ├── DB.php
│   │   │   │   │   │   │   ├── File.php
│   │   │   │   │   │   │   ├── Memcache.php
│   │   │   │   │   │   │   ├── Memcached.php
│   │   │   │   │   │   │   ├── MySQL.php
│   │   │   │   │   │   │   └── Redis.php
│   │   │   │   │   │   ├── Content/
│   │   │   │   │   │   │   └── Type/
│   │   │   │   │   │   │       └── Sniffer.php
│   │   │   │   │   │   ├── Decode/
│   │   │   │   │   │   │   └── HTML/
│   │   │   │   │   │   │       └── Entities.php
│   │   │   │   │   │   ├── HTTP/
│   │   │   │   │   │   │   └── Parser.php
│   │   │   │   │   │   ├── Net/
│   │   │   │   │   │   │   └── IPv6.php
│   │   │   │   │   │   ├── Parse/
│   │   │   │   │   │   │   └── Date.php
│   │   │   │   │   │   ├── XML/
│   │   │   │   │   │   │   └── Declaration/
│   │   │   │   │   │   │       └── Parser.php
│   │   │   │   │   │   ├── Author.php
│   │   │   │   │   │   ├── Cache.php
│   │   │   │   │   │   ├── Caption.php
│   │   │   │   │   │   ├── Category.php
│   │   │   │   │   │   ├── Copyright.php
│   │   │   │   │   │   ├── Core.php
│   │   │   │   │   │   ├── Credit.php
│   │   │   │   │   │   ├── Enclosure.php
│   │   │   │   │   │   ├── Exception.php
│   │   │   │   │   │   ├── File.php
│   │   │   │   │   │   ├── gzdecode.php
│   │   │   │   │   │   ├── IRI.php
│   │   │   │   │   │   ├── Item.php
│   │   │   │   │   │   ├── Locator.php
│   │   │   │   │   │   ├── Misc.php
│   │   │   │   │   │   ├── Parser.php
│   │   │   │   │   │   ├── Rating.php
│   │   │   │   │   │   ├── Registry.php
│   │   │   │   │   │   ├── Restriction.php
│   │   │   │   │   │   ├── Sanitize.php
│   │   │   │   │   │   └── Source.php
│   │   │   │   │   └── SimplePie.php
│   │   │   │   ├── src/
│   │   │   │   │   ├── Cache/
│   │   │   │   │   │   ├── Base.php
│   │   │   │   │   │   ├── BaseDataCache.php
│   │   │   │   │   │   ├── CallableNameFilter.php
│   │   │   │   │   │   ├── DataCache.php
│   │   │   │   │   │   ├── DB.php
│   │   │   │   │   │   ├── File.php
│   │   │   │   │   │   ├── Memcache.php
│   │   │   │   │   │   ├── Memcached.php
│   │   │   │   │   │   ├── MySQL.php
│   │   │   │   │   │   ├── NameFilter.php
│   │   │   │   │   │   ├── Psr16.php
│   │   │   │   │   │   └── Redis.php
│   │   │   │   │   ├── Content/
│   │   │   │   │   │   └── Type/
│   │   │   │   │   │       └── Sniffer.php
│   │   │   │   │   ├── HTTP/
│   │   │   │   │   │   ├── Client.php
│   │   │   │   │   │   ├── ClientException.php
│   │   │   │   │   │   ├── FileClient.php
│   │   │   │   │   │   ├── Parser.php
│   │   │   │   │   │   ├── Psr18Client.php
│   │   │   │   │   │   ├── Psr7Response.php
│   │   │   │   │   │   ├── RawTextResponse.php
│   │   │   │   │   │   └── Response.php
│   │   │   │   │   ├── Net/
│   │   │   │   │   │   └── IPv6.php
│   │   │   │   │   ├── Parse/
│   │   │   │   │   │   └── Date.php
│   │   │   │   │   ├── XML/
│   │   │   │   │   │   └── Declaration/
│   │   │   │   │   │       └── Parser.php
│   │   │   │   │   ├── Author.php
│   │   │   │   │   ├── Cache.php
│   │   │   │   │   ├── Caption.php
│   │   │   │   │   ├── Category.php
│   │   │   │   │   ├── Copyright.php
│   │   │   │   │   ├── Credit.php
│   │   │   │   │   ├── Enclosure.php
│   │   │   │   │   ├── Exception.php
│   │   │   │   │   ├── File.php
│   │   │   │   │   ├── Gzdecode.php
│   │   │   │   │   ├── IRI.php
│   │   │   │   │   ├── Item.php
│   │   │   │   │   ├── Locator.php
│   │   │   │   │   ├── Misc.php
│   │   │   │   │   ├── Parser.php
│   │   │   │   │   ├── Rating.php
│   │   │   │   │   ├── Registry.php
│   │   │   │   │   ├── RegistryAware.php
│   │   │   │   │   ├── Restriction.php
│   │   │   │   │   ├── Sanitize.php
│   │   │   │   │   ├── SimplePie.php
│   │   │   │   │   └── Source.php
│   │   │   │   └── autoloader.php
│   │   │   ├── sitemaps/
│   │   │   │   ├── providers/
│   │   │   │   │   ├── class-wp-sitemaps-posts.php
│   │   │   │   │   ├── class-wp-sitemaps-taxonomies.php
│   │   │   │   │   └── class-wp-sitemaps-users.php
│   │   │   │   ├── class-wp-sitemaps-index.php
│   │   │   │   ├── class-wp-sitemaps-provider.php
│   │   │   │   ├── class-wp-sitemaps-registry.php
│   │   │   │   ├── class-wp-sitemaps-renderer.php
│   │   │   │   ├── class-wp-sitemaps-stylesheet.php
│   │   │   │   └── class-wp-sitemaps.php
│   │   │   ├── sodium_compat/
│   │   │   │   ├── lib/
│   │   │   │   │   ├── constants.php
│   │   │   │   │   ├── namespaced.php
│   │   │   │   │   ├── php72compat.php
│   │   │   │   │   ├── php72compat_const.php
│   │   │   │   │   ├── php84compat.php
│   │   │   │   │   ├── php84compat_const.php
│   │   │   │   │   ├── ristretto255.php
│   │   │   │   │   ├── sodium_compat.php
│   │   │   │   │   └── stream-xchacha20.php
│   │   │   │   ├── namespaced/
│   │   │   │   │   ├── Core/
│   │   │   │   │   │   ├── ChaCha20/
│   │   │   │   │   │   │   ├── Ctx.php
│   │   │   │   │   │   │   └── IetfCtx.php
│   │   │   │   │   │   ├── Curve25519/
│   │   │   │   │   │   │   ├── Ge/
│   │   │   │   │   │   │   │   ├── Cached.php
│   │   │   │   │   │   │   │   ├── P1p1.php
│   │   │   │   │   │   │   │   ├── P2.php
│   │   │   │   │   │   │   │   ├── P3.php
│   │   │   │   │   │   │   │   └── Precomp.php
│   │   │   │   │   │   │   ├── Fe.php
│   │   │   │   │   │   │   └── H.php
│   │   │   │   │   │   ├── Poly1305/
│   │   │   │   │   │   │   └── State.php
│   │   │   │   │   │   ├── BLAKE2b.php
│   │   │   │   │   │   ├── ChaCha20.php
│   │   │   │   │   │   ├── Curve25519.php
│   │   │   │   │   │   ├── Ed25519.php
│   │   │   │   │   │   ├── HChaCha20.php
│   │   │   │   │   │   ├── HSalsa20.php
│   │   │   │   │   │   ├── Poly1305.php
│   │   │   │   │   │   ├── Salsa20.php
│   │   │   │   │   │   ├── SipHash.php
│   │   │   │   │   │   ├── Util.php
│   │   │   │   │   │   ├── X25519.php
│   │   │   │   │   │   ├── XChaCha20.php
│   │   │   │   │   │   └── Xsalsa20.php
│   │   │   │   │   ├── Compat.php
│   │   │   │   │   ├── Crypto.php
│   │   │   │   │   └── File.php
│   │   │   │   ├── src/
│   │   │   │   │   ├── Core/
│   │   │   │   │   │   ├── AEGIS/
│   │   │   │   │   │   │   ├── State128L.php
│   │   │   │   │   │   │   └── State256.php
│   │   │   │   │   │   ├── AES/
│   │   │   │   │   │   │   ├── Block.php
│   │   │   │   │   │   │   ├── Expanded.php
│   │   │   │   │   │   │   └── KeySchedule.php
│   │   │   │   │   │   ├── Base64/
│   │   │   │   │   │   │   ├── Original.php
│   │   │   │   │   │   │   └── UrlSafe.php
│   │   │   │   │   │   ├── ChaCha20/
│   │   │   │   │   │   │   ├── Ctx.php
│   │   │   │   │   │   │   └── IetfCtx.php
│   │   │   │   │   │   ├── Curve25519/
│   │   │   │   │   │   │   ├── Ge/
│   │   │   │   │   │   │   │   ├── Cached.php
│   │   │   │   │   │   │   │   ├── P1p1.php
│   │   │   │   │   │   │   │   ├── P2.php
│   │   │   │   │   │   │   │   ├── P3.php
│   │   │   │   │   │   │   │   └── Precomp.php
│   │   │   │   │   │   │   ├── Fe.php
│   │   │   │   │   │   │   ├── H.php
│   │   │   │   │   │   │   └── README.md
│   │   │   │   │   │   ├── Poly1305/
│   │   │   │   │   │   │   └── State.php
│   │   │   │   │   │   ├── SecretStream/
│   │   │   │   │   │   │   └── State.php
│   │   │   │   │   │   ├── AEGIS128L.php
│   │   │   │   │   │   ├── AEGIS256.php
│   │   │   │   │   │   ├── AES.php
│   │   │   │   │   │   ├── BLAKE2b.php
│   │   │   │   │   │   ├── ChaCha20.php
│   │   │   │   │   │   ├── Curve25519.php
│   │   │   │   │   │   ├── Ed25519.php
│   │   │   │   │   │   ├── HChaCha20.php
│   │   │   │   │   │   ├── HSalsa20.php
│   │   │   │   │   │   ├── Poly1305.php
│   │   │   │   │   │   ├── Ristretto255.php
│   │   │   │   │   │   ├── Salsa20.php
│   │   │   │   │   │   ├── SipHash.php
│   │   │   │   │   │   ├── Util.php
│   │   │   │   │   │   ├── X25519.php
│   │   │   │   │   │   ├── XChaCha20.php
│   │   │   │   │   │   └── XSalsa20.php
│   │   │   │   │   ├── Core32/
│   │   │   │   │   │   ├── ChaCha20/
│   │   │   │   │   │   │   ├── Ctx.php
│   │   │   │   │   │   │   └── IetfCtx.php
│   │   │   │   │   │   ├── Curve25519/
│   │   │   │   │   │   │   ├── Ge/
│   │   │   │   │   │   │   │   ├── Cached.php
│   │   │   │   │   │   │   │   ├── P1p1.php
│   │   │   │   │   │   │   │   ├── P2.php
│   │   │   │   │   │   │   │   ├── P3.php
│   │   │   │   │   │   │   │   └── Precomp.php
│   │   │   │   │   │   │   ├── Fe.php
│   │   │   │   │   │   │   ├── H.php
│   │   │   │   │   │   │   └── README.md
│   │   │   │   │   │   ├── Poly1305/
│   │   │   │   │   │   │   └── State.php
│   │   │   │   │   │   ├── SecretStream/
│   │   │   │   │   │   │   └── State.php
│   │   │   │   │   │   ├── BLAKE2b.php
│   │   │   │   │   │   ├── ChaCha20.php
│   │   │   │   │   │   ├── Curve25519.php
│   │   │   │   │   │   ├── Ed25519.php
│   │   │   │   │   │   ├── HChaCha20.php
│   │   │   │   │   │   ├── HSalsa20.php
│   │   │   │   │   │   ├── Int32.php
│   │   │   │   │   │   ├── Int64.php
│   │   │   │   │   │   ├── Poly1305.php
│   │   │   │   │   │   ├── Salsa20.php
│   │   │   │   │   │   ├── SipHash.php
│   │   │   │   │   │   ├── Util.php
│   │   │   │   │   │   ├── X25519.php
│   │   │   │   │   │   ├── XChaCha20.php
│   │   │   │   │   │   └── XSalsa20.php
│   │   │   │   │   ├── PHP52/
│   │   │   │   │   │   └── SplFixedArray.php
│   │   │   │   │   ├── Compat.php
│   │   │   │   │   ├── Crypto.php
│   │   │   │   │   ├── Crypto32.php
│   │   │   │   │   ├── File.php
│   │   │   │   │   └── SodiumException.php
│   │   │   │   ├── autoload-php7.php
│   │   │   │   ├── autoload.php
│   │   │   │   ├── composer.json
│   │   │   │   └── LICENSE
│   │   │   ├── style-engine/
│   │   │   │   ├── class-wp-style-engine-css-declarations.php
│   │   │   │   ├── class-wp-style-engine-css-rule.php
│   │   │   │   ├── class-wp-style-engine-css-rules-store.php
│   │   │   │   ├── class-wp-style-engine-processor.php
│   │   │   │   └── class-wp-style-engine.php
│   │   │   ├── Text/
│   │   │   │   ├── Diff/
│   │   │   │   │   ├── Engine/
│   │   │   │   │   │   ├── native.php
│   │   │   │   │   │   ├── shell.php
│   │   │   │   │   │   ├── string.php
│   │   │   │   │   │   └── xdiff.php
│   │   │   │   │   ├── Renderer/
│   │   │   │   │   │   └── inline.php
│   │   │   │   │   └── Renderer.php
│   │   │   │   ├── Diff.php
│   │   │   │   └── Exception.php
│   │   │   ├── theme-compat/
│   │   │   │   ├── comments.php
│   │   │   │   ├── embed-404.php
│   │   │   │   ├── embed-content.php
│   │   │   │   ├── embed.php
│   │   │   │   ├── footer-embed.php
│   │   │   │   ├── footer.php
│   │   │   │   ├── header-embed.php
│   │   │   │   ├── header.php
│   │   │   │   └── sidebar.php
│   │   │   ├── widgets/
│   │   │   │   ├── class-wp-nav-menu-widget.php
│   │   │   │   ├── class-wp-widget-archives.php
│   │   │   │   ├── class-wp-widget-block.php
│   │   │   │   ├── class-wp-widget-calendar.php
│   │   │   │   ├── class-wp-widget-categories.php
│   │   │   │   ├── class-wp-widget-custom-html.php
│   │   │   │   ├── class-wp-widget-links.php
│   │   │   │   ├── class-wp-widget-media-audio.php
│   │   │   │   ├── class-wp-widget-media-gallery.php
│   │   │   │   ├── class-wp-widget-media-image.php
│   │   │   │   ├── class-wp-widget-media-video.php
│   │   │   │   ├── class-wp-widget-media.php
│   │   │   │   ├── class-wp-widget-meta.php
│   │   │   │   ├── class-wp-widget-pages.php
│   │   │   │   ├── class-wp-widget-recent-comments.php
│   │   │   │   ├── class-wp-widget-recent-posts.php
│   │   │   │   ├── class-wp-widget-rss.php
│   │   │   │   ├── class-wp-widget-search.php
│   │   │   │   ├── class-wp-widget-tag-cloud.php
│   │   │   │   └── class-wp-widget-text.php
│   │   │   ├── abilities-api.php
│   │   │   ├── abilities.php
│   │   │   ├── admin-bar.php
│   │   │   ├── atomlib.php
│   │   │   ├── author-template.php
│   │   │   ├── block-bindings.php
│   │   │   ├── block-editor.php
│   │   │   ├── block-i18n.json
│   │   │   ├── block-patterns.php
│   │   │   ├── block-template-utils.php
│   │   │   ├── block-template.php
│   │   │   ├── blocks.php
│   │   │   ├── bookmark-template.php
│   │   │   ├── bookmark.php
│   │   │   ├── cache-compat.php
│   │   │   ├── cache.php
│   │   │   ├── canonical.php
│   │   │   ├── capabilities.php
│   │   │   ├── category-template.php
│   │   │   ├── category.php
│   │   │   ├── class-avif-info.php
│   │   │   ├── class-feed.php
│   │   │   ├── class-http.php
│   │   │   ├── class-IXR.php
│   │   │   ├── class-json.php
│   │   │   ├── class-oembed.php
│   │   │   ├── class-phpass.php
│   │   │   ├── class-phpmailer.php
│   │   │   ├── class-pop3.php
│   │   │   ├── class-requests.php
│   │   │   ├── class-simplepie.php
│   │   │   ├── class-smtp.php
│   │   │   ├── class-snoopy.php
│   │   │   ├── class-walker-category-dropdown.php
│   │   │   ├── class-walker-category.php
│   │   │   ├── class-walker-comment.php
│   │   │   ├── class-walker-nav-menu.php
│   │   │   ├── class-walker-page-dropdown.php
│   │   │   ├── class-walker-page.php
│   │   │   ├── class-wp-admin-bar.php
│   │   │   ├── class-wp-ajax-response.php
│   │   │   ├── class-wp-application-passwords.php
│   │   │   ├── class-wp-block-bindings-registry.php
│   │   │   ├── class-wp-block-bindings-source.php
│   │   │   ├── class-wp-block-editor-context.php
│   │   │   ├── class-wp-block-list.php
│   │   │   ├── class-wp-block-metadata-registry.php
│   │   │   ├── class-wp-block-parser-block.php
│   │   │   ├── class-wp-block-parser-frame.php
│   │   │   ├── class-wp-block-parser.php
│   │   │   ├── class-wp-block-pattern-categories-registry.php
│   │   │   ├── class-wp-block-patterns-registry.php
│   │   │   ├── class-wp-block-processor.php
│   │   │   ├── class-wp-block-styles-registry.php
│   │   │   ├── class-wp-block-supports.php
│   │   │   ├── class-wp-block-template.php
│   │   │   ├── class-wp-block-templates-registry.php
│   │   │   ├── class-wp-block-type-registry.php
│   │   │   ├── class-wp-block-type.php
│   │   │   ├── class-wp-block.php
│   │   │   ├── class-wp-classic-to-block-menu-converter.php
│   │   │   ├── class-wp-comment-query.php
│   │   │   ├── class-wp-comment.php
│   │   │   ├── class-wp-customize-control.php
│   │   │   ├── class-wp-customize-manager.php
│   │   │   ├── class-wp-customize-nav-menus.php
│   │   │   ├── class-wp-customize-panel.php
│   │   │   ├── class-wp-customize-section.php
│   │   │   ├── class-wp-customize-setting.php
│   │   │   ├── class-wp-customize-widgets.php
│   │   │   ├── class-wp-date-query.php
│   │   │   ├── class-wp-dependencies.php
│   │   │   ├── class-wp-dependency.php
│   │   │   ├── class-wp-duotone.php
│   │   │   ├── class-wp-editor.php
│   │   │   ├── class-wp-embed.php
│   │   │   ├── class-wp-error.php
│   │   │   ├── class-wp-exception.php
│   │   │   ├── class-wp-fatal-error-handler.php
│   │   │   ├── class-wp-feed-cache-transient.php
│   │   │   ├── class-wp-feed-cache.php
│   │   │   ├── class-wp-hook.php
│   │   │   ├── class-wp-http-cookie.php
│   │   │   ├── class-wp-http-curl.php
│   │   │   ├── class-wp-http-encoding.php
│   │   │   ├── class-wp-http-ixr-client.php
│   │   │   ├── class-wp-http-proxy.php
│   │   │   ├── class-wp-http-requests-hooks.php
│   │   │   ├── class-wp-http-requests-response.php
│   │   │   ├── class-wp-http-response.php
│   │   │   ├── class-wp-http-streams.php
│   │   │   ├── class-wp-http.php
│   │   │   ├── class-wp-image-editor-gd.php
│   │   │   ├── class-wp-image-editor-imagick.php
│   │   │   ├── class-wp-image-editor.php
│   │   │   ├── class-wp-list-util.php
│   │   │   ├── class-wp-locale-switcher.php
│   │   │   ├── class-wp-locale.php
│   │   │   ├── class-wp-matchesmapregex.php
│   │   │   ├── class-wp-meta-query.php
│   │   │   ├── class-wp-metadata-lazyloader.php
│   │   │   ├── class-wp-navigation-fallback.php
│   │   │   ├── class-wp-network-query.php
│   │   │   ├── class-wp-network.php
│   │   │   ├── class-wp-object-cache.php
│   │   │   ├── class-wp-oembed-controller.php
│   │   │   ├── class-wp-oembed.php
│   │   │   ├── class-wp-paused-extensions-storage.php
│   │   │   ├── class-wp-phpmailer.php
│   │   │   ├── class-wp-plugin-dependencies.php
│   │   │   ├── class-wp-post-type.php
│   │   │   ├── class-wp-post.php
│   │   │   ├── class-wp-query.php
│   │   │   ├── class-wp-recovery-mode-cookie-service.php
│   │   │   ├── class-wp-recovery-mode-email-service.php
│   │   │   ├── class-wp-recovery-mode-key-service.php
│   │   │   ├── class-wp-recovery-mode-link-service.php
│   │   │   ├── class-wp-recovery-mode.php
│   │   │   ├── class-wp-rewrite.php
│   │   │   ├── class-wp-role.php
│   │   │   ├── class-wp-roles.php
│   │   │   ├── class-wp-script-modules.php
│   │   │   ├── class-wp-scripts.php
│   │   │   ├── class-wp-session-tokens.php
│   │   │   ├── class-wp-simplepie-file.php
│   │   │   ├── class-wp-simplepie-sanitize-kses.php
│   │   │   ├── class-wp-site-query.php
│   │   │   ├── class-wp-site.php
│   │   │   ├── class-wp-speculation-rules.php
│   │   │   ├── class-wp-styles.php
│   │   │   ├── class-wp-tax-query.php
│   │   │   ├── class-wp-taxonomy.php
│   │   │   ├── class-wp-term-query.php
│   │   │   ├── class-wp-term.php
│   │   │   ├── class-wp-text-diff-renderer-inline.php
│   │   │   ├── class-wp-text-diff-renderer-table.php
│   │   │   ├── class-wp-textdomain-registry.php
│   │   │   ├── class-wp-theme-json-data.php
│   │   │   ├── class-wp-theme-json-resolver.php
│   │   │   ├── class-wp-theme-json-schema.php
│   │   │   ├── class-wp-theme-json.php
│   │   │   ├── class-wp-theme.php
│   │   │   ├── class-wp-token-map.php
│   │   │   ├── class-wp-url-pattern-prefixer.php
│   │   │   ├── class-wp-user-meta-session-tokens.php
│   │   │   ├── class-wp-user-query.php
│   │   │   ├── class-wp-user-request.php
│   │   │   ├── class-wp-user.php
│   │   │   ├── class-wp-walker.php
│   │   │   ├── class-wp-widget-factory.php
│   │   │   ├── class-wp-widget.php
│   │   │   ├── class-wp-xmlrpc-server.php
│   │   │   ├── class-wp.php
│   │   │   ├── class-wpdb.php
│   │   │   ├── class.wp-dependencies.php
│   │   │   ├── class.wp-scripts.php
│   │   │   ├── class.wp-styles.php
│   │   │   ├── comment-template.php
│   │   │   ├── comment.php
│   │   │   ├── compat-utf8.php
│   │   │   ├── compat.php
│   │   │   ├── cron.php
│   │   │   ├── date.php
│   │   │   ├── default-constants.php
│   │   │   ├── default-filters.php
│   │   │   ├── default-widgets.php
│   │   │   ├── deprecated.php
│   │   │   ├── embed-template.php
│   │   │   ├── embed.php
│   │   │   ├── error-protection.php
│   │   │   ├── feed-atom-comments.php
│   │   │   ├── feed-atom.php
│   │   │   ├── feed-rdf.php
│   │   │   ├── feed-rss.php
│   │   │   ├── feed-rss2-comments.php
│   │   │   ├── feed-rss2.php
│   │   │   ├── feed.php
│   │   │   ├── fonts.php
│   │   │   ├── formatting.php
│   │   │   ├── functions.php
│   │   │   ├── functions.wp-scripts.php
│   │   │   ├── functions.wp-styles.php
│   │   │   ├── general-template.php
│   │   │   ├── global-styles-and-settings.php
│   │   │   ├── http.php
│   │   │   ├── https-detection.php
│   │   │   ├── https-migration.php
│   │   │   ├── kses.php
│   │   │   ├── l10n.php
│   │   │   ├── link-template.php
│   │   │   ├── load.php
│   │   │   ├── locale.php
│   │   │   ├── media-template.php
│   │   │   ├── media.php
│   │   │   ├── meta.php
│   │   │   ├── ms-blogs.php
│   │   │   ├── ms-default-constants.php
│   │   │   ├── ms-default-filters.php
│   │   │   ├── ms-deprecated.php
│   │   │   ├── ms-files.php
│   │   │   ├── ms-functions.php
│   │   │   ├── ms-load.php
│   │   │   ├── ms-network.php
│   │   │   ├── ms-settings.php
│   │   │   ├── ms-site.php
│   │   │   ├── nav-menu-template.php
│   │   │   ├── nav-menu.php
│   │   │   ├── option.php
│   │   │   ├── pluggable-deprecated.php
│   │   │   ├── pluggable.php
│   │   │   ├── plugin.php
│   │   │   ├── post-formats.php
│   │   │   ├── post-template.php
│   │   │   ├── post-thumbnail-template.php
│   │   │   ├── post.php
│   │   │   ├── query.php
│   │   │   ├── registration-functions.php
│   │   │   ├── registration.php
│   │   │   ├── rest-api.php
│   │   │   ├── revision.php
│   │   │   ├── rewrite.php
│   │   │   ├── robots-template.php
│   │   │   ├── rss-functions.php
│   │   │   ├── rss.php
│   │   │   ├── script-loader.php
│   │   │   ├── script-modules.php
│   │   │   ├── session.php
│   │   │   ├── shortcodes.php
│   │   │   ├── sitemaps.php
│   │   │   ├── speculative-loading.php
│   │   │   ├── spl-autoload-compat.php
│   │   │   ├── style-engine.php
│   │   │   ├── taxonomy.php
│   │   │   ├── template-canvas.php
│   │   │   ├── template-loader.php
│   │   │   ├── template.php
│   │   │   ├── theme-i18n.json
│   │   │   ├── theme-previews.php
│   │   │   ├── theme-templates.php
│   │   │   ├── theme.json
│   │   │   ├── theme.php
│   │   │   ├── update.php
│   │   │   ├── user.php
│   │   │   ├── utf8.php
│   │   │   ├── vars.php
│   │   │   ├── version.php
│   │   │   ├── widgets.php
│   │   │   ├── wp-db.php
│   │   │   └── wp-diff.php
│   │   ├── index.php
│   │   ├── license.txt
│   │   ├── readme.html
│   │   ├── wp-activate.php
│   │   ├── wp-blog-header.php
│   │   ├── wp-comments-post.php
│   │   ├── wp-config-sample.php
│   │   ├── wp-cron.php
│   │   ├── wp-links-opml.php
│   │   ├── wp-load.php
│   │   ├── wp-login.php
│   │   ├── wp-mail.php
│   │   ├── wp-settings.php
│   │   ├── wp-signup.php
│   │   ├── wp-trackback.php
│   │   └── xmlrpc.php
│   ├── .htaccess
│   ├── DO_NOT_MODIFY.txt
│   └── README.md
├── lupo-agents/
│   ├── 0/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       ├── properties.json
│   │   │       └── system_prompt.txt
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 1/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       ├── properties.json
│   │   │       └── system_prompt.txt
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 10/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       ├── properties.json
│   │   │       └── system_prompt.txt
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 11/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       ├── properties.json
│   │   │       └── system_prompt.txt
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 12/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       ├── properties.json
│   │   │       └── system_prompt.txt
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 13/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       ├── properties.json
│   │   │       └── system_prompt.txt
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 14/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       ├── properties.json
│   │   │       └── system_prompt.txt
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   ├── REFLECTIVE_HUMOR_GEOMETRY.md
│   │   └── system_prompt.txt
│   ├── 2/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       ├── properties.json
│   │   │       └── system_prompt.txt
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 20/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       ├── properties.json
│   │   │       └── system_prompt.txt
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 21/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       ├── properties.json
│   │   │       └── system_prompt.txt
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 22/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       ├── properties.json
│   │   │       └── system_prompt.txt
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 23/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       ├── properties.json
│   │   │       └── system_prompt.txt
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 24/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       ├── properties.json
│   │   │       └── system_prompt.txt
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 25/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       ├── properties.json
│   │   │       └── system_prompt.txt
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 26/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       ├── properties.json
│   │   │       └── system_prompt.txt
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 27/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 28/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 29/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 3/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       ├── properties.json
│   │   │       └── system_prompt.txt
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── COUNTING_IN_LIGHT.md
│   │   ├── DIALOG_FORMAT_ENFORCEMENT.md
│   │   ├── DIALOG_REQUEST_TEMPLATE.txt
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 30/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       ├── properties.json
│   │   │       └── system_prompt.txt
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 31/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       ├── properties.json
│   │   │       └── system_prompt.txt
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 32/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       ├── properties.json
│   │   │       └── system_prompt.txt
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 33/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       ├── properties.json
│   │   │       └── system_prompt.txt
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 34/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       ├── properties.json
│   │   │       └── system_prompt.txt
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 35/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 36/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 37/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 38/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 39/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 40/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 41/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 42/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 43/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 44/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 45/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 46/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 47/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 48/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 49/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 50/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 51/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 52/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 53/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 54/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 55/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 56/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 57/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 58/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       ├── properties.json
│   │   │       └── system_prompt.txt
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 59/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 6/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       ├── properties.json
│   │   │       └── system_prompt.txt
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 60/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       ├── properties.json
│   │   │       └── system_prompt.txt
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 61/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 62/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 63/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 64/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 65/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 66/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 67/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 68/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 69/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 70/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 701/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 702/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 703/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 704/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 705/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 706/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 707/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 708/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 709/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 71/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 72/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 73/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 74/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 75/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 76/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 78/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 79/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 80/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 81/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 82/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 83/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 84/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 85/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 86/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 87/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 89/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 90/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 91/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 92/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 93/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 94/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 95/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 96/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 97/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 98/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   ├── 99/
│   │   ├── versions/
│   │   │   └── v1.0.0/
│   │   │       ├── capabilities.json
│   │   │       ├── prompt.txt
│   │   │       └── properties.json
│   │   ├── agent.json
│   │   ├── capabilities.json
│   │   ├── properties.json
│   │   └── system_prompt.txt
│   └── README.md
├── lupo-includes/
│   ├── agents/
│   │   └── agent-loader.php
│   ├── classes/
│   │   ├── AgentAwarenessLayer.php
│   │   ├── CIPAnalyticsEngine.php
│   │   ├── CIPDoctrineRefinementModule.php
│   │   ├── CIPEmotionalGeometryCalibration.php
│   │   ├── CIPEventPipeline.php
│   │   ├── ColorProtocol.php
│   │   ├── ContinuityValidator.php
│   │   ├── DialogHistoryManager.php
│   │   ├── EmergentRoleDiscovery.php
│   │   ├── FirstExpansionPrincipleValidator.php
│   │   ├── GenesisDoctrineValidator.php
│   │   ├── LABSValidator.php
│   │   ├── MetadataExtractor.php
│   │   ├── ReverseShakaUTC2026.php
│   │   ├── TemporalTruthMonitor.php
│   │   ├── TimelineGenerator.php
│   │   └── TOONParser.php
│   ├── css/
│   │   ├── lupo-includes/
│   │   │   └── modules/
│   │   │       └── content/
│   │   ├── src/
│   │   │   ├── collections/
│   │   │   │   ├── collections.css
│   │   │   │   └── saved-collections.css
│   │   │   ├── components/
│   │   │   │   └── components.css
│   │   │   ├── crafty_syntax/
│   │   │   │   └── crafty_syntax.css
│   │   │   ├── main/
│   │   │   │   └── main.css
│   │   │   ├── navigation/
│   │   │   │   └── navigation.css
│   │   │   └── truth/
│   │   │       └── truth.css
│   │   ├── build-css.php
│   │   ├── components.css
│   │   ├── crafty_syntax.css
│   │   ├── main.css
│   │   ├── navigation.css
│   │   └── truth.css
│   ├── Dialog/
│   │   ├── Api/
│   │   │   └── DialogApi.php
│   │   ├── Database/
│   │   │   └── DialogDatabase.php
│   │   └── LLM/
│   │       ├── LLMInterface.php
│   │       └── OpenAIProvider.php
│   ├── DialogChannelMigration/
│   │   ├── ChannelBuilder.php
│   │   ├── DialogParser.php
│   │   ├── MessageBuilder.php
│   │   ├── MigrationOrchestrator.php
│   │   └── ValidationTool.php
│   ├── EmotionalGeometry/
│   │   └── EmotionalGeometryEngine.php
│   ├── functions/
│   │   ├── auth-helpers.php
│   │   ├── auth-ui-helpers.php
│   │   ├── collection-tabs-loader.php
│   │   ├── collection-zero-helpers.php
│   │   ├── identity-helpers.php
│   │   ├── limits_logger.php
│   │   ├── load_atoms.php
│   │   ├── README.md
│   │   ├── redirect-helpers.php
│   │   ├── render-saved-collections.php
│   │   ├── session-compat-5.3.php
│   │   ├── session-helpers.php
│   │   └── upload-handler.php
│   ├── HistoryReconciliation/
│   │   ├── ContinuityValidator.php
│   │   ├── DocumentationGenerator.php
│   │   └── TimelineManager.php
│   ├── images/
│   │   └── iamback.png
│   ├── js/
│   │   ├── jquery/
│   │   │   ├── ui/
│   │   │   │   ├── accordion.js
│   │   │   │   ├── accordion.min.js
│   │   │   │   ├── autocomplete.js
│   │   │   │   ├── autocomplete.min.js
│   │   │   │   ├── button.js
│   │   │   │   ├── button.min.js
│   │   │   │   ├── checkboxradio.js
│   │   │   │   ├── checkboxradio.min.js
│   │   │   │   ├── controlgroup.js
│   │   │   │   ├── controlgroup.min.js
│   │   │   │   ├── core.js
│   │   │   │   ├── core.min.js
│   │   │   │   ├── datepicker.js
│   │   │   │   ├── datepicker.min.js
│   │   │   │   ├── dialog.js
│   │   │   │   ├── dialog.min.js
│   │   │   │   ├── draggable.js
│   │   │   │   ├── draggable.min.js
│   │   │   │   ├── droppable.js
│   │   │   │   ├── droppable.min.js
│   │   │   │   ├── effect-blind.js
│   │   │   │   ├── effect-blind.min.js
│   │   │   │   ├── effect-bounce.js
│   │   │   │   ├── effect-bounce.min.js
│   │   │   │   ├── effect-clip.js
│   │   │   │   ├── effect-clip.min.js
│   │   │   │   ├── effect-drop.js
│   │   │   │   ├── effect-drop.min.js
│   │   │   │   ├── effect-explode.js
│   │   │   │   ├── effect-explode.min.js
│   │   │   │   ├── effect-fade.js
│   │   │   │   ├── effect-fade.min.js
│   │   │   │   ├── effect-fold.js
│   │   │   │   ├── effect-fold.min.js
│   │   │   │   ├── effect-highlight.js
│   │   │   │   ├── effect-highlight.min.js
│   │   │   │   ├── effect-puff.js
│   │   │   │   ├── effect-puff.min.js
│   │   │   │   ├── effect-pulsate.js
│   │   │   │   ├── effect-pulsate.min.js
│   │   │   │   ├── effect-scale.js
│   │   │   │   ├── effect-scale.min.js
│   │   │   │   ├── effect-shake.js
│   │   │   │   ├── effect-shake.min.js
│   │   │   │   ├── effect-size.js
│   │   │   │   ├── effect-size.min.js
│   │   │   │   ├── effect-slide.js
│   │   │   │   ├── effect-slide.min.js
│   │   │   │   ├── effect-transfer.js
│   │   │   │   ├── effect-transfer.min.js
│   │   │   │   ├── effect.js
│   │   │   │   ├── effect.min.js
│   │   │   │   ├── menu.js
│   │   │   │   ├── menu.min.js
│   │   │   │   ├── mouse.js
│   │   │   │   ├── mouse.min.js
│   │   │   │   ├── progressbar.js
│   │   │   │   ├── progressbar.min.js
│   │   │   │   ├── resizable.js
│   │   │   │   ├── resizable.min.js
│   │   │   │   ├── selectable.js
│   │   │   │   ├── selectable.min.js
│   │   │   │   ├── selectmenu.js
│   │   │   │   ├── selectmenu.min.js
│   │   │   │   ├── slider.js
│   │   │   │   ├── slider.min.js
│   │   │   │   ├── sortable.js
│   │   │   │   ├── sortable.min.js
│   │   │   │   ├── spinner.js
│   │   │   │   ├── spinner.min.js
│   │   │   │   ├── tabs.js
│   │   │   │   ├── tabs.min.js
│   │   │   │   ├── tooltip.js
│   │   │   │   └── tooltip.min.js
│   │   │   ├── jquery-migrate.js
│   │   │   ├── jquery-migrate.min.js
│   │   │   ├── jquery.color.min.js
│   │   │   ├── jquery.form.js
│   │   │   ├── jquery.form.min.js
│   │   │   ├── jquery.hotkeys.js
│   │   │   ├── jquery.hotkeys.min.js
│   │   │   ├── jquery.js
│   │   │   ├── jquery.masonry.min.js
│   │   │   ├── jquery.min.js
│   │   │   ├── jquery.query.js
│   │   │   ├── jquery.schedule.js
│   │   │   ├── jquery.serialize-object.js
│   │   │   ├── jquery.table-hotkeys.js
│   │   │   ├── jquery.table-hotkeys.min.js
│   │   │   ├── jquery.ui.touch-punch.js
│   │   │   ├── suggest.js
│   │   │   └── suggest.min.js
│   │   ├── tinymce/
│   │   │   ├── icons/
│   │   │   │   └── default/
│   │   │   │       └── icons.min.js
│   │   │   ├── langs/
│   │   │   │   └── README.md
│   │   │   ├── models/
│   │   │   │   └── dom/
│   │   │   │       └── model.min.js
│   │   │   ├── plugins/
│   │   │   │   ├── accordion/
│   │   │   │   │   └── plugin.min.js
│   │   │   │   ├── advlist/
│   │   │   │   │   └── plugin.min.js
│   │   │   │   ├── anchor/
│   │   │   │   │   └── plugin.min.js
│   │   │   │   ├── autolink/
│   │   │   │   │   └── plugin.min.js
│   │   │   │   ├── autoresize/
│   │   │   │   │   └── plugin.min.js
│   │   │   │   ├── autosave/
│   │   │   │   │   └── plugin.min.js
│   │   │   │   ├── charmap/
│   │   │   │   │   └── plugin.min.js
│   │   │   │   ├── code/
│   │   │   │   │   └── plugin.min.js
│   │   │   │   ├── codesample/
│   │   │   │   │   └── plugin.min.js
│   │   │   │   ├── directionality/
│   │   │   │   │   └── plugin.min.js
│   │   │   │   ├── emoticons/
│   │   │   │   │   ├── js/
│   │   │   │   │   │   ├── emojiimages.js
│   │   │   │   │   │   ├── emojiimages.min.js
│   │   │   │   │   │   ├── emojis.js
│   │   │   │   │   │   └── emojis.min.js
│   │   │   │   │   └── plugin.min.js
│   │   │   │   ├── fullscreen/
│   │   │   │   │   └── plugin.min.js
│   │   │   │   ├── help/
│   │   │   │   │   ├── js/
│   │   │   │   │   │   └── i18n/
│   │   │   │   │   │       └── keynav/
│   │   │   │   │   │           ├── ar.js
│   │   │   │   │   │           ├── bg-BG.js
│   │   │   │   │   │           ├── bg_BG.js
│   │   │   │   │   │           ├── ca.js
│   │   │   │   │   │           ├── cs.js
│   │   │   │   │   │           ├── da.js
│   │   │   │   │   │           ├── de.js
│   │   │   │   │   │           ├── el.js
│   │   │   │   │   │           ├── en.js
│   │   │   │   │   │           ├── es.js
│   │   │   │   │   │           ├── eu.js
│   │   │   │   │   │           ├── fa.js
│   │   │   │   │   │           ├── fi.js
│   │   │   │   │   │           ├── fr-FR.js
│   │   │   │   │   │           ├── fr_FR.js
│   │   │   │   │   │           ├── he-IL.js
│   │   │   │   │   │           ├── he_IL.js
│   │   │   │   │   │           ├── hi.js
│   │   │   │   │   │           ├── hr.js
│   │   │   │   │   │           ├── hu-HU.js
│   │   │   │   │   │           ├── hu_HU.js
│   │   │   │   │   │           ├── id.js
│   │   │   │   │   │           ├── it.js
│   │   │   │   │   │           ├── ja.js
│   │   │   │   │   │           ├── kk.js
│   │   │   │   │   │           ├── ko-KR.js
│   │   │   │   │   │           ├── ko_KR.js
│   │   │   │   │   │           ├── ms.js
│   │   │   │   │   │           ├── nb-NO.js
│   │   │   │   │   │           ├── nb_NO.js
│   │   │   │   │   │           ├── nl.js
│   │   │   │   │   │           ├── pl.js
│   │   │   │   │   │           ├── pt-BR.js
│   │   │   │   │   │           ├── pt-PT.js
│   │   │   │   │   │           ├── pt_BR.js
│   │   │   │   │   │           ├── pt_PT.js
│   │   │   │   │   │           ├── ro.js
│   │   │   │   │   │           ├── ru.js
│   │   │   │   │   │           ├── sk.js
│   │   │   │   │   │           ├── sl-SI.js
│   │   │   │   │   │           ├── sl_SI.js
│   │   │   │   │   │           ├── sv-SE.js
│   │   │   │   │   │           ├── sv_SE.js
│   │   │   │   │   │           ├── th-TH.js
│   │   │   │   │   │           ├── th_TH.js
│   │   │   │   │   │           ├── tr.js
│   │   │   │   │   │           ├── uk.js
│   │   │   │   │   │           ├── vi.js
│   │   │   │   │   │           ├── zh-CN.js
│   │   │   │   │   │           ├── zh-TW.js
│   │   │   │   │   │           ├── zh_CN.js
│   │   │   │   │   │           └── zh_TW.js
│   │   │   │   │   └── plugin.min.js
│   │   │   │   ├── image/
│   │   │   │   │   └── plugin.min.js
│   │   │   │   ├── importcss/
│   │   │   │   │   └── plugin.min.js
│   │   │   │   ├── insertdatetime/
│   │   │   │   │   └── plugin.min.js
│   │   │   │   ├── link/
│   │   │   │   │   └── plugin.min.js
│   │   │   │   ├── lists/
│   │   │   │   │   └── plugin.min.js
│   │   │   │   ├── media/
│   │   │   │   │   └── plugin.min.js
│   │   │   │   ├── nonbreaking/
│   │   │   │   │   └── plugin.min.js
│   │   │   │   ├── pagebreak/
│   │   │   │   │   └── plugin.min.js
│   │   │   │   ├── preview/
│   │   │   │   │   └── plugin.min.js
│   │   │   │   ├── quickbars/
│   │   │   │   │   └── plugin.min.js
│   │   │   │   ├── save/
│   │   │   │   │   └── plugin.min.js
│   │   │   │   ├── searchreplace/
│   │   │   │   │   └── plugin.min.js
│   │   │   │   ├── table/
│   │   │   │   │   └── plugin.min.js
│   │   │   │   ├── visualblocks/
│   │   │   │   │   └── plugin.min.js
│   │   │   │   ├── visualchars/
│   │   │   │   │   └── plugin.min.js
│   │   │   │   └── wordcount/
│   │   │   │       └── plugin.min.js
│   │   │   ├── skins/
│   │   │   │   ├── content/
│   │   │   │   │   ├── dark/
│   │   │   │   │   │   ├── content.js
│   │   │   │   │   │   └── content.min.css
│   │   │   │   │   ├── default/
│   │   │   │   │   │   ├── content.js
│   │   │   │   │   │   └── content.min.css
│   │   │   │   │   ├── document/
│   │   │   │   │   │   ├── content.js
│   │   │   │   │   │   └── content.min.css
│   │   │   │   │   ├── tinymce-5/
│   │   │   │   │   │   ├── content.js
│   │   │   │   │   │   └── content.min.css
│   │   │   │   │   ├── tinymce-5-dark/
│   │   │   │   │   │   ├── content.js
│   │   │   │   │   │   └── content.min.css
│   │   │   │   │   └── writer/
│   │   │   │   │       ├── content.js
│   │   │   │   │       └── content.min.css
│   │   │   │   └── ui/
│   │   │   │       ├── oxide/
│   │   │   │       │   ├── content.inline.js
│   │   │   │       │   ├── content.inline.min.css
│   │   │   │       │   ├── content.js
│   │   │   │       │   ├── content.min.css
│   │   │   │       │   ├── skin.js
│   │   │   │       │   ├── skin.min.css
│   │   │   │       │   ├── skin.shadowdom.js
│   │   │   │       │   └── skin.shadowdom.min.css
│   │   │   │       ├── oxide-dark/
│   │   │   │       │   ├── content.inline.js
│   │   │   │       │   ├── content.inline.min.css
│   │   │   │       │   ├── content.js
│   │   │   │       │   ├── content.min.css
│   │   │   │       │   ├── skin.js
│   │   │   │       │   ├── skin.min.css
│   │   │   │       │   ├── skin.shadowdom.js
│   │   │   │       │   └── skin.shadowdom.min.css
│   │   │   │       ├── tinymce-5/
│   │   │   │       │   ├── content.inline.js
│   │   │   │       │   ├── content.inline.min.css
│   │   │   │       │   ├── content.js
│   │   │   │       │   ├── content.min.css
│   │   │   │       │   ├── skin.js
│   │   │   │       │   ├── skin.min.css
│   │   │   │       │   ├── skin.shadowdom.js
│   │   │   │       │   └── skin.shadowdom.min.css
│   │   │   │       └── tinymce-5-dark/
│   │   │   │           ├── content.inline.js
│   │   │   │           ├── content.inline.min.css
│   │   │   │           ├── content.js
│   │   │   │           ├── content.min.css
│   │   │   │           ├── skin.js
│   │   │   │           ├── skin.min.css
│   │   │   │           ├── skin.shadowdom.js
│   │   │   │           └── skin.shadowdom.min.css
│   │   │   ├── themes/
│   │   │   │   └── silver/
│   │   │   │       └── theme.min.js
│   │   │   ├── license.md
│   │   │   ├── notices.txt
│   │   │   ├── tinymce.d.ts
│   │   │   └── tinymce.min.js
│   │   ├── crafty_syntax_eyes.js
│   │   ├── dynlayer.js
│   │   ├── legacy_dynlayer.js
│   │   ├── legacy_staticmenu.js
│   │   ├── legacy_xlayer.js
│   │   ├── legacy_xmouse.js
│   │   └── navigation.js
│   ├── KIP/
│   │   ├── KIPEngine.php
│   │   └── KIPValidator.php
│   ├── MigrationOrchestrator/
│   │   ├── Exceptions/
│   │   │   └── StateTransitionException.php
│   │   ├── Models/
│   │   │   └── Migration.php
│   │   ├── State/
│   │   │   ├── AbstractState.php
│   │   │   ├── CompletingState.php
│   │   │   ├── FailedState.php
│   │   │   ├── IdleState.php
│   │   │   ├── MigratingState.php
│   │   │   ├── PreparingState.php
│   │   │   ├── RollingBackState.php
│   │   │   ├── StateInterface.php
│   │   │   ├── ValidatingPostState.php
│   │   │   └── ValidatingPreState.php
│   │   ├── DoctrineValidator.php
│   │   ├── LoggerInterface.php
│   │   ├── Orchestrator.php
│   │   └── StateTransitionRecorder.php
│   ├── models/
│   │   └── GroundedAgentModel.php
│   ├── modules/
│   │   ├── actors/
│   │   │   ├── views/
│   │   │   │   ├── layout-topnav.php
│   │   │   │   └── my-profile.php
│   │   │   ├── actors-controller.php
│   │   │   └── my-profile.css
│   │   ├── auth/
│   │   │   ├── auth-controller.php
│   │   │   └── auth-renderer.php
│   │   ├── channels/
│   │   │   ├── cache/
│   │   │   ├── views/
│   │   │   │   ├── partials/
│   │   │   │   │   ├── _chat_panel.php
│   │   │   │   │   ├── _composer.php
│   │   │   │   │   ├── _message_stream.php
│   │   │   │   │   ├── _operators_visitors.php
│   │   │   │   │   ├── _thread_panels_stack.php
│   │   │   │   │   ├── _threads_list.php
│   │   │   │   │   └── _typing_preview_panel.php
│   │   │   │   ├── channel-log.php
│   │   │   │   └── show.php
│   │   │   ├── channel-check-api.php
│   │   │   ├── channel-interface.css
│   │   │   ├── channel-messages-api.php
│   │   │   ├── channel-send-api.php
│   │   │   ├── channel-typing-api.php
│   │   │   ├── channels-controller.php
│   │   │   ├── operator-accept-visitor-api.php
│   │   │   └── operator-pending-visitors-api.php
│   │   ├── content/
│   │   │   ├── renderers/
│   │   │   │   ├── content-renderer.php
│   │   │   │   ├── render-atom.php
│   │   │   │   ├── render-html.php
│   │   │   │   ├── render-json.php
│   │   │   │   └── render-markdown.php
│   │   │   ├── templates/
│   │   │   │   └── content-page.php
│   │   │   ├── content-controller.php
│   │   │   ├── content-model.php
│   │   │   ├── edge-controller.php
│   │   │   └── lookup-helpers.php
│   │   ├── crafty_syntax/
│   │   │   ├── choosedepartment.php
│   │   │   ├── crafty_syntax-controller.php
│   │   │   ├── CRAFTY_SYNTAX_SQL_TOON_REPORT.md
│   │   │   ├── livehelp-js.php
│   │   │   ├── livehelp.php
│   │   │   ├── visitor-chat-stream.php
│   │   │   ├── visitor-image.php
│   │   │   └── visitor-session-helper.php
│   │   ├── help/
│   │   │   ├── views/
│   │   │   │   ├── 404.php
│   │   │   │   ├── index.php
│   │   │   │   ├── search.php
│   │   │   │   └── topic.php
│   │   │   ├── help-controller.php
│   │   │   ├── help-controller_old.php
│   │   │   └── help-model.php
│   │   ├── list/
│   │   │   ├── views/
│   │   │   │   ├── 404.php
│   │   │   │   ├── entity.php
│   │   │   │   └── index.php
│   │   │   └── list-controller.php
│   │   ├── operator/
│   │   │   └── views/
│   │   │       └── signon.php
│   │   ├── qa/
│   │   │   └── views/
│   │   │       ├── index.php
│   │   │       └── question.php
│   │   ├── truth/
│   │   │   ├── templates/
│   │   │   │   └── truth-page.php
│   │   │   ├── truth-controller.php
│   │   │   ├── truth-model.php
│   │   │   └── truth-render.php
│   │   ├── module-loader.php
│   │   └── module-registry.php
│   ├── Pack/
│   │   ├── Behavior/
│   │   │   └── PackBehaviorEngine.php
│   │   ├── Memory/
│   │   │   └── PackMemoryEngine.php
│   │   ├── Sync/
│   │   │   └── PackSyncEngine.php
│   │   ├── PackContext.php
│   │   ├── PackHandoffProtocol.php
│   │   └── PackRegistry.php
│   ├── Quantum/
│   ├── rest-api/
│   │   └── rest-loader.php
│   ├── security/
│   │   └── password-hash.php
│   ├── semantic/
│   │   └── semantic-loader.php
│   ├── src/
│   ├── system/
│   │   └── logging/
│   │       └── ArchitectLogger.php
│   ├── theme/
│   │   └── theme-loader.php
│   ├── themes/
│   │   ├── basic/
│   │   │   ├── assets/
│   │   │   │   └── .gitkeep
│   │   │   ├── components/
│   │   │   │   └── .gitkeep
│   │   │   ├── css/
│   │   │   │   └── src/
│   │   │   │       └── .gitkeep
│   │   │   └── layouts/
│   │   │       └── .gitkeep
│   │   └── default/
│   │       ├── assets/
│   │       │   └── .gitkeep
│   │       ├── components/
│   │       │   ├── .gitkeep
│   │       │   ├── collection_selector.php
│   │       │   ├── collection_tabs.php
│   │       │   ├── collection_tabs_horizontal.php
│   │       │   ├── collections_dropdown.php
│   │       │   ├── content_outline.php
│   │       │   ├── default_tabs.php
│   │       │   ├── footer.php
│   │       │   ├── README.md
│   │       │   ├── saved-collections-nav.php
│   │       │   ├── semantic_map.php
│   │       │   ├── semantic_panel.php
│   │       │   └── topbar.php
│   │       ├── css/
│   │       │   └── src/
│   │       │       └── .gitkeep
│   │       └── layouts/
│   │           ├── .gitkeep
│   │           └── main_layout.php
│   ├── ui/
│   │   ├── components/
│   │   ├── layouts/
│   │   └── ui-loader.php
│   ├── bootstrap.php
│   ├── calss-thoth_topic.php
│   ├── class-AgentEmotionGovernor.php
│   ├── class-AgentEmotionInterpreter.php
│   ├── class-AgentEmotionRepository.php
│   ├── class-AgentEmotionRuntime.php
│   ├── class-AgentEmotionState.php
│   ├── class-AgentReactAPI.php
│   ├── class-anibus.php
│   ├── class-caduceus.php
│   ├── class-carmen.php
│   ├── class-chronos.php
│   ├── class-ConnectionsService.php
│   ├── class-DatabaseFactory.php
│   ├── class-dialog-manager.php
│   ├── class-federationsync.php
│   ├── class-hermes.php
│   ├── class-iris.php
│   ├── class-metis.php
│   ├── class-pdo_db.php
│   ├── class-ReactActionDispatcher.php
│   ├── class-ReactActionRequest.php
│   ├── class-ReactActionValidator.php
│   ├── class-rose.php
│   ├── class-SearchIndexer.php
│   ├── class-semanticextration.php
│   ├── class-SessionManager.php
│   ├── class-thoth.php
│   ├── class-thothclaim.php
│   ├── class-thothclaimevidence.php
│   ├── class-thothclaimscore.php
│   ├── class-timestamp_ymdhis.php
│   ├── class-wolfmind.php
│   ├── footer.php
│   ├── functions-core.php
│   ├── header.php
│   ├── lupopedia-loader.php
│   ├── lupopedia-setup.php
│   ├── schema-config.php
│   └── version.php
├── lupo-tests/
│   ├── BigRock2MetadataTest.php
│   ├── BigRock3ColorProtocolTest.php
│   ├── ContinuityValidatorTest.php
│   ├── HistoryReconciliationIntegrationTest.php
│   ├── TemporalFrameCompatibilityTest.php
│   ├── TemporalPathologySimulation.php
│   ├── WOLFIE_v0_5_Essential_Test_Suite.php
│   └── WOLFIE_v0_5_IntegrationTest.php
├── migrations/
│   ├── 2026_01_22_001_unified_auth_tables.php
│   ├── 2026_01_22_002_unified_auth_tables.sql
│   ├── 2026_01_24_01_add_custom_path_to_lupo_contents.php
│   ├── 2026_01_24_02_add_semantic_aliases_and_overlays.php
│   ├── 2026_01_25_01_prefix_normalization_noop.sql
│   ├── 2026_01_27_01_mood_rgb_doctrine_alignment.sql
│   ├── 2026_01_27_02_mood_registry_and_assignments.sql
│   ├── 2026_01_27_03_emotional_constellations.sql
│   ├── 2026_01_27_04_emotional_constellation_tables.sql
│   ├── 2026_01_28_01_add_gov_channel.sql
│   ├── 2026_01_28_02_link_gov_no_ads.sql
│   ├── 2026_01_28_03_link_gov_governance_doctrines.sql
│   ├── 2026_01_28_04_link_gov_canonical_governance_doctrines.sql
│   ├── 2026_01_28_05_link_gov_new_doctrines.sql
│   ├── 2026_01_28_06_seed_gov_constitutional_thread.sql
│   ├── 2026_01_28_07_link_gov_channel_docs.sql
│   ├── 2026_01_28_08_seed_gov_constitutional_thread.sql
│   ├── 2026_01_28_09_seed_gov_constitutional_message.sql
│   ├── 4.2.5_insert_pack_survival_guide_tldnr.sql
│   ├── agents_table_migration_3_0_26.sql
│   ├── ai_agents_content_migration_2026_1_0_5.sql
│   ├── channel_roles_escalations_toon_alignment.sql
│   ├── doctrine_cursor_tab_mapping.sql
│   ├── doctrine_versioning_tab_mapping_3_0_26.sql
│   ├── ephemeral_schema_3_0_25.sql
│   ├── ephemeral_schema_3_0_25_cleanup.sql
│   ├── execution_sequence_3_0_30.sql
│   ├── migration_orchestrator_schema_3_0_25.sql
│   ├── migration_orchestrator_schema_3_0_25_cleanup.sql
│   ├── README.md
│   ├── structural_alignment_mysql_migration.sql
│   ├── toon_aligned_dialog_channels.sql
│   ├── toon_files_rollback.sql
│   ├── toon_files_tab_mapping.sql
│   ├── toon_files_validation.sql
│   └── verification_queries_3_0_30.sql
├── prompts/
│   ├── 4.1.16_cursor_complete.txt
│   ├── 4.1.16_cursor_instruction.txt
│   ├── 4.1.20_cursor_complete.txt
│   └── doctrine_verification_prompt.txt
├── reports_for_boss/
│   └── 20260223.md
├── routes/
│   ├── auth.php
│   ├── auth_routes.php
│   ├── emotion.php
│   ├── pack.php
│   ├── pack_behavior.php
│   ├── pack_memory.php
│   ├── pack_sync.php
│   ├── system.php
│   ├── terminal.php
│   └── terminal_routes.php
├── schema/
│   ├── lupo_actors.toon
│   ├── lupo_agents.toon
│   ├── lupo_labs_declarations.toon
│   └── lupo_labs_violations.toon
├── scripts/
│   ├── __pycache__/
│   │   ├── actor_agent_doctrine.cpython-313.pyc
│   │   ├── actor_agent_doctrine.cpython-313.pyc.2856647768032
│   │   ├── db_config.cpython-313.pyc
│   │   └── db_config.cpython-313.pyc.2856678270512
│   ├── actor_agent_doctrine.py
│   ├── add_architect_to_docs.php
│   ├── analyze_missing_tables.py
│   ├── analyze_toon_schema_gaps.py
│   ├── audit_toon_reserved_words.py
│   ├── bulk_update_headers_4_1_6.md
│   ├── check_toon_doctrine_alignment.py
│   ├── cleanup_livehelp_toons.py
│   ├── cleanup_old_directories.php
│   ├── cleanup_old_directories.py
│   ├── create_monday_wolfie_changelog.py
│   ├── db_config.py
│   ├── dialogs_db.py
│   ├── export_channel_snapshots.py
│   ├── fix_migration_semicolons.py
│   ├── generate_alter_statements.py
│   ├── generate_canonical_header.php
│   ├── generate_clean_migration.py
│   ├── generate_directory_tree.py
│   ├── generate_headers.py
│   ├── generate_install_sql.py
│   ├── generate_schema_alignment_migration.py
│   ├── generate_seed_from_toons.py
│   ├── generate_toon_files.py
│   ├── import_os.py
│   ├── import_os_fixed.py
│   ├── insert_changelog_entry.php
│   ├── migrate_filesystem_to_db.php
│   ├── migrate_filesystem_to_db.py
│   ├── migrate_user_mappings.php
│   ├── migrate_wolfie_headers_to_db.php
│   ├── normalize_version_4_0_x_to_3_0_x.py
│   ├── pre-commit-hook.bat
│   ├── pre-commit-hook.sh
│   ├── PYTHON_VS_PHP.md
│   ├── README_migration.md
│   ├── README_PYTHON.md
│   ├── rebuild_lupo_contents.py
│   ├── regenerate_toons_docs.py
│   ├── requirements.txt
│   ├── run_labs_handshake.php
│   ├── run_migration_4_1_6.php
│   ├── setup_help_list_modules.php
│   ├── test_labs_validation.php
│   ├── test_migration_syntax.py
│   ├── update_dialog_headers.php
│   ├── update_headers_to_4_1_6.php
│   ├── update_help_topics.php
│   ├── validate_doc_headers.php
│   ├── validate_identity_propagation.php
│   ├── validate_livehelp_import.py
│   ├── validate_migration.py
│   ├── validate_tab_mappings.php
│   ├── validate_toon_files.php
│   ├── verify_architecture_files.php
│   ├── verify_grounded_architecture.php
│   └── wolfie_orms.py
├── storage/
├── templates/
│   └── canonical_wolfie_header_template.yaml
├── tests/
│   └── integration/
│       ├── DialogSystemTest.php
│       ├── LimitsEnforcementTest.php
│       ├── TerminalAITest.php
│       └── TriggerReplacementTest.php
├── uploads/
│   ├── actors/
│   ├── agents/
│   │   └── 2026/
│   │       └── 01/
│   │           ├── 004eb0827e19248c693ea3a5db32c33bb5b5f60595e3babb3d01a6cc3e9ae49e.json
│   │           ├── 0273ec63c031782ce46b87a766883b3b8bd166fd6e0b08c593621043ecf85d92.txt
│   │           ├── 02ea66d04e24d1c70d8a5ff84ad400eaaac3c72a6a2e79045a7de535a1f65dbe.md
│   │           ├── 0efab9fd354e57c28e8aeacfdb06d507254ed650b6ab30ed4170b1cd6d9cebb2.txt
│   │           ├── 0fa0b4518df7974d61567c10393b14ead623c24c489edb75481fde590d0851c0.md
│   │           ├── 1141a8ba50fbb292a71c5c15333d186d9824dfa25c0bda8130560254ce689941.txt
│   │           ├── 12827ca8008a474eb2e5e6a7caf1f18c10e062848dfff9ab6bfce6f7c15c88e2.txt
│   │           ├── 1c50b458a8e38accf28f626ff61c64ba728fcb11ba2070caa03dd0c798be36c0.json
│   │           ├── 1d4dc8ff6cd7e5043dabe13c4bfabd7c890b2428d4d9b507f8f8dc89509fbbeb.txt
│   │           ├── 28c2f9cee72c50b7493540ffd826080b33e76897b363767df9233dcb3f9e3536.md
│   │           ├── 29342a9d1a6a0ef984e3a57c1a860d6b93d8ac8c9e0312bec2e2d9d605ca0bc5.md
│   │           ├── 32d7b7a85a55002c62047e8daa291e62590c17e22baf13fa2e7c6c7ec239b970.md
│   │           ├── 35924f3a7f27bd3ce509943d166b37151ab7e059ff2b6eab60bc5b49b0b1c58b.txt
│   │           ├── 366a7cf9409a1a8109230e0bc4a0076bd8f36a079002be7198e4c90398c14ec9.md
│   │           ├── 49a6406d35227cfb650a1f1f1cbc9f9cc5255d17fd2426c29eb0744ae98dbfea.md
│   │           ├── 4febc2f3fe48889a4481bcf501c8e6c6b688e8cb27d4a7154a065886aa2f8728.txt
│   │           ├── 519b84cb9b97534065eb04b14b092a5b8b3de87a8b24ced6142af8b07b5df122.json
│   │           ├── 52f0c64d6b7aa157a5fe42fb67a1f151871236e8f3a375e3e273f19b9866114a.md
│   │           ├── 589877ce50f4dd18d21232bcb029011737e3213e54b9cad56b06288b4147e97e.json
│   │           ├── 5a60994257116f21ee47b330396d151d54836c7f47073d1f021b46644c67490f.txt
│   │           ├── 5a7471a9b749d7d6c93faba5006a42ffb78e431ad4d5b5a4f17a63c5dba846ed.json
│   │           ├── 5b11ebb4658d5c376d41bd30aefc6917852bcad2911661ee9b0e3b34a0c533a7.md
│   │           ├── 5db65d2e89845255af03d350914212148d9fa18c1c093337ec0f04b8647a32f7.md
│   │           ├── 62562c4a66e89e79fcf885d05eb55faf4b07d929f86c9ebc58da63f7a53d2b4e.json
│   │           ├── 6375eefe3b31dfc089892351369300f26d00440ef6311bb1621cbae3e413eb62.md
│   │           ├── 6c62c9dcc89ca084eb890cd676a7a90b90cc4e72fd3b4538b947edbbda84522a.json
│   │           ├── 743b96a26359bb7f17ec37a2df6da14adff0b718a1919ff0e36a4cb3bf7a1b71.txt
│   │           ├── 772264c1ab5dbb522977582af8fdc2ab26b5b6aa3c9d1d09b4bcf72aea4b1f91.txt
│   │           ├── 79130a0883e26bce3dc70cffb4be6a12e86e8da92e7cedb169ec49444cd9c7e2.md
│   │           ├── 79f9b4f132775b9e888a763e6907b1305df75b097819c46824f2b534bbae6ea3.txt
│   │           ├── 7ad29fc3a62aeca3453c9d3de2902fd1b05adbdc8dc3c47ffb5165a106339ac3.txt
│   │           ├── 81416c3f1fcfb31b51d3324893a7e73e82e7c9df22163f0807b75e7533035291.txt
│   │           ├── 83e959e10115bfbfbf4f2304021e8ec817a13baaf37e61b1713d98a57b7b416e.txt
│   │           ├── 8546ed0d052a958d9a59c7f562f6360776915f1b8cfd7cb16de03b4f493a6f2a.txt
│   │           ├── 883c04f84f0f68e86a1bf49f0c436c6aa54c0e5a33849540cb2d179788237b1d.json
│   │           ├── 8b960be7eb00828d245df04258a906e6232a801e8fb65ff4bddfc1c22b1a135c.md
│   │           ├── 8bc7ad249d1ef0548dfa092ebc742206026f76e4469f922d75a52965b0e15cc8.json
│   │           ├── 91ec4720ca4d956f86dc8b069b1e85e31aca942ff41577b5e32d17f644e0f07a.txt
│   │           ├── 92f2c182cfe37ca4427bb29cd3b86f23dc6575d073187c6b872a771c09cefbb3.txt
│   │           ├── 9648e2a8c0c0241fc3890dea3f45cade0e2c9aaae8bea619acad75b6afe4f8d8.json
│   │           ├── 98911bf042da3e35b90219b896e0dd7b520a4bd5f425068e31f682bd79be29a0.txt
│   │           ├── 98f82a479a4eef5cc5ccf977720e519df4a375afde0006b4338be9b4dbf1dd19.json
│   │           ├── a00f609de1a4db7b80122c3fb2be4c886345d2f62b3289c0989d873af19609ab.txt
│   │           ├── a910407ef3089064312dae4b7c730b9fe3ad967eea63e45426a0cc66dc9efd8d.json
│   │           ├── ad05ad309bd96e07a1b9d528d72d17c3a7f33a29bbef380e920aed1e6fd491da.json
│   │           ├── aeea636d26514853bb002b6dffd85b7eb8f195a3857e22739f99b4136e916bcb.json
│   │           ├── b0ba2518930ac3ddf697e9c82b9ab136e6d717c4810a3acacc00a5d91b11731d.md
│   │           ├── b185c46c36f3746740d9c4b826781a7581105a1b3b442e1c7f9971156dec3b01.md
│   │           ├── b8b5d693bf725f5578494482216c61ca4e2c72e34461067fde9a318a3e7c7c43.md
│   │           ├── bcdf52b654999b626c42c214619f6372520de9875d5b5fa26056ff130d03419b.md
│   │           ├── bf0f35e81062b439003d5dedb5e5e8b57c163b2f2296fa6265a8f13471eef770.json
│   │           ├── c17005fc4238cfbe8f4df147e3a8722e6420deb1f5ae9520bdf07e819b40faa7.md
│   │           ├── c4795d6ed2e7f91c961f1538ccc9c1168eb6c132b7a338082083f65cc5eefcc1.md
│   │           ├── cdb1be328fe755a0c4a26619dd410b78ce0257084345d105c9e133e727090791.md
│   │           ├── cfed8c1b1913c62a44753efe14d7cf8339547c6f84ab3ad5ef48a1147e1d4f47.json
│   │           ├── d19f17c7387b7643580ca445cc0db4de672ec3e0da7617df2d4f7e0f9a492e1c.txt
│   │           ├── d4c1ac03093454f4e0b67fb0beefeda1c497bd8a17b889644609ae949f970231.txt
│   │           ├── dee385134d9c70004e0111b963fe571b1ad5727979641127958e916d75f475f8.json
│   │           ├── df69d07ade28816a9529eddccd304d853047c70ecbe1be1ef7869ff4972e170c.md
│   │           ├── e175f96a3d1148329700ba0f556f0ea874817904966654067bba9e0f277f8e83.json
│   │           ├── e26055d628a035ea331b25bfe1685b1f34420ff4476cb7b740bd56674fab0d29.json
│   │           ├── e52f4d6490819e96dd5deae34ca68c87dceb455e6e587c6be23d0aa61e268235.md
│   │           ├── e53d13a8bc5ab730398073b3704b36a647c67c548aead9f0b1cb2b4e0cf7a236.json
│   │           ├── eae720609e55a678d97ca9882a1395a95832b699745ec3d5e96615e419b76587.json
│   │           ├── f0c48f4a831257ef66e2ff13079778f53852cfcabd5d65c50db47cb57d16cc1f.md
│   │           ├── f5b8ca6c7b0247d310360b668789477d3b9468bd29bf389f8da370728b0df475.txt
│   │           ├── f8f4c6be2a5e3cc3e8bb3f86f23d24765f4d2e9e81dbfcd34d0cc951c3befa6f.json
│   │           ├── fc26e19827d6a6b8a6995766933227c6c87adee3d7db49775f31820cc4a07bdf.json
│   │           └── fee3f54d986c46d58cfc898972d38f1eced292f2b13dcab3e1116739b6f6bc1b.txt
│   ├── channels/
│   │   └── 2026/
│   │       └── 01/
│   │           ├── 016c923212f46bde1782e867350f2b495f8f684bb8a1a23c3998009bb01f7eb3.json
│   │           ├── 0459f58036202dc2585214af9630fd58babfe7d8c72bc009f70676ae45e52e70.json
│   │           ├── 048340f42fe385c17fd9cdda8ccea99e46c711f6801f98cafb1e090ae8fa720d.json
│   │           ├── 064e88f4a8b60f4a5a5d130f11916388fb7df1d12777d925b224e9be07adeb04.toon
│   │           ├── 0869294614d2e2012c40631890b5e1659854a713248484d634a6ba0caa064a28.json
│   │           ├── 0878d71d21d5fd65d6c351ab3040dcaf703399c3e32f346c3b790fe80fde9208.json
│   │           ├── 093d0a807ce791d2c59fe277c42cd7eca774b0bf00dfe1212362ef123a9eae45.json
│   │           ├── 0ff29afa41e0acdae0ce68e992e477f87b7f8158badb79eca68721ee7eba3f2e.json
│   │           ├── 0ffa7b5aaa3346d69b594d77072a0cccb98513236269f9955532d5a7919fdba1.json
│   │           ├── 128ae422970b7f74c644d0a249d29518244b3258dadb79146afae78aea5006f7.json
│   │           ├── 135f18f3d77dc1cc001b889f0c6094b734f78346951128bd0235e9f1af4b931d.json
│   │           ├── 1367781549f83edc58f386f7b3e6b414d4725046874e34cc33caee84d732eaa1.json
│   │           ├── 1e542a2fadfbe4d428eca950b0d63ac0cdac8f8cd50ada8ec6873373e133db71.md
│   │           ├── 1e6def90eb14aca83292d9d40631221b0895afbee28f5391259beac5999bc4d3.json
│   │           ├── 1fb6ffd922186f3b17034d7f577efa0d9fd9e073d08b63680925d549d5c34143.json
│   │           ├── 213dc483299782e6258306472e5de17720d78d9431cd29ee934cca5bafb66bbe.json
│   │           ├── 224ad4e0a32f73075b0ce296b6e26210556b4a50892f4c53e0ce2b647b7bab30.toon
│   │           ├── 2ccda2819a8de837080bc911084ada500722e3d40563184a6274d129f3edc3ba.json
│   │           ├── 2df47e6e2695dd71e02c345f83e3fd735904e97643937d62dce4051f79673284.md
│   │           ├── 2f10cceb2894516a0d58e3c281e59b12766da985388f450453dc006eb1afa016.json
│   │           ├── 34a468e725942584bf2a9ebdde43910432ed775025036bcf9bc24471357bf64e.json
│   │           ├── 3c3d1ae6aaeea746800f9fa03abb4ced889121f5926ce2519393832f1bd3bba3.json
│   │           ├── 3c8c5c4814353718e13b9cd75d2e5000fbc5f3015a96b184df7b8e2bce47fd6a.json
│   │           ├── 3d7c73d70c4dbda6613df67ad59fec7e3e17230ad735ac9831f788ee2f78b27b.json
│   │           ├── 3e351ee0a6bed486c7b4249b66b11ba5a435b657d98b306dc9f7e014d0ef5cb7.toon
│   │           ├── 3ed87a98a51b0c6fb35eb50656258cf8a475ac583462458fcddfd79ef9d69fe0.json
│   │           ├── 404a4d90fad6df4f344cb47fd7a94149b925908b05073fe3bb6eb1d866142dd9.toon
│   │           ├── 405a4d84cba0f705a039a8ad761d712d16b02de1b5d8db507e46931644bd2677.json
│   │           ├── 432d12abd723f2fef6b2df6a915123351963ea00d27fad981d27b2bbc12b6372.json
│   │           ├── 4488b662980d555c34e7be9e9a85c898dd3b5ed9aa4f862563965eaef5d71c70.json
│   │           ├── 453f1293cac63d6f8277629d2650f6ae7a9c1452f3375fb034070c077c70243b.json
│   │           ├── 459e374d5adb109e007456269bb0d57b5b2039a0378081d98525800fb2884efe.toon
│   │           ├── 48c5d0fabdcd00968f5dfb436c71f5b3a61f095a52a66e95c785f687a0532a87.json
│   │           ├── 48f1d7f5ce0a1de0c1658226d894a20ba49af24c9f707f18e944d34c600a810e.json
│   │           ├── 4973feb9be2a1946aef5a1d182c77c42c786bb57177a4a0296eb8865ff168122.json
│   │           ├── 4a40d34208e843b5a4aaea090dd199ee3c08bad53847ed084d0faca6a15d0627.json
│   │           ├── 4f5d6bf43afba02ec40324eeb1975e1d0f1f904dfd69d6f5eff4dd296f47ab48.json
│   │           ├── 4f7551a466c160b141cd131ec1038f405a210c39ae789695ea19c43242e71dac.json
│   │           ├── 4f9c17cd68cc1a2f347ad41b0751ae79c6f827242c453838ac7e89119989cb60.json
│   │           ├── 4fb47f6ab6f8ab6703d0cfc57d681d1f9adb8855701f69f83484daf2caef2806.json
│   │           ├── 50c8f7938919c3114678e42ca28dc94eae9320131f0fc6e27bb3afbe09bd684b.json
│   │           ├── 51593260fd7f90e1aab4c6ea7bdc3872d4cce9cd1204db2667e43639773e881a.json
│   │           ├── 5837c0abe3943fc51ba95ccfb75feb61f391fa563bdb7accc29ef5ee1681e27b.md
│   │           ├── 5e21f6d3311bdb0572750391b22a10d5890c837d5072d76890a6792064e60328.json
│   │           ├── 64d798df350280274996b5965cd69359426fbf06d2a5e95c765675087e7b1b1e.json
│   │           ├── 6800bd77bb525b9bf5fd5967fd826ea87986135cef4615038dccbf61e9541477.json
│   │           ├── 68c624935b0fe10e4e24b22585ac5dc7952167248a2c415c23bbec194b04f28f.json
│   │           ├── 6a1dcca63a10ca6dad8c814ae4b942f05b43cad349ebd62e640004ea89655109.json
│   │           ├── 6a2b3abdbfb14fed4cf9420dae7bab300c8024da668de3e9b2c46f2976ef4e03.json
│   │           ├── 6dac411aa4b67394f10ea82f14dcef6e406fb1a17a55b34d64983b82d00addeb.json
│   │           ├── 6e0c5475b1a7437b8b91894242abc6f0f78ce5b04977e0d122d707a758fdf89e.toon
│   │           ├── 6e4947e67e59ac202c49101d0f23a36c64a77d3b98cfe83a1348a1fbb0aa91b7.json
│   │           ├── 734f5a91a58479dfdc0467dfdd8c01f9899277b7d2f530f41ebf462650fb00ab.json
│   │           ├── 742c53f01a6c18a4f7baa33635b8fc2374f948ef944f41e9a08ca43ae899ef14.json
│   │           ├── 766b72a5f382ca01b1d2b1e0b030a16f1a94b8f0cea1bc77dbe5721b4aa66d0d.json
│   │           ├── 76a9233a410d9f1dea35444c406dac2218688103879406b9f8f1d40737318339.json
│   │           ├── 779d2329c5489a81aefe75e2d57eef56eb2bb9b6607191c43dfe928b33d84405.json
│   │           ├── 7c5f84141f23870d9aa6c986bd89cf91f37baa2316f1e18b75b1dd2ba21e89f0.json
│   │           ├── 7c68f83be484c55e003a7c5eec0e88447845e760d067c51b452f859a42ec44fe.md
│   │           ├── 7eaf5c83e99fbb91f06d120da4cd269b6916213ad81850fd2dc357ebb954623f.json
│   │           ├── 7ff6acf3019bd3f088713f53802d3870650a4e3781378b442736900eca7eddb1.json
│   │           ├── 808064c810cb11598226ef870b4c2fe4126302ce82a1f646131af252c8396f12.json
│   │           ├── 816652dca79bfd087111f86754abc3695b0d222b21f31a30dd12f288286d1c2a.json
│   │           ├── 81c5b70c99371d7dac9145c861d40649f5acbdf224c0bc9aab6e3504cca8ca9a.json
│   │           ├── 87ede63a6989c05dba47b05855010d0db9f459332c85190bf6af7751fbbdec45.json
│   │           ├── 8886c6078024d69c87356896548f9b53030a481fa5f3726fd665ff3e85c7ed3b.json
│   │           ├── 8a8d26d325e8c640eea5ef40229d9e6cf378f13459d43a2c819290ce6d3f9930.json
│   │           ├── 8b8d0a6924668b21aacb3a80fd1eb2b087ceb2cfdda1efa4c27600f296ef1ea4.toon
│   │           ├── 8c87aa99bb982c3d86f913ad965dab729778cb39bfbfc89914c234bafb22686d.json
│   │           ├── 8ca0ffcc32b245836179292c2539c3a855c07be6ffda9e07acd00affab569efe.json
│   │           ├── 8dc5a17088b0bd65419576a46fefdf8c749942e8b1afe2a5198b75dee1aa4dd2.md
│   │           ├── 8fdc41039241cbc60c93b45d1ec2036d74353feb8522fd0d801878398225d084.json
│   │           ├── 902ddb20ac4b37cd59283055872f749f1811f8319e0f7685c5a05b5a6fd70bcf.json
│   │           ├── 91d6a2b5b0137c51a66b4e08d53dccfe6240a085f33fda54b250fc89f6a59644.json
│   │           ├── 93379e5adbd0bb7c2c90b58b4bc78db4b58a8c87d3c83d981261c1e145317ec0.json
│   │           ├── 94e9f7ccf50b70d60f9d2e7c56cf2c849104d88b7436dd050994852680647890.json
│   │           ├── 9589f836f7f6a3e7b8a3b6d2bc870a99b35d07e19e6a9da6e2fa44a02f7b464c.md
│   │           ├── 977218044cf99c7aa416e08d57fa3b33b8923e510f6b58b1bf20061a6a2de205.json
│   │           ├── 9ad4dfc1bb14c5e56163864c12ab833dcfb5342605ab8d4c6fe29928d4065840.json
│   │           ├── 9b7167a00328f368647e98be68271b6a5291517089fadb4aa769fd3ae8bda24d.json
│   │           ├── a5338d955b09046ec0b16f3a9625b7955c763aae07dc722e474e6078745f932f.json
│   │           ├── a63c883fef60f1824c060ca13dbb6395a2d786f09898bfd1f6938e70c74ec77f.json
│   │           ├── a6558341e416d756f4ad18b6d6635fcb3b143a4a8865cd55608a9eb1dfbeeb2b.json
│   │           ├── a77c52da7af9e374b233a9bd9e02a68a91471f24e100f18fb20060f1a1f39ab7.json
│   │           ├── a9a54392e71fb9606c1dfeff43c3e801d582418d239e8752ba8edf10ea39f21a.json
│   │           ├── aba98cd2f6ae3e63ed9fc2350e78fdfca4b1d78ba319befa0ccaee2043fe7ba5.json
│   │           ├── abbdd455381fd1a6988a1c83aedb6512dc7c569675dd75117a53fb82ef3db54f.json
│   │           ├── acfc6904057d6fee1e49d9765018f25a7a1408c5bd49f110a49b37d27f9322b7.toon
│   │           ├── af0171bc6368e3ef646b7c07bf31d5071ba34e0fd3d503e961942606c6a514f7.json
│   │           ├── b084d941ec2df39bcb1cb8036bf69ebc602a6cdac772d8e867a800f0dfc294a2.json
│   │           ├── b10a3f71d4c6d21fea56a05dfef8a98d64722315971faa266ea0fb8dbc1e7148.json
│   │           ├── b84f92dcd837f906a0e5048f3979d3db320c6fab617469952b878ecc1061df2e.toon
│   │           ├── ba3574c69a28f48099611d12bd8d43416233c4120d95dbd9e9846f4d2ec89152.json
│   │           ├── bf55243a0634edf7f87ac0554e790a377b9616fe33e685f66cba0391f4153054.json
│   │           ├── bf9c62e109e50e30c04f89de35cd6843b688bc587f36028ddf59cee8008bbbe2.json
│   │           ├── c37f6f2f114d29c184737face0c6925b434c38104e23b4238621e46609acd4a3.json
│   │           ├── c6399ef0339205e5fa2c0a1cb3a2a14e985a3c8ee99dc3ece2f3f37cba4f8021.json
│   │           ├── c86a414753b1f82d41a742b23fbda34ffa524ffb363a01c2436903255da13342.json
│   │           ├── cc6297eb9edfdd783915612320379a78a4d7e6ad955af90fb83b8f5a78be79df.json
│   │           ├── cdc731af1fa0e4e3e797673c9dbd7ed989fad926494616a9fd08ea1d199695c7.json
│   │           ├── ce5868680dd1a055cf79a7c12d2919d1e0dff43f514bbb3c342e2a87e8702cc2.json
│   │           ├── ceae04b0b359cc881212b94b5f323735f82fe85913cf1e98038a4a3cfa159da5.json
│   │           ├── cfc87bcb7ab405103569233264fdd7601e5348215110c11ff2b801736560575b.json
│   │           ├── cfe562335912a4c54d29d99bb6198896275d908217d5a364dc97a4d65923c35b.json
│   │           ├── d8df567a8123b0975ecf34aed7af31baeb3e029e078dd8c45d52051b5569975a.json
│   │           ├── db55c959e22b9d5862cfa090e181244a3341b8fd16f566c805b9afecb0d064f4.json
│   │           ├── dd0cb40e48d6072432f02c1bee6570f832002edf1401da95365d5fe1e6fb31ca.json
│   │           ├── dd879789193714e19d835ea56bb8c3a3f3cb9d496c9eb87550a1bcd071a4817d.json
│   │           ├── de5e1f5a8a65f0780e517d43046d9f6bcc3aec908c4087840a32e62b51334cf5.md
│   │           ├── e11218680df177652f2c78c029b34440611cc196f6daa39e33dd619046c84a4b.json
│   │           ├── e11b56a8867e9dc619b936badf1a01690ab267ea1d9eb1f69385da6fc4d2b56b.toon
│   │           ├── e21a4f82ce0de20d260b66bdc8f4c05c306ce72f65bd4b53db5fccf68f9b9dae.json
│   │           ├── e22e4a2ac6daa063f0fdbcfafddcdbd4c4910fd3b85a18e5f92ee4ec2df791c5.json
│   │           ├── e40c6cd8937c7caf8b3f2bfa672112ab6108f1fdf62777b155b5a3a323b5ed29.json
│   │           ├── e7c696415a8ea2b727356767a914740e00c7613bd4ead56485444fd2e604f7a9.json
│   │           ├── ea75150b20c73fe6e763f4005b93cbc9a10d1a9409f8ded6df896b09547d48e3.json
│   │           ├── ead8d4f917b9535b70823d18248c2dd453d11462aebfbca97eaf3b59f30f8cc9.json
│   │           ├── ee2572d6f1ec8e381b3999198f7d36882e85e29f90804055d3615b9f44a0d205.json
│   │           ├── f04c4f53c50597b5aafc79cb249e13267ecfccd018d9d258ccb613f38fd87759.json
│   │           ├── f51d077cd7bd9fb6ef881a6f5be66720949fb13913758d97a18d1246f0b7f979.json
│   │           ├── f7df77bf8ed944a2c800eb5209419ca912e1061dc72ac9188091e41578732546.md
│   │           ├── f88f666b70d3c32c81c6860aec565e20ce88cd0649e9862a9adb1d3ad9139a4a.json
│   │           ├── f8f8b5e05c852a7bfcfc267cfb715c67bdd6165d7a3aa0034768bf4dfdf06649.json
│   │           ├── f93d038a33d6ebf7d727140ebad51f474a73eb18264d83ebf62173a811a91ddc.json
│   │           ├── fa83a76cf260821d890407799ccf26996b18eb5b3b436c3e740d76184c1da5b4.json
│   │           ├── fd35e0aa47c212e294e2cfb5c38056f995f3521e57b265a29e357a105c6328ff.json
│   │           ├── fda78748fbf044a2be38f460874ba9695c2c49c28074ae3e9d75fd665cf0e10a.json
│   │           ├── fee1bd0b7864e88ec319c64559633e457a8cc1ac145cc5af102dc0dc93971c58.json
│   │           └── ff00fd6b7786af0a6fe89191d11948e79c00534372dcaf3fa2036e99e7331ee5.json
│   └── operators/
├── .cursorrules
├── .gitattributes
├── .gitignore
├── .htaccess
├── .output.txt
├── 4.2.0_release_execution.ps1
├── 4.2.1_hotfix_window.sh
├── admin.php
├── AGENT_DIALOG_PROTOCOL.md
├── AGENT_SNAPSHOT_HANDLING_RULES.md
├── AGENTS.md
├── CHANGELOG.md
├── CHANNEL_IDENTITY_BLOCK_TEMPLATE.md
├── channel_summary.md
├── check_dedicated_slot_duplicates.ps1
├── check_folder_assignments.ps1
├── cliff_notes_on_lupopedia.md
├── complete_schema.txt
├── CONTRIBUTING.md
├── crafty_runtime.txt
├── crafty_syntax_return.htm
├── database-rules.yaml
├── DB_SNAPSHOT_PROTOCOL.md
├── dialog.yaml
├── DIRECTORY_STRUCTURE_DOCTRINE.md
├── DIRECTORY_TREE.md
├── documentation_mapping.json
├── emotional.htm
├── fix_includes.php
├── fix_remaining_folders.ps1
├── image.php
├── import-lang.php
├── index.php
├── install.php
├── install_wizard_classes.php
├── LegacyIndex.php
├── license.txt
├── live.php
├── livehelp-history.php
├── livehelp.php
├── livehelp_js.php
├── LUPEDIA_VERSION
├── lupopedia-config.php
├── lupopedia_call_to_action.htm
├── migrate_dialog_channels.php
├── mockup_chat.htm
├── plan_for_crafty_syntax.md
├── plan_for_toon_seed_regeneration.md
├── README.md
├── refactor_folder_moves.ps1
├── refactor_folder_moves_fixed.ps1
├── remote-index.php
├── rename_agent_folders.ps1
├── RUNTIME_AGENT_RULES.md
├── SEMANTIC_LAYER_MODEL.md
├── STRUCTURAL_REALIGNMENT.md
├── system_kernel_index.htm
├── template.htm
├── temporary_index.html
├── test-dialog-send.php
├── test_cip_analytics.php
├── test_color_protocol.php
├── test_dialog_migration.php
├── test_dialog_system.php
├── test_metadata.php
├── what_is_lupopedia.htm
└── wolfie_headers.yaml
