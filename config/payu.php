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
    'api_key' 			=> 'L9vjXNr5rys1M3UPsVp6n4CHTd',
    'merchant_id' 		=> '536780',
    'api_login'			=> '41UfK63y9Z0tCb5',
    'account_id' 		=> '538794',

    'response_url'		=> 'http://buscocasa.co/admin/pagos/respuesta',
    'confirmation_url'	=> 'http://buscocasa.co/pagos/confirmar',
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