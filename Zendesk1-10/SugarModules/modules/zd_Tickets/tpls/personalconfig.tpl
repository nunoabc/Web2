<form name="ConfigureSettings" id="EditView" method="POST" >
	<input type="hidden" name="module" value="zd_Tickets">
	<input type="hidden" name="action" value="SaveConfig">
  <input type="hidden" name="return_module" value="{$RETURN_MODULE}">
  <input type="hidden" name="return_action" value="{$RETURN_ACTION}">
  <input type="hidden" name="return_id" value="{$RETURN_ID}">
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
  
	<tr><th align="left" scope="row" colspan="2"><h4>Personal credentials (only required to edit and create tickets)</h4></th></tr>

	<tr>
    <td width="20%" scope="row">Help Desk URL</span></td>
    <td width="80%" style="whitespace:nowrap"><a href="{$zendesk_instance}" target="_blank">{$zendesk_instance}</a></td>
  </tr>
  
	<tr>
    <td width="20%" scope="row">Email address <span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span></td>
    <td width="80%"><input id='system_zendesk_login' name='system_zendesk_login_{$current_user_id}' tabindex='2' size='25' maxlength='128' type="text" value="{$zendesk_login}" style='width:250px'></td>
  </tr>

	<tr>
    <td width="20%" scope="row">Password <span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span></td>
    <td width="80%"><input id='system_zendesk_password' name='system_zendesk_password_{$current_user_id}' tabindex='3' size='25' maxlength='128' type="password" style='width:250px'></td>
  </tr>

	<tr><th align="left" scope="row" colspan="2"><h4>Ticket view settings</h4></th></tr>

	<tr>
    <td width="20%" scope="row">Tickets per page</td>
    <td width="80%" style="whitespace:nowrap"><input id='system_zendesk_per_page' name='system_zendesk_per_page' tabindex='1' size='25' maxlength='128' type="text" value="{$per_page}" style='width:100px'></td>
  </tr>

  <tr>
  	<td width="20%" scope="row" valign='top'>Sort order</td>
  	<td width="80%"  valign='top'>													
      <select name='system_zendesk_order_by_{$current_user_id}'>
        {foreach from=$columns key=key item=option}
          <option value='{$key}' {if ($order_by==$key)}selected="selected"{/if}>{$option}</option>
        {/foreach}
      </select>
  	</td>
  </tr>

  <tr>
  	<td width="20%" scope="row" valign='top'>Sort descending</td>
  	<td width="80%"  valign='top'>													
  		<input type='hidden' name='system_zendesk_sort_{$current_user_id}' value='0'><input name="system_zendesk_sort_{$current_user_id}" tabindex='1' value="1" class="checkbox" type="checkbox" {if $sort==1}checked='checked'{/if}>
  	</td>
  </tr>

  <tr>
  	<td width="20%" scope="row" valign='top'>Status filter</td>
  	<td width="80%"  valign='top'>													
      <select name='system_zendesk_status_filter_{$current_user_id}'>
        {foreach from=$statusoptions key=key item=option}
          <option value='{$key}' {if ($status_filter==$key)}selected="selected"{/if}>{$option|escape}</option>
        {/foreach}
      </select>
  	</td>
  </tr>

  <tr>
  	<td width="20%" scope="row" valign='top'>Priority filter</td>
  	<td width="80%"  valign='top'>													
      <select name='system_zendesk_priority_filter_{$current_user_id}'>
        {foreach from=$priorityoptions key=key item=option}
          <option value='{$key}' {if ($priority_filter==$key)}selected="selected"{/if}>{$option|escape}</option>
        {/foreach}
      </select>
  	</td>
  </tr>

  <tr>
  	<td width="20%" scope="row" valign='top'>Type filter</td>
  	<td width="80%"  valign='top'>													
      <select name='system_zendesk_type_filter_{$current_user_id}'>
        {foreach from=$typeoptions key=key item=option}
          <option value='{$key}' {if ($type_filter==$key)}selected="selected"{/if}>{$option|escape}</option>
        {/foreach}
      </select>
  	</td>
  </tr>

</table>

<div style="padding-top:2px;">
	<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" class="button" type="submit" name="button" value=" {$APP.LBL_SAVE_BUTTON_LABEL} ">
	<input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" class="button" onclick="this.form.action.value='{$RETURN_ACTION}'; this.form.module.value='{$RETURN_MODULE}';" type="submit" name="button" value=" {$APP.LBL_CANCEL_BUTTON_LABEL} ">
</div>

</form>
