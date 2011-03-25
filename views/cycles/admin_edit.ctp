<div class="cycles form">
    <h2><?php echo $title_for_layout; ?></h2>
    <?php echo $form->create('Cycle');?>
        <fieldset>
            <div class="tabs">
                <ul>
                    <li><a href="#cycle"><?php __('Cycle'); ?></a></li>
                    <li><a href="#current-records"><?php __('Current Cycle Records'); ?></a></li>
                    <li><a href="#current-nodes"><?php __('Nodes Using This Cycle'); ?></a></li>
                    <li><a href="#preview"><?php __('Preview'); ?></a></li>
                </ul>

                <div id="cycle">
                <?php
                	echo $form->input('id', array('type' => 'hidden'));   
                	echo $form->input('title', array('label' => __('Title', true)));
                	echo '<br /><span style="font-style: italic; font-size: 11px;">'.__('The following are optional settings that may not all be used depending on the type of display.').'</span>';
                	echo $form->input('autoplay', array('label' => __('Auto Play on Page Load', true)));
                	echo $form->input('loop', array('label' => __('Loop', true)));
                	echo $form->input('delay', array('after' => '<span style="clear: left; display: block; font-size: 11px; font-style: italic;">'.__('In seconds, 0 for unlimited/manual change required.', true).'</span>', 'label' => __('Delay Between Records', true)));                	
                	echo $form->input('background_hex', array('after' => '<span style="clear: left; display: block; font-size: 11px; font-style: italic;">'.__('A web hex number for the background color when images don\'t fit properly into the cycle area. <br />(Note: images with transparency will have a transparent "letterbox")', true).'</span>', 'label' => __('Background/Letterbox Color', true)));
                ?>
                </div>  
                
                <div id="current-records">                	
                	 <div class="actions">
				        <ul>
				            <li><?php echo $html->link(__('List All Cycle Records', true), array('plugin' => 'cycle', 'controller' => 'cycle_records', 'action'=>'index')); ?></li>
				            <li><?php echo $html->link(__('Add Record to this Cycle', true), array('plugin' => 'cycle', 'controller' => 'cycle_records', 'action'=>'add', $this->data['Cycle']['id'])); ?></li>
				        </ul>
				    </div>
                	<table cellpadding="0" cellspacing="0">
				    <?php
				        $tableHeaders =  $html->tableHeaders(array(
				            __('id', true),
				            __('Title', true),
				            __('Actions', true),
				        ));
				        echo $tableHeaders;

				        $rows = array();
				        foreach($this->data['CycleRecord'] as $record) {
				            $actions  = $html->link(__('Edit', true), array('plugin' => 'cycle', 'admin' => true, 'controller' => 'cycle_records', 'action' => 'edit', $record['id']));
				            $actions .= '|';
				            $actions .= ' ' . $html->link(__('Remove from Cycle', true), array(
				                'controller' => 'cycle_records',
				                'action' => 'remove_from_cycle',
				                $record['id'],
												$this->data['Cycle']['id'],
				                'token' => $this->params['_Token']['key'],
				            ), null, __('Are you sure? This will not delete the cycle record, only the association to this cycle.', true));

				            $rows[] = array(
				                $record['id'],
				                $html->link($record['title'], '/cycle_images/'.$record['path'], array('target' => '_blank')),
				                $actions,
				            );
				        }

				        echo $html->tableCells($rows);
				        echo $tableHeaders;
				    ?>
				    </table>
                </div> 
                
                <div id="current-nodes">
                	<table cellpadding="0" cellspacing="0">
				    <?php
				        $tableHeaders =  $html->tableHeaders(array(
				            __('id', true),
				            __('Title', true),
				            __('Actions', true),
				        ));
				        echo $tableHeaders;

				        $rows = array();
				        foreach($this->data['Node'] as $record) {
				            $actions  = $html->link(__('Edit Node', true), array('plugin' => false, 'admin' => true, 'controller' => 'nodes', 'action' => 'edit', $record['id']));
				            $actions .= '|';
				            $actions .= ' ' . $html->link(__('Remove from Node', true), array(
				                'controller' => 'cycles',
				                'action' => 'remove_from_node',
				                $record['id'],
				                'token' => $this->params['_Token']['key'],
				            ), null, __('Are you sure? This will not delete the cycle or any cycle records, only the association to this node.', true));

				            $rows[] = array(
				                $record['id'],
				                $html->link($record['title'], $record['path'], array('target' => '_blank')),
				                $actions,
				            );
				        }

				        echo $html->tableCells($rows);
				        echo $tableHeaders;
				    ?>
				    </table>    
                </div>
                
                <div id="preview">
                	<div>
                		<?php 
                		$style_elements_folder = new Folder(APP.'plugins'.DS.'cycle'.DS.'views'.DS.'elements'.DS.'cycles');                		
                		$style_files = $style_elements_folder->find(); // get all the template files                		
                		?>
                		<select id="preview_style">                			
                			<option value="">--Choose a Style to Preview--</div>
                			<?php                 				
                				foreach($style_files as $style_file) { 
                					// remove the .ctp
                					$template = substr($style_file, 0, -4);
                					$selected = '';
                					if((isset($this->params['named']['preview_style'])) && ($this->params['named']['preview_style'] == $template)) {
                						$selected = 'selected';
                					}
                					echo '<option value="'.$template.'" '.$selected.'>'.Inflector::humanize($template).'</option>';
                				}
                			?>                			
                		</select>
                		<script type="text/javascript">
                			$("#preview_style").live('change', function() { 
                				window.location = '<?php echo '/admin/cycle/cycles/edit/'.$this->data['Cycle']['id'] . '/'; ?>preview_style:' + $("#preview_style :selected").val() + '#preview'; 
                			});
                		</script>
                	</div>
                	<div style="width: 600px;">
                	<?php //debug($this->data['CycleRecord']);                             	
                	$values = array(		    		
			    		'uuid' => 'cycle_preview_' . $this->uuid('div', '/'),
			    		'plugin' => 'cycle', 
			    		'records' => $this->data['CycleRecord'], 
			    		'width' => '700', 
			    		'height' => '350', 
			    		'delay' => $this->data['Cycle']['delay'], 
			    		'autoplay' => $this->data['Cycle']['autoplay'], 
			    		'loop' => $this->data['Cycle']['loop'],
			    		'letterbox_color' => '#000000'    		
			    	);		
			    	if((isset($this->params['named']['preview_style'])) && (!empty($this->params['named']['preview_style']))) {
			    		echo $this->element('cycles/'.$this->params['named']['preview_style'], $values);
			    	}
                	?>
                	</div>
                	
                </div>
                                                      
            </div>
        </fieldset>
    <?php    	
    	echo $form->end('Submit');
    ?>
</div>
