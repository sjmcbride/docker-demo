#!/bin/bash

# Demo1 SMCLab.net Deployment Script (SSL Fixed)
set -e

echo "🚀 Deploying Demo1 with SSL Fix (demo1.smclab.net)"
echo "================================================="

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

# Start nginx with HTTP-only config first
echo "🌐 Starting Nginx with HTTP-only configuration..."
cp nginx-http-only.conf nginx-current.conf
docker-compose up -d nginx

# Wait for nginx to start
sleep 10

# Test HTTP connectivity
echo "🔍 Testing HTTP connectivity..."
if curl -s -o /dev/null -w "%{http_code}" http://localhost/ | grep -q "200"; then
    echo "✅ HTTP is working"
else
    echo "❌ HTTP test failed"
    docker-compose logs nginx
    exit 1
fi

# Now try to get SSL certificate
echo "🔒 Requesting SSL certificate..."
if docker-compose run --rm certbot; then
    echo "✅ SSL certificate obtained successfully!"

    # Switch to SSL configuration
    echo "🔄 Switching to SSL configuration..."
    cp nginx.conf nginx-current.conf
    docker-compose exec nginx nginx -s reload

    # Test HTTPS
    echo "🔍 Testing HTTPS..."
    sleep 5
    if curl -k -s https://demo1.smclab.net/health | grep -q "healthy"; then
        echo "✅ HTTPS is working!"
    else
        echo "⚠️  HTTPS test failed, but certificate was obtained"
    fi

else
    echo "❌ SSL certificate request failed"
    echo ""
    echo "🔍 Troubleshooting information:"
    echo "1. Check if demo1.smclab.net resolves to this server:"
    nslookup demo1.smclab.net || echo "❌ DNS resolution failed"

    echo ""
    echo "2. Check if port 80 is accessible from internet:"
    echo "   Try: curl http://demo1.smclab.net"

    echo ""
    echo "3. Check nginx logs:"
    docker-compose logs nginx

    echo ""
    echo "4. Check certbot logs:"
    docker-compose logs certbot

    echo ""
    echo "The website is still available via HTTP at: http://demo1.smclab.net"
    echo "You can retry SSL later with: docker-compose run --rm certbot"
fi

# Final status check
echo ""
echo "📊 Final status check..."
docker-compose ps

echo ""
echo "✅ Deployment Complete!"
echo "======================="
echo ""
if [ -f "/var/lib/docker/volumes/docker-demo_certbot_certs/_data/live/demo1.smclab.net/fullchain.pem" ]; then
    echo "🌐 Website URLs:"
    echo "   • https://demo1.smclab.net (Primary - with SSL)"
    echo "   • http://demo1.smclab.net (Redirects to HTTPS)"
else
    echo "🌐 Website URLs:"
    echo "   • http://demo1.smclab.net (HTTP only - SSL setup incomplete)"
fi
echo ""
echo "📋 Database Connection:"
echo "   • Host: localhost:5432"
echo "   • Database: demo_db"
echo "   • Username: demo_user"
echo "   • Password: demo_password"
echo ""
echo "🔧 Management Commands:"
echo "   • Retry SSL: docker-compose run --rm certbot"
echo "   • View logs: docker-compose logs -f"
echo "   • Restart: docker-compose restart"
echo "   • Stop: docker-compose down"
echo ""
echo "⚠️  SSL Troubleshooting:"
echo "   • Ensure demo1.smclab.net DNS points to this server: $(curl -s ifconfig.me)"
echo "   • Ensure ports 80 and 443 are open in firewall"
echo "   • Check domain from external location: curl http://demo1.smclab.net"