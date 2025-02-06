<?php
/**
 * Copyright © 2018 Eloom. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Eloom\Lookbook\Controller\Adminhtml\Lookbook;

use Magento\Backend\App\Action;
use Magento\Store\Model\Store;

class ClearCache extends AbstractLookbook {

	protected function _isAllowed() {
		return $this->_authorization->isAllowed('Eloom_Lookbook::lookbook_save');
	}

	public function execute() {
		$imageHelper = $this->_objectManager->create('Eloom\Lookbook\Helper\Image');
		$resultRedirect = $this->resultRedirectFactory->create();

		try {
			$imageHelper->clearCache();
			$this->messageManager->addSuccess(__('Lookbook image cache was cleared.'));
		} catch (\Magento\Framework\Exception\LocalizedException $e) {
			$this->messageManager->addError($e->getMessage());
		} catch (\RuntimeException $e) {
			$this->messageManager->addError($e->getMessage());
		} catch (\Exception $e) {
			$this->messageManager->addError($e->getMessage());
		}

		return $resultRedirect->setPath('*/*/');
	}

}