<div class="post <?php if($fields['field_event_payment_type_value']->content == 'Premium'): echo 'premium_listing'; endif; ?>">
	<ul>
		<li class="imageWrap">
	
      <?php 
      //	print($fields['field_event_payment_type_value']->content);
      //dpm($fields);
      ?>
      <img width="80" height="61"  src="<?php print($fields['field_event_listing_thumbnail_fid']->content); ?>" />
		</li>
		<li class="contentWrap">
			<h3><?php print $fields['title']->content?></h3>
      
      <ul class="tools">
        <li class="buyTickets"><a href="" title="Buy Tickets"><img width="110" height="23" alt="Buy Tickets" title="Buy Tickets" src="<?php print base_path() . path_to_theme() ?>/img/layout/buyTickets.png" /></a></li>
       <!-- <li class="viewCalendar"><a href="" title="View Calendar"><img width="16" height="16" alt="View Calendar" title="View Calendar" src="<?php print base_path() . path_to_theme() ?>/img/layout/calendarIcon.png" /></a></li>-->
      </ul>
      
      <?php if($fields['field_event_date_value']->content || $fields['field_event_venue_nid']->content): ?>
      <p><?php print $fields['field_event_date_value']->content ?> <?php if($fields['field_event_date_value']->content && $fields['field_event_venue_nid']->content): ?> | <?php endif; ?><?php print $fields['field_event_venue_nid']->content ?></p>
      <?php endif; ?>
			
      <?php print $fields['body']->content?>
			
      <dl>
				<dd><?php print $fields['created']->content?></dd>
				<dd><img height="14" alt="" src="<?php print base_path().path_to_theme() ?>/img/layout/commentIcon.png" /> <?php print $fields['comment_count']->content?></dd>
				<dd><img height="14" alt="" src="<?php print base_path().path_to_theme() ?>/img/layout/likeIcon.png" /> <?php print $fields['count']->content?></dd>
				<dd><?php print $fields['ops']->content?></dd>
			</dl>
		</li>
	</ul>
</div>
