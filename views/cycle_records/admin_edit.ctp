<div class="cycle_records form">
    <h2><?php echo $title_for_layout; ?></h2>
    <?php echo $form->create('CycleRecord', array('type' => 'file'));?>
        <fieldset>
            <div class="tabs">
                <ul>
                    <li><a href="#cycle_record"><?php __('Cycle Record'); ?></a></li>                   
                </ul>

                <div id="cycle_record">
                <?php
                	echo $form->input('id', array('type' => 'hidden'));
                	echo $form->input('title', array('label' => __('Title', true)));
                	echo $form->input('caption', array('label' => __('Caption', true)));
                	echo $form->input('link', array('label' => __('Link', true)));
                	echo $form->input('path', array('type' => 'file', 'label' => __('Image', true)));
                	echo $form->input('Cycle.Cycle', array('options' => $cycles, 'label' => __('Add to Cycle(s)', true), 'after' => '<span style="font-style: italic; clear: left; display: block; margin: 0px 0px 5px 0px; padding: 0px; font-size: 10px;">'.__('Ctrl-click or shift-click to choose multiple cycle groups.', true).'</span>'));
                ?>
                </div>                                                
            </div>
        </fieldset>
    <?php    	
    	echo $form->end('Submit');
    ?>
</div>
