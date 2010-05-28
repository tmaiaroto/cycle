<?php
/* jQuery Gallery View
 * More Info: http://plugins.jquery.com/node/13734
 *
 * This is the element responsible for embedding the jQuery Gallery View plugin for displaying the cycle.
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

/* GALLERY LIST */
/* IMPORTANT - Change '#photos' to the ID of your gallery list to prevent a flash of unstyled content */
#<?php echo $uuid; ?> { visibility: hidden; }

/* GALLERY CONTAINER */
.gallery { background: #000; border: 1px solid #aaa; padding: 5px; }

/* LOADING BOX */
.loader { background: url('/cycle/js/galleryview/loader.gif') center center no-repeat #000; }

/* GALLERY PANELS */
.panel { }

/* DEFINE HEIGHT OF PANEL OVERLAY */
/* NOTE - It is best to define padding here as well so overlay and background retain identical dimensions */
.panel .panel-overlay,
.panel .overlay-background { height: 60px; padding: 0 1em; }

/* PANEL OVERLAY BACKGROUND */
.panel .overlay-background { background: #000; }

/* PANEL OVERLAY CONTENT */
.panel .panel-overlay { color: white; font-size: 0.7em; }
.panel .panel-overlay a { color: white; text-decoration: underline; font-weight: bold; }

/* FILMSTRIP */
/* 'margin' will define top/bottom margin in completed gallery */
.filmstrip { margin: 5px; }

/* FILMSTRIP FRAMES (contains both images and captions) */
.frame {}

/* WRAPPER FOR FILMSTRIP IMAGES */
.frame .img_wrap { border: 1px solid #aaa; }

/* WRAPPER FOR CURRENT FILMSTRIP IMAGE */
.frame .current .img_wrap { border-color: #000;  padding: 0px; }

/* FRAME IMAGES */
.frame img { border: none; margin: 0px; padding: 0px; }

/* FRAME CAPTION */
.frame .caption { font-size: 11px; text-align: center; color: #888; }

/* CURRENT FRAME CAPTION */
.frame.current .caption { color: #000; }

/* POINTER FOR CURRENT FRAME */
.pointer {
	border-color: #fff;
}

.panel img, img.nav-prev, img.nav-next { border: 0px; text-decoration: none; }
.panel img { margin: 0px; padding: 0px; top: 0px; }

/* TRANSPARENT BORDER FIX FOR IE6 */
/* NOTE - DO NOT CHANGE THIS RULE */
*html .pointer {
	filter: chroma(color=pink);
}
--></style>
<ul id="<?php echo $uuid; ?>">
	<?php foreach($records as $record) { ?>
		<li>
			<?php echo $this->ImageVersion->version(array('image' => 'cycle_images/'.$record['path'], 'size' => array($width, $height), 'quality' => 90, 'crop' => false, 'letterbox' => $letterbox_color, 'sharpen' => true), array('alt' => $record['caption'])); ?>
			<!--<div class="panel-overlay">
				<strong><?php echo $record['title']; ?></strong><br />
				<?php echo $record['caption']; ?>
			</div>-->
		</li>	
    <?php } ?>   
</ul>

<?php // not sure these can have "false" as the second argument to move it to scripts for layout because of caching issues, if it is set to false, the index listing of nodes must be shown first, then it will be ok. If the view page for the specific node is shown and cached the index listing/paginated pages won't get the javascript 
echo $this->Html->script('/cycle/js/galleryview/jquery.timers-1.2.js'); 
echo $this->Html->script('/cycle/js/galleryview/jquery.easing.1.3.js'); 
echo $this->Html->script('/cycle/js/galleryview/jquery.galleryview-2.1.1-pack.js'); 
?>
<script type="text/javascript">
$(document).ready(function(){ 
	$('#<?php echo $uuid; ?>').galleryView({		
		panel_width: <?php echo $width; ?>,
		panel_height: <?php echo $height; ?>,			
		pause_on_hover: true,
		nav_theme: 'light'
	}) 
});
</script>
