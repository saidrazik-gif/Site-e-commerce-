<?php
/**
 * Colonnes de reporting dans la liste admin des produits :
 * permet de voir en un coup d'œil la plateforme et le réseau
 * de chaque fiche sans ouvrir l'édition.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class GadgetFlow_Admin_Columns {

	public function __construct() {
		add_filter( 'manage_edit-product_columns', array( $this, 'add_columns' ) );
		add_action( 'manage_product_posts_custom_column', array( $this, 'render_column' ), 10, 2 );
	}

	public function add_columns( $columns ) {
		$columns['plateforme_source']  = __( 'Plateforme', 'gadgetflow-toolkit' );
		$columns['reseau_affiliation'] = __( 'Réseau', 'gadgetflow-toolkit' );
		return $columns;
	}

	public function render_column( $column, $post_id ) {
		if ( 'plateforme_source' === $column ) {
			$terms = get_the_term_list( $post_id, 'plateforme_source', '', ', ' );
			echo $terms ? wp_kses_post( $terms ) : '—';
		}

		if ( 'reseau_affiliation' === $column ) {
			$terms = get_the_term_list( $post_id, 'reseau_affiliation', '', ', ' );
			echo $terms ? wp_kses_post( $terms ) : '—';
		}
	}
}
