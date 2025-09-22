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
    <title>SMC Tech Lab 3 - Database Hero Challenges</title>
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
        <h1>SMC TECH LAB 3</h1>
        <h2 style="font-family: 'Orbitron', monospace; color: #2ed573; font-size: 1.8em; margin-bottom: 30px; letter-spacing: 2px;">💾 DATABASE HERO CHALLENGES 💾</h2>

        <div class="lab-status">
            <p>Database Admin: <span class="status"><?php echo htmlspecialchars($_SESSION['username']); ?></span></p>
            <p>🔒 Encrypted Database Connection Active</p>
            <p class="status">Database Cluster: OPERATIONAL</p>
        </div>

        <div class="missions-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin: 30px 0;">

            <!-- SQL Query Racing -->
            <div class="challenge-container">
                <div class="challenge-title">🏁 SQL Query Racing</div>
                <div class="challenge-difficulty difficulty-medium">MEDIUM DIFFICULTY</div>
                <div class="challenge-description">
                    Write optimal database queries faster than the clock. Performance matters!
                </div>
                <div class="challenge-interface">
                    <div id="query-editor" style="background: #0f1419; padding: 15px; border-radius: 8px; font-family: 'Courier New', monospace; font-size: 12px;">
                        <div style="color: #2ed573; margin-bottom: 10px;">Query Challenge: Find top 5 customers by revenue</div>
                        <div style="color: #bdc3c7; margin-bottom: 15px;">Tables: customers, orders, order_items, products</div>
                        <textarea id="sql-input" placeholder="SELECT ... FROM ..." style="width: 100%; height: 100px; background: #1a252f; color: #ecf0f1; border: 1px solid #34495e; border-radius: 4px; padding: 10px; font-family: 'Courier New', monospace; font-size: 12px; resize: vertical;"></textarea>
                        <div style="margin-top: 10px; display: flex; justify-content: space-between; align-items: center;">
                            <div style="color: #f39c12;">Execution Time: <span id="query-time">-- ms</span></div>
                            <div style="color: #2ed573;">Optimization Score: <span id="query-score">--</span>/100</div>
                        </div>
                    </div>
                    <div class="progress-container" style="margin: 10px 0;">
                        <div class="progress-bar" id="query-progress" style="width: 0%;"></div>
                    </div>
                    <div class="challenge-controls">
                        <button class="game-btn" onclick="executeQuery()">▶️ EXECUTE QUERY</button>
                        <button class="game-btn secondary" onclick="showHint('sql')">💡 HINT (15 XP)</button>
                    </div>
                </div>
                <div class="mission-stats" style="margin-top: 15px; font-size: 14px; color: #bdc3c7;">
                    Base Points: 125 | Time Limit: 2:00 | Success Rate: 56%
                </div>
            </div>

            <!-- Data Corruption Detective -->
            <div class="challenge-container">
                <div class="challenge-title">🔍 Data Detective</div>
                <div class="challenge-difficulty difficulty-hard">HARD DIFFICULTY</div>
                <div class="challenge-description">
                    Corruption detected in customer records! Find and fix the anomalies.
                </div>
                <div class="challenge-interface">
                    <div id="data-scanner" style="background: #0f1419; padding: 15px; border-radius: 8px; font-family: 'Courier New', monospace; font-size: 11px;">
                        <div style="color: #e74c3c; font-weight: bold; margin-bottom: 10px;">⚠️ DATA INTEGRITY SCAN ⚠️</div>
                        <div style="color: #2ed573;">Records Scanned: <span id="records-scanned">0</span> / 15,847</div>
                        <div style="color: #f39c12;">Anomalies Found: <span id="anomalies-found" style="color: #e74c3c; font-weight: bold;">0</span></div>
                        <div style="margin-top: 10px; max-height: 120px; overflow-y: auto;">
                            <div style="color: #bdc3c7;">Scanning customer data...</div>
                            <div id="scan-results"></div>
                        </div>
                        <div style="margin-top: 10px; color: #2ed573;">Data Quality: <span id="data-quality">100%</span></div>
                    </div>
                    <div class="progress-container" style="margin: 10px 0;">
                        <div class="progress-bar" id="scan-progress" style="width: 0%;"></div>
                    </div>
                    <div class="challenge-controls">
                        <button class="game-btn" onclick="startDataScan()">🔍 START SCAN</button>
                        <button class="game-btn secondary" onclick="showHint('data')">💡 HINT (15 XP)</button>
                    </div>
                </div>
                <div class="mission-stats" style="margin-top: 15px; font-size: 14px; color: #bdc3c7;">
                    Base Points: 175 | Time Limit: 4:00 | Success Rate: 31%
                </div>
            </div>

            <!-- Backup Recovery Hero -->
            <div class="challenge-container">
                <div class="challenge-title">🛡️ Backup Recovery Hero</div>
                <div class="challenge-difficulty difficulty-hard">HARD DIFFICULTY</div>
                <div class="challenge-description">
                    Critical data loss! Restore the database from corrupted backup files.
                </div>
                <div class="challenge-interface">
                    <div id="recovery-console" style="background: #0f1419; padding: 15px; border-radius: 8px; font-family: 'Courier New', monospace; font-size: 12px;">
                        <div style="color: #e74c3c; font-weight: bold; margin-bottom: 10px;">DISASTER RECOVERY PROTOCOL</div>
                        <div style="color: #2ed573;">Available Backups:</div>
                        <div style="margin: 10px 0; color: #bdc3c7;">
                            <div>• backup_2024_01_15.sql (98% intact) ✓</div>
                            <div>• backup_2024_01_14.sql (67% intact) ⚠️</div>
                            <div>• backup_2024_01_13.sql (23% intact) ❌</div>
                        </div>
                        <div style="color: #f39c12;">Recovery Status: <span id="recovery-status">Standby</span></div>
                        <div style="color: #2ed573;">Data Integrity: <span id="data-integrity">0%</span></div>
                        <div style="margin-top: 10px; max-height: 80px; overflow-y: auto;">
                            <div id="recovery-log" style="color: #bdc3c7;">Waiting for recovery initiation...</div>
                        </div>
                    </div>
                    <div class="progress-container" style="margin: 10px 0;">
                        <div class="progress-bar danger" id="recovery-progress" style="width: 0%;"></div>
                    </div>
                    <div class="challenge-controls">
                        <button class="game-btn" onclick="startRecovery()">🛡️ INITIATE RECOVERY</button>
                        <button class="game-btn secondary" onclick="showHint('recovery')">💡 HINT (15 XP)</button>
                    </div>
                </div>
                <div class="mission-stats" style="margin-top: 15px; font-size: 14px; color: #bdc3c7;">
                    Base Points: 200 | Time Limit: 6:00 | Success Rate: 18%
                </div>
            </div>

        </div>

        <div class="lab-controls" style="margin: 30px 0; padding: 20px; background: rgba(52, 73, 94, 0.4); border-radius: 8px; border: 1px solid #34495e;">
            <h3 style="font-family: 'Orbitron', monospace; color: #2ed573; margin-bottom: 15px;">💾 Database Operations</h3>
            <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                <button class="game-btn secondary" onclick="viewDatabaseMetrics()">📊 DB METRICS</button>
                <button class="game-btn secondary" onclick="viewQueryHistory()">📄 QUERY HISTORY</button>
                <button class="game-btn secondary" onclick="viewBackupStatus()">💾 BACKUP STATUS</button>
                <a href="?logout=1" class="game-btn danger" style="text-decoration: none;">🚪 LOGOUT</a>
            </div>
        </div>
    </div>

    <script src="../shared/gaming.js"></script>
    <script>
        // Lab 3 specific gaming logic
        let queryAttempts = 0;
        let queryStartTime = null;

        function executeQuery() {
            if (!gaming.currentChallenge) {
                gaming.startChallenge('sql_query_race', 120); // 2 minutes
                queryStartTime = Date.now();
            }

            queryAttempts++;
            const query = document.getElementById('sql-input').value.toLowerCase();
            const timeElement = document.getElementById('query-time');
            const scoreElement = document.getElementById('query-score');
            const progressBar = document.getElementById('query-progress');

            // Simulate query execution time
            const executionTime = Math.random() * 200 + 50;
            timeElement.textContent = Math.round(executionTime) + ' ms';

            // Simple scoring based on query optimization
            let score = 0;
            if (query.includes('select')) score += 20;
            if (query.includes('join')) score += 25;
            if (query.includes('group by')) score += 20;
            if (query.includes('order by')) score += 15;
            if (query.includes('limit')) score += 20;
            if (query.includes('sum') || query.includes('count')) score += 15;
            if (query.includes('where')) score += 10;

            // Penalty for inefficient patterns
            if (query.includes('select *')) score -= 15;
            if (query.includes('like \'%')) score -= 10;

            score = Math.max(0, Math.min(100, score));
            scoreElement.textContent = score;

            progressBar.style.width = score + '%';

            if (score >= 85) {
                progressBar.className = 'progress-bar';
                setTimeout(() => {
                    gaming.completeChallenge(true, 125 + (score - 85));
                }, 1000);
            } else if (score >= 60) {
                progressBar.className = 'progress-bar warning';
            } else {
                progressBar.className = 'progress-bar danger';
            }

            if (queryAttempts >= 5 && score < 85) {
                gaming.completeChallenge(false);
            }
        }

        function startDataScan() {
            gaming.startChallenge('data_corruption_detective', 240); // 4 minutes

            const scannedElement = document.getElementById('records-scanned');
            const anomaliesElement = document.getElementById('anomalies-found');
            const qualityElement = document.getElementById('data-quality');
            const resultsElement = document.getElementById('scan-results');
            const progressBar = document.getElementById('scan-progress');

            let recordsScanned = 0;
            let anomaliesFound = 0;
            const totalRecords = 15847;
            const expectedAnomalies = 23;

            const scanInterval = setInterval(() => {
                recordsScanned += Math.floor(Math.random() * 500) + 200;
                recordsScanned = Math.min(recordsScanned, totalRecords);

                scannedElement.textContent = recordsScanned.toLocaleString();
                progressBar.style.width = (recordsScanned / totalRecords * 100) + '%';

                // Occasionally find anomalies
                if (Math.random() < 0.3 && anomaliesFound < expectedAnomalies) {
                    anomaliesFound++;
                    anomaliesElement.textContent = anomaliesFound;

                    const anomalyTypes = [
                        'NULL email in customer_id 12847',
                        'Invalid date format in order_date',
                        'Negative quantity in order_items',
                        'Duplicate customer records found',
                        'Foreign key constraint violation',
                        'Invalid phone number format',
                        'Missing required field data'
                    ];

                    const anomaly = anomalyTypes[Math.floor(Math.random() * anomalyTypes.length)];
                    resultsElement.innerHTML += `<div style="color: #e74c3c;">[ANOMALY] ${anomaly}</div>`;
                }

                const quality = Math.max(70, 100 - (anomaliesFound / expectedAnomalies * 30));
                qualityElement.textContent = Math.round(quality) + '%';

                if (recordsScanned >= totalRecords) {
                    clearInterval(scanInterval);
                    resultsElement.innerHTML += `<div style="color: #2ed573;">[COMPLETE] Scan finished - ${anomaliesFound} anomalies detected</div>`;

                    if (anomaliesFound >= expectedAnomalies * 0.8) {
                        gaming.completeChallenge(true, 175);
                    } else {
                        gaming.completeChallenge(false);
                    }
                }
            }, 300);
        }

        function startRecovery() {
            gaming.startChallenge('backup_recovery_hero', 360); // 6 minutes

            const statusElement = document.getElementById('recovery-status');
            const integrityElement = document.getElementById('data-integrity');
            const logElement = document.getElementById('recovery-log');
            const progressBar = document.getElementById('recovery-progress');

            let recoveryStage = 0;
            const stages = [
                { text: 'Analyzing backup integrity...', duration: 3000, integrity: 15 },
                { text: 'Extracting usable data segments...', duration: 4000, integrity: 35 },
                { text: 'Reconstructing table structures...', duration: 3500, integrity: 55 },
                { text: 'Restoring foreign key constraints...', duration: 2500, integrity: 75 },
                { text: 'Validating data consistency...', duration: 2000, integrity: 90 },
                { text: 'Finalizing recovery process...', duration: 1500, integrity: 100 }
            ];

            function executeStage() {
                if (recoveryStage < stages.length) {
                    const stage = stages[recoveryStage];
                    statusElement.textContent = 'IN PROGRESS';
                    statusElement.style.color = '#f39c12';

                    logElement.innerHTML += `<div style="color: #2ed573;">[INFO] ${stage.text}</div>`;

                    setTimeout(() => {
                        integrityElement.textContent = stage.integrity + '%';
                        progressBar.style.width = stage.integrity + '%';

                        if (stage.integrity >= 70) {
                            progressBar.className = 'progress-bar';
                        } else if (stage.integrity >= 40) {
                            progressBar.className = 'progress-bar warning';
                        }

                        recoveryStage++;
                        if (recoveryStage < stages.length) {
                            executeStage();
                        } else {
                            statusElement.textContent = 'COMPLETE';
                            statusElement.style.color = '#2ed573';
                            logElement.innerHTML += `<div style="color: #2ed573; font-weight: bold;">[SUCCESS] Database recovery completed successfully!</div>`;
                            gaming.completeChallenge(true, 200);
                        }
                    }, stage.duration);
                }
            }

            executeStage();
        }

        function showHint(type) {
            if (!gaming.addHint(15)) {
                alert('Not enough XP for hint! Complete challenges to earn more.');
                return;
            }

            const hints = {
                sql: 'Use JOINs instead of subqueries. Include GROUP BY, ORDER BY, and LIMIT for optimal scoring.',
                data: 'Look for common data anomalies: NULLs, duplicates, format violations, and constraint errors.',
                recovery: 'Start with the most recent backup with highest integrity. Validate each step.'
            };

            gaming.showNotification(`Hint: ${hints[type]}`);
        }

        function viewDatabaseMetrics() {
            gaming.showNotification('Database performance metrics coming soon!');
        }

        function viewQueryHistory() {
            gaming.showNotification('Query execution history coming soon!');
        }

        function viewBackupStatus() {
            gaming.showNotification('Backup status monitoring coming soon!');
        }

        // Auto-populate sample query for demonstration
        document.getElementById('sql-input').value = 'SELECT c.name, SUM(oi.quantity * p.price) as revenue\nFROM customers c\nJOIN orders o ON c.id = o.customer_id\nJOIN order_items oi ON o.id = oi.order_id\nJOIN products p ON oi.product_id = p.id\nGROUP BY c.id, c.name\nORDER BY revenue DESC\nLIMIT 5;';

        // Simulate live database metrics
        setInterval(() => {
            if (!gaming.currentChallenge) {
                // Add some live data simulation here if needed
            }
        }, 5000);

    </script>
</body>
</html>