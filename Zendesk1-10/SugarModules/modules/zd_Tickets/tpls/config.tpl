<form name="EditView" id="EditView" method="POST" >
	<input type="hidden" name="module" value="zd_Tickets">
	<input type="hidden" name="action" value="SaveConfig">
	<input type="hidden" name="return_module" value="{$RETURN_MODULE}">
	<input type="hidden" name="return_action" value="{$RETURN_ACTION}">
	<input type="hidden" name="source_form" value="config" />

<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
			<input title="{$APP.LBL_SAVE_BUTTON_TITLE}"  onclick="return check_form('EditView');" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button" type="submit" name="button" value=" {$APP.LBL_SAVE_BUTTON_LABEL} ">
			<input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="button" onclick="this.form.action.value='{$RETURN_ACTION}'; this.form.module.value='{$RETURN_MODULE}';" type="submit" name="button" value=" {$APP.LBL_CANCEL_BUTTON_LABEL} ">
		</td>
		<td align="right" nowrap>
			<span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span> {$APP.NTC_REQUIRED}
		</td>
	</tr>
</table>

<table width="100%" border="1" cellspacing="0" cellpadding="0" class="edit view">
  
	<tr><th align="left" scope="row" colspan="3"><h4>Credentials</h4></tr>

	<tr>
    <td width="20%" scope="row">Help Desk URL <span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span></td>
    <td width="60%" style="whitespace:nowrap">
  	  <select name='system_zendesk_https' value="{$zendesk_https}">
        <option value='0' {if !$zendesk_https}selected="selected"{/if}>http://</option>
        <option value='1' {if $zendesk_https}selected="selected"{/if}>https://</option>
      </select>
      <input id='system_zendesk_instance' name='system_zendesk_instance' size='25' maxlength='128' type="text" value="{$zendesk_instance}" style='width:250px'>      
      <span id='system_zendesk_instance_error' class="required"></span>
      eg: company.zendesk.com
      <script type="text/javascript">
        document.getElementById('system_zendesk_instance').onchange = function() {ldelim}
          reg = new RegExp('^([a-zA-Z0-9]([a-zA-Z0-9\-]{ldelim}0,61{rdelim}[a-zA-Z0-9])?\.)*[a-zA-Z0-9]([a-zA-Z0-9\-]{ldelim}0,61{rdelim}[a-zA-Z0-9])?$');
          if (reg.test(this.value)) {ldelim}
            document.getElementById('system_zendesk_instance_error').innerHTML = '';
          {rdelim} else {ldelim}
            document.getElementById('system_zendesk_instance_error').innerHTML = 'Incorrect format';
          {rdelim}
        {rdelim}
      </script>
      
      </td>
    <td width="20%" rowspan="3">For viewing tickets only. Users need provide own credentials to create/edit tickets.</td>
  </tr>

	<tr>
    <td width="20%" scope="row">Email address <span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span></td>
    <td width="60%" > <input id='system_zendesk_login' name='system_zendesk_login' size='25' maxlength='128' type="text" value="{$zendesk_login}" style='width:250px'></td>
  </tr>

	<tr>
    <td width="20%" scope="row">Password <span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span></td>
    <td width="60%" > <input id='system_zendesk_password' name='system_zendesk_password' size='25' maxlength='128' type="password" style='width:250px'></td>
  </tr>

	<tr><th align="left" scope="row" colspan="3"><h4>Advanced</h4></th></tr>

  <tr>
  	<td width="20%" scope="row" valign='top'>Show Account tickets by Zendesk organization</td>
  	<td width="60%"  valign='top'>
  		<input type='hidden' name='system_zendesk_use_account_name' value='0'><input name="system_zendesk_use_account_name" value="1" class="checkbox" type="checkbox" {if $use_account_name==1}checked='checked'{/if}>
  	</td>
  </tr>

	<tr><th align="left" scope="row" colspan="3"><h4>Default settings</h4></th></tr>

	<tr>
    <td width="20%" scope="row">Tickets per page</td>
    <td width="60%" style="whitespace:nowrap"><input id='system_zendesk_per_page' name='system_zendesk_per_page' size='25' maxlength='128' type="text" value="{$per_page}" style='width:100px'></td>
    <td width="20%" rowspan="3">Users will be able to override these settings.</td>
  </tr>

  <tr>
  	<td width="20%" scope="row" valign='top'>Sort order</td>
  	<td width="60%"  valign='top'>													
      <select name='system_zendesk_order_by'>
        {foreach from=$columns key=key item=option}
          <option value='{$key}' {if ($order_by==$key)}selected="selected"{/if}>{$option}</option>
        {/foreach}
      </select>
  	</td>
  </tr>

  <tr>
  	<td width="20%" scope="row" valign='top'>Sort descending</td>
  	<td width="60%"  valign='top'>													
  		<input type='hidden' name='system_zendesk_sort' value='0'><input name="system_zendesk_sort" value="1" class="checkbox" type="checkbox" {if $sort==1}checked='checked'{/if}>
  	</td>
  </tr>

  <tr>
  	<td width="20%" scope="row" valign='top'>Status filter</td>
  	<td width="60%"  valign='top'>													
      <select name='system_zendesk_status_filter'>
        {foreach from=$statusoptions key=key item=option}
          <option value='{$key}' {if ($status_filter==$key)}selected="selected"{/if}>{$option|escape}</option>
        {/foreach}
      </select>
  	</td>
  </tr>

  <tr>
  	<td width="20%" scope="row" valign='top'>Priority filter</td>
  	<td width="60%"  valign='top'>													
      <select name='system_zendesk_priority_filter'>
        {foreach from=$priorityoptions key=key item=option}
          <option value='{$key}' {if ($priority_filter==$key)}selected="selected"{/if}>{$option|escape}</option>
        {/foreach}
      </select>
  	</td>
  </tr>

  <tr>
  	<td width="20%" scope="row" valign='top'>Type filter</td>
  	<td width="60%"  valign='top'>													
      <select name='system_zendesk_type_filter'>
        {foreach from=$typeoptions key=key item=option}
          <option value='{$key}' {if ($type_filter==$key)}selected="selected"{/if}>{$option|escape}</option>
        {/foreach}
      </select>
  	</td>
  </tr>

</table>

<div style="padding-top:2px;">
	<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" onclick="return check_form('EditView');" class="button" type="submit" name="button" value=" {$APP.LBL_SAVE_BUTTON_LABEL} ">
	<input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" class="button" onclick="this.form.action.value='{$RETURN_ACTION}'; this.form.module.value='{$RETURN_MODULE}';" type="submit" name="button" value=" {$APP.LBL_CANCEL_BUTTON_LABEL} ">
</div>

</form>

<script type="text/javascript">
initEditView(document.forms.EditView);
addToValidate('EditView', 'system_zendesk_instance', 'varchar', true,' Help Desk URL');
addToValidate('EditView', 'system_zendesk_login', 'varchar', true, 'Email address');
addToValidate('EditView', 'system_zendesk_password', 'varchar', true, 'Password');
</script>
