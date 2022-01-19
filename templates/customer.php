<?php 

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
 
$soapWsdl = 'http://captivixnav2017.centralus.cloudapp.azure.com:7047/NAV/WS/CRONUS%20USA,%20Inc./Page/MiniCustomerList';
try {
    $options = [
        'soap_version' => SOAP_1_1,
        'connection_timeout' => 120,
        'login' => 'admin',
        'password' => 'P@ssword1',
    ];
 
    $client = new SoapClient($soapWsdl, $options);
    
    $result = $client->ReadMultiple(['filter' => [], 'setSize' => 0]);

    echo '<pre>';
    print_r($result);
    echo '</pre>';
} catch (Exception $e) {
    echo $e->getMessage();
}