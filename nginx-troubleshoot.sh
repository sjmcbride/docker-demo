#!/bin/bash

echo "🔧 Nginx Troubleshooting Script"
echo "================================"

echo "📋 1. Container Status:"
docker-compose ps

echo ""
echo "🌐 2. Network Connectivity:"
echo "Testing if nginx-proxy can reach backend services..."
docker-compose exec nginx-proxy ping -c 2 demo1 || echo "❌ Cannot reach demo1"
docker-compose exec nginx-proxy ping -c 2 demo2 || echo "❌ Cannot reach demo2"
docker-compose exec nginx-proxy ping -c 2 demo3 || echo "❌ Cannot reach demo3"

echo ""
echo "🔍 3. Nginx Configuration Test:"
docker-compose exec nginx-proxy nginx -t

echo ""
echo "📝 4. Nginx Process Status:"
docker-compose exec nginx-proxy ps aux | grep nginx

echo ""
echo "🌐 5. Testing HTTP Responses:"
echo "Testing localhost..."
curl -v http://localhost/ || echo "❌ Localhost test failed"

echo ""
echo "Testing with Host header..."
curl -H "Host: demo1.smclab.net" http://localhost/ || echo "❌ demo1 test failed"

echo ""
echo "📊 6. Recent Nginx Logs:"
echo "--- Access Logs ---"
docker-compose logs --tail=10 nginx-proxy | grep -E "(GET|POST|PUT|DELETE)" || echo "No access logs yet"

echo ""
echo "--- Error Logs ---"
docker-compose logs --tail=10 nginx-proxy | grep -i error || echo "No error logs"

echo ""
echo "🔧 7. Backend Service Tests:"
echo "Testing demo1 directly..."
docker-compose exec demo1 curl -s http://localhost/ | head -5 || echo "❌ demo1 internal test failed"

echo "Testing demo2 directly..."
docker-compose exec demo2 curl -s http://localhost/ | head -5 || echo "❌ demo2 internal test failed"

echo "Testing demo3 directly..."
docker-compose exec demo3 curl -s http://localhost/ | head -5 || echo "❌ demo3 internal test failed"

echo ""
echo "🎯 8. Quick Fixes to Try:"
echo "   • Restart nginx: docker-compose restart nginx-proxy"
echo "   • Reload config: docker-compose exec nginx-proxy nginx -s reload"
echo "   • Check logs: docker-compose logs nginx-proxy"
echo "   • Test config: docker-compose exec nginx-proxy nginx -t"