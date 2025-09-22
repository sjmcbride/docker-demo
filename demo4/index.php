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
    <title>SMC Tech Lab 4 - Security Command Center</title>
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
        <h1>SMC TECH LAB 4</h1>
        <h2 style="font-family: 'Orbitron', monospace; color: #2ed573; font-size: 1.8em; margin-bottom: 30px; letter-spacing: 2px;">🛡️ SECURITY COMMAND CENTER 🛡️</h2>

        <div class="lab-status">
            <p>Security Analyst: <span class="status"><?php echo htmlspecialchars($_SESSION['username']); ?></span></p>
            <p>🔒 Classified Network Access Granted</p>
            <p class="status">Security Posture: HIGH ALERT</p>
        </div>

        <div class="missions-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin: 30px 0;">

            <!-- Vulnerability Hunter -->
            <div class="challenge-container">
                <div class="challenge-title">🔍 Vulnerability Hunter</div>
                <div class="challenge-difficulty difficulty-hard">HARD DIFFICULTY</div>
                <div class="challenge-description">
                    Scan application code for critical security vulnerabilities before deployment.
                </div>
                <div class="challenge-interface">
                    <div id="vuln-scanner" style="background: #0f1419; padding: 15px; border-radius: 8px; font-family: 'Courier New', monospace; font-size: 11px;">
                        <div style="color: #e74c3c; font-weight: bold; margin-bottom: 10px;">🔍 VULNERABILITY ASSESSMENT 🔍</div>
                        <div style="color: #2ed573;">Files Scanned: <span id="files-scanned">0</span> / 247</div>
                        <div style="color: #f39c12;">Vulnerabilities: <span id="vulns-found" style="color: #e74c3c; font-weight: bold;">0</span></div>
                        <div style="margin: 10px 0; max-height: 120px; overflow-y: auto; border: 1px solid #34495e; padding: 8px; border-radius: 4px;">
                            <div style="color: #bdc3c7;">Initializing security scan...</div>
                            <div id="vuln-results"></div>
                        </div>
                        <div style="color: #2ed573;">Security Score: <span id="security-score" style="color: #e74c3c; font-weight: bold;">0</span>/100</div>
                    </div>
                    <div class="progress-container" style="margin: 10px 0;">
                        <div class="progress-bar danger" id="vuln-progress" style="width: 0%;"></div>
                    </div>
                    <div class="challenge-controls">
                        <button class="game-btn" onclick="startVulnScan()">🔍 INITIATE SCAN</button>
                        <button class="game-btn secondary" onclick="showHint('vuln')">💡 HINT (15 XP)</button>
                    </div>
                </div>
                <div class="mission-stats" style="margin-top: 15px; font-size: 14px; color: #bdc3c7;">
                    Base Points: 200 | Time Limit: 5:00 | Success Rate: 28%
                </div>
            </div>

            <!-- Password Fortress -->
            <div class="challenge-container">
                <div class="challenge-title">🔐 Password Fortress</div>
                <div class="challenge-difficulty difficulty-medium">MEDIUM DIFFICULTY</div>
                <div class="challenge-description">
                    Design an unbreakable authentication system that withstands attacks.
                </div>
                <div class="challenge-interface">
                    <div id="auth-system" style="background: #0f1419; padding: 15px; border-radius: 8px; font-family: 'Courier New', monospace; font-size: 12px;">
                        <div style="color: #2ed573; font-weight: bold; margin-bottom: 10px;">AUTHENTICATION FORTRESS</div>
                        <div style="margin-bottom: 15px;">
                            <div style="color: #f39c12;">Security Features:</div>
                            <div id="security-features" style="margin-left: 20px; color: #bdc3c7;">
                                <div><input type="checkbox" id="feat-mfa"> Multi-Factor Authentication</div>
                                <div><input type="checkbox" id="feat-bcrypt"> bcrypt Password Hashing</div>
                                <div><input type="checkbox" id="feat-rate-limit"> Rate Limiting</div>
                                <div><input type="checkbox" id="feat-session"> Secure Session Management</div>
                                <div><input type="checkbox" id="feat-captcha"> CAPTCHA Protection</div>
                            </div>
                        </div>
                        <div style="color: #e74c3c;">Brute Force Attempts: <span id="brute-attempts">0</span></div>
                        <div style="color: #2ed573;">Fortress Strength: <span id="fortress-strength">25%</span></div>
                    </div>
                    <div class="progress-container" style="margin: 10px 0;">
                        <div class="progress-bar danger" id="fortress-progress" style="width: 25%;"></div>
                    </div>
                    <div class="challenge-controls">
                        <button class="game-btn" onclick="testFortress()">🛡️ TEST DEFENSES</button>
                        <button class="game-btn secondary" onclick="showHint('auth')">💡 HINT (15 XP)</button>
                    </div>
                </div>
                <div class="mission-stats" style="margin-top: 15px; font-size: 14px; color: #bdc3c7;">
                    Base Points: 150 | Time Limit: 4:00 | Success Rate: 52%
                </div>
            </div>

            <!-- Digital Forensics Investigation -->
            <div class="challenge-container">
                <div class="challenge-title">🕵️ Digital Forensics</div>
                <div class="challenge-difficulty difficulty-hard">HARD DIFFICULTY</div>
                <div class="challenge-description">
                    Investigate a security incident and identify the attack vector.
                </div>
                <div class="challenge-interface">
                    <div id="forensics-console" style="background: #0f1419; padding: 15px; border-radius: 8px; font-family: 'Courier New', monospace; font-size: 11px;">
                        <div style="color: #e74c3c; font-weight: bold; margin-bottom: 10px;">INCIDENT RESPONSE - CASE #2024-0156</div>
                        <div style="color: #f39c12; margin-bottom: 10px;">BREACH DETECTED: Unauthorized data access</div>
                        <div style="color: #2ed573;">Evidence Collected: <span id="evidence-count">0</span>/12</div>
                        <div style="margin: 10px 0; max-height: 100px; overflow-y: auto; border: 1px solid #34495e; padding: 8px; border-radius: 4px;">
                            <div style="color: #bdc3c7;">Starting forensic analysis...</div>
                            <div id="forensics-log"></div>
                        </div>
                        <div style="color: #2ed573;">Case Status: <span id="case-status" style="color: #f39c12;">INVESTIGATING</span></div>
                        <div style="color: #bdc3c7;">Suspected Vector: <span id="attack-vector" style="color: #e74c3c;">UNKNOWN</span></div>
                    </div>
                    <div class="progress-container" style="margin: 10px 0;">
                        <div class="progress-bar warning" id="forensics-progress" style="width: 0%;"></div>
                    </div>
                    <div class="challenge-controls">
                        <button class="game-btn" onclick="startForensics()">🕵️ BEGIN INVESTIGATION</button>
                        <button class="game-btn secondary" onclick="showHint('forensics')">💡 HINT (15 XP)</button>
                    </div>
                </div>
                <div class="mission-stats" style="margin-top: 15px; font-size: 14px; color: #bdc3c7;">
                    Base Points: 250 | Time Limit: 10:00 | Success Rate: 15%
                </div>
            </div>

        </div>

        <div class="lab-controls" style="margin: 30px 0; padding: 20px; background: rgba(52, 73, 94, 0.4); border-radius: 8px; border: 1px solid #34495e;">
            <h3 style="font-family: 'Orbitron', monospace; color: #2ed573; margin-bottom: 15px;">🛡️ Security Operations</h3>
            <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                <button class="game-btn secondary" onclick="viewThreatIntel()">🔍 THREAT INTEL</button>
                <button class="game-btn secondary" onclick="viewIncidentReports()">📄 INCIDENTS</button>
                <button class="game-btn secondary" onclick="viewSecurityMetrics()">📊 METRICS</button>
                <a href="?logout=1" class="game-btn danger" style="text-decoration: none;">🚪 LOGOUT</a>
            </div>
        </div>
    </div>

    <script src="../shared/gaming.js"></script>
    <script>
        // Lab 4 specific gaming logic

        function startVulnScan() {
            gaming.startChallenge('vulnerability_hunter', 300); // 5 minutes

            const filesElement = document.getElementById('files-scanned');
            const vulnsElement = document.getElementById('vulns-found');
            const scoreElement = document.getElementById('security-score');
            const resultsElement = document.getElementById('vuln-results');
            const progressBar = document.getElementById('vuln-progress');

            let filesScanned = 0;
            let vulnsFound = 0;
            const totalFiles = 247;
            const criticalVulns = 8;

            const vulnerabilities = [
                { type: 'SQL Injection', file: 'login.php', severity: 'CRITICAL' },
                { type: 'XSS Vulnerability', file: 'profile.php', severity: 'HIGH' },
                { type: 'CSRF Token Missing', file: 'admin.php', severity: 'MEDIUM' },
                { type: 'Insecure Direct Object Reference', file: 'api.php', severity: 'HIGH' },
                { type: 'Hardcoded Credentials', file: 'config.php', severity: 'CRITICAL' },
                { type: 'Path Traversal', file: 'upload.php', severity: 'HIGH' },
                { type: 'Weak Session Management', file: 'session.php', severity: 'MEDIUM' },
                { type: 'Information Disclosure', file: 'error.php', severity: 'LOW' }
            ];

            const scanInterval = setInterval(() => {
                filesScanned += Math.floor(Math.random() * 15) + 5;
                filesScanned = Math.min(filesScanned, totalFiles);

                filesElement.textContent = filesScanned;
                progressBar.style.width = (filesScanned / totalFiles * 100) + '%';

                // Find vulnerabilities as we scan
                if (Math.random() < 0.4 && vulnsFound < vulnerabilities.length) {
                    const vuln = vulnerabilities[vulnsFound];
                    vulnsFound++;
                    vulnsElement.textContent = vulnsFound;

                    const colorClass = vuln.severity === 'CRITICAL' ? '#e74c3c' :
                                      vuln.severity === 'HIGH' ? '#f39c12' :
                                      vuln.severity === 'MEDIUM' ? '#f39c12' : '#2ed573';

                    resultsElement.innerHTML += `<div style="color: ${colorClass};">[${vuln.severity}] ${vuln.type} in ${vuln.file}</div>`;
                }

                const score = Math.max(0, 100 - (vulnsFound / criticalVulns * 100));
                scoreElement.textContent = Math.round(score);
                scoreElement.style.color = score > 70 ? '#2ed573' : score > 40 ? '#f39c12' : '#e74c3c';

                if (progressBar) {
                    if (score > 70) {
                        progressBar.className = 'progress-bar';
                    } else if (score > 40) {
                        progressBar.className = 'progress-bar warning';
                    } else {
                        progressBar.className = 'progress-bar danger';
                    }
                }

                if (filesScanned >= totalFiles) {
                    clearInterval(scanInterval);
                    resultsElement.innerHTML += `<div style="color: #2ed573; font-weight: bold;">[COMPLETE] Security scan finished</div>`;

                    if (vulnsFound >= criticalVulns * 0.8) {
                        gaming.completeChallenge(true, 200 + (vulnsFound * 10));
                    } else {
                        gaming.completeChallenge(false);
                    }
                }
            }, 400);
        }

        function testFortress() {
            gaming.startChallenge('password_fortress', 240); // 4 minutes

            const features = {
                'feat-mfa': 25,
                'feat-bcrypt': 20,
                'feat-rate-limit': 20,
                'feat-session': 15,
                'feat-captcha': 20
            };

            let totalStrength = 25; // Base strength
            Object.keys(features).forEach(featId => {
                const checkbox = document.getElementById(featId);
                if (checkbox && checkbox.checked) {
                    totalStrength += features[featId];
                }
            });

            const strengthElement = document.getElementById('fortress-strength');
            const progressBar = document.getElementById('fortress-progress');
            const attemptsElement = document.getElementById('brute-attempts');

            strengthElement.textContent = totalStrength + '%';
            progressBar.style.width = totalStrength + '%';

            if (totalStrength > 80) {
                progressBar.className = 'progress-bar';
            } else if (totalStrength > 50) {
                progressBar.className = 'progress-bar warning';
            } else {
                progressBar.className = 'progress-bar danger';
            }

            // Simulate brute force attack
            let attempts = 0;
            const maxAttempts = Math.max(10, 100 - totalStrength);

            const attackInterval = setInterval(() => {
                attempts++;
                attemptsElement.textContent = attempts;

                if (attempts >= maxAttempts) {
                    clearInterval(attackInterval);
                    if (totalStrength >= 75) {
                        gaming.completeChallenge(true, 150 + (totalStrength - 75));
                    } else {
                        gaming.completeChallenge(false);
                    }
                }
            }, 200);
        }

        function startForensics() {
            gaming.startChallenge('digital_forensics', 600); // 10 minutes

            const evidenceElement = document.getElementById('evidence-count');
            const statusElement = document.getElementById('case-status');
            const vectorElement = document.getElementById('attack-vector');
            const logElement = document.getElementById('forensics-log');
            const progressBar = document.getElementById('forensics-progress');

            let evidenceFound = 0;
            const totalEvidence = 12;

            const evidenceItems = [
                'Unusual network traffic patterns detected',
                'Suspicious login attempts from foreign IPs',
                'Modified system files timestamp analysis',
                'Database query log anomalies found',
                'Privilege escalation attempts logged',
                'Malicious payload signatures identified',
                'Lateral movement indicators discovered',
                'Data exfiltration patterns confirmed',
                'Command injection traces located',
                'Backdoor installation evidence found',
                'Credential harvesting tools detected',
                'Attack attribution indicators collected'
            ];

            const investigationInterval = setInterval(() => {
                if (evidenceFound < evidenceItems.length && Math.random() < 0.7) {
                    const evidence = evidenceItems[evidenceFound];
                    evidenceFound++;
                    evidenceElement.textContent = evidenceFound;
                    logElement.innerHTML += `<div style="color: #2ed573;">[EVIDENCE] ${evidence}</div>`;
                    progressBar.style.width = (evidenceFound / totalEvidence * 100) + '%';

                    if (evidenceFound >= 8) {
                        vectorElement.textContent = 'SQL INJECTION';
                        vectorElement.style.color = '#e74c3c';
                    }

                    if (evidenceFound >= 10) {
                        statusElement.textContent = 'CASE SOLVED';
                        statusElement.style.color = '#2ed573';
                        progressBar.className = 'progress-bar';
                        clearInterval(investigationInterval);

                        logElement.innerHTML += `<div style="color: #2ed573; font-weight: bold;">[SOLVED] Attack vector identified: SQL Injection via login form</div>`;
                        gaming.completeChallenge(true, 250 + (evidenceFound * 5));
                    }
                }
            }, 1500);
        }

        function showHint(type) {
            if (!gaming.addHint(15)) {
                alert('Not enough XP for hint! Complete challenges to earn more.');
                return;
            }

            const hints = {
                vuln: 'Look for common vulnerabilities: SQL injection, XSS, CSRF, hardcoded credentials, and insecure file uploads.',
                auth: 'Strong authentication needs multiple layers: MFA, strong hashing, rate limiting, and secure sessions.',
                forensics: 'Follow the digital trail: logs, network traffic, file modifications, and timeline reconstruction.'
            };

            gaming.showNotification(`Hint: ${hints[type]}`);
        }

        function viewThreatIntel() {
            gaming.showNotification('Threat intelligence feed coming soon!');
        }

        function viewIncidentReports() {
            gaming.showNotification('Security incident dashboard coming soon!');
        }

        function viewSecurityMetrics() {
            gaming.showNotification('Security metrics analytics coming soon!');
        }

        // Enable security features by default for better UX
        setTimeout(() => {
            document.getElementById('feat-mfa').checked = true;
            document.getElementById('feat-bcrypt').checked = true;
        }, 1000);

        // Auto-update fortress strength when checkboxes change
        ['feat-mfa', 'feat-bcrypt', 'feat-rate-limit', 'feat-session', 'feat-captcha'].forEach(featId => {
            const checkbox = document.getElementById(featId);
            if (checkbox) {
                checkbox.addEventListener('change', () => {
                    const features = {
                        'feat-mfa': 25,
                        'feat-bcrypt': 20,
                        'feat-rate-limit': 20,
                        'feat-session': 15,
                        'feat-captcha': 20
                    };

                    let totalStrength = 25;
                    Object.keys(features).forEach(id => {
                        const cb = document.getElementById(id);
                        if (cb && cb.checked) {
                            totalStrength += features[id];
                        }
                    });

                    document.getElementById('fortress-strength').textContent = totalStrength + '%';
                    const progressBar = document.getElementById('fortress-progress');
                    progressBar.style.width = totalStrength + '%';

                    if (totalStrength > 80) {
                        progressBar.className = 'progress-bar';
                    } else if (totalStrength > 50) {
                        progressBar.className = 'progress-bar warning';
                    } else {
                        progressBar.className = 'progress-bar danger';
                    }
                });
            }
        });

    </script>
</body>
</html>