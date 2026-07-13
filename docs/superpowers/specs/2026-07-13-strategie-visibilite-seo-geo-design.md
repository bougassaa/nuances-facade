# Stratégie de visibilité — SEO, GEO & conversion

**Date :** 2026-07-13
**Projet :** nuances-facade.fr (site vitrine PHP — façadier Vaucluse / Drôme / Gard / Ardèche)
**Objectif :** augmenter le trafic qualifié (organique + IA + local) et le taux de conversion en demandes de devis.

---

## 1. Contexte

Site vitrine en **PHP vanilla** hébergé chez OVH (mutualisé), déployé via GitHub Action → FTP.
Base technique saine déjà en place : HTTPS forcé, URL canonique `www`, redirections `.htaccess`, sitemap, schema `LocalBusiness`, tag Google Ads.

Points bloquants identifiés à l'audit :
1. Images très lourdes (24 Mo, deux PNG de 5–6 Mo, aucun WebP/lazy) → performance dégradée.
2. 24 pages « villes » quasi-dupliquées (`routerCities.php`) → risque de *doorway pages*.
3. Aucun contenu éditorial (blog) → ne capte que les recherches de marque.
4. Aucune optimisation GEO (moteurs IA), pas de `FAQPage`/`BreadcrumbList`, avis non exploités en données structurées.
5. Formulaire de contact en GET, sans anti-spam ni délivrabilité soignée.
6. Pas de page « À propos » ni « Tarifs » (E-E-A-T + requêtes prix).

---

## 2. Contraintes & principes

- **PHP vanilla**, aucun framework ni étape de build serveur.
- Déploiement **GitHub Action → FTP OVH inchangé**.
- **Pas de duplication de code** : toute logique répétée (rendu d'image, injection de schema, meta) passe par des helpers dans `layouts/`.
- **Aucune régression** sur l'existant (canonical, `.htaccess`, redirections 301).
- Contenu métier rédigé avec placeholders explicites `[À CONFIRMER : …]`.
- **Hors périmètre :** outils d'analytics (Search Console, GA4) — non gérés dans ce projet.

---

## 3. Découpage en jalons

Cinq jalons livrables indépendamment, du plus rentable-rapide au plus long. Chaque jalon peut être implémenté, testé et déployé seul.

### Jalon 1 — Technique & Performance

**But :** améliorer la vitesse (Core Web Vitals) et l'hygiène SEO de base.

- **Pipeline d'optimisation d'images** — script local `tools/optimize-images.sh` :
  - convertit chaque image en **WebP** et génère une version redimensionnée (largeur max ~1600 px) ;
  - dépend de `cwebp` ou ImageMagick installé en local ;
  - les fichiers WebP sont commités à côté des originaux (aucun traitement serveur) ;
  - cible prioritaire : `images/facades/01-juillet-2023.png` (6 Mo), `01-avril-2023.png` (5,3 Mo), `les-angles-1-apres.JPG` (1,7 Mo).
- **Helper `renderImage()`** dans un nouveau `layouts/helpers.php` :
  - signature : `renderImage($src, $alt, $opts = [])` (options : `class`, `width`, `height`, `lazy`, `sizes`) ;
  - produit un `<picture>` avec source WebP + fallback d'origine, attributs `width`/`height` et `loading="lazy"` (sauf hero de l'accueil, en `eager`) ;
  - remplace progressivement les `<img>` et les `background-image` lourds (cartes prestations, carousel, réalisations).
- **Titres & meta** :
  - raccourcir tous les `<title>` à < 60 caractères, orientés bénéfice + zone ;
  - supprimer la balise `<meta name="keywords">` (obsolète) ;
  - remplacer `og:image` (logo) par une photo avant/après attractive ;
  - homogénéiser les `$description` par page (une description propre et unique chacune).
- **Correctifs divers** :
  - chemins d'images en absolu (`/images/...`) dans `header.php` (logo nav) et `realisations.php` ;
  - `alt` descriptifs (les 4 avatars ont aujourd'hui `alt="avatar clo"` ; réalisations = noms de fichiers) ;
  - `robots.txt` : retirer la première ligne `Disallow:` vide ;
  - corrections orthographiques « Drome » → « Drôme », « facade » → « façade » dans les textes visibles.

**Critères de succès :** poids page d'accueil réduit fortement (objectif < 1,5 Mo images au premier rendu), toutes images avec dimensions + lazy, titres/meta uniques et conformes.

### Jalon 2 — GEO & données structurées

**But :** être citable par les moteurs IA et enrichir la compréhension par les moteurs.

- **`llms.txt`** à la racine : activité, zone d'intervention, liste des prestations, coordonnées, liens clés, en texte brut structuré.
- **`robots.txt`** : autoriser explicitement les crawlers IA (`GPTBot`, `OAI-SearchBot`, `PerplexityBot`, `Google-Extended`, `ClaudeBot`, `Claude-Web`, `cohere-ai`, `Bytespider` selon souhait).
- **Helper d'injection de schema** dans `layouts/helpers.php` + `header.php` :
  - variables optionnelles par page : `$faqItems` (tableau Q/R), `$breadcrumbs` (tableau libellé/URL) ;
  - génère `FAQPage` quand `$faqItems` est défini ;
  - génère `BreadcrumbList` quand `$breadcrumbs` est défini ;
  - enrichit le `LocalBusiness` existant : `areaServed` (départements + villes), `geo` (coordonnées de Valréas — `[À CONFIRMER : lat/lng]`), `sameAs` (Facebook, fiche Google), `priceRange`, `AggregateRating` + `Review` à partir des 4 avis réels de la page d'accueil.
  - *Note :* Google affiche rarement les étoiles d'auto-avis sur un site propriétaire ; ces données restent utiles pour les IA et le knowledge graph.
- **Fil d'Ariane visible** sur les pages internes (composant `layouts/breadcrumb.php`), alimenté par `$breadcrumbs`.
- **Contenu « extractable »** : sur les pages clés et les articles, réponses factuelles courtes en tête (format question → réponse directe en 2–3 phrases) pour favoriser la citation par les IA.

**Critères de succès :** `llms.txt` accessible, bots IA autorisés, schemas valides (test Rich Results / validator schema.org sans erreur) sur accueil + une page ville + un article.

### Jalon 3 — Contenu

**But :** capter les recherches informationnelles et géolocalisées, renforcer l'E-E-A-T.

- **Template villes enrichi** (`routerCities.php`) :
  - remplacer le tableau `slug => nom` par un tableau **structuré par commune** : `nom`, `departement`, `code_departement`, `communes_voisines[]`, `distance_valreas`, `faq[]` (Q/R locale) ;
  - générer une intro et une FAQ variables à partir de ces données → chaque page devient sensiblement unique ;
  - conserver les 24 URLs `/prestation/ravalement-facade-{ville}` existantes ;
  - alimenter `areaServed` et `$breadcrumbs`/`$faqItems` du jalon 2.
- **Page « À propos »** (`/a-propos`, fichier `a-propos.php`) : histoire de l'entreprise, présentation de l'artisan, années d'expérience, SIRET, assurance décennale, RGE — placeholders `[À CONFIRMER]`. Ajout au menu et au footer.
- **Page « Tarifs »** (`/tarifs`, fichier `tarifs.php`) : fourchettes indicatives au m² par prestation + FAQ prix — placeholders. `FAQPage` associé.
- **Blog** :
  - page index `/blog` servie par `blog.php` (cohérent avec le routing `.htaccess` qui mappe `/blog` → `blog.php`) ;
  - articles dans le dossier `blog/`, URL `/blog/{slug}` → `blog/{slug}.php`, réutilisant `header.php`/`footer.php`, avec schema `Article` ;
  - 3 articles guides rédigés avec placeholders : « Prix d'un ravalement au m² en 2026 », « Aides & subventions 2026 », « Quand ravaler sa façade ? (obligations) » ;
  - ajout des URLs au `sitemap.xml` ; maillage interne des articles vers pages prestations/villes.
- **Enrichissement `realisations.php`** : titre + ville + type de prestation + `alt` riches par chantier (contenu géolocalisé).

**Critères de succès :** 24 pages villes non dupliquées (intro + FAQ variables), pages À propos & Tarifs en ligne, blog avec index + 3 articles indexables et liés dans le sitemap.

### Jalon 4 — Conversion

**But :** transformer le trafic en demandes de devis.

- **Bouton d'appel flottant** (mobile, clic-to-call toujours visible) + **lien WhatsApp Business** (`[À CONFIRMER : numéro WhatsApp]`), dans le footer commun.
- **CTA « Devis gratuit » + réassurance** (« Réponse sous 24h », « Devis gratuit », « Assurance décennale ») répétés en haut et bas des pages clés et près du formulaire.
- **Formulaire de contact sécurisé** (`contact.php` + `send-contact.php`) :
  - passage de GET à **POST** ;
  - champ **honeypot** anti-spam (masqué) + éventuel piège temporel ;
  - **validation** e-mail / téléphone côté serveur ;
  - en-têtes `From` / `Reply-To` propres pour améliorer la délivrabilité de `mail()` ;
  - conservation du feedback de succès existant (session `message_sent`).
- **Galerie avant/après interactive** sur `/realisations` : curseur glissant (JS vanilla, sans dépendance), avec titres / villes / `alt` enrichis.

**Critères de succès :** formulaire en POST fonctionnel avec anti-spam, bouton d'appel + WhatsApp visibles sur mobile, CTA présents sur les pages clés, galerie avant/après fonctionnelle.

### Jalon 5 — Hors-site (checklist pour le client, hors analytics)

**But :** bâtir l'autorité locale et le référencement local. Actions manuelles côté client, formalisées en checklist dans un document dédié (`docs/checklist-hors-site.md`).

- **Fiche Google Business** : revendiquer/optimiser (catégorie « Entreprise de ravalement », photos de chantiers, zone d'intervention, description, horaires).
- **Process d'avis** : réutiliser le lien existant `g.page/r/CapAaNx-0yS-EB0/review` (QR code sur devis/factures, relance clients) pour un flux régulier d'avis récents.
- **Annuaires & citations** (NAP strictement cohérent : nom, adresse, téléphone identiques partout) : PagesJaunes, Chambre des Métiers, annuaires BTP/artisans.
- **Backlinks locaux** : mairies, partenaires (maçons, couvreurs, architectes), presse/associatif local.

**Critères de succès :** checklist livrée et actionnable ; suivi laissé au client.

---

## 4. Nouveaux fichiers / fichiers modifiés (vue d'ensemble)

**Nouveaux :**
- `tools/optimize-images.sh`
- `layouts/helpers.php` (renderImage, injection schema)
- `layouts/breadcrumb.php`
- `llms.txt`
- `a-propos.php`, `tarifs.php`
- `blog.php` (index) + `blog/*.php` (articles)
- `docs/checklist-hors-site.md`
- versions `.webp` des images

**Modifiés :**
- `layouts/header.php` (meta, schema dynamique, keywords supprimé, chemin logo)
- `layouts/footer.php` (liens À propos/Tarifs/Blog, bouton flottant, WhatsApp)
- `index.php`, `prestations.php`, `ravalement-facade.php`, `les-finitions-de-facade.php` (renderImage, CTA)
- `routerCities.php` (données structurées par ville, FAQ, breadcrumb)
- `realisations.php` (galerie avant/après, alt enrichis)
- `contact.php`, `send-contact.php` (POST, honeypot, validation, headers)
- `robots.txt` (nettoyage + bots IA)
- `sitemap.xml` (nouvelles pages, dates lastmod)

---

## 5. Informations à fournir par le client (placeholders à lever)

- Coordonnées géo de Valréas (lat/lng) pour le schema.
- SIRET, assurance décennale, certifications (RGE ?), année de création, présentation de l'artisan.
- Fourchettes de prix indicatives au m² par prestation.
- Numéro WhatsApp Business (si utilisé).
- Villes où des chantiers réels ont été réalisés (pour prioriser le contenu local).
- URL exacte de la fiche Google Business et du profil Facebook (pour `sameAs`).

---

## 6. Risques & points d'attention

- **Pages villes** : même enrichi par données, le template reste généré ; surveiller que la variabilité est suffisante (intro + FAQ + communes voisines réellement différentes). Idéal : y ajouter à terme des chantiers locaux réels.
- **`mail()` OVH** : délivrabilité limitée ; les en-têtes propres aident mais un service SMTP serait plus fiable (hors périmètre pour l'instant).
- **Étoiles d'avis** : ne pas promettre l'affichage des étoiles dans Google (politique Google sur les auto-avis).
- **WebP** : prévoir le fallback pour très anciens navigateurs (géré par `<picture>`).
