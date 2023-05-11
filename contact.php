<?php $title = 'Contact - Devis gratuit'; include_once "layouts/header.php" ?>
<div class="container container-min-height navbar-offset">
    <h1 class="py-3">Page de contact</h1>
    <div class="row">
        <div class="col-md order-1 order-md-0">
            <form action="/send-contact.php">
                <div class="mb-3">
                    <label for="name" class="form-label">Votre nom</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="M Dupont" required>
                </div>
                <div class="mb-3">
                    <label for="contact" class="form-label">Adresse e-mail ou numéro de téléphone</label>
                    <input type="text" class="form-control" id="contact" name="contact" placeholder="dupont@gmail.com ou 0606060606" required>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Votre message</label>
                    <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                </div>
                <div class="mb-3 d-grid d-md-block">
                    <button type="submit" class="btn btn-primary">Contacter Nuances Façade</button>
                </div>
            </form>
        </div>
        <div class="col-md order-0 order-md-1">
            <div class="alert alert-primary">
                Nous serions ravis de vous aider à réaliser votre projet. Pour obtenir un devis gratuit, il vous suffit de remplir le formulaire de contact
                ci-joint en nous fournissant quelques détails sur votre projet. Nous vous répondrons dans les plus brefs délais pour vous fournir un devis détaillé.
                Si vous avez des questions ou si vous souhaitez en savoir plus sur nos services, n'hésitez pas à nous contacter.
                Nous sommes là pour vous aider à réaliser vos projets dans les départements Drome, Vaucluse, Gard et Ardèche.
            </div>
            <img src="/images/vectors/home-repairing.svg" class="img-fluid d-none d-md-block" alt="home repairing">
        </div>
    </div>
</div>
<?php include_once "layouts/footer.php" ?>
