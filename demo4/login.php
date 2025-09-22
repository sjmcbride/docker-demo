<?php
require_once 'config.php';

$error = '';

if ($_POST) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (loginUser($username, $password)) {
        header('Location: index.php');
        exit;
    } else {
        $error = 'Invalid username or password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Demo1 SMCLab.net</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            color: #333;
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
            background: rgba(255, 255, 255, 0.03);
            border: 3px solid rgba(255, 255, 255, 0.1);
        }

        .gear:before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 70%;
            height: 70%;
            background: repeating-conic-gradient(
                from 0deg,
                transparent 0deg 18deg,
                rgba(255, 255, 255, 0.05) 18deg 36deg
            );
            border-radius: 50%;
        }

        .gear1 { width: 120px; height: 120px; top: 10%; left: 15%; animation: rotate-clockwise 20s linear infinite; }
        .gear2 { width: 80px; height: 80px; top: 20%; right: 20%; animation: rotate-counter-clockwise 15s linear infinite; }
        .gear3 { width: 100px; height: 100px; bottom: 15%; left: 10%; animation: rotate-clockwise 25s linear infinite; }
        .gear4 { width: 140px; height: 140px; bottom: 10%; right: 15%; animation: rotate-counter-clockwise 18s linear infinite; }
        .gear5 { width: 60px; height: 60px; top: 50%; left: 5%; animation: rotate-clockwise 12s linear infinite; }
        .gear6 { width: 90px; height: 90px; top: 40%; right: 8%; animation: rotate-counter-clockwise 22s linear infinite; }

        @keyframes rotate-clockwise { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        @keyframes rotate-counter-clockwise { from { transform: rotate(0deg); } to { transform: rotate(-360deg); } }
        .container {
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            max-width: 400px;
            width: 100%;
            position: relative;
            z-index: 10;
        }
        h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
        }
        form {
            margin-top: 20px;
        }
        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #4CAF50;
            color: #333;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background: #45a049;
        }
        .error {
            color: #ff6b6b;
            margin: 10px 0;
        }
        .demo-info {
            margin-top: 20px;
            font-size: 14px;
            opacity: 0.8;
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
        <h1>demo4.smclab.net</h1>
        <p>Please login to continue</p>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <div class="demo-info">
            <strong>Demo Credentials:</strong><br>
            Username: demo4user<br>
            Password: password<br>
            <br>
            Admin access: admin / password
        </div>
    </div>
</body>
</html>