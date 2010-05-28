<div class="cycles form">
    <h2><?php echo $title_for_layout; ?></h2>
    <?php echo $form->create('Cycle');?>
        <fieldset>
            <div class="tabs">
                <ul>
                    <li><a href="#cycle"><?php __('Cycle'); ?></a></li>                   
                </ul>

                <div id="cycle">
                <?php
                	echo $form->input('title', array('label' => __('Title', true)));      
                	echo '<br /><span style="font-style: italic; font-size: 11px;">'.__('The following are optional settings that may not all be used depending on the type of display.').'</span>';
                	echo $form->input('autoplay', array('checked' => 'checked', 'label' => __('Auto Play on Page Load', true)));
                	echo $form->input('loop', array('checked' => 'checked', 'label' => __('Loop', true)));
                	echo $form->input('delay', array('value' => '5', 'after' => '<span style="clear: left; display: block; font-size: 11px; font-style: italic;">'.__('In seconds, 0 for unlimited/manual change required.', true).'</span>', 'label' => __('Delay Between Records', true)));
                	echo $form->input('background_hex', array('value' => '000000', 'after' => '<span style="clear: left; display: block; font-size: 11px; font-style: italic;">'.__('A web hex number for the background color when images don\'t fit properly into the cycle area. <br />(Note: images with transparency will have a transparent "letterbox")', true).'</span>', 'label' => __('Background/Letterbox Color', true)));                	
                ?>
                </div>                                                
            </div>
        </fieldset>
    <?php    	
    	echo $form->end('Submit');
    ?>
</div>
