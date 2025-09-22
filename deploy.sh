#!/bin/bash

# SMCLab.net Demo Stack Deployment Script (Nginx Proxy)
set -e

echo "🚀 Deploying SMCLab.net Demo Stack with Nginx..."

# Check if Docker is running
if ! docker info >/dev/null 2>&1; then
    echo "❌ Docker is not running. Please start Docker and try again."
    exit 1
fi

# Check if Docker Compose is available
if ! command -v docker-compose >/dev/null 2>&1; then
    echo "❌ Docker Compose is not installed. Please install Docker Compose and try again."
    exit 1
fi

# Create necessary directories
echo "📁 Creating directories..."
mkdir -p nginx/conf.d nginx/logs

# Pull latest images
echo "📦 Pulling latest Docker images..."
docker-compose pull

# Stop any existing containers
echo "🛑 Stopping existing containers..."
docker-compose down --remove-orphans

# Start the stack
echo "🔧 Starting the Docker stack..."
docker-compose up -d

# Wait for services to be ready
echo "⏳ Waiting for services to start..."
sleep 15

# Test nginx configuration
echo "🔍 Testing Nginx configuration..."
docker-compose exec nginx-proxy nginx -t || echo "⚠️  Nginx config test failed"

# Check service status
echo "📊 Checking service status..."
docker-compose ps

# Test connectivity
echo "🌐 Testing connectivity..."
curl -s -o /dev/null -w "%{http_code}" http://localhost/ || echo "❌ HTTP test failed"

echo ""
echo "✅ Deployment complete!"
echo ""
echo "🌐 Your services are available at:"
echo "   • http://demo1.smclab.net"
echo "   • http://demo2.smclab.net"
echo "   • http://demo3.smclab.net"
echo "   • http://localhost (default page)"
echo ""
echo "📋 Database Info:"
echo "   • Host: localhost:5432"
echo "   • Database: demo_db"
echo "   • User: demo_user"
echo "   • Password: demo_password"
echo ""
echo "🔧 Useful commands:"
echo "   • View logs: docker-compose logs -f"
echo "   • View nginx logs: docker-compose logs nginx-proxy"
echo "   • Test nginx config: docker-compose exec nginx-proxy nginx -t"
echo "   • Reload nginx: docker-compose exec nginx-proxy nginx -s reload"
echo "   • Stop stack: docker-compose down"
echo "   • Restart: docker-compose restart"
echo ""
echo "💡 Tips:"
echo "   • Add entries to /etc/hosts for local testing:"
echo "     127.0.0.1 demo1.smclab.net demo2.smclab.net demo3.smclab.net"
echo "   • Check nginx status: curl http://localhost/nginx_status"
echo "   • Health check: curl http://localhost/health"