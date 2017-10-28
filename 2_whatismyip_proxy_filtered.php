<?php 
// returns first forwarded ip match it finds
function forwarded_ip(){
	// the forwarded ip value may me in one of theses variables namesaccording to the proxy itself, so we are goinng to check which of them is set to a value and then grap the list of ip values it contains and return the first one
	
	//for test without using $_SERVER and using fake $server instead
	//$server = array(
	//	'HTTP_X_FOWARDED_FOR' => '123.123.123,0.0.0.0, 123.123.123.123' );
	$keys = array(
		'HTTP_X_FOWARDED_FOR',
		'HTTP_X_FORWARDED',
		'HTTP_FORWARDED_FOR',
		'HTTP_FORWARDED',
		'HTTP_CLIENT_IP',
		'HTTP_X_CLUSTER_CLIENT_IP',
		);
	foreach($keys as $key) {
		if (isset($_SERVER[$key])){
			$ip_array = explode(',',$_SERVER[$key]);
			foreach($ip_array as $ip) {
				$ip = trim($ip);
				if (validate_ip($ip)) {
				return $ip;	
				}
			}
		}
	}
	return '';
}	

function validate_ip($ip){
	if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
		return false;
	}else {
		return true;
	}
}

$remote_ip = $_SERVER['REMOTE_ADDR'];
$forwarded_ip = forwarded_ip();

 ?>

 Remote IP Address: <?php echo $remote_ip;?>
 <br />
 <br />

 <?php if ($forwarded_ip != ''){ ?>
	Forwarded For: <?php echo $forwarded_ip; ?>
	<br />
	<br />
 <?php } ?>

