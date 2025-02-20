<?php
/**
 * Copyright © 2018 Eloom, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Eloom\Lookbookpro\Block\Lookbook;

use Eloom\Lookbookpro\Model\Lookbook;
use Eloom\Lookbookpro\Model\ResourceModel\Lookbook\Collection;
use Eloom\Lookbookpro\Block\Category\LookbookListToolbar as Toolbar;
use Eloom\Lookbookpro\Model\Toolbar as ToolbarModel;

class ListLookbook extends \Eloom\Lookbookpro\Block\Lookbook\AbstractLookbook {
  protected $_lookbookCollection;

  protected $_defaultToolbarBlock = Toolbar::class;

  protected $_currentCategory;

  const DEFAULT_ORDER = 'position';
  const LIMIT_VAR_NAME = ToolbarModel::LIMIT_PARAM_NAME;
  const PAGE_VAR_NAME = 'p';
  const DEFAULT_ITEMS_PER_PAGE = 15;
  const AVAILABLE_LIMIT = [
    15 => 15,
    30 => 30,
    60 => 60
  ];

  public function getLookbookLoadedCollection() {
    if (!$this->_lookbookCollection) {
      $lookbookCategory = $this->_coreRegistry->registry('lookbook_category');

      $this->_lookbookCollection = $lookbookCategory->getLookbookCollection()
        ->setPageSize($this->getLookbooksPerPage())
        ->setCurPage($this->getRequest()->getParam(self::PAGE_VAR_NAME, 1));
    }
    return $this->_lookbookCollection;
  }

  public function getCurrentCategory() {
    if ($this->_currentCategory === null) {
      $this->_currentCategory = $this->_coreRegistry->registry('lookbook_category');
    }
    return $this->_currentCategory;
  }

  public function getDefaultLookbooksPerPage() {
    if (!$this->hasData('default_items_per_page')) {
      $this->setData('default_items_per_page',
        $this->_objectManager->get(\Eloom\Lookbookpro\Helper\LookbookList::class)
          ->getDefaultLimitPerPageValue('grid') //todo
      );
    }
    return $this->getData('default_items_per_page');
  }

  public function getLookbooksPerPage() {
    return $this->getRequest()->getParam(self::LIMIT_VAR_NAME, $this->getDefaultLookbooksPerPage()); //self::DEFAULT_ITEMS_PER_PAGE
  }

  protected function _beforeToHtml() {
    $collection = $this->getLookbookLoadedCollection();

    $category = $this->getCurrentCategory();
    // if ($category->getId() == $this->_helper->getStoreRootCategoryId()) {
    // $this->setSortBy('created_at');
    // $this->setDefaultDirection('desc');
    // }

    $this->configureToolbar($this->getToolbarBlock(), $collection);
    $collection->load();

    return parent::_beforeToHtml();
  }

  public function getToolbarHtml() {
    return $this->getChildHtml('lookbook_list_toolbar');
  }

  public function getToolbarBlock() {
    $blockName = $this->getToolbarBlockName();
    if ($blockName) {
      $block = $this->getLayout()->getBlock($blockName);
      if ($block) {
        return $block;
      }
    }
    $block = $this->getLayout()->createBlock($this->_defaultToolbarBlock, uniqid(microtime()));
    return $block;
  }

  private function configureToolbar(Toolbar $toolbar, Collection $collection) {
    // use sortable parameters


    $orders = $this->getAvailableOrders();
    if ($orders) {
      $toolbar->setAvailableOrders($orders);
    }
    $sort = $this->getSortBy();
    if ($sort) {
      $toolbar->setDefaultOrder($sort);
    }
    $dir = $this->getDefaultDirection();
    if ($dir) {
      $toolbar->setDefaultDirection($dir);
    }
    $modes = $this->getModes();
    if ($modes) {
      $toolbar->setModes($modes);
    }

    $toolbar->setCollection($collection);
    $this->setChild('lookbook_list_toolbar', $toolbar);
  }

  public function getCacheKeyInfo() {
    return [
      $this->_cacheTag,
      $this->getRequest()->getParam('id', 0),
      $this->_storeManager->getStore()->getId(),
      $this->getRequest()->getParam(self::PAGE_VAR_NAME, 1),
      $this->getRequest()->getParam(self::LIMIT_VAR_NAME, self::DEFAULT_ITEMS_PER_PAGE),
      $this->getRequest()->getParam('list_order'),
      $this->getRequest()->getParam('list_dir'),
      $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_GROUP)
    ];
  }

  public function getIdentities() {
    $identities = [];
    $category = $this->getCurrentCategory();
    if ($category) {
      $categoryId = $category->getId();
    } else {
      $categoryId = 0;
    }
    $identities[] = [Lookbook::CACHE_LOOKBOOK_CATEGORY_TAG . '_' . $category->getId()];
    return $identities;
  }
}