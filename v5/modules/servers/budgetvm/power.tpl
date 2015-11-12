<div class="row">
  <div class="col30">
    <div class="internalpadding">
      <div class="styled_title"><h2>Power Management</h2></div>
      <p>Device Power Management</p>
      <br />
      <p><input type="button" value="&laquo; Back to Service" class="btn" onclick="window.location='clientarea.php?action=productdetails&id={$service}'" /></p>
    </div>
  </div>
  <div class="col70">
    <div class="internalpadding">
{if isset($return) && !empty($return)}
{if $return->success == true}
      <div class="alert alert-block alert-success">
{else}
      <div class="alert alert-block alert-warning">
{/if}
        {$return->result}
      </div>
{/if}
{if $type->success == true}
{if $type->result == "dedicated"}
      <table width="100%" cellpadding="2" cellspacing="0" class="frame">
	    <tr>
	      <td width="150" class="fieldarea">Current Power Status</td>
{if $powerStatus->success == true}
{if $powerStatus->result == "on"}
	      <td><span class="label active">Powered On</span></td>
{else}
	      <td><span class="label terminated">Powered Off</span></td>
{/if}
{else}
	      <td><span class="label suspended">Unknown</span></td>>
{/if}
	    </tr>
      </table>
      <form class="form-inline" name="power" method="post" action="clientarea.php?action=productdetails&id={$service}&modop=custom&a=power" onsubmit="this.power_button.disabled=true;">
      <input type="hidden" name="service" value="{$service}" />
      <input type="hidden" name="modop" value="custom" />
      <input type="hidden" name="a" value="power" />
      <table width="100%" cellpadding="2" cellspacing="0" class="frame">
        <tr>
          <td><h5>Power Action</h5></td>
          <td><h5>One Time Boot Order</h5></td>
        </tr>
        <tr>
          <td><label><input type="radio" name="poweraction" value="reboot" checked="true"> Reboot</td>
          <td><label><input type="radio" name="bootorder" value="standard" checked="true"> Standard Boot Order</td>
        </tr>
        <tr>
          <td><label><input type="radio" name="poweraction" value="on"> Power On</label></td>
          <td><label><input type="radio" name="bootorder" value="pxe"> Boot to PXE</label></td>
        </tr>
        <tr>
          <td><label><input type="radio" name="poweraction" value="off"> Power Off</label></td>
          <td><label><input type="radio" name="bootorder" value="cdrom"> Boot to ISO Image</label></td>
        </tr>
        <tr>
          <td></td>
          <td><label><input type="radio" name="bootorder" value="bios"> Boot to BIOS</label></td>
        </tr>
        <tr>
          <td colspan="2"><input type="submit" name="power_button" value="Perform Action"></td>
        </tr>
      </table>
{else}
      <table width="100%" cellpadding="2" cellspacing="0" class="frame">
	    <tr>
	      <td width="150" class="fieldarea">Current Power Status</td>
{if $powerStatus->success == true}
{if $powerStatus->result == "online"}
	      <td><span class="label active">Powered On</span></td>
{else}
	      <td><span class="label terminated">Powered Off</span></td>
{/if}
{else}
	      <td><span class="label suspended">Unknown</span></td>>
{/if}
	    </tr>
      </table>
      <form class="form-inline" name="power" method="post" action="clientarea.php?action=productdetails&id={$service}&modop=custom&a=power" onsubmit="this.power_button.disabled=true;">
      <input type="hidden" name="service" value="{$service}" />
      <input type="hidden" name="modop" value="custom" />
      <input type="hidden" name="a" value="power" />
      <table width="100%" cellpadding="2" cellspacing="0" class="frame">
        <tr>
          <td><h5>Power Action</h5></td>
          <td></td>
        </tr>
        <tr>
          <td><label><input type="radio" name="poweraction" value="reboot" checked="true"> Reboot</td>
          <td></td>
        </tr>
        <tr>
          <td><label><input type="radio" name="poweraction" value="on"> Power On</label></td>
          <td></td>
        </tr>
        <tr>
          <td><label><input type="radio" name="poweraction" value="off"> Power Off</label></td>
          <td></td>
        </tr>
        <tr>
          <td colspan="2"><input type="submit" name="power_button" value="Perform Action"></td>
        </tr>
      </table>
{/if}
{else}
      Failed to determine device type.
{/if}
    </div>
  </div>
</div>
