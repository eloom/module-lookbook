<?php
/**
 * Copyright © 2018 Eloom. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Eloom\Lookbookpro\Model\ResourceModel\LookbookItem;


class Collection extends \Magento\Catalog\Model\ResourceModel\Collection\AbstractCollection {
	protected function _construct() {
		$this->_init('Eloom\Lookbookpro\Model\LookbookItem', 'Eloom\Lookbookpro\Model\ResourceModel\LookbookItem');
	}
}
