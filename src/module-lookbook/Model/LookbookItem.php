<?php

namespace Eloom\Lookbook\Model;

class LookbookItem extends \Eloom\Lookbook\Model\AbstractModel {

	const ENTITY = 'lookbook_item';

	const CACHE_TAG = self::ENTITY;

	const CACHE_LOOKBOOK_ITEM_TAG = 'lookbook_item_group';

	protected function _construct() {
		$this->_init('Eloom\Lookbook\Model\ResourceModel\LookbookItem');
	}
}
