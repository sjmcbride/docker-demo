#!/bin/bash

# SSL Setup Script for Demo1
set -e

echo "🔒 Setting up SSL for demo1.smclab.net"
echo "======================================"

# First, start with HTTP-only nginx to handle Let's Encrypt challenges
echo "1️⃣ Starting HTTP-only nginx for certificate challenges..."
docker-compose down nginx 2>/dev/null || true

# Update nginx to use HTTP-only config
docker-compose exec nginx cp /etc/nginx/nginx-http-only.conf /etc/nginx/nginx.conf 2>/dev/null || {
    # If nginx isn't running, start it with HTTP-only config
    docker run --rm -v "$(pwd)/nginx-http-only.conf:/etc/nginx/nginx.conf:ro" \
               -v "$(pwd)/nginx.conf:/etc/nginx/nginx-ssl.conf:ro" \
               --network docker-demo_web \
               -p 80:80 \
               --name nginx-temp \
               -d nginx:alpine
    sleep 5
}

# Test that the challenge path is accessible
echo "2️⃣ Testing webroot accessibility..."
mkdir -p webroot/.well-known/acme-challenge
echo "test" > webroot/.well-known/acme-challenge/test
docker-compose exec nginx mkdir -p /var/www/certbot/.well-known/acme-challenge || true

# Get the SSL certificate
echo "3️⃣ Requesting SSL certificate..."
if docker-compose run --rm certbot; then
    echo "✅ SSL certificate obtained successfully!"

    # Stop temporary nginx if it was started
    docker stop nginx-temp 2>/dev/null || true

    # Now start nginx with full SSL config
    echo "4️⃣ Starting nginx with SSL configuration..."
    docker-compose up -d nginx

    # Wait for nginx to start
    sleep 10

    # Test HTTPS
    echo "5️⃣ Testing HTTPS connectivity..."
    if curl -k -s https://demo1.smclab.net/health | grep -q "healthy"; then
        echo "✅ HTTPS is working!"
    else
        echo "⚠️  HTTPS test failed, but SSL certificate was obtained"
    fi

else
    echo "❌ SSL certificate request failed"
    echo "Let's troubleshoot..."

    echo "Testing HTTP connectivity to demo1.smclab.net..."
    curl -v http://demo1.smclab.net/.well-known/acme-challenge/test 2>&1 || echo "❌ HTTP test failed"

    echo "Checking DNS resolution..."
    nslookup demo1.smclab.net || echo "❌ DNS resolution failed"

    echo "Checking if port 80 is accessible..."
    nc -zv demo1.smclab.net 80 2>&1 || echo "❌ Port 80 not accessible"

    exit 1
fi

echo ""
echo "🎉 SSL setup complete!"
echo "Website: https://demo1.smclab.net"