<?php
require_once 'meta.php';

$page = basename($_SERVER['PHP_SELF'], ".php"); 
$data = $meta[$page] ?? $meta['index'];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="96x96" href="assets/favicon/favicon-96x96.png">
    <link rel="icon" type="image/svg+xml" href="assets/favicon/favicon.svg">
    <link rel="shortcut icon" href="assets/favicon/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-touch-icon.png">
    <link rel="manifest" href="assets/favicon/site.webmanifest">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;900&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="Style.css">

    <!-- SEO -->
    <title><?= htmlspecialchars($data['title']) ?></title>
    <meta name="description" content="<?= htmlspecialchars($data['description']) ?>">
    <meta property="og:title" content="<?= htmlspecialchars($data['og_title']) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($data['og_description']) ?>">
    <meta property="og:image" content="https://yoursite.com/assets/Picture/logo1.png">
    <meta property="og:url" content="<?= htmlspecialchars($data['og_url']) ?>">
    <meta property="og:type" content="website">

    <!-- Telegram -->
    <meta property="og:url" content="<?= htmlspecialchars($data['og_bot']) ?>">
    <meta property="og:url" content="<?= htmlspecialchars($data['og_channel']) ?>">
    <meta name="compliance" content="152-fz-exempt">

    <!-- Twitter -->
    <meta name="twitter:card" content="<?= htmlspecialchars($data['twitter_card']) ?>">
</head>
<body class="fade-in">
<section class="welcome">
    <div class="logo">
         <a href="index.php">
            <img src="assets/Picture/logo.svg" alt="IT Bureau LADY GAIDOUK" class="clickable" height="40">
        </a>
    </div>
