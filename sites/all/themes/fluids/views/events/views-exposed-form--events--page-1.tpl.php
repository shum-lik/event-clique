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

	// SEARCH BAR VIEW CONTENT AT HOME PAGE



?>

<?php if (!empty($q)): ?>
<?php
    // This ensures that, if clean URLs are off, the 'q' is added first so that
    // it shows up first in the URL.
    print $q;
?>
<?php endif; ?>
<div id="mainSearch">
    <h1><img src="<?php print base_path().path_to_theme() ?>/img/mainLogo.jpg" /><br/>
	<p style="color:#7F7F7F;font-size: 18px;font-style: italic;margin: 0 0 30px;">Your journey to Great Events in the Asia Pacific Region starts here!
	<span style="color: #F99846;display:block;font-style: normal;margin: 2px 0 0;">Concerts &#183; Theatre &#183; Sports &#183; Entertainment &#183; Exhibitions &#183; Conferences</span></p>

	</h1>
	<label style="color: #333333;display: block;font-weight: bold;margin: 0 0 10px;font-size:14px;">Find events in:</label>
	<?php print $widgets['filter-tid_1']->widget?>
    <?php print $widgets['filter-title']->widget ?>
    <div class="advancedSearchBox">
        <!--<p id="advanced"><a title="Advanced Search" href="#">Advanced Search</a></p>
        <div class="advancedToggle" style="display:none">
            <?php foreach($widgets as $id => $widget): ?>
              <?php if($id!='filter-title'):?>
              <div class="widget-wrapper">
                  <div class="label"><label for="<?php print $widget->id; ?>"><?php print $widget->label; ?></label></div>
                  <div class="widget"><?php print $widget->widget; ?></div>
              </div>
              <?php endif; ?>
            <?php endforeach; ?>
            <div class="clear"></div>
        </div>
		-->
    </div>
    <div class="searchButton">
      <?php print $button ?>
    </div>
</div>
