<?php 
/* SVN FILE: $Id$ */
/* CycleRecordsCycles schema generated on: 2010-04-29 18:04:21 : 1272584541*/
class CycleRecordsCyclesSchema extends CakeSchema {
	var $name = 'CycleRecordsCycles';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $cycle_records_cycles = array(
		'cycle_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11, 'key' => 'index'),
		'cycle_record_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11, 'key' => 'index')
	);
}
?><!-- 0.16s -->
