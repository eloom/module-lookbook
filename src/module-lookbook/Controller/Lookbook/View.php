<?php
/**
 * Copyright © 2018 Eloom. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Eloom\Lookbook\Controller\Lookbook;

use Eloom\Lookbook\Model\LookbookFactory;
use Magento\Framework\View\Result\PageFactory;


class View extends \Magento\Framework\App\Action\Action {
	protected $coreRegistry;

	protected $storeManager;

	protected $resultPageFactory;

	protected $resultForwardFactory;

	protected $lookbookFactory;

	protected $categoryFactory;

	protected $helper;

	protected $scopeConfig;

	protected $storeId;

	public function __construct(
		\Magento\Framework\App\Action\Context               $context,
		\Magento\Store\Model\StoreManagerInterface          $storeManager,
		\Eloom\Lookbook\Model\LookbookFactory               $lookbookFactory,
		\Magento\Framework\Registry                         $coreRegistry,
		\Eloom\Lookbook\Helper\Data                         $helper,
		\Magento\Framework\Registry                         $registry,
		\Magento\Framework\App\Config\ScopeConfigInterface  $scopeConfig,
		PageFactory                                         $resultPageFactory,
		\Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory
	) {
		parent::__construct($context);
		$this->storeManager = $storeManager;
		$this->coreRegistry = $coreRegistry;
		$this->resultPageFactory = $resultPageFactory;
		$this->resultForwardFactory = $resultForwardFactory;
		$this->lookbookFactory = $lookbookFactory;
		$this->helper = $helper;
		$this->scopeConfig = $scopeConfig;
		$this->storeId = $this->storeManager->getStore()->getId();
	}

	protected function addDefaultInformation($lookbook) {
		$lookbook->addData([
			'thumbnail_url' => $this->helper->getLookbookThumbnailUrl($lookbook, 500, null),
			'url' => $this->helper->getLookbookUrl($lookbook)
		]);
	}

	protected function _initLookbook() {
		$lookbookId = $this->getRequest()->getParam('id', 0);
		if ($lookbookId) {
			$lookbook = $this->lookbookFactory->create();
			$lookbook->setStoreId($this->storeId);
			$lookbook->load($lookbookId);
			if ($lookbook->getId()) {
				$lookbookItems = $this->helper->getItemsByLookbookId($lookbookId, $this->storeId);
				$lookbook->setItemCollection($lookbookItems);
				$this->addDefaultInformation($lookbook);
				$this->coreRegistry->register('current_lookbook', $lookbook);
				return $lookbook;
			}
		}
		return false;
	}

	public function execute() {
		$lookbook = $this->_initLookbook();
		if ($lookbook) {
			$page = $this->resultPageFactory->create();
			$page->getConfig()->addBodyClass('lookbook')->addBodyClass('lookbook-' . $lookbook->getUrlKey());
			return $page;
		}
	}
}