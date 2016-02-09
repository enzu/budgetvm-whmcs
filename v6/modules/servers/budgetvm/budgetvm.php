<?php
/**
 * WHMCS SDK Sample Provisioning Module
 *
 * Provisioning Modules, also referred to as Product or Server Modules, allow
 * you to create modules that allow for the provisioning and management of
 * products and services in WHMCS.
 *
 * This sample file demonstrates how a provisioning module for WHMCS should be
 * structured and exercises all supported functionality.
 *
 * Provisioning Modules are stored in the /modules/servers/ directory. The
 * module name you choose must be unique, and should be all lowercase,
 * containing only letters & numbers, always starting with a letter.
 *
 * Within the module itself, all functions must be prefixed with the module
 * filename, followed by an underscore, and then the function name. For this
 * example file, the filename is "provisioningmodule" and therefore all
 * functions begin "budgetvm_".
 *
 * If your module or third party API does not support a given function, you
 * should not define that function within your module. Only the _ConfigOptions
 * function is required.
 *
 * For more information, please refer to the online documentation.
 *
 * @see http://docs.whmcs.com/Provisioning_Module_Developer_Docs
 *
 * @copyright Copyright (c) WHMCS Limited 2015
 * @license http://www.whmcs.com/license/ WHMCS Eula
 */

if (!defined("WHMCS")) {
  die("This file cannot be accessed directly");
}
require(dirname(__FILE__) . '/lib/budgetvm.class.php');
// Require any libraries needed for the module to function.
// require_once __DIR__ . '/path/to/library/loader.php';
//
// Also, perform any initialization required by the service's library.

/**
 * Define module related meta data.
 *
 * Values returned here are used to determine module related abilities and
 * settings.
 *
 * @see http://docs.whmcs.com/Provisioning_Module_Meta_Data_Parameters
 *
 * @return array
 */
function budgetvm_MetaData()
{
  return array(
  'DisplayName'             => 'BudgetVM Management Module',
  'APIVersion'              => '1.1', // Use API Version 1.1
  'RequiresServer'          => true, // Set true if module requires a server to work
  'DefaultNonSSLPort'       => '80', // Default Non-SSL Connection Port
  'DefaultSSLPort'          => '443', // Default SSL Connection Port
  'ServiceSingleSignOnLabel'=> 'Login to Panel as User',
  'AdminSingleSignOnLabel'  => 'Login to Panel as Admin',
  );
}

/**
 * Define product configuration options.
 *
 * The values you return here define the configuration options that are
 * presented to a user when configuring a product for use with the module. These
 * values are then made available in all module function calls with the key name
 * configoptionX - with X being the index number of the field from 1 to 24.
 *
 * You can specify up to 24 parameters, with field types:
 * * text
 * * password
 * * yesno
 * * dropdown
 * * radio
 * * textarea
 *
 * Examples of each and their possible configuration parameters are provided in
 * this sample function.
 *
 * @return array
 */
function budgetvm_ConfigOptions()
{
  return array(
    "Reinstall" => array( 
     "Type" => "yesno", 
      "Description" => "Tick to allow clients to reinstall their system." 
    ),
    "Network" => array( 
      "Type" => "yesno", 
      "Description" => "Tick to allow clients to view their network graphs." 
    ),
  );
}

/**
 * Provision a new instance of a product/service.
 *
 * Attempt to provision a new instance of a given product/service. This is
 * called any time provisioning is requested inside of WHMCS. Depending upon the
 * configuration, this can be any of:
 * * When a new order is placed
 * * When an invoice for a new order is paid
 * * Upon manual request by an admin user
 *
 * @param array $params common module parameters
 *
 * @see http://docs.whmcs.com/Provisioning_Module_SDK_Parameters
 *
 * @return string "success" or an error message
 */
function budgetvm_CreateAccount(array $params)
{
  // Note this does nto create a service, but rather unsuspends it, so that you can re-use hardware for customers.
  try {
    $action               = new BudgetVM_Api($params['serverpassword']);
    $info->post->service  = $params['customfields']['BudgetVM Service ID'];
    $action               = $action->call("v2", "network", "port", "put", $info);
    if($action->success == true){
      return "success";
    }else{
      return $action->result;
    }
  } catch (Exception $e) {
    // Record the error in WHMCS's module log.
    logModuleCall('provisioningmodule', __FUNCTION__, $params, $e->getMessage(), $e->getTraceAsString());
    return $e->getMessage();
  }
  /* Once auto deployment for resold servers is completed we will deploy systems here automatically, however configoptions are the current blocker of this.
  try {
    // Call the service's provisioning function, using the values provided
    // by WHMCS in `$params`.
    //
    // A sample `$params` array may be defined as:
    //
    // ```
    // array(
    //   'domain' => 'The domain of the service to provision',
    //   'username' => 'The username to access the new service',
    //   'password' => 'The password to access the new service',
    //   'configoption1' => 'The amount of disk space to provision',
    //   'configoption2' => 'The new services secret key',
    //   'configoption3' => 'Whether or not to enable FTP',
    //   ...
    // )
    // ```
  } catch (Exception $e) {
    // Record the error in WHMCS's module log.
    logModuleCall('provisioningmodule', __FUNCTION__, $params, $e->getMessage(), $e->getTraceAsString());

    return $e->getMessage();
  }

  return 'success';
  */
}

/**
 * Suspend an instance of a product/service.
 *
 * Called when a suspension is requested. This is invoked automatically by WHMCS
 * when a product becomes overdue on payment or can be called manually by admin
 * user.
 *
 * @param array $params common module parameters
 *
 * @see http://docs.whmcs.com/Provisioning_Module_SDK_Parameters
 *
 * @return string "success" or an error message
 */
function budgetvm_SuspendAccount(array $params)
{
  try {
    $action               = new BudgetVM_Api($params['serverpassword']);
    $info->post->service  = $params['customfields']['BudgetVM Service ID'];
    $action               = $action->call("v2", "network", "port", "delete", $info);
    if($action->success == true){
      return "success";
    }else{
      return $action->result;
    }
  } catch (Exception $e) {
    // Record the error in WHMCS's module log.
    logModuleCall('provisioningmodule', __FUNCTION__, $params, $e->getMessage(), $e->getTraceAsString());
    return $e->getMessage();
  }
}

/**
 * Un-suspend instance of a product/service.
 *
 * Called when an un-suspension is requested. This is invoked
 * automatically upon payment of an overdue invoice for a product, or
 * can be called manually by admin user.
 *
 * @param array $params common module parameters
 *
 * @see http://docs.whmcs.com/Provisioning_Module_SDK_Parameters
 *
 * @return string "success" or an error message
 */
function budgetvm_UnsuspendAccount(array $params)
{
  try {
    $action               = new BudgetVM_Api($params['serverpassword']);
    $info->post->service  = $params['customfields']['BudgetVM Service ID'];
    $action               = $action->call("v2", "network", "port", "put", $info);
    if($action->success == true){
      return "success";
    }else{
      return $action->result;
    }
  } catch (Exception $e) {
    // Record the error in WHMCS's module log.
    logModuleCall('provisioningmodule', __FUNCTION__, $params, $e->getMessage(), $e->getTraceAsString());
    return $e->getMessage();
  }
}

/**
 * Terminate instance of a product/service.
 *
 * Called when a termination is requested. This can be invoked automatically for
 * overdue products if enabled, or requested manually by an admin user.
 *
 * @param array $params common module parameters
 *
 * @see http://docs.whmcs.com/Provisioning_Module_SDK_Parameters
 *
 * @return string "success" or an error message
 */
function budgetvm_TerminateAccount(array $params)
{
  // Note, this does not actually terminate the service it just suspends it.
  try {
    $action               = new BudgetVM_Api($params['serverpassword']);
    $info->post->service  = $params['customfields']['BudgetVM Service ID'];
    $action               = $action->call("v2", "network", "port", "delete", $info);
    if($action->success == true){
      return "success";
    }else{
      return $action->result;
    }
  } catch (Exception $e) {
    // Record the error in WHMCS's module log.
    logModuleCall('provisioningmodule', __FUNCTION__, $params, $e->getMessage(), $e->getTraceAsString());
    return $e->getMessage();
  }
}

/**
 * Change the password for an instance of a product/service.
 *
 * Called when a password change is requested. This can occur either due to a
 * client requesting it via the client area or an admin requesting it from the
 * admin side.
 *
 * This option is only available to client end users when the product is in an
 * active status.
 *
 * @param array $params common module parameters
 *
 * @see http://docs.whmcs.com/Provisioning_Module_SDK_Parameters
 *
 * @return string "success" or an error message
 */
function budgetvm_ChangePassword(array $params)
{
  try {
    // Call the service's change password function, using the values
    // provided by WHMCS in `$params`.
    //
    // A sample `$params` array may be defined as:
    //
    // ```
    // array(
    //   'username' => 'The service username',
    //   'password' => 'The new service password',
    // )
    // ```
  } catch (Exception $e) {
    // Record the error in WHMCS's module log.
    logModuleCall('provisioningmodule', __FUNCTION__, $params, $e->getMessage(), $e->getTraceAsString());

    return $e->getMessage();
  }

  return 'success';
}

/**
 * Upgrade or downgrade an instance of a product/service.
 *
 * Called to apply any change in product assignment or parameters. It
 * is called to provision upgrade or downgrade orders, as well as being
 * able to be invoked manually by an admin user.
 *
 * This same function is called for upgrades and downgrades of both
 * products and configurable options.
 *
 * @param array $params common module parameters
 *
 * @see http://docs.whmcs.com/Provisioning_Module_SDK_Parameters
 *
 * @return string "success" or an error message
 */
function budgetvm_ChangePackage(array $params)
{
  try {
    // Call the service's change password function, using the values
    // provided by WHMCS in `$params`.
    //
    // A sample `$params` array may be defined as:
    //
    // ```
    // array(
    //   'username' => 'The service username',
    //   'configoption1' => 'The new service disk space',
    //   'configoption3' => 'Whether or not to enable FTP',
    // )
    // ```
  } catch (Exception $e) {
    // Record the error in WHMCS's module log.
    logModuleCall('provisioningmodule', __FUNCTION__, $params, $e->getMessage(), $e->getTraceAsString());

    return $e->getMessage();
  }

  return 'success';
}

/**
 * Test connection with the given server parameters.
 *
 * Allows an admin user to verify that an API connection can be
 * successfully made with the given configuration parameters for a
 * server.
 *
 * When defined in a module, a Test Connection button will appear
 * alongside the Server Type dropdown when adding or editing an
 * existing server.
 *
 * @param array $params common module parameters
 *
 * @see http://docs.whmcs.com/Provisioning_Module_SDK_Parameters
 *
 * @return array
 */
function budgetvm_TestConnection(array $params)
{
  try {
    // Call the service's connection test function.
    $action               = new BudgetVM_Api($params['serverpassword']);
    $action               = $action->call("v2", "test", "connection", "get", NULL);
    if($action->success == true){
      $success            = true;
      $errorMsg           = $action->result;
    }else{
      $success            = false;
      $errorMsg           = $action->result;
    }
  } catch (Exception $e) {
    // Record the error in WHMCS's module log.
    logModuleCall('provisioningmodule', __FUNCTION__, $params, $e->getMessage(), $e->getTraceAsString());

    $success  = false;
    $errorMsg = $e->getMessage();
  }

  return array(
    'success' => $success,
    'error'   => $errorMsg,
  );
}

/**
 * Additional actions an admin user can invoke.
 *
 * Define additional actions that an admin user can perform for an
 * instance of a product/service.
 *
 * @see budgetvm_buttonOneFunction()
 *
 * @return array
 */
function budgetvm_AdminCustomButtonArray()
{
  return array(
    "Power On"        => "powerOn",
    "Power Off"       => "powerOff",
    "Reboot"          => "powerReboot",
    "Reset Console"   => "resetConsole",
  );
}

/**
 * Additional actions a client user can invoke.
 *
 * Define additional actions a client user can perform for an instance of a
 * product/service.
 *
 * Any actions you define here will be automatically displayed in the available
 * list of actions within the client area.
 *
 * @return array
 */
function budgetvm_ClientAreaCustomButtonArray()
{
  return array(
    "Power On"        => "powerOn",
    "Power Off"       => "powerOff",
    "Reboot"          => "powerReboot",
    "Reset Console"   => "resetConsole",
  );
}

/**
 * Custom function for performing an additional action.
 *
 * You can define an unlimited number of custom functions in this way.
 *
 * Similar to all other module call functions, they should either return
 * 'success' or an error message to be displayed.
 *
 * @param array $params common module parameters
 *
 * @see http://docs.whmcs.com/Provisioning_Module_SDK_Parameters
 * @see budgetvm_AdminCustomButtonArray()
 *
 * @return string "success" or an error message
 */
function budgetvm_powerOn(array $params)
{
  try {
    // Call the service's function, using the values provided by WHMCS in
    // `$params`.
    $update             = new BudgetVM_Api($params['serverpassword']);
    $var = new stdclass();
    $var->post = new stdclass();
    $var->post->service = $params['customfields']['BudgetVM Service ID'];
    $var->post->action  = "on";
    $return             = $update->call("v2", "device", "power", "post", $var);
    if($return->success == true){
      return 'success';
    }else{
      return $return->result;
    }
  } catch (Exception $e) {
    // Record the error in WHMCS's module log.
    logModuleCall('provisioningmodule', __FUNCTION__, $params, $e->getMessage(), $e->getTraceAsString());
    return $e->getMessage();
  }
}

function budgetvm_powerOff(array $params)
{
  try {
    // Call the service's function, using the values provided by WHMCS in
    // `$params`.
    $update             = new BudgetVM_Api($params['serverpassword']);
    $var = new stdclass();
    $var->post = new stdclass();
    $var->post->service = $params['customfields']['BudgetVM Service ID'];
    $var->post->action  = "off";
    $return             = $update->call("v2", "device", "power", "post", $var);
    if($return->success == true){
      return 'success';
    }else{
      return $return->result;
    }
  } catch (Exception $e) {
    // Record the error in WHMCS's module log.
    logModuleCall('provisioningmodule', __FUNCTION__, $params, $e->getMessage(), $e->getTraceAsString());
    return $e->getMessage();
  }
}

function budgetvm_powerReboot(array $params)
{
  try {
    // Call the service's function, using the values provided by WHMCS in
    // `$params`.
    $update             = new BudgetVM_Api($params['serverpassword']);
    $var = new stdclass();
    $var->post = new stdclass();
    $var->post->service = $params['customfields']['BudgetVM Service ID'];
    $var->post->action  = "reboot";
    $return             = $update->call("v2", "device", "power", "post", $var);
    if($return->success == true){
      return 'success';
    }else{
      return $return->result;
    }
  } catch (Exception $e) {
    // Record the error in WHMCS's module log.
    logModuleCall('provisioningmodule', __FUNCTION__, $params, $e->getMessage(), $e->getTraceAsString());
    return $e->getMessage();
  }
}

function budgetvm_resetConsole(array $params)
{
  try {
    // Call the service's function, using the values provided by WHMCS in
    // `$params`.
    $reset_ipmi           = new BudgetVM_Api($params['serverpassword']);
    $var = new stdclass();
    $var->post = new stdclass();
    $var->post->service   = $params['customfields']['BudgetVM Service ID'];
    $return               = $reset_ipmi->call("v2", "device", "console", "delete", $var);  
    if($return->success == true){
      return 'success';
    }else{
      return $return->result;
    }
  } catch (Exception $e) {
    // Record the error in WHMCS's module log.
    logModuleCall('provisioningmodule', __FUNCTION__, $params, $e->getMessage(), $e->getTraceAsString());
    return $e->getMessage();
  }
}


/**
 * Admin services tab additional fields.
 *
 * Define additional rows and fields to be displayed in the admin area service
 * information and management page within the clients profile.
 *
 * Supports an unlimited number of additional field labels and content of any
 * type to output.
 *
 * @param array $params common module parameters
 *
 * @see http://docs.whmcs.com/Provisioning_Module_SDK_Parameters
 * @see budgetvm_AdminServicesTabFieldsSave()
 *
 * @return array
 */
function budgetvm_AdminServicesTabFields(array $params)
{
  try {
    // Call the service's function, using the values provided by WHMCS in
    // `$params`.
    $response = array();

    // Return an array based on the function's response.
    return array();
  } catch (Exception $e) {
    // Record the error in WHMCS's module log.
    logModuleCall('provisioningmodule', __FUNCTION__, $params, $e->getMessage(), $e->getTraceAsString());

    // In an error condition, simply return no additional fields to display.
  }

  return array();
}

/**
 * Execute actions upon save of an instance of a product/service.
 *
 * Use to perform any required actions upon the submission of the admin area
 * product management form.
 *
 * It can also be used in conjunction with the AdminServicesTabFields function
 * to handle values submitted in any custom fields which is demonstrated here.
 *
 * @param array $params common module parameters
 *
 * @see http://docs.whmcs.com/Provisioning_Module_SDK_Parameters
 * @see budgetvm_AdminServicesTabFields()
 */
function budgetvm_AdminServicesTabFieldsSave(array $params)
{
  // Fetch form submission variables.
  $originalFieldValue = isset($_REQUEST['budgetvm_original_uniquefieldname'])
    ? $_REQUEST['budgetvm_original_uniquefieldname']
    : '';

  $newFieldValue = isset($_REQUEST['budgetvm_uniquefieldname'])
    ? $_REQUEST['budgetvm_uniquefieldname']
    : '';

  // Look for a change in value to avoid making unnecessary service calls.
  if ($originalFieldValue != $newFieldValue) {
    try {
      // Call the service's function, using the values provided by WHMCS
      // in `$params`.
    } catch (Exception $e) {
      // Record the error in WHMCS's module log.
      logModuleCall('provisioningmodule', __FUNCTION__, $params, $e->getMessage(), $e->getTraceAsString());

      // Otherwise, error conditions are not supported in this operation.
    }
  }
}

/**
 * Perform single sign-on for a given instance of a product/service.
 *
 * Called when single sign-on is requested for an instance of a product/service.
 *
 * When successful, returns a URL to which the user should be redirected.
 *
 * @param array $params common module parameters
 *
 * @see http://docs.whmcs.com/Provisioning_Module_SDK_Parameters
 *
 * @return array
 */
function budgetvm_ServiceSingleSignOn(array $params)
{
  try {
    // Call the service's single sign-on token retrieval function, using the
    // values provided by WHMCS in `$params`.
    $response = array();

    return array(
      'success' => true,
      'redirectTo' => $response['redirectUrl'],
    );
  } catch (Exception $e) {
    // Record the error in WHMCS's module log.
    logModuleCall('provisioningmodule', __FUNCTION__, $params, $e->getMessage(), $e->getTraceAsString());

    return array(
      'success' => false,
      'errorMsg' => $e->getMessage(),
    );
  }
}

/**
 * Perform single sign-on for a server.
 *
 * Called when single sign-on is requested for a server assigned to the module.
 *
 * This differs from ServiceSingleSignOn in that it relates to a server
 * instance within the admin area, as opposed to a single client instance of a
 * product/service.
 *
 * When successful, returns a URL to which the user should be redirected to.
 *
 * @param array $params common module parameters
 *
 * @see http://docs.whmcs.com/Provisioning_Module_SDK_Parameters
 *
 * @return array
 */
function budgetvm_AdminSingleSignOn(array $params)
{
  try {
    // Call the service's single sign-on admin token retrieval function,
    // using the values provided by WHMCS in `$params`.
    $response = array();

    return array(
      'success' => true,
      'redirectTo' => $response['redirectUrl'],
    );
  } catch (Exception $e) {
    // Record the error in WHMCS's module log.
    logModuleCall('provisioningmodule', __FUNCTION__, $params, $e->getMessage(), $e->getTraceAsString());

    return array(
      'success' => false,
      'errorMsg' => $e->getMessage(),
    );
  }
}

/**
 * Client area output logic handling.
 *
 * This function is used to define module specific client area output. It should
 * return an array consisting of a template file and optional additional
 * template variables to make available to that template.
 *
 * The template file you return can be one of two types:
 *
 * * tabOverviewModuleOutputTemplate - The output of the template provided here
 *   will be displayed as part of the default product/service client area
 *   product overview page.
 *
 * * tabOverviewReplacementTemplate - Alternatively using this option allows you
 *   to entirely take control of the product/service overview page within the
 *   client area.
 *
 * Whichever option you choose, extra template variables are defined in the same
 * way. This demonstrates the use of the full replacement.
 *
 * Please Note: Using tabOverviewReplacementTemplate means you should display
 * the standard information such as pricing and billing details in your custom
 * template or they will not be visible to the end user.
 *
 * @param array $params common module parameters
 *
 * @see http://docs.whmcs.com/Provisioning_Module_SDK_Parameters
 *
 * @return array
 */
function budgetvm_ClientArea(array $params)
{
  // Determine the requested action and set service call parameters based on
  // the action.
  $requestedAction              = isset($_REQUEST['customAction']) ? $_REQUEST['customAction'] : '';
  $service                      = $params['serviceid'];
  $bvmid                        = $params['customfields']['BudgetVM Service ID'];
  $var                          = new stdclass();
  $var->post                    = new stdclass();
  $var->post->service           = $bvmid;
  $budgetvm->bvmid              = $bvmid;
  $budgetvm->service            = $service;
  $type                         = new BudgetVM_Api($params['serverpassword']);
  $type                         = $type->call("v2", "device", "type", "get", $var); 
  $budgetvm->type               = $type->result;
  if($requestedAction == "manage"){
    
    $serviceAction              = 'get_usage';
    $templateFile               = 'templates/manage.tpl';
  }elseif ($requestedAction == 'reverse') {
    if($_SERVER['REQUEST_METHOD'] == "POST" && is_array($_POST['update'])){
      $update                   = new BudgetVM_Api($params['serverpassword']);
      $v->post->records         = json_encode($_POST['update']);
      $budgetvm->return         = $update->call("v2", "dns", "reverse", "post", $v);
      if(is_object($budgetvm->return->result)){
        $fixed                  = "";
        foreach($budgetvm->return->result as $ip=>$ret){
          $fixed                .= $ip . " - " . $ret . "<br>" . PHP_EOL;
        }
        $budgetvm->return->result= $fixed;
      }
    }else{
      $budgetvm->return         = NULL;
    }
    $netblocks                  = new BudgetVM_Api($params['serverpassword']);
    $budgetvm->netblocks        = $netblocks->call("v2", "network", "netblock", "get", $var);
      
    $serviceAction              = 'get_usage';
    $templateFile               = 'templates/rdns.tpl';
  }elseif ($requestedAction == 'network') {
    
    $bandwidth                  = new BudgetVM_Api($params['serverpassword']);
    if($budgetvm->type == "dedicated"){
      if(isset($_GET['period'])){
        switch($_GET['period']){
          case "hour";
            $var->post->start   = strtotime("-1 hour");
            break;
          case "day";
            $var->post->start   = strtotime("-1 day");
            break;
          case "week";
            $var->post->start   = strtotime("-1 week");
            break;
          case "month";
            $var->post->start   = strtotime("-1 month");
            break;
          case "year";
            $var->post->start   = strtotime("-1 year");
            break;
          default;
            $var->post->start   = strtotime("-1 month");
            break;
        }
      }else{
        $var->post->start       = strtotime("last Month");
      }
      $var->post->end           = strtotime("now");
      $budgetvm->bandwidth      = $bandwidth->call("v2", "network", "bandwidth", "post", $var);
    }else{
      $budgetvm->bandwidth      = $bandwidth->call("v2", "network", "bandwidth", "post", $var);
    }
    $serviceAction              = 'get_usage';
    $templateFile               = 'templates/network.tpl';
  }elseif ($requestedAction == 'power') {
        
    if($_SERVER['REQUEST_METHOD'] == "POST"){
      if($_POST['bootorder'] != "standard"){
        // Custom Boot Order was Requested
        $bootorder              = new BudgetVM_Api($params['serverpassword']);
        $var->post->request     = $_POST['bootorder'];
        $budgetvm->bootorder    = $bootorder->call("v2", "device", "power", "put", $var);
      }else{
        $budgetvm->bootorder    = NULL;
      }
      // Power Action
      $update                   = new BudgetVM_Api($params['serverpassword']);
      $var->post->action        = $_POST['poweraction'];
      $budgetvm->return         = $update->call("v2", "device", "power", "post", $var);
      if($budgetvm->return->success == true){
        if($_POST['bootorder'] != "standard" && !empty($budgetvm->bootorder) && $budgetvm->bootorder->success == true){
          // Power Action with custom boot order
          $budgetvm->return->result = $budgetvm->bootorder->result . " & " . $budgetvm->return->result;
        }
      }
    }else{
      $budgetvm->return         = NULL;
    }
    $powerStatus                = new BudgetVM_Api($params['serverpassword']);
    $budgetvm->powerStatus      = $powerStatus->call("v2", "device", "power", "get", $var);
    
    $serviceAction              = 'get_usage';
    $templateFile               = 'templates/power.tpl';
  }elseif ($requestedAction == 'reinstall') {
    if($_SERVER['REQUEST_METHOD'] == "POST"){
      $cancel                   = $_POST['cancel'];
      if(isset($cancel) && $cancel == true){
        $stop                   = new BudgetVM_Api($params['serverpassword']);
        $budgetvm->return       = $stop->call("v2", "device", "reload", "delete", $var);
      }else{
        $provision              = new BudgetVM_Api($params['serverpassword']);
        $var->post->value       = $_POST['profile'];
        $budgetvm->return       = $provision->call("v2", "device", "reload", "post", $var);
        if($budgetvm->return->success == true){
          if($type->result == "dedicated"){
            $budgetvm->return->result = "System Reinstall Started</br>Root Password: " . $budgetvm->return->result;
          }
        }
      }
    }else{
      $budgetvm->return         = NULL;
    }
    if($type->result == "dedicated"){
      $status                   = new BudgetVM_Api($params['serverpassword']);
      $budgetvm->status         = $status->call("v2", "device", "reload", "put", $var)->result;
    }else{
      $budgetvm->status         = NULL;
    }
    $profiles                   = new BudgetVM_Api($params['serverpassword']);  
    $budgetvm->profiles         = $profiles->call("v2", "device", "reload", "get", $var)->result;
    
    $serviceAction              = 'get_usage';
    $templateFile               = 'templates/reinstall.tpl';
  }elseif ($requestedAction == 'ipmi') {
    
    if($_SERVER['REQUEST_METHOD'] == "POST"){
      if($type->result == "dedicated"){
        if($_POST['ipmi_reset'] == true){
          $reset_ipmi           = new BudgetVM_Api($params['serverpassword']);
          $budgetvm->return     = $reset_ipmi->call("v2", "device", "console", "delete", $var);
        }elseif($_POST['ipmi_launch'] == true){
          $launch_ipmi          = new BudgetVM_Api($params['serverpassword']);
          $budgetvm->return     = $launch_ipmi->call("v2", "device", "console", "get", $var);
          if($budgetvm->return->success == true){
            $launch_ipmi->ipmiLaunch(base64_decode($budgetvm->return->result));
            $budgetvm->return->result  = "KVM Launched, File download started.";
          }
        }elseif($_POST['image_unmount'] == true){
          $unmount_image        = new BudgetVM_Api($params['serverpassword']);
          $budgetvm->return     = $unmount_image->call("v2", "device", "iso", "delete", $var);
        }elseif($_POST['image_mount'] == true){
          $mount_image          = new BudgetVM_Api($params['serverpassword']);
          $var->post->image     = $_POST['profile'];
          $budgetvm->return     = $mount_image->call("v2", "device", "iso", "post", $var);
          
          $mount_image          = new BudgetVM_Api($params['serverpassword']);
          $budgetvm->return     = $mount_image->call("v2", "device", "iso", "put", $var);
        }
      }else{
        if($_POST['ipmi_vm_launch'] == true){
          $ipmi_vm_launch       = new BudgetVM_Api($params['serverpassword']);
          $budgetvm->return     = $ipmi_vm_launch->call("v2", "device", "console", "get", $var);
          if($budgetvm->return->success == true){
            $message            = "<h4>Management Console</h4>";
            $message            .= "Username: " . $budgetvm->return->result->user . "</br>" . PHP_EOL;
            $message            .= "Password: " . $budgetvm->return->result->pass . "</br>" . PHP_EOL;
            $message            .= "Host: <a href='" . $budgetvm->return->result->host . "' target='_blank'>" . $budgetvm->return->result->host . "</a>" . PHP_EOL;
            $budgetvm->return->result = $message;
          }
        }
      }
    }else{
      $budgetvm->return         = NULL;
    }
  
    $images                     = new BudgetVM_Api($params['serverpassword']);
    $budgetvm->images           = $images->call("v2", "device", "iso", "get", NULL);
    $status                     = new BudgetVM_Api($params['serverpassword']);
    $budgetvm->status           = $status->call("v2", "device", "iso", "get", $var);
    
    $serviceAction              = 'get_usage';
    $templateFile               = 'templates/ipmi.tpl';
  } else {
    // Service Overview
    if($type->success == true){
      $budgetvm->type           = $type->result;
      if($type->result == "dedicated"){
        $device                 = new BudgetVM_Api($params['serverpassword']);
        $device                 = $device->call("v2", "device", "hardware", "get", $var);
        $network                = new BudgetVM_Api($params['serverpassword']);
        $network                = $network->call("v2", "network", "netblock", "get", $var);
        if($network->result == true){
          $budgetvm->network    = $network->result;
        }
      }else{
        $device                 = new BudgetVM_Api($params['serverpassword']);
        $device                 = $device->call("v2", "device", "stats", "get", $var);
      }
      if($device->success == true){
        $budgetvm->device       = $device->result;
      }
    }
    $var->post->start           = strtotime("last Month");
    $var->post->end             = strtotime("now");
    $bandwidth                  = new BudgetVM_Api($params['serverpassword']);
    $budgetvm->bandwidth        = $bandwidth->call("v2", "network", "bandwidth", "post", $var);
    $status                     = new BudgetVM_Api($params['serverpassword']);
    $status                     = $status->call("v2", "device", "power", "get", $var);
    if($status->success == true && $status->success == true){
      $budgetvm->status         = $status->result;
    }else{
      $budgetvm->status         = "Unknown";
    }
    $serviceAction              = 'get_stats';
    $templateFile               = 'templates/overview.tpl';
  }

  try {
    // Call the service's function based on the request action, using the
    // values provided by WHMCS in `$params`.
    return array(
      'tabOverviewReplacementTemplate' => $templateFile,
      'templateVariables' => array(
        'budgetvm'  => $budgetvm,
      ),
    );
  } catch (Exception $e) {
    // Record the error in WHMCS's module log.
    logModuleCall('provisioningmodule', __FUNCTION__, $params, $e->getMessage(), $e->getTraceAsString());

    // In an error condition, display an error page.
    return array(
      'tabOverviewReplacementTemplate' => 'error.tpl',
      'templateVariables' => array(
        'usefulErrorHelper' => $e->getMessage(),
      ),
    );
  }
}
