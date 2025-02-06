<?php
/**
 * Copyright © 2018 Eloom. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Eloom\Lookbookpro\Controller\Adminhtml\LookbookCategory;

use Magento\Backend\App\Action;

class Index extends AbstractLookbookCategory {
  /**
   * @var \Magento\Framework\View\Result\PageFactory
   */
  protected $resultPageFactory;

  /**
   * @param \Magento\Backend\App\Action\Context $context
   * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
   */
  public function __construct(
    Action\Context                             $context,
    \Magento\Framework\View\Result\PageFactory $resultPageFactory
  ) {
    parent::__construct($context);
    $this->resultPageFactory = $resultPageFactory;
  }

  protected function _isAllowed() {
    return $this->_authorization->isAllowed('Eloom_Lookbookpro::eloomlookbook_category');
  }

  public function execute() {
    /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
    $resultPage = $this->resultPageFactory->create();
    $resultPage->addBreadcrumb(__('Lookbook Pro'), __('Lookbook Pro'));
    $resultPage->addBreadcrumb(__('Lookbook Category'), __('Lookbook Category'));
    $resultPage->setActiveMenu('Eloom_Lookbookpro::eloomlookbook_category');
    $resultPage->getConfig()->getTitle()->prepend(__('Lookbook Category'));
    return $resultPage;
  }
}

