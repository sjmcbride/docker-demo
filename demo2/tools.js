// Tools functionality for Demo2
document.addEventListener('DOMContentLoaded', function() {
    initializeTools();
});

// Command execution functions
function executeCommand(command) {
    showNotification(`Executing: ${command}`, 'info');

    // Simulate command execution based on command type
    let output = '';

    switch(command) {
        case 'docker ps':
            output = `CONTAINER ID   IMAGE              COMMAND                  CREATED        STATUS        PORTS                                      NAMES
a1b2c3d4e5f6   traefik:v3.0       "/entrypoint.sh --ap…"   2 weeks ago    Up 2 weeks    0.0.0.0:80->80/tcp, 0.0.0.0:443->443/tcp   traefik
b2c3d4e5f6a1   postgres:15-alpine "docker-entrypoint.s…"   2 weeks ago    Up 2 weeks    0.0.0.0:5432->5432/tcp                     postgres
c3d4e5f6a1b2   nginx:alpine       "/docker-entrypoint.…"   2 weeks ago    Up 2 weeks    80/tcp                                     demo1
d4e5f6a1b2c3   nginx:alpine       "/docker-entrypoint.…"   2 weeks ago    Up 2 weeks    80/tcp                                     demo2
e5f6a1b2c3d4   nginx:alpine       "/docker-entrypoint.…"   2 weeks ago    Up 2 weeks    80/tcp                                     demo3`;
            break;

        case 'docker stats':
            output = `CONTAINER ID   NAME       CPU %     MEM USAGE / LIMIT     MEM %     NET I/O           BLOCK I/O         PIDS
a1b2c3d4e5f6   traefik    2.15%     47.82MiB / 2GiB       2.34%     1.2GB / 890MB     156MB / 12.3MB    15
b2c3d4e5f6a1   postgres   1.87%     234.5MiB / 2GiB       11.45%    45MB / 127MB      2.1GB / 890MB     25
c3d4e5f6a1b2   demo1      0.12%     8.945MiB / 512MiB     1.75%     234MB / 189MB     12MB / 0B         3
d4e5f6a1b2c3   demo2      0.08%     9.123MiB / 512MiB     1.78%     267MB / 201MB     14MB / 0B         3
e5f6a1b2c3d4   demo3      0.09%     8.876MiB / 512MiB     1.73%     198MB / 156MB     11MB / 0B         3`;
            break;

        case 'docker logs':
            output = `[${new Date().toISOString()}] INFO: Application started successfully
[${new Date().toISOString()}] INFO: Health check endpoint responding
[${new Date().toISOString()}] INFO: SSL certificate validated
[${new Date().toISOString()}] INFO: Database connection established
[${new Date().toISOString()}] INFO: Ready to handle requests`;
            break;

        case 'docker network ls':
            output = `NETWORK ID     NAME                    DRIVER    SCOPE
abc123def456   docker-demo_web         bridge    local
def456abc123   docker-demo_backend     bridge    local
789012345678   bridge                  bridge    local
345678901234   host                    host      local
901234567890   none                    null      local`;
            break;

        default:
            output = `Command: ${command}\nStatus: Executed successfully\nTimestamp: ${new Date().toISOString()}`;
    }

    updateTerminalOutput(output);
}

function executeDockerCommand() {
    const command = document.getElementById('dockerCommand').value;
    const service = document.getElementById('serviceFilter').value;

    let fullCommand = command;
    if (service && command.includes('docker-compose')) {
        fullCommand += ` ${service}`;
    }

    showNotification(`Executing Docker command...`, 'info');

    setTimeout(() => {
        let output = `$ ${fullCommand}\n`;

        if (command.includes('ps')) {
            output += `Name               Command               State                 Ports
traefik            /entrypoint.sh --api ...      Up      0.0.0.0:443->443/tcp
postgres           docker-entrypoint.sh ...      Up      0.0.0.0:5432->5432/tcp
demo1              /docker-entrypoint.sh ...     Up      80/tcp
demo2              /docker-entrypoint.sh ...     Up      80/tcp
demo3              /docker-entrypoint.sh ...     Up      80/tcp`;
        } else if (command.includes('logs')) {
            output += `Attaching to logs...
[INFO] ${new Date().toISOString()} - Container health check passed
[INFO] ${new Date().toISOString()} - Processing 127 requests/minute
[INFO] ${new Date().toISOString()} - SSL certificate valid for 67 days`;
        } else if (command.includes('restart')) {
            output += `Restarting containers...
Stopping containers... done
Starting containers... done
All services restarted successfully!`;
        } else {
            output += `Command executed successfully.
Status: OK
Time: ${new Date().toLocaleTimeString()}`;
        }

        updateTerminalOutput(output);
        showNotification('Docker command completed successfully!', 'success');
    }, 1500);
}

function executeDatabaseOperation() {
    const operation = document.getElementById('dbOperation').value;
    const query = document.getElementById('dbQuery').value;

    showNotification(`Executing database operation: ${operation}`, 'info');

    setTimeout(() => {
        let output = `PostgreSQL Database Operation: ${operation}\n`;

        switch(operation) {
            case 'status':
                output += `Connection Status: CONNECTED ✅
Database: demo_db
User: demo_user
Active Connections: 12/100
Last Backup: 2 hours ago
Performance: Optimal`;
                break;

            case 'tables':
                output += `Tables in database 'demo_db':
┌─────────────┬──────────┬─────────────┐
│ Table Name  │ Rows     │ Size        │
├─────────────┼──────────┼─────────────┤
│ demo_sites  │ 3        │ 8.2 KB      │
│ visitors    │ 15,429   │ 2.1 MB      │
│ sessions    │ 8,756    │ 1.3 MB      │
│ analytics   │ 45,291   │ 5.7 MB      │
└─────────────┴──────────┴─────────────┘`;
                break;

            case 'backup':
                output += `Creating database backup...
Backup file: demo_db_backup_${new Date().toISOString().split('T')[0]}.sql
Progress: [████████████████████████████████] 100%
Backup size: 2.3 GB
Status: COMPLETED ✅`;
                break;

            case 'vacuum':
                output += `Running VACUUM ANALYZE on database...
Analyzing table: demo_sites... done
Analyzing table: visitors... done
Analyzing table: sessions... done
Analyzing table: analytics... done
Database optimization completed!
Reclaimed space: 45.2 MB`;
                break;

            default:
                output += `Operation completed successfully.
Rows affected: ${Math.floor(Math.random() * 100)}
Execution time: ${Math.floor(Math.random() * 500)}ms`;
        }

        if (query) {
            output += `\n\nCustom Query Results:
${query}
Query executed successfully.
Rows returned: ${Math.floor(Math.random() * 50)}`;
        }

        updateTerminalOutput(output);
        showNotification('Database operation completed!', 'success');
    }, 2000);
}

function executeNetworkTest() {
    const test = document.getElementById('networkTest').value;
    const host = document.getElementById('targetHost').value;

    showNotification(`Running ${test} test on ${host}...`, 'info');

    setTimeout(() => {
        let output = `Network Test: ${test.toUpperCase()}\nTarget: ${host}\n`;

        switch(test) {
            case 'ping':
                output += `PING ${host} (172.16.0.125): 56 data bytes
64 bytes from 172.16.0.125: icmp_seq=0 time=1.234ms
64 bytes from 172.16.0.125: icmp_seq=1 time=1.156ms
64 bytes from 172.16.0.125: icmp_seq=2 time=1.298ms
64 bytes from 172.16.0.125: icmp_seq=3 time=1.187ms

--- ${host} ping statistics ---
4 packets transmitted, 4 received, 0% packet loss
round-trip min/avg/max/stddev = 1.156/1.219/1.298/0.059 ms`;
                break;

            case 'dns':
                output += `DNS Lookup for ${host}:
A Record: 172.16.0.125
AAAA Record: 2001:db8::1
MX Record: mail.smclab.net (Priority: 10)
NS Records: ns1.smclab.net, ns2.smclab.net
TTL: 300 seconds
Status: RESOLVED ✅`;
                break;

            case 'ssl':
                output += `SSL Certificate Check for ${host}:
Subject: CN=${host}
Issuer: Let's Encrypt Authority X3
Valid From: ${new Date(Date.now() - 30*24*60*60*1000).toDateString()}
Valid Until: ${new Date(Date.now() + 60*24*60*60*1000).toDateString()}
Serial Number: 04:a1:b2:c3:d4:e5:f6:78:90:12
Signature Algorithm: SHA256withRSA
Key Size: 2048 bits
Grade: A+ ✅`;
                break;

            case 'ports':
                output += `Port Scan Results for ${host}:
80/tcp   open   http     nginx
443/tcp  open   https    nginx
22/tcp   closed ssh
3306/tcp closed mysql
5432/tcp filtered postgres

Scan completed: 5 ports scanned in 2.1 seconds`;
                break;

            default:
                output += `Test completed successfully.
Response time: ${Math.floor(Math.random() * 100)}ms
Status: OK ✅`;
        }

        updateTerminalOutput(output);
        showNotification(`${test} test completed successfully!`, 'success');
    }, 3000);
}

function openMonitoringTool(tool) {
    showNotification(`Opening ${tool} monitoring tool...`, 'info');

    setTimeout(() => {
        let output = `${tool.toUpperCase()} - Real-time System Monitor\n${'='.repeat(40)}\n`;

        switch(tool) {
            case 'htop':
                output += `  PID USER      PRI  NI  VIRT   RES   SHR S CPU% MEM%   TIME+  Command
    1 root       20   0  1656   536   472 S  0.0  0.0  0:00.12 /sbin/init
  847 root       20   0 81.2M  12.3M  8156K S  2.1  0.6  1:23.45 traefik
 1234 postgres   20   0  234M   45M   12M S  1.8  2.2  0:45.67 postgres
 1456 www-data   20   0  23.4M  8.9M  6.7M S  0.1  0.4  0:12.34 nginx
 1678 www-data   20   0  24.1M  9.2M  6.8M S  0.1  0.4  0:11.89 nginx
 1890 www-data   20   0  23.8M  8.7M  6.5M S  0.1  0.4  0:12.01 nginx`;
                break;

            case 'iotop':
                output += `Total DISK READ:  12.34 M/s | Total DISK WRITE:  3.45 M/s
  TID  PRIO  USER     DISK READ  DISK WRITE  SWAPIN     IO>    COMMAND
 1234     be/4 postgres    8.90 M/s    2.10 M/s  0.00 %  85.67 % postgres
 1456     be/4 www-data    2.34 M/s    0.89 M/s  0.00 %  12.34 % nginx
 1678     be/4 www-data    0.89 M/s    0.34 M/s  0.00 %   5.67 % nginx
 1890     be/4 www-data    0.21 M/s    0.12 M/s  0.00 %   2.34 % nginx`;
                break;

            case 'netstat':
                output += `Active Internet connections (servers and established)
Proto Recv-Q Send-Q Local Address           Foreign Address         State
tcp        0      0 0.0.0.0:80             0.0.0.0:*               LISTEN
tcp        0      0 0.0.0.0:443            0.0.0.0:*               LISTEN
tcp        0      0 0.0.0.0:5432           0.0.0.0:*               LISTEN
tcp        0      0 172.16.0.125:443       203.0.113.42:34567      ESTABLISHED
tcp        0      0 172.16.0.125:443       198.51.100.23:45678     ESTABLISHED`;
                break;

            case 'lsof':
                output += `COMMAND     PID   USER   FD   TYPE DEVICE SIZE/OFF NODE NAME
nginx      1456 www-data    4u  IPv4  12345      0t0  TCP *:80 (LISTEN)
nginx      1456 www-data    5u  IPv4  12346      0t0  TCP *:443 (LISTEN)
postgres   1234 postgres    6u  IPv4  12347      0t0  TCP *:5432 (LISTEN)
traefik     847 root        7u  IPv4  12348      0t0  TCP *:8080 (LISTEN)`;
                break;

            default:
                output += `Tool: ${tool}\nStatus: Running\nPID: ${Math.floor(Math.random() * 10000)}`;
        }

        updateTerminalOutput(output);
        showNotification(`${tool} is now running in terminal`, 'success');
    }, 1000);
}

function scheduleMaintenance() {
    const type = document.getElementById('maintenanceType').value;
    const time = document.getElementById('scheduleTime').value;
    const notes = document.getElementById('maintenanceNotes').value;

    if (!time) {
        showNotification('Please select a schedule time', 'warning');
        return;
    }

    showNotification(`Scheduling ${type} maintenance...`, 'info');

    setTimeout(() => {
        const output = `Maintenance Scheduled Successfully!
Type: ${type.toUpperCase()}
Scheduled Time: ${new Date(time).toLocaleString()}
Duration: Estimated 15-30 minutes
Downtime: Minimal (rolling restart)
Notes: ${notes || 'No additional notes'}

Notification will be sent 1 hour before maintenance begins.
Auto-backup will be created before any system changes.`;

        updateTerminalOutput(output);
        showNotification('Maintenance scheduled successfully!', 'success');

        // Clear the form
        document.getElementById('scheduleTime').value = '';
        document.getElementById('maintenanceNotes').value = '';
    }, 1500);
}

function quickAction(action) {
    showNotification(`Executing quick action: ${action}`, 'info');

    setTimeout(() => {
        let output = `Quick Action: ${action.toUpperCase()}\n${'='.repeat(30)}\n`;

        switch(action) {
            case 'restart':
                output += `Initiating graceful restart sequence...
1. Health check all services... ✅
2. Create pre-restart snapshot... ✅
3. Begin rolling restart:
   - Stopping demo3... ✅
   - Starting demo3... ✅
   - Stopping demo2... ✅
   - Starting demo2... ✅
   - Stopping demo1... ✅
   - Starting demo1... ✅
   - Restarting Traefik... ✅
   - Restarting PostgreSQL... ✅

All services restarted successfully!
Total downtime: < 30 seconds`;
                break;

            case 'backup':
                output += `Emergency Database Backup
Timestamp: ${new Date().toISOString()}
Initiating backup process...

1. Lock tables for consistency... ✅
2. Dump database schema... ✅
3. Dump table data... ✅
4. Compress backup file... ✅
5. Verify backup integrity... ✅
6. Unlock tables... ✅

Backup completed successfully!
File: emergency_backup_${Date.now()}.sql.gz
Size: 2.3 GB
Location: /backups/emergency/`;
                break;

            case 'health':
                output += `Comprehensive System Health Check
${'='.repeat(35)}

Container Health:
✅ Traefik: Healthy (uptime: 15d 4h)
✅ PostgreSQL: Healthy (connections: 12/100)
✅ Demo1: Healthy (response: 127ms)
✅ Demo2: Healthy (response: 134ms)
✅ Demo3: Healthy (response: 119ms)

System Resources:
✅ CPU Usage: 12.3% (optimal)
✅ Memory Usage: 34.5% (healthy)
✅ Disk Space: 67.8% (monitoring)
✅ Network: No issues detected

Security Status:
✅ SSL Certificates: Valid (67 days remaining)
✅ Firewall: Active and configured
✅ Failed Login Attempts: 0 (last 24h)
✅ System Updates: Current

Overall Status: EXCELLENT ✅`;
                break;

            case 'logs':
                output += `Exporting System Logs
Time Range: Last 24 hours
Log Types: All containers + system

Collecting logs from:
✅ Traefik access logs (2.1 MB)
✅ Traefik error logs (45 KB)
✅ PostgreSQL logs (890 KB)
✅ Nginx access logs (5.7 MB)
✅ System logs (1.2 MB)
✅ Docker daemon logs (234 KB)

Archive created: system_logs_${new Date().toISOString().split('T')[0]}.tar.gz
Total size: 10.2 MB
Download link: /exports/logs/latest.tar.gz`;
                break;

            default:
                output += `Action: ${action}\nStatus: Completed\nTime: ${new Date().toLocaleTimeString()}`;
        }

        updateTerminalOutput(output);
        showNotification(`${action} action completed successfully!`, 'success');
    }, 2500);
}

function updateCommandPreview() {
    const command = document.getElementById('dockerCommand').value;
    const service = document.getElementById('serviceFilter').value;
    const preview = document.getElementById('commandPreview');

    if (preview) {
        let fullCommand = command;
        if (service && command.includes('docker-compose')) {
            fullCommand += ` ${service}`;
        }
        preview.value = fullCommand;
    }
}

// Utility functions
function updateTerminalOutput(text) {
    const terminal = document.getElementById('terminalOutput');
    if (terminal) {
        terminal.textContent = text;
        terminal.scrollTop = terminal.scrollHeight;
    }
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;

    const icons = {
        success: '✅',
        info: 'ℹ️',
        warning: '⚠️',
        error: '❌'
    };

    notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-icon">${icons[type] || 'ℹ️'}</span>
            <span class="notification-message">${message}</span>
            <button class="notification-close" onclick="this.parentElement.parentElement.remove()">×</button>
        </div>
    `;

    const colors = {
        success: 'rgba(76, 175, 80, 0.9)',
        info: 'rgba(33, 150, 243, 0.9)',
        warning: 'rgba(255, 152, 0, 0.9)',
        error: 'rgba(244, 67, 54, 0.9)'
    };

    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${colors[type] || colors.info};
        color: white;
        padding: 1rem;
        border-radius: 8px;
        backdrop-filter: blur(10px);
        z-index: 1000;
        max-width: 400px;
        animation: slideInRight 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        if (notification.parentNode) {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }
    }, 5000);
}

function initializeTools() {
    // Initialize command preview
    updateCommandPreview();

    // Set default schedule time to 1 hour from now
    const scheduleInput = document.getElementById('scheduleTime');
    if (scheduleInput) {
        const now = new Date();
        now.setHours(now.getHours() + 1);
        const isoString = now.toISOString().slice(0, 16);
        scheduleInput.value = isoString;
    }

    // Show welcome message
    setTimeout(() => {
        showNotification('Tools interface loaded successfully!', 'success');
    }, 1000);
}