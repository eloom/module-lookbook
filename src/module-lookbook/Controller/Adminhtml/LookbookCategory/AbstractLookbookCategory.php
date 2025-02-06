<?php
/**
 * Copyright © 2018 Eloom. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Eloom\Lookbookpro\Controller\Adminhtml\LookbookCategory;

class AbstractLookbookCategory extends \Magento\Backend\App\Action {
  protected $primary = 'entity_id';
  protected $modelClass = 'Eloom\Lookbookpro\Model\LookbookCategory';

  public function execute() {
    /* TO DO */
  }

  private function resolveCategoryId() {
    $categoryId = (int)$this->getRequest()->getParam('id', false);

    return $categoryId ?: (int)$this->getRequest()->getParam('entity_id', false);
  }

  protected function _initCategory($getRootInstead = false) {
    $categoryId = $this->resolveCategoryId();
    $storeId = (int)$this->getRequest()->getParam('store');
    $category = $this->_objectManager->create($this->modelClass);
    $category->setStoreId($storeId);

    if ($categoryId) {
      $category->load($categoryId);
    }

    $this->_objectManager->get(\Magento\Framework\Registry::class)->register('category', $category);
    $this->_objectManager->get(\Magento\Framework\Registry::class)->register('current_category', $category);
    $this->_objectManager->get(\Magento\Framework\Registry::class)->register('lookbookpro_eloomlookbook_category', $category);

    $this->_objectManager->get(\Magento\Cms\Model\Wysiwyg\Config::class)
      ->setStoreId($this->getRequest()->getParam('store'));
    return $category;
  }
}

