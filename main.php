<?php
// Backend logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $url = '';

    // Firebase API URL based on action
    if ($action === 'register') {
        $url = "https://identitytoolkit.googleapis.com/v1/accounts:signUp?key=AIzaSyAhjNKR9vT6R0oSRiAbeX_hZzR5cIzpqSE";
    } elseif ($action === 'login') {
        $url = "https://identitytoolkit.googleapis.com/v1/accounts:signInWithPassword?key=AIzaSyAhjNKR9vT6R0oSRiAbeX_hZzR5cIzpqSE";
    }

    $data = json_encode([
        "email" => $email,
        "password" => $password,
        "returnSecureToken" => true,
    ]);

    // cURL request to Firebase
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $result = json_decode($response, true);

    // Handle response
    if ($httpCode === 200) {
        if ($action === 'register') {
            $message = "Registration successful! Welcome, " . htmlspecialchars($email);
        } elseif ($action === 'login') {
            $message = "Login successful! Welcome back, " . htmlspecialchars($email);
        }
    } else {
        $message = "Error: " . ($result['error']['message'] ?? 'An unknown error occurred.');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firebase Auth</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background: #ffffff20;
            border-radius: 15px;
            padding: 20px 40px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #fff;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #6a11cb;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            color: #fff;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background: #2575fc;
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            background: #ffffff40;
            border-radius: 5px;
            color: #fff;
        }
        .divider {
            margin: 20px 0;
            color: #aaa;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Firebase Authentication</h2>
        <!-- Registration Form -->
        <form method="POST">
            <input type="hidden" name="action" value="register">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>

        <div class="divider">OR</div>

        <!-- Login Form -->
        <form method="POST">
            <input type="hidden" name="action" value="login">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <?php if (!empty($message)): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
