<?php
/**
 * Settings for flat rate shipping.
 *
 * @package WooCommerce\Classes\Shipping
 */

defined( 'ABSPATH' ) || exit;

$settings = array(
	'title'      => array(
		'title'       => __( 'Name', 'woocommerce' ),
		'type'        => 'text',
		'description' => __( 'Your customers will see the name of this shipping method during checkout.', 'woocommerce' ),
		'default'     => __( 'Flat rate', 'woocommerce' ),
		'placeholder' => __( 'e.g. Standard national', 'woocommerce' ),
		'desc_tip'    => true,
	),
	'moto_rate'       => array(
		'title'             => __( 'Moto', 'woocommerce' ),
		'type'              => 'text',
		'class'             => 'wc-shipping-modal-price',
		'placeholder'       => '',
		'description'       => 'Informe o valor do para entregas por Motocicleta',
		'default'           => '0',
		'desc_tip'          => true,
		'sanitize_callback' => array( $this, 'sanitize_moto_rate' ),
	),
	'carro_rate'       => array(
		'title'             => __( 'Carro Pequeno', 'woocommerce' ),
		'type'              => 'text',
		'class'             => 'wc-shipping-modal-price',
		'placeholder'       => '',
		'description'       => 'Informe o valor do para entregas por Carro pequeno',
		'default'           => '0',
		'desc_tip'          => true,
		'sanitize_callback' => array( $this, 'sanitize_carro_rate' ),
	),
	'caminhonete_rate'       => array(
		'title'             => __( 'Caminhonete', 'woocommerce' ),
		'type'              => 'text',
		'class'             => 'wc-shipping-modal-price',
		'placeholder'       => '',
		'description'       => 'Informe o valor do para entregas por Caminhonete',
		'default'           => '0',
		'desc_tip'          => true,
		'sanitize_callback' => array( $this, 'sanitize_caminhonete_rate' ),
	),
	'caminhao_rate'       => array(
		'title'             => __( 'Caminhão', 'woocommerce' ),
		'type'              => 'text',
		'class'             => 'wc-shipping-modal-price',
		'placeholder'       => '',
		'description'       => 'Informe o valor do para entregas por Caminhão',
		'default'           => '0',
		'desc_tip'          => true,
		'sanitize_callback' => array( $this, 'sanitize_caminhao_rate' ),
	),
);

return $settings;
