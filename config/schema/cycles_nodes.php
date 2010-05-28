<?php 
/* SVN FILE: $Id$ */
/* CyclesNodes schema generated on: 2010-05-28 12:05:55 : 1275069475*/
class CyclesNodesSchema extends CakeSchema {
	var $name = 'CyclesNodes';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $cycles_nodes = array(
		'cycle_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11),
		'node_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11, 'key' => 'index'),
		'position' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 2),
		'style' => array('type' => 'string', 'null' => false, 'default' => 'jquery_infinite_carousel', 'length' => 100),
		'width' => array('type' => 'integer', 'null' => false, 'default' => '500', 'length' => 2),
		'height' => array('type' => 'integer', 'null' => false, 'default' => '200', 'length' => 2)
	);
}
?><!-- 0.412s -->
