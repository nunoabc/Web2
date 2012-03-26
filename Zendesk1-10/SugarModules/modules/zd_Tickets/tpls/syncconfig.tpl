<script language="javascript" type="text/javascript">
  var case_fields = [];
  {foreach from=$dropdowns key=key item=options}
    case_fields['{$key}'] = {ldelim}
    {foreach from=$options key=name item=value}
      '{$name}': '{$value}',
    {/foreach}
    {rdelim};
  {/foreach}
  
{literal}
  
  function getFieldOptionHTML(case_field, field_name, value) {
    if (typeof(case_fields[case_field]) != "undefined") {
      var options = "<option value=''></option>";
      for (var name in case_fields[case_field]) {
        var val = case_fields[case_field][name];
        options += "<option value='" + name + "'" + ((value == name) ? " selected='selected'" : '') + ">" + val + "</option>";
      }
      return "<select name='" + field_name + "' style='width:260px'>" + options + "</select>";
    } else {
      return "<input name='" + field_name + "' type='text' value='" + value + "' style='width:250px'>";
    }
  }
  
  function updateFieldOptions(case_field, field) {
    for (var i = 0; i < 5; i++) {
      var field_name = 'system_zendesk_' + field + '_map_' + i;
      var oldvalue = document.getElementsByName(field_name)[0].value;
      document.getElementById(field + i).innerHTML = getFieldOptionHTML(case_field, field_name, oldvalue);
    }
  }
{/literal}
</script>

<form name="ConfigureSettings" id="EditView" method="POST" >
	<input type="hidden" name="module" value="zd_Tickets">
	<input type="hidden" name="action" value="SaveConfig">
	<input type="hidden" name="return_module" value="{$RETURN_MODULE}">
	<input type="hidden" name="return_action" value="{$RETURN_ACTION}">
	<input type="hidden" name="source_form" value="config" />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
			<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button" type="submit" name="button" value=" {$APP.LBL_SAVE_BUTTON_LABEL} ">
			<input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="button" onclick="this.form.action.value='{$RETURN_ACTION}'; this.form.module.value='{$RETURN_MODULE}';" type="submit" name="button" value=" {$APP.LBL_CANCEL_BUTTON_LABEL} ">
		</td>
		<td align="right" nowrap>
			<span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span> {$APP.NTC_REQUIRED}
		</td>
	</tr>
</table>

<table width="100%" border="1" cellspacing="0" cellpadding="0" class="edit view">
	<tr><th align="left" scope="row" colspan="2"><h4>Zendesk Ticket Status</h4></th></tr>
	<tr>
    <td width="10%" scope="row">Map field to</td>
    <td width="90%" style="whitespace:nowrap">
      <select name="system_zendesk_status_map" onchange="updateFieldOptions(this.value, 'status');" style="width:260px">
        <option value=''></option>
        {foreach from=$case_fields key=key item=option}
          <option value='{$key}' {if ($system_zendesk_status_map==$key)}selected="selected"{/if}>{$option}</option>
        {/foreach}
      </select>
    </td>
  </tr>
	<tr><td width="10%" scope="row">New</td><td width="90%" style="whitespace:nowrap"><div id="status0"></div></td></tr>
	<tr><td width="10%" scope="row">Open</td><td width="90%" style="whitespace:nowrap"><div id="status1"></div></td></tr>
	<tr><td width="10%" scope="row">Pending</td><td width="90%" style="whitespace:nowrap"><div id="status2"></div></td></tr>
	<tr><td width="10%" scope="row">Solved</td><td width="90%" style="whitespace:nowrap"><div id="status3"></div></td></tr>
	<tr><td width="10%" scope="row">Closed</td><td width="90%" style="whitespace:nowrap"><div id="status4"></div></td></tr>

	<tr><th align="left" scope="row" colspan="2"><h4>Zendesk Ticket Priority</h4></th></tr>
	<tr>
    <td width="10%" scope="row">Map field to</td>
    <td width="90%" style="whitespace:nowrap">
      <select name="system_zendesk_priority_map" onchange="updateFieldOptions(this.value, 'priority');" style="width:260px">
        <option value=''></option>
        {foreach from=$case_fields key=key item=option}
          <option value='{$key}' {if ($system_zendesk_priority_map==$key)}selected="selected"{/if}>{$option}</option>
        {/foreach}
      </select>
    </td>
  </tr>
	<tr><td width="10%" scope="row">(no value)</td><td width="90%" style="whitespace:nowrap"><div id="priority0"></div></td></tr>
	<tr><td width="10%" scope="row">Low</td><td width="90%" style="whitespace:nowrap"><div id="priority1"></div></td></tr>
	<tr><td width="10%" scope="row">Normal</td><td width="90%" style="whitespace:nowrap"><div id="priority2"></div></td></tr>
	<tr><td width="10%" scope="row">High</td><td width="90%" style="whitespace:nowrap"><div id="priority3"></div></td></tr>
	<tr><td width="10%" scope="row">Urgent</td><td width="90%" style="whitespace:nowrap"><div id="priority4"></div></td></tr>

	<tr><th align="left" scope="row" colspan="2"><h4>Zendesk Ticket Type</h4></th></tr>
	<tr>
    <td width="10%" scope="row">Map field to</td>
    <td width="90%" style="whitespace:nowrap">
      <select name="system_zendesk_type_map" onchange="updateFieldOptions(this.value, 'type');" style="width:260px">
        <option value=''></option>
        {foreach from=$case_fields key=key item=option}
          <option value='{$key}' {if ($system_zendesk_type_map==$key)}selected="selected"{/if}>{$option}</option>
        {/foreach}
      </select>
    </td>
  </tr>
	<tr><td width="10%" scope="row">(no value)</td><td width="90%" style="whitespace:nowrap"><div id="type0"></div></td></tr>
	<tr><td width="10%" scope="row">Question</td><td width="90%" style="whitespace:nowrap"><div id="type1"></div></td></tr>
	<tr><td width="10%" scope="row">Incident</td><td width="90%" style="whitespace:nowrap"><div id="type2"></div></td></tr>
	<tr><td width="10%" scope="row">Problem</td><td width="90%" style="whitespace:nowrap"><div id="type3"></div></td></tr>
	<tr><td width="10%" scope="row">Task</td><td width="90%" style="whitespace:nowrap"><div id="type4"></div></td></tr>

</table>

<script language="javascript" type="text/javascript">
document.getElementById('status0').innerHTML = getFieldOptionHTML('{$system_zendesk_status_map}', 'system_zendesk_status_map_0', '{$system_zendesk_status_map_0}');
document.getElementById('status1').innerHTML = getFieldOptionHTML('{$system_zendesk_status_map}', 'system_zendesk_status_map_1', '{$system_zendesk_status_map_1}');
document.getElementById('status2').innerHTML = getFieldOptionHTML('{$system_zendesk_status_map}', 'system_zendesk_status_map_2', '{$system_zendesk_status_map_2}');
document.getElementById('status3').innerHTML = getFieldOptionHTML('{$system_zendesk_status_map}', 'system_zendesk_status_map_3', '{$system_zendesk_status_map_3}');
document.getElementById('status4').innerHTML = getFieldOptionHTML('{$system_zendesk_status_map}', 'system_zendesk_status_map_4', '{$system_zendesk_status_map_4}');
document.getElementById('priority0').innerHTML = getFieldOptionHTML('{$system_zendesk_priority_map}', 'system_zendesk_priority_map_0', '{$system_zendesk_priority_map_0}');
document.getElementById('priority1').innerHTML = getFieldOptionHTML('{$system_zendesk_priority_map}', 'system_zendesk_priority_map_1', '{$system_zendesk_priority_map_1}');
document.getElementById('priority2').innerHTML = getFieldOptionHTML('{$system_zendesk_priority_map}', 'system_zendesk_priority_map_2', '{$system_zendesk_priority_map_2}');
document.getElementById('priority3').innerHTML = getFieldOptionHTML('{$system_zendesk_priority_map}', 'system_zendesk_priority_map_3', '{$system_zendesk_priority_map_3}');
document.getElementById('priority4').innerHTML = getFieldOptionHTML('{$system_zendesk_priority_map}', 'system_zendesk_priority_map_4', '{$system_zendesk_priority_map_4}');
document.getElementById('type0').innerHTML = getFieldOptionHTML('{$system_zendesk_type_map}', 'system_zendesk_type_map_0', '{$system_zendesk_type_map_0}');
document.getElementById('type1').innerHTML = getFieldOptionHTML('{$system_zendesk_type_map}', 'system_zendesk_type_map_1', '{$system_zendesk_type_map_1}');
document.getElementById('type2').innerHTML = getFieldOptionHTML('{$system_zendesk_type_map}', 'system_zendesk_type_map_2', '{$system_zendesk_type_map_2}');
document.getElementById('type3').innerHTML = getFieldOptionHTML('{$system_zendesk_type_map}', 'system_zendesk_type_map_3', '{$system_zendesk_type_map_3}');
document.getElementById('type4').innerHTML = getFieldOptionHTML('{$system_zendesk_type_map}', 'system_zendesk_type_map_4', '{$system_zendesk_type_map_4}');
</script>

<div style="padding-top:2px;">
	<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" class="button" type="submit" name="button" value=" {$APP.LBL_SAVE_BUTTON_LABEL} ">
	<input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" class="button" onclick="this.form.action.value='{$RETURN_ACTION}'; this.form.module.value='{$RETURN_MODULE}';" type="submit" name="button" value=" {$APP.LBL_CANCEL_BUTTON_LABEL} ">
</div>

</form>

