<div class="row">
  <div class="col30">
    <div class="internalpadding">
      <div class="styled_title"><h2>RDNS Management</h2></div>
      <p>Reverse DNS Management</p><br />
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
      <form name="prov" method="post" action="clientarea.php?action=productdetails&id={$service}&modop=custom&a=reverse" onsubmit="this.update_records.disabled=true;">
      <input type="hidden" name="service" value="{$serviceid|escape}" />
      <input type="hidden" name="modop" value="custom" />
      <input type="hidden" name="a" value="reverse" />
      <input type="hidden" name="update" value="true" />
      <table width="100%" cellpadding="2" cellspacing="0">
        <tr>
          <td width="150" class="fieldarea">IP Address</td>
          <td>Reverse DNS Record</td>
        </tr>
{foreach from=$netblocks->result key=ip item=record}
        <tr>
          <td width="150" class="fieldarea">{$ip}</td>
          <td><input type="text" name="update[{$ip}]" style="width: 350px;" value="{$record}"></td>
        </tr>
{/foreach}
        <tr>
          <td>&nbsp;</td>
          <td><input type="submit" name="update_records" value="Update Records"></td>
        </tr>
      </table>
      </form>
{else}
      Failed to determine device type.
{/if}
    </div>
  </div>
</div>
