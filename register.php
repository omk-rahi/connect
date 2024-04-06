<?php

require(__DIR__ . './utils.php');
require(__DIR__ . './database.php');

session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: ./index.php');
    exit;
}


$errors = [];
$user_info = [];

if (isset($_POST['register'])) {

    $errors = validate_user_info($connection);

    $user_info['name'] = clean_data($_POST['name']);
    $user_info['email'] = clean_data($_POST['email']);
    $user_info['password'] = clean_data($_POST['password']);
    $user_info['confirm_password'] = clean_data($_POST['confirm_password']);


    if (count($errors) === 0) {

        unset($user_info['confirm_password']);

        $user_info['profile_pic'] = 'default-profile.png';
        $result  = $connection->save('users', $user_info);

        if ($result) {
            $_SESSION['user_id'] = $result;
            header('Location: ./index.php');
        }
    }
}


function validate_user_info($connection)
{
    $errors = [];

    if (empty($_POST['name'])) {
        $errors['name_error'] = 'Please enter your name';
    } else if (strlen($_POST['name']) < 4) {
        $errors['name_error'] = 'Please enter a valid name';
    }

    if (empty($_POST['email'])) {
        $errors['email_error'] = 'Please enter your email';
    } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email_error'] = 'Please enter valid email';
    } else {
        $result = $connection->find("users", ['email' => $_POST['email']]);

        if (count($result) !== 0) $errors['email_error'] = 'Email already exits. Please login';
    }

    if (empty($_POST['password'])) {
        $errors['password_error'] = 'Please enter your password';
    } else if (strlen($_POST['password']) < 8) {
        $errors['password_error'] = 'Password must be 8 characters long';
    }

    if (empty($_POST['confirm_password'])) {
        $errors['confirm_password_error'] = 'Please confirm your password';
    } else if ($_POST['password'] !== $_POST['confirm_password']) {
        $errors['confirm_password_error'] = 'Confirm password does not match';
    }

    return $errors;
}

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
    <link rel='stylesheet' href='./styles/style.css?<?= filemtime($_SERVER["DOCUMENT_ROOT"] . "./styles/style.css"); ?>' />
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

    <form action="./register.php" class="auth-form" method="post">
        <img src="./assets/images/logo-small.png" alt="Connect brand logo" class="form__logo">

        <div class="form__group">
            <span class="form--input-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
            </span>

            <input type="text" name="name" id="name" placeholder="Enter your name" value="<?= $user_info['name'] ?? '' ?>">
        </div>
        <p class="error"><?= $errors['name_error'] ?? "" ?></p>

        <div class="form__group">
            <span class="form--input-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                </svg>
            </span>

            <input type="email" name="email" id="email" placeholder="Enter your email" value="<?= $user_info['email'] ?? '' ?>">
        </div>
        <p class="error"><?= $errors['email_error'] ?? "" ?></p>

        <div class="form__group">
            <span class="form--input-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                </svg>
            </span>

            <input type="password" name="password" id="password" placeholder="Enter your password" value="<?= $user_info['password'] ?? '' ?>">
        </div>
        <p class="error"><?= $errors['password_error'] ?? "" ?></p>

        <div class="form__group">
            <span class="form--input-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                </svg>
            </span>

            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm your password" value="<?= $user_info['confirm_password'] ?? '' ?>">
        </div>

        <p class="error"><?= $errors['confirm_password_error'] ?? "" ?></p>

        <input type="submit" value="Join now" class="btn btn--primary btn--full" name="register">

        <span class="form__link">
            Already have an account ? <a href="./login.php" class="btn--regular">Login here</a>
        </span>
    </form>

</body>

</html>