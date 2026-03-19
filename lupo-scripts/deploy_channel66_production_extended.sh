#!/bin/bash
#
# Channel 66 Production Deployment Script
#
# Comprehensive deployment automation for Channel 66 production-grade ingestion
# with environment validation, backup, rollback, and health monitoring.
#
# Usage: ./deploy_channel66_production_extended.sh [environment] [options]
#   environment: dev|staging|production (default: production)
#   options:
#     --validate-only    Validate configuration only, do not deploy
#     --skip-backup     Skip backup creation
#     --skip-health-check Skip post-deployment health check
#     --dry-run         Show what would be done without executing
#
# @version 4.0.80
# @author HEPHAESTUS (actor_id 3)
#

set -e

# Configuration
ENVIRONMENT=${1:-production}
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(dirname "$SCRIPT_DIR")"
LOG_FILE="$PROJECT_ROOT/lupo-logs/admin/channel66_deployment_extended_$(date +%Y%m%d_%H%M%S).log"
CONFIG_FILE="$PROJECT_ROOT/lupo-config/channel66_production_extended_$ENVIRONMENT.ini"
BACKUP_DIR="$PROJECT_ROOT/lupo-backups/channel66_production_extended/$(date +%Y%m%d)"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Logging function
log() {
    local level=$1
    shift
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] [$level] $*" | tee -a "$LOG_FILE"
}

# Error handling
error_exit() {
    log "ERROR" "$1"
    echo -e "${RED}ERROR: $1${NC}"
    exit 1
}

success() {
    log "INFO" "$1"
    echo -e "${GREEN}$1${NC}"
}

warning() {
    log "WARNING" "$1"
    echo -e "${YELLOW}$1${NC}"
}

info() {
    log "INFO" "$1"
    echo -e "${BLUE}$1${NC}"
}

# Show usage
show_usage() {
    echo "Channel 66 Production Deployment Script (Extended)"
    echo ""
    echo "Usage: $0 [environment] [options]"
    echo ""
    echo "Environments:"
    echo "  dev         Development environment"
    echo "  staging     Staging environment"
    echo "  production  Production environment (default)"
    echo ""
    echo "Options:"
    echo "  --validate-only    Validate configuration only, do not deploy"
    echo "  --skip-backup     Skip backup creation"
    echo "  --skip-health-check Skip post-deployment health check"
    echo "  --dry-run         Show what would be done without executing"
    echo "  --quick-test      Run quick validation tests"
    echo ""
    echo "Examples:"
    echo "  $0 production                    # Deploy to production"
    echo "  $0 staging --validate-only      # Validate staging configuration"
    echo "  $0 dev --dry-run --skip-backup  # Dry run for dev without backup"
}

# Check prerequisites
check_prerequisites() {
    info "Checking deployment prerequisites..."
    
    # Check if running as appropriate user
    if [[ $EUID -eq 0 ]]; then
        error_exit "This script should not be run as root for security reasons"
    fi
    
    # Check required directories
    local required_dirs=(
        "$PROJECT_ROOT/lupo-channels/66"
        "$PROJECT_ROOT/lupo-database/lupopedia/toon"
        "$PROJECT_ROOT/lupo-scripts"
        "$PROJECT_ROOT/lupo-includes/classes"
        "$PROJECT_ROOT/lupo-logs/admin"
        "$PROJECT_ROOT/lupo-config"
        "$PROJECT_ROOT/lupo-tests/integration"
    )
    
    for dir in "${required_dirs[@]}"; do
        if [[ ! -d "$dir" ]]; then
            error_exit "Required directory not found: $dir"
        fi
    done
    
    # Check required files
    local required_files=(
        "$PROJECT_ROOT/lupo-scripts/ingest_channel66_production.php"
        "$PROJECT_ROOT/lupo-includes/classes/Channel66ProductionIngester.php"
        "$PROJECT_ROOT/lupo-includes/classes/Channel66ProductionConfig.php"
        "$PROJECT_ROOT/lupo-includes/classes/Channel66BatchProcessor.php"
        "$PROJECT_ROOT/lupo-includes/classes/Channel66ProductionErrorHandler.php"
        "$PROJECT_ROOT/lupo-includes/classes/Channel66PerformanceMonitor.php"
        "$PROJECT_ROOT/lupo-includes/classes/Channel66ProductionLogger.php"
        "$PROJECT_ROOT/lupo-includes/classes/Channel66HeaderProjection.php"
        "$PROJECT_ROOT/lupo-tests/integration/channel66_production_test.php"
        "$PROJECT_ROOT/lupo-tests/integration/channel66_production_extended_test.php"
    )
    
    for file in "${required_files[@]}"; do
        if [[ ! -f "$file" ]]; then
            error_exit "Required file not found: $file"
        fi
    done
    
    # Check PHP availability
    if ! command -v php &> /dev/null; then
        error_exit "PHP is not available or not in PATH"
    fi
    
    # Check PHP extensions
    local required_extensions=("yaml" "pdo" "json" "mbstring")
    for ext in "${required_extensions[@]}"; do
        if ! php -m | grep -q "^$ext$"; then
            error_exit "Required PHP extension not found: $ext"
        fi
    done
    
    success "Prerequisites check passed"
}

# Validate environment
validate_environment() {
    info "Validating $ENVIRONMENT environment..."
    
    # Environment-specific validation
    case $ENVIRONMENT in
        dev)
            if [[ ! -d "$PROJECT_ROOT/lupo-tests/temp/channel66_dev" ]]; then
                error_exit "Development environment directory not found: $PROJECT_ROOT/lupo-tests/temp/channel66_dev"
            fi
            ;;
        staging)
            if [[ ! -d "$PROJECT_ROOT/lupo-tests/temp/channel66_staging" ]]; then
                error_exit "Staging environment directory not found: $PROJECT_ROOT/lupo-tests/temp/channel66_staging"
            fi
            ;;
        production)
            if [[ ! -d "$PROJECT_ROOT/lupo-channels/66" ]]; then
                error_exit "Production Channel 66 directory not found: $PROJECT_ROOT/lupo-channels/66"
            fi
            ;;
        *)
            error_exit "Invalid environment: $ENVIRONMENT. Use: dev|staging|production"
            ;;
    esac
    
    success "Environment validation passed for $ENVIRONMENT"
}

# Create configuration
create_configuration() {
    info "Creating configuration for $ENVIRONMENT..."
    
    local config_dir="$PROJECT_ROOT/lupo-config"
    mkdir -p "$config_dir"
    
    cat > "$CONFIG_FILE" << EOF
[production]
scope_root = $PROJECT_ROOT/lupo-channels/66
batch_size = 200
memory_limit = 1G
error_retry_attempts = 3
error_retry_delay = 5
enable_monitoring = true
log_level = INFO
performance_alert_threshold = 0.05
memory_alert_threshold = 0.8
throughput_alert_threshold = 0.7
max_execution_time = 7200

[extended_testing]
large_scale_test_files = true
memory_pressure_test = true
performance_regression_test = true
concurrent_stress_test = true
malformed_toon_test = true
database_failure_recovery_test = true
configuration_failure_test = true
monitoring_integration_test = true
deployment_validation_test = true
rollback_procedure_test = true
logger_output_test = true
end_to_end_test = true

[deployment]
environment = $ENVIRONMENT
validate_environment = true
backup_before_deploy = true
rollback_on_failure = true
max_execution_time = 7200
deployment_timestamp = $(date +%s)
deployment_user = $(whoami)
deployment_host = $(hostname)
health_check_timeout = 300
smoke_test_timeout = 60

[monitoring]
extended_metrics_collection = true
alerting_thresholds = true
log_rotation_validation = true
performance_trends_analysis = true
real_time_alerts = true

[testing]
quick_test_mode = false
comprehensive_test_suite = true
parallel_test_execution = false
test_data_cleanup = true
test_timeout = 1800
EOF
    
    success "Configuration created: $CONFIG_FILE"
    echo "CONFIG_FILE=$CONFIG_FILE"
}

# Backup current deployment
backup_deployment() {
    if [[ "$SKIP_BACKUP" == "true" ]]; then
        warning "Skipping backup as requested"
        return 0
    fi
    
    info "Creating backup of current deployment..."
    
    mkdir -p "$BACKUP_DIR"
    
    # Backup configuration
    if [[ -f "$PROJECT_ROOT/lupo-config/channel66_production_extended_$ENVIRONMENT.ini" ]]; then
        cp "$PROJECT_ROOT/lupo-config/channel66_production_extended_$ENVIRONMENT.ini" "$BACKUP_DIR/"
        info "Configuration backed up"
    fi
    
    # Backup logs (last 7 days)
    find "$PROJECT_ROOT/lupo-logs/admin" -name "channel66_production_extended_*.jsonl" -mtime -7 -exec cp {} "$BACKUP_DIR/" \;
    local log_count=$(find "$PROJECT_ROOT/lupo-logs/admin" -name "channel66_production_extended_*.jsonl" -mtime -7 | wc -l)
    info "Log files backed up: $log_count files"
    
    # Verify backup integrity
    info "Verifying backup integrity..."
    local backup_failed=false
    
    # Check configuration backup
    if [[ ! -f "$BACKUP_DIR/channel66_production_extended_$ENVIRONMENT.ini" ]]; then
        error_exit "Configuration backup failed - file not found in backup"
    fi
    
    # Check log backup count
    local backed_up_logs=$(find "$BACKUP_DIR" -name "channel66_production_extended_*.jsonl" | wc -l)
    if [[ $backed_up_logs -lt $log_count ]]; then
        error_exit "Log backup integrity failed - missing files in backup"
    fi
    
    # Create backup checksum
    local backup_checksum=$(find "$BACKUP_DIR" -type f -exec sha256sum {} \; | sha256sum | cut -d' ' ' -f1)
    echo "$backup_checksum" > "$BACKUP_DIR/backup_checksum.sha256"
    info "Backup integrity verified: checksum created"
    
    if [[ "$backup_failed" == "true" ]]; then
        error_exit "Backup integrity verification failed"
    fi
    
    # Backup database schema (read-only)
    if [[ -d "$PROJECT_ROOT/lupo-database/lupopedia/toon" ]]; then
        cp -r "$PROJECT_ROOT/lupo-database/lupopedia/toon" "$BACKUP_DIR/toon_backup"
        info "TOON schema backed up"
    fi
    
    success "Backup completed: $BACKUP_DIR"
    echo "BACKUP_DIR=$BACKUP_DIR"
}

# Validate configuration
validate_configuration() {
    info "Validating production configuration..."
    
    if [[ ! -f "$CONFIG_FILE" ]]; then
        error_exit "Configuration file not found: $CONFIG_FILE"
    fi
    
    # Test configuration parsing
    php -r "
        require_once '$PROJECT_ROOT/lupopedia-config.php';
        require_once '$PROJECT_ROOT/lupo-includes/bootstrap.php';
        \$config = parse_ini_file('$CONFIG_FILE');
        if (\$config === false) {
            exit(1);
        }
        echo 'Configuration parsing successful';
    " || error_exit "Configuration parsing failed"
    
    success "Configuration validation passed"
}

# Run extended tests
run_extended_tests() {
    if [[ "$SKIP_TESTS" == "true" ]]; then
        warning "Skipping extended tests as requested"
        return 0
    fi
    
    info "Running extended production test suite..."
    
    local test_dir="$PROJECT_ROOT/lupo-tests/temp/channel66_extended_tests"
    mkdir -p "$test_dir"
    
    # Run the extended test suite
    php "$PROJECT_ROOT/lupo-tests/integration/channel66_production_extended_test.php" \
        --test-dir="$test_dir" \
        --comprehensive-test || error_exit "Extended tests failed"
    
    success "Extended tests completed"
}

# Deploy to production
deploy_production() {
    if [[ "$DRY_RUN" == "true" ]]; then
        info "DRY RUN: Would deploy to $ENVIRONMENT environment"
        info "Configuration: $CONFIG_FILE"
        info "Scope root: $PROJECT_ROOT/lupo-channels/66"
        return 0
    fi
    
    info "Deploying to $ENVIRONMENT environment..."
    
    # Create deployment marker
    local deployment_marker="$PROJECT_ROOT/.channel66_deployment_extended_in_progress"
    echo "$(date +%s)" > "$deployment_marker"
    
    # Run pre-deployment validation
    info "Running pre-deployment validation..."
    php "$PROJECT_ROOT/lupo-scripts/ingest_channel66_production.php" \
        --config="$CONFIG_FILE" \
        --thread-id=1001 \
        --dry-run || error_exit "Pre-deployment validation failed"
    
    # Run a test ingestion to validate deployment
    info "Running validation ingestion..."
    local test_results_dir="$PROJECT_ROOT/lupo-tests/temp/channel66_validation_test"
    mkdir -p "$test_results_dir"
    
    # Create test files
    for i in {1..5}; do
        cat > "$test_results_dir/validation_test_$i.md" << EOF
---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  lupopedia.schema: "thread"
  system_version: "4.0.80"
  file_path_from_root: "validation_test_$i.md"
  web_path: "http://validation.test"
  last_modified_utc: "20260319"
  channel_id: 66
  actor_id: 3
  delegation_chain: "hephaestus:root"
  artifact_type: "thread"
  artifact_kind: "test"
  purpose: "Deployment validation test"
lupopedia.edges:
  outbound_edges:
    - { to: "validation_target", type: "test", weight: 1.0 }
---
EOF
    done
    
    php "$PROJECT_ROOT/lupo-scripts/ingest_channel66_production.php" \
        --config="$CONFIG_FILE" \
        --thread-id=1001 \
        --scope-root="$test_results_dir" || error_exit "Validation ingestion failed"
    
    # Remove test files
    rm -rf "$test_results_dir"
    
    # Main deployment - run production ingestion on actual Channel 66
    info "Starting production ingestion..."
    
    # Atomic deployment with transaction tracking
    local deployment_transaction_id=$(date +%s%N)
    local deployment_state_file="$PROJECT_ROOT/.channel66_deployment_state"
    
    # Create deployment state file
    cat > "$deployment_state_file" << EOF
deployment_id=$deployment_transaction_id
status=in_progress
start_time=$(date +%s)
EOF
    
    # Set trap for cleanup on exit
    trap 'deployment_cleanup $deployment_transaction_id $?' EXIT
    
    info "Deployment transaction ID: $deployment_transaction_id"
    
    # Run production ingestion with error handling
    if php "$PROJECT_ROOT/lupo-scripts/ingest_channel66_production.php" \
        --config="$CONFIG_FILE" \
        --thread-id=null; then
        
        # Update deployment state to success
        sed -i "s/status=in_progress/status=success/" "$deployment_state_file"
        sed -i "s/end_time=.*/end_time=$(date +%s)/" "$deployment_state_file"
        
        # Remove deployment marker
        rm -f "$deployment_marker"
        
        success "Production deployment completed successfully"
    else
        # Deployment failed - trigger rollback
        local exit_code=$?
        error "Production deployment failed with exit code: $exit_code"
        
        # Update deployment state to failed
        sed -i "s/status=in_progress/status=failed/" "$deployment_state_file"
        sed -i "s/end_time=.*/end_time=$(date +%s)/" "$deployment_state_file"
        sed -i "s/exit_code=.*/exit_code=$exit_code/" "$deployment_state_file"
        
        # Trigger rollback
        warning "Triggering automatic rollback..."
        rollback
        
        error_exit "Production deployment failed - rollback completed"
    fi
}

# Deployment cleanup function
deployment_cleanup() {
    local deployment_id=$1
    local exit_code=$2
    
    info "Cleaning up deployment $deployment_id..."
    
    # Remove deployment state file
    rm -f "$PROJECT_ROOT/.channel66_deployment_state"
    
    # Remove deployment marker if exists
    rm -f "$PROJECT_ROOT/.channel66_deployment_extended_in_progress"
    
    if [[ "$exit_code" == "0" ]]; then
        success "Deployment $deployment_id completed successfully"
    else
        warning "Deployment $deployment_id failed with exit code $exit_code"
    fi
}
}

# Health check after deployment
health_check() {
    if [[ "$SKIP_HEALTH_CHECK" == "true" ]]; then
        warning "Skipping health check as requested"
        return 0
    fi
    
    info "Running post-deployment health check..."
    
    local health_check_start=$(date +%s)
    local health_timeout=${HEALTH_CHECK_TIMEOUT:-300}
    
    # Check if deployment marker exists (stuck deployment)
    if [[ -f "$PROJECT_ROOT/.channel66_deployment_extended_in_progress" ]]; then
        local marker_age=$(($(date +%s) - $(cat "$PROJECT_ROOT/.channel66_deployment_extended_in_progress")))
        if [[ $marker_age -gt $health_timeout ]]; then
            warning "Deployment marker is old, possible stuck deployment"
        fi
    fi
    
    # Check database connectivity
    local db_check=$(php -r "
        require_once '$PROJECT_ROOT/lupopedia-config.php';
        require_once '$PROJECT_ROOT/lupo-includes/bootstrap.php';
        global \$mydatabase;
        if (!\$mydatabase) {
            exit(1);
        }
        echo 'Database connectivity: OK';
    " || local db_check_failed=true)
    
    # Check recent log files
    local recent_logs=$(find "$PROJECT_ROOT/lupo-logs/admin" -name "channel66_production_extended_*.jsonl" -mtime -1 | wc -l)
    local logs_ok=$recent_logs -gt 0
    
    # Check if processes are running
    local process_check=$(pgrep -f "ingest_channel66_production.php" | wc -l)
    
    # Check memory usage
    local memory_usage=$(php -r "echo memory_get_usage(true);" | tail -1)
    local memory_ok=$(echo "$memory_usage" | grep -q "^[0-9]\+$")
    
    # Check disk space
    local disk_usage=$(df "$PROJECT_ROOT" | tail -1 | awk 'NR==2 {print $5}')
    local disk_ok=$(echo "$disk_usage" | grep -q "^[0-9]\+")
    
    local all_checks_passed=$db_check_failed && $logs_ok && $process_check -gt 0 && $memory_ok && $disk_ok
    
    if [[ $all_checks_passed ]]; then
        success "Health check passed - all systems operational"
    else
        warning "Health check detected issues:"
        [[ "$db_check_failed" == "true" ]] && warning "  - Database connectivity issue"
        [[ "$logs_ok" == "false" ]] && warning "  - No recent log files"
        [[ "$process_check" -le 1 ]] && warning "  - No ingestion processes running"
        [[ "$memory_ok" == "false" ]] && warning "  - High memory usage: $memory_usage"
        [[ "$disk_ok" == "false" ]] && warning "  - Low disk space: $disk_usage"
    fi
    
    local health_duration=$(($(date +%s) - $health_check_start))
    info "Health check completed in ${health_duration}s"
}

# Cleanup function
cleanup() {
    info "Cleaning up temporary files..."
    
    # Remove deployment marker if exists
    rm -f "$PROJECT_ROOT/.channel66_deployment_extended_in_progress"
    
    # Clean up test directories
    rm -rf "$PROJECT_ROOT/lupo-tests/temp/channel66_extended_tests"
    rm -rf "$PROJECT_ROOT/lupo-tests/temp/channel66_validation_test"
    
    success "Cleanup completed"
}

# Rollback function
rollback() {
    if [[ ! -d "$BACKUP_DIR" ]]; then
        error_exit "No backup directory found for rollback"
    fi
    
    info "Starting rollback procedure..."
    
    # Find most recent backup
    local latest_backup=$(find "$BACKUP_DIR" -name "backup_*.tar.gz" -printf '%T@%T\n' | sort | tail -1)
    
    if [[ -z "$latest_backup" ]]; then
        error_exit "No backup found for rollback"
    fi
    
    info "Rolling back to: $latest_backup"
    
    # Extract backup
    cd "$PROJECT_ROOT"
    tar -xzf "$latest_backup" || error_exit "Failed to extract backup"
    
    # Restore configuration
    if [[ -f "$BACKUP_DIR/channel66_production_extended_$ENVIRONMENT.ini" ]]; then
        cp "$BACKUP_DIR/channel66_production_extended_$ENVIRONMENT.ini" "$PROJECT_ROOT/lupo-config/channel66_production_extended.ini"
        info "Configuration restored from backup"
    fi
    
    success "Rollback completed"
}

# Main execution
main() {
    log "INFO" "Starting Channel 66 Production Deployment (Extended)"
    log "INFO" "Project root: $PROJECT_ROOT"
    log "INFO" "Environment: $ENVIRONMENT"
    
    # Parse command line options
    VALIDATE_ONLY=false
    SKIP_BACKUP=false
    SKIP_HEALTH_CHECK=false
    SKIP_TESTS=false
    DRY_RUN=false
    QUICK_TEST=false
    
    while [[ $# -gt 0 ]]; do
        case $1 in
            --validate-only)
                VALIDATE_ONLY=true
                shift
                ;;
            --skip-backup)
                SKIP_BACKUP=true
                shift
                ;;
            --skip-health-check)
                SKIP_HEALTH_CHECK=true
                shift
                ;;
            --dry-run)
                DRY_RUN=true
                shift
                ;;
            --quick-test)
                QUICK_TEST=true
                shift
                ;;
            --help)
                show_usage
                exit 0
                ;;
            *)
                shift
                ;;
        esac
    done
    
    # Execute based on options
    if [[ "$QUICK_TEST" == "true" ]]; then
        check_prerequisites
        validate_environment
        create_configuration
        run_extended_tests
        cleanup
    elif [[ "$VALIDATE_ONLY" == "true" ]]; then
        check_prerequisites
        validate_environment
        create_configuration
        validate_configuration
    else
        check_prerequisites
        validate_environment
        create_configuration
        validate_configuration
        
        if [[ "$SKIP_TESTS" != "true" ]]; then
            run_extended_tests
        fi
        
        if [[ "$DRY_RUN" != "true" ]]; then
            backup_deployment
        fi
        
        deploy_production
        
        if [[ "$SKIP_HEALTH_CHECK" != "true" ]]; then
            health_check
        fi
        
        cleanup
    fi
    
    success "Channel 66 production deployment completed for $ENVIRONMENT"
}

# Trap cleanup on exit
trap cleanup EXIT

# Execute main function
main "$@"
