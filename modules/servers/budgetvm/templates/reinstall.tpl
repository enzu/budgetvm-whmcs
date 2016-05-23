<div class="row">
  <div class="col-sm-8">
    <h2>Operating System Reinstall</h2>
  </div>
  <div class="col-sm-4">
    <form method="post" action="clientarea.php?action=productdetails">
      <input type="hidden" name="id" value="{$serviceid}" />
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
{if $budgetvm->status->success == true && isset($budgetvm->status->result->statusprogress)}
  <div class="row">
    <div class="col-sm-12">
      <div class="progress">
        <div class="progress-bar" role="progressbar" aria-valuenow="{$budgetvm->status->result->statusprogress}" aria-valuemin="0" aria-valuemax="100" style="width: {$budgetvm->status->result->statusprogress}%;">
          <span class="sr-only">{$budgetvm->status->result->statusprogress}% Complete</span>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-5">
      Hostname
    </div>
    <div class="col-sm-7">
      {$budgetvm->status->result->hostname}
    </div>
  </div>
  <div class="row">
    <div class="col-sm-5">
      Status
    </div>
    <div class="col-sm-7">
      {$budgetvm->status->result->statusmsg}
    </div>
  </div>
  <div class="row">
    <div class="col-sm-5">
      Profile
    </div>
    <div class="col-sm-7">
      {$budgetvm->status->result->profilename}
    </div>
  </div>
  <div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-4">
      <form class="form-inline" name="launch_ipmi" method="post" action="clientarea.php?action=productdetails&id={$budgetvm->service}&customAction=reinstall" onsubmit="this.pxe_cancel.disabled=true;">
      <input type="hidden" name="service" value="{$budgetvm->service}" />
      <input type="hidden" name="customAction" value="reinstall" />
      <input type="hidden" name="cancel" value="true" />
      <input type="submit" name="pxe_cancel" value="Cancel Installation" class="btn btn-primary">
      </form>
    </div>
    <div class="col-sm-4"></div>
  </div>
{else}
  <div class="row">
    <form class="form-inline" name="reinstall" method="post" action="clientarea.php?action=productdetails&id={$budgetvm->service}&customAction=reinstall" onsubmit="this.reinstall.disabled=true;">
    <input type="hidden" name="service" value="{$budgetvm->service}" />
    <input type="hidden" name="customAction" value="reinstall" />
    <input type="hidden" name="update" value="true" />
    <div class="col-sm-4">
      Operating System
    </div>
    <div class="col-sm-4">
      <select id="profile" name="profile" class="form-control">
  {foreach from=$budgetvm->profiles item=profile}
        <option value="{$profile->value}">{$profile->name}</option>
  {/foreach}
      </select>
    </div>
    <div class="col-sm-4">
      <input type="submit" name="reinstall" value="Provision server" onclick="return confirm('This will delete all existing data on disk. Are you sure?');" class="btn btn-primary">
    </div>
    </form>
  </div>
{/if}