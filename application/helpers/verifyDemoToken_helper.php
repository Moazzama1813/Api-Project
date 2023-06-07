<?php

if(!function_exists('verifyDemoToken')){
	function verifyDemoToken($token){
		$jwt = new JWT();
		$JwtSecret="MyloginSecret";
		$verification=$jwt->decode($token,$JwtSecret,'HS256');
		$verification_json=$jwt->jsonEncode($verification);
		return $verification_json;

	}
}





?>
 