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
    <title>SMC Laboratory - Access Portal</title>
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
            max-width: 500px;
            width: 100%;
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
            font-size: 3em;
            font-weight: 600;
            margin-bottom: 10px;
            color: #daa520;
            text-shadow:
                2px 2px 4px rgba(139, 69, 19, 0.8),
                0 0 20px rgba(218, 165, 32, 0.5);
            letter-spacing: 2px;
        }

        h2 {
            font-family: 'Cinzel', serif;
            color: #cd853f;
            margin-bottom: 30px;
            font-size: 1.8em;
            font-weight: 400;
            letter-spacing: 1px;
        }

        p {
            font-size: 1.2em;
            margin-bottom: 15px;
            color: #f4e4bc;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            line-height: 1.6;
        }

        .error {
            color: #ff6b6b;
            margin: 15px 0;
            padding: 15px;
            background: rgba(139, 69, 19, 0.2);
            border: 2px solid #8b4513;
            border-radius: 8px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: 2px solid #8b4513;
            border-radius: 8px;
            background: rgba(244, 228, 188, 0.9);
            color: #3e2723;
            font-size: 16px;
            font-family: 'Crimson Text', serif;
            box-sizing: border-box;
        }

        input:focus {
            outline: none;
            border-color: #daa520;
            box-shadow: 0 0 10px rgba(218, 165, 32, 0.3);
        }

        button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(145deg, #cd853f, #b8860b);
            color: #f4e4bc;
            border: 2px solid #8b4513;
            border-radius: 8px;
            font-size: 18px;
            font-family: 'Cinzel', serif;
            font-weight: 600;
            cursor: pointer;
            margin-top: 15px;
            box-shadow: 0 4px 15px rgba(139, 69, 19, 0.3);
            transition: all 0.3s ease;
        }

        button:hover {
            background: linear-gradient(145deg, #daa520, #cd853f);
            box-shadow: 0 6px 20px rgba(218, 165, 32, 0.4);
            transform: translateY(-2px);
        }

        .demo-info {
            margin-top: 25px;
            font-size: 14px;
            opacity: 0.8;
            padding: 15px;
            background: rgba(139, 69, 19, 0.1);
            border: 1px solid #8b4513;
            border-radius: 8px;
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
        <h1>SMC Laboratory</h1>
        <h2>Chamber Four Access Portal</h2>
        <p>Please present your credentials to access the demonstration chamber</p>

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
            Chamber 4: demo4user / password<br>
            Master Access: admin / password
        </div>
    </div>
</body>
</html>