<?php
App::uses('AppHelper', 'View/Helper');

/**
 * Croogo Helper
 *
 * PHP version 5
 *
 * @category Helper
 * @package  Croogo.Croogo.View.Helper
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class GroupHelper extends AppHelper {

	public $helpers = array(
		'Html' => array('className' => 'Croogo.CroogoHtml'),
		);

/**
 * Provides backward compatibility for deprecated methods
 */
public function setVarsbyGroupType($group_type){
	$group_var=array();
	switch ($group_type){
		case 4:
			$group_var['head_staff_type']="Kindergarten teacher";
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
