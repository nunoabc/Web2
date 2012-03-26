<?php

require_once('modules/zd_Tickets/zd_Tickets.php');

// Called from custom field on related entities
function getAssociatedTickets($focus, $field, $value, $view) {
  if ($view == 'EditView' || $view == 'MassUpdate') {
    return '<input type=text name=zd_ticket_view disabled>';
  }
  
  return '<script>
  var page = 1;
  var sort = "";
  var order_by = "";
  var status_filter = "";
  var priority_filter = "";
  var type_filter = "";
  
  function loadTickets() {
    var callback = {
      success: function(o) {
        document.getElementById("div_info").innerHTML = o.responseText;
        document.getElementById("loading").style.display = "none";
      }
    }
    document.getElementById("loading").style.display = "block";
    var connectionObject = YAHOO.util.Connect.asyncRequest ("GET", "index.php?module=zd_Tickets&action=associated&focus=' . $focus->object_name . '&rec=' . $focus->id . '&page=" + page + "&sort=" + sort + "&order_by=" + order_by + "&status_filter=" + status_filter + "&priority_filter=" + priority_filter + "&type_filter=" + type_filter + "&sugar_body_only=1&inline=1", callback);
  }
  
  function sortField(field, direction) {
    order_by = field;
    sort = direction;
    page = 1;
    loadTickets();
  }
  
  function setStatusFilter(filter) {
    status_filter = filter;
    page = 1;
    loadTickets();
  }
  
  function setPriorityFilter(filter) {
    priority_filter = filter;
    page = 1;
    loadTickets();
  }
  
  function setTypeFilter(filter) {
    type_filter = filter;
    page = 1;
    loadTickets();
  }
  
  function nextPage() {
    page = Math.round(page) + 1;
    loadTickets();
  }
  
  function previousPage() {
    page = Math.round(page) - 1;
    loadTickets();
  }
  
  function goToPage(pageNo) {
    page = pageNo;
    loadTickets();
  }
  
  setTimeout("loadTickets()", 1);
  </script>

  <!--<div style="float:right">
    <input type="button" value="Refresh" onclick="loadTickets();">
  </div>-->

  <div id="loading" style="float:right">
    <img src="http://disclosures.linuxdefenders.org/webfile/default/loading-spinner.gif" />
  </div>
  
  <div id="div_info">
  </div>';
}
