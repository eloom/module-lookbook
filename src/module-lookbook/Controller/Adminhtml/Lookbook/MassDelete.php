<?php
/**
 * Copyright © 2018 Eloom. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Eloom\Lookbookpro\Controller\Adminhtml\Lookbook;

use Magento\Backend\App\Action;
use \Magento\Store\Model\Store;

class MassDelete extends AbstractLookbook {

  protected function _isAllowed() {
    return $this->_authorization->isAllowed('Eloom_Lookbookpro::eloomlookbook_save');
  }

  public function execute() {
    $selected = $this->getRequest()->getParam('selected');
    $excluded = $this->getRequest()->getParam('excluded');

    try {
      if ($selected) {
        $collection = $this->_objectManager->create($this->modelClass)->getCollection();
        $collection->addFieldToFilter($this->primary, ['in' => $selected]);
        $count = 0;
        if ($collection->count()) {
          foreach ($collection->getItems() as $model) {
            $model->delete();
            $count++;
          }
        }
      }
      $this->setSuccessMessage($count);
    } catch (\Exception $e) {
      $this->messageManager->addError($e->getMessage());
    }
    $resultRedirect = $this->resultRedirectFactory->create();
    return $resultRedirect->setPath('*/*/');
  }

  protected function setSuccessMessage($count) {
    $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $count));
  }
}