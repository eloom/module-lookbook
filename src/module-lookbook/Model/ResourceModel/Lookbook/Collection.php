<?php
namespace Eloom\Lookbookpro\Model\ResourceModel\Lookbook;

class Collection extends \Magento\Catalog\Model\ResourceModel\Collection\AbstractCollection {
	protected function _construct() {
		$this->_init('Eloom\Lookbookpro\Model\Lookbook', 'Eloom\Lookbookpro\Model\ResourceModel\Lookbook');
	}

	public function setJoinField($table, $field) {
		$this->_joinFields[$field] = ['table' => $table, 'field' => $field];
	}

	public function addAttributeToSort($attribute, $dir = self::SORT_ORDER_ASC) {
		if ($attribute == 'position') {
			$fromPart = $this->getSelect()->getPart(\Magento\Framework\DB\Select::FROM);
			if (!empty($fromPart['ccl'])) {
				$this->_joinFields['position'] = ['table' => 'ccl', 'field' => 'position'];
				$this->getSelect()->order($this->_getAttributeFieldName($attribute) . ' ' . $dir);
				return $this;
			}
		}
		return parent::addAttributeToSort($attribute, $dir);
	}
}
