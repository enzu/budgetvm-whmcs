<div class="row">
  <div class="col-sm-8">
    <h2>Power Management</h2>
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
{if $budgetvm->type == "dedicated"}
<div class="row">
  <div class="col-sm-5">
    Current Power Status
  </div>
  <div class="col-sm-7">
{if $budgetvm->powerStatus->success == true}  
{if $budgetvm->powerStatus->result == "on"}
    <span class="label label-success">Powered On</span>
{else}
    <span class="label label-danger">Powered Off</span>
{/if}
{else}
    <span class="label label-info">Unknown</span>
{/if}
  </div>
</div>
  <form class="form-inline" name="power" method="post" action="clientarea.php?action=productdetails&id={$budgetvm->service}&customAction=power" onsubmit="this.power_button.disabled=true;">
  <input type="hidden" name="service" value="{$budgetvm->service}" />
  <input type="hidden" name="customAction" value="power" />
  <div class="row">
    <div class="col-sm-4">
      <div class="row">
        <div class="col-sm-12">
          <h5>Power Action</h5>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <label><input type="radio" name="poweraction" value="reboot" checked="true"> Reboot</label>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <label><input type="radio" name="poweraction" value="on"> Power On</label>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <label><input type="radio" name="poweraction" value="off"> Power Off</label>
        </div>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="row">
        <div class="col-sm-12">
          <h5>One Time Boot Order</h5>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <label><input type="radio" name="bootorder" value="standard" checked="true"> Standard Boot Order</label>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <label><input type="radio" name="bootorder" value="pxe"> Boot to PXE</label>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <label><input type="radio" name="bootorder" value="cdrom"> Boot to ISO Image</label>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <label><input type="radio" name="bootorder" value="bios"> Boot to BIOS</label>
        </div>
      </div>
    </div>
    <div class="col-sm-4">
      <input type="submit" name="power_button" value="Perform Action" class="btn btn-primary">
    </div>
  </div>
  </form>
{else}
<div class="row">
  <div class="col-sm-5">
    Current Power Status
  </div>
  <div class="col-sm-7">
{if $budgetvm->powerStatus->success == true}  
{if $budgetvm->powerStatus->result == "online"}
    <span class="label label-success">Powered On</span>
{else}
    <span class="label label-danger">Powered Off</span>
{/if}
{else}
    <span class="label label-info">Unknown</span>
{/if}
  </div>
</div>
  <form class="form-inline" name="power" method="post" action="clientarea.php?action=productdetails&id={$budgetvm->service}&customAction=power" onsubmit="this.power_button.disabled=true;">
  <input type="hidden" name="service" value="{$budgetvm->service}" />
  <input type="hidden" name="customAction" value="power" />
  <div class="row">
    <div class="col-sm-6">
      <div class="row">
        <div class="col-sm-12">
          <h5>Power Action</h5>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <label><input type="radio" name="poweraction" value="reboot" checked="true"> Reboot</label>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <label><input type="radio" name="poweraction" value="on"> Power On</label>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <label><input type="radio" name="poweraction" value="off"> Power Off</label>
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <input type="submit" name="power_button" value="Perform Action" class="btn btn-primary">
    </div>
  </div>
  </form>
{/if}
