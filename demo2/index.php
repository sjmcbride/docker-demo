<?php
require_once 'config.php';

if (isset($_GET['logout'])) {
    logoutUser();
}

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMC Tech Lab 2 - Network Command Center</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600&display=swap');

        body {
            font-family: 'Exo 2', sans-serif;
            margin: 0;
            padding: 20px;
            background:
                radial-gradient(circle at 20% 30%, rgba(46, 213, 115, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(46, 213, 115, 0.08) 0%, transparent 40%),
                linear-gradient(135deg, #2c3e50 0%, #34495e 25%, #2c3e50 50%, #1a252f 75%, #0f1419 100%);
            color: #ecf0f1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .tech-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        .circuit {
            position: absolute;
            background: linear-gradient(90deg, transparent, rgba(46, 213, 115, 0.3), transparent);
            height: 1px;
            animation: circuit-flow linear infinite;
        }

        .node {
            position: absolute;
            width: 8px;
            height: 8px;
            background: radial-gradient(circle, #2ed573, #1dd1a1);
            border-radius: 50%;
            box-shadow: 0 0 15px rgba(46, 213, 115, 0.6);
            animation: pulse 2s ease-in-out infinite;
        }

        .grid-line {
            position: absolute;
            background: linear-gradient(to right, transparent, rgba(149, 165, 166, 0.1), transparent);
            height: 1px;
        }

        .circuit1 { width: 300px; top: 15%; left: 10%; animation-duration: 8s; }
        .circuit2 { width: 200px; top: 60%; right: 15%; animation-duration: 6s; animation-delay: 2s; }
        .circuit3 { width: 150px; bottom: 20%; left: 20%; animation-duration: 10s; animation-delay: 4s; }

        .node1 { top: 20%; left: 8%; animation-delay: 0s; }
        .node2 { top: 40%; right: 12%; animation-delay: 1s; }
        .node3 { bottom: 30%; left: 15%; animation-delay: 2s; }
        .node4 { top: 70%; right: 25%; animation-delay: 3s; }

        .grid-line1 { width: 100%; top: 25%; }
        .grid-line2 { width: 100%; top: 50%; }
        .grid-line3 { width: 100%; top: 75%; }

        @keyframes circuit-flow {
            0% { transform: translateX(-100%); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateX(100vw); opacity: 0; }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.8; }
            50% { transform: scale(1.5); opacity: 1; }
        }

        .container {
            text-align: center;
            background:
                linear-gradient(145deg,
                    rgba(52, 73, 94, 0.95) 0%,
                    rgba(44, 62, 80, 0.98) 50%,
                    rgba(26, 37, 47, 0.95) 100%);
            padding: 50px;
            border-radius: 15px;
            border: 2px solid rgba(46, 213, 115, 0.3);
            box-shadow:
                0 0 30px rgba(46, 213, 115, 0.2),
                inset 0 0 50px rgba(46, 213, 115, 0.05),
                0 15px 35px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 10;
            max-width: 600px;
            backdrop-filter: blur(10px);
        }

        .container::before {
            content: '';
            position: absolute;
            top: -1px;
            left: -1px;
            right: -1px;
            bottom: -1px;
            background: linear-gradient(45deg,
                transparent,
                rgba(46, 213, 115, 0.4),
                transparent,
                rgba(149, 165, 166, 0.2),
                transparent);
            border-radius: 15px;
            z-index: -1;
            animation: border-glow 3s ease-in-out infinite;
        }

        @keyframes border-glow {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        .logo {
            font-family: 'Orbitron', monospace;
            font-size: 4em;
            font-weight: 900;
            margin-bottom: 20px;
            background: linear-gradient(45deg, #95a5a6, #ecf0f1, #bdc3c7);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 30px rgba(46, 213, 115, 0.3);
            letter-spacing: 0.1em;
        }

        .logo .sc {
            background: linear-gradient(45deg, #95a5a6 0%, #ecf0f1 30%, #2ed573 60%, #1dd1a1 100%);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        h1 {
            font-family: 'Orbitron', monospace;
            font-size: 2.5em;
            font-weight: 700;
            margin-bottom: 20px;
            color: #ecf0f1;
            text-shadow: 0 0 20px rgba(46, 213, 115, 0.4);
            letter-spacing: 0.15em;
        }

        p {
            font-size: 1.2em;
            margin-bottom: 15px;
            color: #bdc3c7;
            font-weight: 300;
            line-height: 1.6;
        }

        .status {
            color: #2ed573;
            font-weight: 600;
        }

        a {
            color: #2ed573;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            padding: 8px 16px;
            border: 1px solid rgba(46, 213, 115, 0.3);
            border-radius: 8px;
            background: rgba(46, 213, 115, 0.1);
            display: inline-block;
        }

        a:hover {
            color: #0f1419;
            background: #2ed573;
            border-color: #2ed573;
            box-shadow: 0 0 20px rgba(46, 213, 115, 0.4);
            transform: translateY(-2px);
        }
    </style>
    <link rel="stylesheet" href="../shared/gaming.css">
</head>
<body>
    <div class="tech-elements">
        <div class="circuit circuit1"></div>
        <div class="circuit circuit2"></div>
        <div class="circuit circuit3"></div>
        <div class="node node1"></div>
        <div class="node node2"></div>
        <div class="node node3"></div>
        <div class="node node4"></div>
        <div class="grid-line grid-line1"></div>
        <div class="grid-line grid-line2"></div>
        <div class="grid-line grid-line3"></div>
    </div>

    <div class="container">
        <div class="logo"><span class="sc">SMC</span></div>
        <h1>SMC TECH LAB 2</h1>
        <h2 style="font-family: 'Orbitron', monospace; color: #2ed573; font-size: 1.8em; margin-bottom: 30px; letter-spacing: 2px;">🌐 NETWORK COMMAND CENTER 🌐</h2>

        <div class="lab-status">
            <p>Network Operator: <span class="status"><?php echo htmlspecialchars($_SESSION['username']); ?></span></p>
            <p>🔒 Secure VPN Tunnel Established</p>
            <p class="status">Network Status: OPERATIONAL</p>
        </div>

        <div class="missions-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin: 30px 0;">

            <!-- Network Troubleshooting Maze -->
            <div class="challenge-container">
                <div class="challenge-title">🌐 Network Trace Mission</div>
                <div class="challenge-difficulty difficulty-medium">MEDIUM DIFFICULTY</div>
                <div class="challenge-description">
                    Follow packet routes through the network maze to identify failures.
                </div>
                <div class="challenge-interface">
                    <div id="network-topology" style="background: #0f1419; padding: 20px; border-radius: 8px; font-family: 'Courier New', monospace; font-size: 11px; color: #2ed573;">
                        <div style="text-align: center; margin-bottom: 15px; color: #ecf0f1;">Network Topology Map</div>
                        <pre style="margin: 0; line-height: 1.2;">
[CLIENT] ---> [RTR-01] ---> [SW-01] ---> [SRV-WEB]
    |             |            |            |
    |             |         [SW-02]    [SRV-DB]
    |             |            |            |
[LAPTOP] ---> [RTR-02] ---> [FW-01] ---> [SRV-API]
                        </pre>
                        <div style="margin-top: 15px; color: #f39c12;">Status: <span id="trace-status">Waiting for trace...</span></div>
                        <div style="color: #bdc3c7;">Hops discovered: <span id="hop-count">0</span>/8</div>
                    </div>
                    <div class="progress-container" style="margin: 10px 0;">
                        <div class="progress-bar" id="trace-progress" style="width: 0%;"></div>
                    </div>
                    <div class="challenge-controls">
                        <button class="game-btn" onclick="startNetworkTrace()">🔍 START TRACE</button>
                        <button class="game-btn secondary" onclick="showHint('network')">💡 HINT (15 XP)</button>
                    </div>
                </div>
                <div class="mission-stats" style="margin-top: 15px; font-size: 14px; color: #bdc3c7;">
                    Base Points: 150 | Time Limit: 5:00 | Success Rate: 78%
                </div>
            </div>

            <!-- Security Breach Response -->
            <div class="challenge-container">
                <div class="challenge-title">🚨 Security Breach Response</div>
                <div class="challenge-difficulty difficulty-hard">HARD DIFFICULTY</div>
                <div class="challenge-description">
                    Malicious traffic detected! Block threats before they penetrate the network.
                </div>
                <div class="challenge-interface">
                    <div id="security-monitor" style="background: #0f1419; padding: 15px; border-radius: 8px; font-family: 'Courier New', monospace; font-size: 12px;">
                        <div style="color: #e74c3c; font-weight: bold; margin-bottom: 10px;">⚠️ THREAT DETECTION ACTIVE ⚠️</div>
                        <div style="color: #2ed573;">Monitoring: <span id="monitored-ips">47,382</span> IP addresses</div>
                        <div style="color: #f39c12;">Suspicious Activity: <span id="threats-detected" style="color: #e74c3c; font-weight: bold;">8</span> sources</div>
                        <div style="color: #bdc3c7; margin-top: 10px;">Recent Alerts:</div>
                        <div id="threat-log" style="max-height: 100px; overflow-y: auto; margin-top: 5px;">
                            <div style="color: #e74c3c;">[CRITICAL] 203.45.67.89 - SQL injection attempt</div>
                            <div style="color: #f39c12;">[WARNING] 156.23.78.45 - Port scan detected</div>
                            <div style="color: #e74c3c;">[CRITICAL] 87.123.45.67 - Brute force login</div>
                        </div>
                    </div>
                    <div class="progress-container" style="margin: 10px 0;">
                        <div class="progress-bar danger" id="threat-level" style="width: 75%;"></div>
                    </div>
                    <div class="challenge-controls">
                        <button class="game-btn" onclick="startThreatResponse()">🛡️ ACTIVATE DEFENSES</button>
                        <button class="game-btn secondary" onclick="showHint('security')">💡 HINT (15 XP)</button>
                    </div>
                </div>
                <div class="mission-stats" style="margin-top: 15px; font-size: 14px; color: #bdc3c7;">
                    Base Points: 200 | Time Limit: 2:00 | Success Rate: 34%
                </div>
            </div>

            <!-- Load Balancer Master -->
            <div class="challenge-container">
                <div class="challenge-title">⚖️ Load Balancer Master</div>
                <div class="challenge-difficulty difficulty-easy">EASY DIFFICULTY</div>
                <div class="challenge-description">
                    Keep all backend servers healthy while balancing incoming traffic efficiently.
                </div>
                <div class="challenge-interface">
                    <div id="load-balancer-display" style="background: #0f1419; padding: 15px; border-radius: 8px; font-family: 'Courier New', monospace; font-size: 12px;">
                        <div style="color: #2ed573; text-align: center; margin-bottom: 15px;">Load Balancer Status</div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; margin-bottom: 15px;">
                            <div style="text-align: center;">
                                <div style="color: #ecf0f1;">Server 1</div>
                                <div id="server1-status" style="color: #2ed573; font-weight: bold;">HEALTHY</div>
                                <div style="color: #bdc3c7;">Load: <span id="server1-load">23%</span></div>
                            </div>
                            <div style="text-align: center;">
                                <div style="color: #ecf0f1;">Server 2</div>
                                <div id="server2-status" style="color: #2ed573; font-weight: bold;">HEALTHY</div>
                                <div style="color: #bdc3c7;">Load: <span id="server2-load">31%</span></div>
                            </div>
                            <div style="text-align: center;">
                                <div style="color: #ecf0f1;">Server 3</div>
                                <div id="server3-status" style="color: #f39c12; font-weight: bold;">WARNING</div>
                                <div style="color: #bdc3c7;">Load: <span id="server3-load">89%</span></div>
                            </div>
                        </div>
                        <div style="color: #2ed573;">Total Requests: <span id="total-requests">1,247</span>/min</div>
                        <div style="color: #bdc3c7;">Average Response: <span id="avg-response">245ms</span></div>
                    </div>
                    <div class="progress-container" style="margin: 10px 0;">
                        <div class="progress-bar warning" id="balance-efficiency" style="width: 67%;"></div>
                    </div>
                    <div class="challenge-controls">
                        <button class="game-btn" onclick="startLoadBalancing()">⚖️ BALANCE LOAD</button>
                        <button class="game-btn secondary" onclick="showHint('loadbalancer')">💡 HINT (15 XP)</button>
                    </div>
                </div>
                <div class="mission-stats" style="margin-top: 15px; font-size: 14px; color: #bdc3c7;">
                    Base Points: 100 | Time Limit: 3:00 | Success Rate: 89%
                </div>
            </div>

        </div>

        <div class="lab-controls" style="margin: 30px 0; padding: 20px; background: rgba(52, 73, 94, 0.4); border-radius: 8px; border: 1px solid #34495e;">
            <h3 style="font-family: 'Orbitron', monospace; color: #2ed573; margin-bottom: 15px;">🌐 Network Operations</h3>
            <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                <button class="game-btn secondary" onclick="viewNetworkMap()">🗺️ NETWORK MAP</button>
                <button class="game-btn secondary" onclick="viewTrafficStats()">📊 TRAFFIC STATS</button>
                <button class="game-btn secondary" onclick="viewSecurityLogs()">📄 SECURITY LOGS</button>
                <a href="?logout=1" class="game-btn danger" style="text-decoration: none;">🚪 LOGOUT</a>
            </div>
        </div>
    </div>

    <script src="../shared/gaming.js"></script>
    <script>
        // Lab 2 specific gaming logic

        function startNetworkTrace() {
            gaming.startChallenge('network_trace_maze', 300); // 5 minutes

            const status = document.getElementById('trace-status');
            const hopCount = document.getElementById('hop-count');
            const progressBar = document.getElementById('trace-progress');

            let currentHop = 0;
            const hops = ['CLIENT', 'RTR-01', 'SW-01', 'SW-02', 'FW-01', 'RTR-02', 'SRV-WEB', 'SRV-API'];

            status.textContent = 'Initializing trace...';

            const traceInterval = setInterval(() => {
                if (currentHop < hops.length) {
                    currentHop++;
                    hopCount.textContent = currentHop;
                    progressBar.style.width = (currentHop / hops.length * 100) + '%';
                    status.textContent = `Tracing hop ${currentHop}: ${hops[currentHop - 1]}`;

                    if (currentHop === 6) {
                        status.textContent = 'Network failure detected at SRV-WEB!';
                        status.style.color = '#e74c3c';
                    }
                } else {
                    clearInterval(traceInterval);
                    status.textContent = 'Trace complete - Failure point identified!';
                    status.style.color = '#2ed573';
                    progressBar.style.width = '100%';
                    gaming.completeChallenge(true, 150);
                }
            }, 1200);
        }

        function startThreatResponse() {
            gaming.startChallenge('security_breach_response', 120); // 2 minutes

            const threatLevel = document.getElementById('threat-level');
            const threatsDetected = document.getElementById('threats-detected');
            const threatLog = document.getElementById('threat-log');

            let threatsBlocked = 0;
            const totalThreats = 8;

            setTimeout(() => {
                threatLog.innerHTML += '<div style="color: #2ed573;">[BLOCKED] 203.45.67.89 - IP blacklisted</div>';
                threatsBlocked++;
                threatsDetected.textContent = totalThreats - threatsBlocked;
                threatLevel.style.width = ((totalThreats - threatsBlocked) / totalThreats * 100) + '%';
            }, 2000);

            setTimeout(() => {
                threatLog.innerHTML += '<div style="color: #2ed573;">[BLOCKED] 156.23.78.45 - Port scan prevented</div>';
                threatsBlocked++;
                threatsDetected.textContent = totalThreats - threatsBlocked;
                threatLevel.style.width = ((totalThreats - threatsBlocked) / totalThreats * 100) + '%';
            }, 4000);

            setTimeout(() => {
                threatLog.innerHTML += '<div style="color: #2ed573;">[BLOCKED] Multiple IPs - Coordinated attack stopped</div>';
                threatsBlocked = totalThreats;
                threatsDetected.textContent = '0';
                threatLevel.style.width = '0%';
                threatLevel.className = 'progress-bar';
                gaming.completeChallenge(true, 200);
            }, 6000);
        }

        function startLoadBalancing() {
            gaming.startChallenge('load_balancer_master', 180); // 3 minutes

            const server1Load = document.getElementById('server1-load');
            const server2Load = document.getElementById('server2-load');
            const server3Load = document.getElementById('server3-load');
            const server3Status = document.getElementById('server3-status');
            const efficiencyBar = document.getElementById('balance-efficiency');
            const totalRequests = document.getElementById('total-requests');
            const avgResponse = document.getElementById('avg-response');

            setTimeout(() => {
                server1Load.textContent = '45%';
                server2Load.textContent = '47%';
                server3Load.textContent = '52%';
                server3Status.textContent = 'HEALTHY';
                server3Status.style.color = '#2ed573';
                efficiencyBar.style.width = '85%';
                efficiencyBar.className = 'progress-bar';
                totalRequests.textContent = '2,156';
                avgResponse.textContent = '178ms';
            }, 3000);

            setTimeout(() => {
                server1Load.textContent = '33%';
                server2Load.textContent = '35%';
                server3Load.textContent = '32%';
                efficiencyBar.style.width = '95%';
                totalRequests.textContent = '2,847';
                avgResponse.textContent = '142ms';
                gaming.completeChallenge(true, 100);
            }, 6000);
        }

        function showHint(type) {
            if (!gaming.addHint(15)) {
                alert('Not enough XP for hint! Complete challenges to earn more.');
                return;
            }

            const hints = {
                network: 'Use traceroute to follow the packet path. Look for timeouts or unreachable hops.',
                security: 'Prioritize blocking critical threats first. Use IP blacklisting and rate limiting.',
                loadbalancer: 'Redistribute traffic evenly. Consider health checks and weighted routing.'
            };

            gaming.showNotification(`Hint: ${hints[type]}`);
        }

        function viewNetworkMap() {
            gaming.showNotification('Network topology viewer coming soon!');
        }

        function viewTrafficStats() {
            gaming.showNotification('Real-time traffic analytics coming soon!');
        }

        function viewSecurityLogs() {
            gaming.showNotification('Security event viewer coming soon!');
        }

        // Simulate live network monitoring
        setInterval(() => {
            if (!gaming.currentChallenge) {
                const monitoredIps = document.getElementById('monitored-ips');
                if (monitoredIps) {
                    const current = parseInt(monitoredIps.textContent.replace(',', ''));
                    const newCount = current + Math.floor(Math.random() * 100);
                    monitoredIps.textContent = newCount.toLocaleString();
                }

                const totalReqs = document.getElementById('total-requests');
                if (totalReqs) {
                    const current = parseInt(totalReqs.textContent.replace(',', ''));
                    const newReqs = Math.max(800, current + Math.floor(Math.random() * 200 - 100));
                    totalReqs.textContent = newReqs.toLocaleString();
                }
            }
        }, 5000);

        // Server load simulation
        setInterval(() => {
            if (!gaming.currentChallenge) {
                const loads = ['server1-load', 'server2-load', 'server3-load'];
                loads.forEach(loadId => {
                    const element = document.getElementById(loadId);
                    if (element) {
                        const current = parseInt(element.textContent);
                        const newLoad = Math.max(15, Math.min(95, current + Math.floor(Math.random() * 20 - 10)));
                        element.textContent = newLoad + '%';

                        // Update server status based on load
                        const statusId = loadId.replace('-load', '-status');
                        const statusElement = document.getElementById(statusId);
                        if (statusElement) {
                            if (newLoad > 80) {
                                statusElement.textContent = 'WARNING';
                                statusElement.style.color = '#f39c12';
                            } else if (newLoad > 90) {
                                statusElement.textContent = 'CRITICAL';
                                statusElement.style.color = '#e74c3c';
                            } else {
                                statusElement.textContent = 'HEALTHY';
                                statusElement.style.color = '#2ed573';
                            }
                        }
                    }
                });
            }
        }, 3000);

    </script>
</body>
</html>