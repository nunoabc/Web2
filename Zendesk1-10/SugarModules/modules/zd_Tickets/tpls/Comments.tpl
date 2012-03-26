<table cellpadding="0" cellspacing="0" width="100%" class="h3Row">
  <tr>
    <td width="20%" valign="bottom">
      <h3>Comments</h3>
    </td>
  </tr>
</table>


<table class="other view">
  <tr height="20">
    <th>By</th>
    <th>Message</th>
  </tr>
{foreach from=$bean->comments key=key item=comment}
  <tr>
    <td style="text-align:center;vertical-align:top">
      <img src="{$comment.author_photo_url}" style="border:1px solid #dddddd;padding:2px;margin:3px;vertical-align:top" />
      <div style="text-align:center;white-space:nowrap;font-size:11px;color:#333333;margin:3px">
        {$comment.author_name}
      </div>
    </td>
    <td>
      <img src="https://support.zendesk.com/images/speak_{if $comment.is_public}public{else}private{/if}_small.png" style="float:left;margin-top:6px" />
      <p style="margin:6px 0px 0px 24px;line-height:1.3em;padding:0px;">
       {$comment.value}
      </p>
      <div style="color:#888888;margin-top:16px;">{$comment.created_at}</div>
    </td>
  </tr>
{/foreach}
</table>
