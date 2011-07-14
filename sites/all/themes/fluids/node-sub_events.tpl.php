<?php
  $background = '';
  if($node->field_event_background!=NULL){
    $background = base_path().$node->field_event_background[0]['filepath'];
  }
  else{
    $res = db_query('select filepath from files a inner join content_field_event_background b on b.field_event_background_fid = a.fid  where b.nid = %d',$node->field_parent_event[0]['nid']);
    $row = db_fetch_object($res);
    $background = base_path().$row->filepath;
  }
?>
<?php
// $Id: node.tpl.php,v 1.5 2007/10/11 09:51:29 goba Exp $
?>
<div id="event-<?php print $node->nid; ?>" class="subevent-wrapper" <?php if($background!=''):?>style="background:url(<?php print $background ?>)"<?php endif ?>>
  <div class="content">
    <div class="description">
	   <h2><?php print $node->title ?></h2>
	   <div class="get-ticket"><a href="http://www.ticketbooth.com.sg/SelectSession.ntuc?EventId=75&amp;cid=3731">Get Ticket</a></div>
       <?php print $node->content['body']['#value']; ?>
    </div>
	<div class="clear"></div>
  </div>
  <div class="clear"></div>
</div>
