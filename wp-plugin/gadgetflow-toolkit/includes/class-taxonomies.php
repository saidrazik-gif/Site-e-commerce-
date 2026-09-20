<?php
/**
 * Taxonomies personnalisées du catalogue produits.
 *
 * `plateforme_source` est la taxonomie essentielle du projet : elle permet
 * le filtrage front (badges, archives filtrées) et le reporting par
 * plateforme marchande (cf. docs/plan-action.md section 4).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class GadgetFlow_Taxonomies {

	public function __construct() {
		add_action( 'init', array( $this, 'register' ) );
	}

	public function register() {

		register_taxonomy(
			'plateforme_source',
			'product',
			array(
				'label'             => __( 'Plateforme source', 'gadgetflow-toolkit' ),
				'hierarchical'      => true,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'public'            => true,
				'rewrite'           => array( 'slug' => 'plateforme' ),
			)
		);

		register_taxonomy(
			'reseau_affiliation',
			'product',
			array(
				'label'             => __( "Réseau d'affiliation", 'gadgetflow-toolkit' ),
				'hierarchical'      => true,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'public'            => false,
			)
		);

		register_taxonomy(
			'public_cible',
			'product',
			array(
				'label'        => __( 'Public cible', 'gadgetflow-toolkit' ),
				'hierarchical' => true,
				'show_in_rest' => true,
				'public'       => true,
				'rewrite'      => array( 'slug' => 'pour' ),
			)
		);

		register_taxonomy(
			'type_financement',
			'product',
			array(
				'label'        => __( 'Type de financement', 'gadgetflow-toolkit' ),
				'hierarchical' => true,
				'show_in_rest' => true,
				'public'       => true,
				'rewrite'      => array( 'slug' => 'financement' ),
			)
		);
	}

	/**
	 * Termes de départ, à créer une seule fois (appelé à l'activation).
	 * Idempotent : ne recrée pas un terme déjà existant.
	 */
	public function seed_default_terms() {
		$platforms = array( 'Amazon', 'eBay', 'AliExpress', 'Autre' );
		foreach ( $platforms as $term ) {
			if ( ! term_exists( $term, 'plateforme_source' ) ) {
				wp_insert_term( $term, 'plateforme_source' );
			}
		}

		$networks = array( 'Amazon Associates', 'EPN', 'Admitad', 'Awin', 'CJ Affiliate' );
		foreach ( $networks as $term ) {
			if ( ! term_exists( $term, 'reseau_affiliation' ) ) {
				wp_insert_term( $term, 'reseau_affiliation' );
			}
		}

		$financements = array( 'Kickstarter', 'Indiegogo', 'Aucun (produit standard)' );
		foreach ( $financements as $term ) {
			if ( ! term_exists( $term, 'type_financement' ) ) {
				wp_insert_term( $term, 'type_financement' );
			}
		}
	}
}
