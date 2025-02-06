<?php

namespace Eloom\Lookbook\Model;

use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Stdlib\DateTime\DateTimeFormatterInterface;

class LookbookItemAttribute extends \Magento\Eav\Model\Entity\Attribute implements \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface {
	const MODULE_NAME = 'Eloom_Lookbook';

	const KEY_IS_GLOBAL = 'is_global';

	protected $_eventObject = 'attribute';

	protected $_eventPrefix = 'lookbook_item_entity_attribute';

	protected function _construct() {
		$this->_init('Eloom\Lookbook\Model\ResourceModel\LookbookItemAttribute');
	}

	public function isScopeStore() {
		return !$this->isScopeGlobal() && !$this->isScopeWebsite();
	}

	public function isScopeGlobal() {
		return $this->getIsGlobal() == self::SCOPE_GLOBAL;
	}

	public function isScopeWebsite() {
		return $this->getIsGlobal() == self::SCOPE_WEBSITE;
	}

	public function __sleep() {
		$this->unsetData('entity_type');
		return parent::__sleep();
	}

}
