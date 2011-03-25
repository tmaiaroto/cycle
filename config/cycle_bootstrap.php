<?php

/**
* Component
*
* This plugin's OmniForm component will be loaded from AppController
*/
Croogo::hookComponent('*', 'Cycle.CycleHook');


/**
* Helper
*
* This plugin's OmniForm helper will be loaded inside LayoutHelper,
* and the extra callbacks supported for hook helpers by Croogo will be called.
*/
Croogo::hookHelper('*', 'Cycle.CycleHook');

/**
* Admin menu (navigation)
*
* This plugin's admin_menu element will be rendered in admin panel under Extensions menu.
*/
Croogo::hookAdminMenu('Cycle');

