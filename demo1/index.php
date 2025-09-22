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
    <title>Demo1 - SMCLab.net</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600&family=Crimson+Text:ital,wght@0,400;1,400&display=swap');

        body {
            font-family: 'Crimson Text', serif;
            margin: 0;
            padding: 20px;
            background:
                radial-gradient(circle at 20% 20%, rgba(139, 69, 19, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(160, 82, 45, 0.2) 0%, transparent 50%),
                linear-gradient(135deg, #2c1810 0%, #3d2817 25%, #4a321c 50%, #5d4037 75%, #3e2723 100%);
            color: #f4e4bc;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .gears {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        .gear {
            position: absolute;
            border-radius: 50%;
            background:
                radial-gradient(circle at center, #cd853f 0%, #b8860b 30%, #8b6914 70%, #654321 100%);
            border: 4px solid #8b4513;
            box-shadow:
                inset 0 0 20px rgba(139, 69, 19, 0.5),
                0 0 30px rgba(205, 133, 63, 0.3),
                0 0 50px rgba(184, 134, 11, 0.2);
        }

        .gear:before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            height: 80%;
            background: repeating-conic-gradient(
                from 0deg,
                #8b4513 0deg 12deg,
                #cd853f 12deg 18deg,
                #8b4513 18deg 30deg,
                #cd853f 30deg 36deg
            );
            border-radius: 50%;
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.3);
        }

        .gear:after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 25%;
            height: 25%;
            background: radial-gradient(circle, #654321 0%, #3e2723 100%);
            border-radius: 50%;
            border: 2px solid #8b4513;
        }

        .gear1 {
            width: 120px;
            height: 120px;
            top: 10%;
            left: 15%;
            animation: rotate-clockwise 20s linear infinite;
        }

        .gear2 {
            width: 80px;
            height: 80px;
            top: 20%;
            right: 20%;
            animation: rotate-counter-clockwise 15s linear infinite;
        }

        .gear3 {
            width: 100px;
            height: 100px;
            bottom: 15%;
            left: 10%;
            animation: rotate-clockwise 25s linear infinite;
        }

        .gear4 {
            width: 140px;
            height: 140px;
            bottom: 10%;
            right: 15%;
            animation: rotate-counter-clockwise 18s linear infinite;
        }

        .gear5 {
            width: 60px;
            height: 60px;
            top: 50%;
            left: 5%;
            animation: rotate-clockwise 12s linear infinite;
        }

        .gear6 {
            width: 90px;
            height: 90px;
            top: 40%;
            right: 8%;
            animation: rotate-counter-clockwise 22s linear infinite;
        }

        @keyframes rotate-clockwise {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes rotate-counter-clockwise {
            from { transform: rotate(0deg); }
            to { transform: rotate(-360deg); }
        }

        .container {
            text-align: center;
            background:
                linear-gradient(145deg, rgba(139, 69, 19, 0.15) 0%, rgba(101, 67, 33, 0.25) 100%);
            padding: 50px;
            border-radius: 15px;
            border: 3px solid #8b4513;
            box-shadow:
                inset 0 0 30px rgba(205, 133, 63, 0.1),
                0 0 40px rgba(139, 69, 19, 0.3),
                0 15px 35px rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(10px);
            position: relative;
            z-index: 10;
            max-width: 600px;
        }

        .container:before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, #cd853f, #b8860b, #daa520, #cd853f);
            border-radius: 17px;
            z-index: -1;
        }

        h1 {
            font-family: 'Cinzel', serif;
            font-size: 3.5em;
            font-weight: 600;
            margin-bottom: 20px;
            color: #daa520;
            text-shadow:
                2px 2px 4px rgba(139, 69, 19, 0.8),
                0 0 20px rgba(218, 165, 32, 0.5);
            letter-spacing: 2px;
        }

        p {
            font-size: 1.3em;
            margin-bottom: 12px;
            color: #f4e4bc;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            line-height: 1.6;
        }

        a {
            color: #daa520;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        a:hover {
            color: #cd853f;
            text-shadow: 0 0 10px rgba(218, 165, 32, 0.8);
        }
    </style>
</head>
<body>
    <div class="gears">
        <div class="gear gear1"></div>
        <div class="gear gear2"></div>
        <div class="gear gear3"></div>
        <div class="gear gear4"></div>
        <div class="gear gear5"></div>
        <div class="gear gear6"></div>
    </div>

    <div class="container">
        <h1>demo1.smclab.net</h1>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
        <p>🌐 https://demo1.smclab.net</p>
        <p>Secured with SSL via nginx-proxy</p>
        <p>PHP + PostgreSQL authentication system</p>
        <p><a href="?logout=1" style="color: #fff; text-decoration: underline;">Logout</a></p>
    </div>
</body>
</html>