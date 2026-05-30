# Lupopedia Hooks

This directory contains Git hooks and validation scripts for Lupopedia development.

## Hook Files

### Pre-commit Hooks
- `pre-commit.sh` - Bash script for Unix/Linux/macOS systems
- `pre-commit.bat` - Batch script for Windows systems  
- `pre_commit_validate.py` - Python validation runner

### Installation

#### To install the pre-commit hook:

```bash
# Copy to Git hooks directory
cp hooks/pre-commit.sh .git/hooks/pre-commit

# Make executable (Unix/Linux/macOS)
chmod +x .git/hooks/pre-commit
```

For Windows:
```cmd
copy hooks\pre-commit.bat .git\hooks\pre-commit.bat
```

## Validation Scripts

The hooks call validation scripts located in `scripts/`:

- `validate_lupopedia_headers_universal.py` - Header validation
- `validate_implementation.py` - Implementation validation
- `validate_actor_registry.py` - Actor registry validation
- `validate_channel_artifacts.py` - Channel artifacts validation

## Hook Behavior

The pre-commit hook will:
1. Run header validation on all modified files
2. Validate implementation folders if changed
3. Check actor registry consistency
4. Validate channel artifacts
5. Block commit if critical validation fails

## Disabling Hooks

To temporarily disable hooks:
```bash
git commit --no-verify
```

To permanently disable:
```bash
rm .git/hooks/pre-commit
```

## Troubleshooting

If hooks fail:
1. Run validation manually: `python scripts/validate_lupopedia_headers_universal.py`
2. Check that all required header fields are present
3. Ensure file paths in `lupopedia.edges` are correct
4. Verify actor IDs are valid in the registry
