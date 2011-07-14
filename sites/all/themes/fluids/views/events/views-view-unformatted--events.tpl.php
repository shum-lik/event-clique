<?php
// $Id: views-view-unformatted.tpl.php,v 1.6 2008/10/01 20:52:11 merlinofchaos Exp $
/**
 * @file views-view-unformatted.tpl.php
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 */

	// EVENT SEARCH RESULTS VIEW  
?>

<h4 style="border-bottom: 1px solid #D5DDF0;color: #003366;font-size: 16px;font-weight: bold;margin: 0 0 15px;padding: 0 0 5px;">
	Search Result
</h4>

<?php 
	if($_GET['title'] != null && $_GET['title'] != ""):
		print("<h3>Your search result for: " .$_GET['title']. "</h3>");
	endif;

?>


<?php if (!empty($title)): ?>
  	<h4><?php print $title; ?></h4>
<?php endif; ?>
<?php foreach ($rows as $id => $row): ?>
  <div class="<?php print $classes[$id]; ?>">
    <?php print $row; ?>
  </div>
<?php endforeach; ?>