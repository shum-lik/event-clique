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

	// SEARCH BAR VIEW AT HEADER REGION ( APPEAR ALL PAGE EXCEPT HOME PAGE AND SEARCH RESULTS PAGE ) 

?>
<?php if (!empty($q)): ?>
<?php
    // This ensures that, if clean URLs are off, the 'q' is added first so that
    // it shows up first in the URL.
    print $q;
?>
<?php endif; ?>

	<?php 
		//print $widgets['filter-tid_1']->widget	
	?>
    <?php print $widgets['filter-title']->widget ?>
    
    <div class="searchButton">
      <?php print $button ?>
    </div>

