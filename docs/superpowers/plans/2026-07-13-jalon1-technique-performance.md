# Jalon 1 — Technique & Performance — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Réduire fortement le poids des images et corriger l'hygiène SEO de base (titres, meta, chemins, alt, robots.txt) sans changer l'architecture PHP vanilla.

**Architecture:** Un script local (`tools/optimize-images.sh`) génère des versions WebP redimensionnées, committées à côté des originaux. Un helper PHP (`layouts/helpers.php`, fonction `renderImage()`) produit des balises `<picture>` (WebP + fallback, `loading`, dimensions auto) et remplace les `<img>` lourds dans les gabarits. Les correctifs meta/titres/chemins sont appliqués fichier par fichier.

**Tech Stack:** PHP 8.2 (vanilla), `cwebp` 1.6, `sips` (macOS), Bootstrap 5, serveur de test `php -S`.

## Global Constraints

- PHP vanilla, **aucun framework ni build serveur** ; aucune dépendance Composer ajoutée.
- Déploiement GitHub Action → FTP OVH **inchangé** ; ne pas modifier `.github/workflows/deploy.yml` ni `.htaccess`.
- **Ne pas casser** l'existant : URL canonique `https://www.nuances-facade.fr`, redirections 301, sitemap.
- Pas de test unitaire disponible : vérification via `php -l` (« No syntax errors detected »), `php -S localhost:8000` + `curl`, et inspection de fichiers.
- Chemins d'images **absolus** (`/images/...`) dans le code modifié.
- Tout HTML produit par un helper doit échapper les attributs (`htmlspecialchars`).

---

### Task 1: Pipeline d'optimisation d'images (WebP)

**Files:**
- Create: `tools/optimize-images.sh`
- Modify (régénération binaire): images `.webp` sous `images/`
- Modify (réduction en place des 2 PNG géants): `images/facades/01-juillet-2023.png`, `images/facades/01-avril-2023.png`

**Interfaces:**
- Produces: pour chaque `images/**/nom.{jpg,jpeg,png}`, un fichier frère `images/**/nom.webp`. Convention de nommage utilisée par `renderImage()` (Task 2) : même chemin, extension remplacée par `.webp`.

- [ ] **Step 1: Écrire le script d'optimisation**

Create `tools/optimize-images.sh`:

```bash
#!/usr/bin/env bash
# Génère des versions WebP redimensionnées à côté des images d'origine.
# Dépendances : cwebp (obligatoire), sips (macOS, pour lire la largeur).
set -euo pipefail

MAX_WIDTH=1600
QUALITY=80
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"

if ! command -v cwebp >/dev/null 2>&1; then
  echo "Erreur : cwebp introuvable. Installez webp (brew install webp)." >&2
  exit 1
fi

find images -type f \( -iname '*.jpg' -o -iname '*.jpeg' -o -iname '*.png' \) -print0 \
| while IFS= read -r -d '' img; do
  out="${img%.*}.webp"
  width=$(sips -g pixelWidth "$img" 2>/dev/null | awk '/pixelWidth/{print $2}')
  if [ "${width:-0}" -gt "$MAX_WIDTH" ]; then
    cwebp -quiet -q "$QUALITY" -resize "$MAX_WIDTH" 0 "$img" -o "$out"
  else
    cwebp -quiet -q "$QUALITY" "$img" -o "$out"
  fi
  echo "✓ $out"
done

echo "Terminé."
```

- [ ] **Step 2: Rendre le script exécutable et le lancer**

Run:
```bash
chmod +x tools/optimize-images.sh && ./tools/optimize-images.sh
```
Expected: une ligne `✓ images/.../xxx.webp` par image, puis `Terminé.` (aucune erreur).

- [ ] **Step 3: Réduire les 2 PNG originaux surdimensionnés (fallback plus léger)**

Run:
```bash
sips --resampleWidth 1600 images/facades/01-juillet-2023.png --out images/facades/01-juillet-2023.png
sips --resampleWidth 1600 images/facades/01-avril-2023.png  --out images/facades/01-avril-2023.png
# régénère leur webp à partir des versions réduites
./tools/optimize-images.sh
```
Expected: pas d'erreur ; ces deux `.png` passent sous ~1 Mo.

- [ ] **Step 4: Vérifier que les WebP existent et sont plus légers**

Run:
```bash
ls -1 images/facades/*.webp | head
du -k images/facades/home-2.jpeg images/facades/home-2.webp
du -sh images
```
Expected: le `.webp` de `home-2` est nettement plus petit que le `.jpeg` ; le total `images/` a fortement baissé (objectif < 8 Mo).

- [ ] **Step 5: Commit**

```bash
git add tools/optimize-images.sh 'images/**/*.webp' images/facades/01-juillet-2023.png images/facades/01-avril-2023.png
git commit -m "perf(images): script d'optimisation WebP + generation des .webp"
```

---

### Task 2: Helper `renderImage()`

**Files:**
- Create: `layouts/helpers.php`

**Interfaces:**
- Produces:
  - `webp_path(string $src): string` — remplace l'extension image par `.webp`.
  - `renderImage(string $src, string $alt, array $opts = []): string` — retourne une balise `<picture>`. Options : `class` (string), `width` (int), `height` (int), `lazy` (bool, défaut `true`), `sizes` (string|null). Si `width`/`height` non fournis et le fichier existe sous `DOCUMENT_ROOT`, les dimensions sont lues via `getimagesize()` (anti-CLS).
- Consumes: rien.

- [ ] **Step 1: Écrire le helper**

Create `layouts/helpers.php`:

```php
<?php

if (!function_exists('webp_path')) {
    function webp_path(string $src): string
    {
        return preg_replace('/\.(jpe?g|png)$/i', '.webp', $src);
    }
}

if (!function_exists('renderImage')) {
    /**
     * Rend une balise <picture> WebP + fallback.
     * $opts : class, width, height, lazy (bool), sizes
     */
    function renderImage(string $src, string $alt, array $opts = []): string
    {
        $class  = $opts['class']  ?? '';
        $width  = $opts['width']  ?? null;
        $height = $opts['height'] ?? null;
        $lazy   = $opts['lazy']   ?? true;
        $sizes  = $opts['sizes']  ?? null;

        // Dimensions automatiques (anti-CLS) si non fournies et fichier lisible.
        if ($width === null && $height === null && !empty($_SERVER['DOCUMENT_ROOT'])) {
            $fsPath = $_SERVER['DOCUMENT_ROOT'] . $src;
            if (is_file($fsPath)) {
                $dim = @getimagesize($fsPath);
                if ($dim !== false) {
                    $width  = $dim[0];
                    $height = $dim[1];
                }
            }
        }

        $webp = webp_path($src);

        $attrs = '';
        if ($class !== '')    { $attrs .= ' class="' . htmlspecialchars($class, ENT_QUOTES) . '"'; }
        if ($width !== null)  { $attrs .= ' width="' . (int) $width . '"'; }
        if ($height !== null) { $attrs .= ' height="' . (int) $height . '"'; }
        if ($sizes !== null)  { $attrs .= ' sizes="' . htmlspecialchars($sizes, ENT_QUOTES) . '"'; }
        $attrs .= $lazy
            ? ' loading="lazy" decoding="async"'
            : ' loading="eager" fetchpriority="high"';

        return '<picture>'
            . '<source srcset="' . htmlspecialchars($webp, ENT_QUOTES) . '" type="image/webp">'
            . '<img src="' . htmlspecialchars($src, ENT_QUOTES) . '"'
            . ' alt="' . htmlspecialchars($alt, ENT_QUOTES) . '"' . $attrs . '>'
            . '</picture>';
    }
}
```

- [ ] **Step 2: Vérifier la syntaxe**

Run: `php -l layouts/helpers.php`
Expected: `No syntax errors detected in layouts/helpers.php`

- [ ] **Step 3: Vérifier le rendu (test manuel via php -r)**

Run:
```bash
php -r 'require "layouts/helpers.php"; echo renderImage("/images/facades/home-2.jpeg","Maison",["class"=>"w-100","lazy"=>true]);'
```
Expected (une ligne contenant) :
```
<picture><source srcset="/images/facades/home-2.webp" type="image/webp"><img src="/images/facades/home-2.jpeg" alt="Maison" class="w-100" loading="lazy" decoding="async"></picture>
```
(Les attributs `width`/`height` peuvent être absents ici car `DOCUMENT_ROOT` est vide en CLI — c'est attendu.)

- [ ] **Step 4: Commit**

```bash
git add layouts/helpers.php
git commit -m "feat(helpers): renderImage() -> <picture> WebP + fallback"
```

---

### Task 3: Charger le helper globalement + convertir les images `<img>` des gabarits

**Files:**
- Modify: `layouts/header.php` (inclure le helper)
- Modify: `layouts/carousel.php`
- Modify: `realisations.php`
- Modify: `prestations.php`

**Interfaces:**
- Consumes: `renderImage()` de Task 2.

- [ ] **Step 1: Inclure le helper dans le header**

In `layouts/header.php`, à la fin du bloc PHP d'ouverture (juste après la ligne `$keywords = $keywords ?? ...;`, avant le `?>` de la ligne 6), ajouter :

```php
require_once __DIR__ . '/helpers.php';
```

- [ ] **Step 2: Convertir le carousel de l'accueil**

In `layouts/carousel.php`, remplacer les quatre `<img ...>` par des appels `renderImage()`. Le carousel étant visible d'emblée, la **première image est en `eager`**, les suivantes en `lazy`.

Remplacer le bloc `carousel-inner` (lignes 8–22) par :

```php
    <div class="carousel-inner">
        <div class="carousel-item active">
            <?= renderImage('/images/facades/home-2.jpeg', 'Ravalement de façade — maison rénovée', ['class' => 'd-block w-100 h-100 object-fit-cover', 'lazy' => false]) ?>
        </div>
        <div class="carousel-item">
            <?= renderImage('/images/facades/home-1.png', 'Façade rénovée par Nuances Façade', ['class' => 'd-block w-100 h-100 object-fit-cover']) ?>
        </div>
        <div class="carousel-item">
            <?= renderImage('/images/facades/home-3.jpeg', 'Ravalement de façade — réalisation', ['class' => 'd-none d-md-block w-100 h-100 object-fit-cover']) ?>
            <?= renderImage('/images/facades/home-5.png', 'Ravalement de façade — réalisation (mobile)', ['class' => 'd-block d-md-none w-100 h-100 object-fit-cover']) ?>
        </div>
        <div class="carousel-item">
            <?= renderImage('/images/facades/home-4.jpeg', 'Façade neuve réalisée par Nuances Façade', ['class' => 'd-block w-100 h-100 object-fit-cover']) ?>
        </div>
    </div>
```

- [ ] **Step 3: Convertir les images de `realisations.php` (et corriger les chemins relatifs + alt)**

In `realisations.php`, remplacer chaque `<img class="img-fluid rounded-4" src="images/facades/XXX" alt="YYY">` par un appel `renderImage()` avec chemin **absolu** et `alt` descriptif. Les flèches (`arrow-black.png`) restent inchangées.

Exemple pour le premier couple (lignes 10–16) — appliquer le même patron à tous les couples avant/après :

```php
        <div class="col-md">
            <?= renderImage('/images/facades/vedene-pignon-avant.jpeg', 'Pignon à Vedène avant ravalement de façade', ['class' => 'img-fluid rounded-4']) ?>
        </div>
        <div class="col-auto">
            <img class="arrow-image" src="/images/logo/arrow-black.png" width="80" height="80" alt="flèche avant-après">
        </div>
        <div class="col-md">
            <?= renderImage('/images/facades/vedene-pignon-apres.jpeg', 'Pignon à Vedène après ravalement de façade', ['class' => 'img-fluid rounded-4']) ?>
        </div>
```

Correspondance `alt` à appliquer pour les autres couples (avant → après) :
- `garage-avant` / `garage-apres` → « Garage avant / après ravalement »
- `vedene-nord-avant` / `vedene-nord-apres` → « Façade nord à Vedène avant / après »
- `terrasse-avant` / `terrasse-apres` → « Terrasse avant / après rénovation »
- `vedene-est-avant` / `vedene-est-apres` → « Façade est à Vedène avant / après »
- `vedene-sur-avant` / `vedene-sur-apres` → « Façade sud à Vedène avant / après »
- `garcasse-avant` / `garcasse-apres` → « Maison à Garcasse avant / après ravalement »
- `les-angles-1-avant` / `les-angles-1-apres` → « Maison aux Angles avant / après ravalement »
- `nettoyage-avant` / `nettoyage-apres` → « Façade avant / après nettoyage »

(Attention : `les-angles-1-apres.JPG` et `garcasse-apres.jpg` ont des extensions particulières — conserver l'extension exacte dans le `src` passé à `renderImage()`.)

- [ ] **Step 4: Convertir les images de contenu de `prestations.php`**

In `prestations.php`, remplacer les trois `<img ... src="/images/prestations/XXX" ...>` (lignes 19, 33, 47) par :

```php
            <?= renderImage('/images/prestations/nettoyage-facade.jpeg', 'Nettoyage de façade au Kärcher', ['class' => 'd-block w-100 h-100 object-fit-cover rounded-4']) ?>
```
```php
            <?= renderImage('/images/prestations/nettoyage-toiture.jpeg', 'Nettoyage de toiture (mousses et lichens)', ['class' => 'd-block w-100 h-100 object-fit-cover rounded-4']) ?>
```
```php
            <?= renderImage('/images/prestations/joint-pierre.jpeg', 'Réfection de joints de pierre', ['class' => 'd-block w-100 h-100 object-fit-cover rounded-4']) ?>
```

- [ ] **Step 5: Vérifier la syntaxe des fichiers modifiés**

Run:
```bash
php -l layouts/header.php && php -l layouts/carousel.php && php -l realisations.php && php -l prestations.php
```
Expected: `No syntax errors detected` pour les quatre.

- [ ] **Step 6: Vérifier le rendu HTML via le serveur intégré**

Run (dans un terminal) : `php -S localhost:8000 &`
Puis :
```bash
curl -s "http://localhost:8000/realisations.php" | grep -c "<picture>"
curl -s "http://localhost:8000/realisations.php" | grep -c 'srcset="/images/facades/.*\.webp"'
curl -s "http://localhost:8000/index.php" | grep -c "<picture>"
```
Expected: `realisations.php` renvoie ≥ 18 `<picture>` et autant de `srcset` WebP ; `index.php` ≥ 4 `<picture>`.
Puis arrêter le serveur : `kill %1`

- [ ] **Step 7: Commit**

```bash
git add layouts/header.php layouts/carousel.php realisations.php prestations.php
git commit -m "perf(images): rendu <picture> via renderImage + chemins absolus + alt descriptifs"
```

---

### Task 4: Nettoyage `<head>` — titres, meta, og:image, chemin logo

**Files:**
- Modify: `layouts/header.php`
- Modify: `index.php`, `realisations.php`, `prestations.php`, `ravalement-facade.php`, `les-finitions-de-facade.php`, `contact.php`

**Interfaces:**
- Consumes: variables `$title`, `$description` déjà en place dans le header.

- [ ] **Step 1: Supprimer la balise keywords et corriger le chemin du logo**

In `layouts/header.php` :

Supprimer la ligne 15 :
```php
    <meta name="keywords" content="<?= $keywords ?>">
```

Remplacer le `src` relatif du logo (ligne 92) :
```php
            <img src="../images/logo/nf-logo-square-144.png" alt="Nuances Facade Logo">
```
par (chemin absolu + dimensions) :
```php
            <img src="/images/logo/nf-logo-square-144.png" width="72" height="72" alt="Logo Nuances Façade">
```

- [ ] **Step 2: Remplacer l'image Open Graph / Twitter par une photo de façade**

In `layouts/header.php`, remplacer les deux occurrences de `images/logo/nf-logo-big.png` (lignes 23 et 31) par la photo :
```
https://www.nuances-facade.fr/images/facades/home-2.jpeg
```
(Le fichier `og:image` doit rester une URL absolue.)

- [ ] **Step 3: Raccourcir les `<title>` et fixer une `$description` unique par page**

Appliquer ces valeurs exactes (chaque `<title>` < 60 caractères) :

`index.php` ligne 2 :
```php
$title = 'Ravalement de façade en Vaucluse | Nuances Façade';
```

`realisations.php` — remplacer la ligne 2 par les deux lignes (la page n'a pas de `$description`) :
```php
$title = 'Réalisations avant/après | Nuances Façade';
$description = "Découvrez nos chantiers de ravalement et nettoyage de façade avant/après en Vaucluse, Drôme, Gard et Ardèche.";
```

`prestations.php` ligne 2 :
```php
$title = 'Nettoyage façade, toiture & joints de pierre | Nuances Façade';
```

`ravalement-facade.php` ligne 2 :
```php
$title = 'Ravalement de façade : tout comprendre | Nuances Façade';
```

`les-finitions-de-facade.php` ligne 2 — remplacer le `$title` existant par :
```php
$title = 'Les finitions de crépi de façade | Nuances Façade';
```

`contact.php` ligne 1 — remplacer `$title = 'Contact - Devis gratuit';` par (ajout d'une `$description`) :
```php
<?php $title = 'Contact & devis gratuit | Nuances Façade'; $description = "Contactez Nuances Façade pour un devis gratuit de ravalement ou nettoyage de façade en Vaucluse, Drôme, Gard et Ardèche."; include_once "layouts/header.php" ?>
```

- [ ] **Step 4: Vérifier la syntaxe**

Run:
```bash
php -l layouts/header.php && php -l index.php && php -l realisations.php && php -l prestations.php && php -l ravalement-facade.php && php -l les-finitions-de-facade.php && php -l contact.php
```
Expected: `No syntax errors detected` partout.

- [ ] **Step 5: Vérifier le rendu du head**

Run: `php -S localhost:8000 &`
```bash
curl -s "http://localhost:8000/index.php" | grep -E "<title>|og:image"
curl -s "http://localhost:8000/index.php" | grep -c 'name="keywords"'
```
Expected: le `<title>` court attendu s'affiche, `og:image` pointe vers `home-2.jpeg`, et le compteur `keywords` renvoie `0`.
Puis : `kill %1`

- [ ] **Step 6: Commit**

```bash
git add layouts/header.php index.php realisations.php prestations.php ravalement-facade.php les-finitions-de-facade.php contact.php
git commit -m "seo: titres courts, descriptions uniques, og:image photo, logo en chemin absolu, suppression keywords"
```

---

### Task 5: Correctifs `robots.txt`, alt des avatars, orthographe « Drôme »

**Files:**
- Modify: `robots.txt`
- Modify: `index.php`

**Interfaces:**
- Consumes: rien.

- [ ] **Step 1: Nettoyer `robots.txt`**

Remplacer le contenu de `robots.txt` par (suppression de la ligne `Disallow:` vide) :

```
User-agent: *
Disallow: /cgi-bin/
Disallow: /layouts/
Sitemap: https://www.nuances-facade.fr/sitemap.xml
```

- [ ] **Step 2: Corriger les `alt` des avatars d'avis**

In `index.php`, les quatre avatars ont `alt="avatar clo"`. Corriger chacun avec le prénom correspondant :
- ligne 113 : `alt="Avatar de Clotilde"`
- ligne 130 : `alt="Avatar de Cyril A."`
- ligne 147 : `alt="Avatar de Thimote H."`
- ligne 164 : `alt="Avatar de Manuelle P."`

Corriger aussi les `alt="stars reviews"` en `alt="Note 5 étoiles"` (4 occurrences) et `alt="google reviews"` (ligne 105) en `alt="Avis Google"`.

- [ ] **Step 3: Corriger l'orthographe « Drome » → « Drôme » dans le texte visible**

Cibler uniquement la forme capitalisée « Drome » (les slugs d'URL en minuscules `drome` ne doivent PAS changer).

Run pour repérer les occurrences :
```bash
grep -rn "Drome" --include=*.php .
```
Expected: occurrences dans `index.php` (texte d'accroche), `prestations.php`, `contact.php`, `layouts/header.php` (schema `description`), et le libellé `'Drome - 26'` de `routerCities.php`.

Pour chacune, remplacer `Drome` par `Drôme` (texte affiché + libellé du tableau `routeCities` `'ravalement-facade-drome' => 'Drome - 26'` → `'Drome - 26'` devient `'Drôme - 26'`). **Ne pas** toucher la clé `'ravalement-facade-drome'`.

- [ ] **Step 4: Vérifier syntaxe + absence de régression sur les slugs**

Run:
```bash
php -l index.php && php -l prestations.php && php -l contact.php && php -l layouts/header.php && php -l routerCities.php
grep -rn "'ravalement-facade-drome'" routerCities.php
grep -rn "Drome[^-]" --include=*.php . || echo "OK: plus de 'Drome' capitalisé en texte"
```
Expected: pas d'erreur de syntaxe ; la clé de slug `'ravalement-facade-drome'` est toujours présente ; plus de « Drome » capitalisé résiduel dans le texte.

- [ ] **Step 5: Vérifier le rendu d'une page ville (non cassée)**

Run: `php -S localhost:8000 &`
```bash
curl -s "http://localhost:8000/routerCities.php?route=ravalement-facade-drome" | grep -E "<h1>|Drôme"
kill %1
```
Expected: la page ville Drôme s'affiche avec « Drôme » dans le texte.

- [ ] **Step 6: Commit**

```bash
git add robots.txt index.php prestations.php contact.php layouts/header.php routerCities.php
git commit -m "fix: robots.txt nettoye, alt avatars/avis descriptifs, orthographe Drome->Drôme"
```

---

## Self-Review

**Couverture spec (Jalon 1) :**
- Pipeline WebP + réduction images → Task 1 ✅
- Helper `renderImage()` (picture/lazy/dimensions) → Task 2 ✅ ; application aux gabarits → Task 3 ✅
- Titres < 60c, suppression keywords, og:image photo, descriptions uniques → Task 4 ✅
- Chemins d'images absolus → Tasks 3 & 4 ✅
- alt descriptifs (avatars, réalisations) → Tasks 3 & 5 ✅
- robots.txt nettoyé → Task 5 ✅
- Orthographe « Drôme » → Task 5 ✅ (« façade » minuscule reporté au jalon Contenu pour éviter de casser du code — noté dans la spec §6/risques)

**Placeholders :** aucun ; tout le code et toutes les commandes sont explicites.

**Cohérence des types :** `renderImage()` / `webp_path()` sont définis en Task 2 et consommés avec la même signature en Task 3. Convention de nommage WebP (même chemin, extension `.webp`) partagée entre Task 1 (génération) et Task 2 (`webp_path`).

**Hors périmètre de ce plan (autres jalons) :** llms.txt, schemas FAQ/Breadcrumb/Review, fil d'Ariane (Jalon 2) ; template villes, À propos, Tarifs, blog (Jalon 3) ; formulaire, CTA, galerie, bouton flottant (Jalon 4) ; hors-site (Jalon 5). Chacun aura son propre plan.
