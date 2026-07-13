<div id="realisations-carousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#realisations-carousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#realisations-carousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#realisations-carousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        <button type="button" data-bs-target="#realisations-carousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <?= renderImage('/images/facades/home-2.jpeg', 'Ravalement de façade — maison rénovée', ['class' => 'd-block w-100 h-100 object-fit-cover', 'lazy' => false]) ?>
        </div>
        <div class="carousel-item">
            <?= renderImage('/images/facades/home-1.jpg', 'Façade rénovée par Nuances Façade', ['class' => 'd-block w-100 h-100 object-fit-cover']) ?>
        </div>
        <div class="carousel-item">
            <?= renderImage('/images/facades/home-3.jpeg', 'Ravalement de façade — réalisation', ['class' => 'd-none d-md-block w-100 h-100 object-fit-cover']) ?>
            <?= renderImage('/images/facades/home-5.jpg', 'Ravalement de façade — réalisation (mobile)', ['class' => 'd-block d-md-none w-100 h-100 object-fit-cover']) ?>
        </div>
        <div class="carousel-item">
            <?= renderImage('/images/facades/home-4.jpeg', 'Façade neuve réalisée par Nuances Façade', ['class' => 'd-block w-100 h-100 object-fit-cover']) ?>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#realisations-carousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Précédent</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#realisations-carousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Suivant</span>
    </button>
</div>
