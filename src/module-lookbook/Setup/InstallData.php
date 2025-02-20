<?php
/**
 * Copyright © 2018 Eloom. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Eloom\Lookbookpro\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface {

  private $setupFactory;

  protected $_lookCategoryFactory;

  protected $_lookCollectionFactory;

  public function __construct(
    \Eloom\Lookbookpro\Setup\LookbookproSetupFactory                          $setupFactory,
    \Eloom\Lookbookpro\Model\ResourceModel\LookbookCategory\CollectionFactory $lookCollectionFactory,
    \Eloom\Lookbookpro\Model\LookbookCategoryFactory                          $lookCategoryFactory
  ) {
    $this->setupFactory = $setupFactory;
    $this->_lookCollectionFactory = $lookCollectionFactory;
    $this->_lookCategoryFactory = $lookCategoryFactory;
  }


  public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context) {
    $setup->startSetup();
    $moduleSetup = $this->setupFactory->create(['setup' => $setup]);
    $moduleSetup->installEntities();
    $setup->endSetup();
  }

}
