# Troubleshooting Guide - SMCLab Docker Stack

## Common Issues and Solutions

### 1. Docker/Docker Compose Not Available
```bash
# Check if Docker is installed and running
docker --version
docker info

# Check if Docker Compose is available
docker-compose --version
# OR for newer versions:
docker compose version
```

### 2. Permission Issues
```bash
# Add user to docker group (then logout/login)
sudo usermod -aG docker $USER

# Or run with sudo
sudo docker-compose up -d
```

### 3. Port Conflicts
```bash
# Check what's using ports 80/443
sudo netstat -tulpn | grep :80
sudo netstat -tulpn | grep :443

# Stop conflicting services
sudo systemctl stop apache2 nginx
```

### 4. DNS/Domain Issues
```bash
# Test DNS resolution
nslookup demo1.smclab.net
dig demo1.smclab.net

# Add to /etc/hosts for testing
echo "127.0.0.1 demo1.smclab.net demo2.smclab.net demo3.smclab.net traefik.smclab.net" >> /etc/hosts
```

### 5. SSL Certificate Issues
```bash
# Check Traefik logs
docker-compose logs traefik

# Ensure acme.json has correct permissions
chmod 600 letsencrypt/acme.json
```

### 6. Container Startup Issues
```bash
# Check container status
docker-compose ps

# View container logs
docker-compose logs [service_name]

# Check available resources
df -h
free -h
```

### 7. Network Issues
```bash
# List Docker networks
docker network ls

# Inspect network
docker network inspect docker-demo_web
docker network inspect docker-demo_backend
```

## Quick Fixes

### Reset Everything
```bash
# Stop and remove all containers
docker-compose down --remove-orphans

# Remove volumes (WARNING: deletes database data)
docker-compose down -v

# Clean up Docker system
docker system prune -a

# Restart deployment
./deploy.sh
```

### Check System Requirements
```bash
# Minimum requirements:
# - 2GB RAM
# - 10GB disk space
# - Docker 20.10+
# - Docker Compose 1.29+

# Check current usage
docker system df
```

### Manual SSL Certificate Generation
If Let's Encrypt fails, you can test with self-signed certificates:

```bash
# Create self-signed certificates (for testing only)
mkdir -p ssl
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout ssl/privkey.pem -out ssl/fullchain.pem \
  -subj "/CN=*.smclab.net"
```

## Service-Specific Issues

### Traefik Not Starting
- Check if ports 80/443 are free
- Verify acme.json permissions (600)
- Check domain DNS resolution

### PostgreSQL Issues
- Ensure sufficient disk space
- Check container logs for initialization errors
- Verify volume permissions

### Demo Sites Not Accessible
- Confirm Traefik is running and healthy
- Check service labels in docker-compose.yml
- Verify network connectivity

## Debugging Commands

```bash
# Container resource usage
docker stats

# Detailed container inspection
docker inspect [container_name]

# Follow logs in real-time
docker-compose logs -f

# Execute commands in running container
docker-compose exec postgres psql -U demo_user -d demo_db
docker-compose exec traefik traefik version
```

## Getting Help

Please provide the following information when reporting issues:

1. **Operating System**: `uname -a`
2. **Docker Version**: `docker --version`
3. **Docker Compose Version**: `docker-compose --version`
4. **Error Output**: Full error messages
5. **Container Status**: `docker-compose ps`
6. **Container Logs**: `docker-compose logs [failing-service]`
7. **System Resources**: `free -h && df -h`