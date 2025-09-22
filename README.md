# SMC Laboratory Demo Sites

A Docker-based multi-site deployment featuring 4 demo websites with mad scientist themed interfaces, SSL termination, and PostgreSQL authentication.

## 🧪 Overview

This project deploys 4 fun, cartoon-styled laboratory demo sites:
- **demo1.smclab.net** - Chamber One
- **demo2.smclab.net** - Chamber Two
- **demo3.smclab.net** - Chamber Three
- **demo4.smclab.net** - Chamber Four

Each site features:
- Mad scientist themed UI with animated lab equipment
- User authentication via PostgreSQL
- SSL/TLS certificates via Let's Encrypt
- Domain-based routing with nginx-proxy

## 🔬 Architecture

```
nginx-proxy (Port 80/443)
├── SSL Termination (Let's Encrypt)
├── Domain Routing
└── Backend Services
    ├── demo1 (PHP-FPM + Nginx)
    ├── demo2 (PHP-FPM + Nginx)
    ├── demo3 (PHP-FPM + Nginx)
    ├── demo4 (PHP-FPM + Nginx)
    └── PostgreSQL Database
```

## 🚀 Quick Start

### Prerequisites
- Docker & Docker Compose
- Domain names pointing to your server:
  - `demo1.smclab.net`
  - `demo2.smclab.net`
  - `demo3.smclab.net`
  - `demo4.smclab.net`

### Deployment

1. **Clone and Navigate**
   ```bash
   git clone <repository-url>
   cd docker-demo
   ```

2. **Start Services**
   ```bash
   docker-compose up -d
   ```

3. **Verify Deployment**
   ```bash
   docker-compose ps
   docker-compose logs nginx-proxy
   ```

4. **Access Sites**
   - Visit any demo site (e.g., https://demo1.smclab.net)
   - SSL certificates will be automatically generated

## 🧬 Services

### nginx-proxy
- **Image**: `nginxproxy/nginx-proxy:latest`
- **Function**: Reverse proxy with automatic SSL
- **Ports**: 80, 443
- **Volumes**: Docker socket, SSL certificates

### nginx-proxy-companion
- **Image**: `nginxproxy/acme-companion:latest`
- **Function**: Let's Encrypt SSL automation
- **Email**: `da@madbox.co.uk`

### PostgreSQL Database
- **Image**: `postgres:13`
- **Database**: `labsites`
- **User**: `postgres`
- **Password**: `labpassword123`
- **Port**: 5432 (internal)

### Demo Sites (demo1-4)
- **Base Image**: `php:8.1-fpm`
- **Web Server**: Nginx
- **Features**: PHP sessions, PostgreSQL connectivity
- **Theme**: Mad scientist laboratory

## 👨‍🔬 Authentication

### Default Users
| Username | Password | Access |
|----------|----------|---------|
| `demo1user` | `demo1pass` | demo1.smclab.net |
| `demo2user` | `demo2pass` | demo2.smclab.net |
| `demo3user` | `demo3pass` | demo3.smclab.net |
| `demo4user` | `demo4pass` | demo4.smclab.net |
| `admin` | `adminpass` | All sites |

### Database Schema
```sql
-- Users table
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    site VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sessions table
CREATE TABLE sessions (
    id VARCHAR(128) PRIMARY KEY,
    user_id INTEGER REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP
);
```

## 🎨 Theme Features

### Mad Scientist Design
- **Colors**: Bright pink, teal, yellow, green gradients
- **Fonts**: Fredoka One (headings), Comic Neue (body)
- **Animations**:
  - Floating bubbles rising from bottom
  - Bubbling beakers with scaling effects
  - Shaking test tubes
  - Wiggling containers
  - Bouncing emoji decorations

### UI Elements
- Rounded, colorful containers with multiple borders
- Interactive form elements with hover/focus effects
- Animated background gradients
- Lab equipment positioning and animations
- Cartoon-style error messages

## 📁 Project Structure

```
docker-demo/
├── docker-compose.yml          # Main orchestration
├── database/
│   └── init.sql               # Database initialization
├── demo1/
│   ├── Dockerfile
│   ├── nginx.conf
│   ├── config.php             # Database connection
│   ├── index.php              # Main page
│   └── login.php              # Authentication
├── demo2/                     # Same structure as demo1
├── demo3/                     # Same structure as demo1
├── demo4/                     # Same structure as demo1
└── README.md                  # This file
```

## 🔧 Configuration

### Environment Variables
- `LETSENCRYPT_EMAIL`: Email for SSL certificates
- `VIRTUAL_HOST`: Domain routing configuration
- `LETSENCRYPT_HOST`: SSL certificate domains

### Database Connection
Each demo site includes `config.php` with:
```php
$host = 'postgres';
$dbname = 'labsites';
$username = 'postgres';
$password = 'labpassword123';
```

## 🛠️ Development

### Adding New Sites
1. Copy an existing demo directory
2. Update `docker-compose.yml` with new service
3. Add domain configuration (VIRTUAL_HOST, LETSENCRYPT_HOST)
4. Update database users if needed

### Theme Customization
- Modify CSS in `index.php` and `login.php`
- Animation keyframes in CSS `@keyframes` sections
- Color schemes in CSS variables

### Database Management
```bash
# Access PostgreSQL
docker-compose exec postgres psql -U postgres -d labsites

# View logs
docker-compose logs postgres

# Backup database
docker-compose exec postgres pg_dump -U postgres labsites > backup.sql
```

## 🐛 Troubleshooting

### SSL Certificate Issues
```bash
# Check nginx-proxy logs
docker-compose logs nginx-proxy

# Check acme-companion logs
docker-compose logs nginx-proxy-companion

# Force certificate renewal
docker-compose restart nginx-proxy-companion
```

### Database Connection Problems
```bash
# Check PostgreSQL status
docker-compose logs postgres

# Test database connection
docker-compose exec postgres psql -U postgres -d labsites -c "SELECT NOW();"

# Reset database (⚠️ destroys data)
docker-compose down -v
docker-compose up -d
```

### Site Access Issues
```bash
# Check all services
docker-compose ps

# Check individual site logs
docker-compose logs demo1

# Verify domain resolution
nslookup demo1.smclab.net
```

## 📝 Maintenance

### Regular Tasks
- Monitor SSL certificate expiration (automatic renewal)
- Check disk space for logs and databases
- Update Docker images periodically
- Backup database regularly

### Updates
```bash
# Pull latest images
docker-compose pull

# Recreate services
docker-compose up -d --force-recreate
```

## 🔒 Security Notes

- Default passwords should be changed in production
- Database is only accessible internally
- SSL certificates auto-renew via Let's Encrypt
- Sessions expire and are managed securely
- No sensitive data exposed in logs

## 📞 Support

For issues or questions:
1. Check logs: `docker-compose logs [service-name]`
2. Verify configuration in `docker-compose.yml`
3. Test individual components
4. Review this README for troubleshooting steps

## 🎯 Future Enhancements

- [ ] Add admin panel for user management
- [ ] Implement role-based access control
- [ ] Add monitoring/metrics dashboard
- [ ] Create automated backup system
- [ ] Add more laboratory themes/variations