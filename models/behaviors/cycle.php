<?php
/**
 * Cycle Behavior
 *
 * Hooks in to add a behavior to save the data to associate cycles to nodes.
 *
 * @category Behavior
 * @package  Cycle
 * @author   Tom Maiaroto <tom@shift8creative.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.shift8creative.com
 */
class CycleBehavior extends ModelBehavior {

	var $model;	
	
	function setup(&$model, $settings=array()) {	
		$this->model = $model;
		// if(isset($settings['value'])): $this->value = $settings['value']; endif;		
		$this->model->bindModel(array('hasAndBelongsToMany' => array('Cycle' => array('className' => 'Cycle.Cycle', 'joinTable' => 'cycles_nodes', 'with' => 'CyclesNode'))));
		//$this->model->bindModel(array('hasMany'=>array('CyclesNode')));	
	}

	function cleanup(&$model) {	}
			
	function afterSave($created) {
		// The main node record is now saved and associated - but we need to add more data to the join table 			
		if(!empty($this->model->data['Cycle']['Cycle'][0])) {			
			$CycleNodes = new Model(false, 'cycles_nodes');	
			$CycleNodes->primaryKey = 'node_id'; // was cycle_id ... why??		
			if(isset($this->model->data['Node']['id'])) {
				$this->model->data['CyclesNode']['node_id'] = $this->model->data['Node']['id'];
			} elseif(isset($this->model->id)) {
				$this->model->data['CyclesNode']['node_id'] = $this->model->id;
			}
			if(isset($this->model->data['CyclesNode']['node_id'])) {
				$CycleNodes->save($this->model->data['CyclesNode']); // would be a saveAll (and array structure might need to change) if there was multiple (probably won't do multiple)
				// Clear the thumbnail cache
				$this->model->Cycle->unbindModel(array('hasAndBelongsToMany' => array('Node')));
				$cycle_images = $this->model->Cycle->read(null, $this->model->data['Cycle']['Cycle'][0]);
				foreach($cycle_images['CycleRecord'] as $image) {
					touch(WWW_ROOT.'cycle_images'.DS.$image['path']); // Updates modified time on images so their thumbnails are regenerated					
				}
				// Clear element cache
				clearCache('cycle_'.$this->model->data['Cycle']['Cycle'][0], 'views', '');				
				// Node cache also has to be cleared, but will be done by Croogo core files
			}
		}		
	}
		
	function beforeDelete($cascade) {
		// Remove the associated data on delete. Not sure why the association isn't taking care of this. Did I miss a property setting?
		if((isset($this->model->data['Cycle'])) && (count($this->model->data['Cycle'] > 1))) {
			foreach($this->model->data['Cycle'] as $cycles) {				
				$this->model->CyclesNode->primaryKey = 'node_id';
				$this->model->CyclesNode->delete($cycles['CyclesNode']['node_id']);				
			}
		}
		return true;	
	}
			
}
?>
