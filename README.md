# Site e-commerce d'affiliation multi-réseaux (façon Gadget Flow)

Blueprint et ressources pour construire une plateforme de découverte de produits (tech, gadgets, maison connectée) fonctionnant en affiliation multi-plateformes (Amazon Associates, eBay Partner Network, AliExpress via Admitad/Awin), bâtie sur WordPress + Elementor Pro + WooCommerce en mode catalogue.

## Contenu du dépôt

- [`docs/plan-action.md`](docs/plan-action.md) — plan d'action complet : choix de stack, comparatif des plugins d'affiliation multi-réseaux, procédure d'inscription Amazon/eBay/Admitad, structure des templates Elementor, prompts Elementor AI, bonnes pratiques d'import/synchronisation, plan de contenu, tracking GA4, monétisation complémentaire, conformité et maintenance.
- [`docs/installation-hebergement-existant.md`](docs/installation-hebergement-existant.md) — checklist pas-à-pas pour mettre en place le site sur un WordPress déjà installé (plugins, WooCommerce catalogue, plugin custom, taxonomies, comptes d'affiliation).
- [`wp-plugin/gadgetflow-toolkit/`](wp-plugin/gadgetflow-toolkit/) — plugin WordPress custom : taxonomies personnalisées (`plateforme_source`, `reseau_affiliation`, `public_cible`, `type_financement`), colonnes de reporting admin, données structurées Schema.org (Product/AggregateRating), et tracking GA4 des clics sortants par plateforme (événement `affiliate_click`).

## Par où commencer

1. Lire `docs/plan-action.md` (roadmap complète, section 14) pour la vision d'ensemble.
2. Suivre `docs/installation-hebergement-existant.md` pour la mise en place concrète sur ton hébergement WordPress déjà en place.
