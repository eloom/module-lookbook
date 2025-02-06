<?php

namespace Eloom\Lookbook\Model\ResourceModel\LookbookItem;


class Collection extends \Magento\Catalog\Model\ResourceModel\Collection\AbstractCollection {
	protected function _construct() {
		$this->_init('Eloom\Lookbook\Model\LookbookItem', 'Eloom\Lookbook\Model\ResourceModel\LookbookItem');
	}
}
