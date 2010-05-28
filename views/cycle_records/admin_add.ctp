<div class="cycle_records form">
    <h2><?php echo $title_for_layout; ?></h2>
    <?php 
    if(isset($cycle_id)) { $cycle_id_action = '/'.$cycle_id; }
    echo $form->create('CycleRecord', array('type' => 'file', 'action' => 'add'.$cycle_id_action));?>
        <fieldset>
            <div class="tabs">
                <ul>
                    <li><a href="#cycle_record"><?php __('Cycle Record'); ?></a></li>                   
                </ul>

                <div id="cycle_record">
                <?php
                	$selected = null;
                	if(isset($cycle_id)) {
                		foreach($cycles as $k => $v) {
                			// Ensure the requested cycle id is actually in the list of cycle options, we don't want to save bad associations
                			if($k == $cycle_id) {
                				$selected = $cycle_id;
                			}
                		}
                	}                
                	echo $form->input('title', array('label' => __('Title', true)));
                	echo $form->input('caption', array('label' => __('Caption', true)));
                	echo $form->input('link', array('label' => __('Link', true)));
                	echo $form->input('path', array('type' => 'file', 'label' => __('Image', true)));
                	echo $form->input('Cycle.Cycle', array('options' => $cycles, 'selected' => $selected, 'label' => __('Add to Cycle(s)', true), 'after' => '<span style="font-style: italic; clear: left; display: block; margin: 0px 0px 5px 0px; padding: 0px; font-size: 10px;">'.__('Ctrl-click or shift-click to choose multiple cycle groups.', true).'</span>'));
                ?>
                </div>                                                
            </div>
        </fieldset>
    <?php    	
    	echo $form->end('Submit');
    ?>
</div>
