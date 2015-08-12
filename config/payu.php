<?php
return [
    /*
	|--------------------------------------------------------------------------
	| Production data
	|--------------------------------------------------------------------------
	|
	| 
	|
	*/
    'url'				=> 'https://gateway.payulatam.com/ppp-web-gateway',
    'api_key' 			=> env('PAYU_KEY'),
    'merchant_id' 		=> env('PAYU_MERCHANT_ID'),
    'api_login'			=> env('PAYU_API_LOGIN'),
    'account_id' 		=> env('PAYU_ACCOUNT_ID'),

    'response_url'		=> env('PAYU_RESPONSE_URL', 'http://buscocasa.co/admin/pagos/respuesta'),
    'confirmation_url'	=> env('PAYU_CONFIRMATION_URL', 'http://buscocasa.co/pagos/confirmar'),
    'dispute_url'		=> 'http://buscocasa.co/pagos/disputa',


    /*
	|--------------------------------------------------------------------------
	| Test data
	|--------------------------------------------------------------------------
	|
	| 
	|
	*/
    'test_url' 				=> 'https://stg.gateway.payulatam.com/ppp-web-gateway',
    'test_merchant_id' 		=> '500238',
    'test_api_login'		=> '11959c415b33d0c',
    'test_api_key' 			=> '6u39nqhq8ftd0hlvnjfs66eh8c',
    'test_account_id'		=> '500538',
];