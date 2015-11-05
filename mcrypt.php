<?php
$key='707E9506F4FC980170C16F053490B60';
$l='8C5477A6CF099B8524A0C350B1A3C2CB70D741C04D426B2968F50962CDB4';
print base64_decode('jFR3ps8i2hasoqnidH5SZVY=')."<br>";
$td = mcrypt_module_open('cast-256', '', 'cfb', '');
$size=mcrypt_enc_get_key_size($td);
$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
mcrypt_generic_init($td, $key, $iv);
$decrypted = mdecrypt_generic($td, base64_decode('jFR3ps8i2hasoqnidH5SZVY='));
print $decrypted
?>