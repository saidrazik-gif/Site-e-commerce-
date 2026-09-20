<?php
/**
 * Plugin Name:       GadgetFlow Toolkit
 * Description:       Fonctionnalités custom pour le site d'affiliation multi-réseaux : taxonomies (plateforme source, réseau d'affiliation...), colonnes de reporting admin, saisie manuelle des offres marchand + bloc comparatif [gf_offers], données structurées Schema.org et tracking GA4 des clics sortants.
 * Version:           1.1.0
 * Requires PHP:      7.4
 * Requires Plugins:  woocommerce
 * Text Domain:       gadgetflow-toolkit
 *
 * Gère nativement la saisie manuelle des offres par plateforme (Amazon/eBay/
 * AliExpress/Autre) via le shortcode [gf_offers] — une alternative gratuite
 * à un plugin d'import automatique type Content Egg (cf. docs/plan-action.md
 * section 1.2). Migration vers un import automatique possible plus tard sans
 * changer les taxonomies ni le rendu front.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'GADGETFLOW_TOOLKIT_VERSION', '1.1.0' );
define( 'GADGETFLOW_TOOLKIT_PATH', plugin_dir_path( __FILE__ ) );
define( 'GADGETFLOW_TOOLKIT_URL', plugin_dir_url( __FILE__ ) );

require_once GADGETFLOW_TOOLKIT_PATH . 'includes/class-taxonomies.php';
require_once GADGETFLOW_TOOLKIT_PATH . 'includes/class-admin-columns.php';
require_once GADGETFLOW_TOOLKIT_PATH . 'includes/class-offers.php';
require_once GADGETFLOW_TOOLKIT_PATH . 'includes/class-offers-shortcode.php';
require_once GADGETFLOW_TOOLKIT_PATH . 'includes/class-schema.php';
require_once GADGETFLOW_TOOLKIT_PATH . 'includes/class-tracking-assets.php';

/**
 * Point d'entrée : instancie chaque module.
 */
function gadgetflow_toolkit_init() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		add_action(
			'admin_notices',
			function () {
				echo '<div class="notice notice-error"><p>GadgetFlow Toolkit nécessite WooCommerce (mode catalogue).</p></div>';
			}
		);
		return;
	}

	new GadgetFlow_Taxonomies();
	new GadgetFlow_Admin_Columns();
	new GadgetFlow_Offers();
	new GadgetFlow_Offers_Shortcode();
	new GadgetFlow_Schema();
	new GadgetFlow_Tracking_Assets();
}
add_action( 'plugins_loaded', 'gadgetflow_toolkit_init' );

/**
 * À l'activation : crée les taxonomies (nécessaire avant d'ajouter les
 * termes) puis seed les termes par défaut une seule fois.
 */
function gadgetflow_toolkit_activate() {
	require_once GADGETFLOW_TOOLKIT_PATH . 'includes/class-taxonomies.php';
	$taxonomies = new GadgetFlow_Taxonomies();
	$taxonomies->register();
	$taxonomies->seed_default_terms();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'gadgetflow_toolkit_activate' );

function gadgetflow_toolkit_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'gadgetflow_toolkit_deactivate' );
