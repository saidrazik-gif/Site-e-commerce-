# Site e-commerce d'affiliation multi-réseaux (façon Gadget Flow)

Blueprint et ressources pour construire une plateforme de découverte de produits (tech, gadgets, maison connectée) fonctionnant en affiliation multi-plateformes (Amazon Associates, eBay Partner Network, AliExpress via Admitad/Awin), bâtie sur WordPress + Elementor Pro + WooCommerce en mode catalogue.

## Contenu du dépôt

- [`docs/plan-action.md`](docs/plan-action.md) — plan d'action complet : choix de stack, comparatif des plugins d'affiliation multi-réseaux, procédure d'inscription Amazon/eBay/Admitad, structure des templates Elementor, prompts Elementor AI, bonnes pratiques d'import/synchronisation, plan de contenu, tracking GA4, monétisation complémentaire, conformité et maintenance.
- [`code/functions-snippets.php`](code/functions-snippets.php) — taxonomies personnalisées (`plateforme_source`, `reseau_affiliation`, `public_cible`, `type_financement`), colonne de reporting admin, helper Schema.org (Product/AggregateRating).
- [`code/ga4-affiliate-tracking.js`](code/ga4-affiliate-tracking.js) — script de tracking des clics sortants par plateforme (événement GA4 `affiliate_click`).

## Par où commencer

Lire `docs/plan-action.md` dans l'ordre : il est structuré comme une roadmap (section 14) allant de l'installation WordPress à la mise en ligne, avec chaque décision technique justifiée (comparatifs de plugins, procédures d'inscription aux programmes d'affiliation, etc.).
