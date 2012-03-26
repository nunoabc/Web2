{literal}
<script language="javascript" type="text/javascript">
function moveOptionsUp(selectId) {
 var selectList = document.getElementById(selectId);
 var selectOptions = selectList.getElementsByTagName('option');
 for (var i = 1; i < selectOptions.length; i++) {
  var opt = selectOptions[i];
  if (opt.selected) {
   selectList.removeChild(opt);
   selectList.insertBefore(opt, selectOptions[i - 1]);
     }
    }
}
function moveOptionsDown(selectId) {
 var selectList = document.getElementById(selectId);
 var selectOptions = selectList.getElementsByTagName('option');
 for (var i = selectOptions.length - 2; i >= 0; i--) {
  var opt = selectOptions[i];
  if (opt.selected) {
   var nextOpt = selectOptions[i + 1];
   opt = selectList.removeChild(opt);
   nextOpt = selectList.replaceChild(opt, nextOpt);
   selectList.insertBefore(nextOpt, opt);
     }
    }
}
function moveOptionsAcross(fromSelectId, toSelectId) {
 var fromSelectList = document.getElementById(fromSelectId);
 var toSelectList = document.getElementById(toSelectId);
  var selectOptions = fromSelectList.getElementsByTagName('option');
  for (var i = 0; i < selectOptions.length; i++) {
     var opt = selectOptions[i];
     if (opt.selected) {
      fromSelectList.removeChild(opt);
      toSelectList.appendChild(opt);
      i--;
     }
   }
}
function currentSelectionString(selectId) {
  var selectList = document.getElementById(selectId);
  var selectOptions = selectList.getElementsByTagName('option');
  var res = '';
  for (var i = 0; i < selectOptions.length; i++) {
    if (i > 0) {
      res += ',';
    }
    res += selectOptions[i].value;
  }
  return res;
}

function setActiveFields(fieldsId, selectId) {
  var e = document.getElementById(fieldsId);
  e.value = currentSelectionString(selectId);
}

function selectItemByName(selectId, name) {
  var selectList = document.getElementById(selectId);
  var selectOptions = selectList.getElementsByTagName('option');
  for (var i in selectOptions) {
    var so = selectOptions[i];
    if (selectOptions[i].value == name) {
      selectOptions[i].selected = true;
      return;
    }
  }
}

function initActiveValues(fieldsId, fromSelectId, toSelectId) {
  var e = document.getElementById(fieldsId);
  var s = e.value;
  if (s != '') {
    var items = s.split(',');
    for (var i in items) {
      selectItemByName(fromSelectId, items[i]);
      moveOptionsAcross(fromSelectId, toSelectId);
    }
  }
}
</script>
{/literal}

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
	<tr><th align="left" scope="row" colspan="2"><h4>Configure fields</h4></th></tr>
	
	{foreach from=$records key=rec item=record}
	
	<tr id="rec_{$record.key}"{if !$record.enabled} style="display:none"{/if}>
    <td width="10%" scope="row">{$record.name}</td>
    <td width="90%" style="whitespace:nowrap">
      <input type="hidden" id="activeFields_{$record.key}" name="system_zendesk_widget_{$record.key}" value="{$record.current}" />
      <table>
        <tr>
          <td>
            <select id="remainingList_{$record.key}" multiple="multiple" size="5" style="width:260px">
              {foreach from=$record.fields key=key item=option}
                <option value='{$key}'>{$option}</option>
              {/foreach}
            </select>
          </td>
          <td style="vertical-align:middle">
            <div style="margin-bottom:10px">
              <a onclick="moveOptionsAcross('remainingList_{$record.key}', 'remainingListright_{$record.key}');setActiveFields('activeFields_{$record.key}', 'remainingListright_{$record.key}');return false" class="button"><img src='{sugar_getimagepath file="Zendesk__RightArrow.png"}'/></a>
            </div>
            <div style="margin-top:10px">
              <a onclick="moveOptionsAcross('remainingListright_{$record.key}', 'remainingList_{$record.key}');setActiveFields('activeFields_{$record.key}', 'remainingListright_{$record.key}');return false" class="button"><img src='{sugar_getimagepath file="Zendesk__LeftArrow.png"}'/></a>
            </div>
          </td>
          <td>
            <select id="remainingListright_{$record.key}" multiple="multiple" size="5" style="width:260px"></select>
          </td>
          <td style="vertical-align:middle">
            <div style="margin-bottom:10px">
              <a onclick="moveOptionsUp('remainingListright_{$record.key}');setActiveFields('activeFields_{$record.key}', 'remainingListright_{$record.key}');return false" class="button"><img src='{sugar_getimagepath file="Zendesk__UpArrow.png"}'/></a>
            </div>
            <div style="margin-top:10px">
              <a onclick="moveOptionsDown('remainingListright_{$record.key}');setActiveFields('activeFields_{$record.key}', 'remainingListright_{$record.key}');return false" class="button"><img src='{sugar_getimagepath file="Zendesk__DownArrow.png"}'/></a>
            </div>
          </td>
        </tr>
      </table>
      
      <script language="javascript" type="text/javascript">
        initActiveValues('activeFields_{$record.key}', 'remainingList_{$record.key}', 'remainingListright_{$record.key}');
      	
      </script>
      
    </td>
  </tr>

  {/foreach}
  
	<tr>
    <td width="10%" scope="row">Add record</td>
    <td width="90%" style="whitespace:nowrap">
      <select id="disabledRecords" onchange="document.getElementById(this.value).style.display='';this.options[this.selectedIndex] = null;">
        <option value=''></option>
      	{foreach from=$records key=rec item=record}
      	  {if !$record.enabled}
            <option value='rec_{$record.key}'>{$record.name}</option>
          {/if}
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

