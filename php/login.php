<?php
// Start the session
session_start();

// Include database connection
include('database.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['UserName'];
    $password = $_POST['Password'];

    // Fetch the user from the database
    $sql = "SELECT * FROM Users WHERE UserName = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['PasswordHash'])) {
        // Set session variables
        $_SESSION['UserID'] = $user['UserID'];
        $_SESSION['UserName'] = $user['UserName'];

        // Redirect to user home page
        header("Location: user_home.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
       /* General Styles */
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-image: url('../images/hero-background.webp'); /* Replace with your image path */
    background-size: cover;
    background-position: center;
    background-attachment: fixed; /* Optional */
    color: #d4d4d4; /* Light Gray */
    display: flex;
    flex-direction: column;
    align-items: center;
    min-height: 100vh;
}

/* Header */
header {
    width: 100%;
    background-color: #a80000; /* Bright Red */
    text-align: center;
    padding: 20px 0;
}

header h1 {
    color: white;
    margin: 0;
    font-size: 2rem;
}

/* Create an Account Title (h2) */
h2 {
    text-align: center;
    font-size: 24px;
    color: #a80000; /* Bright Red */
    margin-bottom: 20px;
}

/* Navbar */
.navbar {
    width: 100%;
    background-color: #1f1f1f; /* Dark Gray */
    padding: 0.5em 0;
    display: flex;
    justify-content: center;
}

.navbar-menu a {
    color: #d4d4d4; /* Light Gray */
    margin: 0 1em;
    text-decoration: none;
    font-weight: bold;
}

.navbar-menu a:hover {
    color: #a80000; /* Bright Red */
}

/* Form Container */
.form-container {
    width: 100%;
    max-width: 400px;
    background-color: rgba(23, 23, 23, 0.8); /* Sheer dark gray with transparency */
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    margin-top: 30px;
}

/* Form Styles */
form {
    display: flex;
    flex-direction: column;
}

label {
    font-size: 14px;
    margin-bottom: 5px;
    color: #d4d4d4; /* Light Gray */
}

input {
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #707070; /* Medium Gray */
    border-radius: 4px;
    font-size: 14px;
    background-color: #707070; /* Medium Gray */
    color: #d4d4d4; /* Light Gray */
}

input:focus {
    border-color: #a80000; /* Bright Red */
    outline: none;
}

button {
    padding: 12px;
    background-color: #a80000; /* Bright Red */
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
}

button:hover {
    background-color: #731010; /* Dark Red */
}

/* Links */
p {
    text-align: center;
    font-size: 14px;
}

p a {
    color: #a80000; /* Bright Red */
    text-decoration: none;
}

p a:hover {
    text-decoration: underline;
}

/* Error or Success Message */
.alert {
    background-color: #731010; /* Dark Red */
    color: white;
    padding: 10px;
    border-radius: 4px;
    margin-bottom: 20px;
    text-align: center;
}

.success {
    background-color: #707070; /* Medium Gray */
    color: white;
}

/* Footer Styles */
.main-footer {
    background-color: #1f1f1f; /* Dark Gray */
    color: white;
    text-align: center;
    padding: 1em 0;
    position: absolute;
    bottom: 0;
    width: 100%;
}

    </style>
</head>
<body>
<header>
    <h1>Travel Manager</h1>
</header>
<nav class="navbar">
    <div class="container">
        <div class="navbar-menu">
            <a href="../index.php">Home</a>
            <a href="destinations.php">Destinations</a>
            <a href="transport.php">Transportation</a>
        </div>
    </div>
</nav>
<div class="form-container">
    <h2>Login</h2>
    <?php if (isset($error)): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
        <label for="UserName">UserName</label>
        <input type="text" id="UserName" name="UserName" required>
        
        <label for="Password">Password</label>
        <input type="password" id="Password" name="Password" required>
        
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
</div>
<footer class="main-footer">
    <p>&copy; 2024 Travel Manager. All Rights Reserved.</p>
</footer>
</body>
</html>
