<?php
/**
 * Copyright © 2018 Eloom. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Eloom\Lookbookpro\Controller\Adminhtml\Lookbook;

use Magento\Backend\App\Action;
use Magento\Store\Model\Store;

class MassDisable extends \Eloom\Lookbookpro\Controller\Adminhtml\AbstractMassStatus {
  protected $primary = 'entity_id';
  protected $collectionClass = 'Eloom\Lookbookpro\Model\ResourceModel\Lookbook\Collection';
  protected $modelClass = 'Eloom\Lookbookpro\Model\Lookbook';
  protected $status = 0;

  protected function _isAllowed() {
    return $this->_authorization->isAllowed('Eloom_Lookbookpro::eloomlookbook_save');
  }

  protected function setSuccessMessage($count) {
    $this->messageManager->addSuccess(__('A total of %1 record(s) have been disabled.', $count));
    return $this;
  }
}
