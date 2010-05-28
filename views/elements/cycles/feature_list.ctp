<?php
/* Feature List
 * More Info: http://jqueryglobe.com/article/feature-list
 *
 * This is the element responsible for embedding the jQuery Feature List plugin for displaying the cycle.
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
if(!$letterbox_color) { $letterbox_color = '000000'; } // black if not specified
?>
<style><!--
div#<?php echo $uuid; ?>-container {
	width: <?php echo $width; ?>px;
	height: <?php echo $height; ?>px;
	overflow: hidden;
	position: relative;
}
div#<?php echo $uuid; ?>-container ul {
	position: absolute;
	top: 0;
	list-style: none;	
	padding: 0;
	margin: 0;
}
ul#<?php echo $uuid; ?>-tabs {
	left: 0;
	z-index: 2;
	width: 320px;
}

ul#<?php echo $uuid; ?>-tabs li {
	font-size: 12px;
	font-family: Arial;
}

ul#<?php echo $uuid; ?>-tabs li img {
	padding: 5px;
	border: none;
	float: left;
	margin: 10px 10px 0 0;
}

ul#<?php echo $uuid; ?>-tabs li a {
	color: #222;
	text-decoration: none;	
	display: block;
	padding: 10px;
	height: 60px;
	outline: none;
}

ul#<?php echo $uuid; ?>-tabs li a:hover {
	text-decoration: underline;
}

ul#<?php echo $uuid; ?>-tabs li a.current {
	background:  url('/cycle/img/featurelist/feature-tab-current.png');
	color: #FFF;
}

ul#<?php echo $uuid; ?>-tabs li a.current:hover {
	text-decoration: none;
	cursor: default;
}


ul#<?php echo $uuid; ?>-output {
	right: 0;
	width: <?php echo ($width - 287); ?>px;
	height: <?php echo $height; ?>px;
	position: relative;
}

ul#<?php echo $uuid; ?>-output li {
	position: absolute;
	width: <?php echo ($width - 287); ?>px;
	height: <?php echo $height; ?>px;
}

ul#<?php echo $uuid; ?>-output li a {
	position: absolute;
	bottom: 10px;
	right: 10px;
	padding: 8px 12px;
	text-decoration: none;
	font-size: 11px;
	color: #FFF;
	background: #000;
	-moz-border-radius: 5px;
	border: 1px solid #444;
}
ul#<?php echo $uuid; ?>-output li img {
	border: 0px;
	padding: 0px;
	margin: 0px;
}

ul#<?php echo $uuid; ?>-output li a:hover {
	background: #007fff;
}
h3.<?php echo $uuid; ?> {
	margin: 0;	
	padding: 7px 0 0 0;
	font-size: 16px;
	text-transform: uppercase;
	font-family: Helvetica, sans-serif;
	font-weight: bold;
}
--></style>
<div id="<?php echo $uuid; ?>-container">
	<ul id="<?php echo $uuid; ?>-tabs">
<?php foreach($records as $record) { ?>		
		<li>
			<a href="javascript:;">			
			<?php echo $this->ImageVersion->version(array('image' => 'cycle_images/'.$record['path'], 'size' => array(32, 32), 'quality' => 80, 'crop' => true, 'letterbox' => $letterbox_color, 'sharpen' => false)); ?>
			<h3 class="<?php echo $uuid; ?>"><?php echo $record['title']; ?></h3>
			<span><?php echo $record['caption']; ?></span>
			</a>
		</li>
<?php
}
?>
	</ul>
	
	<ul id="<?php echo $uuid; ?>-output">
<?php foreach($records as $record) { ?>		
		<li>
			<?php
			$thumb_width = ($width - 287);			
			echo $this->ImageVersion->version(array('image' => 'cycle_images/'.$record['path'], 'size' => array($thumb_width, $height), 'quality' => 90, 'crop' => true, 'letterbox' => $letterbox_color, 'sharpen' => true), array('width' => $thumb_width, 'height' => $height)); ?>
			<?php echo $this->Html->link('Read More', $record['link']); ?>
			</a>
		</li>
<?php
}
?>
	</ul>	
</div>

<?php echo $this->Html->script('/cycle/js/jquery.featureList-1.0.0.js'); ?>

<script type="text/javascript">
$(document).ready(function() {
	$.featureList(
		$("#<?php echo $uuid; ?>-tabs li a"),
		$("#<?php echo $uuid; ?>-output li"), {
			start_item	:	1
		}
	);
});
</script>
