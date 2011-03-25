<?php
/**
 * CycleHook Helper
 *
 * @category Helper
 * @package  Cycle
 * @author   Tom Maiaroto <tom@shift8creative.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.shift8creative.com
 */
class CycleHookHelper extends AppHelper {
/**
 * Other helpers used by this helper
 *
 * @var array
 * @access public
 */
    var $helpers = array(
        'Html',
        'Layout',
        'Form',
        'Cycle.ImageVersion'
    );

    function addFormSection() {    	
    	if((isset($this->Layout->View->viewVars['cycles'])) && (count($this->Layout->View->viewVars['cycles']) > 0)) {
	   		$jsBlock = '$(document).ready(function() { '; // open
    
    		// Add a section for cycles
    			$jsBlock .= '$("#node-main").append(\'<div id="NodeCycles" class="node_cycles"></div>\');'; 
    			// Give the section a title
    			$jsBlock .= '$("#NodeCycles").append(\'<a href="#" id="NodeCyclesExpand" class="node_cycles_title_link"><h3 class="node_cycles_title"><img src="/img/icons/bullet_arrow_right.png" rel="closed" class="node_cycles_expand_section_arrow" alt="expand or collapse section" />Cycle Plugin</h3></a>\');';
    			
    			$jsBlock .= '$("#NodeCyclesExpand").live("click", function() { 
    				$("#NodeCyclesContent").slideToggle(); 
    				if($("#NodeCycles .node_cycles_expand_section_arrow").attr("rel") == "open") {
    					$("#NodeCycles .node_cycles_expand_section_arrow").attr("src", "/img/icons/bullet_arrow_right.png");
    					$("#NodeCycles .node_cycles_expand_section_arrow").attr("rel", "closed");
    				} else {
    					$("#NodeCycles .node_cycles_expand_section_arrow").attr("src", "/img/icons/bullet_arrow_down.png");
    					$("#NodeCycles .node_cycles_expand_section_arrow").attr("rel", "open");
    				}
    				return false; 
    			});';
    			// Add content section
    			$jsBlock .= '$("#NodeCycles").append(\'<div id="NodeCyclesContent"></div>\');'; 	
    			$jsBlock .= '$("#NodeCyclesContent").hide("slide", { direction: "up" });'; // start closed
    		
    			// Add select list (manually, the helper causes escape issues)
    			$jsBlock .= '$("#NodeCyclesContent").append(\'<div class="input select"><label for="CycleCycle">Use Cycle</label><select style="margin-top: 10px;" name="data[Cycle][Cycle][]" id="CycleCycle"><option value="">None</option></select></div>\'); ';
    			// Only allowing one for now even though it's a HABTM (so using a different input name, the above would normally be used)
    			//$jsBlock .= '$("#NodeCyclesContent").append(\'<div class="input select"><label for="CyclesNodeCycleId">Use Cycle</label><select style="margin-top: 10px;" name="data[CyclesNode][cycle_id]" id="CyclesNodeCycleId"><option value="">None</option></select></div>\'); ';  
								
				// For now just one possible selection (probably always) - even though it's a HABTM association, still building and array to loop in case it changes
    			$cycles_selected = array();
    			$cycle_position = null;
    			$cycle_style = null;
    			$cycle_width = "500";
    			$cycle_height = "200";
    			// If the data is set at least (meaning admin_edit)
    			if(isset($this->data['CyclesNode'])) {
    				foreach($this->data['CyclesNode'] as $cycle_records) {
    				 	foreach($cycle_records['CyclesNode'] as $k => $v) {    				 	
	    					if($k == 'cycle_id') {
	    						$cycles_selected[] = $v; 
	    					}
	    					if($k == 'position') {
	    						// See, right here is where having multiple cycles would get screwed up. Unless we're combining cycles ... Or showing them all in the same position one after another. Otherwise, the form needs to change quite a bit, or the position is set on the cycle record which reduces flexibility...We'd then need to make a new cycle (group) record for each position EVEN IF the cycle had all the same records in it. Just so one node could have it one place and another node could have it another. Sounds like a waste.
	    						$cycle_position = $v;
	    					}
	    					if($k == 'style') {	    						
	    						$cycle_style = $v;
	    					}
	    					if($k == 'width') {
	    						$cycle_width = $v;
	    					}
	    					if($k == 'height') {
	    						$cycle_height = $v;
	    					}
    					}
    				}
    			}
    			// Set the drop down's options for cycles and preselect the currently chosen one if this is an admin_edit
    			foreach($this->Layout->View->viewVars['cycles'] as $k => $v) {
    				if(in_array($k, $cycles_selected)) {
    					$jsBlock .= '$("#CycleCycle").append(\'<option selected value="'.$k.'">'.$v.'</option>\');';
    				} else {
    					$jsBlock .= '$("#CycleCycle").append(\'<option value="'.$k.'">'.$v.'</option>\');';
    				}
    			}
    			
    			// Add select list for location of cycle
				$jsBlock .= '$("#NodeCyclesContent").append(\'<div class="input select"><label for="CyclesNodePosition">Position on Page</label><select style="margin-top: 10px;" name="data[CyclesNode][position]" id="CyclesNodePosition"></select></div>\'); ';
				// Now set that drop down's options				
				$positions = array(
					'1' => 'Before Node Info',
					'2' => 'After Node Info',
					'3' => 'Before Node Body',
					'4' => 'After Node Body',
					'5' => 'Before Node More Info',
					'6' => 'After Node More Info'
					// Maybe more in the future if more hooks are added...Where? I don't know. Maybe custom define positions later. I could have a model that stores this and associates but too much, just hard code id values that correspond to positions ... Didn't want to put large amounts of text in the table.
				);
				foreach($positions as $k => $v) {
					if($k == $cycle_position) {
    					$jsBlock .= '$("#CyclesNodePosition").append(\'<option selected value="'.$k.'">'.$v.'</option>\');';
    				} else {
    					$jsBlock .= '$("#CyclesNodePosition").append(\'<option value="'.$k.'">'.$v.'</option>\');';
    				}
				}
				
				// Add select list for style
				$jsBlock .= '$("#NodeCyclesContent").append(\'<div class="input select"><label for="CyclesNodeStyle">Cycle Style</label><select style="margin-top: 10px;" name="data[CyclesNode][style]" id="CyclesNodeStyle"></select></div>\'); ';
				// Now set that drop down's options	(shows everything within the elements/cycles folder whether they work or not, but allows users to add their own new display plugins)
				$style_elements_folder = new Folder(APP.'plugins'.DS.'cycle'.DS.'views'.DS.'elements'.DS.'cycles');                		
                $style_files = $style_elements_folder->find(); // get all the template files
                $styles = array();
                foreach($style_files as $style_file) { 
                	// remove the .ctp
                	$template = substr($style_file, 0, -4);
                	$styles[$template] = Inflector::humanize($template);
               	}		
				
				foreach($styles as $k => $v) {
					if($k == $cycle_style) {
    					$jsBlock .= '$("#CyclesNodeStyle").append(\'<option selected value="'.$k.'">'.$v.'</option>\');';
    				} else {
    					$jsBlock .= '$("#CyclesNodeStyle").append(\'<option value="'.$k.'">'.$v.'</option>\');';
    				}
				}
				
				// Add input for display width and height
				$jsBlock .= '$("#NodeCyclesContent").append(\'<div class="input text"><label for="CyclesNodeWidth">Display Width</label><input name="data[CyclesNode][width]" id="CyclesNodeWidth" type="text" maxlength="5" value="'.$cycle_width.'" /></div>\'); ';
				$jsBlock .= '$("#NodeCyclesContent").append(\'<div class="input text"><label for="CyclesNodeHeight">Display Height</label><input name="data[CyclesNode][height]" id="CyclesNodeHeight" type="text" maxlength="5" value="'.$cycle_height.'" /></div>\'); ';
				
				//$jsBlock .= '$("#NodeCyclesContent").append(\'<input type="hidden" name="data[CyclesNode][cycle_id]" value="1" />\'); ';
				
    			
    		$jsBlock .= ' });'; // close
    		return $jsBlock;
    	} else {
    		return;
    	}
    }
    
    
/**
 * Called after activating the hook in ExtensionsHooksController::admin_toggle()
 *
 * @param object $controller Controller
 * @return void
 */
    function onActivate(&$controller) {
    }
/**
 * Called after deactivating the hook in ExtensionsHooksController::admin_toggle()
 *
 * @param object $controller Controller
 * @return void
 */
    function onDeactivate(&$controller) {
    }
/**
 * Before render callback. Called before the view file is rendered.
 *
 * @return void
 */
    function beforeRender() {   
    	// IF ADMIN ADD OR EDIT
    	if((($this->action == 'admin_edit') || ($this->action == 'admin_add')) && ($this->params['controller'] == 'nodes')) {    	
    		$this->Html->scriptBlock($this->addFormSection(), array('inline' => false));	 
    		//debug($this->Layout->View->viewVars);    		
    	}     	   	    
    }
/**
 * After render callback. Called after the view file is rendered
 * but before the layout has been rendered.
 *
 * @return void
 */
    function afterRender() {    	   	
    	if($this->params['controller'] == 'nodes') {
    		if(($this->action == 'admin_add') || ($this->action == 'admin_edit')) {
       			echo $this->Html->css(array('/cycle/css/styles'));    	
       		}
    	}
    }
/**
 * Before layout callback. Called before the layout is rendered.
 *
 * @return void
 */
    function beforeLayout() {
    }
/**
 * After layout callback. Called after the layout has rendered.
 *
 * @return void
 */
    function afterLayout() {      		
    }
/**
 * Called after LayoutHelper::setNode()
 *
 * @return void
 */
    function afterSetNode() {     
    }
/**
 * Called before LayoutHelper::nodeInfo()
 * POSITION: 1
 *
 * @return string
 */
    function beforeNodeInfo() {     
        //debug($this->Layout->node['Cycle']);   
        if($this->params['controller'] == 'nodes') {
    		if(($this->action != 'admin_add') && ($this->action != 'admin_edit') && ($this->action != 'admin_index')) {   			    			
    			if((isset($this->Layout->node['Cycle'])) && ($this->Layout->node['Cycle']['CyclesNode']['position'] == 1)) {
    				// It may actually be that a cycle is associated but it has no records, so check first
    				if((isset($this->Layout->node['Cycle']['CycleRecord'])) && (isset($this->Layout->node['Cycle']['CycleRecord']))) {  
    					return $this->_outputCycleModule($this->Layout->node['Cycle']['CyclesNode']['style'], $this->Layout->node['Cycle']);
    				}    				
    			}
    		}
    	}
    }
/**
 * Called after LayoutHelper::nodeInfo()
 * POSITION: 2
 *
 * @return string
 */
    function afterNodeInfo() {
        if($this->params['controller'] == 'nodes') {
    		if(($this->action != 'admin_add') && ($this->action != 'admin_edit') && ($this->action != 'admin_index')) {   			    			
    			if((isset($this->Layout->node['Cycle'])) && ($this->Layout->node['Cycle']['CyclesNode']['position'] == 2)) {
    				// It may actually be that a cycle is associated but it has no records, so check first
    				if((isset($this->Layout->node['Cycle']['CycleRecord'])) && (isset($this->Layout->node['Cycle']['CycleRecord']))) {  
    					return $this->_outputCycleModule($this->Layout->node['Cycle']['CyclesNode']['style'], $this->Layout->node['Cycle']);
    				}    				
    			}
    		}
    	}
    }
/**
 * Called before LayoutHelper::nodeBody()
 * POSITION: 3
 *
 * @return string
 */
    function beforeNodeBody() {
        if($this->params['controller'] == 'nodes') {
    		if(($this->action != 'admin_add') && ($this->action != 'admin_edit') && ($this->action != 'admin_index')) {   			    			
    			if((isset($this->Layout->node['Cycle'])) && ($this->Layout->node['Cycle']['CyclesNode']['position'] == 3)) {
    				// It may actually be that a cycle is associated but it has no records, so check first
    				if((isset($this->Layout->node['Cycle']['CycleRecord'])) && (isset($this->Layout->node['Cycle']['CycleRecord']))) {  
    					return $this->_outputCycleModule($this->Layout->node['Cycle']['CyclesNode']['style'], $this->Layout->node['Cycle']);
    				}    				
    			}
    		}
    	}
    }
/**
 * Called after LayoutHelper::nodeBody()
 * POSITION: 4
 *
 * @return string
 */
    function afterNodeBody() {
        if($this->params['controller'] == 'nodes') {
    		if(($this->action != 'admin_add') && ($this->action != 'admin_edit') && ($this->action != 'admin_index')) {   			    			
    			if((isset($this->Layout->node['Cycle'])) && ($this->Layout->node['Cycle']['CyclesNode']['position'] == 4)) {
    				// It may actually be that a cycle is associated but it has no records, so check first
    				if((isset($this->Layout->node['Cycle']['CycleRecord'])) && (isset($this->Layout->node['Cycle']['CycleRecord']))) {  
    					return $this->_outputCycleModule($this->Layout->node['Cycle']['CyclesNode']['style'], $this->Layout->node['Cycle']);
    				}    				
    			}
    		}
    	}
    }
/**
 * Called before LayoutHelper::nodeMoreInfo()
 * POSITION: 5
 *
 * @return string
 */
    function beforeNodeMoreInfo() {
       if($this->params['controller'] == 'nodes') {
    		if(($this->action != 'admin_add') && ($this->action != 'admin_edit') && ($this->action != 'admin_index')) {   			    			
    			if((isset($this->Layout->node['Cycle'])) && ($this->Layout->node['Cycle']['CyclesNode']['position'] == 5)) {
    				// It may actually be that a cycle is associated but it has no records, so check first
    				if((isset($this->Layout->node['Cycle']['CycleRecord'])) && (isset($this->Layout->node['Cycle']['CycleRecord']))) {  
    					return $this->_outputCycleModule($this->Layout->node['Cycle']['CyclesNode']['style'], $this->Layout->node['Cycle']);
    				}    				
    			}
    		}
    	}
    }
/**
 * Called after LayoutHelper::nodeMoreInfo()
 * POSITION: 6
 *
 * @return string
 */
    function afterNodeMoreInfo() {
        if($this->params['controller'] == 'nodes') {
    		if(($this->action != 'admin_add') && ($this->action != 'admin_edit') && ($this->action != 'admin_index')) {   			    			
    			if((isset($this->Layout->node['Cycle'])) && ($this->Layout->node['Cycle']['CyclesNode']['position'] == 6)) {
    				// It may actually be that a cycle is associated but it has no records, so check first
    				if((isset($this->Layout->node['Cycle']['CycleRecord'])) && (isset($this->Layout->node['Cycle']['CycleRecord']))) {  
    					return $this->_outputCycleModule($this->Layout->node['Cycle']['CyclesNode']['style'], $this->Layout->node['Cycle']);
    				}    				
    			}
    		}
    	}
    }
    
/**
 * Outputs a JavaScript cycle module.
 *
 * @param $style[Int] The style id that chooses the cycle plugin to use. NOTE: More can be added this way, may make an easier/more modular way to do so.
 * @param $cycle_settings[Array] The cycle data which includes delay time, autoplay and other settings
 * @return string The HTML, JavaScript, and CSS to get the job done
 */
    function _outputCycleModule($style=null, $cycle_settings=array()) {
    	if(!$style) {
    		return;
    	}      	
    	//debug($this->Layout->node['Cycle']['CyclesNode']); 
    	$unique_id = 'cycle_' . $this->Layout->node['Cycle']['CyclesNode']['cycle_id'] . '_node_' . $this->Layout->node['Cycle']['CyclesNode']['node_id'];
    	$values = array(
    		'cache' => array('time' => '+1 day', 'key' => $unique_id), // TODO: possibly make this cache time a user controlled setting in the Cycle record or CyclesNode
    		'uuid' => $unique_id,
    		'plugin' => 'cycle', 
    		'records' => $this->Layout->node['Cycle']['CycleRecord'], 
    		'width' => $this->Layout->node['Cycle']['CyclesNode']['width'], 
    		'height' => $this->Layout->node['Cycle']['CyclesNode']['height'], 
    		'delay' => $cycle_settings['delay'], 
    		'autoplay' => $cycle_settings['autoplay'], 
    		'loop' => $cycle_settings['loop'],
    		'letterbox_color' =>  $cycle_settings['background_hex']
    	);
    	
    	// Embed the element that contains the cycle visualization (jquery plugins, etc.)
    	echo $this->Layout->View->element('cycles/'.$style, $values);
    }
  
}
?>
