# Demo1 SMCLab.net - Docker Stack

A simple, production-ready Docker stack with SSL-enabled website and PostgreSQL database.

## 🏗️ Architecture

```
Internet → Nginx (SSL/80,443) → Demo1 Website → PostgreSQL Database
```

## 📁 Project Structure

```
docker-demo/
├── docker-compose.yml    # Container orchestration
├── nginx.conf           # Nginx reverse proxy config
├── deploy.sh            # Automated deployment script
├── init.sql             # Database initialization
└── demo1/
    └── index.html       # Demo website
```

## 🚀 Quick Start

### Prerequisites
- Docker and Docker Compose installed
- Domain `demo1.smclab.net` pointing to your server
- Ports 80 and 443 open

### Deploy
```bash
git clone <repository>
cd docker-demo
./deploy.sh
```

## 🔧 Services

### Nginx Reverse Proxy
- **Image:** nginx:alpine
- **Ports:** 80 (HTTP), 443 (HTTPS)
- **Features:** SSL termination, HTTP→HTTPS redirect, rate limiting

### Demo1 Website
- **Image:** nginx:alpine
- **Content:** Static HTML with modern design
- **Features:** Responsive layout, SSL status display

### PostgreSQL Database
- **Image:** postgres:15-alpine
- **Port:** 5432
- **Database:** demo_db
- **User:** demo_user

### Certbot (SSL)
- **Image:** certbot/certbot
- **Purpose:** Let's Encrypt certificate management
- **Email:** da@madbox.co.uk

## 🌐 Access

- **Primary URL:** https://demo1.smclab.net
- **HTTP URL:** http://demo1.smclab.net (redirects to HTTPS)
- **Health Check:** https://demo1.smclab.net/health

## 🔧 Management Commands

```bash
# View all logs
docker-compose logs -f

# View specific service logs
docker-compose logs nginx
docker-compose logs demo1
docker-compose logs postgres

# Restart services
docker-compose restart

# Stop all services
docker-compose down

# Renew SSL certificate
docker-compose run --rm certbot renew

# Test nginx configuration
docker-compose exec nginx nginx -t

# Access database
docker-compose exec postgres psql -U demo_user -d demo_db

# Check service status
docker-compose ps
```

## 🔒 SSL Certificate Management

### Initial Setup
SSL certificate is automatically obtained during deployment.

### Renewal
Set up automatic renewal with cron:
```bash
# Add to crontab
0 12 * * * cd /path/to/docker-demo && docker-compose run --rm certbot renew && docker-compose exec nginx nginx -s reload
```

### Manual Renewal
```bash
docker-compose run --rm certbot renew
docker-compose exec nginx nginx -s reload
```

## 🗄️ Database

### Connection Details
- **Host:** localhost:5432
- **Database:** demo_db
- **Username:** demo_user
- **Password:** demo_password

### Tables Created
- `demo_info` - Site configuration and metadata
- `visitors` - Visitor tracking (for future use)

### Access Database
```bash
# Connect to database
docker-compose exec postgres psql -U demo_user -d demo_db

# Sample queries
SELECT * FROM demo_info;
SELECT COUNT(*) FROM visitors;
```

## 🛠️ Troubleshooting

### SSL Certificate Issues
```bash
# Check certificate status
docker-compose exec nginx ls -la /etc/letsencrypt/live/demo1.smclab.net/

# View certbot logs
docker-compose logs certbot

# Retry certificate
docker-compose run --rm certbot
```

### Nginx Issues
```bash
# Test configuration
docker-compose exec nginx nginx -t

# Reload configuration
docker-compose exec nginx nginx -s reload

# View error logs
docker-compose logs nginx
```

### Database Issues
```bash
# Check database status
docker-compose exec postgres pg_isready

# View database logs
docker-compose logs postgres

# Connect to database
docker-compose exec postgres psql -U demo_user -d demo_db
```

### Connectivity Issues
```bash
# Test HTTP
curl -v http://demo1.smclab.net

# Test HTTPS
curl -v https://demo1.smclab.net

# Check DNS
nslookup demo1.smclab.net

# Check ports
netstat -tulpn | grep -E ':80|:443'
```

## 🔐 Security Features

- **SSL/TLS:** Let's Encrypt certificates with auto-renewal
- **Security Headers:** HSTS, X-Frame-Options, X-Content-Type-Options
- **Rate Limiting:** Protection against abuse
- **HTTP→HTTPS:** Automatic redirect
- **Network Isolation:** Separate frontend and backend networks

## 📈 Performance Features

- **HTTP/2:** Modern protocol support
- **Gzip Compression:** Reduced bandwidth usage
- **Static Content:** Fast delivery
- **Container Optimization:** Alpine Linux base images

## 🎯 Production Considerations

1. **Monitoring:** Add monitoring tools (Prometheus, Grafana)
2. **Backups:** Implement database backup strategy
3. **Logging:** Configure log aggregation
4. **Scaling:** Consider load balancing for multiple instances
5. **Security:** Regular security updates and scanning

## 📞 Support

For issues or questions:
- Check logs: `docker-compose logs`
- Verify DNS: Ensure demo1.smclab.net points to server
- Check firewall: Ports 80/443 must be open
- SSL issues: Verify domain ownership