# SMC Tech Lab Demo Sites

A Docker-based multi-site deployment featuring 4 professional technology laboratory demo sites with modern dark theme interfaces, SSL termination, and PostgreSQL authentication.

## 🔬 Overview

This project deploys 4 professional tech laboratory demo sites:
- **demo1.smclab.net** - SMC Tech Lab 1
- **demo2.smclab.net** - SMC Tech Lab 2
- **demo3.smclab.net** - SMC Tech Lab 3
- **demo4.smclab.net** - SMC Tech Lab 4

Each site features:
- Modern dark tech theme with animated circuit elements
- Professional SMC branding with metallic silver and green accents
- User authentication via PostgreSQL
- SSL/TLS certificates via Let's Encrypt
- Domain-based routing with nginx-proxy

## 🏗️ Architecture

```
nginx-proxy (Port 80/443)
├── SSL Termination (Let's Encrypt)
├── Domain Routing
└── Backend Services
    ├── demo1 (PHP-FPM + Nginx) - SMC Tech Lab 1
    ├── demo2 (PHP-FPM + Nginx) - SMC Tech Lab 2
    ├── demo3 (PHP-FPM + Nginx) - SMC Tech Lab 3
    ├── demo4 (PHP-FPM + Nginx) - SMC Tech Lab 4
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
- **Theme**: Modern SMC Tech Lab

## 👨‍💻 Authentication

### Default Users
| Username | Password | Access |
|----------|----------|---------|
| `demo1user` | `demo1pass` | SMC Tech Lab 1 |
| `demo2user` | `demo2pass` | SMC Tech Lab 2 |
| `demo3user` | `demo3pass` | SMC Tech Lab 3 |
| `demo4user` | `demo4pass` | SMC Tech Lab 4 |
| `admin` | `adminpass` | All labs |

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

## 🎨 Design System

### SMC Tech Lab Theme
- **Brand Colors**:
  - Primary: Metallic Silver (#95a5a6, #ecf0f1, #bdc3c7)
  - Accent: Tech Green (#2ed573, #1dd1a1)
  - Background: Dark Blue-Gray (#2c3e50, #34495e, #0f1419)
- **Visual Style**: Professional, modern, technology-focused
- **Elements**:
  - Flowing circuit animations with green glow
  - Pulsing network nodes
  - Subtle grid overlays
  - Glowing borders with fade effects
  - Professional authentication forms

### Animated Elements
- **Circuit Lines**: Flowing green-lit pathways across the background
- **Network Nodes**: Pulsing connection points with green glow
- **Grid System**: Subtle background grid for tech aesthetic
- **Border Glow**: Animated glowing borders on containers
- **Smooth Transitions**: Hover effects and form interactions

## 📝 Typography

### Font Family Stack

#### Primary Heading Font: **Orbitron**
```css
font-family: 'Orbitron', monospace;
```
- **Usage**: Main headings (h1), SMC logo, authentication buttons
- **Weights**: 400 (Regular), 700 (Bold), 900 (Black)
- **Character**: Futuristic, sci-fi inspired, technology-focused
- **Source**: Google Fonts
- **Fallbacks**: monospace

**Example Applications:**
- SMC logo display
- Main site titles (SMC TECH LAB 1, 2, 3, 4)
- Authentication form buttons
- Portal access headings

#### Body Font: **Exo 2**
```css
font-family: 'Exo 2', sans-serif;
```
- **Usage**: Body text, descriptions, form inputs, status messages
- **Weights**: 300 (Light), 400 (Regular), 600 (Semi-Bold)
- **Character**: Clean, modern, highly readable
- **Source**: Google Fonts
- **Fallbacks**: sans-serif

**Example Applications:**
- Welcome messages and system status
- Form input fields and placeholders
- Demo credential information
- General body content

### Typography Hierarchy

#### Level 1: SMC Logo
```css
font-family: 'Orbitron', monospace;
font-size: 4em;
font-weight: 900;
background: linear-gradient(45deg, #95a5a6 0%, #ecf0f1 30%, #2ed573 60%, #1dd1a1 100%);
background-clip: text;
-webkit-text-fill-color: transparent;
letter-spacing: 0.1em;
```

#### Level 2: Main Headings (Lab Names)
```css
font-family: 'Orbitron', monospace;
font-size: 2.5em;
font-weight: 700;
color: #ecf0f1;
text-shadow: 0 0 20px rgba(46, 213, 115, 0.4);
letter-spacing: 0.15em;
```

#### Level 3: Section Headers
```css
font-family: 'Orbitron', monospace;
font-size: 1.8em;
font-weight: 700;
color: #ecf0f1;
letter-spacing: 0.15em;
```

#### Level 4: Sub-Headers
```css
font-family: 'Orbitron', monospace;
font-size: 1.4em;
font-weight: 400;
color: #2ed573;
letter-spacing: 1px;
```

#### Body Text: Regular Content
```css
font-family: 'Exo 2', sans-serif;
font-size: 1.2em;
font-weight: 300;
color: #bdc3c7;
line-height: 1.6;
```

#### Status Text: System Information
```css
font-family: 'Exo 2', sans-serif;
font-size: 1.2em;
font-weight: 600;
color: #2ed573;
```

#### Form Elements: Inputs and Buttons
```css
/* Input Fields */
font-family: 'Exo 2', sans-serif;
font-size: 16px;
color: #ecf0f1;

/* Buttons */
font-family: 'Orbitron', monospace;
font-size: 18px;
font-weight: 700;
letter-spacing: 1px;
```

### Typography Guidelines

#### Do's ✅
- Use Orbitron for all tech-focused, important headings
- Use Exo 2 for readable body content and forms
- Maintain consistent letter-spacing for Orbitron (0.1em - 0.15em)
- Apply green accent color (#2ed573) to status and active elements
- Use text shadows for headings to enhance the tech aesthetic
- Ensure sufficient contrast against dark backgrounds

#### Don'ts ❌
- Don't mix additional font families beyond Orbitron and Exo 2
- Don't use Orbitron for long-form body text (readability issues)
- Don't ignore letter-spacing - it's crucial for the tech aesthetic
- Don't use light font weights on dark backgrounds without proper contrast
- Don't break the established hierarchy

### Responsive Typography

#### Desktop (1200px+)
- SMC Logo: 4em
- Main Headings: 2.5em
- Body Text: 1.2em

#### Tablet (768px - 1199px)
- SMC Logo: 3.5em
- Main Headings: 2em
- Body Text: 1.1em

#### Mobile (< 768px)
- SMC Logo: 2.5em
- Main Headings: 1.5em
- Body Text: 1em

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
│   ├── index.php              # SMC Tech Lab 1 main page
│   └── login.php              # Authentication portal
├── demo2/                     # SMC Tech Lab 2
├── demo3/                     # SMC Tech Lab 3
├── demo4/                     # SMC Tech Lab 4
├── update_themes.sh           # Theme update utility
└── README.md                  # This file
```

## 🔧 Configuration

### Environment Variables
- `LETSENCRYPT_EMAIL`: Email for SSL certificates (`da@madbox.co.uk`)
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

### Adding New Tech Labs
1. Copy an existing demo directory
2. Update `docker-compose.yml` with new service
3. Add domain configuration (VIRTUAL_HOST, LETSENCRYPT_HOST)
4. Update database users and site numbering
5. Modify branding to reflect new lab number

### Theme Customization
- **Colors**: Modify CSS custom properties for brand colors
- **Animations**: Adjust keyframe animations for circuit flows
- **Typography**: Update font imports and CSS font declarations
- **Logo**: Modify gradient backgrounds in `.logo .sc` class

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
- Review typography consistency across updates

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
- Professional authentication forms prevent basic attacks

## 📞 Support

For issues or questions:
1. Check logs: `docker-compose logs [service-name]`
2. Verify configuration in `docker-compose.yml`
3. Test individual components
4. Review this README for troubleshooting steps
5. Check typography rendering across different browsers

## 🎯 Future Enhancements

- [ ] Add admin panel for user management
- [ ] Implement role-based access control
- [ ] Add monitoring/metrics dashboard
- [ ] Create automated backup system
- [ ] Develop additional SMC Tech Lab themes
- [ ] Add responsive typography improvements
- [ ] Implement advanced authentication methods
- [ ] Create brand style guide documentation