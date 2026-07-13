<?php
$title = 'Réalisations | Un petit aperçu de notre travail';
include_once "layouts/header.php"
?>
<div class="container container-min-height navbar-offset py-3">
    <h1>Realisations</h1>

    <div class="row g-4 mt-2 align-items-center justify-content-center">
        <div class="col-md">
            <?= renderImage('/images/facades/vedene-pignon-avant.jpeg', 'Pignon à Vedène avant ravalement de façade', ['class' => 'img-fluid rounded-4']) ?>
        </div>
        <div class="col-auto">
            <img class="arrow-image" src="/images/logo/arrow-black.png" width="80" height="80" alt="flèche avant-après">
        </div>
        <div class="col-md">
            <?= renderImage('/images/facades/vedene-pignon-apres.jpeg', 'Pignon à Vedène après ravalement de façade', ['class' => 'img-fluid rounded-4']) ?>
        </div>
    </div>
    <hr>
    <div class="row g-4 align-items-center justify-content-center">
        <div class="col-md">
            <?= renderImage('/images/facades/garage-avant.jpeg', 'Garage avant ravalement', ['class' => 'img-fluid rounded-4']) ?>
        </div>
        <div class="col-auto">
            <img class="arrow-image" src="/images/logo/arrow-black.png" width="80" height="80" alt="flèche avant-après">
        </div>
        <div class="col-md">
            <?= renderImage('/images/facades/garage-apres.jpeg', 'Garage après ravalement', ['class' => 'img-fluid rounded-4']) ?>
        </div>
    </div>
    <hr>
    <div class="row g-4 align-items-center justify-content-center">
        <div class="col-md">
            <?= renderImage('/images/facades/vedene-nord-avant.jpeg', 'Façade nord à Vedène avant ravalement', ['class' => 'img-fluid rounded-4']) ?>
        </div>
        <div class="col-auto">
            <img class="arrow-image" src="/images/logo/arrow-black.png" width="80" height="80" alt="flèche avant-après">
        </div>
        <div class="col-md">
            <?= renderImage('/images/facades/vedene-nord-apres.jpeg', 'Façade nord à Vedène après ravalement', ['class' => 'img-fluid rounded-4']) ?>
        </div>
    </div>
    <hr>
    <div class="row g-4 align-items-center justify-content-center">
        <div class="col-md">
            <?= renderImage('/images/facades/terrasse-avant.jpeg', 'Terrasse avant rénovation', ['class' => 'img-fluid rounded-4']) ?>
        </div>
        <div class="col-auto">
            <img class="arrow-image" src="/images/logo/arrow-black.png" width="80" height="80" alt="flèche avant-après">
        </div>
        <div class="col-md">
            <?= renderImage('/images/facades/terrasse-apres.jpeg', 'Terrasse après rénovation', ['class' => 'img-fluid rounded-4']) ?>
        </div>
    </div>
    <hr>
    <div class="row g-4 align-items-center justify-content-center">
        <div class="col-md">
            <?= renderImage('/images/facades/vedene-est-avant.jpeg', 'Façade est à Vedène avant ravalement', ['class' => 'img-fluid rounded-4']) ?>
        </div>
        <div class="col-auto">
            <img class="arrow-image" src="/images/logo/arrow-black.png" width="80" height="80" alt="flèche avant-après">
        </div>
        <div class="col-md">
            <?= renderImage('/images/facades/vedene-est-apres.jpeg', 'Façade est à Vedène après ravalement', ['class' => 'img-fluid rounded-4']) ?>
        </div>
    </div>
    <hr>
    <div class="row g-4 align-items-center justify-content-center">
        <div class="col-md">
            <?= renderImage('/images/facades/vedene-sur-avant.jpeg', 'Façade sud à Vedène avant ravalement', ['class' => 'img-fluid rounded-4']) ?>
        </div>
        <div class="col-auto">
            <img class="arrow-image" src="/images/logo/arrow-black.png" width="80" height="80" alt="flèche avant-après">
        </div>
        <div class="col-md">
            <?= renderImage('/images/facades/vedene-sur-apres.jpeg', 'Façade sud à Vedène après ravalement', ['class' => 'img-fluid rounded-4']) ?>
        </div>
    </div>
    <hr>
    <div class="row g-4 align-items-center justify-content-center">
        <div class="col-md">
            <?= renderImage('/images/facades/garcasse-avant.jpeg', 'Maison à Garcasse avant ravalement', ['class' => 'img-fluid rounded-4']) ?>
        </div>
        <div class="col-auto">
            <img class="arrow-image" src="/images/logo/arrow-black.png" width="80" height="80" alt="flèche avant-après">
        </div>
        <div class="col-md">
            <?= renderImage('/images/facades/garcasse-apres.jpg', 'Maison à Garcasse après ravalement', ['class' => 'img-fluid rounded-4']) ?>
        </div>
    </div>
    <hr>
    <div class="row g-4 align-items-center justify-content-center">
        <div class="col-md">
            <?= renderImage('/images/facades/les-angles-1-avant.jpeg', 'Maison aux Angles avant ravalement', ['class' => 'img-fluid rounded-4']) ?>
        </div>
        <div class="col-auto">
            <img class="arrow-image" src="/images/logo/arrow-black.png" width="80" height="80" alt="flèche avant-après">
        </div>
        <div class="col-md">
            <?= renderImage('/images/facades/les-angles-1-apres.JPG', 'Maison aux Angles après ravalement', ['class' => 'img-fluid rounded-4']) ?>
        </div>
    </div>
    <h3 class="mt-4">Nettoyage d'une façade (avant - après) :</h3>
    <div class="row g-4 align-items-center justify-content-center">
        <div class="col-md">
            <?= renderImage('/images/facades/nettoyage-avant.jpeg', 'Façade avant nettoyage', ['class' => 'img-fluid rounded-4']) ?>
        </div>
        <div class="col-auto">
            <img class="arrow-image" src="/images/logo/arrow-black.png" width="80" height="80" alt="flèche avant-après">
        </div>
        <div class="col-md">
            <?= renderImage('/images/facades/nettoyage-apres.jpeg', 'Façade après nettoyage', ['class' => 'img-fluid rounded-4']) ?>
        </div>
    </div>
</div>
<?php include_once "layouts/footer.php" ?>
