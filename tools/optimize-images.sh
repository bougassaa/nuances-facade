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
