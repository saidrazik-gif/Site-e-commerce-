# Plan d'action — Site d'affiliation multi-réseaux façon Gadget Flow

Stack : WordPress auto-hébergé + Elementor Pro (Theme Builder) + WooCommerce en mode catalogue.
Réseaux d'affiliation ciblés : Amazon Associates, eBay Partner Network, AliExpress (via Admitad/Awin), extensible à Awin/Admitad/CJ/Rakuten/ShareASale.

> Ce document est le blueprint du projet. Il est volontairement détaillé pour servir de checklist de mise en œuvre. Vérifie toujours les conditions tarifaires et fonctionnelles actuelles des plugins/programmes avant achat ou inscription : elles évoluent.

---

## 1. Stack technique : thèmes et plugins

### 1.1 Thème

| Choix | Avantages | Inconvénients | Recommandation |
|---|---|---|---|
| **Hello Elementor + Elementor Pro** | Ultra léger, zéro CSS superflu, 100% pensé pour Theme Builder | Aucun style par défaut, tout est à construire | ✅ Recommandé pour un design 100% sur-mesure façon Gadget Flow |
| **Astra + Astra Pro / Elementor Pro** | Léger, bibliothèque de starter templates, hooks utiles, bonne compatibilité WooCommerce | Overhead marginal vs Hello Elementor | Alternative solide si tu veux des starter templates e-commerce |
| **GeneratePress + GP Premium** | Très performant, excellent support, hooks avancés | Courbe d'apprentissage des hooks | Bon choix si tu veux du contrôle fin sans coder de thème enfant |
| **REHub** ou **Passive Flow** (thèmes spécialisés affiliation) | Fonctionnalités affiliation intégrées (comparateurs, tableaux de prix, boutons multi-marchands) en natif | Moins flexible avec Elementor, style propriétaire, dépendance au thème | À envisager seulement si tu ne veux pas construire les blocs comparatifs toi-même |

**Recommandation finale : Hello Elementor + Elementor Pro.** C'est le combo le plus proche de la philosophie Gadget Flow (design épuré, 100% maîtrisé), le plus performant, et il laisse le champ libre pour construire le bloc « Où l'acheter » exactement selon le cahier des charges multi-réseaux, sans hériter des choix de structuration d'un thème spécialisé.

### 1.2 Plugin d'affiliation multi-réseaux — comparatif

C'est le choix le plus structurant du projet : il détermine comment les produits Amazon/eBay/AliExpress sont importés, synchronisés en prix, et affichés.

| Critère | **affiliate-toolkit** | **Content Egg** | **PAP Afiliados Pro** |
|---|---|---|---|
| Réseaux natifs | Amazon PA-API, eBay, AWIN, Admitad, Skimlinks, Digidip, etc. | Amazon, eBay, AliExpress, Awin, Admitad, CJ, Envato, et modules comparateur de prix | Amazon, eBay, AliExpress (détection automatique par URL) |
| Détection automatique de plateforme depuis un lien | Non (config manuelle par module) | Partielle (modules séparés par source) | Oui — champ natif, c'est son argument principal |
| Intégration Elementor native | Shortcodes/blocs Gutenberg ; compatible Elementor via shortcode | Shortcodes/blocs ; widgets Elementor tiers nécessaires | Intégration Elementor annoncée comme native (widgets dédiés) |
| Comparateur multi-offres (même produit, plusieurs marchands) | Oui, avec templates de comparaison | Oui, c'est son point fort (modules « Price Comparison », « Deals ») | Oui, orienté badges dynamiques par plateforme |
| Import en masse / synchronisation prix (cron) | Oui, robuste, API officielles | Oui, très complet (import CSV, API, scraping encadré) | Oui, plus simple, moins de réglages fins |
| Maturité / communauté / support | Très mature (CodeCanyon, mises à jour longues) | Très mature, très populaire pour les sites deals/comparateurs | Plus récent, communauté plus restreinte |
| Coût | Licence CodeCanyon (single site), abordable | Licence + abonnement optionnel pour certains modules premium | Licence CodeCanyon |
| Complexité de configuration | Moyenne | Moyenne à élevée (beaucoup de modules) | Faible à moyenne (pensé simplicité) |

**Recommandation : Content Egg comme moteur principal**, complété par **affiliate-toolkit uniquement si tu as besoin de l'API PA-API Amazon en direct avec plus de contrôle sur le rendu**. Justification :

- Content Egg gère nativement Amazon + eBay + AliExpress + Awin + Admitad dans une seule interface, ce qui correspond exactement au besoin multi-réseaux simultané.
- Son module « Price Comparison » est conçu pour le bloc « Où l'acheter » comparatif demandé (plusieurs marchands, un même produit).
- Il expose des hooks/shortcodes qu'on peut appeler depuis des widgets HTML/Shortcode Elementor Pro, donc la contrainte « Elementor Pro comme constructeur » reste respectée sans plugin de liaison supplémentaire.
- affiliate-toolkit reste une option de secours ou un complément si Content Egg s'avère limité sur un réseau particulier (ex. gestion fine du reporting Admitad).

**PAP Afiliados Pro** est intéressant pour sa détection automatique de plateforme et ses badges dynamiques « prêts à l'emploi », mais sa communauté plus restreinte et sa moindre maturité sur le volume d'import en font un second choix, à réserver à un lancement rapide avec un catalogue plus petit (< 200 produits) si Content Egg est jugé trop complexe à démarrer.

**AmaSync / Ampar** : uniquement pertinents si le catalogue est backend Amazon-only. À écarter ici puisque le cœur du projet est justement l'orchestration multi-réseaux — ils devraient de toute façon être combinés à un second plugin pour eBay/AliExpress, ce qui complexifie sans bénéfice net face à Content Egg qui couvre déjà tout en un seul plugin.

### 1.3 Plugins complémentaires obligatoires

| Fonction | Plugin | Notes |
|---|---|---|
| SEO | Rank Math (ou Yoast SEO) | Rank Math a un meilleur module Schema.org natif (Product, Review) sans code additionnel |
| Cache / performance | WP Rocket | Lazy loading images, minification CSS/JS, cache serveur |
| CDN images | Optionnel : plugin de compression (Imagify/ShortPixel) | Les galeries produit 1600×900 doivent être optimisées |
| Liens morts | Broken Link Checker | Contrôle mensuel des liens d'affiliation cassés |
| Wishlist | YITH WooCommerce Wishlist (gratuit) | Compatible catalogue-only |
| Recherche/filtres avancés | FiboSearch (Ajax Search for WooCommerce) ou filtres natifs Elementor Pro (Query Loop + widget Search) | Filtrer par prix/catégorie/plateforme/note |
| Emailing | Elementor Pro Forms + intégration native Mailchimp/ConvertKit | Pas besoin de plugin tiers, Elementor Pro gère la connexion API |
| Popups | Elementor Pro Popup Builder | Natif, pas de plugin supplémentaire nécessaire |
| Sécurité | Wordfence ou Sucuri | Un site avec beaucoup de trafic sortant est une cible ; à sécuriser dès le départ |
| Traduction (si multilingue prévu) | WPML ou Polylang | À anticiper si expansion internationale |

---

## 2. Configuration WooCommerce + plugin d'affiliation

### 2.1 WooCommerce en mode catalogue

1. Installer WooCommerce, lancer l'assistant de configuration mais **désactiver Stripe/PayPal et tout module de paiement**.
2. Réglages → Produits → Général : décocher tout ce qui concerne le panier natif si tu utilises un plugin type **YITH WooCommerce Catalog Mode** (recommandé) pour :
   - masquer le bouton « Ajouter au panier » globalement,
   - masquer les prix natifs WooCommerce si tu préfères afficher uniquement les prix générés par Content Egg (évite la désynchronisation),
   - remplacer le CTA par un bouton personnalisé pointant vers le lien d'affiliation.
3. Désactiver les emails transactionnels WooCommerce (commande, facture) inutiles en mode catalogue.
4. Garder les taxonomies natives `product_cat` uniquement pour la structure générale ; créer les taxonomies personnalisées dédiées (voir section 4) plutôt que de tout entasser dans les catégories WooCommerce.
5. Chaque « produit » WooCommerce sert de fiche éditoriale enrichie (titre réécrit, description longue, galerie) ; les données brutes marchand (prix, dispo, lien) sont gérées par Content Egg en tant que « offres » attachées au produit.

### 2.2 Configuration Content Egg

1. Installer Content Egg + activer les modules : **Amazon**, **eBay**, **AliExpress**, **Awin**, **Admitad**, **Price Comparison**, **Product Import**.
2. Dans chaque module, renseigner les identifiants API (voir section 3 pour l'obtention) :
   - Amazon PA-API : Access Key, Secret Key, Associate Tag, marketplace (ex. amazon.fr).
   - eBay : App ID via EPN, Campaign ID.
   - AliExpress : via Admitad (Advertiser ID + tracking ID) ou Awin (Publisher ID).
3. Créer un champ personnalisé produit **« Plateforme source »** (Amazon / eBay / AliExpress / Autre) — ceci est une taxonomie (voir section 4), pas un simple champ, pour permettre le filtrage front et le reporting.
4. Créer un second champ **« Réseau d'affiliation »** (Amazon Associates / EPN / Admitad / Awin / Autre) en champ personnalisé (ACF ou meta Content Egg) pour tracer précisément par quel réseau transite chaque lien, indépendamment de la plateforme marchande — utile quand une même plateforme (ex. AliExpress) peut être routée via Admitad ou Awin selon les campagnes.
5. Régler la fréquence de synchronisation des prix (cron WP) : toutes les 6 à 12h pour Amazon/eBay (API rapides), toutes les 24h pour AliExpress via réseau tiers (latence plus grande côté Admitad/Awin).
6. Activer le module **Price Comparison** et l'associer au champ « Plateforme source » pour générer le bloc comparatif multi-offres sur chaque fiche produit.
7. Configurer le format de lien de sortie : forcer `rel="nofollow sponsored noopener"` et un préfixe d'URL de redirection interne (ex. `/go/nom-produit-amazon/`) pour permettre le tracking de clic (voir section 8) sans exposer directement le lien tracké brut en dur dans le HTML.

### 2.3 Routage par plateforme (logique de configuration)

```
Lien produit détecté
   ├── domaine amazon.* → Module Amazon PA-API → lien Amazon Associates (tag= ton-tag)
   ├── domaine ebay.*   → Module eBay → lien EPN (campid= ta-campagne)
   └── domaine aliexpress.* → Module AliExpress
          ├── si compte Admitad actif → lien tracké Admitad (deeplink AliExpress)
          └── sinon si compte Awin actif → lien tracké Awin
```

Ce routage est géré automatiquement par les modules Content Egg dès que les clés API/réseaux sont renseignées ; il n'y a pas de règle à coder manuellement, mais il faut vérifier module par module que le bon réseau est bien sélectionné par défaut (paramètre « réseau prioritaire » dans les réglages du module AliExpress).

---

## 3. Inscription et configuration des 3 programmes d'affiliation

### 3.1 Amazon Associates

1. S'inscrire sur [affiliate-program.amazon.fr](https://affiliate-program.amazon.fr) (ou .com selon le marché ciblé) avec l'URL du site déjà en ligne (Amazon exige un site fonctionnel, pas juste un nom de domaine).
2. Renseigner les moyens de paiement et infos fiscales.
3. Générer un **Tracking ID** (tag associé) — en créer un dédié par canal si tu veux distinguer plus tard homepage/blog/newsletter.
4. Demander l'accès à la **Product Advertising API (PA-API 5.0)** : nécessite d'avoir réalisé au moins 3 ventes qualifiées dans les 180 jours suivant l'inscription, sinon l'accès API est suspendu — à anticiper, car sans PA-API, Content Egg ne peut pas récupérer prix/stock en temps réel pour Amazon.
5. Générer Access Key + Secret Key API dans la console Amazon Associates.
6. Ajouter la mention légale obligatoire en pied de page et sur chaque fiche produit Amazon : *« En tant que Partenaire Amazon, je réalise un bénéfice sur les achats remplissant les conditions requises. »*
7. Respecter les règles Amazon : ne jamais afficher un prix Amazon en cache/statique au-delà de 24h, toujours rediriger vers un lien de tracking valide, ne jamais utiliser le mot « Amazon » dans le nom de domaine.

### 3.2 eBay Partner Network (EPN)

1. Créer un compte sur [partnernetwork.ebay.com](https://partnernetwork.ebay.com).
2. Créer une **Campaign ID** par usage (ex. une campagne « site principal », une autre « newsletter » si besoin de reporting distinct).
3. Récupérer l'**App ID** (nécessaire pour l'API Finding/Browse d'eBay utilisée par Content Egg pour importer prix/photos produits).
4. Générer les liens via l'**EPN Link Generator** ou laisser Content Egg les générer automatiquement une fois App ID + Campaign ID renseignés.
5. Règle stricte EPN à respecter : **pas de double tracking**. Un même clic ne doit jamais passer par deux liens d'affiliation superposés (ex. lien EPN encapsulé dans un raccourcisseur d'URL tiers qui ajoute son propre tracking) — utilise uniquement ta propre redirection interne (`/go/...`) qui pointe en 301 vers le lien EPN final, sans repasser par un second réseau d'affiliation.
6. Ajouter la mention de transparence sur les fiches eBay (moins strict que Amazon légalement en France, mais bonne pratique à harmoniser avec la mention Amazon).

### 3.3 AliExpress via Admitad (ou Awin)

**Point critique déjà identifié : AliExpress ne fournit pas de deep-linking simple depuis son programme natif.** Il faut passer par un réseau tiers.

**Procédure Admitad (recommandé, réseau historique le plus complet sur AliExpress) :**

1. Créer un compte éditeur sur [admitad.com](https://www.admitad.com).
2. Ajouter le site (validation manuelle par Admitad, prévoir 1 à 5 jours ouvrés — décrire précisément le site comme « comparateur/agrégateur de produits tech avec liens d'affiliation multi-marchands »).
3. Rechercher et rejoindre le programme **AliExpress** dans le catalogue d'annonceurs Admitad (parfois listé sous « AliExpress Portals » ou similaire selon la région).
4. Une fois approuvé, récupérer :
   - l'**Advertiser/Website ID** Admitad,
   - le **paramètre de tracking** (ulp — deep link) qui permet de transformer n'importe quelle URL produit AliExpress en lien traqué.
5. Configurer le module AliExpress de Content Egg avec ces identifiants ; certains plugins nécessitent le **AliExpress Affiliate API** (clé App Key / App Secret) en complément si tu veux aussi importer automatiquement les produits (pas seulement générer un lien).
6. Alternative/complément : **Awin** référence aussi un programme AliExpress dans certaines régions — à activer en secours si Admitad refuse la candidature ou si le taux de commission Awin est ponctuellement meilleur. Le principe est identique (inscription éditeur → recherche annonceur AliExpress → génération de deep links via l'API Awin).
7. Mention de transparence sur les fiches AliExpress : préciser que le lien est un lien sponsorisé/affilié, conformément aux exigences Admitad de transparence vis-à-vis du consommateur.

### 3.4 Extension future (Awin, Admitad généraliste, CJ, Rakuten, ShareASale)

- Pour diversifier au-delà des 3 réseaux socles, ouvrir un compte **Awin** (généraliste, couvre de nombreuses marques tech/déco/DIY européennes) et **CJ Affiliate** (marques US/internationales) dès que le trafic dépasse un seuil crédible pour être accepté (les réseaux généralistes valident souvent sur trafic réel, contrairement à Amazon).
- Chaque nouveau réseau ajouté doit être déclaré dans la taxonomie « Réseau d'affiliation » (section 4) pour rester cohérent dans le reporting.

---

## 4. Taxonomies personnalisées

À créer via **Pods**, **ACF (Custom Fields Pro)**, ou en code dans un plugin de fonctionnalités dédié (voir `code/functions-snippets.php` fourni dans ce dépôt) :

| Taxonomie | Slug | Termes exemples | Usage |
|---|---|---|---|
| Catégorie produit | `categorie_produit` | Tech, Maison connectée, Design, Outdoor | Navigation principale |
| Public cible | `public_cible` | Pour lui, Pour elle, Enfants, Mixte | Filtres et sections thématiques |
| **Plateforme source** | `plateforme_source` | Amazon, eBay, AliExpress, Autre | **Essentiel** : badges, filtres, reporting par plateforme |
| Réseau d'affiliation | `reseau_affiliation` | Amazon Associates, EPN, Admitad, Awin, CJ | Traçabilité fine indépendante de la plateforme affichée |
| Type de financement | `type_financement` | Kickstarter, Indiegogo, Aucun (produit standard) | Sections « Campagnes crowdfunding » |
| Marque | `marque` | (dynamique par produit) | Pages marque, cross-sell |

La taxonomie `plateforme_source` doit être exposée en filtre front (widget Elementor Pro « Query Loop » avec filtre par taxonomie) et en colonne de reporting côté admin (ajouter la colonne dans la liste des produits WooCommerce via un snippet, cf. `code/functions-snippets.php`).

---

## 5. Structure des templates Elementor (Theme Builder)

| Template | Type Elementor Pro | Contenu clé |
|---|---|---|
| **En-tête (Header)** | Header | Logo, menu principal (par `categorie_produit`), barre de recherche avec filtres, icône wishlist, sélecteur mode sombre |
| **Pied de page (Footer)** | Footer | Mentions légales d'affiliation (Amazon/EPN/Admitad), liens réseaux sociaux, newsletter, mentions RGPD/cookies |
| **Archive produits** | Archive (Products) | Query Loop connecté à `product` + filtres par `plateforme_source`, `categorie_produit`, prix, note. Cartes produit en grille masonry |
| **Fiche produit unique** | Single (Products) | Galerie, titre, description éditoriale, section « Pourquoi on aime », specs, bloc comparatif « Où l'acheter » (Content Egg Price Comparison), badges plateforme, notation, cross-sell |
| **Page d'accueil** | Page normale (ou Front Page) | Hero, sections dynamiques par Query Loop (Tendances, Nouveautés, Coups de cœur, Crowdfunding, Meilleures ventes AliExpress), CTA newsletter |
| **Archive Blog / Single Article** | Archive + Single (Post) | Grille d'articles, article avec liens internes vers fiches produits, CTA fin d'article |
| **Popup capture email** | Popup | Déclenché par intention de sortie (exit-intent) ou après X secondes |

**Ordre de construction recommandé :** Header/Footer → Fiche produit unique (le plus complexe, contient le bloc comparatif) → Archive produits → Page d'accueil → Templates blog → Popups.

---

## 6. Exemples de prompts Elementor AI

À utiliser dans l'éditeur Elementor Pro (fonctionnalité Elementor AI) pour accélérer la génération de sections, puis affiner manuellement le design :

**Hero page d'accueil :**
> « Génère une section hero pour un site de découverte de gadgets tech innovants, style minimaliste et moderne, fond blanc avec un accent de couleur vive (orange ou bleu électrique), un titre accrocheur sur la découverte de produits innovants, un sous-titre, et un bouton CTA "Découvrir les tendances". »

**Grille de produits (Query Loop) :**
> « Crée une carte produit pour une grille de type masonry : image produit en haut, badge rond en coin supérieur affichant le logo de la plateforme marchande, titre du produit, courte accroche d'une ligne, prix indicatif, bouton CTA "Voir le prix" en bas de carte, style épuré avec ombre légère au survol. »

**Bloc comparatif « Où l'acheter » :**
> « Génère un bloc comparatif de 3 colonnes présentant une offre par plateforme (Amazon, eBay, AliExpress) : logo de la plateforme, prix, note du marchand, badge optionnel ("Meilleur prix", "Livraison rapide"), et un bouton CTA distinct par colonne avec la mention "Acheter chez [Marchand]". »

**CTA newsletter (popup) :**
> « Crée un popup de capture email avec un titre engageant sur les meilleures trouvailles tech de la semaine, un champ email, un bouton d'inscription, et un visuel de fond discret en dégradé de couleur claire. »

**Section article de blog :**
> « Génère une mise en page d'article de blog avec image à la une en pleine largeur, chapô en gras, corps de texte en deux colonnes optionnelles, et un encart latéral "Produits mentionnés dans cet article" avec mini-cartes produit. »

> Traite chaque résultat d'Elementor AI comme un brouillon de mise en page : ajuste ensuite manuellement les espacements, la typographie et les couleurs pour respecter la charte définie en section 9.

---

## 7. Bonnes pratiques d'import multi-plateformes et synchronisation des prix

1. **Import qualitatif, jamais en masse brute** : importer un produit via Content Egg (recherche par mot-clé ou ASIN/ID), puis **toujours réécrire manuellement** titre, description, section « Pourquoi on aime » avant publication — ne jamais publier le texte brut du flux marchand (règle anti-duplication + valeur éditoriale façon Gadget Flow).
2. **Statut brouillon par défaut à l'import** : configurer Content Egg pour créer les produits importés en `draft`, avec publication manuelle après enrichissement éditorial.
3. **Synchronisation des prix automatique mais affichage non figé** : activer le cron de mise à jour des prix (6-12h pour Amazon/eBay, 24h pour AliExpress) mais ne jamais indiquer un prix exact « en dur » dans le texte éditorial — toujours utiliser le shortcode/bloc dynamique Content Egg pour l'affichage du prix, jamais une valeur copiée-collée qui deviendrait obsolète (violation des règles Amazon).
4. **Gestion des ruptures de stock/produits retirés** : programmer une vérification hebdomadaire (rapport Content Egg) des produits dont l'offre a disparu chez un marchand ; soit retirer l'offre du comparatif, soit dépublier la fiche si plus aucune offre n'est disponible.
5. **Limiter le volume par vague** : viser des lots de 20 à 50 produits par semaine bien traités plutôt que des imports de centaines de produits d'un coup (cohérent avec la contrainte qualité > quantité, objectif ~500 produits bien présentés).
6. **Dédoublonnage** : avant chaque import, vérifier qu'un produit équivalent n'existe pas déjà sous une autre plateforme (utiliser la recherche interne par titre/marque) pour éviter deux fiches quasi identiques qui se cannibaliseraient en SEO — dans ce cas, une seule fiche avec plusieurs offres (le bloc comparatif) est la bonne approche.
7. **Contrôle qualité images** : uniformiser toutes les images en 1600×900 (recadrage), compresser via Imagify/ShortPixel à l'import pour ne pas pénaliser les Core Web Vitals.

---

## 8. Tracking des clics par plateforme (GA4, événements personnalisés, UTM)

### 8.1 Principe

Chaque bouton CTA de sortie (Amazon / eBay / AliExpress / Autre) doit déclencher un événement GA4 personnalisé **avant** la redirection, afin de mesurer le taux de clic sortant par plateforme et par produit.

### 8.2 Mise en œuvre

1. Créer une redirection interne pour chaque lien d'affiliation (`/go/{slug-produit}-{plateforme}/`) via un plugin de redirection (ou le module de tracking natif de Content Egg, qui propose déjà des URLs de type `/recommends/...`).
2. Ajouter un attribut `data-*` sur chaque bouton CTA généré par le bloc comparatif, précisant `data-plateforme="amazon|ebay|aliexpress"`, `data-produit="{id ou slug}"`, `data-reseau="{amazon-associates|epn|admitad|awin}"`.
3. Injecter un script global (voir `code/ga4-affiliate-tracking.js` fourni dans ce dépôt) qui écoute les clics sur ces boutons et pousse un événement `dataLayer.push()` GA4 personnalisé (`affiliate_click`) avec les paramètres plateforme/produit/réseau, **avant** de laisser la redirection s'exécuter (délai de quelques centaines de ms ou usage de `navigator.sendBeacon` pour ne pas perdre l'event si la page se décharge trop vite).
4. Dans GA4, configurer l'événement `affiliate_click` comme conversion, avec les paramètres personnalisés `platform`, `product_id`, `network` enregistrés comme dimensions personnalisées pour pouvoir ventiler les rapports par plateforme.
5. Ajouter des **UTM** sur les liens internes qui pointent vers les fiches produits depuis les articles de blog et la newsletter (`utm_source=newsletter`, `utm_medium=email`, `utm_campaign=nom-campagne`) pour distinguer l'origine du trafic qui convertit le mieux, indépendamment du tracking d'affiliation lui-même (qui reste toujours dans le lien de sortie tracké par le réseau).
6. Construire un tableau de bord GA4 (Explorations) croisant `platform` × `product_id` × device pour suivre en continu Amazon vs eBay vs AliExpress, et ajuster la mise en avant de chaque plateforme selon les taux de conversion réels.

---

## 9. Design et charte graphique

- Fond dominant blanc/gris très clair (#FAFAFA), texte gris foncé quasi noir (#1A1A1A) pour la lisibilité.
- Une couleur d'accent vive unique pour tous les CTA (ex. orange #FF5A1F ou bleu électrique #2D6CFF) — jamais plus d'un accent pour ne pas diluer l'attention.
- Typographie sans-serif (Inter, Poppins, ou Manrope) via Google Fonts, chargée en `font-display: swap`.
- Badges de plateforme : petits cercles 32-40px en coin supérieur droit des cartes produit, logo officiel de la plateforme (respecter les guidelines de marque Amazon/eBay/AliExpress sur l'usage de leurs logos).
- Mode sombre : variables CSS (custom properties) basculées via une classe `body.dark-mode`, toggle dans le header, préférence sauvegardée en `localStorage`.
- Mobile-first : grille de cartes qui passe de 4 colonnes (desktop) à 2 (tablette) à 1 (mobile), CTA toujours visibles sans scroll horizontal.

---

## 10. Plan de contenu initial

### 10.1 Catégories de lancement (taxonomie `categorie_produit`)

Tech, Maison connectée, Design & Déco, Outdoor & Voyage, Audio & Son, Bien-être & Fitness.

### 10.2 Guides d'achat comparatifs (articles pivots, forte valeur SEO)

- « Amazon vs AliExpress : où acheter vos gadgets au meilleur prix ? »
- « eBay ou Amazon pour l'électronique reconditionnée : le comparatif complet »
- « Livraison rapide vs prix bas : comment choisir sa plateforme selon vos besoins »
- « AliExpress est-il fiable ? Notre méthode pour sélectionner les meilleurs vendeurs »
- « Kickstarter et Indiegogo : comment repérer les projets tech qui tiennent leurs promesses »

### 10.3 Premiers articles de blog (chacun renvoie vers 3-5 fiches produits)

- « Les 10 gadgets maison connectée qui changent vraiment le quotidien »
- « Top objets outdoor innovants pour l'été »
- « Idées cadeaux tech à moins de 50€ »
- « Les meilleures trouvailles AliExpress testées et validées »
- « Rentrée : les accessoires audio les plus attendus de l'année »

### 10.4 Rythme de publication recommandé

2 fiches produit enrichies par jour ouvré (≈ 40/mois) + 1 article de blog/guide par semaine, en priorisant les guides comparatifs qui consolident le maillage interne vers les fiches produits déjà publiées.

---

## 11. Monétisation complémentaire

- **Bannières display** ciblées tech (ex. régies spécialisées gadgets/high-tech) en complément de l'affiliation, positionnées en sidebar et fin d'article, jamais dans le bloc comparatif pour ne pas cannibaliser le CTA principal.
- **Placements sponsorisés** : articles « Découverte » clairement étiquetés « Contenu sponsorisé », vendus directement à des marques émergentes (crowdfunding, D2C) — bonne synergie avec la section « Campagnes crowdfunding ».
- **Newsletter monétisée** : une sélection hebdomadaire de produits avec liens d'affiliation, éventuellement un encart sponsorisé unique par envoi une fois l'audience suffisante.
- **Programme d'affiliation propre** (optionnel, plus tard) : si le site développe des guides très suivis, envisager un partenariat direct avec certaines marques (commission négociée hors réseau, meilleure marge) une fois le trafic significatif.
- Toujours garder la diversification comme filet de sécurité : ne jamais construire une section entière du site autour d'un seul réseau (ex. une home 100% Amazon) pour rester résilient à une baisse de commission d'un partenaire.

---

## 12. Conformité et bonnes pratiques transverses

- Mention légale Amazon obligatoire visible en pied de page **et** sur chaque fiche produit Amazon.
- Aucune double captation de tracking eBay (jamais de lien EPN encapsulé dans un raccourcisseur tiers).
- Transparence explicite sur le caractère sponsorisé/affilié de tous les liens sortants (bandeau ou mention discrète en fiche produit), conforme aux exigences Admitad/Awin et aux obligations de transparence commerciale.
- Tous les liens sortants marchands en `rel="nofollow sponsored noopener"`.
- Zéro contenu dupliqué : chaque description est réécrite, jamais copiée du flux marchand.
- Qualité > quantité : objectif ~500 produits, jamais d'import de masse non contrôlé.

---

## 13. Maintenance récurrente

| Fréquence | Tâche |
|---|---|
| Hebdomadaire | Vérifier les rapports Content Egg (offres disparues, ruptures de stock) |
| Hebdomadaire | Publier au moins 1 article/guide comparatif |
| Mensuelle | Scan complet Broken Link Checker + correction des liens morts |
| Mensuelle | Revue des taux de conversion par plateforme (GA4) et ajustement de la mise en avant éditoriale |
| Mensuelle | Mise à jour des plugins (thème, Elementor Pro, WooCommerce, Content Egg, sécurité) sur un environnement de staging avant prod |
| Trimestrielle | Audit SEO (Rank Math), revue des Core Web Vitals, revue de la charte design |
| Trimestrielle | Revue des conditions des programmes d'affiliation (barèmes de commission, CGU) — Amazon et les réseaux tiers changent parfois leurs règles sans préavis long |

---

## 14. Séquence de mise en œuvre recommandée (roadmap synthétique)

1. **Semaine 1** : hébergement + install WordPress, thème Hello Elementor, Elementor Pro, WooCommerce en mode catalogue, SEO/cache/sécurité de base.
2. **Semaine 1-2** : installation et configuration Content Egg (modules Amazon/eBay/AliExpress/Admitad/Awin), création des taxonomies personnalisées.
3. **Semaine 2** : inscriptions Amazon Associates, EPN, Admitad (démarrer ces inscriptions le plus tôt possible car elles impliquent des délais de validation externes).
4. **Semaine 2-3** : construction Theme Builder (header/footer, single produit avec bloc comparatif, archive produits).
5. **Semaine 3** : construction page d'accueil + templates blog + popups.
6. **Semaine 3-4** : mise en place du tracking GA4/UTM, import et enrichissement des 50 premiers produits.
7. **Semaine 4** : rédaction des premiers guides comparatifs, lancement, premier envoi newsletter.
8. **En continu** : montée en charge du catalogue (2 fiches/jour), routine de maintenance (section 13).

---

## Annexes fournies dans ce dépôt

- `code/functions-snippets.php` : snippets à intégrer (via un plugin de fonctionnalités ou le functions.php du thème enfant) pour les taxonomies personnalisées, la colonne de reporting « Plateforme source » dans l'admin, et un helper Schema.org (Product/AggregateRating).
- `code/ga4-affiliate-tracking.js` : script de tracking des clics sortants par plateforme pour GA4.
