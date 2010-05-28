<?php
/**
 * Cycle Model
 *
 * @category Model
 * @package  Cycle
 * @author   Tom Maiaroto <tom@shift8creative.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.shift8creative.com
 */
class Cycle extends CycleAppModel {
	
	var $name = 'Cycle';
	var $hasAndBelongsToMany = array('Node', 'CycleRecord' => array('className' => 'Cycle.CycleRecord'));	
	
	var $validate = array(
		'title' => array(
            'rule' => 'notEmpty',
            'message' => 'This field cannot be left blank.',
        )
	);
	
	function afterSave($created) {
		// CLEAR CACHE (for updates)
		if(($created === false) && (isset($this->data['Cycle']['id']))) {	
			$cycle = $this->read(null, $this->data['Cycle']['id']);
			foreach($cycle['CycleRecord'] as $record) {				
				touch(WWW_ROOT.'cycle_images'.DS.$record['path']); // each image
			}
			foreach($cycle['Node'] as $node) {				
				Cache::delete('node_1_'.$node['slug']); // the query cache for each node view with a cycle (do they always get cached as node_1 ??)
				Cache::delete('node_'.$node['id'].'_'.$node['slug']); // just in case
			}
			// clear the view element cache too
			clearCache('cycle_'.$this->data['Cycle']['id'], 'views', '');
			// clear the recent nodes cache, which we don't know if there's cycles in but to be safe we're removing them
			clearCache('croogo_nodes_recent', 'queries', '');
		}
	}
	
	function beforeDelete($cascade) {	
		// CLEAR CACHE (for deletes)
		$cycle = $this->read(null, $this->id);			
		foreach($cycle['Node'] as $node) {				
			Cache::delete('node_1_'.$node['slug']); // the query cache for each node view with a cycle (do they always get cached as node_1 ??)
			Cache::delete('node_'.$node['id'].'_'.$node['slug']); // just in case
		}
		// clear the view element cache too
		clearCache('cycle_'.$this->id, 'views', '');
		// clear the recent nodes cache, which we don't know if there's cycles in but to be safe we're removing them
		clearCache('croogo_nodes_recent', 'queries', '');
		// Remove all associations (not automatically done)
		$CyclesNode = new Model(false, 'cycles_nodes');
		$CyclesNode->alias = 'CyclesNode';
		if($CyclesNode) {
			$CyclesNode->deleteAll(array('CyclesNode.cycle_id' => $this->id), false);
		}
		return true;
	}
	
}
?>
