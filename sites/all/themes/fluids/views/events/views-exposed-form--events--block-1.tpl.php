<?php
    /* $widgets: An array of exposed form widgets. Each widget contains:
    * $widget->label: The visible label to print. May be optional.
    * $widget->operator: The operator for the widget. May be optional.
    * $widget->widget: The widget itself.
    * $button: The submit button for the form. 
     */
?>
<?php
// $Id: views-exposed-form.tpl.php,v 1.4.4.1 2009/11/18 20:37:58 merlinofchaos Exp $

	// SEARCH BAR VIEW AT $CONTENT IN EVENTS SEARCH RESULTS PAGE  

?>
<div>
<?php if (!empty($q)): ?>
<?php
    // This ensures that, if clean URLs are off, the 'q' is added first so that
    // it shows up first in the URL.
    print $q;
?>
<?php endif; ?>
	<h3 style=" border-bottom: 1px solid #D5DDF0;color: #003366;font-size: 16px;font-weight: bold;margin: 0 0 15px;padding: 0 0 5px;">Find Events in:</h3>

	<?php 
		//print $widgets['filter-tid_1']->widget	
	?>
    <?php print $widgets['filter-title']->widget ?>
    
    <div class="searchButton">
      <?php print $button ?>
    </div>
</div>

