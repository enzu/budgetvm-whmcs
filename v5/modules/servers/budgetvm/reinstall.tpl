<div class="row">
  <div class="col30">
    <div class="internalpadding">
      <div class="styled_title"><h2>Operating System Reinstall</h2></div>
      <p>Operating System Reinstall</p>
      <br />
      <p><input type="button" value="&laquo; Back to Service" class="btn" onclick="window.location='clientarea.php?action=productdetails&id={$service}'" /></p>
    </div>
  </div>
  <div class="col70">
    <div class="internalpadding">
{if $type->success == true}
{if isset($return) && !empty($return)}
{if $return->success == true}
      <div class="alert alert-block alert-success">
{else}
      <div class="alert alert-block alert-warning">
{/if}
        {$return->result}
      </div>
{/if}
{if $status->success == true && !empty($status->result->result)}
      <h3>Reload in Progress</h3>
      <table width="100%" cellpadding="2" cellspacing="0">
        <tr>
          <td width="150" class="fieldarea">Hostname</td>
          <td>{$status->result->result->hostname}</td>
        </tr>
        <tr>
          <td width="150" class="fieldarea">Status</td>
          <td>{$status->result->result->statusmsg}</td>
        </tr>
        <tr>
          <td width="150" class="fieldarea">Profile</td>
          <td>{$status->result->result->profilename}</td>
        </tr>
      </table>
{else}
      <form class="form-inline" name="reinstall" method="post" action="clientarea.php?action=productdetails&id={$service}&modop=custom&a=reinstall" onsubmit="this.reinstall.disabled=true;">
      <input type="hidden" name="service" value="{$service|escape}" />
      <input type="hidden" name="modop" value="custom" />
      <input type="hidden" name="a" value="reinstall" />
      <input type="hidden" name="update" value="true" />
      <table width="100%" cellpadding="2" cellspacing="0">
        <tr>
          <td width="150" class="fieldarea">Operating System</td>
          <td>
            <select id="profile" name="profile">
{foreach from=$profiles->result item=profile}
              <option value="{$profile->value}">{$profile->name}</option>
{/foreach}
            </select>
          </td>
        </tr>
        <tr>
          <td colspan="2"><input type="submit" name="reinstall" value="Provision server (WARNING: overwrites data on disk)" onclick="return confirm('This will delete all existing data on disk. Are you sure?');"></td>
        </tr>
      </table>
      </form>
{/if}
{else}
      Failed to determine device type.
{/if}
    </div>
  </div>
</div>
