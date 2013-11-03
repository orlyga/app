<?php
App::uses('AppHelper', 'View/Helper');
/**
 * Example Helper
 *
 * An example hook helper for demonstrating hook system.
 *
 * @category Helper
 * @package  Croogo
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class GroupHelper extends AppHelper {

/**
 * Other helpers used by this helper
 *
 * @var array
 * @access public
 */
	public $helpers = array(
		'Html',
		
	);

/**
 * Before render callback. Called before the view file is rendered.
 *
 * @return void
 */
	public function beforeRender($viewFile) {
	}

/**
 * After render callback. Called after the view file is rendered
 * but before the layout has been rendered.
 *
 * @return void
 */
public function setVarsbyGroupType($group_type){
	$group_var=array();
	switch ($group_type){
		case 4:
			$group_var['head_staff_type']="Kindergarten Teacher";
			break;
		case 5:
			$group_var['head_staff_type']="Teacher";
			break;
		default:
			
			break;
	}
	return $group_var;
}
}
