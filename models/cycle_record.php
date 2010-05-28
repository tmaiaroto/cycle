<?php
/**
 * Cycle Model
 *
 * @category Model
 * @package  Cycle
 * @author   Tom Maiaroto <tom@shift8creative.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.shift8creative.com
 */
class CycleRecord extends CycleAppModel {
	
	var $name = 'CycleRecord';	
	var $hasAndBelongsToMany = array('Cycle' => array('className' => 'Cycle.Cycle'));
	var $actsAs = array(
		'MeioUpload.MeioUpload' => array(
			'path' => array(
				'dir' => 'cycle_images',
				'fields' => array(
					'mimetype' => 'mime_type'
				),
				'allowedMime' => array('image/jpeg', 'image/pjpeg', 'image/png', 'image/gif'),
				'allowedExt' => array('.jpg', '.jpeg', '.png', '.gif'),
			)
		)
	);
	
	var $validate = array(
		'title' => array(
            'rule' => 'notEmpty',
            'message' => 'This field cannot be left blank.',
        )
	);
		
	function beforeDelete($cascade) {	    
		$record = $this->read(null, $this->id);
		// Don't forget to clear the cache!
        $cycles = Set::extract('/Cycle/id', $record);
        foreach($cycles as $id) {
        	Cache::delete('cycle_records_'.$id); // the query cache
        	clearCache('cycle_'.$id, 'views', ''); // the view cache for the elements, they don't have .php extensions!
        }
        // Also remove the image (MeioUpload didn't seem to) // TODO: Maybe also figure out how to delete all versions of this image too
        $image = Set::extract('/CycleRecord/path', $record);
        if(is_file(WWW_ROOT.'cycle_images'.DS.$image[0])) {        
        	@unlink(WWW_ROOT.'cycle_images'.DS.$image[0]);
        }        
        return true;
	}
	
}
?>
