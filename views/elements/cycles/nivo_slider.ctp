<?php
/* jQuery Nivo Slider
 * More Info: http://nivo.dev7studios.com
 *
 * This is the element responsible for embedding the jQuery Nivo Slider plugin for displaying the cycle.
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
	width: <?php echo $width; ?>px;
	position:relative;
	margin: 0px 0px 30px 0px;
	background:#202834 url(/cycle/img/nivo/loading.gif) no-repeat 50% 50%;
	-moz-box-shadow:0px 0px 10px #333;
	-webkit-box-shadow:0px 0px 10px #333;
	box-shadow:0px 0px 10px #333;
}
#<?php echo $uuid; ?> img {
	position:absolute;
	top:0px;
	left:0px;
}
#<?php echo $uuid; ?> a {
	border:0;	
}
/* The slices in the Slider */
.nivo-slice {
	display:block;
	position:absolute;
	z-index:50;
	height:100%;
}
/* Caption styles */
.nivo-caption {
	position:absolute;
	left:0px;
	bottom:0px;
	background:#000;
	color:#fff;
	opacity:0.8; /* Overridden by captionOpacity setting */
	width:100%;
	z-index:89;
}
.nivo-caption p {
	display: inline;
	padding:5px;
	margin:0;
}
.nivo-controlNav {
	position:absolute;
	left:47%;
	bottom:-30px;
}
.nivo-controlNav a {
	display:block;
	width:10px;
	height:10px;
	background:url(/cycle/img/nivo/bullets.png) no-repeat;
	text-indent:-9999px;
	border:0;
	margin-right:3px;
	float:left;
}
.nivo-controlNav a.active {
	background-position:-10px 0;
}
.nivo-directionNav a {
	display:block;
	width:32px;
	height:34px;
	background:url(/cycle/img/nivo/arrows.png) no-repeat;
	text-indent:-9999px;
	border:0;	
	position:absolute;
	top:45%;
	z-index:99;
	cursor:pointer;
}
a.nivo-nextNav {
	background-position:-32px 0;
	right:10px;
}
a.nivo-prevNav {
	left:10px;
}
/* If an image is wrapped in a link */
#<?php echo $uuid; ?> a.nivo-imageLink {
	position:absolute;
	top:0px;
	left:0px;
	width:100%;
	height:100%;
	border:0;
	padding:0;
	margin:0;
	z-index:60;
	display:none;
}
--></style>
<div id="<?php echo $uuid; ?>">   
      <?php foreach($records as $record) { ?>	      
	          <a href="<?php echo $record['link']; ?>"><?php echo $this->ImageVersion->version(array('image' => 'cycle_images/'.$record['path'], 'size' => array($width, $height), 'quality' => 90, 'crop' => false, 'letterbox' => $letterbox_color, 'sharpen' => true), array('width' => $width, 'height' => $height, 'title' => $record['caption'])); ?></a>
      <?php } ?>      
</div>
<?php // not sure this can have "false" as the second argument to move it to scripts for layout because of caching issues, if it is set to false, the index listing of nodes must be shown first, then it will be ok. If the view page for the specific node is shown and cached the index listing/paginated pages won't get the javascript
echo $this->Html->script('/cycle/js/jquery.nivo.slider.pack.js'); 
?>
<script type="text/javascript">
$(window).load(function() {
	$('#<?php echo $uuid; ?>').nivoSlider();
});
</script>
