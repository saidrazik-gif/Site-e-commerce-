<?php
/**
 * Shortcode [gf_offers] : rend le bloc comparatif "Où l'acheter" à partir
 * des offres saisies via GadgetFlow_Offers, une carte par plateforme
 * renseignée. À déposer sur le template Elementor de fiche produit unique
 * via le widget "Shortcode" (cf. docs/plan-action.md section 5).
 *
 * Les boutons CTA portent déjà les attributs attendus par
 * assets/js/ga4-affiliate-tracking.js (classe `gf-affiliate-cta` et
 * data-attributes), donc l'événement GA4 `affiliate_click` fonctionne
 * sans configuration supplémentaire une fois gtag/GTM posé sur le site.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class GadgetFlow_Offers_Shortcode {

	public function __construct() {
		add_shortcode( 'gf_offers', array( $this, 'render' ) );
	}

	public function render( $atts ) {
		$atts = shortcode_atts(
			array(
				'product_id' => get_the_ID(),
			),
			$atts,
			'gf_offers'
		);

		$product_id = (int) $atts['product_id'];
		if ( ! $product_id ) {
			return '';
		}

		$offers = GadgetFlow_Offers::get_offers( $product_id );
		if ( empty( $offers ) ) {
			return '';
		}

		$product_name = get_the_title( $product_id );

		ob_start();
		?>
		<div class="gf-offers">
			<?php foreach ( $offers as $slug => $offer ) : ?>
				<?php
				$platform_label = GadgetFlow_Offers::PLATFORMS[ $slug ] ?? ucfirst( $slug );
				$reseau         = $offer['reseau'] ?? '';
				?>
				<div class="gf-offer-card gf-offer-card--<?php echo esc_attr( $slug ); ?>">
					<span class="gf-offer-badge-platform"><?php echo esc_html( $platform_label ); ?></span>

					<?php if ( ! empty( $offer['badge'] ) ) : ?>
						<span class="gf-offer-badge-highlight"><?php echo esc_html( $offer['badge'] ); ?></span>
					<?php endif; ?>

					<?php if ( ! empty( $offer['prix'] ) ) : ?>
						<div class="gf-offer-price">
							<?php echo esc_html( number_format_i18n( (float) $offer['prix'], 2 ) ); ?>
							<?php echo esc_html( $offer['devise'] ?? 'EUR' ); ?>
						</div>
					<?php endif; ?>

					<a href="<?php echo esc_url( $offer['lien'] ); ?>"
						class="gf-affiliate-cta gf-offer-cta"
						rel="nofollow sponsored noopener"
						target="_blank"
						data-plateforme="<?php echo esc_attr( $slug ); ?>"
						data-produit="<?php echo esc_attr( $product_id ); ?>"
						data-produit-nom="<?php echo esc_attr( $product_name ); ?>"
						data-reseau="<?php echo esc_attr( $reseau ); ?>">
						<?php
						printf(
							/* translators: %s: nom de la plateforme marchande (Amazon, eBay...) */
							esc_html__( 'Acheter chez %s', 'gadgetflow-toolkit' ),
							esc_html( $platform_label )
						);
						?>
					</a>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
		return ob_get_clean();
	}
}
