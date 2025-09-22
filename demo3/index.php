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
    <title>Demo3 - SMCLab.net</title>
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
        <div class="logo"><span class="sc">SC</span></div>
        <h1>DEMO3.SMCLAB.NET</h1>
        <p>Welcome, <span class="status"><?php echo htmlspecialchars($_SESSION['username']); ?></span></p>
        <p>🔒 Secure Connection Established</p>
        <p class="status">System Status: ONLINE</p>
        <p>Advanced Authentication Protocol Active</p>
        <p><a href="?logout=1">System Logout</a></p>
    </div>
</body>
</html>