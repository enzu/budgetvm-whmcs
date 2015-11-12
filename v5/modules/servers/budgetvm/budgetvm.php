<?php
require(dirname(__FILE__) . '/api.class.php');
function budgetvm_ConfigOptions() {

	# Should return an array of the module options for each product - maximum of 24

    $configarray = array(
	 "Reinstall" => array( "Type" => "yesno", "Description" => "Tick to allow clients to reinstall their system." ),
	 "Network" => array( "Type" => "yesno", "Description" => "Tick to allow clients to view their network graphs." ),
	);

	return $configarray;

}

function budgetvm_CreateAccount($params) {

    # ** The variables listed below are passed into all module functions **

    $serviceid = $params["serviceid"]; # Unique ID of the product/service in the WHMCS Database
    $pid = $params["pid"]; # Product/Service ID
    $producttype = $params["producttype"]; # Product Type: hostingaccount, reselleraccount, server or other
    $domain = $params["domain"];
	$username = $params["username"];
	$password = $params["password"];
    $clientsdetails = $params["clientsdetails"]; # Array of clients details - firstname, lastname, email, country, etc...
    $customfields = $params["customfields"]; # Array of custom field values for the product
    $configoptions = $params["configoptions"]; # Array of configurable option values for the product

	if($params["server"] == true){
    # Product module option settings from ConfigOptions array above
		$api->host 			= $params["serverip"];
		$api->client 		= $params["serverusername"];
		$api->key 			= $params["serverpassword"];
		$api->reinstall 	= $params["configoption3"];
		$api->network		= $params["configoption4"];
		
		$server->hostname	= $params["domain"];
		$server->username	= $params["username"];
		$server->password	= $params["password"];
		$server->client		= $params["clientsdetails"]["userid"];
		$server->service	= $params["serviceid"];
		// select an operating system so that we can fire off a pxe install... or if they want to use an iso, let them mount it then enter their password...
		# Code to perform action goes here...
		/*
		 * Submit the order to budgetvm automatically?
		*/
		
		/*
		 * Update the System Details
		 */
		if(isset($params['customfields']['BudgetVM Service ID']) && !empty($params['customfields']['BudgetVM Service ID'])){
			if ($successful) {
				$result = "success";
			} else {
				$result = "Error Message Goes Here...";
			}
		}else{
			// Please set the Service ID before we try to fetch the data.
			$result = "Please set the BudgetVM Service ID before we try to fetch the data.";
		}
	}else{
		$result = "Missing Server Configuration";
	}
	return $result;

}

function budgetvm_TerminateAccount($params) {
	return "success";
	# Code to perform action goes here...
	// if dedicated
		// should fire off a pxe disk wipe to clean the system, however they may not like that?
		
	// if vps
		// no idea?
    if ($successful) {
		$result = "success";
	} else {
		$result = "Error Message Goes Here...";
	}
	return $result;

}

function budgetvm_SuspendAccount($params) {
	$action 				= new BudgetVM_Api($params['serverpassword']);
	$info->post->service 	= $params['customfields']['BudgetVM Service ID'];
	$action					= $action->call("v2", "network", "port", "delete", $info);
	
	if($action->success == true){
		return "success";
	}else{
		return $action->result;
	}
}

function budgetvm_UnsuspendAccount($params) {
	$action 				= new BudgetVM_Api($params['serverpassword']);
	$info->post->service 	= $params['customfields']['BudgetVM Service ID'];
	$action					= $action->call("v2", "network", "port", "put", $info);
	
	if($action->success == true){
		return "success";
	}else{
		return $action->result;
	}
}

function budgetvm_ChangePassword($params) {

	# Code to perform action goes here...

    if ($successful) {
		$result = "success";
	} else {
		$result = "Error Message Goes Here...";
	}
	return $result;

}

function budgetvm_ChangePackage($params) {

	# Code to perform action goes here...

    if ($successful) {
		$result = "success";
	} else {
		$result = "Error Message Goes Here...";
	}
	return $result;

}

function budgetvm_ClientArea($params) {
	$type 					= new BudgetVM_Api($params['serverpassword']);
	$info->post->service 	= $params['customfields']['BudgetVM Service ID'];
	$type					= $type->call("v2", "device", "type", "get", $info);
	
	if($type->success == true){
		$status = new BudgetVM_Api($params['serverpassword']);
		$var->post->service    = $params['customfields']['BudgetVM Service ID'];
		$status = $status->call("v2", "device", "power", "get", $var);
		if($type->result == "dedicated"){
		    # Output can be returned like this, or defined via a clientarea.tpl template file (see docs for more info)
			$device = new BudgetVM_Api($params['serverpassword']);
			$var->post->service    = $params['customfields']['BudgetVM Service ID'];
			$device = $device->call("v2", "device", "hardware", "get", $var);

			$netblocks = new BudgetVM_Api($params['serverpassword']);
			$var->post->service    = $params['customfields']['BudgetVM Service ID'];
			$netblocks = $netblocks->call("v2", "network", "netblock", "get", $var);
			$code = '
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="50%" style="text-align: center;"><input type="button" style="width: 150px" class="searchinput" value="Manage Reverse DNS" onClick="window.location=\'clientarea.php?action=productdetails&id=' . $params[serviceid] . '&modop=custom&a=reverse\'" /></td>
					<td width="50%" style="text-align: center;"><input type="button" style="width: 150px" class="searchinput" value="Network Graphs" onClick="window.location=\'clientarea.php?action=productdetails&id=' . $params[serviceid] . '&modop=custom&a=network\'" /></td>
				</tr>
				<tr>
					<td width="50%" style="text-align: center;"><input type="button" style="width: 135px" class="searchinput" value="Power Management" onClick="window.location=\'clientarea.php?action=productdetails&id=' . $params[serviceid] . '&modop=custom&a=power\'" /></td>
					<td width="50%" style="text-align: center;"><input type="button" style="width: 135px" class="searchinput" value="Reinstall Server" onClick="window.location=\'clientarea.php?action=productdetails&id=' . $params[serviceid] . '&modop=custom&a=reinstall\'" /></td>
				</tr>
			</table>
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="100%" style="text-align: center;"><input type="button" style="width: 200px;" class="searchinput" value="Out of Band Management (IPMI)" onClick="window.location=\'clientarea.php?action=productdetails&id=' . $params[serviceid] . '&modop=custom&a=manage\'" /></td>
				</tr>
			</table>';
			$code .= '<h3>System Information</h3>';
			if($status->success == true && $status->results > 0){
				$code .= '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
				$code .= '<tr>';
				$code .= '<td width="150" class="fieldarea" style="text-align: left;">Power Status</td>';
				if($status->result == "on"){
					$code .= '<td style="text-align: left;"><span class="label active">' . ucfirst($status->result) . '</span></td>';
				}else{
					$code .= '<td style="text-align: left;"><span class="label terminated">' . ucfirst($status->result) . '</span></td>';
				}
				$code .= '</tr>';
				$code .= '</table>';
			}
			if($device->success == true && $device->results > 0){
				$code .= '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
				foreach($device->result->parts as $type=>$data){
					foreach($data as $k=>$v){
						$code .= '<tr>';
						$code .= '<td width="150" class="fieldarea" style="text-align: left;">' . $type . ' ' . ($k + 1) . '</td>';
						$code .= '<td style="text-align: left;">' . $v . '</td>';
						$code .= '</tr>';
					}
				}
				$code .= '</table>';
			}
			if($device->success == true && $device->results > 0){
				$code .= '<h3>Network Ports</h3>';
				$code .= '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
				foreach($device->result->ports as $switch=>$switchData){
					foreach($switchData as $port=>$data){
						$code .= '<tr>';
						$code .= '	<td style="text-align: left;">' . $switch . ' - ' . $port . '</td>';
						$code .= '	<td style="text-align: left;vertical-align:middle; line-height: 28px;">';
						if($data->ifAdminStatus == 'Up' && $data->ifOperStatus == 'Up'){
							$code .= ' <span class="label active">Port Online - ' . $data->ifSpeed . '</span> ';
						}else{
							$code .= ' <span class="label terminated">Port Offline - ' . $data->ifSpeed . '</span> ';
						}
						$code .= '<input type="button" class="label suspended" style="border:0;height:16px;" value="View Graph" onClick="window.location=\'clientarea.php?action=productdetails&id=' . $params[serviceid] . '&serveraction=custom&a=network&portid=' . $data->id . '\'" />';
						$code .= '</td>';
						$code .= '</tr>';
					}
				}
				$code .= '</table>';
			}
		}else{
			$device = new BudgetVM_Api($params['serverpassword']);
			$var->post->service    = $params['customfields']['BudgetVM Service ID'];
			$device = $device->call("v2", "device", "stats", "get", $var);
			$code = '
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="50%" style="text-align: center;"><input type="button" style="width: 150px" class="searchinput" value="Manage Reverse DNS" onClick="window.location=\'clientarea.php?action=productdetails&id=' . $params[serviceid] . '&modop=custom&a=reverse\'" /></td>
					<td width="50%" style="text-align: center;"><input type="button" style="width: 150px" class="searchinput" value="Network Graphs" onClick="window.location=\'clientarea.php?action=productdetails&id=' . $params[serviceid] . '&modop=custom&a=network\'" /></td>
				</tr>
				<tr>
					<td width="50%" style="text-align: center;"><input type="button" style="width: 135px" class="searchinput" value="Power Management" onClick="window.location=\'clientarea.php?action=productdetails&id=' . $params[serviceid] . '&modop=custom&a=power\'" /></td>
					<td width="50%" style="text-align: center;"><input type="button" style="width: 135px" class="searchinput" value="Reinstall Server" onClick="window.location=\'clientarea.php?action=productdetails&id=' . $params[serviceid] . '&modop=custom&a=reinstall\'" /></td>
				</tr>
			</table>
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="100%" style="text-align: center;"><input type="button" style="width: 200px;" class="searchinput" value="Out of Band Management (IPMI)" onClick="window.location=\'clientarea.php?action=productdetails&id=' . $params[serviceid] . '&modop=custom&a=manage\'" /></td>
				</tr>
			</table>';
			$code .= '<h3>System Information</h3>';
			if($status->success == true && $status->results > 0){
				$code .= '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
				$code .= '<tr>';
				$code .= '<td width="150" class="fieldarea" style="text-align: left;">Power Status</td>';
				if($status->result == "online"){
					$code .= '<td style="text-align: left;"><span class="label active">' . ucfirst($status->result) . '</span></td>';
				}else{
					$code .= '<td style="text-align: left;"><span class="label terminated">' . ucfirst($status->result) . '</span></td>';
				}
				$code .= '</tr>';
				$code .= '</table>';
			}
			if($device->success == true && $device->results > 0){
				$code .= '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
				$code .= '<tr>';
				$code .= '  <td width="150" class="fieldarea" style="text-align: left;">Virtualization</td>';
				$code .= '  <td style="text-align: left;">' . $device->result->type . '</td>';
				$code .= '</tr>';
				$code .= '<tr>';
				$code .= '  <td width="150" class="fieldarea" style="text-align: left;">Hardware Node</td>';
				$code .= '  <td style="text-align: left;">' . $device->result->node . '</td>';
				$code .= '</tr>';
				$code .= '<tr>';
				$code .= '  <td width="150" class="fieldarea" style="text-align: left;">Memory</td>';
				$code .= '  <td style="text-align: left;">' . $device->result->memory . ' MB</td>';
				$code .= '</tr>';
				$code .= '<tr>';
				$code .= '  <td width="150" class="fieldarea" style="text-align: left;">Hard Disk</td>';
				$code .= '  <td style="text-align: left;">' . $device->result->harddisk . ' GB</td>';
				$code .= '</tr>';
				$code .= '<tr>';
				$code .= '  <td width="150" class="fieldarea" style="text-align: left;">Bandwidth</td>';
				$code .= '  <td style="text-align: left;">' . $device->result->bandwidth . ' GB</td>';
				$code .= '</tr>';
				$code .= '</table>';
				$code .= '<h3>Network Information</h3>';
				$code .= '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
				foreach($device->result->ipaddr as $k=>$v){
					$code .= '<tr>';
					$code .= '<td colspan="2" style="text-align: left;">' . $v . '</td>';
					$code .= '</tr>';
				}
				$code .= '</table>';
			}
		}
	}else{
		$code = 'Failed to determine Device Type';
	}
	return $code;
}

function budgetvm_AdminLink($params) {

	//$code = '<form action=\"https://portal.budgetvm.com" method="post" target="_blank">
	//<input type="submit" value="Login to Control Panel" />
	//</form>';
	$code = "";
	return $code;

}

function budgetvm_LoginLink($params) {
	//echo "<a href=\"http://".$params["serverip"]."/controlpanel?gotousername=".$params["username"]."\" target=\"_blank\" style=\"color:#cc0000\">login to control panel</a>";
}

function budgetvm_reverse($params) {
	// Known problem with IPv6 on VPS
	if($_SERVER['REQUEST_METHOD'] == "POST" && is_array($_POST['update'])){
		$update 			= new BudgetVM_Api($params['serverpassword']);
		$var->post->records = json_encode($_POST['update']);
		$return				= $update->call("v2", "dns", "reverse", "post", $var);
		if(is_object($return->result)){
			$fixed	= "";
			foreach($return->result as $ip=>$ret){
				$fixed 	.= $ip . " - " . $ret . "<br>" . PHP_EOL;
			}
			$return->result	= $fixed;
		}
	}else{
		$return				= NULL;
	}
	$netblocks 				= new BudgetVM_Api($params['serverpassword']);
	$var->post->service		= $params['customfields']['BudgetVM Service ID'];
	$type 					= new BudgetVM_Api($params['serverpassword']);
	$info->post->service 	= $params['customfields']['BudgetVM Service ID'];
	$pagearray = array(
		'templatefile' 		=> 'reverse',
		'breadcrumb' 		=> ' > <a href="#">Services</a> / <a href="#">Manage Reverse DNS</a>',
		'vars' => array(
			'service' 		=> $params["serviceid"],
			'netblocks' 	=> $netblocks->call("v2", "network", "netblock", "get", $var),
			'return'		=> $return,
			'type' 			=> $type->call("v2", "device", "type", "get", $info),
		),
    );
	return $pagearray;
}

function budgetvm_power($params) {
	$type 					= new BudgetVM_Api($params['serverpassword']);
	$info->post->service 	= $params['customfields']['BudgetVM Service ID'];
	$type 					= $type->call("v2", "device", "type", "get", $info);
	
	if($_SERVER['REQUEST_METHOD'] == "POST"){
		if($_POST['bootorder'] != "standard"){
			// Custom Boot Order was Requested
			$bootorder 			= new BudgetVM_Api($params['serverpassword']);
			$var->post->service = $params['customfields']['BudgetVM Service ID'];
			$var->post->request	= $_POST['bootorder'];
			$bootorder			= $bootorder->call("v2", "device", "power", "put", $var);
		}else{
			$bootorder			= NULL;
		}
		// Power Action
		$update 			= new BudgetVM_Api($params['serverpassword']);
		$var->post->service = $params['customfields']['BudgetVM Service ID'];
		$var->post->action 	= $_POST['poweraction'];
		$return				= $update->call("v2", "device", "power", "post", $var);
		if($return->success == true){
			if($_POST['bootorder'] != "standard" && !empty($bootorder) && $bootorder->success == true){
				// Power Action with custom boot order
				$return->result	= $bootorder->result . " & " . $return->result;
			}
		}
	}else{
		$return				= NULL;
	}
	if($type->result == "dedicated"){
	
	}else{
	
	}
	$powerStatus 			= new BudgetVM_Api($params['serverpassword']);
	$getP->post->service	= $params['customfields']['BudgetVM Service ID'];
	$powerStatus 			= $powerStatus->call("v2", "device", "power", "get", $getP);
	$pagearray 				= array(
		'templatefile' 		=> 'power',
		'breadcrumb' 		=> ' > <a href="#">Services</a> / <a href="#">Power Management</a>',
		'vars' 				=> array(
			'service' 		=> $params["serviceid"],
			'powerStatus' 	=> $powerStatus,
			'return'		=> $return,
			'type' 			=> $type,
		),
    );
	return $pagearray;
}

function budgetvm_network($params) {
	$type 					= new BudgetVM_Api($params['serverpassword']);
	$info->post->service 	= $params['customfields']['BudgetVM Service ID'];
	$type 					= $type->call("v2", "device", "type", "get", $info);
	
	$bandwidth 				= new BudgetVM_Api($params['serverpassword']);
	$var->post->service 	= $params['customfields']['BudgetVM Service ID'];
	if($type->result == "dedicated"){
		if(isset($_GET['period'])){
			switch($_GET['period']){
				case "hour";
					$var->post->start 	= strtotime("-1 hour");
					break;
				case "day";
					$var->post->start 	= strtotime("-1 day");
					break;
				case "week";
					$var->post->start 	= strtotime("-1 week");
					break;
				case "month";
					$var->post->start 	= strtotime("-1 month");
					break;
				case "year";
					$var->post->start 	= strtotime("-1 year");
					break;
				default;
					$var->post->start 	= strtotime("-1 month");
					break;
			}
		}else{
			$var->post->start 	= strtotime("last Month");
		}
		$var->post->end 	= strtotime("now");
		$bandwidth			= $bandwidth->call("v2", "network", "bandwidth", "post", $var);
	}else{
		$bandwidth			= $bandwidth->call("v2", "network", "bandwidth", "post", $var);
	}
	$pagearray 				= array(
		'templatefile' 		=> 'network',
		'breadcrumb' 		=> ' > <a href="#">Services</a> / <a href="#">Network Graphs</a>',
		'vars' 				=> array(
			'period' 		=> $_GET["period"],
			'service' 		=> $params["serviceid"],
			'bandwidth' 	=> $bandwidth,
			'type' 			=> $type,
		),
    );
	return $pagearray;
}

function budgetvm_manage($params) {
	$type 						= new BudgetVM_Api($params['serverpassword']);
	$info->post->service 		= $params['customfields']['BudgetVM Service ID'];
	$type						= $type->call("v2", "device", "type", "get", $info);
	
	if($_SERVER['REQUEST_METHOD'] == "POST"){
		if($type->result == "dedicated"){
			if($_POST['ipmi_reset'] == true){
				$reset_ipmi			= new BudgetVM_Api($params['serverpassword']);
				$var->post->service = $params['customfields']['BudgetVM Service ID'];
				$return				= $reset_ipmi->call("v2", "device", "console", "delete", $var);
			}elseif($_POST['ipmi_launch'] == true){
				$launch_ipmi		= new BudgetVM_Api($params['serverpassword']);
				$var->post->service = $params['customfields']['BudgetVM Service ID'];
				$return				= $launch_ipmi->call("v2", "device", "console", "get", $var);
				if($return->success == true){
					budgetvm_ipmiLaunch(base64_decode($return->result));
					$return->result	= "KVM Launched, File download started.";
				}
			}elseif($_POST['image_unmount'] == true){
				$unmount_image		= new BudgetVM_Api($params['serverpassword']);
				$var->post->service = $params['customfields']['BudgetVM Service ID'];
				$return				= $unmount_image->call("v2", "device", "iso", "delete", $var);
			}elseif($_POST['image_mount'] == true){
				$mount_image		= new BudgetVM_Api($params['serverpassword']);
				$var->post->service = $params['customfields']['BudgetVM Service ID'];
				$var->post->image 	= $_POST['profile'];
				$return				= $mount_image->call("v2", "device", "iso", "post", $var);
				
				$mount_image		= new BudgetVM_Api($params['serverpassword']);
				$var->post->service = $params['customfields']['BudgetVM Service ID'];
				$return				= $mount_image->call("v2", "device", "iso", "put", $var);
			}
		}else{
			if($_POST['ipmi_vm_launch'] == true){
				$ipmi_vm_launch		= new BudgetVM_Api($params['serverpassword']);
				$var->post->service = $params['customfields']['BudgetVM Service ID'];
				$return				= $ipmi_vm_launch->call("v2", "device", "console", "get", $var);
				if($return->success == true){
					$message	= "<h4>Management Console</h4>";
					$message	.= "Username: " . $return->result->user . "</br>" . PHP_EOL;
					$message	.= "Password: " . $return->result->pass . "</br>" . PHP_EOL;
					$message	.= "Host: <a href='" . $return->result->host . "' target='_blank'>" . $return->result->host . "</a>" . PHP_EOL;
					$return->result = $message;
				}
			}
		}
	}else{
			$return				= NULL;
	}
	$status					= new BudgetVM_Api($params['serverpassword']);
	$var->post->service 	= $params['customfields']['BudgetVM Service ID'];
	
	$images					= new BudgetVM_Api($params['serverpassword']);
	
	$pagearray 				= array(
		'templatefile' 		=> 'manage',
		'breadcrumb' 		=> ' > <a href="#">Services</a> / <a href="#">Out of Band Management</a>',
		'vars' 				=> array(
			'service' 		=> $params["serviceid"],
			'type' 			=> $type,
			'images' 		=> $images->call("v2", "device", "iso", "get", NULL),
			'status' 		=> $status->call("v2", "device", "iso", "get", $var),
			'return'		=> $return,
		),
    );
	return $pagearray;
}

function budgetvm_reinstall($params) {
	$type 						= new BudgetVM_Api($params['serverpassword']);
	$info->post->service 		= $params['customfields']['BudgetVM Service ID'];
	$type						= $type->call("v2", "device", "type", "get", $info);
	
	if($_SERVER['REQUEST_METHOD'] == "POST"){
		$provision			= new BudgetVM_Api($params['serverpassword']);
		$var->post->service = $params['customfields']['BudgetVM Service ID'];
		$var->post->value 	= $_POST['profile'];
		$return				= $provision->call("v2", "device", "reload", "post", $var);
		if($return->success == true){
			if($type->result == "dedicated"){
				$return->result = "System Reinstall Started</br>Root Password: " . $return->result;
			}
		}
	}else{
		$return				= NULL;
	}
	if($type->result == "dedicated"){
		$status				= new BudgetVM_Api($params['serverpassword']);
		$var->post->service = $params['customfields']['BudgetVM Service ID'];
		$status				= $status->call("v2", "device", "reload", "put", $var);
	}
	$profiles				= new BudgetVM_Api($params['serverpassword']);
	$var->post->service 	= $params['customfields']['BudgetVM Service ID'];
	
	$pagearray 				= array(
		'templatefile' 		=> 'reinstall',
		'breadcrumb' 		=> ' > <a href="#">Services</a> / <a href="#">Operating System Reinstall</a>',
		'vars' 				=> array(
			'service' 		=> $params["serviceid"],
			'type' 			=> $type,
			'profiles' 		=> $profiles->call("v2", "device", "reload", "get", $var),
			'return'		=> $return,
			'status'		=> $status,
		),
    );
	return $pagearray;
}

function budgetvm_ClientAreaCustomButtonArray() {
    $buttonarray = array(
		"Manage Reverse DNS" 			=> "reverse",
		"Network Graphs" 				=> "network",
		"Reinstall Server" 				=> "reinstall",
		"Power Management" 				=> "power",
		"Out of Band Management (IPMI)" => "manage",
	);
	return $buttonarray;
}

function budgetvm_AdminCustomButtonArray() {
	
	$buttonarray = array(
		"Retrieve BudgetVM IP Details" => "adminUpdateDetails",
	);
	return $buttonarray;
}

function budgetvm_adminPowerOn($params){
	$action 			= new BudgetVM_Api($params['serverpassword']);
	$var->post->service = $params['customfields']['BudgetVM Service ID'];
	$var->post->action 	= "on";
	$return				= $action->call("v2", "device", "power", "post", $var);
	if($return->success == true){
		return "success";
	}else{
		return $return->result;
	}
}

function budgetvm_adminPowerOff($params){
	$action 			= new BudgetVM_Api($params['serverpassword']);
	$var->post->service = $params['customfields']['BudgetVM Service ID'];
	$var->post->action 	= "off";
	$return				= $action->call("v2", "device", "power", "post", $var);
	if($return->success == true){
		return "success";
	}else{
		return $return->result;
	}
}

function budgetvm_adminPowerReboot($params){
	$action 			= new BudgetVM_Api($params['serverpassword']);
	$var->post->service = $params['customfields']['BudgetVM Service ID'];
	$var->post->action 	= "reboot";
	$return				= $action->call("v2", "device", "power", "post", $var);
	if($return->success == true){
		return "success";
	}else{
		return $return->result;
	}
}

function budgetvm_adminResetIPMI($params){
	$reset_ipmi			= new BudgetVM_Api($params['serverpassword']);
	$var->post->service = $params['customfields']['BudgetVM Service ID'];
	$return				= $reset_ipmi->call("v2", "device", "console", "delete", $var);	
	if($return->success == true){
		return "success";
	}else{
		return $return->result;
	}
}

function budgetvm_adminUpdateDetails($params){
	$ips 					= new BudgetVM_Api($params['serverpassword']);
	$g->post->service 		= $params['customfields']['BudgetVM Service ID'];
	$ips 					= $ips->call("v2", "network", "netblock", "get", $g);
	foreach($ips->result as $ip=>$rdns){
		$mainip			= $ip;
		break;
	}
	$ipAddon				= "";
	foreach($ips->result as $ip=>$rdns){
		if($ip != $mainip){
			$ipAddon			.= $ip . PHP_EOL;
		}
	}
	
    update_query("tblhosting",array("dedicatedip" => $mainip, "assignedips" => $ipAddon, "lastupdate"=>"now()",),array("id"=>$params["serviceid"]));
}


function budgetvm_AdminServicesTabFields($params) {

	$powerStatus 			= new BudgetVM_Api($params['serverpassword']);
	$getP->post->service	= $params['customfields']['BudgetVM Service ID'];
	$powerStatus 			= $powerStatus->call("v2", "device", "power", "get", $getP);
	
	switch($powerStatus->result){
		case "on";
		case "online";
			$power			= "<strong>Powered On</strong>";
			break;
		case "off";
		case "offline";
			$power			= "<strong>Powered Off</strong>";
			break;
		default;
			$power			= "<strong>Unknown</strong>";
			break;
	}
	
	$fieldsarray["Power Status"] 		= $power;
	$fieldsarray["Power Management"] 	= "<input type=\"button\" value=\"Power On\" class=\"btn\" onclick=\"runModuleCommand('custom','adminPowerOn')\"> <input type=\"button\" value=\"Power Off\" class=\"btn\" onclick=\"runModuleCommand('custom','adminPowerOff')\"> <input type=\"button\" value=\"Power Cycle\" class=\"btn\" onclick=\"runModuleCommand('custom','adminPowerReboot')\">";
	
	$type 					= new BudgetVM_Api($params['serverpassword']);
	$info->post->service 	= $params['customfields']['BudgetVM Service ID'];
	$type 					= $type->call("v2", "device", "type", "get", $info);
	
	if($type->result == "dedicated"){
		$fieldsarray["IPMI Controller"] = "<input type='button' style='width: 135px' class='searchinput' value='Launch KVM' onclick=\"window.open('../modules/servers/budgetvm/ipmi.php?id=" . base64_encode($params['customfields']['BudgetVM Service ID']) . "&apikey=" . base64_encode($params['serverpassword']) . "', '_blank','width=600,height=200,status=no,location=no,toolbar=no,scrollbars=1,menubar=no')\"> <input type=\"button\" value=\"Reset IPMI Controller\" class=\"btn\" onclick=\"runModuleCommand('custom','adminResetIPMI')\">";
	}
	if($type->result == "vps"){
		$getConsole			= new BudgetVM_Api($params['serverpassword']);
		$var->post->service = $params['customfields']['BudgetVM Service ID'];
		$console			= $getConsole->call("v2", "device", "console", "get", $var);
		if(empty($console->result->user) && !empty($console->result->pass)){
			$fieldsarray["VNC Host"] = $console->result->host . ":" . $console->result->port;
			$fieldsarray["VNC Password"] = $console->result->pass;
		}else{
			$fieldsarray["Console Username"] = $console->result->user;
			$fieldsarray["Console Password"] = $console->result->pass;
			$fieldsarray["Out of Band Console"] = "<input type='button' style='width: 135px' class='searchinput' value='Launch Console' onclick=\"window.open('" . $console->result->host . "', '_blank','width=800,height=600,status=no,location=no,toolbar=no,scrollbars=1,menubar=no')\">";
		}
	}
	
	$displayNetwork			= "";
	$bandwidth 				= new BudgetVM_Api($params['serverpassword']);
	$var->post->service 	= $params['customfields']['BudgetVM Service ID'];
	if($type->result == "dedicated"){
		$var->post->start 	= strtotime("last Month");
		$var->post->end 	= strtotime("now");
		$bandwidth			= $bandwidth->call("v2", "network", "bandwidth", "post", $var);
		foreach($bandwidth->result as $server){
			foreach($server as $switch=>$port ){
				foreach($port as $id=>$info ){
					$displayNetwork .= "<h2>" . $info->name . "</h2>";
					$displayNetwork .= '<img src="data:image/png;base64, ' . $info->graph . '">';
				}
			}
		}
	}else{
		$bandwidth			= $bandwidth->call("v2", "network", "bandwidth", "post", $var);
		if($bandwidth->success == true){
			$displayNetwork .= '<img src="data:image/png;base64, ' . $bandwidth->result->graph . '">';
		}
	}
	$fieldsarray["Network Information"] = $displayNetwork;
	
    return $fieldsarray;

}

function budgetvm_UsageUpdate($params) {

	$serverid 				= $params['serverid'];
	$serverhostname 		= $params['serverhostname'];
	$serverip 				= $params['serverip'];
	$serverusername 		= $params['serverusername'];
	$serverpassword 		= $params['serverpassword'];
	$serveraccesshash 		= $params['serveraccesshash'];
	$serversecure 			= $params['serversecure'];

	// get network usage details
	/*
	$type 					= new BudgetVM_Api($params['serverpassword']);
	$info->post->service 	= $params['customfields']['BudgetVM Service ID'];
	$type 					= $type->call("v2", "device", "type", "get", $info);
	
	$bandwidth 				= new BudgetVM_Api($params['serverpassword']);
	$var->post->service 	= $params['customfields']['BudgetVM Service ID'];
	if($type->result == "dedicated"){
		if(isset($_GET['period'])){
			switch($_GET['period']){
				case "hour";
					$var->post->start 	= strtotime("-1 hour");
					break;
				case "day";
					$var->post->start 	= strtotime("-1 day");
					break;
				case "week";
					$var->post->start 	= strtotime("-1 week");
					break;
				case "month";
					$var->post->start 	= strtotime("-1 month");
					break;
				case "year";
					$var->post->start 	= strtotime("-1 year");
					break;
				default;
					$var->post->start 	= strtotime("-1 month");
					break;
			}
		}else{
			$var->post->start 	= strtotime("last Month");
		}
		$var->post->end 	= strtotime("now");
		$bandwidth			= $bandwidth->call("v2", "network", "bandwidth", "post", $var);
	}else{
		$bandwidth			= $bandwidth->call("v2", "network", "bandwidth", "post", $var);
	}
	*/
	# Run connection to retrieve usage for all domains/accounts on $serverid

	# Now loop through results and update DB
	// Here we would update dedicatedip & assigned ips
	
	foreach ($results AS $domain=>$values) {
        update_query("tblhosting",array(
         "diskused"=>$values['diskusage'],
         "dislimit"=>$values['disklimit'],
         "bwused"=>$values['bwusage'],
         "bwlimit"=>$values['bwlimit'],
         "lastupdate"=>"now()",
        ),array("server"=>$serverid,"domain"=>$values['domain']));
    }

}

function budgetvm_ApiCall($host, $key, $data){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $host);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 100);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-API-KEY: ' . $key));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	$results = curl_exec($ch);
	curl_close($ch);
	if(!empty($results)){
		return "success";
	}else{
		return "error - Please contact support.";
	}
	return "success";
}
	
function convToUtf8($str){ 
	if(mb_detect_encoding($str,"UTF-8, ISO-8859-1, GBK") != "UTF-8"){ 
		return iconv("gbk","utf-8",$str); 
	}else{ 
		return $str; 
	}
}

function budgetvm_ipmiLaunch($data){
	header('Content-Type: application/x-java-jnlp-file', true);
	header('Content-Disposition: attachment; filename="launch.jnlp"', true);
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT", true);
	header('Accept-Ranges: bytes', true);
	header("Cache-control: private", true);
	header('Pragma: private', true);
	echo $data;
}
?>