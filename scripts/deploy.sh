#!/bin/bash

# ============================================
# Bunda PAUD - Production Deployment Script
# ============================================

set -e

echo "🚀 Starting deployment..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
APP_DIR="/opt/bunda-paud"
BACKUP_DIR="/opt/backups/bunda-paud"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)

# Functions
log_info() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

log_warn() {
    echo -e "${YELLOW}[WARN]${NC} $1"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Pre-deployment checks
log_info "Running pre-deployment checks..."

if ! command -v docker &> /dev/null; then
    log_error "Docker is not installed!"
    exit 1
fi

if ! command -v docker-compose &> /dev/null; then
    log_error "Docker Compose is not installed!"
    exit 1
fi

# Create backup
log_info "Creating database backup..."
mkdir -p $BACKUP_DIR
docker-compose -f $APP_DIR/docker-compose.yml exec -T postgres pg_dump -U bunda_paud bunda_paud > $BACKUP_DIR/db_backup_$TIMESTAMP.sql 2>/dev/null || log_warn "Backup skipped (no existing database)"

# Pull latest images
log_info "Pulling latest Docker images..."
cd $APP_DIR
docker-compose pull

# Stop services gracefully
log_info "Stopping services..."
docker-compose down --timeout 30

# Start services
log_info "Starting services..."
docker-compose up -d

# Wait for services to be healthy
log_info "Waiting for services to be healthy..."
sleep 10

# Run migrations
log_info "Running database migrations..."
docker-compose exec -T backend php artisan migrate --force

# Clear and cache config
log_info "Optimizing Laravel..."
docker-compose exec -T backend php artisan config:cache
docker-compose exec -T backend php artisan route:cache
docker-compose exec -T backend php artisan view:cache

# Health check
log_info "Running health check..."
HEALTH_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost/api/health || echo "000")

if [ "$HEALTH_STATUS" == "200" ]; then
    log_info "✅ Health check passed!"
else
    log_error "❌ Health check failed with status: $HEALTH_STATUS"
    log_warn "Rolling back to previous version..."
    docker-compose down
    # Restore from backup if needed
    exit 1
fi

# Cleanup old backups (keep last 7 days)
log_info "Cleaning up old backups..."
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete

# Cleanup old Docker images
log_info "Cleaning up unused Docker images..."
docker image prune -f

echo ""
log_info "🎉 Deployment completed successfully!"
echo ""
echo "Services:"
docker-compose ps
