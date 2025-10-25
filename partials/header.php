<?php
$firstName = current(explode(" ", $userInfo->name));
/** @var string $searchTerm = */

$searchTerm = $searchTerm ?? 'Pesquisar...';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title></title>
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1"/>
    <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>
<header>
    <div class="container">
        <div class="logo">
            <a href="/"><img src="assets/images/devsbook_logo.png" /></a>
        </div>
        <div class="head-side">
            <div class="head-side-left">
                <div class="search-area">
                    <form method="GET" action="/search.php">
                        <label for="s" Aba de Pesquisa>
                                <input type="search" placeholder="<?= $searchTerm ?>" name="search" />
                        </label>
                    </form>
                </div>
            </div>
            <div class="head-side-right">
                <a href="<?=$base;?>/perfil.php" class="user-area">
                    <div class="user-area-text"><?=$firstName?></div>
                    <div class="user-area-icon">
                        <img src="media/avatars/<?=$userInfo->avatar?>" />
                    </div>
                </a>
                <a href="/logout.php" class="user-logout">
                    <img src="assets/images/power_white.png" />
                </a>
            </div>
        </div>
    </div>
</header>
<section class="container main">
