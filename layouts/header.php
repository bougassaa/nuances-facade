<?php
session_start();
$title = $title ?? 'Nuances Façade';
$description = $description ?? "Spécialiste façadier dans le Vaucluse, la Drôme, le Gard et l'Ardèche. Devis gratuits pour enduits, joints de pierre, nettoyage de façades et toitures.";
$keywords = $keywords ?? "façade vaucluse, façade drome, façade gard, façade ardèche, enduit façade, façadier vaucluse, facade, ravalement de facade";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="<?= $description ?>">
    <meta name="keywords" content="<?= $keywords ?>">
    <meta name="language" content="fr-FR">

    <!-- Facebook Meta Tags -->
    <meta property="og:url" content="https://www.nuances-facade.fr/">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Nuances Façade - Ravalement de façade">
    <meta property="og:description" content="Spécialiste façadier dans le Vaucluse, la Drôme, le Gard et l'Ardèche. Devis gratuits pour enduits, joints de pierre, nettoyage de façades et toitures.">
    <meta property="og:image" content="https://www.nuances-facade.fr/images/logo/nf-logo-big.png">

    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta property="twitter:domain" content="nuances-facade.fr">
    <meta property="twitter:url" content="https://www.nuances-facade.fr/">
    <meta name="twitter:title" content="Nuances Façade - Ravalement de façade">
    <meta name="twitter:description" content="Spécialiste façadier dans le Vaucluse, la Drôme, le Gard et l'Ardèche. Devis gratuits pour enduits, joints de pierre, nettoyage de façades et toitures.">
    <meta name="twitter:image" content="https://www.nuances-facade.fr/images/logo/nf-logo-big.png">

    <link rel="shortcut icon" href="/images/logo/nf-logo-square-48.png" type="image/png">
    <link rel="apple-touch-icon" href="/images/logo/nf-logo-square-144.png" />
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="canonical" href="https://www.nuances-facade.fr<?= (stripos($_SERVER['REQUEST_URI'], '/') !== 0 ? '/' : '') . $_SERVER['REQUEST_URI'] ?>" />
    <script type="application/ld+json">
        {
            "@context": "http://schema.org",
            "@type": "LocalBusiness",
            "name": "Nuances Façade",
            "url": "https://www.nuances-facade.fr/",
            "image": "https://www.nuances-facade.fr/images/logo/nf-logo-long.png",
            "description": "Entreprise de ravalement de façade et prestations associées (nettoyage, joints de pierre) dans les régions de Drome, Vaucluse, Gard et Ardèche. Devis gratuit.",
            "address": {
                "@type": "PostalAddress",
                "addressLocality": "Valréas",
                "postalCode": "84600",
                "addressCountry": "FR"
            },
            "telephone": "+33 6 51 38 81 81",
            "openingHours": [
                "Mo-Fr 08:00-18:00",
                "Sa 09:00-12:00"
            ]
        }
    </script>
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
            <img src="../images/logo/nf-logo-square-144.png" alt="Nuances Facade Logo">
        </a>
        <button class="bg-transparent btn btn-lg py-0 d-inline d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-menu" aria-label="Ouvrir le menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="d-none d-md-block justify-content-lg-end">
            <ul class="navbar-nav gap-2">
                <li class="nav-item">
                    <a class="nav-link" href="/ravalement-facade">Ravalement de façade</a>
                </li>
                <li class="nav-item dropdown dropdown-hover">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" data-trigger="hover" aria-expanded="false">
                        Nos prestations
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="/prestations#nettoyage-facade">Nettoyage de façade</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="/prestations#nettoyage-toiture">Nettoyage de toiture</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="/prestations#joint-pierre">Joints de pierre</a>
                        </li>
                    </ul>
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
