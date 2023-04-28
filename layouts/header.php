<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Nuances Façade' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark fixed-top <?= isset($isHome) ? 'home' : 'bg-dark' ?>">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="images/nf-logo-short.png" alt="Nuances Facade Logo">
        </a>
        <button class="bg-transparent btn btn-lg py-0 d-inline d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="d-none d-md-block justify-content-lg-end">
            <ul class="navbar-nav gap-2">
                <li class="nav-item">
                    <a class="nav-link" href="#">Réalisations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">A propos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contact</a>
                </li>
            </ul>
        </div>
    </div>
</nav>