<?php
class CyclesController extends CycleAppController {
	
	var $name = 'Cycles';
	var $uses = array('Cycle.Cycle');
	var $components = array('Cycle.ImageVersion');
	var $helpers = array('Cycle.ImageVersion');
	// var $persistModel = true; // There's a problem using this for some reason, but we do have a lot of caching already.
	var $paginate;
	
	function admin_index() {
        $this->set('title_for_layout', __('Cycle', true));
        $this->NodeSchema->recursive = 0;
        $this->paginate['Cycle']['order'] = 'Cycle.title ASC';
        $this->set('records', $this->paginate());
    }
	
	function admin_add() {
		$this->set('title_for_layout', __('Add Cycle', true));
		
        if (!empty($this->data)) {        	
            $this->Cycle->create();
            if ($this->Cycle->saveAll($this->data)) {
                $this->Session->setFlash(__('The Cycle has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The Cycle could not be saved. Please, try again.', true));
            }
        }
                       
        $cycle_records = $this->Cycle->CycleRecord->find('list');
        $this->set(compact('cycle_records'));
	}
	
	function admin_edit($id = null) {
        $this->set('title_for_layout', __('Edit Cycle', true));

        if(!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid Cycle', true));
            $this->redirect(array('action'=>'index'));
        }
        if(!empty($this->data)) {        	                
            if ($this->Cycle->save($this->data)) {
            	// Don't forget to clear the cache!            	
            	Cache::delete('cycle_records_'.$this->Cycle->id); // the query cache
            	clearCache('cycle_'.$this->Cycle->id, 'views', ''); // the view cache for the elements, they don't have .php extensions!            	
                $this->Session->setFlash(__('The Cycle has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The Cycle could not be saved. Please, try again.', true));
            }
        }
        if(empty($this->data)) {        
        	// $this->Cycle->unbindModel(array('hasAndBelongsToMany' => array('Node'))); // Don't really need this for anything right now...eh, yea actually let's use it
            // $this->data = $this->Cycle->read(null, $id);
            $this->Cycle->Behaviors->attach('Containable'); // But we will use contain because we really don't need all the node body info, etc.
            $this->data = $this->Cycle->find('first', array(
            	'conditions' => array('Cycle.id' => $id),
            	'contain' => array(
            		'Node' => array(
            			'fields' => array(
            				'Node.id','Node.title','Node.path'
            			)
            		),
            		'CycleRecord' => array(
            			'fields' => array(
            				'CycleRecord.id','CycleRecord.title','CycleRecord.caption','CycleRecord.path','CycleRecord.link'
            			)
            		)
            	)
            ));           	
        }

        $cycle_records = $this->Cycle->CycleRecord->find('list');
        $this->set(compact('cycle_records'));
    }
    
    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for Cycle', true));
            $this->redirect(array('action'=>'index'));
        }
        if (!isset($this->params['named']['token']) || ($this->params['named']['token'] != $this->params['_Token']['key'])) {
            $blackHoleCallback = $this->Security->blackHoleCallback;
            $this->$blackHoleCallback();
        }
        if ($this->Cycle->delete($id)) {
            $this->Session->setFlash(__('Cycle deleted', true));
            $this->redirect(array('action'=>'index'));
        }
    }
    
    /*
     * Overtime the generated thumbnails will increase and as nodes change their cycle embed dimensions, these thumbnails will no longer be used (unless the cycle record is shared and used at the same dimension in several places).
     * So this method will clean out the thumbnail folders and help keep tidy, freeing up disk space. 
     * Both index pages (cycles and cycle_records) have links to this method and will clear all thumbnails, however a folder name could be passed to just clear a specific size.
     *
     * @param $folder_name String[optional] If provided, a specific folder with thumbnails will be removed instead of all thumbnails
    */
    function admin_clear_thumbnail_cache($folder_name=null) {
    	$this->autoRender = false;
    	if($folder_name[0] == '.') { $folder_name = null; return false; } // don't something like ../other-folder be passed, even though htaccess would catch and it wouldn't even get here, if code somewhere calls it.
    	$cycle_images_folder = new Folder(WWW_ROOT.'cycle_images');
    	if(empty($folder)) {
	    	$contents = $cycle_images_folder->read();
	    	foreach($contents[0] as $folder) {
	    		// Might as well do a final check to ensure it's really a folder just to be safe also don't remove the "thumb" folder, it's been put there by meio upload and it's not being used. There's nothing in it anyway.
	    		if((is_dir(WWW_ROOT.'cycle_images'.DS.$folder)) && ($folder != 'thumb')) {
	    			$cycle_images_folder->delete(WWW_ROOT.'cycle_images'.DS.$folder);
	    		}
	    	}
    	} else {
    		// Ensure it's a directory, don't let files be removed
    		if(is_dir(WWW_ROOT.'cycle_images'.DS.$folder_name)) {
    			$cycle_images_folder->delete(WWW_ROOT.'cycle_images'.DS.$folder_name);
    		}
    	}    
    	clearCache('cycles', 'views', ''); // Elements need to be cleared too in order to run PHP to regenerate new thumbnails
    	$this->redirect(array('action' => 'index'));
    }	   
    
}
?>
