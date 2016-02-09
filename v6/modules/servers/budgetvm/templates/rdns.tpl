<div class="row">
  <div class="col-sm-8">
    <h2>Manage Reverse DNS</h2>
  </div>
  <div class="col-sm-4">
    <form method="post" action="clientarea.php?action=productdetails">
      <input type="hidden" name="id" value="{$budgetvm->service}" />
      <button type="submit" class="btn btn-default btn-block"><i class="fa fa-arrow-circle-left"></i> Back to Overview</button>
    </form>
  </div>
</div>
{if isset($budgetvm->return->success)}
<div class="row">
{if $budgetvm->return->success == true && $budgetvm->return->result ne "Action Failed."}
  <div class="alert alert-success">
{else}
  <div class="alert alert-danger">
{/if}
    <p>{$budgetvm->return->result}</p>
  </div>
</div>
{/if}

<div class="row">
  <div class="col-sm-12">
    <form name="prov" method="post" action="clientarea.php?action=productdetails&id={$budgetvm->service}&customAction=reverse" onsubmit="this.update_records.disabled=true;">
    <input type="hidden" name="service" value="{$budgetvm->service}" />
    <input type="hidden" name="customAction" value="reverse" />
    <div class="row">
      <div class="col-sm-4">
        <h5>IP Address</h5>
      </div>
      <div class="col-sm-8">
        <h5>Reverse DNS Record</h5>
      </div>
    </div>
{foreach from=$budgetvm->netblocks->result key=ip item=record}
    <div class="row">
      <div class="col-sm-4">
        <label>{$ip}</label>
      </div>
      <div class="col-sm-8">
        <input type="text" id="update[{$ip}]" name="update[{$ip}]" value="{$record}" class="form-control">
      </div>
    </div>
{/foreach}
    <div class="row">
      <div class="col-sm-4"></div>
      <div class="col-sm-4">
        <input type="submit" name="update_records" value="Update Records" class="btn btn-primary">
      </div>
      <div class="col-sm-4"></div>
    </div>
    </form>
  </div>
</div>
