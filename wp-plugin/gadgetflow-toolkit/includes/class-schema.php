<?php
/**
 * Données structurées Schema.org (Product + AggregateOffer + AggregateRating)
 * sur la fiche produit unique.
 *
 * Les valeurs (prix, note) sont lues depuis des champs meta `_gf_*` pour
 * rester découplées du plugin d'affiliation. Une fois Content Egg installé
 * et configuré, mappe ses propres meta keys vers ces champs `_gf_*`
 * (via un petit hook `save_post` côté Content Egg, ou en changeant
 * directement les `get_post_meta()` ci-dessous pour pointer vers les
 * meta natives de Content Egg) — cf. docs/plan-action.md section 2.2.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class GadgetFlow_Schema {

	public function __construct() {
		add_action( 'wp_head', array( $this, 'output_product_schema' ) );
	}

	public function output_product_schema() {
		if ( ! is_singular( 'product' ) ) {
			return;
		}

		global $post;

		$rating_value = get_post_meta( $post->ID, '_gf_rating_value', true );
		$rating_count = get_post_meta( $post->ID, '_gf_rating_count', true );
		$best_price   = get_post_meta( $post->ID, '_gf_best_price', true );
		$currency     = get_post_meta( $post->ID, '_gf_currency', true );
		$currency     = $currency ? $currency : 'EUR';
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
}
