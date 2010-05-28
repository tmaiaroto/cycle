<?php
/* jQuery Coin Slider
 * More Info: http://workshop.rs/projects/coin-slider
 *
 * This is the element responsible for embedding the jQuery Coin Slider plugin for displaying the cycle.
 *
 * Note the unique id. It is generated from the association between the cycle and node, using both of their id values.
 * Since there can only be one cycle per node, this should safely be a "unique" string. So you should be able to also
 * use an external style sheet if you wanted, but that could leave you in the spot of having to add new styles for each
 * new node that uses a cycle.
 *
 * You can also copy this file and put it in your theme's elements/cycle folder so you can make alterations to it there.
*/
//if(!$uuid) { $uuid = $this->uuid('div', '/'); }
if($autoplay == 1) { $autopay = true; } else { $autoplay = false; } // This plugin uses booleans in some cases and numbers in other cases...This is one where it uses a boolean, but all values in the database are 0/1 for on/off or true/false.
if(!$letterbox_color) { $letterbox_color = array(0,0,0); } // black if not specified
?>
<style><!--
.coin-slider, #<?php echo $uuid; ?> { width: <?php echo $width; ?>px; overflow: hidden; height: <?php echo ($height + 30); ?>px; display: block; overflow: visible; }
.coin-slider a { text-decoration: none; outline: none; border: none; }
.cs-buttons { font-size: 0px; padding: 10px; float: left; padding-left: 0px; }
.cs-buttons a { margin-left: 5px; height: 10px; width: 10px; float: left; border: 1px solid #B8C4CF; color: #B8C4CF; text-indent: -1000px; }
.cs-title { width: <?php echo ($width - 20); ?>px; padding: 10px; background-color: #000000; color: #FFFFFF; }
.cs-prev, .cs-next { background-color: #000000; color: #FFFFFF; padding: 0px 10px; }
.cs-active { background-color: #B8C4CF; color: #FFFFFF; }
.cs-title { width: <?php echo ($width - 20); ?>px; padding: 10px; background-color: #000000; color: #FFFFFF; }

--></style>
<div id="<?php echo $uuid; ?>">
	<?php foreach($records as $record) { ?>
	<a href="<?php echo $record['link']; ?>" target="_blank">
		<?php echo $this->ImageVersion->version(array('image' => 'cycle_images/'.$record['path'], 'size' => array($width, $height), 'quality' => 90, 'crop' => false, 'letterbox' => $letterbox_color, 'sharpen' => true), array('width' => $width, 'height' => $height)); ?>
		<span><?php echo $record['caption']; ?></span>
	</a>
    <?php } ?>	
</div>
<div style="clear: left; height: 1px; width: 1px;"></div>
<?php echo $this->Html->script('/cycle/js/coin-slider.min.js'); // because of some caching, can't put this in scripts for layout... which is unfortunate if its called more than once on a page... todo: fix. ?>
<script type="text/javascript">
$(document).ready(function(){ $('#<?php echo $uuid; ?>').coinslider({width: <?php echo $width; ?>, height: <?php echo $height; ?>}); });
</script>
