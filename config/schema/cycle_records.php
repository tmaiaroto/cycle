<?php 
/* SVN FILE: $Id$ */
/* CycleRecords schema generated on: 2010-05-28 12:05:44 : 1275069524*/
class CycleRecordsSchema extends CakeSchema {
	var $name = 'CycleRecords';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $cycle_records = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11, 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
		'caption' => array('type' => 'text', 'null' => true, 'default' => NULL, 'length' => NULL),
		'link' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 200),
		'path' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 255),
		'mime_type' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'created' => array('type' => 'timestamp', 'null' => true, 'default' => NULL, 'length' => NULL),
		'updated' => array('type' => 'timestamp', 'null' => false, 'default' => '0000-00-00 00:00:00', 'length' => NULL)
	);
}
?><!-- 0.568s -->
