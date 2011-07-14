<?php 
 //dsm($fields);
?>

<div class="comment-picture">
    <?php print theme('imagecache','user_pictures_small',$fields['picture']->raw);?>
</div>
<div class="comment-content">


  <div class="st_name_block" style="font-size:13px;">
    <p style="float:left;"><span class="event-link"><?php print $fields['title']->content ?></span></p>
    <p style="float:left;"><span class="username"><?php print ucwords($fields['name']->content); ?></span></p>
    <p style="float:left;"><span class="st_comment_text"><?php print $fields['comment']->content?></span></p>
</div>
    <div class="comment-info">
		<span class="timestamp"><?php print $fields['timestamp']->content?></span>
		<span class="like-counter comment-link"><img width="13" height="12" src="<?php print base_path().path_to_theme() ?>/img/layout/likeIcon.png"> <?php print $fields['count']->content ?></span>
		<span class="like-button comment-link"><?php print $fields['ops']->content?></span>
		<span class="delete-button comment-link"><?php print $fields['edit-comment']->content?></span>
		<span class="delete-button comment-link"><?php print $fields['delete-comment']->content?></span>
	</div>
</div>
<div class="clear"></div>
