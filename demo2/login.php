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
    <title>SMC Laboratory - Chamber Two Access Portal</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Fredoka+One:wght@400&family=Comic+Neue:wght@400;700&display=swap');

        body {
            font-family: 'Comic Neue', cursive;
            margin: 0;
            padding: 20px;
            background:
                radial-gradient(circle at 15% 20%, #ff6b9d 0%, transparent 40%),
                radial-gradient(circle at 85% 80%, #4ecdc4 0%, transparent 40%),
                radial-gradient(circle at 50% 50%, #ffe66d 0%, transparent 30%),
                linear-gradient(135deg, #a8e6cf 0%, #88d8c0 25%, #7fcdcd 50%, #81c784 75%, #aed581 100%);
            color: #2d3436;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
            animation: bubbleFloat 20s ease-in-out infinite;
        }

        @keyframes bubbleFloat {
            0%, 100% { background-position: 0% 0%, 100% 100%, 50% 50%, 0% 0%; }
            50% { background-position: 10% 20%, 90% 80%, 60% 40%, 10% 10%; }
        }

        .lab-equipment {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        .bubble {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.1));
            border: 2px solid rgba(255, 255, 255, 0.6);
            animation: float-up linear infinite;
        }

        .beaker {
            position: absolute;
            width: 60px;
            height: 80px;
            background: linear-gradient(to bottom, transparent 20%, #ff6b9d 20%, #ff6b9d 80%, #e84393 100%);
            border: 4px solid #2d3436;
            border-radius: 0 0 20px 20px;
            animation: bubble-brew 3s ease-in-out infinite;
        }

        .beaker:before {
            content: '';
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 20px;
            background: transparent;
            border: 4px solid #2d3436;
            border-bottom: none;
            border-radius: 10px 10px 0 0;
        }

        .test-tube {
            position: absolute;
            width: 20px;
            height: 100px;
            background: linear-gradient(to bottom, transparent 30%, #4ecdc4 30%, #4ecdc4 90%, #00b894 100%);
            border: 3px solid #2d3436;
            border-radius: 0 0 10px 10px;
            animation: shake 2s ease-in-out infinite;
        }

        .bubble1 { width: 20px; height: 20px; left: 10%; top: 100%; animation-duration: 8s; animation-delay: 0s; }
        .bubble2 { width: 15px; height: 15px; left: 20%; top: 100%; animation-duration: 6s; animation-delay: 1s; }
        .bubble3 { width: 25px; height: 25px; left: 80%; top: 100%; animation-duration: 10s; animation-delay: 2s; }
        .bubble4 { width: 18px; height: 18px; left: 85%; top: 100%; animation-duration: 7s; animation-delay: 0.5s; }
        .bubble5 { width: 22px; height: 22px; left: 15%; top: 100%; animation-duration: 9s; animation-delay: 3s; }
        .bubble6 { width: 16px; height: 16px; left: 90%; top: 100%; animation-duration: 8s; animation-delay: 1.5s; }

        .beaker1 { top: 10%; left: 8%; }
        .beaker2 { top: 70%; right: 10%; }
        .test-tube1 { top: 20%; right: 5%; }
        .test-tube2 { bottom: 20%; left: 5%; }

        @keyframes float-up {
            0% { transform: translateY(0px) scale(0); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-100vh) scale(1.2); opacity: 0; }
        }

        @keyframes bubble-brew {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        @keyframes shake {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(2deg); }
            75% { transform: rotate(-2deg); }
        }

        .container {
            text-align: center;
            background:
                linear-gradient(145deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.85) 100%);
            padding: 40px;
            border-radius: 25px;
            border: 5px solid #2d3436;
            box-shadow:
                0 0 0 3px #ff6b9d,
                0 0 0 6px #4ecdc4,
                0 20px 40px rgba(45, 52, 54, 0.3),
                inset 0 0 20px rgba(255, 235, 59, 0.2);
            position: relative;
            z-index: 10;
            max-width: 500px;
            width: 100%;
            transform: rotate(-1deg);
            animation: wiggle 6s ease-in-out infinite;
        }

        @keyframes wiggle {
            0%, 100% { transform: rotate(-1deg); }
            25% { transform: rotate(1deg); }
            50% { transform: rotate(-0.5deg); }
            75% { transform: rotate(0.5deg); }
        }

        .container:before {
            content: '⚗️';
            position: absolute;
            top: -20px;
            left: -20px;
            font-size: 2em;
            animation: bounce 2s ease-in-out infinite;
        }

        .container:after {
            content: '🧪';
            position: absolute;
            top: -20px;
            right: -20px;
            font-size: 2em;
            animation: bounce 2s ease-in-out infinite 1s;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        h1 {
            font-family: 'Fredoka One', cursive;
            font-size: 2.8em;
            margin-bottom: 10px;
            color: #e84393;
            text-shadow:
                3px 3px 0px #2d3436,
                6px 6px 10px rgba(45, 52, 54, 0.3);
            letter-spacing: 2px;
            transform: rotate(1deg);
        }

        h2 {
            font-family: 'Fredoka One', cursive;
            color: #4ecdc4;
            margin-bottom: 20px;
            font-size: 1.5em;
            letter-spacing: 1px;
            transform: rotate(-1deg);
        }

        p {
            font-size: 1.2em;
            margin-bottom: 15px;
            color: #2d3436;
            font-weight: 700;
            line-height: 1.6;
        }

        .error {
            color: #e84393;
            margin: 15px 0;
            padding: 15px;
            background: rgba(255, 107, 157, 0.1);
            border: 3px solid #ff6b9d;
            border-radius: 15px;
            font-weight: bold;
            animation: wiggle 2s ease-in-out infinite;
        }

        input {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: 3px solid #4ecdc4;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.9);
            color: #2d3436;
            font-size: 16px;
            font-family: 'Comic Neue', cursive;
            font-weight: 700;
            box-sizing: border-box;
        }

        input:focus {
            outline: none;
            border-color: #ff6b9d;
            box-shadow: 0 0 15px rgba(255, 107, 157, 0.4);
            transform: scale(1.02);
        }

        button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(145deg, #4ecdc4, #00b894);
            color: #ffffff;
            border: 3px solid #2d3436;
            border-radius: 15px;
            font-size: 18px;
            font-family: 'Fredoka One', cursive;
            cursor: pointer;
            margin-top: 15px;
            box-shadow: 0 8px 20px rgba(78, 205, 196, 0.3);
            transition: all 0.3s ease;
            transform: rotate(-1deg);
        }

        button:hover {
            background: linear-gradient(145deg, #ff6b9d, #e84393);
            box-shadow: 0 10px 25px rgba(255, 107, 157, 0.4);
            transform: scale(1.05) rotate(1deg);
        }

        .demo-info {
            margin-top: 25px;
            font-size: 14px;
            padding: 15px;
            background: rgba(255, 230, 109, 0.3);
            border: 2px solid #ffe66d;
            border-radius: 15px;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="lab-equipment">
        <div class="bubble bubble1"></div>
        <div class="bubble bubble2"></div>
        <div class="bubble bubble3"></div>
        <div class="bubble bubble4"></div>
        <div class="bubble bubble5"></div>
        <div class="bubble bubble6"></div>
        <div class="beaker beaker1"></div>
        <div class="beaker beaker2"></div>
        <div class="test-tube test-tube1"></div>
        <div class="test-tube test-tube2"></div>
    </div>

    <div class="container">
        <h1>Mad Scientist Lab!</h1>
        <h2>Chamber Two Entry ⚙️</h2>
        <p>Show your secret lab credentials to enter the bubbling chamber!</p>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Enter Laboratory</button>
        </form>

        <div class="demo-info">
            <strong>Demonstration Credentials:</strong><br>
            Chamber 2: demo2user / password<br>
            Master Access: admin / password
        </div>
    </div>
</body>
</html>