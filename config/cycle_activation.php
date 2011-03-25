<?php
/**
 * CycleHook Activation Class 
 *
 * The Form class for activation / deactivation of the Omnilogic Form plugin.
 *
 * @category Activation Class
 * @package  Omnilogic Form Plugin
 * @version  1.0
 * @author   Omnilogic Systems Inc <contact@omnilogic.net>
 * @link     http://www.omnilogic.net
 */
class CycleActivation
{
    /**
     * onActivate will be called if this returns true
     *
     * @param  object $controller Controller
     * @return boolean
     **/
    public function beforeActivation(&$controller) {
        return true;
    }

    /**
     * Called after activating the plugin in ExtensionsPluginsController::admin_toggle()
     *
     * @param object $controller Controller
     * @return void
     */
    public function onActivation(&$controller) 
    {
				$controller->Croogo->addAco('Cycle'); // the controller
        $controller->Croogo->addAco('Cycle/admin_index'); // admin methods
        $controller->Croogo->addAco('Cycle/admin_add');
        $controller->Croogo->addAco('Cycle/admin_edit');
        $controller->Croogo->addAco('Cycle/admin_delete');
        $controller->Croogo->addAco('CycleRecord'); // the controller
        $controller->Croogo->addAco('CycleRecord/admin_index'); // admin methods
        $controller->Croogo->addAco('CycleRecord/admin_add');
        $controller->Croogo->addAco('CycleRecord/admin_edit');
        $controller->Croogo->addAco('CycleRecord/admin_delete');

				// Install the database tables we need
        App::Import('CakeSchema');
        $CakeSchema = new CakeSchema();
        $db =& ConnectionManager::getDataSource('default'); // TODO: How do we change this for installs?
        
        // A list of schema files to import for this plugin to work
        $schema_files = array(
         'cycles.php',
         'cycle_records.php',
         'cycle_records_cycles.php',
         'cycles_nodes.php'
        );
        foreach($schema_files as $schema_file) {
        	$class_name = Inflector::camelize(substr($schema_file, 0, -4)).'Schema';
        	$table_name = substr($schema_file, 0, -4);
        	// Only build the tables if they don't already exist
        	if(!in_array($table_name, $db->_sources)) {
						include_once(APP.'plugins'.DS.'cycle'.DS.'config'.DS.'schema'.DS.$schema_file); // Can app import also work here?
						$ActivateSchema = new $class_name;
						$created = false;
						if(isset($ActivateSchema->tables[$table_name])) {
							$db->execute($db->createSchema($ActivateSchema, $table_name));
						}
					}
       }
    }

    /**
     * onDeactivate will be called if this returns true
     *
     * @param  object $controller Controller
     * @return boolean
     */
    public function beforeDeactivation(&$controller) {
        return true;
    }

    /**
     * Called after deactivating the hook in ExtensionsPluginsController::admin_toggle()
     *
     * @param object $controller Controller
     * @return void
     */
    public function onDeactivation(&$controller) 
    {
				// ACL: remove ACOs with permissions
        $controller->Croogo->removeAco('CycleRecord');
        $controller->Croogo->removeAco('Cycle'); // CycleController ACO and it's actions will be removed
    }
}
?>
