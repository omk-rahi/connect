<?php

require(__DIR__ . '/database.php');

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ./login.php');
    exit;
}


$user_id = $_SESSION['user_id'];

$user = $connection->findById('users', $user_id);


session_abort();


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connect</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./styles/style.css">
</head>

<body>

    <nav class="navbar">
        <div class="navbar__brand">
            <img src="./assets/images/logo.png" alt="Connect brand logo" class="navbar__brand-image">
        </div>

        <div class="navbar__menu">
            <a href="#" class="btn btn--regular">Login</a>
            <a href="#" class="btn btn--primary">Register</a>
        </div>

    </nav>

    <h1>
        <?= 'Hello, ' . $user['name'] ?>
    </h1>

</body>

</html>