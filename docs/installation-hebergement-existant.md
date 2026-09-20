# Mise en place sur un hébergement WordPress déjà installé

Checklist opérationnelle pour démarrer la construction du site sur ton WordPress existant. Chaque étape renvoie à la section correspondante de [`docs/plan-action.md`](plan-action.md) pour le détail/justification.

> Ces actions se font dans l'admin WordPress (`/wp-admin`) et sur les sites tiers (Amazon, eBay, Admitad) — je n'ai pas d'accès direct à ton hébergement. Coche au fur et à mesure et dis-moi où tu bloques : je peux ajuster le plan, le code du plugin custom, ou les instructions.

## Étape 0 — Avant de commencer

- [ ] Faire une sauvegarde complète du site actuel (fichiers + base de données) avant d'installer quoi que ce soit.
- [ ] Vérifier la version de PHP (≥ 7.4, idéalement 8.1+) dans l'hébergement : Elementor Pro et WooCommerce récents l'exigent.
- [ ] Mettre à jour WordPress core vers la dernière version stable.

## Étape 1 — Thème

- [ ] Installer et activer **Hello Elementor** (thème → Ajouter → rechercher « Hello Elementor »).
- [ ] Ne PAS activer d'autres thèmes en parallèle une fois Hello Elementor choisi.

_Référence : plan-action.md section 1.1_

## Étape 2 — Plugins de base

Installer dans cet ordre (Extensions → Ajouter) :

- [ ] **Elementor** (gratuit, prérequis d'Elementor Pro)
- [ ] **Elementor Pro** (licence à activer avec ta clé)
- [ ] **WooCommerce**
- [ ] **YITH WooCommerce Catalog Mode** (désactive achat direct, remplace par CTA custom)
- [ ] **Rank Math SEO** (ou Yoast SEO)
- [ ] **WP Rocket** (cache/performance)
- [ ] **Broken Link Checker**
- [ ] **YITH WooCommerce Wishlist**
- [ ] Un plugin de sécurité : **Wordfence** ou **Sucuri**

_Référence : plan-action.md section 1.3_

## Étape 3 — WooCommerce en mode catalogue

- [ ] Lancer l'assistant de configuration WooCommerce, **sans activer Stripe/PayPal**.
- [ ] Dans YITH Catalog Mode : masquer le bouton « Ajouter au panier » globalement, désactiver les emails transactionnels inutiles (commande, facture).
- [ ] Vérifier qu'aucun tunnel de paiement n'est actif (Réglages WooCommerce → Paiements : tout désactivé).

_Référence : plan-action.md section 2.1_

## Étape 4 — Plugin d'affiliation multi-réseaux

Deux voies possibles selon le budget disponible au démarrage :

### Option A — Content Egg (payant, import/sync automatique)

- [ ] Acheter et installer **Content Egg** (CodeCanyon).
- [ ] Activer les modules : Amazon, eBay, AliExpress, Awin, Admitad, Price Comparison, Product Import.
- [ ] Renseigner les clés API au fur et à mesure qu'elles sont obtenues (voir étape 6 ci-dessous) :
  - Amazon PA-API : Access Key / Secret Key / Associate Tag
  - eBay : App ID + Campaign ID (EPN)
  - AliExpress : identifiants Admitad (Advertiser ID + tracking) ou Awin
- [ ] Régler la fréquence de synchronisation prix : 6-12h (Amazon/eBay), 24h (AliExpress).
- [ ] Configurer le préfixe de lien de sortie (`/go/...` ou équivalent Content Egg) et forcer `rel="nofollow sponsored noopener"`.

_Référence : plan-action.md sections 1.2, 2.2, 2.3_

### Option B — Démarrage gratuit (saisie manuelle via le plugin custom)

Pour démarrer sans frais, le plugin `gadgetflow-toolkit` (étape 5) inclut désormais un système de saisie manuelle des offres, en remplacement de Content Egg :

- [ ] Installer **ThirstyAffiliates** (gratuit, Extensions → Ajouter) pour cloaker les liens (`/go/nom-produit-amazon/`), forcer `nofollow sponsored`, et suivre les clics par lien.
- [ ] Sur chaque fiche produit, remplir la meta box **« Où l'acheter — offres par plateforme »** (ajoutée par `gadgetflow-toolkit` v1.1.0) : prix, devise, lien tracké (généré via ThirstyAffiliates), réseau, badge optionnel — une ligne par plateforme (Amazon/eBay/AliExpress/Autre), laisser vide si pas d'offre.
- [ ] Ajouter le shortcode `[gf_offers]` dans le template Elementor de fiche produit unique (widget « Shortcode ») pour afficher le bloc comparatif — les boutons sont déjà en `rel="nofollow sponsored noopener"` et déjà câblés pour le tracking GA4 (étape 7).
- [ ] Migration vers Content Egg possible plus tard (une fois le trafic/revenu le justifie) sans perdre les taxonomies ni casser le rendu front.

_Référence : plan-action.md section 1.2 ; code dans `wp-plugin/gadgetflow-toolkit/includes/class-offers.php` et `class-offers-shortcode.php`_

## Étape 5 — Installer le plugin custom du dépôt

Le dossier [`wp-plugin/gadgetflow-toolkit/`](../wp-plugin/gadgetflow-toolkit/) de ce dépôt contient les taxonomies personnalisées, les colonnes de reporting admin, la saisie manuelle des offres + bloc comparatif `[gf_offers]`, le Schema.org et le tracking GA4.

- [ ] Compresser le dossier `wp-plugin/gadgetflow-toolkit/` en `.zip` (le dossier lui-même à la racine du zip, pas son contenu directement).
- [ ] Dans l'admin WordPress : Extensions → Ajouter → Téléverser une extension → sélectionner le zip → Installer → Activer (si une version précédente est déjà active, la réactivation met simplement à jour le code, aucune donnée n'est perdue).
- [ ] Vérifier après activation : les taxonomies « Plateforme », « Réseau », « Public cible », « Type de financement » apparaissent dans Produits, et 4 termes de plateforme (Amazon/eBay/AliExpress/Autre) sont pré-créés.
- [ ] Vérifier que la colonne « Plateforme » apparaît bien dans la liste des produits (Produits → Tous les produits).
- [ ] Vérifier qu'une meta box « Où l'acheter — offres par plateforme » apparaît en éditant une fiche produit.

_Alternative sans FTP/zip_ : si SSH/WP-CLI devient disponible plus tard, `wp plugin install` n'est pas utilisable pour un plugin non publié sur wordpress.org — il faudra copier le dossier directement dans `wp-content/plugins/` via FTP/SFTP ou un gestionnaire de fichiers cPanel.

## Étape 6 — Comptes d'affiliation (à démarrer en parallèle, délais de validation externes)

- [ ] **Amazon Associates** : inscription sur affiliate-program.amazon.fr (le site doit déjà être en ligne). Générer le tracking ID. Demander l'accès PA-API une fois 3 ventes qualifiées réalisées.
- [ ] **eBay Partner Network** : inscription sur partnernetwork.ebay.com, créer une Campaign ID, récupérer l'App ID.
- [ ] **Admitad** : inscription éditeur, ajout du site (validation manuelle 1-5 jours), candidature au programme AliExpress une fois approuvé.
- [ ] Ajouter les mentions légales obligatoires (pied de page + fiches produit Amazon) — texte exact en section 3.1 de plan-action.md.

_Référence : plan-action.md section 3, procédures complètes_

## Étape 7 — Tracking GA4

- [ ] Vérifier que Google Tag Manager ou gtag.js est déjà installé sur le site (le plugin custom ne pose que l'événement, pas le tag GA4 lui-même).
- [ ] Ajouter les attributs `data-plateforme`, `data-produit`, `data-produit-nom`, `data-reseau` et la classe `gf-affiliate-cta` sur les boutons CTA du bloc comparatif « Où l'acheter » construit dans Elementor.
- [ ] Dans GA4 : configurer l'événement `affiliate_click` comme conversion, avec `platform`/`product_id`/`network` en dimensions personnalisées.
- [ ] Tester en DebugView GA4 qu'un clic sur un CTA remonte bien l'événement.

_Référence : plan-action.md section 8_

## Étape 8 — Construction Elementor (Theme Builder)

Une fois les étapes 1-5 faites, suivre l'ordre de construction recommandé en section 5 de plan-action.md : Header/Footer → Fiche produit unique (bloc comparatif) → Archive produits → Page d'accueil → Blog → Popups.

## Étape 9 — Premier lot de contenu

- [ ] Importer et enrichir les 20-50 premiers produits (réécriture complète, jamais le texte brut du flux marchand — cf. plan-action.md section 7).
- [ ] Publier le premier article/guide comparatif (liste en section 10.2 de plan-action.md).

---

**Dis-moi à quelle étape tu es rendu** (ou si un plugin/réglage pose problème) et je continue en conséquence — code du plugin custom, ajustement des instructions, ou contenu éditorial.
