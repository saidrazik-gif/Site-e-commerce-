/**
 * Tracking GA4 des clics sortants par plateforme d'affiliation.
 *
 * Prérequis HTML sur chaque bouton CTA du bloc comparatif "Où l'acheter" :
 *   <a href="/go/mon-produit-amazon/"
 *      class="gf-affiliate-cta"
 *      data-plateforme="amazon"
 *      data-produit="123"
 *      data-produit-nom="Nom du produit"
 *      data-reseau="amazon-associates"
 *      rel="nofollow sponsored noopener"
 *      target="_blank">
 *     Acheter chez Amazon
 *   </a>
 *
 * Ce script pousse un événement GA4 personnalisé "affiliate_click"
 * dans le dataLayer avant que la nouvelle fenêtre/onglet ne s'ouvre.
 * Comme les liens s'ouvrent dans un nouvel onglet (target="_blank"),
 * il n'y a pas besoin de retarder la navigation : la page courante
 * n'est jamais déchargée.
 *
 * Si les liens doivent s'ouvrir dans le même onglet, utiliser
 * navigator.sendBeacon ou un court délai avant navigation (cf. fonction
 * trackAndNavigate ci-dessous) pour ne pas perdre l'événement.
 */
(function () {
	'use strict';

	window.dataLayer = window.dataLayer || [];

	function pushAffiliateClickEvent( link ) {
		window.dataLayer.push( {
			event: 'affiliate_click',
			platform: link.dataset.plateforme || 'inconnu',
			product_id: link.dataset.produit || '',
			product_name: link.dataset.produitNom || '',
			network: link.dataset.reseau || 'inconnu',
			destination_url: link.href,
		} );
	}

	function trackAndNavigate( event, link ) {
		// Utilisé uniquement pour les liens qui s'ouvrent dans le même onglet.
		event.preventDefault();
		pushAffiliateClickEvent( link );

		var destination = link.href;
		var alreadyNavigated = false;

		function navigate() {
			if ( alreadyNavigated ) {
				return;
			}
			alreadyNavigated = true;
			window.location.href = destination;
		}

		// Laisse le temps à GA4 d'envoyer l'événement (gtag envoie en général
		// en quelques dizaines de ms), avec un filet de sécurité à 300ms.
		setTimeout( navigate, 300 );
	}

	document.addEventListener( 'click', function ( event ) {
		var link = event.target.closest( '.gf-affiliate-cta' );
		if ( ! link ) {
			return;
		}

		if ( link.target === '_blank' ) {
			// Nouvel onglet : la page courante reste active, tracking direct.
			pushAffiliateClickEvent( link );
			return;
		}

		// Même onglet : on retarde légèrement la navigation.
		trackAndNavigate( event, link );
	} );
})();
