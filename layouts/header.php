<?php
session_start();
$title = $title ?? 'Nuances Façade';
$description = $description ?? "Spécialiste de l'enduit de façade dans le Vaucluse, la Drôme, le Gard et l'Ardèche. Devis gratuits pour enduits, joints de pierre, nettoyage de façades et toitures.";
$keywords = $keywords ?? "façade vaucluse, façade drome, façade gard, façade ardèche, enduit façade, façadier vaucluse, facade, ravalement de facade";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= $description ?>">
    <meta name="keywords" content="<?= $keywords ?>">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="notification-container">
    <?php if (isset($_SESSION['message_sent'])): ?>
        <?php unset($_SESSION['message_sent']); ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Votre message a bien été envoyé ✅
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
</div>

<nav class="navbar navbar-expand-md navbar-dark fixed-top <?= isset($isHome) ? 'home' : 'bg-dark' ?>">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img src="../images/logo/nf-logo-short.png" alt="Nuances Facade Logo">
        </a>
        <button class="bg-transparent btn btn-lg py-0 d-inline d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="d-none d-md-block justify-content-lg-end">
            <ul class="navbar-nav gap-2">
                <li class="nav-item">
                    <a class="nav-link" href="/ravalement-facade">Ravalement de façade</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/realisations">Réalisations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/contact">Contact</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
