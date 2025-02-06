<?php
/**
 * Copyright © 2018 Eloom. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Eloom\Lookbook\Controller\Adminhtml\Lookbook;

use Magento\Backend\App\Action;
use Magento\Store\Model\Store;

class MassEnable extends \Eloom\Lookbook\Controller\Adminhtml\AbstractMassStatus {
	protected $primary = 'entity_id';
	protected $collectionClass = 'Eloom\Lookbook\Model\ResourceModel\Lookbook\Collection';
	protected $modelClass = 'Eloom\Lookbook\Model\Lookbook';
	protected $status = 1;

	protected function _isAllowed() {
		return $this->_authorization->isAllowed('Eloom_Lookbook::lookbook_save');
	}

	protected function setSuccessMessage($count) {
		$this->messageManager->addSuccess(__('A total of %1 record(s) have been enabled.', $count));
		return $this;
	}
}
