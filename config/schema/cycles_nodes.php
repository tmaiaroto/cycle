<?php 
/* SVN FILE: $Id$ */
/* CyclesNodes schema generated on: 2010-04-29 18:04:28 : 1272584668*/
class CyclesNodesSchema extends CakeSchema {
	var $name = 'CyclesNodes';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $cycles_nodes = array(
		'cycle_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11, 'key' => 'index'),
		'node_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11, 'key' => 'index')
	);
}
?><!-- 0.172s -->