<?php
/**
 * Copyright © 2018 Eloom. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Eloom\Lookbook\Controller\Adminhtml\LookbookCategory;

use Magento\Backend\App\Action;

class Grid extends AbstractLookbookCategory {
	/**
	 * Core registry
	 *
	 * @var \Magento\Framework\Registry
	 */
	protected $_coreRegistry = null;
	/**
	 * @var \Magento\Framework\View\Result\PageFactory
	 */
	protected $resultPageFactory;

	public function __construct(
		Action\Context                                  $context,
		\Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
		\Magento\Framework\View\LayoutFactory           $layoutFactory,
		\Magento\Framework\Registry                     $registry
	) {
		$this->resultRawFactory = $resultRawFactory;
		$this->layoutFactory = $layoutFactory;
		$this->_coreRegistry = $registry;
		parent::__construct($context);
	}

	public function execute() {
		$id = $this->getRequest()->getParam($this->primary);
		$model = $this->_objectManager->create($this->modelClass);

		$storeId = $this->getRequest()->getParam('store', \Magento\Store\Model\Store::DEFAULT_STORE_ID);
		$model->setData('store_id', $storeId);

		if ($id) {
			$model->load($id);
		}
		$this->_coreRegistry->register('lookbook_lookbook_category', $model);
		if (!$model) {

		}
		$resultRaw = $this->resultRawFactory->create();
		return $resultRaw->setContents(
			$this->layoutFactory->create()->createBlock(
				\Eloom\Lookbook\Block\Adminhtml\LookbookCategory\LookbookGrid::class,
				'category.product.grid'
			)->toHtml()
		);
	}
}
