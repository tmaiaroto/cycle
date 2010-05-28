<?php 
/* SVN FILE: $Id$ */
/* Cycles schema generated on: 2010-04-29 18:04:44 : 1272584084*/
class CyclesSchema extends CakeSchema {
	var $name = 'Cycles';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $cycles = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11, 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
		'created' => array('type' => 'timestamp', 'null' => true, 'default' => NULL, 'length' => NULL),
		'updated' => array('type' => 'timestamp', 'null' => false, 'default' => '0000-00-00 00:00:00', 'length' => NULL)
	);
}
?><!-- 0.268s -->