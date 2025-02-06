<?php
/**
 * Copyright © 2018 Eloom. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Eloom\Lookbookpro\Controller\Index;

use Magento\Framework\View\Result\PageFactory;
use Eloom\Lookbookpro\Model\LookbookFactory;


class Export extends \Magento\Framework\App\Action\Action {
  protected $storeManager;

  protected $lookbookData;

  protected $helper;

  protected $storeId;


  public function __construct(
    \Magento\Framework\App\Action\Context      $context,
    \Magento\Store\Model\StoreManagerInterface $storeManager,
    \Eloom\Lookbookpro\Model\LookbookData    $lookbookData,
    \Eloom\Lookbookpro\Helper\Data           $helper
  ) {
    parent::__construct($context);
    $this->storeManager = $storeManager;
    $this->lookbookData = $lookbookData;
    $this->helper = $helper;
    $this->storeId = $this->storeManager->getStore()->getId();
  }

  public function execute() {
    if (!$this->getRequest()->getParam('import')) {
      $result = $this->lookbookData->exportLookbooksToCSV();
      echo '<p><strong>Export Lookbooks</strong></p>';
      echo $result['message'];

      $result = $this->lookbookData->exportLookbookItemsToCSV();
      echo '<p><strong>Export Items</strong></p>';
      echo $result['message'];

      $result = $this->lookbookData->exportLookbookCatagoriesToCSV();
      echo '<p><strong>Export Categories</strong></p>';
      echo $result['message'];
      die();
    } else {
      $this->lookbookData->importLookbooks();
      $this->lookbookData->importLookbookItems();
      $this->lookbookData->importLookbookCategories();
      $this->lookbookData->assignLookbookToCategory();
      $this->lookbookData->assignItemToLookbook();
    }
  }

}