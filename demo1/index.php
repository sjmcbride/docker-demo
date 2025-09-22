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
    <title>SMC Tech Lab 1 - Server Rescue Missions</title>
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
        <h1>SMC TECH LAB 1</h1>
        <h2 style="font-family: 'Orbitron', monospace; color: #2ed573; font-size: 1.8em; margin-bottom: 30px; letter-spacing: 2px;">🚨 SERVER RESCUE MISSIONS 🚨</h2>

        <div class="lab-status">
            <p>Operator: <span class="status"><?php echo htmlspecialchars($_SESSION['username']); ?></span></p>
            <p>🔒 Secure Connection Established</p>
            <p class="status">Mission Control: ACTIVE</p>
        </div>

        <div class="missions-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin: 30px 0;">

            <!-- Critical Server Down Mission -->
            <div class="challenge-container">
                <div class="challenge-title">🔥 Critical Server Down</div>
                <div class="challenge-difficulty difficulty-hard">HARD DIFFICULTY</div>
                <div class="challenge-description">
                    Web server has crashed! Diagnose and restore service within 5 minutes.
                </div>
                <div class="challenge-interface">
                    <div class="console-output" id="server-console">
                        <div class="console-line console-error">[ERROR] nginx: [emerg] bind() to 0.0.0.0:80 failed</div>
                        <div class="console-line console-error">[ERROR] Service unavailable - Connection refused</div>
                        <div class="console-line console-warning">[WARN] Multiple connection timeouts detected</div>
                        <div class="console-line console-prompt">root@server:~# </div>
                    </div>
                    <div class="progress-container" style="margin: 10px 0;">
                        <div class="progress-bar danger" id="server-health" style="width: 15%;"></div>
                    </div>
                    <div class="challenge-controls">
                        <button class="game-btn" onclick="startServerRescue()">🚀 START MISSION</button>
                        <button class="game-btn secondary" onclick="showHint('server')">💡 HINT (15 XP)</button>
                    </div>
                </div>
                <div class="mission-stats" style="margin-top: 15px; font-size: 14px; color: #bdc3c7;">
                    Base Points: 200 | Time Limit: 5:00 | Success Rate: 23%
                </div>
            </div>

            <!-- Memory Leak Detective Mission -->
            <div class="challenge-container">
                <div class="challenge-title">🧠 Memory Leak Detective</div>
                <div class="challenge-difficulty difficulty-medium">MEDIUM DIFFICULTY</div>
                <div class="challenge-description">
                    Find the rogue process consuming excessive memory before system crashes.
                </div>
                <div class="challenge-interface">
                    <div id="memory-monitor" style="background: #0f1419; padding: 15px; border-radius: 8px; font-family: 'Courier New', monospace; font-size: 12px; color: #2ed573;">
                        <div>Memory Usage: <span id="memory-usage" style="color: #e74c3c;">87%</span></div>
                        <div>Available: <span id="memory-available">2.1GB</span></div>
                        <div>Critical Threshold: <span style="color: #f39c12;">90%</span></div>
                        <div style="margin-top: 10px; color: #bdc3c7;">Scanning processes...</div>
                    </div>
                    <div class="progress-container" style="margin: 10px 0;">
                        <div class="progress-bar warning" id="memory-pressure" style="width: 87%;"></div>
                    </div>
                    <div class="challenge-controls">
                        <button class="game-btn" onclick="startMemoryHunt()">🔍 INVESTIGATE</button>
                        <button class="game-btn secondary" onclick="showHint('memory')">💡 HINT (15 XP)</button>
                    </div>
                </div>
                <div class="mission-stats" style="margin-top: 15px; font-size: 14px; color: #bdc3c7;">
                    Base Points: 150 | Time Limit: 3:00 | Success Rate: 67%
                </div>
            </div>

            <!-- Performance Optimizer Mission -->
            <div class="challenge-container">
                <div class="challenge-title">⚡ Performance Optimizer</div>
                <div class="challenge-difficulty difficulty-medium">MEDIUM DIFFICULTY</div>
                <div class="challenge-description">
                    Optimize server response time to under 2 seconds for peak efficiency.
                </div>
                <div class="challenge-interface">
                    <div id="performance-dashboard" style="background: #0f1419; padding: 15px; border-radius: 8px; font-family: 'Courier New', monospace; font-size: 12px;">
                        <div style="color: #2ed573;">Current Response Time: <span id="response-time" style="color: #e74c3c; font-weight: bold;">4.7s</span></div>
                        <div style="color: #2ed573;">Target: <span style="color: #2ed573; font-weight: bold;">&lt;2.0s</span></div>
                        <div style="color: #f39c12; margin-top: 10px;">Performance Issues Detected:</div>
                        <div style="color: #bdc3c7; margin-left: 20px;">• Database query optimization needed</div>
                        <div style="color: #bdc3c7; margin-left: 20px;">• Cache configuration missing</div>
                        <div style="color: #bdc3c7; margin-left: 20px;">• Static file compression disabled</div>
                    </div>
                    <div class="progress-container" style="margin: 10px 0;">
                        <div class="progress-bar danger" id="performance-score" style="width: 35%;"></div>
                    </div>
                    <div class="challenge-controls">
                        <button class="game-btn" onclick="startOptimization()">🚀 OPTIMIZE</button>
                        <button class="game-btn secondary" onclick="showHint('performance')">💡 HINT (15 XP)</button>
                    </div>
                </div>
                <div class="mission-stats" style="margin-top: 15px; font-size: 14px; color: #bdc3c7;">
                    Base Points: 100 | Time Limit: 4:00 | Success Rate: 45%
                </div>
            </div>

        </div>

        <div class="lab-controls" style="margin: 30px 0; padding: 20px; background: rgba(52, 73, 94, 0.4); border-radius: 8px; border: 1px solid #34495e;">
            <h3 style="font-family: 'Orbitron', monospace; color: #2ed573; margin-bottom: 15px;">🎮 Lab Controls</h3>
            <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                <button class="game-btn secondary" onclick="viewLeaderboard()">🏆 LEADERBOARD</button>
                <button class="game-btn secondary" onclick="viewAchievements()">🎯 ACHIEVEMENTS</button>
                <button class="game-btn secondary" onclick="viewProfile()">👤 PROFILE</button>
                <a href="?logout=1" class="game-btn danger" style="text-decoration: none;">🚪 LOGOUT</a>
            </div>
        </div>
    </div>

    <script src="../shared/gaming.js"></script>
    <script>
        // Lab 1 specific gaming logic

        function startServerRescue() {
            gaming.startChallenge('server_down_critical', 300); // 5 minutes

            // Simulate server rescue scenario
            const console = document.getElementById('server-console');
            const healthBar = document.getElementById('server-health');

            setTimeout(() => {
                console.innerHTML += '<div class="console-line console-prompt">Checking service status...</div>';
            }, 1000);

            setTimeout(() => {
                console.innerHTML += '<div class="console-line console-success">[INFO] Identified port conflict on :80</div>';
                healthBar.style.width = '35%';
            }, 3000);

            setTimeout(() => {
                console.innerHTML += '<div class="console-line console-success">[INFO] Killing conflicting process...</div>';
                healthBar.style.width = '60%';
            }, 5000);

            setTimeout(() => {
                console.innerHTML += '<div class="console-line console-success">[SUCCESS] nginx service restored!</div>';
                healthBar.style.width = '100%';
                healthBar.className = 'progress-bar'; // Remove danger class
                gaming.completeChallenge(true, 200);
            }, 7000);
        }

        function startMemoryHunt() {
            gaming.startChallenge('memory_leak_hunt', 180); // 3 minutes

            const monitor = document.getElementById('memory-monitor');
            const pressureBar = document.getElementById('memory-pressure');

            setTimeout(() => {
                monitor.innerHTML += '<div style="color: #2ed573;">Scanning process list...</div>';
            }, 1000);

            setTimeout(() => {
                monitor.innerHTML += '<div style="color: #f39c12;">Found suspicious process: chrome_helper (2.3GB)</div>';
                document.getElementById('memory-usage').textContent = '89%';
                pressureBar.style.width = '89%';
            }, 3000);

            setTimeout(() => {
                monitor.innerHTML += '<div style="color: #2ed573;">Terminating rogue process...</div>';
                document.getElementById('memory-usage').textContent = '34%';
                document.getElementById('memory-available').textContent = '10.8GB';
                pressureBar.style.width = '34%';
                pressureBar.className = 'progress-bar'; // Remove warning class
                gaming.completeChallenge(true, 150);
            }, 5000);
        }

        function startOptimization() {
            gaming.startChallenge('performance_tuning', 240); // 4 minutes

            const dashboard = document.getElementById('performance-dashboard');
            const scoreBar = document.getElementById('performance-score');

            setTimeout(() => {
                dashboard.innerHTML += '<div style="color: #2ed573; margin-top: 10px;">Enabling query cache...</div>';
                document.getElementById('response-time').textContent = '3.2s';
                document.getElementById('response-time').style.color = '#f39c12';
                scoreBar.style.width = '50%';
                scoreBar.className = 'progress-bar warning';
            }, 2000);

            setTimeout(() => {
                dashboard.innerHTML += '<div style="color: #2ed573;">Optimizing database queries...</div>';
                document.getElementById('response-time').textContent = '2.1s';
                scoreBar.style.width = '75%';
            }, 4000);

            setTimeout(() => {
                dashboard.innerHTML += '<div style="color: #2ed573;">Enabling gzip compression...</div>';
                document.getElementById('response-time').textContent = '1.3s';
                document.getElementById('response-time').style.color = '#2ed573';
                scoreBar.style.width = '100%';
                scoreBar.className = 'progress-bar'; // Remove warning class
                gaming.completeChallenge(true, 100);
            }, 6000);
        }

        function showHint(type) {
            if (!gaming.addHint(15)) {
                alert('Not enough XP for hint! Complete challenges to earn more.');
                return;
            }

            const hints = {
                server: 'Check if another service is already using port 80. Use: netstat -tulpn | grep :80',
                memory: 'Look for processes with high RSS memory usage. Use: ps aux --sort=-%mem | head',
                performance: 'Start with database optimization, then caching, then compression.'
            };

            gaming.showNotification(`Hint: ${hints[type]}`);
        }

        function viewLeaderboard() {
            gaming.showNotification('Leaderboard feature coming soon!');
        }

        function viewAchievements() {
            gaming.showNotification('Achievement gallery coming soon!');
        }

        function viewProfile() {
            gaming.showNotification('Player profile coming soon!');
        }

        // Update memory usage simulation
        setInterval(() => {
            const usage = document.getElementById('memory-usage');
            if (usage && !gaming.currentChallenge) {
                const current = parseInt(usage.textContent);
                const newUsage = Math.max(75, Math.min(89, current + Math.random() * 4 - 2));
                usage.textContent = Math.round(newUsage) + '%';

                const pressureBar = document.getElementById('memory-pressure');
                if (pressureBar) {
                    pressureBar.style.width = newUsage + '%';
                }
            }
        }, 2000);

        // Update response time simulation
        setInterval(() => {
            const responseTime = document.getElementById('response-time');
            if (responseTime && !gaming.currentChallenge) {
                const current = parseFloat(responseTime.textContent);
                const newTime = Math.max(3.5, Math.min(5.5, current + Math.random() * 0.6 - 0.3));
                responseTime.textContent = newTime.toFixed(1) + 's';

                const scoreBar = document.getElementById('performance-score');
                if (scoreBar) {
                    const score = Math.max(0, 100 - (newTime - 1) * 25);
                    scoreBar.style.width = score + '%';
                }
            }
        }, 3000);

    </script>
</body>
</html>