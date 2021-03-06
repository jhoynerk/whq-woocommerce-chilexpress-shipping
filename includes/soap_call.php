<?php
if (!defined('ABSPATH')) {
	die();
}

function whq_wcchp_call_soap($ns, $url, $route, $method, $data = '') {
	global $whq_wcchp_default;

	$soap_login    = WC_WHQ_Chilexpress_Shipping::get_chilexpress_option( 'soap_login' );
	$soap_password = WC_WHQ_Chilexpress_Shipping::get_chilexpress_option( 'soap_password' );

	if( empty($soap_login) ) {
		$soap_login    = $whq_wcchp_default['chilexpress_soap_login'];
	}

	if( empty($soap_password) ) {
		$soap_password = $whq_wcchp_default['chilexpress_soap_pass'];
	}

	try {
		$client_options = array(
			'login'    => $soap_login,
			'password' => $soap_password
		);
		$client = new SoapClient($url, $client_options);

		$time_now = date( 'Y-m-d\TH:i:s.Z\Z', time() );
		$header_body = array(
			'transaccion' => array(
				'fechaHora'            => $time_now,
				'idTransaccionNegocio' => '0',
				'sistema'              => 'TEST',
				'usuario'              => 'TEST'
			)
		);
		$header = new SOAPHeader($ns, 'headerRequest', $header_body);

		$client->__setSoapHeaders($header);
		$result = $client->__soapCall($route, [$route => [$method => $data]]);

		return $result;
	} catch(SoapFault $e) {
		return $e;
	}
}

function whq_wcchp_get_tarificacion($destination, $origin, $weight, $length, $width, $height) {
	global $whq_wcchp_default;

	$ns     = $whq_wcchp_default['chilexpress_url'] . '/TarificaCourier/';
	$url    = $whq_wcchp_default['plugin_url'] . 'wsdl/WSDL_Tarificacion_QA.wsdl';
	$route  = 'TarificarCourier';
	$method = 'reqValorizarCourier';
	$parameters = [ 'CodCoberturaOrigen' => $origin,
	'CodCoberturaDestino'                => $destination,
	'PesoPza'                            => $weight,
	'DimAltoPza'                         => $length,
	'DimAnchoPza'                        => $width,
	'DimLargoPza'                        => $height ];

	return whq_wcchp_call_soap($ns, $url, $route, $method, $parameters);
}
