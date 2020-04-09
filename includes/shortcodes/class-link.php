<?php

namespace WC_Quick_Buy\Shortcodes;

use VSP\Modules\Shortcode;
use WC_Product;
use WC_Quick_Buy\Helper;
use WC_Quick_Buy\URL_Generator;

if ( ! class_exists( '\WC_Quick_Buy\Shortcodes\Link' ) ) {
	/**
	 * Class Button
	 *
	 * @package WC_Quick_Buy\Shortcodes
	 * @author Varun Sridharan <varunsridharan23@gmail.com>
	 */
	final class Link extends Shortcode {
		/**
		 * Registers Shortcode Name.
		 *
		 * @var string
		 */
		protected $shortcode_name = 'wc_quick_buy_link';

		/**
		 * Returns Default Args.
		 *
		 * @return array
		 */
		protected function defaults() {
			return array(
				'product' => false,
				'qty'     => Helper::option( 'quantity', 1 ),
			);
		}

		/**
		 * Generates Output.
		 *
		 * @return bool|mixed|string
		 */
		protected function output() {
			global $product;
			$shortcode_product = ( empty( $this->options['product'] ) ) ? $product : wc_get_product( $this->options['product'] );

			if ( $shortcode_product instanceof WC_Product && method_exists( $shortcode_product, 'is_in_stock' ) ) {
				if ( $shortcode_product->is_in_stock() ) {
					$quick_buy_link_product_types = Helper::option( 'enabled_product_types' );
					$quick_buy_link_product_types = ( ! is_array( $quick_buy_link_product_types ) ) ? array( 'simple' ) : $quick_buy_link_product_types;

					/* @var \WC_Product $product */
					if ( in_array( $shortcode_product->get_type(), $quick_buy_link_product_types, true ) ) {
						$instance = new URL_Generator( array(
							'product' => $shortcode_product->get_id(),
							'qty'     => $this->option( 'qty' ),
							'seo'     => true,
						) );
						return $instance->html();
					}
				}
			}

			return '';
		}
	}
}