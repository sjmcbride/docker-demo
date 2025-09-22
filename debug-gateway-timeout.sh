#!/bin/bash

echo "🔍 Debugging Gateway Timeout Issues"
echo "===================================="

echo "📋 1. Checking container status..."
docker-compose ps

echo ""
echo "🌐 2. Checking networks..."
docker network ls | grep docker-demo

echo ""
echo "📊 3. Checking Traefik logs (last 20 lines)..."
docker-compose logs --tail=20 traefik

echo ""
echo "🔍 4. Checking demo container logs..."
echo "--- Demo1 logs ---"
docker-compose logs --tail=5 demo1

echo "--- Demo2 logs ---"
docker-compose logs --tail=5 demo2

echo "--- Demo3 logs ---"
docker-compose logs --tail=5 demo3

echo ""
echo "🔧 5. Testing internal connectivity..."
echo "Testing if Traefik can reach demo1..."
docker-compose exec traefik wget -qO- http://demo1/ || echo "❌ Cannot reach demo1"

echo ""
echo "🏥 6. Checking container health..."
docker-compose exec demo1 ps aux || echo "❌ Demo1 not responding"
docker-compose exec demo2 ps aux || echo "❌ Demo2 not responding"
docker-compose exec demo3 ps aux || echo "❌ Demo3 not responding"

echo ""
echo "📝 7. Checking nginx status inside containers..."
docker-compose exec demo1 nginx -t || echo "❌ Demo1 nginx config error"

echo ""
echo "🔍 8. Inspecting Traefik configuration..."
docker-compose exec traefik cat /etc/traefik/traefik.yml || echo "Using CLI config"

echo ""
echo "🌐 9. Testing port availability..."
curl -I http://localhost/ || echo "❌ Port 80 not responding"
curl -I https://localhost/ || echo "❌ Port 443 not responding"

echo ""
echo "🔧 10. Quick fixes to try:"
echo "   - Restart containers: docker-compose restart"
echo "   - Check DNS: ping demo1.smclab.net"
echo "   - Verify domain points to server IP"
echo "   - Check firewall: sudo ufw status"