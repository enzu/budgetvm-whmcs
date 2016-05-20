<div class="row">
  <div class="col30">
    <div class="internalpadding">
      <div class="styled_title"><h2>Network Graphs</h2></div>
      <p>Network Traffic Graphs</p>
      <br />
      <p><input type="button" value="&laquo; Back to Service" class="btn" onclick="window.location='clientarea.php?action=productdetails&id={$service}'" /></p>
      <br />
{if $type->result == "dedicated"}
      <h4>Graph Period</h4>
      <select id="period" onchange="window.location='clientarea.php?action=productdetails&id={$service}&modop=custom&a=network&period=' + this.options[this.selectedIndex].value;">
{if isset($period)}
{if $period == "hour"}
		<option value="hour" checked>--> Last Hour</option>
{elseif $period == "day"}
		<option value="day" checked>--> Last Day</option>
{elseif $period == "week"}
		<option value="week" checked>--> Last Week</option>
{elseif $period == "month"}
		<option value="month" checked>--> Last Month</option>
{elseif $period == "year"}
		<option value="year" checked>--> Last Year</option>		
{/if}
{else}
		<option value="month" checked>--> Last Month</option>
{/if}
		<option value="hour">Last Hour</option>
        <option value="day">Last Day</option>
        <option value="week">Last Week</option>
        <option value="month">Last Month</option>
        <option value="year">Last Year</option>
      </select>
{/if}
    </div>
  </div>
  <div class="col70">
    <div class="internalpadding">
{if $type->success == true}
{if $type->result == "dedicated"}
{foreach from=$bandwidth->result->result key=switch item=switchData}
      <ul>
        <li>{$switch}
          <ul>
{foreach from=$switchData key=port item=portData}
            <li><h5>Port: {$port}</h5><img src="data:image/png;base64, {$portData->graph}"></li>
{/foreach}
          </ul>
        </li>
      </ul>
{/foreach}
{else}
      <h3>Network Graphs</h3>
      <ul>
      <li>Bandwidth Usage: {$bandwidth->result->bandwidth} GB</h5>
      </ul>
      <img src="data:image/png;base64, {$bandwidth->result->graph}">
{/if}
{else}
      Failed to determine device type.
{/if}
    </div>
  </div>
</div>
