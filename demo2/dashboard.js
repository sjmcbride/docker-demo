// Dashboard functionality for Demo2
document.addEventListener('DOMContentLoaded', function() {
    initializeDashboard();
    startRealTimeUpdates();
});

// Tab functionality
function showTab(tabName) {
    // Hide all tab panes
    const tabPanes = document.querySelectorAll('.tab-pane');
    tabPanes.forEach(pane => {
        pane.classList.remove('active');
    });

    // Remove active class from all tabs
    const tabs = document.querySelectorAll('.tab');
    tabs.forEach(tab => {
        tab.classList.remove('active');
    });

    // Show selected tab pane
    const selectedPane = document.getElementById(tabName);
    if (selectedPane) {
        selectedPane.classList.add('active');
    }

    // Add active class to clicked tab
    event.target.classList.add('active');
}

// Dashboard card interactions
function showSystemStatus() {
    showNotification('System Status: All services operational ✅', 'success');
    updateTerminalOutput('Checking system status...\n✅ Traefik: Running\n✅ PostgreSQL: Connected\n✅ Demo Apps: All responding\n✅ SSL Certificates: Valid');
}

function showDatabaseMetrics() {
    showNotification('Database metrics retrieved successfully 📊', 'info');
    updateTerminalOutput('Database Performance Metrics:\n📊 Active Connections: 12/100\n📊 Query Response Time: 45ms avg\n📊 Cache Hit Ratio: 96.3%\n📊 Storage Usage: 2.3GB');
}

function showTrafficAnalytics() {
    showNotification('Traffic analytics updated 📈', 'info');
    updateTerminalOutput('Traffic Analytics Summary:\n📈 Current Active Users: 1,247\n📈 Requests/Hour: 15,429\n📈 Peak Traffic Time: 2:00 PM - 4:00 PM\n📈 Geographic Distribution: 45% US, 18% CA, 12% UK');
}

function showSecurityOverview() {
    showNotification('Security status: All systems secure 🔒', 'success');
    updateTerminalOutput('Security Overview:\n🔒 SSL Grade: A+\n🔒 TLS Version: 1.3\n🔒 Blocked Threats: 1,247 this month\n🔒 Vulnerability Scan: Clean (2 hours ago)');
}

// Quick actions
function restartServices() {
    showNotification('Initiating graceful restart of all services...', 'info');
    updateTerminalOutput('docker-compose restart\nRestarting traefik ... done\nRestarting postgres ... done\nRestarting demo1 ... done\nRestarting demo2 ... done\nRestarting demo3 ... done\n\nAll services restarted successfully! ✅');
}

function viewLogs() {
    showNotification('Opening container logs...', 'info');
    const logSample = `[${new Date().toISOString()}] INFO: Container health check passed
[${new Date().toISOString()}] INFO: SSL certificate validation successful
[${new Date().toISOString()}] INFO: Database connection pool optimal
[${new Date().toISOString()}] INFO: Traefik routing 127 requests/minute`;
    updateTerminalOutput(logSample);
}

function backupData() {
    showNotification('Creating database backup...', 'info');
    updateTerminalOutput('pg_dump demo_db > backup_' + new Date().toISOString().split('T')[0] + '.sql\nBackup created successfully!\nSize: 2.3GB\nLocation: /backups/\nRetention: 30 days ✅');
}

function updateContainers() {
    showNotification('Checking for container updates...', 'info');
    updateTerminalOutput('docker-compose pull\nPulling traefik ... up to date\nPulling postgres ... up to date\nPulling nginx ... up to date\n\nAll images are up to date! 🎉');
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

    // Style the notification
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

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }
    }, 5000);
}

function initializeDashboard() {
    // Animate stat numbers on load
    const statNumbers = document.querySelectorAll('.stat-number');
    statNumbers.forEach(stat => {
        const finalValue = stat.textContent;
        stat.textContent = '0';

        // Simple counter animation
        const increment = parseFloat(finalValue) / 50;
        let current = 0;
        const timer = setInterval(() => {
            current += increment;
            if (current >= parseFloat(finalValue)) {
                stat.textContent = finalValue;
                clearInterval(timer);
            } else {
                stat.textContent = Math.floor(current).toLocaleString();
            }
        }, 20);
    });

    // Animate progress bars
    const progressBars = document.querySelectorAll('.stat-progress-bar');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0';
        setTimeout(() => {
            bar.style.width = width;
        }, 500);
    });
}

function startRealTimeUpdates() {
    // Simulate real-time data updates
    setInterval(() => {
        updateActiveUsers();
        updateRequests();
        updateResponseTime();
    }, 5000);
}

function updateActiveUsers() {
    const usersStat = document.getElementById('activeUsers');
    if (usersStat) {
        const currentUsers = parseInt(usersStat.textContent.replace(',', ''));
        const variation = Math.floor(Math.random() * 100) - 50; // ±50 users
        const newUsers = Math.max(1000, currentUsers + variation);
        usersStat.textContent = newUsers.toLocaleString();
    }
}

function updateRequests() {
    const requestsStat = document.getElementById('totalRequests');
    if (requestsStat) {
        const current = parseFloat(requestsStat.textContent.replace('K', ''));
        const increment = Math.random() * 0.1; // Small increments
        const newValue = (current + increment).toFixed(1);
        requestsStat.textContent = newValue + 'K';
    }
}

function updateResponseTime() {
    const responseStat = document.getElementById('responseTime');
    if (responseStat) {
        const baseTime = 120;
        const variation = Math.floor(Math.random() * 20) - 10; // ±10ms
        const newTime = Math.max(80, baseTime + variation);
        responseStat.textContent = newTime + 'ms';
    }
}

// Chart interactions
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('chart-bar')) {
        const value = e.target.getAttribute('data-value');
        showNotification(`Chart data point: ${value}`, 'info');
    }
});

// Add dashboard card hover effects
document.querySelectorAll('.dashboard-card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'scale(1.02) translateY(-5px)';
        this.style.boxShadow = '0 10px 25px rgba(240, 147, 251, 0.2)';
    });

    card.addEventListener('mouseleave', function() {
        this.style.transform = 'scale(1)';
        this.style.boxShadow = 'none';
    });
});

// Add CSS animations dynamically
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    .notification-content {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .notification-close {
        background: none;
        border: none;
        color: white;
        font-size: 1.2rem;
        cursor: pointer;
        margin-left: auto;
        padding: 0 0.25rem;
    }

    .notification-close:hover {
        opacity: 0.7;
    }

    .stat-progress-bar {
        transition: width 1.5s ease-out;
    }

    .chart-bar {
        cursor: pointer;
    }

    .chart-bar:hover {
        filter: brightness(1.2);
    }
`;
document.head.appendChild(style);