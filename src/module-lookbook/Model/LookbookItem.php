<?php
/**
 * Copyright © 2018 Eloom. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Eloom\Lookbookpro\Model;

class LookbookItem extends \Eloom\Lookbookpro\Model\AbstractModel {

  const ENTITY = 'eloom_lookbook_item';

  const CACHE_TAG = self::ENTITY;

  const CACHE_LOOKBOOK_ITEM_TAG = 'eloom_lookbook_item_group';

  protected function _construct() {
    $this->_init('Eloom\Lookbookpro\Model\ResourceModel\LookbookItem');
  }
}
