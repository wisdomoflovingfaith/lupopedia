#!/bin/bash
#
# Channel 66 Production Deployment Script
#
# Automated deployment for Channel 66 production-grade ingestion
# with environment validation, backup, and rollback capabilities.
#
# Usage: ./deploy_channel66_production.sh [environment]
#   environment: dev|staging|production (default: production)
#
# @version 4.0.80
# @author HEPHAESTUS (actor_id 3)
#

set -e

# Configuration
ENVIRONMENT=${1:-production}
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(dirname "$SCRIPT_DIR")"
LOG_FILE="$PROJECT_ROOT/logs/admin/channel66_deployment_$(date +%Y%m%d_%H%M%S).log"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
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

# Check prerequisites
check_prerequisites() {
    log "INFO" "Checking deployment prerequisites..."
    
    # Check if running as appropriate user
    if [[ $EUID -eq 0 ]]; then
        error_exit "This script should not be run as root for security reasons"
    fi
    
    # Check required directories
    local required_dirs=(
        "$PROJECT_ROOT/channels/66"
        "$PROJECT_ROOT/database/lupopedia/toon"
        "$PROJECT_ROOT/scripts"
        "$PROJECT_ROOT/includes/classes"
        "$PROJECT_ROOT/logs/admin"
    )
    
    for dir in "${required_dirs[@]}"; do
        if [[ ! -d "$dir" ]]; then
            error_exit "Required directory not found: $dir"
        fi
    done
    
    # Check required files
    local required_files=(
        "$PROJECT_ROOT/scripts/ingest_channel66_production.php"
        "$PROJECT_ROOT/includes/classes/Channel66ProductionIngester.php"
        "$PROJECT_ROOT/includes/classes/Channel66ProductionConfig.php"
        "$PROJECT_ROOT/includes/classes/Channel66BatchProcessor.php"
        "$PROJECT_ROOT/includes/classes/Channel66ProductionErrorHandler.php"
        "$PROJECT_ROOT/includes/classes/Channel66PerformanceMonitor.php"
        "$PROJECT_ROOT/includes/classes/Channel66ProductionLogger.php"
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
    local required_extensions=("yaml" "pdo" "json")
    for ext in "${required_extensions[@]}"; do
        if ! php -m | grep -q "^$ext$"; then
            error_exit "Required PHP extension not found: $ext"
        fi
    done
    
    success "Prerequisites check passed"
}

# Validate environment configuration
validate_environment() {
    log "INFO" "Validating $ENVIRONMENT environment..."
    
    # Environment-specific configuration
    case $ENVIRONMENT in
        dev)
            BATCH_SIZE=10
            MEMORY_LIMIT="128M"
            LOG_LEVEL="DEBUG"
            MONITORING="true"
            ;;
        staging)
            BATCH_SIZE=50
            MEMORY_LIMIT="512M"
            LOG_LEVEL="INFO"
            MONITORING="true"
            ;;
        production)
            BATCH_SIZE=200
            MEMORY_LIMIT="1G"
            LOG_LEVEL="INFO"
            MONITORING="true"
            ;;
        *)
            error_exit "Invalid environment: $ENVIRONMENT. Use: dev|staging|production"
            ;;
    esac
    
    # Validate Channel 66 directory
    if [[ ! -d "$PROJECT_ROOT/channels/66" ]]; then
        error_exit "Channel 66 directory not found"
    fi
    
    # Validate TOON directory
    if [[ ! -d "$PROJECT_ROOT/database/lupopedia/toon" ]]; then
        error_exit "TOON directory not found"
    fi
    
    success "Environment validation passed for $ENVIRONMENT"
}

# Create configuration file
create_configuration() {
    log "INFO" "Creating configuration for $ENVIRONMENT..."
    
    local config_dir="$PROJECT_ROOT/config"
    local config_file="$config_dir/channel66_production_$ENVIRONMENT.ini"
    
    mkdir -p "$config_dir"
    
    cat > "$config_file" << EOF
[production]
scope_root = $PROJECT_ROOT/channels/66
batch_size = $BATCH_SIZE
memory_limit = $MEMORY_LIMIT
error_retry_attempts = 3
error_retry_delay = 5
enable_monitoring = $MONITORING
log_level = $LOG_LEVEL
performance_alert_threshold = 0.05
memory_alert_threshold = 0.8
throughput_alert_threshold = 0.7
max_execution_time = 7200

[deployment]
environment = $ENVIRONMENT
validate_environment = true
backup_before_deploy = true
rollback_on_failure = true
max_execution_time = 7200
deployment_timestamp = $(date +%s)
deployment_user = $(whoami)
deployment_host = $(hostname)
EOF
    
    success "Configuration created: $config_file"
    echo "CONFIG_FILE=$config_file"
}

# Backup current deployment
backup_deployment() {
    log "INFO" "Creating backup of current deployment..."
    
    local backup_dir="$PROJECT_ROOT/backups/channel66_production/$(date +%Y%m%d_%H%M%S)"
    mkdir -p "$backup_dir"
    
    # Backup configuration
    if [[ -f "$PROJECT_ROOT/config/channel66_production.ini" ]]; then
        cp "$PROJECT_ROOT/config/channel66_production.ini" "$backup_dir/"
        log "INFO" "Configuration backed up"
    fi
    
    # Backup logs (last 7 days)
    find "$PROJECT_ROOT/logs/admin" -name "channel66_production_*.jsonl" -mtime -7 -exec cp {} "$backup_dir/" \;
    
    success "Backup completed: $backup_dir"
    echo "BACKUP_DIR=$backup_dir"
}

# Validate configuration
validate_configuration() {
    log "INFO" "Validating production configuration..."
    
    if [[ ! -f "$CONFIG_FILE" ]]; then
        error_exit "Configuration file not found: $CONFIG_FILE"
    fi
    
    # Test configuration parsing
    php "$PROJECT_ROOT/scripts/ingest_channel66_production.php" \
        --config="$CONFIG_FILE" \
        --dry-run \
        --thread-id=1001 || error_exit "Configuration validation failed"
    
    success "Configuration validation passed"
}

# Run smoke tests
run_smoke_tests() {
    log "INFO" "Running smoke tests..."
    
    # Test with small subset first
    local test_dir="$PROJECT_ROOT/tests/fixtures/channel66_ingestion"
    
    if [[ -d "$test_dir" ]]; then
        php "$PROJECT_ROOT/tests/integration/channel66_production_test.php" \
            --test-dir="$test_dir" \
            --quick-test || error_exit "Smoke tests failed"
    fi
    
    success "Smoke tests passed"
}

# Deploy to production
deploy_production() {
    log "INFO" "Starting production deployment..."
    
    # Create deployment marker
    local deployment_marker="$PROJECT_ROOT/.channel66_deployment_in_progress"
    echo "$(date +%s)" > "$deployment_marker"
    
    # Run a test ingestion to validate deployment
    log "INFO" "Running validation ingestion..."
    php "$PROJECT_ROOT/scripts/ingest_channel66_production.php" \
        --config="$CONFIG_FILE" \
        --thread-id=1001 \
        --monitoring || error_exit "Validation ingestion failed"
    
    # Remove deployment marker
    rm -f "$deployment_marker"
    
    success "Production deployment completed successfully"
}

# Health check after deployment
health_check() {
    log "INFO" "Running post-deployment health check..."
    
    # Check if deployment marker exists (stuck deployment)
    if [[ -f "$PROJECT_ROOT/.channel66_deployment_in_progress" ]]; then
        local marker_age=$(($(date +%s) - $(cat "$PROJECT_ROOT/.channel66_deployment_in_progress")))
        if [[ $marker_age -gt 3600 ]]; then
            warning "Deployment marker is old, possible stuck deployment"
        fi
    fi
    
    # Check log files are being created
    local recent_logs=$(find "$PROJECT_ROOT/logs/admin" -name "channel66_production_*.jsonl" -mtime -1 | wc -l)
    if [[ $recent_logs -eq 0 ]]; then
        warning "No recent log files found, possible logging issue"
    fi
    
    # Check database connectivity
    php -r "
        require_once '$PROJECT_ROOT/lupopedia-config.php';
        require_once '$PROJECT_ROOT/includes/bootstrap.php';
        global \$mydatabase;
        if (!\$mydatabase) {
            exit(1);
        }
        echo 'Database connectivity: OK';
    " || error_exit "Database connectivity check failed"
    
    success "Health check passed"
}

# Rollback function
rollback() {
    log "INFO" "Starting rollback procedure..."
    
    if [[ -n "$BACKUP_DIR" ]] && [[ -d "$BACKUP_DIR" ]]; then
        # Restore configuration
        if [[ -f "$BACKUP_DIR/channel66_production.ini" ]]; then
            cp "$BACKUP_DIR/channel66_production.ini" "$PROJECT_ROOT/config/"
            log "INFO" "Configuration restored from backup"
        fi
        
        success "Rollback completed: $BACKUP_DIR"
    else
        error_exit "No backup available for rollback"
    fi
}

# Cleanup function
cleanup() {
    log "INFO" "Cleaning up temporary files..."
    
    # Remove any temporary deployment markers
    rm -f "$PROJECT_ROOT/.channel66_deployment_in_progress"
    
    # Clean old logs (keep last 30 days)
    find "$PROJECT_ROOT/logs/admin" -name "channel66_production_*.jsonl" -mtime +30 -delete
    
    success "Cleanup completed"
}

# Show usage
show_usage() {
    echo "Channel 66 Production Deployment Script"
    echo ""
    echo "Usage: $0 [environment]"
    echo ""
    echo "Environments:"
    echo "  dev         Development environment (small batches, debug logging)"
    echo "  staging     Staging environment (medium batches, info logging)"
    echo "  production  Production environment (large batches, info logging)"
    echo ""
    echo "Options:"
    echo "  --validate-only    Validate configuration only, do not deploy"
    echo "  --smoke-test       Run smoke tests only"
    echo "  --health-check      Run health check only"
    echo "  --rollback          Rollback to last backup"
    echo "  --cleanup           Clean up old logs and temporary files"
    echo ""
    echo "Examples:"
    echo "  $0 production                    # Deploy to production"
    echo "  $0 staging --validate-only      # Validate staging configuration"
    echo "  $0 dev --smoke-test             # Run dev smoke tests"
}

# Main execution
main() {
    log "INFO" "Starting Channel 66 production deployment for $ENVIRONMENT"
    log "INFO" "Project root: $PROJECT_ROOT"
    
    # Parse command line options
    VALIDATE_ONLY=false
    SMOKE_TEST=false
    HEALTH_CHECK=false
    ROLLBACK=false
    CLEANUP=false
    
    while [[ $# -gt 0 ]]; do
        case $1 in
            --validate-only)
                VALIDATE_ONLY=true
                shift
                ;;
            --smoke-test)
                SMOKE_TEST=true
                shift
                ;;
            --health-check)
                HEALTH_CHECK=true
                shift
                ;;
            --rollback)
                ROLLBACK=true
                shift
                ;;
            --cleanup)
                CLEANUP=true
                shift
                ;;
            --help)
                show_usage
                exit 0
                ;;
            *)
                error_exit "Unknown option: $1. Use --help for usage."
                ;;
        esac
    done
    
    # Execute based on options
    if [[ "$ROLLBACK" == "true" ]]; then
        rollback
    elif [[ "$CLEANUP" == "true" ]]; then
        cleanup
    elif [[ "$HEALTH_CHECK" == "true" ]]; then
        health_check
    elif [[ "$SMOKE_TEST" == "true" ]]; then
        check_prerequisites
        validate_environment
        create_configuration
        run_smoke_tests
    elif [[ "$VALIDATE_ONLY" == "true" ]]; then
        check_prerequisites
        validate_environment
        create_configuration
        validate_configuration
    else
        # Full deployment
        check_prerequisites
        validate_environment
        backup_deployment
        create_configuration
        validate_configuration
        run_smoke_tests
        deploy_production
        health_check
    fi
    
    success "Channel 66 production deployment completed for $ENVIRONMENT"
}

# Trap cleanup on exit
trap cleanup EXIT

# Execute main function
main "$@"
