#!/bin/bash

# Demo1 SMCLab.net Deployment Script
set -e

echo "🚀 Deploying Demo1 with SSL (demo1.smclab.net)"
echo "=============================================="

# Check requirements
if ! command -v docker >/dev/null 2>&1; then
    echo "❌ Docker is not installed"
    exit 1
fi

if ! command -v docker-compose >/dev/null 2>&1; then
    echo "❌ Docker Compose is not installed"
    exit 1
fi

if ! docker info >/dev/null 2>&1; then
    echo "❌ Docker is not running"
    exit 1
fi

# Stop existing containers
echo "🛑 Stopping any existing containers..."
docker-compose down --remove-orphans 2>/dev/null || true

# Start database and demo1 first
echo "🗄️ Starting PostgreSQL database..."
docker-compose up -d postgres

echo "🌐 Starting demo1 website..."
docker-compose up -d demo1

# Wait for services to be ready
echo "⏳ Waiting for services to initialize..."
sleep 15

# Test if services are responding
echo "🔍 Testing service connectivity..."
if docker-compose exec demo1 wget -q --spider http://localhost/; then
    echo "✅ Demo1 website is responding"
else
    echo "❌ Demo1 website is not responding"
    echo "Logs:"
    docker-compose logs demo1
    exit 1
fi

# Get SSL certificate
echo "🔒 Obtaining SSL certificate..."
if docker-compose run --rm certbot; then
    echo "✅ SSL certificate obtained successfully"
else
    echo "⚠️  SSL certificate failed, but continuing..."
    echo "You can retry SSL later with: docker-compose run --rm certbot"
fi

# Start nginx proxy
echo "🌐 Starting Nginx reverse proxy..."
docker-compose up -d nginx

# Wait a bit more for nginx to start
sleep 10

# Final status check
echo "📊 Final status check..."
docker-compose ps

echo ""
echo "✅ Deployment Complete!"
echo "======================="
echo ""
echo "🌐 Website URLs:"
echo "   • https://demo1.smclab.net (Primary - with SSL)"
echo "   • http://demo1.smclab.net (Redirects to HTTPS)"
echo ""
echo "📋 Database Connection:"
echo "   • Host: localhost:5432"
echo "   • Database: demo_db"
echo "   • Username: demo_user"
echo "   • Password: demo_password"
echo ""
echo "🔧 Management Commands:"
echo "   • View all logs: docker-compose logs -f"
echo "   • View nginx logs: docker-compose logs nginx"
echo "   • View website logs: docker-compose logs demo1"
echo "   • Renew SSL: docker-compose run --rm certbot renew"
echo "   • Restart stack: docker-compose restart"
echo "   • Stop stack: docker-compose down"
echo ""
echo "🔍 Health Checks:"
echo "   • Nginx config test: docker-compose exec nginx nginx -t"
echo "   • Database connection: docker-compose exec postgres pg_isready"
echo "   • Website health: curl -k https://demo1.smclab.net/health"
echo ""
echo "⚠️  Important Notes:"
echo "   • Ensure demo1.smclab.net DNS points to this server"
echo "   • Ports 80 and 443 must be open in firewall"
echo "   • SSL certificate will auto-renew via cron (recommended)"
echo ""

# Test final connectivity
echo "🧪 Testing final connectivity..."
if curl -s -o /dev/null -w "%{http_code}" http://localhost/ | grep -q "200\|301\|302"; then
    echo "✅ HTTP is working"
else
    echo "⚠️  HTTP test failed"
fi

echo ""
echo "🎉 Demo1 is now live at https://demo1.smclab.net"