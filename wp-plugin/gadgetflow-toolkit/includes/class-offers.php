<?php
/**
 * Saisie manuelle des offres marchand (Amazon / eBay / AliExpress / Autre)
 * sur chaque fiche produit, en alternative gratuite à un module d'import
 * automatique type Content Egg (cf. docs/plan-action.md section 1.2 : la
 * migration vers un plugin d'import automatique reste possible plus tard,
 * les taxonomies et le rendu front ne changent pas).
 *
 * Une "offre" = un lien d'affiliation vers une plateforme donnée, avec son
 * prix indicatif, sa devise, son réseau d'affiliation et un badge optionnel
 * ("Meilleur prix", "Livraison rapide"...). Stockée en un seul champ meta
 * `_gf_offers` (tableau associatif indexé par plateforme).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class GadgetFlow_Offers {

	const META_KEY = '_gf_offers';

	/**
	 * Plateformes gérées, dans l'ordre d'affichage. Doit rester cohérent
	 * avec les termes seedés par GadgetFlow_Taxonomies::seed_default_terms().
	 */
	const PLATFORMS = array(
		'amazon'     => 'Amazon',
		'ebay'       => 'eBay',
		'aliexpress' => 'AliExpress',
		'autre'      => 'Autre',
	);

	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'register_meta_box' ) );
		add_action( 'save_post_product', array( $this, 'save' ) );
	}

	public function register_meta_box() {
		add_meta_box(
			'gadgetflow_offers',
			__( 'Où l\'acheter — offres par plateforme', 'gadgetflow-toolkit' ),
			array( $this, 'render_meta_box' ),
			'product',
			'normal',
			'high'
		);
	}

	public function render_meta_box( $post ) {
		wp_nonce_field( 'gadgetflow_offers_save', 'gadgetflow_offers_nonce' );

		$offers = self::get_offers( $post->ID );
		?>
		<table class="widefat gadgetflow-offers-table">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Plateforme', 'gadgetflow-toolkit' ); ?></th>
					<th><?php esc_html_e( 'Prix', 'gadgetflow-toolkit' ); ?></th>
					<th><?php esc_html_e( 'Devise', 'gadgetflow-toolkit' ); ?></th>
					<th><?php esc_html_e( 'Lien d\'affiliation (tracké)', 'gadgetflow-toolkit' ); ?></th>
					<th><?php esc_html_e( 'Réseau', 'gadgetflow-toolkit' ); ?></th>
					<th><?php esc_html_e( 'Badge (optionnel)', 'gadgetflow-toolkit' ); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ( self::PLATFORMS as $slug => $label ) : ?>
				<?php $offer = isset( $offers[ $slug ] ) ? $offers[ $slug ] : array(); ?>
				<tr>
					<td><strong><?php echo esc_html( $label ); ?></strong></td>
					<td>
						<input type="number" step="0.01" min="0" style="width:90px"
							name="gadgetflow_offers[<?php echo esc_attr( $slug ); ?>][prix]"
							value="<?php echo esc_attr( $offer['prix'] ?? '' ); ?>" />
					</td>
					<td>
						<input type="text" style="width:60px" maxlength="3"
							name="gadgetflow_offers[<?php echo esc_attr( $slug ); ?>][devise]"
							value="<?php echo esc_attr( $offer['devise'] ?? 'EUR' ); ?>" />
					</td>
					<td>
						<input type="url" style="width:100%"
							placeholder="https://..."
							name="gadgetflow_offers[<?php echo esc_attr( $slug ); ?>][lien]"
							value="<?php echo esc_attr( $offer['lien'] ?? '' ); ?>" />
					</td>
					<td>
						<input type="text" style="width:140px"
							placeholder="ex. Amazon Associates"
							name="gadgetflow_offers[<?php echo esc_attr( $slug ); ?>][reseau]"
							value="<?php echo esc_attr( $offer['reseau'] ?? '' ); ?>" />
					</td>
					<td>
						<input type="text" style="width:140px"
							placeholder="ex. Meilleur prix"
							name="gadgetflow_offers[<?php echo esc_attr( $slug ); ?>][badge]"
							value="<?php echo esc_attr( $offer['badge'] ?? '' ); ?>" />
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<p class="description">
			<?php esc_html_e( 'Laisse le lien vide pour une plateforme sans offre : elle n\'apparaîtra pas dans le bloc comparatif. Colle ici le lien déjà tracké (Amazon Associates, EPN, ou lien Admitad/Awin pour AliExpress), avec rel="nofollow sponsored" appliqué automatiquement à l\'affichage.', 'gadgetflow-toolkit' ); ?>
		</p>
		<?php
	}

	public function save( $post_id ) {
		if ( ! isset( $_POST['gadgetflow_offers_nonce'] ) ||
			! wp_verify_nonce( $_POST['gadgetflow_offers_nonce'], 'gadgetflow_offers_save' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$submitted = isset( $_POST['gadgetflow_offers'] ) ? wp_unslash( $_POST['gadgetflow_offers'] ) : array();
		$offers    = array();

		foreach ( self::PLATFORMS as $slug => $label ) {
			$row = isset( $submitted[ $slug ] ) ? $submitted[ $slug ] : array();
			$lien = isset( $row['lien'] ) ? esc_url_raw( trim( $row['lien'] ) ) : '';

			if ( '' === $lien ) {
				continue;
			}

			$offers[ $slug ] = array(
				'prix'   => isset( $row['prix'] ) ? sanitize_text_field( $row['prix'] ) : '',
				'devise' => isset( $row['devise'] ) && '' !== $row['devise'] ? sanitize_text_field( $row['devise'] ) : 'EUR',
				'lien'   => $lien,
				'reseau' => isset( $row['reseau'] ) ? sanitize_text_field( $row['reseau'] ) : '',
				'badge'  => isset( $row['badge'] ) ? sanitize_text_field( $row['badge'] ) : '',
			);
		}

		update_post_meta( $post_id, self::META_KEY, $offers );

		$this->sync_best_price_meta( $post_id, $offers );
	}

	/**
	 * Dérive `_gf_best_price` / `_gf_currency` (utilisés par le Schema.org
	 * Product, cf. class-schema.php) à partir du prix le plus bas parmi les
	 * offres saisies, pour éviter la double saisie.
	 */
	private function sync_best_price_meta( $post_id, $offers ) {
		$prices = array();

		foreach ( $offers as $offer ) {
			if ( '' !== $offer['prix'] && is_numeric( $offer['prix'] ) ) {
				$prices[] = array(
					'prix'   => (float) $offer['prix'],
					'devise' => $offer['devise'],
				);
			}
		}

		if ( empty( $prices ) ) {
			delete_post_meta( $post_id, '_gf_best_price' );
			delete_post_meta( $post_id, '_gf_currency' );
			return;
		}

		usort(
			$prices,
			function ( $a, $b ) {
				return $a['prix'] <=> $b['prix'];
			}
		);

		update_post_meta( $post_id, '_gf_best_price', $prices[0]['prix'] );
		update_post_meta( $post_id, '_gf_currency', $prices[0]['devise'] );
	}

	/**
	 * @return array<string, array{prix:string, devise:string, lien:string, reseau:string, badge:string}>
	 */
	public static function get_offers( $post_id ) {
		$offers = get_post_meta( $post_id, self::META_KEY, true );
		return is_array( $offers ) ? $offers : array();
	}
}
