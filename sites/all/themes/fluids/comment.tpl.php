<div class="comment<?php print ($comment->new) ? ' comment-new' : ''; print ($comment->status == COMMENT_NOT_PUBLISHED) ? ' comment-unpublished' : ''; ?> clear-block">
<div class="commment-content-left">
<?php print $picture ?>
</div>
<div class="comment-content-right">
<?php print_r($comment); ?>
  <?php if ($comment->new) : ?>
    <a id="new"></a>
    <span class="new"><?php print $new ?></span>
  <?php endif; ?>
  <h3><?php print $title ?></h3>
  <div class="submitted">
    <?php print $submitted ?>
  </div>
  <div class="content">
    <?php print $content ?>
  </div>
  <?php print $links ?>
</div>
  
</div>