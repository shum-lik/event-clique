
<?php
// $Id: node.tpl.php,v 1.5 2007/10/11 09:51:29 goba Exp $
?>
<div id="event-<?php print $node->nid; ?>" class="event-wrapper allRounded" <?php if($node->field_event_background!=NULL):?>style="background:url(<?php print base_path().$node->field_event_background[0]['filepath'] ?>)"<?php endif ?>>
  <div class="content">
    <div class="description">

       <?php print $node->content['body']['#value']; ?>
    </div>
  </div>
  <div class="clear"></div>
</div>
