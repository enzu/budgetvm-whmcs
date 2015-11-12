<div class="row">
  <div class="col-sm-8">
    <h2>Out of Band Management (IPMI)</h2>
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

{if $budgetvm->type == "dedicated"}
<div class="row">
  <div class="col-sm-4">
    Current Image Status
  </div>
{if $budgetvm->status->success == true}
  {if $budgetvm->status->result == "Mounted"}
    <div class="col-sm-4">
      <span class="label label-success">Image Mounted</span>
    </div>
    <div class="col-sm-4">
      <form class="form-inline" name="unmount_image" method="post" action="clientarea.php?action=productdetails&id={$budgetvm->service}&customAction=ipmi" onsubmit="this.unmount_image.disabled=true;">
      <input type="hidden" name="service" value="{$budgetvm->service}" />
      <input type="hidden" name="customAction" value="ipmi" />
      <input type="hidden" name="image_unmount" value="true" />
      <input type="submit" id="unmount_image" value="Unmount Image" class="btn btn-primary">
      </form>
    </div>
  {else}
    <div class="col-sm-8">
      <span class="label label-danger">No Image Mounted</span>
    </div>
  {/if}
{else}
  <div class="col-sm-8">
    <span class="label label-info">Unknown</span>
  </div>
{/if}
</div>
<div class="row">
  <div class="col-sm-12"></div>
</div>
{if $budgetvm->status->result == "Mounted"}
<div class="row">
  <div class="col-sm-12">
    <center><h5>Please unmount the image before mounting another.</h5></center>
  </div>
</div>
{else}
<div class="row" style="padding-top: 25px;">
  <form class="form-inline" name="mount_image" method="post" action="clientarea.php?action=productdetails&id={$budgetvm->service}&customAction=ipmi" onsubmit="this.mount_image.disabled=true;">
  <input type="hidden" name="service" value="{$budgetvm->service}" />
  <input type="hidden" name="customAction" value="ipmi" />
  <input type="hidden" name="image_mount" value="true" />
  <div class="col-sm-12">
  <div class="row">
    <div class="col-sm-3">
      Select ISO
    </div>
    <div class="col-sm-6">
      <select id="profile" name="profile" class="form-control">
{foreach from=$budgetvm->images->result item=profile}
        <option value="{$profile->value}">{$profile->name}</option>
{/foreach}
      </select>
    </div>
    <div class="col-sm-3">
      <input type="submit" name="mount_image" value="Mount ISO Image" class="btn btn-primary">
    </div>
  </div>
  </form>
  </div>
</div>
{/if}
<div class="row" style="padding-top: 25px;">
  <div class="col-sm-6">
    <form class="form-inline" name="reset_ipmi" method="post" action="clientarea.php?action=productdetails&id={$budgetvm->service}&customAction=ipmi" onsubmit="this.reset_ipmi.disabled=true;">
    <input type="hidden" name="service" value="{$budgetvm->service}" />
    <input type="hidden" name="customAction" value="ipmi" />
    <input type="hidden" name="ipmi_reset" value="true" />
    <input type="submit" name="reset_ipmi" value="Reset IPMI Controller" class="btn btn-danger">
    </form>
  </div>
  <div class="col-sm-6">
    <form class="form-inline" name="launch_ipmi" method="post" action="clientarea.php?action=productdetails&id={$budgetvm->service}&customAction=ipmi" onsubmit="this.launch_ipmi.disabled=true;">
    <input type="hidden" name="service" value="{$budgetvm->service}" />
    <input type="hidden" name="customAction" value="ipmi" />
    <input type="hidden" name="ipmi_launch" value="true" />
    <input type="submit" name="launch_ipmi" value="Launch KVM Session" class="btn btn-primary">
    </form>
  </div>
</div>
{else}
<h3>Remote Management Console</h3>
<div class="row">
  <div class="col-sm-12">
    <form class="form-inline" name="launch_vm_ipmi" method="post" action="clientarea.php?action=productdetails&id={$budgetvm->service}&customAction=manage" onsubmit="this.launch_vm_ipmi.disabled=true;">
    <input type="hidden" name="service" value="{$budgetvm->service}" />
    <input type="hidden" name="customAction" value="ipmi" />
    <input type="hidden" name="ipmi_vm_launch" value="true" />
    <input type="submit" name="launch_vm_ipmi" value="Open Console Session" class="btn btn-primary">
    </form>
  </div>
</div>
{/if}
