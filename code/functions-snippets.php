<?php
/**
 * Snippets à intégrer dans un plugin de fonctionnalités dédié
 * (ou le functions.php d'un thème enfant Hello Elementor).
 * Ne pas coller directement dans le thème parent : il sera écrasé
 * à chaque mise à jour.
 *
 * Contenu :
 * 1. Taxonomies personnalisées (plateforme_source, reseau_affiliation,
 *    public_cible, type_financement).
 * 2. Colonne "Plateforme source" dans la liste admin des produits.
 * 3. Helper Schema.org (Product + AggregateRating) pour la fiche produit.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. Taxonomies personnalisées.
 */
add_action( 'init', 'gf_register_custom_taxonomies' );
function gf_register_custom_taxonomies() {

	register_taxonomy(
		'plateforme_source',
		'product',
		array(
			'label'             => 'Plateforme source',
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
			'label'             => 'Réseau d\'affiliation',
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
			'label'             => 'Public cible',
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'public'            => true,
			'rewrite'           => array( 'slug' => 'pour' ),
		)
	);

	register_taxonomy(
		'type_financement',
		'product',
		array(
			'label'             => 'Type de financement',
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'public'            => true,
			'rewrite'           => array( 'slug' => 'financement' ),
		)
	);
}

/**
 * Termes par défaut à créer une seule fois après activation
 * (à lancer manuellement une fois, par ex. via WP-CLI ou un appel ponctuel).
 */
function gf_seed_default_terms() {
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
}

/**
 * 2. Colonne "Plateforme source" dans la liste admin des produits,
 * pour un reporting visuel rapide sans ouvrir chaque fiche.
 */
add_filter( 'manage_edit-product_columns', 'gf_add_platform_column' );
function gf_add_platform_column( $columns ) {
	$columns['plateforme_source'] = 'Plateforme';
	$columns['reseau_affiliation'] = 'Réseau';
	return $columns;
}

add_action( 'manage_product_posts_custom_column', 'gf_render_platform_column', 10, 2 );
function gf_render_platform_column( $column, $post_id ) {
	if ( 'plateforme_source' === $column ) {
		$terms = get_the_term_list( $post_id, 'plateforme_source', '', ', ' );
		echo $terms ? wp_kses_post( $terms ) : '—';
	}
	if ( 'reseau_affiliation' === $column ) {
		$terms = get_the_term_list( $post_id, 'reseau_affiliation', '', ', ' );
		echo $terms ? wp_kses_post( $terms ) : '—';
	}
}

/**
 * 3. Schema.org Product + AggregateRating.
 * À appeler dans le template de fiche produit unique (hook Elementor
 * "single product" ou simplement wp_head sur is_singular('product')).
 *
 * Les valeurs de prix/note doivent provenir des champs générés par
 * le plugin d'affiliation (Content Egg) plutôt que d'un prix WooCommerce
 * natif, pour rester synchronisées avec les offres réelles.
 */
add_action( 'wp_head', 'gf_output_product_schema' );
function gf_output_product_schema() {
	if ( ! is_singular( 'product' ) ) {
		return;
	}

	global $post;

	$rating_value = get_post_meta( $post->ID, '_gf_rating_value', true );
	$rating_count = get_post_meta( $post->ID, '_gf_rating_count', true );
	$best_price   = get_post_meta( $post->ID, '_gf_best_price', true );
	$currency     = get_post_meta( $post->ID, '_gf_currency', true ) ?: 'EUR';
	$image        = get_the_post_thumbnail_url( $post->ID, 'full' );

	$schema = array(
		'@context'    => 'https://schema.org/',
		'@type'       => 'Product',
		'name'        => get_the_title( $post ),
		'description' => wp_strip_all_tags( get_the_excerpt( $post ) ),
	);

	if ( $image ) {
		$schema['image'] = $image;
	}

	if ( $best_price ) {
		$schema['offers'] = array(
			'@type'         => 'AggregateOffer',
			'priceCurrency' => $currency,
			'lowPrice'      => $best_price,
			'url'           => get_permalink( $post ),
		);
	}

	if ( $rating_value && $rating_count ) {
		$schema['aggregateRating'] = array(
			'@type'       => 'AggregateRating',
			'ratingValue' => $rating_value,
			'reviewCount' => $rating_count,
		);
	}

	echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n";
}
