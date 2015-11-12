<?php
define("CLIENTAREA",true);
//define("FORCESSL",true); // Uncomment to force the page to use https://
require("../../../init.php");
$ca = new WHMCS_ClientArea();
require(dirname(__FILE__) . '/api.class.php');
if(!empty($_GET['id']) && !empty($_GET['apikey']) && isset($_SESSION['adminid']) && !empty($_SESSION['adminid']) && isset($_SESSION['adminpw']) && !empty($_SESSION['adminpw'])){
	$launch_ipmi		= new BudgetVM_Api(base64_decode($_GET['apikey']));
	$var->post->service = base64_decode($_GET['id']);
	$return				= $launch_ipmi->call("v2", "device", "console", "get", $var);
	if($return->success == true){
		header('Content-Type: application/x-java-jnlp-file', true);
		header('Content-Disposition: attachment; filename="launch.jnlp"', true);
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT", true);
		header('Accept-Ranges: bytes', true);
		header("Cache-control: private", true);
		header('Pragma: private', true);
		echo base64_decode($return->result);
	}
}
?>