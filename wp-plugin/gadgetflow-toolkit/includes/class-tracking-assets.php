<?php
/**
 * Charge le script de tracking GA4 des clics sortants par plateforme
 * sur tout le front-end (cf. docs/plan-action.md section 8).
 *
 * Prérequis : gtag.js / Google Tag Manager déjà installé sur le site
 * (via le thème, GTM, ou un plugin dédié) et window.dataLayer disponible.
 * Ce plugin ne pose PAS le tag GA4 lui-même — seulement l'événement
 * personnalisé `affiliate_click`.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class GadgetFlow_Tracking_Assets {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	public function enqueue() {
		wp_enqueue_script(
			'gadgetflow-affiliate-tracking',
			GADGETFLOW_TOOLKIT_URL . 'assets/js/ga4-affiliate-tracking.js',
			array(),
			GADGETFLOW_TOOLKIT_VERSION,
			true
		);

		wp_enqueue_style(
			'gadgetflow-offers',
			GADGETFLOW_TOOLKIT_URL . 'assets/css/offers.css',
			array(),
			GADGETFLOW_TOOLKIT_VERSION
		);
	}
}
