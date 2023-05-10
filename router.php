<?php

$routes = [
    'ravalement-facade-vaucluse' => 'Vaucluse - 84',
    'ravalement-facade-drome' => 'Drome - 26',
    'ravalement-facade-gard' => 'Gard - 30',
    'ravalement-facade-ardeche' => 'Ardèche - 07',
    'ravalement-facade-orange' => 'Orange',
    'ravalement-facade-bollene' => 'Bollène',
    'ravalement-facade-valreas' => 'Valréas',
    'ravalement-facade-montelimar' => 'Montélimar',
    'ravalement-facade-pierrelatte' => 'Pierrelatte',
    'ravalement-facade-nyons' => 'Nyons',
    'ravalement-facade-lapalud' => 'Lapalud',
    'ravalement-facade-laudun-l-ardoise' => 'Laudun l\'Ardoise',
    'ravalement-facade-carpentras' => 'Carpentras',
    'ravalement-facade-mondragon' => 'Mondragon',
    'ravalement-facade-piolenc' => 'Piolenc',
    'ravalement-facade-pont-saint-esprit' => 'Pont-Saint-Esprit',
    'ravalement-facade-mornas' => 'Mornas',
    'ravalement-facade-grignan' => 'Grignan',
    'ravalement-facade-visan' => 'Visan',
    'ravalement-facade-camaret-sur-aigues' => 'Camaret-sur-Aigues',
    'ravalement-facade-tulette' => 'Tulette',
    'ravalement-facade-vaison-la-romaine' => 'Vaison-la-Romaine',
    'ravalement-facade-suze-la-rousse' => 'Suze-la-Rousse',
];

if(!isset($routes[$_REQUEST['route']])) {
    header('Location: /');
    return;
}
$city = $routes[$_REQUEST['route']];
$title = "Ravalement de façade $city";

include_once 'layouts/header.php';
?>
<div class="container container-min-height navbar-offset py-3">
    <h1>Ravalement de façade <?= $city ?></h1>
    <p>
        Chez Nuances Façade, nous sommes spécialisés dans le ravalement de façade et les prestations qui y sont liées pour les habitants de <?= $city ?>.
        Nous sommes à votre disposition pour rénover et embellir votre façade en utilisant les techniques les plus modernes et les matériaux les plus adaptés.
        Que vous soyez un particulier ou un professionnel, nous pouvons également effectuer des travaux de nettoyage de façade, de nettoyage de toiture et de joints
        de pierre pour vous offrir une maison ou un bâtiment impeccable à <?= $city ?>.
    </p>
    <p>
        Contactez-nous dès aujourd'hui pour obtenir un devis gratuit et bénéficier de notre expertise et de notre savoir-faire pour tous vos projets de rénovation de façade à <?= $city ?>.
    </p>

    <div class="alert alert-success" role="alert">
        <h4 class="alert-heading">😁 Visitez notre site !</h4>
        <div>Commencez par là pour visiter notre site : <a href="/" type="button" class="btn btn-light">C'est parti</a></div>
    </div>

    <div>
        <img src="/images/facades/home-3.jpeg" class="d-block w-100 h-100 object-fit-cover rounded-4" alt="Facade">
    </div>

    <div class="mt-3">
        <p>Pages liées :</p>
        <ul class="nav flex-column">
            <?php foreach ($routes as $key => $c): ?>
                <li class="nav-item">
                    <a class="nav-link" href="/prestation/<?= $key ?>">Ravalement de facade <?= $c ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<?php include_once 'layouts/footer.php'; ?>
