# SMC Tech Lab Demo Sites

A Docker-based multi-site deployment featuring 4 professional technology laboratory demo sites with SSL termination and PostgreSQL authentication.

## Overview

This project deploys 4 tech lab demo sites:
- **demo1.smclab.net** - SMC Tech Lab 1
- **demo2.smclab.net** - SMC Tech Lab 2
- **demo3.smclab.net** - SMC Tech Lab 3
- **demo4.smclab.net** - SMC Tech Lab 4

Features:
- Modern dark tech theme with animated elements
- Professional SMC branding
- User authentication via PostgreSQL
- Automatic SSL certificates via Let's Encrypt
- Domain-based routing

## Architecture

```
Internet → nginx-proxy (80/443) → SSL Termination → Demo Sites (1-4) → PostgreSQL
```

| Service | Image | Function |
|---------|-------|----------|
| **nginx-proxy** | `nginxproxy/nginx-proxy` | Reverse proxy & SSL termination |
| **nginx-proxy-companion** | `nginxproxy/acme-companion` | Let's Encrypt automation |
| **demo1-4** | `php:8.1-fpm` + nginx | SMC Tech Lab sites |
| **postgres** | `postgres:13` | Authentication database |

## Quick Start

### Prerequisites
- Docker & Docker Compose
- Domain names pointing to your server

### Deployment

1. **Start Services**
   ```bash
   docker-compose up -d
   ```

2. **Access Sites**
   - Visit https://demo1.smclab.net
   - SSL certificates will be automatically generated

## Default Users

| Username | Password | Access |
|----------|----------|---------|
| `demo1user` | `demo1pass` | SMC Tech Lab 1 |
| `demo2user` | `demo2pass` | SMC Tech Lab 2 |
| `demo3user` | `demo3pass` | SMC Tech Lab 3 |
| `demo4user` | `demo4pass` | SMC Tech Lab 4 |
| `admin` | `adminpass` | All labs |

## Configuration

### Environment Variables
- `LETSENCRYPT_EMAIL`: Email for SSL certificates (`da@madbox.co.uk`)
- `VIRTUAL_HOST`: Domain routing configuration
- `LETSENCRYPT_HOST`: SSL certificate domains

### Database
- **Database**: `labsites`
- **User**: `postgres`
- **Password**: `labpassword123`

## Troubleshooting

### SSL Issues
```bash
docker-compose logs nginx-proxy
docker-compose logs nginx-proxy-companion
```

### Database Issues
```bash
docker-compose logs postgres
docker-compose exec postgres psql -U postgres -d labsites
```

### General Issues
```bash
docker-compose ps
docker-compose logs [service-name]
```

## Security Notes

- Change default passwords in production
- Database is only accessible internally
- SSL certificates auto-renew
- Sessions are managed securely