<?php
$title = 'Nuances Façade - Ravalement de façade';
$isHome = true;
include_once "layouts/header.php"
?>

<div class="p-3 align-items-end align-items-md-center justify-content-center" id="landing-home">
    <div class="text-light">
        <h1>Nuances Façade</h1>
        <p>
            Embellissez votre maison avec notre entreprise de <a class="link-light" href="/ravalement-facade">ravalement de façade</a> de qualité ! Nous sommes fiers de desservir les régions Drome, Vaucluse, Gard et Ardèche, et nous offrons une expertise dans la rénovation et le neuf, ainsi que dans le nettoyage de façade ancienne.
        </p>
        <p>
            Nous sommes également heureux de proposer des devis et des déplacements gratuits pour tous nos clients. Faites confiance à notre expérience pour donner à votre maison une apparence neuve et fraîche !
        </p>
        <p>
            <a href="/contact" class="btn btn-secondary">Faire un devis gratuitement</a>
        </p>
    </div>
</div>

<div class="bg-dark text-light">
    <div class="container pt-5 pb-3">
        <h2>Quelques réalisations...</h2>
    </div>

    <?php include_once "layouts/carousel.php" ?>

    <div class="container py-5">
        <p>
            Nous sommes fiers de vous montrer quelques-unes de nos réalisations les plus récentes en matière de ravalement de façade. Mais ce n'est qu'un petit aperçu de tout ce que nous pouvons faire pour embellir votre maison. Si vous souhaitez voir plus de nos travaux, cliquez sur le bouton ci-dessous pour découvrir notre galerie de projets. Vous y trouverez des exemples de notre travail en matière de rénovation et de neuf, de nettoyage de façade ancienne au karcher, de toitures et de joints de pierre. Nous espérons que cela vous inspirera pour votre propre projet de ravalement de façade.
        </p>
        <div>
            <a href="/realisations" class="btn btn-outline-light btn-sm">Voir d'autres réalisations...</a>
        </div>
    </div>
</div>

<div class="container pt-5 pb-4">
    <h2 class="mb-4">Nos prestations</h2>

    <div class="row">
        <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-work-preview" style="background-image: url('images/prestations/ravalement-facade.jpeg')"></div>
                <div class="card-body">
                    <h5 class="card-title text-truncate">Ravalement de façade</h5>
                    <div class="card-text text-secondary">
                        Rénovez et donnez un aspect neuf à votre maison ou clôture grâce à notre service de ravalement de façade professionnel. Sur du neuf ou rénovation.
                        <a href="/ravalement-facade" class="stretched-link">Voir plus...</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-work-preview" style="background-image: url('images/prestations/nettoyage-facade.jpeg')"></div>
                <div class="card-body">
                    <h5 class="card-title text-truncate">Nettoyage de façade</h5>
                    <div class="card-text text-secondary">
                        Redonnez à votre façade son éclat d'origine. Nous éliminons la saleté, la moisissure et les taches pour une apparence fraîche et propre.
                        <a href="/prestations#nettoyage-facade" class="stretched-link">Voir plus...</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-work-preview" style="background-image: url('images/prestations/nettoyage-toiture.jpeg')"></div>
                <div class="card-body">
                    <h5 class="card-title text-truncate">Nettoyage de toitures</h5>
                    <div class="card-text text-secondary">
                        Protégez votre toit et préservez sa durée de vie. Nous éliminons les champignons, les lichens et les mousses pour une meilleure résistance aux intempéries.
                        <a href="/prestations#nettoyage-toiture" class="stretched-link">Voir plus...</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-work-preview" style="background-image: url('images/prestations/joint-pierre.jpeg')"></div>
                <div class="card-body">
                    <h5 class="card-title text-truncate">Joints de pierre</h5>
                    <div class="card-text text-secondary">
                        Améliorez la stabilité et l'aspect de vos murs en pierre. Nous remplaçons les joints endommagés ou manquants pour un aspect neuf et une meilleure sécurité.
                        <a href="/prestations#joint-pierre" class="stretched-link">Voir plus...</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-dark text-light">
    <div class="container pt-5 pb-4">
        <div class="row align-items-center">
            <div class="col-md-6 text-center mb-4">
                <h2>Nous contacter</h2>
                <div class="mt-4">
                    Par téléphone :
                    <div class="mt-1">
                        <a href="tel:0651388181" class="btn btn-outline-light">06 51 38 81 81</a>
                    </div>
                </div>
                <hr class="my-4">
                <div>
                    Par e-mail :
                    <div class="mt-1">
                        <a href="mailto:contact@nuances-facade.fr" class="btn btn-outline-light">contact@nuances-facade.fr</a>
                    </div>
                </div>
                <hr class="my-4">
                <div>
                    Ou depuis notre page de contact :
                    <div class="mt-1">
                        <a href="/contact" class="btn btn-outline-light">En cliquant ici</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <img class="localisation-preview" src="images/landing-page/departements.png" alt="Localisation">
            </div>
        </div>
    </div>
</div>

<?php include_once "layouts/footer.php" ?>
