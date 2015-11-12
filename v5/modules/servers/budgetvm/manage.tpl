<div class="row">
  <div class="col30">
    <div class="internalpadding">
      <div class="styled_title"><h2>Out of Band Management</h2></div>
      <p>Out of Band System Management (IPMI)</p>
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
	      <td width="150" class="fieldarea">Current Image Status</td>
{if $status->success == true}
{if $status->result == "Mounted"}
	      <td style="text-align: left;vertical-align:middle; line-height: 28px;"><span class="label active">Image Mounted</span></td>
{else}
	      <td><span class="label terminated">No Image Mounted</span></td>
{/if}
{else}
	      <td><span class="label suspended">Unknown</span></td>>
{/if}
	    </tr>
      </table>
{if $status->result == "Mounted"}
      <h3>Please unmount the image before mounting another.</h3>
      <form class="form-inline" name="unmount_image" method="post" action="clientarea.php?action=productdetails&id={$service}&modop=custom&a=manage" onsubmit="this.unmount_image.disabled=true;">
      <input type="hidden" name="service" value="{$service|escape}" />
      <input type="hidden" name="modop" value="custom" />
      <input type="hidden" name="a" value="manage" />
      <input type="hidden" name="image_unmount" value="true" />
      <input type="submit" id="unmount_image" value="Unmount Image">
      </form>
{else}
      <form class="form-inline" name="mount_image" method="post" action="clientarea.php?action=productdetails&id={$service}&modop=custom&a=manage" onsubmit="this.mount_image.disabled=true;">
      <input type="hidden" name="service" value="{$service|escape}" />
      <input type="hidden" name="modop" value="custom" />
      <input type="hidden" name="a" value="manage" />
      <input type="hidden" name="image_mount" value="true" />
      <table width="100%" cellpadding="2" cellspacing="0">
        <tr>
          <td width="150" class="fieldarea">Select ISO</td>
          <td>
            <select id="profile" name="profile">
{foreach from=$images->result item=profile}
              <option value="{$profile->value}">{$profile->name}</option>
{/foreach}
            </select>
          </td>
          <td><input type="submit" name="mount_image" value="Mount ISO Image"></td>
        </tr>
      </table>
      </form>
{/if}
      <table width="100%" cellpadding="2" cellspacing="0" class="frame">
        <tr>
          <td>
            <form class="form-inline" name="reset_ipmi" method="post" action="clientarea.php?action=productdetails&id={$service}&modop=custom&a=manage" onsubmit="this.reset_ipmi.disabled=true;">
            <input type="hidden" name="service" value="{$service|escape}" />
            <input type="hidden" name="modop" value="custom" />
            <input type="hidden" name="a" value="manage" />
            <input type="hidden" name="ipmi_reset" value="true" />
            <input type="submit" name="reset_ipmi" value="Reset IPMI Controller">
            </form>
          </td>
          <td>
            <form class="form-inline" name="launch_ipmi" method="post" action="clientarea.php?action=productdetails&id={$service}&modop=custom&a=manage" onsubmit="this.launch_ipmi.disabled=true;">
            <input type="hidden" name="service" value="{$service|escape}" />
            <input type="hidden" name="modop" value="custom" />
            <input type="hidden" name="a" value="manage" />
            <input type="hidden" name="ipmi_launch" value="true" />
            <input type="submit" name="launch_ipmi" value="Launch KVM Session">
            </form>
          </td>
        </tr>
      </table>
{else}
      <h3>Remote Management Console</h3>
      <table width="100%" cellpadding="2" cellspacing="0" class="frame">
        <tr>
          <td>
            <form class="form-inline" name="launch_vm_ipmi" method="post" action="clientarea.php?action=productdetails&id={$service}&modop=custom&a=manage" onsubmit="this.launch_vm_ipmi.disabled=true;">
            <input type="hidden" name="service" value="{$service|escape}" />
            <input type="hidden" name="modop" value="custom" />
            <input type="hidden" name="a" value="manage" />
            <input type="hidden" name="ipmi_vm_launch" value="true" />
            <input type="submit" name="launch_vm_ipmi" value="Open Console Session">
            </form>
          </td>
        </tr>
      </table>
{/if}
{else}
      Failed to determine device type.
{/if}
    </div>
  </div>
</div>
