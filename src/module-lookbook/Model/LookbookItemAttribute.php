<?php
/**
 * Copyright © 2018 Eloom. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Eloom\Lookbookpro\Model;

use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Stdlib\DateTime\DateTimeFormatterInterface;

class LookbookItemAttribute extends \Magento\Eav\Model\Entity\Attribute implements \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface {
  const MODULE_NAME = 'Eloom_Lookbookpro';

  const KEY_IS_GLOBAL = 'is_global';

  protected $_eventObject = 'attribute';

  protected $_eventPrefix = 'eloomlookbook_item_entity_attribute';

  protected function _construct() {
    $this->_init('Eloom\Lookbookpro\Model\ResourceModel\LookbookItemAttribute');
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
