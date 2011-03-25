<?php
/**
 * CycleHook Component
 *
 * @category Component
 * @package  Cycle
 * @author   Tom Maiaroto <tom@shift8creative.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.shift8creative.com
 */
class CycleHookComponent extends Object {
	
	var $controller;
	var $components = array('Cycle.ImageVersion');
	
/**
 * Called after the Controller::beforeFilter() and before the controller action
 *
 * @param object $controller Controller with components to startup
 * @return void
 */
    function startup(&$controller) {
    	$this->controller =& $controller;
    	// Add the Cycle behavior that will attach data to nodes for us
    	$this->controller->Node->Behaviors->attach('Cycle.Cycle');
    	// We need to bind the HABTM for Cycle here too along with the behavior - Why??
    	$this->controller->Node->bindModel(array('hasAndBelongsToMany' => array('Cycle' => array('className' => 'Cycle.Cycle', 'joinTable' => 'cycles_nodes'))));    	
    }
/**
 * Called after the Controller::beforeRender(), after the view class is loaded, and before the
 * Controller::render()
 *
 * @param object $controller Controller with components to beforeRender
 * @return void
 */
    function beforeRender(&$controller) {
    	// Actions link for nodes list -- this gets a little much, no? How many links can be in the actions area? Maybe have a settings somehwere to control this for users who want it shown
    	if(Inflector::camelize(Inflector::singularize($controller->params['controller'])) == 'Node') {
    		// Configure::write('Admin.rowActions.Cycle', 'plugin:cycle/controller:cycles/action:index/:id');
    	}
        
        // Admin menu: admin_menu element of NodeSchema plugin will be shown in admin panel's navigation
        Configure::write('Admin.menus.cycle', 1);        
        $this->controller =& $controller;

        // debug($this->controller->viewVars); // this would show all the fused data                
        // SOME ADMIN METHODS - NEED VALUES FOR FORMS (CycleHelper hook's calls to form helper will need this data)
        if($this->controller->params['controller'] == 'nodes') {
        	if(($this->controller->action == 'admin_add') || ($this->controller->action == 'admin_edit')) {
		        $controller->viewVars['cycles'] = $this->controller->Node->Cycle->find('list');
		        //debug($controller->viewVars['cycles']);
			}
			// Set existing values for forms to use if admin_edit
			if($this->controller->action == 'admin_edit') {				
				// This is a HABTM association, but currently only one is allowed (the select is not multiple) ... So there should only be one entry returned (for now)
				$cycle_data = $this->controller->Node->CyclesNode->find('all', array('conditions' => array('node_id' => $controller->data['Node']['id'])));
				if($cycle_data) {
					$key = key($cycle_data);
					$controller->data['CyclesNode'][$key] = $cycle_data[$key];								
				}						
    		}
    		
    		// Don't need to do the following lookups for admin methods.
		    if((!isset($this->controller->params['admin'])) || ($this->controller->params['admin'] == 0)) {
			    // Have to go deeper and get all the cycle records, the recursion is too deep or can't be set without editing core files
			    // For multiple node records (index pages for example)		    
			    if(isset($this->controller->viewVars['nodes'])) {
			    	// $this->controller->viewVars['nodes_for_layout']['recent_posts'] has the cycle_ids we need...but I'm not going to rely on that always being avaiable and will make new queries here to find the cycle_id for each node on the current page.
					$i=0;
					foreach($this->controller->viewVars['nodes'] as $node) {
						// First get the cycle for this Node, we have to query the join table for this because we don't want to put "cycle_id" on the Node table itself which would modify the core CMS. This is almost identical to the code below.
						$cycle_node = $this->controller->Node->CyclesNode->find('first', array('conditions' => array('CyclesNode.node_id' => $node['Node']['id'])));
						// IF there's a cycle for this node...
						if((!empty($cycle_node)) && (isset($cycle_node['CyclesNode']['cycle_id']))) {					
							$cycle_id = $cycle_node['CyclesNode']['cycle_id']; // just convience so we can write less code below in additional queries and caching
												
							// CACHED FIND FOR CYCLE & CYCLE RECORDS
							$cycle_records = Cache::read('cycle_records_'.$cycle_id);
							if($cycle_records === false) {
								$this->controller->Node->Cycle->unbindModel(array('hasAndBelongsToMany' => array('Node'))); // Don't need this here, we have what we need from it.
								$cycle_records = $this->controller->Node->Cycle->find('first', array('conditions' => array('id' => $cycle_id)));
								Cache::write('cycle_records_'.$cycle_id, $cycle_records);
							}
							// Set some data for our view (the helper needs it)
							$this->controller->viewVars['nodes'][$i]['Cycle'] = $cycle_records['Cycle'];
							$this->controller->viewVars['nodes'][$i]['Cycle']['CycleRecord'] = $cycle_records['CycleRecord'];
							$this->controller->viewVars['nodes'][$i]['Cycle']['CyclesNode'] = $cycle_node['CyclesNode']; // this holds data we need specific to each relationship
						}
					$i++;
					}
			    }
			    // For single node records (view method for example) ... very similar to the above, just one is a loop and i'd combine the code but they work with different array keys
			    //debug($this->controller->viewVars['node']);
			    if(isset($this->controller->viewVars['node'])) {				
					// First get the cycle for this Node, we have to query the join table for this because we don't want to put "cycle_id" on the Node table itself which would modify the core CMS.
					$cycle_node = $this->controller->Node->CyclesNode->find('first', array('conditions' => array('CyclesNode.node_id' => $this->controller->viewVars['node']['Node']['id'])));
					// IF there's a cycle for this node...
					if((!empty($cycle_node)) && (isset($cycle_node['CyclesNode']['cycle_id']))) {					
						$cycle_id = $cycle_node['CyclesNode']['cycle_id']; // just convience so we can write less code below in additional queries and caching
											
						// CACHED FIND FOR CYCLE & CYCLE RECORDS
						$cycle_records = Cache::read('cycle_records_'.$cycle_id);
						if($cycle_records === false) {
							// The following line no longer works because Containable was used with Node model. So an additional find is required.
							// $cycle_records = $cycle_records = $this->controller->Node->Cycle->find('first', array('conditions' => array('id' => $this->controller->viewVars['node']['Cycle'][0]['id'])));
							// ...but before the cycle_id was already retrieved for us with the Node find that Croogo made, which would've saved us the join table query above. 
							// Not a major issue, but the Node find results were cached, which included the cycle_id. Now we have an uncached query because of this disconnect between Node and Cycle...But again, we don't want to alter the core.
							
							// Get the cycle records		
							$this->controller->Node->Cycle->unbindModel(array('hasAndBelongsToMany' => array('Node'))); // Don't need this here, we have what we need from it.
							$cycle_records = $this->controller->Node->Cycle->find('first', array('conditions' => array('Cycle.id' => $cycle_id)));
							
							Cache::write('cycle_records_'.$cycle_id, $cycle_records); // Store into cache
						}						
						// Set some data for our view (the helper needs it)	
						$this->controller->viewVars['node']['Cycle'] = $cycle_records['Cycle'];
						$this->controller->viewVars['node']['Cycle']['CycleRecord'] = $cycle_records['CycleRecord'];
						$this->controller->viewVars['node']['Cycle']['CyclesNode'] = $cycle_node['CyclesNode']; // this holds data we need specific to each relationship
					}			
			    }			    	
	        } // end if (non-admin queries)
        }
        
    }
/**
 * Called after Controller::render() and before the output is printed to the browser.
 *
 * @param object $controller Controller with components to shutdown
 * @return void
 */
    function shutdown(&$controller) {
    } 
    
}
?>
