<?php
/*
Plugin Name: Frete Fixo baseado em veículo
Description: Mostra um frete fixo pora veículo com base em peso e volume.
Version: 1.0
Author: Murilo Gama
*/

if (!defined('ABSPATH')) {
	exit;
}

add_action('woocommerce_shipping_init', 'custom_shipping_method');

function custom_shipping_method()
{
	/**
	 * Flat Rate Shipping Method.
	 *
	 * @version 2.6.0
	 * @package WooCommerce\Classes\Shipping
	 */

	defined('ABSPATH') || exit;

	/**
	 * WC_Shipping_Flat_Rate class.
	 */
	class WC_Shipping_Transportation_Flat_Rate extends WC_Shipping_Method
	{
		/**
		 * Constructor.
		 *
		 * @param int $instance_id Shipping method instance ID.
		 */
		public function __construct($instance_id = 0)
		{
			$this->id                 = 'transportation_flat_rate';
			$this->instance_id        = absint($instance_id);
			$this->method_title       = __('Frete por Tipo de Veículo', 'woocommerce');
			$this->method_description = __('Implementa frete fixo por tipos de Veículo', 'woocommerce');
			$this->supports           = array(
				'shipping-zones',
				'instance-settings',
				'instance-settings-modal',
			);
			$this->init();

			add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
		}

		/**
		 * Init user set variables.
		 */
		public function init()
		{
			$this->instance_form_fields = include __DIR__ . '/includes/settings-flat-rate.php';
			$this->title                = $this->get_option('title');
			$this->moto_rate            = $this->get_option('moto_rate');
			$this->carro_rate 					= $this->get_option('carro_rate');
			$this->caminhonete_rate 		= $this->get_option('caminhonete_rate');
			$this->caminhao_rate 				= $this->get_option('caminhao_rate');
		}

		/**
		 * Calculate the shipping costs.
		 *
		 * @param array $package Package of items from cart.
		 */
		public function calculate_shipping($package = array())
		{
			$weight = WC()->cart->get_cart_contents_weight();
			$volume = 0;

			// Calculando o volume em metros cúbicos
			foreach ($package['contents'] as $item) {
				$product = $item['data'];
				$product_volume = ($product->get_length() * $product->get_width() * $product->get_height()) / 1000000; // Convertendo de cm³ para m³
				$volume += $product_volume * $item['quantity'];
			}

			// Array com as configurações dos tipos de transporte
			$transport_modes = [
				[
					'type' => 'Moto',
					'volume_limit' => 1,
					'weight_limit' => 5,
					'rate' => $this->moto_rate,
				],
				[
					'type' => 'Carro',
					'volume_limit' => 2,
					'weight_limit' => 10,
					'rate' => $this->carro_rate,
				],
				[
					'type' => 'Caminhonete',
					'volume_limit' => 5,
					'weight_limit' => 30,
					'rate' => $this->caminhonete_rate,
				],
				[
					'type' => 'Caminhão',
					'volume_limit' => 10,
					'weight_limit' => 50,
					'rate' => $this->caminhao_rate,
				],
			];

			// Determinando o custo de frete com base nas condições de transporte
			$shipping_cost = 0;
			$shipping_type = '';

			foreach ($transport_modes as $mode) {
				if ($volume <= $mode['volume_limit'] && $weight <= $mode['weight_limit'] && $mode['rate'] > 0) {
					$shipping_cost = $mode['rate'];
					$shipping_type = $mode['type'];
					break;
				}
			}

			// Adicionando a taxa ao método de envio
			if ($shipping_cost > 0) {
				$rate = array(
					'id' => $this->id,
					'label' => $this->title . ': ' . $shipping_type,
					'cost' => $shipping_cost,
					'calc_tax' => 'per_item'
				);

				$this->add_rate($rate);
			}
		}
	}
}

add_filter('woocommerce_shipping_methods', 'add_custom_shipping_method');

function add_custom_shipping_method($methods)
{
	$methods['transportation_flat_rate'] = 'WC_Shipping_Transportation_Flat_Rate';
	return $methods;
}
