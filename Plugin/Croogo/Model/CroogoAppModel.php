<?php

App::uses('Model', 'Model');

/**
 * Croogo App Model
 *
 * PHP version 5
 *
 * @category Croogo.Model
 * @package  Croogo.Croogo.Model
 * @version  1.5
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class CroogoAppModel extends Model {

/**
 * use Caching
 *
 * @var string
 */
	public $useCache = true;

/**
 * Default behaviors
 */
	public $actsAs = array(
		'Containable',
	);

/**
 * Display fields for admin_index. Use displayFields()
 *
 * @var array
 * @access protected
 */
	protected $_displayFields = array();

/**
 * Edit fields for admin_edit. Use editFields()
 *
 * @var array
 * @access protected
 */
	protected $_editFields = array();
	public $error_message;

/**
 * Constructor
 *
 * @param mixed  $id Set this ID for this model on startup, can also be an array of options, see above.
 * @param string $table Name of database table to use.
 * @param string $ds DataSource connection name.
 */
	public function __construct($id = false, $table = null, $ds = null) {
		Croogo::applyHookProperties('Hook.model_properties', $this);
		parent::__construct($id, $table, $ds);
	}
/*Orly Added*/
	public function getError() {
		
		if(count($this->validationErrors)>0){
			$x=array_keys($this->validationErrors);
			$entity=$x[0];
			$x=array_keys($this->validationErrors[$entity]);
			$fldname=$x[0];
			$error=$this->validationErrors[$entity][$fldname][0];
			$err_message=__('Problem with')." ".$fldname." ". __($error);
			return $err_message;
		}
		else 
			return false;
	}
/**
 * Override find function to use caching
 *
 * Caching can be done either by unique names,
 * or prefixes where a hashed value of $options array is appended to the name
 *
 * @param mixed $type Type of find operation (all / first / count / neighbors / list / threaded)
 * @param array $options Option fields (conditions / fields / joins / limit / offset / order / page / group / callbacks)
 * @return array Array of records, or Null of failure
 * @access public
 */
	public function find($type = 'first', $options = array()) {
		if ($this->useCache) {
			$cachedResults = $this->_findCached($type, $options);
			if ($cachedResults) {
				return $cachedResults;
			}
		}

		$args = func_get_args();
		$results = call_user_func_array(array('parent', 'find'), $args);
		if ($this->useCache) {
			if (isset($options['cache']['name']) && isset($options['cache']['config'])) {
				$cacheName = $options['cache']['name'];
			} elseif (isset($options['cache']['prefix']) && isset($options['cache']['config'])) {
				$cacheName = $options['cache']['prefix'] . md5(serialize($options));
			}

			if (isset($cacheName)) {
				$cacheName .= '_' . Configure::read('Config.language');
				Cache::write($cacheName, $results, $options['cache']['config']);
				$this->cacheConfig = $options['cache']['config'];
			}
		}
		return $results;
	}

/**
 * Check if find() was already cached
 *
 * @param mixed $type
 * @param array $options
 * @return array Array of records, or False when no records found in cache
 * @access private
 */
	protected function _findCached($type, $options) {
		if (isset($options['cache']['name']) && isset($options['cache']['config'])) {
			$cacheName = $options['cache']['name'];
		} elseif (isset($options['cache']['prefix']) && isset($options['cache']['config'])) {
			$cacheName = $options['cache']['prefix'] . md5(serialize($options));
		} else {
			return false;
		}

		$cacheName .= '_' . Configure::read('Config.language');
		$results = Cache::read($cacheName, $options['cache']['config']);
		if ($results) {
			return $results;
		}
		return false;
	}

/**
 * Updates multiple model records based on a set of conditions.
 *
 * call afterSave() callback after successful update.
 *
 * @param array $fields	 Set of fields and values, indexed by fields.
 *     Fields are treated as SQL snippets, to insert literal values manually escape your data.
 * @param mixed $conditions Conditions to match, true for all records
 * @return boolean True on success, false on failure
 * @access public
 */
	public function updateAll($fields, $conditions = true) {
		$args = func_get_args();
		$output = call_user_func_array(array('parent', 'updateAll'), $args);
		if ($output) {
			$created = false;
			$options = array();
			$field = sprintf('%s.%s', $this->alias, $this->primaryKey);
			if (!empty($args[1][$field])) {
				foreach ($args[1][$field] as $id) {
					$this->id = $id;
					$event = new CakeEvent('Model.afterSave', $this, array(
						$created, $options
					));
					$this->getEventManager()->dispatch($event);
				}
			}
			$this->_clearCache();
			return true;
		}
		return false;
	}

/**
 * Fix to the Model::invalidate() method to display localized validate messages
 *
 * @param string $field The name of the field to invalidate
 * @param mixed $value Name of validation rule that was not failed, or validation message to
 *	be returned. If no validation key is provided, defaults to true.
 * @access public
 */
	public function invalidate($field, $value = true) {
		return parent::invalidate($field, __d('croogo', $value));
	}

/**
 * Return formatted display fields
 *
 * @param array $displayFields
 * @return array
 */
	public function displayFields($displayFields = null) {
		if (isset($displayFields)) {
			$this->_displayFields = $displayFields;
		}
		$out = array();
		$defaults = array('sort' => true, 'type' => 'text', 'url' => array(), 'options' => array());
		foreach ($this->_displayFields as $field => $label) {
			if (is_int($field)) {
				$field = $label;
				list(, $label) = pluginSplit($label);
				$out[$field] = Hash::merge($defaults, array(
					'label' => Inflector::humanize($label),
				));
			} elseif (is_array($label)) {
				$out[$field] = Hash::merge($defaults, $label);
				if (!isset($out[$field]['label'])) {
					$out[$field]['label'] = Inflector::humanize($field);
				}
			} else {
				$out[$field] = Hash::merge($defaults, array(
					'label' => $label,
				));
			}
		}
		return $out;
	}

/**
 * Return formatted edit fields
 *
 * @param array $editFields
 * @return array
 */
	public function editFields($editFields = null) {
		if (isset($editFields)) {
			$this->_editFields = $editFields;
		}
		if (empty($this->_editFields)) {
			$this->_editFields = array_keys($this->schema());
			$id = array_search('id', $this->_editFields);
			if ($id !== false) {
				unset($this->_editFields[$id]);
			}
		}
		$out = array();
		foreach ($this->_editFields as $field => $label) {
			if (is_int($field)) {
				$out[$label] = array();
			} elseif (is_array($label)) {
				$out[$field] = $label;
			} else {
				$out[$field] = array(
					'label' => $label,
				);
			}
		}
		return $out;
	}

/**
 * Validation method for alias field
 * @return bool true when validation successful
 * @deprecated Protected validation methods are no longer supported
 */
	protected function _validAlias($check) {
		return $this->validAlias($check);
	}

/**
 * Validation method for name or title fields
 * @return bool true when validation successful
 * @deprecated Protected validation methods are no longer supported
 */
	protected function _validName($check) {
		return $this->validName($check);
	}
	protected function _username($check) {
		return $this->username($check);
	}
	protected function _alphaNumericW($check) {
		return $this->alphaNumericW($check);
	}

/**
 * Validation method for alias field
 *
 * @return bool true when validation successful
 */
	public function validAlias($check) {
		return (preg_match('/^[\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}-_]+$/mu', $check[key($check)]) == 1);
	}

/**
 * Validation method for name or title fields
 *
 * @return bool true when validation successful
 */
	public function validName($check) {
		return (preg_match('/^[\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}-_\[\]\(\) ]+$/mu', $check[key($check)]) == 1);
	}
	//Orly added
	public static function username($check) {
$hostname = '(?:[a-z0-9][-a-z0-9]*\.)*(?:[a-z0-9][-a-z0-9]{0,62})\.(?:(?:[a-z]{2}\.)?[a-z]{2,4}|museum|travel)';
			$regex = '/^[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*@' . $hostname . '$/i';
		$return=(preg_match($regex,$check[key($check)]) == 1);
		//$return = self::_check($check, $regex);
		if ($return) return true;
		return self::cellphone($check);
		//this is not an email
		//return self::_check($check, '/^[\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}]+$/mu');
		
	}
	//Orly added
	public static function alphaNumericW($check) {
		
		return (preg_match('/^[\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}\p{Z}\x{0027}]+$/mu',$check[key($check)]) == 1);
	}
	public static function phone($check, $regex = null, $country = 'all') {
		//pr($regex);
		//$country=__("us");
		if (is_null($regex)) {
			
			switch ($country) {
				case 'us':
				case 'all':
				case 'can':
					// includes all NANPA members.
					// see http://en.wikipedia.org/wiki/North_American_Numbering_Plan#List_of_NANPA_countries_and_territories
					$regex = '/^(?:\+?1)?[-. ]?\\(?[2-9][0-8][0-9]\\)?[-. ]?[2-9][0-9]{2}[-. ]?[0-9]{4}$/';
				break;
				//orly added
				case 'il':
					$regex  ='/^0\d([\d]{0,1})([-]{0,1})\d{7}$/';
										
					break;
			}
		}
		if (empty($regex)) {
		return true;
		}
		return (preg_match($regex, $check[key($check)]) == 1);
		;
	}
	public static function cellphone($check, $regex = null, $country = 'all') {
		
		//$country=__("us");
			//$regex=null;
			switch ($country) {
				case 'us':
				case 'all':
				case 'can':
					// includes all NANPA members.
					// see http://en.wikipedia.org/wiki/North_American_Numbering_Plan#List_of_NANPA_countries_and_territories
					$regex  = '/^(?:\+?1)?[-. ]?\\(?[2-9][0-8][0-9]\\)?[-. ]?[2-9][0-9]{2}[-. ]?[0-9]{4}$/';
					break;
					//orly added
				case 'il':
					$regex  ='/^05\d([-]{0,1})\d{7}$/';
					break;
			
		}
		if (empty($regex)||empty($check[key($check)])) {
			return true;
		}
	
		return (preg_match($regex,$check[key($check)]) == 1);
	}
	//orly added new function to support html5 validation
	public static function getRegex($type,$country){
		$regex=false;
		switch ($country) {
			case 'us':
			case 'all':
			case 'can':
				// includes all NANPA members.
				// see http://en.wikipedia.org/wiki/North_American_Numbering_Plan#List_of_NANPA_countries_and_territories
				switch ($type){
					case 'cellphone':
					$regex  = '(?:\+?1)?[-. ]?\\(?[2-9][0-8][0-9]\\)?[-. ]?[2-9][0-9]{2}[-. ]?[0-9]{4}';
					break;
					case 'phone':
					$regex = '(?:\+?1)?[-. ]?\\(?[2-9][0-8][0-9]\\)?[-. ]?[2-9][0-9]{2}[-. ]?[0-9]{4}';
												break;
				}
				break;
				//orly added
			case 'il':
				switch ($type){
					case 'cellphone':
						$regex  = '05\d([-]{0,1})\d{7}';
						break;
					case 'phone':
					$regex  ='0\d([\d]{0,1})([-]{0,1})\d{7}';
					break;
				}
				break;
		}
		return $regex;
	}
	
	protected static function _defaults($params) {
		//self::_reset();
		$defaults = array(
			'check' => null,
			'regex' => null,
			'country' => null,
			'deep' => false,
			'type' => null
		);
		$params = array_merge($defaults, $params);
		if ($params['country'] !== null) {
			$params['country'] = mb_strtolower($params['country']);
		}
		return $params;
	}
	//protected static function _reset() {
	//	self::$errors = array();
	//}
	protected static function _check($check, $regex) {
		if (is_string($regex) && preg_match($regex, $check)) {
			return true;
		} else {
			return false;
		}
	}

}
