<?php 
/* SVN FILE: $Id$ */
/* Cycles schema generated on: 2010-05-28 13:05:28 : 1275069628*/
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
		'autoplay' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'length' => 1),
		'loop' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'length' => 1),
		'delay' => array('type' => 'integer', 'null' => false, 'default' => '5', 'length' => 4),
		'background_hex' => array('type' => 'string', 'null' => true, 'default' => '000000', 'length' => 11),
		'created' => array('type' => 'timestamp', 'null' => true, 'default' => NULL, 'length' => NULL),
		'updated' => array('type' => 'timestamp', 'null' => false, 'default' => '0000-00-00 00:00:00', 'length' => NULL)
	);
}
?><!-- 0.496s -->
