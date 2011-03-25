<?php
class CycleRecordsController extends CycleAppController {
	
	var $name = 'CycleRecords';
	var $uses = array('Cycle.CycleRecord');
	// var $persistModel = true; // There's a problem using this for some reason, but we do have a lot of caching already.
	var $paginate;
			
	function admin_index() {
        $this->set('title_for_layout', __('Cycle Records', true));
        $this->NodeSchema->recursive = 0;
        $this->paginate['CycleRecord']['order'] = 'CycleRecord.title ASC';
        $this->set('records', $this->paginate());
    }
	
	function admin_add($cycle_id=null) {
		$this->set('title_for_layout', __('Add Cycle Record', true));
		$redirect = array('action' => 'index');
		if(!empty($cycle_id)) {
			$this->set('cycle_id', $cycle_id);
			$redirect = array('controller' => 'cycles', 'action' => 'edit', $cycle_id . '#current-records');
		}
		
        if (!empty($this->data)) {        	
            $this->CycleRecord->create();
            if ($this->CycleRecord->saveAll($this->data)) {
            	// Don't forget to clear the cache!
            	foreach($this->data['Cycle']['Cycle'] as $cycle) {
            		Cache::delete('cycle_records_'.$cycle); // the query cache
            		clearCache('cycle_'.$cycle, 'views', ''); // the view cache for the elements, they don't have .php extensions!
            	}
                $this->Session->setFlash(__('The Cycle Record has been saved', true));
                $this->redirect($redirect);
            } else {
                $this->Session->setFlash(__('The Cycle Record could not be saved. Please, try again.', true));
            }
        }
        $cycles = $this->CycleRecord->Cycle->find('list');
        $this->set(compact('cycles'));        
	}
	
	function admin_edit($id = null) {
        $this->set('title_for_layout', __('Edit Cycle Record', true));

        if(!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid Cycle Record', true));
            $this->redirect(array('action'=>'index'));
        }
        if(!empty($this->data)) {        	                
            if ($this->CycleRecord->save($this->data)) {
            	// Don't forget to clear the cache!
            	foreach($this->data['Cycle']['Cycle'] as $cycle) {
            		Cache::delete('cycle_records_'.$cycle); // the query cache
            		clearCache('cycle_'.$cycle, 'views', ''); // the view cache for the elements, they don't have .php extensions!
            	}
                $this->Session->setFlash(__('The Cycle Record has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The Cycle Record could not be saved. Please, try again.', true));
            }
        }
        if(empty($this->data)) {
            $this->data = $this->CycleRecord->read(null, $id);
        }
		$cycles = $this->CycleRecord->Cycle->find('list');
        $this->set(compact('cycles'));
    }
    
    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for Cycle Record', true));
            $this->redirect(array('action'=>'index'));
        }
        if (!isset($this->params['named']['token']) || ($this->params['named']['token'] != $this->params['_Token']['key'])) {
            $blackHoleCallback = $this->Security->blackHoleCallback;
            $this->$blackHoleCallback();
        }
        if ($this->CycleRecord->delete($id)) {
            $this->Session->setFlash(__('Cycle Record deleted', true));
            $this->redirect(array('action'=>'index'));
        }
    }

		function admin_remove_from_cycle($cycle_record_id,$cycle_id) {
			if (!isset($this->params['named']['token']) || ($this->params['named']['token'] != $this->params['_Token']['key'])) {
        $blackHoleCallback = $this->Security->blackHoleCallback;
        $this->$blackHoleCallback();
      }

			$cycleRecordsCycleModel = ClassRegistry::init('Cycle.CycleRecordsCycle');
			$cycleRecordsCycle = $cycleRecordsCycleModel->find(
				'first',
				array(
					'conditions' => array(
						'cycle_record_id' => $cycle_record_id,
						'cycle_id' => $cycle_id
					)
				)
			);
			if (!empty($cycleRecordsCycle)) {
				// clear cache
				Cache::delete('cycle_records_'.$cycle_id); // the query cache
        clearCache('cycle_'.$cycle_id, 'views', ''); // the view cache for the elements, they don't have .php extensions!            	
				// delete
				$cycleRecordsCycleModel->deleteAll(
					array(
						'cycle_record_id' => $cycle_record_id,
						'cycle_id' => $cycle_id
					)
				);
				$this->Session->setFlash(__('Cycle Record removed from Cycle',true));
				$this->redirect(array('plugin'=>'cycle','controller'=>'cycles','action'=>'edit',$cycle_id,'#'=>'current_records'));
			}
			$this->Session->setFlash(__('Cycle Record not found',true));
			$this->redirect(array('plugin'=>'cycle','controller'=>'cycles','action'=>'index'));
		}
    
}
?>
