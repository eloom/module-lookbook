<?php
/**
 * Copyright © 2018 Eloom, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Eloom\Lookbookpro\Block\Lookbook;

use Magento\Framework\View\Element\Template;
use Eloom\Lookbookpro\Model\Lookbook;

class AbstractLookbook extends \Magento\Framework\View\Element\Template {
  protected $_context;
  protected $_httpContext;
  protected $_mediaUrl;
  protected $_objectManager;
  protected $_imageHelper;
  protected $_coreRegistry;
  protected $_copeConfig;
  protected $_helper;
  protected $_cacheTag = Lookbook::CACHE_TAG;

  public function __construct(
    Template\Context                    $context,
    \Magento\Framework\App\Http\Context $httpContext,
    \Magento\Framework\Registry         $coreRegistry,
    \Eloom\Lookbookpro\Helper\Data    $helper,
    array                               $data = []
  ) {
    parent::__construct($context, $data);
    $this->_httpContext = $httpContext;
    $this->_context = $context;
    $this->_storeManager = $context->getStoreManager();
    $this->_helper = $helper;
    $this->_coreRegistry = $coreRegistry;
    $this->_copeConfig = $context->getScopeConfig();
    $this->_objectManager = $helper->getObjectManager();
  }
}