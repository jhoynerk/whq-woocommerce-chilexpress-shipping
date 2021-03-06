<?php
if (!defined('ABSPATH')) {
	die();
}

add_action( 'wp_ajax_whq_wcchp_regions_ajax', 'whq_wcchp_regions_ajax' );
add_action( 'wp_ajax_nopriv_whq_wcchp_regions_ajax', 'whq_wcchp_regions_ajax' );
function whq_wcchp_regions_ajax() {
	global $whq_wcchp_default;

	$url    = $whq_wcchp_default['plugin_url'] . 'wsdl/WSDL_GeoReferencia_QA.wsdl';
	$ns     = $whq_wcchp_default['chilexpress_url'] . '/CorpGR/';
	$route  = 'ConsultarRegiones';
	$method = 'reqObtenerRegion';

	wp_send_json( whq_wcchp_call_soap($ns, $url, $route, $method)->respObtenerRegion->Regiones );
}

add_action( 'wp_ajax_whq_wcchp_cities_ajax', 'whq_wcchp_cities_ajax' );
add_action( 'wp_ajax_nopriv_whq_wcchp_cities_ajax', 'whq_wcchp_cities_ajax' );
function whq_wcchp_cities_ajax() {
	global $whq_wcchp_default;

	$url    = $whq_wcchp_default['plugin_url'] . 'wsdl/WSDL_GeoReferencia_QA.wsdl';
	$ns     = $whq_wcchp_default['chilexpress_url'] . '/CorpGR/';
	$route  = 'ConsultarCoberturas';
	$method = 'reqObtenerCobertura';

	$codregion        = (int) absint( $_POST['codregion'] );
	$codtipocobertura = (int) absint( $_POST['codtipocobertura'] );
	$parameters       = [ 'CodRegion'        => $codregion,
					      'CodTipoCobertura' => $codtipocobertura ];

	wp_send_json( whq_wcchp_call_soap($ns, $url, $route, $method, $parameters)->respObtenerCobertura->Coberturas );
}
