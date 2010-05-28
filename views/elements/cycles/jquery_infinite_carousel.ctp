<?php
/* jQuery Infinite Carousel
 * More Info: http://www.catchmyfame.com/2009/12/30/huge-updates-to-jquery-infinite-carousel-version-2-released
 *
 * This is the element responsible for embedding the jQuery Infinite Carousel plugin for displaying the cycle.
 * You may need to adjust the margin settings for the $uuid div depending on your site and usage.
 * The example that came with the plugin had no margin at all, but displaying it using the "Minimal" theme 
 * required this margin adjustment to display properly. You can of course also change other styles here as well.
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
#<?php echo $uuid; ?> {
	padding: 0;
	margin: 0;
}
#<?php echo $uuid; ?> ul {
	list-style: none;
	width:1600px;
	margin: 0;
	padding: 0;
	position:relative;
}
#<?php echo $uuid; ?> li {
	display:inline;
	float:left;
	margin: -15px 0px 0px -7px;
}
#<?php echo $uuid; ?> .textholder {
	padding: 2px 5px 2px 5px;
}
--></style>
<div id="<?php echo $uuid; ?>">
	<ul>
<?php foreach($records as $record) { ?>
		<li><?php echo $this->ImageVersion->version(array('image' => 'cycle_images/'.$record['path'], 'size' => array($width, $height), 'quality' => 90, 'crop' => false, 'letterbox' => $letterbox_color, 'sharpen' => true), array('width' => $width, 'height' => $height)); ?><p><strong><?php echo $record['title']; ?></strong><br /><?php echo $record['caption']; ?></p></li>
<?php
}
?>
	</ul>
</div>

<?php echo $this->Html->script('/cycle/js/jquery.infinitecarousel2.min.js'); ?>
<script type="text/javascript">
$(function(){
	$('#<?php echo $uuid; ?>').infiniteCarousel({
		transitionSpeed: 2000,
		displayTime: <?php echo ($delay * 1000); ?>,
		autoStart: <?php echo $autoplay; ?>,
		textholderHeight: .25,
		displayProgressBar: 1,
		displayThumbnailNumbers: 1,
		imagePath: '/cycle/img/infinitecarousel/'
	});
});
</script>
