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
