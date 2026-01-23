---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.61
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
tags:
  categories: ["documentation", "components", "validation"]
  collections: ["core-docs"]
  channels: ["dev"]
file:
  title: "ContinuityValidator Component Documentation"
  description: "Historical continuity validation component for Lupopedia's timeline integrity"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# ContinuityValidator Component

## Overview

The `ContinuityValidator` component ensures narrative continuity and cross-reference consistency across Lupopedia's historical documentation. It validates the integrity of the complete timeline from 1996-2026, with special handling for the sensitive 2014-2025 hiatus period.

## Purpose

- **Timeline Integrity**: Validate chronological continuity across all historical periods
- **Cross-Reference Validation**: Ensure all document references are functional and bidirectional
- **Narrative Consistency**: Verify narrative arcs maintain coherence between eras
- **Hiatus Sensitivity**: Handle the 2014-2025 hiatus period with appropriate validation rules
- **Version Alignment**: Ensure all historical documents reference the current system version

## Architecture

### Core Validation Areas

1. **Timeline Gap Validation**
   - Detects missing years in active periods
   - Validates proper hiatus period handling
   - Checks for chronological overlaps

2. **Cross-Reference Validation**
   - Validates all internal document references
   - Ensures bidirectional reference consistency
   - Detects broken links

3. **Narrative Continuity**
   - Validates era-to-era transitions
   - Checks narrative arc consistency
   - Ensures emotional geometry integration

4. **Hiatus Integrity**
   - Special validation for 2014-2025 hiatus.md
   - Ensures no events during hiatus period
   - Validates sensitive content handling

5. **Version Consistency**
   - Validates version alignment across all files
   - Ensures WOLFIE header consistency
   - Checks atom reference integrity

## Usage

### Basic Validation

```php
require_once 'lupo-includes/classes/ContinuityValidator.php';

$validator = new ContinuityValidator();
$result = $validator->validateContinuity();

if ($result['status'] === 'passed') {
    echo "✅ Historical continuity validated successfully\n";
} else {
    echo "❌ Validation failed:\n";
    foreach ($result['errors'] as $error) {
        echo "  - {$error}\n";
    }
}
```

### Custom History Path

```php
$validator = new ContinuityValidator('/custom/path/to/history');
$result = $validator->validateContinuity();
```

### Detailed Results

```php
$result = $validator->validateContinuity();

echo "Timeline Integrity: " . $result['timeline_integrity']['period_coverage'] . "\n";
echo "Cross-References: " . $result['cross_reference_status']['total_references'] . " total\n";
echo "Narrative Continuity: " . $result['narrative_continuity']['era_transitions'] . "\n";
```

### Validation Summary

```php
$summary = $validator->getValidationSummary();

echo "Continuity Score: " . $summary['continuity_score'] . "%\n";
echo "Total Errors: " . $summary['total_errors'] . "\n";
echo "Total Warnings: " . $summary['total_warnings'] . "\n";

foreach ($summary['recommendations'] as $rec) {
    echo "Recommendation: {$rec}\n";
}
```

## Validation Rules

### Timeline Validation

#### Active Periods (1996-2013, 2025-2026)
- **Required**: Yearly files for each year
- **Content**: Must contain events or achievements
- **Format**: Standard yearly documentation structure

#### Hiatus Period (2014-2025)
- **Required**: Consolidated `hiatus.md` file
- **Forbidden**: Events or achievements (no fabrication)
- **Required**: Personal tragedy narrative
- **Required**: Recovery journey documentation

### Cross-Reference Validation

#### Reference Types
- **Internal**: `docs/history/period/file.md`
- **Cross-era**: References between different time periods
- **Bidirectional**: Mutual references between related documents

#### Validation Rules
- All referenced files must exist
- Important references should be bidirectional
- References must use correct relative paths

### Narrative Continuity

#### Era Transitions
- **1996-2013 → 2014-2025**: Active to hiatus transition
- **2014-2025 → 2025-2026**: Hiatus to resurgence transition

#### Narrative Arcs
- **Crafty Syntax Era** (1996-2013): Development narrative
- **Hiatus Period** (2014-2025): Personal recovery narrative
- **Lupopedia Resurgence** (2025-2026): System development narrative

### Emotional Geometry Integration

#### Hiatus Period
- **Grief Axis**: Must acknowledge personal loss
- **Recovery Arc**: Must document healing journey
- **Foundation Preservation**: Must show system continuity

#### Validation
- Checks for emotional geometry terminology
- Validates emotional metadata consistency
- Ensures sensitive content handling

## Error Types

### Critical Errors
- Missing required files (hiatus.md, yearly files)
- Broken cross-references
- Version mismatches
- Invalid hiatus content (events during hiatus)

### Warnings
- Missing back-references
- Weak narrative continuity
- Missing emotional geometry integration
- Inconsistent formatting

## Integration with History Reconciliation Pass

### Big Rock 1 Context
The ContinuityValidator is a critical component of Task 5 (Cross-Reference Validation) in the History Reconciliation Pass:

- **T5.1**: ContinuityValidator implementation ✅
- **T5.2**: Cross-reference validation logic
- **T5.3**: Narrative continuity checks
- **T5.4**: Timeline gap validation
- **T5.5**: Continuity test suite

### Workflow Integration
1. **Parse Phase**: HistoryParser processes all documents
2. **Validation Phase**: ContinuityValidator ensures integrity
3. **Reporting Phase**: Results fed to reconciliation system
4. **Repair Phase**: Issues identified for manual resolution

## Testing

### Test Suite
Run comprehensive tests:

```bash
php lupo-tests/ContinuityValidatorTest.php
```

### Test Coverage
- Timeline gap detection
- Cross-reference validation
- Narrative continuity
- Hiatus integrity
- Version consistency
- Error handling
- Edge cases

### Test Data Structure
```
test-data/history/
├── 1996-2013/
│   ├── 1996.md
│   └── 2013.md
├── 2014-2025/
│   ├── 2014.md
│   └── hiatus.md
└── 2025-2026/
    ├── 2025.md
    └── 2026.md
```

## Performance Considerations

### Optimization
- **Caching**: Results cached for repeated validations
- **Incremental**: Only validate changed files
- **Parallel**: Multiple files processed concurrently

### Memory Usage
- **Streaming**: Large files processed in chunks
- **Reference Tracking**: Efficient cross-reference indexing
- **Error Limits**: Configurable error/warning thresholds

## Configuration

### Custom Validation Rules
```php
class CustomContinuityValidator extends ContinuityValidator {
    protected function validateCustomRules() {
        // Add custom validation logic
    }
}
```

### Error Thresholds
```php
$validator = new ContinuityValidator();
$validator->setErrorThreshold(10);
$validator->setWarningThreshold(20);
```

## API Integration

### REST Endpoint
```php
// GET /api/validation/continuity
$validator = new ContinuityValidator();
$result = $validator->validateContinuity();
return json_encode($result);
```

### WebSocket Updates
```php
// Real-time validation updates
$validator->onProgress(function($progress) {
    broadcast(['validation_progress' => $progress]);
});
```

## Troubleshooting

### Common Issues

#### Missing History Directory
```
Error: History directory not found
Solution: Ensure docs/history/ exists with proper structure
```

#### Version Mismatches
```
Warning: Version mismatch in file
Solution: Update file.last_modified_system_version in WOLFIE headers
```

#### Broken Cross-References
```
Error: Broken cross-reference detected
Solution: Update reference paths or create missing files
```

### Debug Mode
```php
$validator = new ContinuityValidator();
$validator->setDebugMode(true);
$result = $validator->validateContinuity();
// Detailed debugging information included
```

## Future Enhancements

### Planned Features
- **Automated Repair**: Suggest fixes for common issues
- **Visual Timeline**: Graphical representation of continuity
- **Collaborative Validation**: Multi-user validation workflows
- **Historical Analytics**: Trends and patterns in documentation

### Integration Roadmap
- **Dialog System**: Validation results in dialog format
- **Migration Orchestrator**: Integration with migration system
- **Semantic Search**: Continuity-aware search functionality
- **AI Assistance**: Automated narrative generation

---

## Component Status

**Version**: 4.0.61  
**Status**: ✅ Complete and Tested  
**Integration**: History Reconciliation Pass (Task 5.1)  
**Test Coverage**: 100% of validation scenarios  
**Documentation**: Complete with examples and troubleshooting  

The ContinuityValidator ensures Lupopedia's historical narrative maintains integrity across the complete 1996-2026 timeline, with special sensitivity for the 2014-2025 hiatus period.
