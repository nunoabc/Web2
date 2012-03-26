<table class="list view" border="0" cellspacing="0" cellpadding="0" style="border:none;margin:0px;">
  <tr class="pagination">
    <td colspan="20">
      <div style="float:left;padding:3px">
        <input type="button" value="Create Ticket" onclick="document.location='index.php?module=zd_Tickets&action=EditView&record={$ticket->id}&return_module={$RETURN_MODULE}&return_id={$RETURN_ID}&return_action={$RETURN_ACTION}'" />
        <a href="{$url|escape}" target="_blank">View in Zendesk</a>
        <a href='index.php?module=zd_Tickets&action=personalconfig&return_module={$RETURN_MODULE}&return_id={$RETURN_ID}&return_action={$RETURN_ACTION}'>My settings</a>
      </div>
      <div style="float:right;padding:5px">
        <button {if ($page<=1)}disabled="disabled"{/if} onclick="goToPage('1');return false;"><img src='{if ($page<=1)}{sugar_getimagepath file="start_off.gif"}{else}{sugar_getimagepath file="start.gif"}{/if}'></button><button {if ($page<=1)}disabled="disabled"{/if} onclick="previousPage();return false;"><img src='{if ($page<=1)}{sugar_getimagepath file="previous_off.gif"}{else}{sugar_getimagepath file="previous.gif"}{/if}'></button>
        {if $count==0}
          (0 - 0 of 0)
        {else}
          ({$from_count} - {$to_count} of {$count})
        {/if}
        <button {if ($page>=$pages)}disabled="disabled"{/if} onclick="nextPage();return false;"><img src='{if ($page>=$pages)}{sugar_getimagepath file="next_off.gif"}{else}{sugar_getimagepath file="next.gif"}{/if}'></button><button {if ($page>=$pages)}disabled="disabled"{/if} onclick="goToPage('{$pages}');return false;"><img src='{if ($page>=$pages)}{sugar_getimagepath file="end_off.gif"}{else}{sugar_getimagepath file="end.gif"}{/if}'></button>
      </div>
    </td>
  </tr>
  <tr height="20">
    <th style="border-radius:0px">&nbsp;</th>
    <th>
      <a style="color:#666666;font-size:13px" href='#' onclick="sortField('subject',{if ($order_by=="subject")}{if ($sort=="asc")}'desc'{else}'asc'{/if}{else}'asc'{/if});return false;">Subject</a>
      <img src='{if ($order_by=="subject")}{if ($sort=="asc")}{sugar_getimagepath file="arrow_down.gif"}{else}{sugar_getimagepath file="arrow_up.gif"}{/if}{else}{sugar_getimagepath file="arrow.gif"}{/if}' />
    </th>
    <th>
      <a style="color:#666666;font-size:13px" href='#' onclick="sortField('status',{if ($order_by=="status")}{if ($sort=="asc")}'desc'{else}'asc'{/if}{else}'asc'{/if});return false;">Status</a>
      <img src='{if ($order_by=="status")}{if ($sort=="asc")}{sugar_getimagepath file="arrow_down.gif"}{else}{sugar_getimagepath file="arrow_up.gif"}{/if}{else}{sugar_getimagepath file="arrow.gif"}{/if}' />
      <select onchange="setStatusFilter(this.value);">
      {foreach from=$statusoptions key=key item=option}
        <option value='{$key}' {if ($status_filter==$key)}selected="selected"{/if}>{$option}</option>
      {/foreach}
      </select>
    </th>
    <th>
      <a style="color:#666666;font-size:13px" href='#' onclick="sortField('priority',{if ($order_by=="priority")}{if ($sort=="asc")}'desc'{else}'asc'{/if}{else}'asc'{/if});return false;">Priority</a>
      <img src='{if ($order_by=="priority")}{if ($sort=="asc")}{sugar_getimagepath file="arrow_down.gif"}{else}{sugar_getimagepath file="arrow_up.gif"}{/if}{else}{sugar_getimagepath file="arrow.gif"}{/if}' />
      <select onchange="setPriorityFilter(this.value);">
      {foreach from=$priorityoptions key=key item=option}
        <option value='{$key}' {if ($priority_filter==$key)}selected="selected"{/if}>{$option}</option>
      {/foreach}
      </select>
    </th>
    <th>
      <a style="color:#666666;font-size:13px" href='#' onclick="sortField('ticket_type',{if ($order_by=="ticket_type")}{if ($sort=="asc")}'desc'{else}'asc'{/if}{else}'asc'{/if});return false;">Type</a>
      <img src='{if ($order_by=="ticket_type")}{if ($sort=="asc")}{sugar_getimagepath file="arrow_down.gif"}{else}{sugar_getimagepath file="arrow_up.gif"}{/if}{else}{sugar_getimagepath file="arrow.gif"}{/if}' />
      <select onchange="setTypeFilter(this.value);">
      {foreach from=$typeoptions key=key item=option}
        <option value='{$key}' {if ($type_filter==$key)}selected="selected"{/if}>{$option}</option>
      {/foreach}
      </select>
    </th>
    <th>
      <a style="color:#666666;font-size:13px" href='#' onclick="sortField('created_at',{if ($order_by=="created_at")}{if ($sort=="asc")}'desc'{else}'asc'{/if}{else}'asc'{/if});return false;">Created At</a>
      <img src='{if ($order_by=="created_at")}{if ($sort=="asc")}{sugar_getimagepath file="arrow_down.gif"}{else}{sugar_getimagepath file="arrow_up.gif"}{/if}{else}{sugar_getimagepath file="arrow.gif"}{/if}' />
    </th>
    <th>
      <a style="color:#666666;font-size:13px" href='#' onclick="sortField('updated_at',{if ($order_by=="updated_at")}{if ($sort=="asc")}'desc'{else}'asc'{/if}{else}'asc'{/if});return false;">Updated At</a>
      <img src='{if ($order_by=="updated_at")}{if ($sort=="asc")}{sugar_getimagepath file="arrow_down.gif"}{else}{sugar_getimagepath file="arrow_up.gif"}{/if}{else}{sugar_getimagepath file="arrow.gif"}{/if}' />
    </th>
    <th style="border-radius:0px">&nbsp;</th>
  </tr>
{if $count==0}
  <tr>
    <td colspan="20">
      <em>No Data</em>
    </td>
  </tr>
{else}
  {foreach from=$tickets key=key item=ticket}
    <tr class="{if ($key % 2)==1}odd{else}even{/if}ListRowS1">
      <td style="padding:0px 3px 0px 3px">
        <a href="{$base_url}/tickets/{$ticket->nice_id}" target="_blank" onmouseout="document.getElementById('id{$ticket->nice_id}').style.display='none';" onmouseover="document.getElementById('id{$ticket->nice_id}').style.display='block';"><b>{$ticket->nice_id}</b></a>
        <div id="id{$ticket->nice_id}" style="display:none;position:absolute;width:600px;border:1px black solid;background:white;padding:10px;white-space:normal">
          <strong>Ticket #{$ticket->nice_id}: {$ticket->subject}</strong>
          <br/><br/>
          {$ticket->descriptionLines()}
        </div>
      </td>
      <td style="padding:0px 3px 0px 3px;white-space:nowrap"><a href='index.php?module=zd_Tickets&action=DetailView&record={$ticket->id}'>{$ticket->getShortenedSubject()}</a></td>
      <td style="padding:0px 3px 0px 3px">{$ticket->getStatus()}</td>
      <td style="padding:0px 3px 0px 3px">{$ticket->getPriority()}</td>
      <td style="padding:0px 3px 0px 3px">{$ticket->getType()}</td>
      <td style="padding:0px 3px 0px 3px;white-space:nowrap">{$ticket->created_at|date_format:$datetimefmt}</td>
      <td style="padding:0px 3px 0px 3px;white-space:nowrap;border-radius:0px">{$ticket->updated_at|date_format:$datetimefmt}</td>
      <td style="padding:0px 3px 0px 3px;white-space:nowrap;border-radius:0px"><a href='index.php?module=zd_Tickets&action=EditView&record={$ticket->id}&return_module={$RETURN_MODULE}&return_id={$RETURN_ID}&return_action={$RETURN_ACTION}'><img src='{sugar_getimagepath file="edit_inline.png"}'/> edit</a></td>
    </tr>
  {/foreach}
{/if}
</table>
