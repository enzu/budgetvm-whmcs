<?php

use WHMCS\View\Menu\Item as MenuItem;
 
add_hook('ClientAreaPrimarySidebar', 1, function(MenuItem $primarySidebar)
{
  if (!is_null($primarySidebar->getChild('Service Details Overview'))) {
    $service = Menu::context('service');
    if ($service->domainStatus==="Active"){
      $serviceid    = (int) $_GET['id'];
      $primarySidebar->getChild('Service Details Actions')->removeChild('Change Password');
      $primarySidebar->getChild('Service Details Overview')->removeChild('Information');
      $primarySidebar->getChild('Service Details Overview')->addChild('Service Dashboard', array(
          'label' => 'Service Dashboard',
          'uri' => '/clientarea.php?action=productdetails&id=' . $serviceid, 
          'order' => '100'
        ))->setClass(( !isset($_GET['customAction']) ? 'active' : ''));
        $primarySidebar->getChild('Service Details Overview')->addChild('Manage Reverse DNS', array(
          'label' => 'Manage Reverse DNS',
          'uri' => '/clientarea.php?action=productdetails&id=' . $serviceid . '&customAction=reverse', 
          'order' => '101'
        ))->setClass(( isset($_GET['customAction']) && $_GET['customAction'] == "reverse" ? 'active' : ''));
      $primarySidebar->getChild('Service Details Overview')->addChild('Network Graphs', array(
          'label' => 'Network Graphs',
          'uri' => '/clientarea.php?action=productdetails&id=' . $serviceid . '&customAction=network', 
          'order' => '102'
        ))->setClass(( isset($_GET['customAction']) && $_GET['customAction'] == "network" ? 'active' : ''));
      $primarySidebar->getChild('Service Details Overview')->addChild('Power Management', array(
          'label' => 'Power Management',
          'uri' => '/clientarea.php?action=productdetails&id=' . $serviceid . '&customAction=power', 
          'order' => '103'
        ))->setClass(( isset($_GET['customAction']) && $_GET['customAction'] == "power" ? 'active' : ''));
      $primarySidebar->getChild('Service Details Overview')->addChild('Reinstall Server', array(
          'label' => 'Reinstall Server',
          'uri' => '/clientarea.php?action=productdetails&id=' . $serviceid . '&customAction=reinstall', 
          'order' => '104'
        ))->setClass(( isset($_GET['customAction']) && $_GET['customAction'] == "reinstall" ? 'active' : ''));
      $primarySidebar->getChild('Service Details Overview')->addChild('Out of Band Management (IPMI)', array(
          'label' => 'Out of Band Management (IPMI)',
          'uri' => '/clientarea.php?action=productdetails&id=' . $serviceid . '&customAction=ipmi', 
          'order' => '105'
        ))->setClass(( isset($_GET['customAction']) && $_GET['customAction'] == "ipmi" ? 'active' : ''));      
    }
  }
});