<form action="javascript:submit()" method="POST" name="EditView" id="EditView" >
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="actionsContainer">
<tr>
<td class="buttons">
<input type="hidden" name="module" value="zd_Tickets">
<input type="hidden" name="record" value="{$bean->id}">
<input type="hidden" name="isDuplicate" value="false">
<input type="hidden" name="action">
<input type="hidden" name="return_module" value="{$RETURN_MODULE}">
<input type="hidden" name="return_action" value="{$RETURN_ACTION}">
<input type="hidden" name="return_id" value="{$RETURN_ID}">
<input type="hidden" name="module_tab"> 
<input type="hidden" name="contact_role">
<input type="hidden" name="relate_to" value="zd_Tickets">
<input type="hidden" name="relate_id" value="">
<input type="hidden" name="offset" value="1">
<input type="hidden" name="nice_id" value="{$bean->nice_id}">
<input title="Save [Alt+S]" accessKey="S" class="button primary" onclick="this.form.action.value='AjaxSave'; return check_form('EditView');" type="submit" name="button" value="Save"> 
<input title="Cancel [Alt+X]" accessKey="X" class="button" onclick="window.location.href='index.php?action={$RETURN_ACTION}&module={$RETURN_MODULE}&record={$RETURN_ID}'; return false;" type="button" name="button" value="Cancel">
<img src="http://disclosures.linuxdefenders.org/webfile/default/loading-spinner.gif" id="saving1" style="display:none;vertical-align:middle" />

{if $bean->can_update}
<div style="float:right">
Macro:
<select id='macroSelector'>
<option value=''>Select...</option>
{foreach from=$bean->getMacros() key=i item=macro}
  <option value='{$macro.id}'>{$macro.title|escape}</option>
{/foreach}
</select>

<img src="http://disclosures.linuxdefenders.org/webfile/default/loading-spinner.gif" id="loading" style="float:right;display:none" />
</div>

<script type="text/javascript">
  document.getElementById('macroSelector').onchange = function() {ldelim}
    applyMacro(this.value);
  {rdelim}
  
  function applyMacro(macro_id) {ldelim}
    var callback = {ldelim}
      success: function(o) {ldelim}
        document.getElementById("loading").style.display = "none";
        response = eval('(' + o.responseText + ')');
        if (response.subject) {ldelim}
          document.getElementById('subject').value = response.subject;
        {rdelim}
        if (response.current_tags) {ldelim}
          document.getElementById('tags').value = response.current_tags;
        {rdelim}
        if (response.priority_id) {ldelim}
          document.getElementById('priority').value = response.priority_id;
        {rdelim}
        if (response.status_id) {ldelim}
          document.getElementById('status').value = response.status_id;
        {rdelim}
        if (response.ticket_type_id) {ldelim}
          document.getElementById('ticket_type').value = response.ticket_type_id;
          updateTicketType(document.getElementById('ticket_type'));
        {rdelim}
        if (response.group_id) {ldelim}
          document.getElementById('group_id').value = response.group_id;
          updateGroupUsers(response.group_id);
        {rdelim}
        if (response.assignee_id) {ldelim}
          document.getElementById('assignee_id').value = response.assignee_id;
        {rdelim}
        if (response.comment) {ldelim}
          {if $new}
            if (response.comment.value) {ldelim}
              document.getElementById('description').value = response.comment.value;
            {rdelim}
          {else}
            if (response.comment.value) {ldelim}
              document.getElementById('comment_value').value = response.comment.value;
            {rdelim}
            if (response.comment.is_public) {ldelim}
              document.getElementById('comment_is_public').checked = (response.comment.is_public == 'true');
            {rdelim}
          {/if}
        {rdelim}
      {rdelim}
    {rdelim}
    document.getElementById("loading").style.display = "block";
    var connectionObject = YAHOO.util.Connect.asyncRequest("GET", "index.php?module=zd_Tickets&action=macro&record={$bean->id}&macro_id=" +  macro_id + "&sugar_body_only=1&inline=1", callback);
  {rdelim}
</script>
{/if}

</td>
<td align='right'>
	<span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span> {$APP.NTC_REQUIRED}
	* only when status is Solved
</td>
</tr>
</table>
<div id="EditView_tabs">
<div>
<div id="Default_zd_Tickets_Subpanel">
<table width="100%" border="0" cellspacing="1" cellpadding="0"  class="edit view">

<tr>
  {if $new}
    <td valign="top" id='requester_name_label' width='12.5%' scope="row">Requester <span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span></td>
    {if (sizeof($requesters) > 0)}
      <td valign="top" width='37.5%'>
        <select name='requester_email' id='requester_email'>
          <option value=''></option>
        	{foreach from=$requesters key=email item=name}
            <option value='{$email|escape}|{$name|escape}'>{$name|escape} &lt;{$email|escape}&gt;</option>
          {/foreach}
        </select>
      </td>
    {else}
      <td valign="top" width='37.5%'><input type='text' name='requester_name' id='requester_name' size='30' maxlength='255' value='' title='' tabindex='100'>
      <td valign="top" id='requester_email_label' width='12.5%' scope="row">Requester Email <span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span></td>
      <td valign="top" width='37.5%'><input type='text' name='requester_email' id='requester_email' size='30' maxlength='255' value='' title='' tabindex='101'>
    {/if}
  {else}
    <td valign="top" width='12.5%' scope="row">Requester</td>
    <td valign="top" width='37.5%'>{$bean->requester_name|escape}</td>
    <td valign="top" width='12.5%' scope="row">CC</td>
    <td valign="top" width='37.5%'>{$bean->cc_list|escape}</td>
  {/if}
</tr>

{if ($zendesk_fields.FieldSubject)}
<tr>
  <td valign="top" width='12.5%' scope="row">{$zendesk_fields.FieldSubject.title}{if ($zendesk_fields.FieldSubject.required)} *{/if}</td>
  <td valign="top" width='87.5%' colspan="3"><input type='text' name='subject' id='subject' size='60' maxlength='255' value='{$bean->subject|escape}' title='' tabindex='102'{if !$bean->can_update} disabled="disabled"{/if}></td>
</tr>
{/if}

{if ($zendesk_fields.FieldStatus || $zendesk_fields.FieldPriority)}
<tr>
  {if ($zendesk_fields.FieldStatus)}
    <td valign="top" width='12.5%' scope="row">{$zendesk_fields.FieldStatus.title|escape}</td>
    <td valign="top" width='37.5%'>
      <select name="status" id="status" title='' tabindex="103"{if !$bean->can_update} disabled="disabled"{/if}>
      {foreach from=$dropdowns.status_list key=name item=value}
        {if $name!=4}{if $name>0 || $bean->status==0}
        <option value='{$name}' {if ($bean->status==$name)}selected="selected"{/if}>{$value|escape}</option>
        {/if}{/if}
      {/foreach}
      </select>
    </td>
  {/if}

  <script type="text/javascript">
  document.getElementById('status').onchange = function() {ldelim}
    if (this.value == 3) {ldelim}
      enableValidations();
    {rdelim} else {ldelim}
      disableValidations();
    {rdelim}
  {rdelim}
  </script>
  
  {if ($zendesk_fields.FieldPriority)}
    <td valign="top" width='12.5%' scope="row">{$zendesk_fields.FieldPriority.title|escape}{if ($zendesk_fields.FieldPriority.required)} *{/if}</td>
    <td valign="top" width='37.5%'>
      <select name="priority" id="priority" title='' tabindex="103"{if !$bean->can_update} disabled="disabled"{/if}>
      {foreach from=$dropdowns.priority_list key=name item=value}
        <option value='{if $name!=0}{$name}{/if}' {if ($bean->priority==$name)}selected="selected"{/if}>{$value|escape}</option>
      {/foreach}
      </select>
    </td>
  {/if}
</tr>
{/if}

{if ($zendesk_fields.FieldTicketType)}
<tr>
  <td valign="top" width='12.5%' scope="row">{$zendesk_fields.FieldTicketType.title|escape}{if ($zendesk_fields.FieldTicketType.required)} *{/if}</td>
  <td valign="top" width='37.5%'>
    <select name="type" id="ticket_type" onchange="document.getElementById('due_date_select').style.display=(this.value==4)?'':'none';document.getElementById('due_date_label').style.display=(this.value==4)?'':'none';"{if !$bean->can_update} disabled="disabled"{/if}>
    {foreach from=$dropdowns.type_list key=name item=value}
      <option value='{if $name!=0}{$name}{/if}' {if ($bean->type==$name)}selected="selected"{/if}>{$value|escape}</option>
    {/foreach}
    </select>
  </td>
  <td valign="top" width='12.5%' id="due_date_label" style='display:none' scope="row">Due Date</td>
  <td valign="top" width='37.5%' id="due_date_select" style='display:none'>
    <input autocomplete="off" type="text" name="due_date" id="due_date" {if ($bean->due_date != null)}value="{$bean->due_date|date_format:$datefmt}" {/if}title=''  tabindex='103' size="11" maxlength="10"{if !$bean->can_update} disabled="disabled"{/if}>
    {if $bean->can_update}
    <img id='due_date_trigger' src='{sugar_getimagepath file="jscalendar.png"}' align="absmiddle"/>
    {/if}
  </td>

<script type="text/javascript">

function updateTicketType(elem) {ldelim}
  document.getElementById('due_date_select').style.display=(elem.value==4)?'':'none';
  document.getElementById('due_date_label').style.display=(elem.value==4)?'':'none';
{rdelim}

document.getElementById('ticket_type').onchange = function() {ldelim}
  updateTicketType(this);
{rdelim}

Calendar.setup ({ldelim}
  inputField: "due_date",
  daFormat: "%m/%d/%Y %I:%M%P",
  button: "due_date_trigger",
  singleClick: true,
  dateStr: '{if ($bean->due_date != null)}{$bean->due_date|date_format:$datefmt}{/if}',
  step: 1,
  weekNumbers: false
{rdelim});
</script>
</tr>
{/if}


{if ($zendesk_fields.FieldGroup || $zendesk_fields.FieldAssignee)}
<tr>
  {if ($zendesk_fields.FieldGroup)}
    <td valign="top" width='12.5%' scope="row">{$zendesk_fields.FieldGroup.title|escape}{if ($zendesk_fields.FieldGroup.required)} *{/if}</td>
    <td valign="top" width='37.5%'>
      <select name="group_id" id="group_id" title='' tabindex="103"{if !$bean->can_update} disabled="disabled"{/if}>
      <option value=''></option>
      {foreach from=$bean->groups key=name item=value}
        <option value='{$name}' {if ($bean->group_id==$name)}selected="selected"{/if}>{$value.name|escape}</option>
      {/foreach}
      </select>
    </td>
  {/if}

  {if ($zendesk_fields.FieldAssignee)}
    <td valign="top" width='12.5%' scope="row">{$zendesk_fields.FieldAssignee.title|escape}{if ($zendesk_fields.FieldAssignee.required)} *{/if}</td>
    <td valign="top" width='37.5%' id='assignee_options'>
      <select name="assignee_id" id="assignee_id"{if !$bean->can_update} disabled="disabled"{/if}><option value=''></option></select>
    </td>
  {/if}
</tr>

<script type="text/javascript">
  var group_users = [];
  {foreach from=$bean->groups key=group_id item=group_data}
    group_users['{$group_id}'] = {ldelim}
    {foreach from=$group_data.users key=i item=user_id}
      '{$user_id}': '{$bean->getUserName($user_id)|escape}',
    {/foreach}
    {rdelim};
  {/foreach}

  document.getElementById('group_id').onchange = function() {ldelim}
    updateGroupUsers(this.value);
  {rdelim}
  
  {if $bean->group_id}
    updateGroupUsers({$bean->group_id});
    document.getElementById('assignee_id').value = {$bean->assignee_id};
  {/if}
  
  {literal}
  function updateGroupUsers(group_id) {
    var options = "<option value=''></option>";
    for (var user_id in group_users[group_id]) {
      var user_name = group_users[group_id][user_id];
      options += "<option value='" + user_id + "'>" + user_name + "</option>";
    }
    document.getElementById('assignee_options').innerHTML = '<select name="assignee_id" id="assignee_id">' + options + '</select>';
  }
  {/literal}
</script>

{/if}

{if $bean->can_edit_tags}
<tr>
  <td valign="top" width='12.5%' scope="row">Tags</td>
  <td valign="top" width='37.5%' colspan='3'><input type='text' name='tags' id='tags' size='30' maxlength='255' value='{$bean->tags}' title='' tabindex='106'></td>
</tr>
{/if}

{if $new}
<tr>
  <td valign="top" width='12.5%' scope="row">{$zendesk_fields.FieldDescription.title|escape} <span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span></td>
  <td valign="top" width='87.5%' colspan="3"><textarea id='description' name='description' rows="6" cols="80" title='' tabindex="107" ></textarea></td>
</tr>
{else}
  {if $bean->can_comment}
    <tr>
      <td valign="top" width='12.5%' scope="row">Comment</span></td>
      <td valign="top" width='87.5%' colspan="3">
        <textarea id='comment_value' name='comment_value' rows="6" cols="80" title='' tabindex="107" ></textarea><br/>
        {if $bean->can_public_comment}
          <input type="checkbox" value="1" name="comment_is_public" id="comment_is_public" checked="checked" tabindex="19">
          <label for="comment_is_public">Requester and CCs can see this comment (public comment)</label>
        {else}
          This comment will not be seen by the requester
        {/if}
      </td>
    </tr>
  {/if}
{/if}

{foreach from=$zendesk_fields key=name item=field_meta}
  {if (!$field_meta.system)}
  <tr>
    <td valign="top" width='12.5%' scope="row">{$field_meta.title|escape}{if ($field_meta.required)} *{/if}</td>
    <td valign="top" width='37.5%'>
      {if ($field_meta.type == "FieldTagger")}
        <select name="custom_{$name}" id="custom_{$name}" title='' tabindex="103"{if !$bean->can_update} disabled="disabled"{/if}>
        <option value=''></option>
        {foreach from=$field_meta.options key=option item=value}
          <option value='{$option}' {if ($field_meta.value==$option)}selected="selected"{/if}>{$value}</option>
        {/foreach}
        </select>
      {elseif ($field_meta.type == "FieldCheckbox")}
        <input type='hidden' name="custom_{$name}" id="custom_{$name}_shadow" value='{$field_meta.value}'>
        <input type="checkbox" onchange="document.getElementById('custom_{$name}_shadow').value=this.checked?'1':'0'" id="custom_{$name}" {if ($field_meta.value == '1')}checked="checked"{/if} tabindex="19"{if !$bean->can_update} disabled="disabled"{/if}>
      {elseif ($field_meta.type == "FieldTextarea")}
      <textarea name='custom_{$name}' id='custom_{$name}' rows="3" cols="80"{if !$bean->can_update} disabled="disabled"{/if}>{$field_meta.value}</textarea>
      {else}
        <input type='text' name='custom_{$name}' id='custom_{$name}' size='30' maxlength='255' value='{$field_meta.value}' title='' tabindex='106'{if !$bean->can_update} disabled="disabled"{/if}>
        {if ($field_meta.type=="FieldRegexp")}
        <span id='custom_{$name}_error' class="required"></span>
        <script type="text/javascript">
          document.getElementById('custom_{$name}').onkeyup = function() {ldelim}
            reg = new RegExp('{$field_meta.regexp}');
            if (reg.test(this.value)) {ldelim}
              document.getElementById('custom_{$name}_error').innerHTML = '';
            {rdelim} else {ldelim}
              document.getElementById('custom_{$name}_error').innerHTML = 'Not matching {$field_meta.regexp|escape}';
            {rdelim}
          {rdelim}
        </script>
        {/if}
      {/if}
    </td>
  </tr>
  {/if}
{/foreach}

</table>
</div>
</div></div>
<div class="buttons">
<input title="Save [Alt+S]" accessKey="S" class="button primary" onclick="this.form.action.value='AjaxSave'; return check_form('EditView');" type="submit" name="button" value="Save"> 
<input title="Cancel [Alt+X]" accessKey="X" class="button" onclick="window.location.href='index.php?action={$RETURN_ACTION}&module={$RETURN_MODULE}&record={$RETURN_ID}'; return false;" type="button" name="button" value="Cancel">
<img src="http://disclosures.linuxdefenders.org/webfile/default/loading-spinner.gif" id="saving2" style="display:none;vertical-align:middle" />
</div>
</form>

<script type="text/javascript">

function submit() {ldelim}
  var callback = {ldelim}
    success: function(o) {ldelim}
      document.getElementById("saving1").style.display = "none";
      document.getElementById("saving2").style.display = "none";
      var res = o.responseText;
      if (res == "OK") {ldelim}
        window.location.href='index.php?action={$RETURN_ACTION}&module={$RETURN_MODULE}&record={$RETURN_ID}';
      {rdelim} else {ldelim}
        alert(res);
      {rdelim}
    {rdelim}
  {rdelim}
  
  var formObject = document.getElementById('EditView');
  YAHOO.util.Connect.setForm(formObject);
  
  document.getElementById("saving1").style.display = "";
  document.getElementById("saving2").style.display = "";
  var connectionObject = YAHOO.util.Connect.asyncRequest(
    "POST",
    "index.php?sugar_body_only=1&inline=1",
    callback
  );
{rdelim}

initEditView(document.forms.EditView);
addToValidate('EditView', 'requester_name', 'text', true, 'Requester');
addToValidate('EditView', 'requester_email', 'text', true, 'Requester Email');
addToValidate('EditView', 'description', 'text', true, 'Description');
addToValidate('EditView', 'due_date', 'date', false, 'Due Date');

function enableValidations() {ldelim}
{if ($zendesk_fields.FieldSubject)}
  addToValidate('EditView', 'subject', 'varchar', {if $zendesk_fields.FieldSubject.required}true{else}false{/if}, '{$zendesk_fields.FieldSubject.title|escape}');
{/if}
{if ($zendesk_fields.FieldPriority)}
  addToValidate('EditView', 'priority', 'varchar', {if $zendesk_fields.FieldPriority.required}true{else}false{/if}, '{$zendesk_fields.FieldPriority.title|escape}');
{/if}
{if ($zendesk_fields.FieldTicketType)}
  addToValidate('EditView', 'ticket_type', 'varchar', {if $zendesk_fields.FieldTicketType.required}true{else}false{/if}, '{$zendesk_fields.FieldTicketType.title|escape}');
{/if}
{if ($zendesk_fields.FieldGroup)}
  addToValidate('EditView', 'group_id', 'enum', {if $zendesk_fields.FieldGroup.required}true{else}false{/if}, '{$zendesk_fields.FieldGroup.title|escape}');
{/if}
{if ($zendesk_fields.FieldAssignee)}
  addToValidate('EditView', 'assignee_id', 'enum', {if $zendesk_fields.FieldAssignee.required}true{else}false{/if}, '{$zendesk_fields.FieldAssignee.title|escape}');
{/if}

{foreach from=$zendesk_fields key=name item=field_meta}
  {if (!$field_meta.system)}
    addToValidate('EditView', 'custom_{$name}', '{if $field_meta.type=="FieldDecimal"}float{elseif $field_meta.type=="FieldInteger"}int{else}varchar{/if}', {if $field_meta.required}true{else}false{/if}, '{$field_meta.title|escape}');
  {/if}
{/foreach}
{rdelim}

function disableValidations() {ldelim}
{if ($zendesk_fields.FieldSubject)}
  removeFromValidate('EditView', 'subject');
{/if}
{if ($zendesk_fields.FieldPriority)}
  removeFromValidate('EditView', 'priority');
{/if}
{if ($zendesk_fields.FieldTicketType)}
  removeFromValidate('EditView', 'ticket_type');
{/if}
{if ($zendesk_fields.FieldGroup)}
  removeFromValidate('EditView', 'group_id');
{/if}
{if ($zendesk_fields.FieldAssignee)}
  removeFromValidate('EditView', 'assignee_id');
{/if}

{foreach from=$zendesk_fields key=name item=field_meta}
  {if (!$field_meta.system)}
    removeFromValidate('EditView', 'custom_{$name}');
  {/if}
{/foreach}
{rdelim}


{if ($bean->status==4)}
enableValidations();
{/if}
</script>
