<?php
/**
 * Copyright © 2018 Eloom, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Eloom\Lookbookpro\Block\Category;

use Eloom\Lookbookpro\Helper\LookbookList;
use Eloom\Lookbookpro\Model\Toolbar as ToolbarModel;
use Eloom\Lookbookpro\Block\Lookbook\ListLookbook;

class LookbookListToolbar extends \Magento\Framework\View\Element\Template {
  /**
   * Products collection
   *
   * @var \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
   */
  protected $_collection = null;

  /**
   * List of available order fields
   *
   * @var array
   */
  protected $_availableOrder = null;

  /**
   * List of available view types
   *
   * @var array
   */
  protected $_availableMode = [];

  /**
   * Is enable View switcher
   *
   * @var bool
   */
  protected $_enableViewSwitcher = true;

  /**
   * Is Expanded
   *
   * @var bool
   */
  protected $_isExpanded = true;

  /**
   * Default Order field
   *
   * @var string
   */
  protected $_orderField = null;

  /**
   * Default direction
   *
   * @var string
   */
  protected $_direction = LookbookList::DEFAULT_SORT_DIRECTION;

  /**
   * Default View mode
   *
   * @var string
   */
  protected $_viewMode = null;

  /**
   * @var bool $_paramsMemorizeAllowed
   */
  protected $_paramsMemorizeAllowed = true;

  /**
   * @var string
   */
  protected $_template = 'Magento_Catalog::product/list/toolbar.phtml';

  /**
   * Catalog config
   *
   * @var \Magento\Catalog\Model\Config
   */
  protected $_catalogConfig;

  /**
   * Catalog session
   *
   * @var \Magento\Catalog\Model\Session
   */
  protected $_catalogSession;

  /**
   * @var ToolbarModel
   */
  protected $_toolbarModel;

  /**
   * @var LookbookList
   */
  protected $_lookbookListHelper;

  /**
   * @var \Magento\Framework\Url\EncoderInterface
   */
  protected $urlEncoder;

  /**
   * @var \Magento\Framework\Data\Helper\PostHelper
   */
  protected $_postDataHelper;

  /**
   * @param \Magento\Framework\View\Element\Template\Context $context
   * @param \Magento\Catalog\Model\Session $catalogSession
   * @param \Magento\Catalog\Model\Config $catalogConfig
   * @param ToolbarModel $toolbarModel
   * @param \Magento\Framework\Url\EncoderInterface $urlEncoder
   * @param LookbookList $lookbookListHelper
   * @param \Magento\Framework\Data\Helper\PostHelper $postDataHelper
   * @param array $data
   */
  public function __construct(
    \Magento\Framework\View\Element\Template\Context $context,
    \Magento\Catalog\Model\Session                   $catalogSession,
    \Magento\Catalog\Model\Config                    $catalogConfig,
    ToolbarModel                                     $toolbarModel,
    \Magento\Framework\Url\EncoderInterface          $urlEncoder,
    LookbookList                                     $lookbookListHelper,
    \Magento\Framework\Data\Helper\PostHelper        $postDataHelper,
    array                                            $data = []
  ) {
    $this->_catalogSession = $catalogSession;
    $this->_catalogConfig = $catalogConfig;
    $this->_toolbarModel = $toolbarModel;
    $this->urlEncoder = $urlEncoder;
    $this->_lookbookListHelper = $lookbookListHelper;
    $this->_postDataHelper = $postDataHelper;
    parent::__construct($context, $data);
  }

  /**
   * Disable list state params memorizing
   *
   * @return $this
   */
  public function disableParamsMemorizing() {
    $this->_paramsMemorizeAllowed = false;
    return $this;
  }

  /**
   * Memorize parameter value for session
   *
   * @param string $param parameter name
   * @param mixed $value parameter value
   * @return $this
   */
  protected function _memorizeParam($param, $value) {
    if ($this->_paramsMemorizeAllowed && !$this->_catalogSession->getParamsMemorizeDisabled()) {
      $this->_catalogSession->setData($param, $value);
    }
    return $this;
  }

  /**
   * Set collection to pager
   *
   * @param \Magento\Framework\Data\Collection $collection
   * @return $this
   */
  public function setCollection($collection) {
    $this->_collection = $collection;

    $this->_collection->setCurPage($this->getCurrentPage());

    // we need to set pagination only if passed value integer and more that 0
    $limit = (int)$this->getLimit();
    if ($limit) {
      $this->_collection->setPageSize($limit);
    }
    if ($this->getCurrentOrder()) {
      $this->_collection->setOrder($this->getCurrentOrder(), $this->getCurrentDirection());
    }
    return $this;
  }

  /**
   * Return products collection instance
   *
   * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
   */
  public function getCollection() {
    return $this->_collection;
  }

  /**
   * Return current page from request
   *
   * @return int
   */
  public function getCurrentPage() {
    return $this->_toolbarModel->getCurrentPage();
  }

  /**
   * Get grit products sort order field
   *
   * @return string
   */
  public function getCurrentOrder() {
    $order = $this->_getData('_current_grid_order');
    if ($order) {
      return $order;
    }

    $orders = $this->getAvailableOrders();
    $defaultOrder = $this->getOrderField();

    if (!isset($orders[$defaultOrder])) {
      $keys = array_keys($orders);
      $defaultOrder = $keys[0];
    }

    $order = $this->_toolbarModel->getOrder();
    if (!$order || !isset($orders[$order])) {
      $order = $defaultOrder;
    }

    if ($order != $defaultOrder) {
      $this->_memorizeParam('sort_order', $order);
    }

    $this->setData('_current_grid_order', $order);
    return $order;
  }

  /**
   * Retrieve current direction
   *
   * @return string
   */
  public function getCurrentDirection() {
    $dir = $this->_getData('_current_grid_direction');
    if ($dir) {
      return $dir;
    }

    $directions = ['asc', 'desc'];
    $dir = strtolower((string)$this->_toolbarModel->getDirection());
    if (!$dir || !in_array($dir, $directions)) {
      $dir = $this->_direction;
    }

    if ($dir != $this->_direction) {
      $this->_memorizeParam('sort_direction', $dir);
    }

    $this->setData('_current_grid_direction', $dir);
    return $dir;
  }

  /**
   * Set default Order field
   *
   * @param string $field
   * @return $this
   */
  public function setDefaultOrder($field) {
    $this->loadAvailableOrders();
    if (isset($this->_availableOrder[$field])) {
      $this->_orderField = $field;
    }
    return $this;
  }

  /**
   * Set default sort direction
   *
   * @param string $dir
   * @return $this
   */
  public function setDefaultDirection($dir) {
    if (in_array(strtolower($dir), ['asc', 'desc'])) {
      $this->_direction = strtolower($dir);
    }
    return $this;
  }

  /**
   * Retrieve available Order fields list
   *
   * @return array
   */
  public function getAvailableOrders() {
    $this->loadAvailableOrders();
    return $this->_availableOrder;
  }

  /**
   * Set Available order fields list
   *
   * @param array $orders
   * @return $this
   */
  public function setAvailableOrders($orders) {
    $this->_availableOrder = $orders;
    return $this;
  }

  /**
   * Add order to available orders
   *
   * @param string $order
   * @param string $value
   * @return \Magento\Catalog\Block\Product\LookbookList\Toolbar
   */
  public function addOrderToAvailableOrders($order, $value) {
    $this->loadAvailableOrders();
    $this->_availableOrder[$order] = $value;
    return $this;
  }

  /**
   * Remove order from available orders if exists
   *
   * @param string $order
   * @return $this
   */
  public function removeOrderFromAvailableOrders($order) {
    $this->loadAvailableOrders();
    if (isset($this->_availableOrder[$order])) {
      unset($this->_availableOrder[$order]);
    }
    return $this;
  }

  /**
   * Compare defined order field with current order field
   *
   * @param string $order
   * @return bool
   */
  public function isOrderCurrent($order) {
    return $order == $this->getCurrentOrder();
  }

  /**
   * Return current URL with rewrites and additional parameters
   *
   * @param array $params Query parameters
   * @return string
   */
  public function getPagerUrl($params = []) {
    $urlParams = [];
    $urlParams['_current'] = true;
    $urlParams['_escape'] = false;
    $urlParams['_use_rewrite'] = true;
    $urlParams['_query'] = $params;
    return $this->getUrl('*/*/*', $urlParams);
  }

  /**
   * @param array $params
   * @return string
   */
  public function getPagerEncodedUrl($params = []) {
    return $this->urlEncoder->encode($this->getPagerUrl($params));
  }

  /**
   * Retrieve current View mode
   *
   * @return string
   */
  public function getCurrentMode() {
    $mode = $this->_getData('_current_grid_mode');
    if ($mode) {
      return $mode;
    }
    $defaultMode = $this->_lookbookListHelper->getDefaultViewMode($this->getModes());
    $mode = $this->_toolbarModel->getMode();
    if (!$mode || !isset($this->_availableMode[$mode])) {
      $mode = $defaultMode;
    }

    $this->setData('_current_grid_mode', $mode);
    return $mode;
  }

  /**
   * Compare defined view mode with current active mode
   *
   * @param string $mode
   * @return bool
   */
  public function isModeActive($mode) {
    return $this->getCurrentMode() == $mode;
  }

  /**
   * Retrieve available view modes
   *
   * @return array
   */
  public function getModes() {
    // if ($this->_availableMode === []) {
    // $this->_availableMode = $this->_lookbookListHelper->getAvailableViewMode();
    // }
    // return $this->_availableMode;
    return [];
  }

  /**
   * Set available view modes list
   *
   * @param array $modes
   * @return $this
   */
  public function setModes($modes) {
    $this->getModes();
    if (!isset($this->_availableMode)) {
      $this->_availableMode = $modes;
    }
    return $this;
  }

  /**
   * Disable view switcher
   *
   * @return \Magento\Catalog\Block\Product\LookbookList\Toolbar
   */
  public function disableViewSwitcher() {
    $this->_enableViewSwitcher = false;
    return $this;
  }

  /**
   * Enable view switcher
   *
   * @return \Magento\Catalog\Block\Product\LookbookList\Toolbar
   */
  public function enableViewSwitcher() {
    $this->_enableViewSwitcher = true;
    return $this;
  }

  /**
   * Is a enabled view switcher
   *
   * @return bool
   */
  public function isEnabledViewSwitcher() {
    return $this->_enableViewSwitcher;
  }

  /**
   * Disable Expanded
   *
   * @return \Magento\Catalog\Block\Product\LookbookList\Toolbar
   */
  public function disableExpanded() {
    $this->_isExpanded = false;
    return $this;
  }

  /**
   * Enable Expanded
   *
   * @return \Magento\Catalog\Block\Product\LookbookList\Toolbar
   */
  public function enableExpanded() {
    $this->_isExpanded = true;
    return $this;
  }

  /**
   * Check is Expanded
   *
   * @return bool
   */
  public function isExpanded() {
    return $this->_isExpanded;
  }

  /**
   * Retrieve default per page values
   *
   * @return string (comma separated)
   */
  public function getDefaultPerPageValue() {
    if ($this->getCurrentMode() == 'list' && ($default = $this->getDefaultListPerPage())) {
      return $default;
    } elseif ($this->getCurrentMode() == 'grid' && ($default = $this->getDefaultGridPerPage())) {
      return $default;
    }
    return $this->_lookbookListHelper->getDefaultLimitPerPageValue($this->getCurrentMode());
    //return ListLookbook::DEFAULT_ITEMS_PER_PAGE;
  }

  /**
   * Retrieve available limits for current view mode
   *
   * @return array
   */
  public function getAvailableLimit() {
    return $this->_lookbookListHelper->getAvailableLimit($this->getCurrentMode());
    //return ListLookbook::AVAILABLE_LIMIT;
  }

  /**
   * Get specified products limit display per page
   *
   * @return string
   */
  public function getLimit() {
    $limit = $this->_getData('_current_limit');
    if ($limit) {
      return $limit;
    }

    $limits = $this->getAvailableLimit();
    $defaultLimit = $this->getDefaultPerPageValue();
    if (!$defaultLimit || !isset($limits[$defaultLimit])) {
      $keys = array_keys($limits);
      $defaultLimit = $keys[0];
    }

    $limit = $this->_toolbarModel->getLimit();
    if (!$limit || !isset($limits[$limit])) {
      $limit = $defaultLimit;
    }

    if ($limit != $defaultLimit) {
      $this->_memorizeParam('limit_page', $limit);
    }

    $this->setData('_current_limit', $limit);
    return $limit;
  }

  /**
   * @param int $limit
   * @return bool
   */
  public function isLimitCurrent($limit) {
    return $limit == $this->getLimit();
  }

  /**
   * @return int
   */
  public function getFirstNum() {
    $collection = $this->getCollection();
    return $collection->getPageSize() * ($collection->getCurPage() - 1) + 1;
  }

  /**
   * @return int
   */
  public function getLastNum() {
    $collection = $this->getCollection();
    return $collection->getPageSize() * ($collection->getCurPage() - 1) + $collection->count();
  }

  /**
   * @return int
   */
  public function getTotalNum() {
    return $this->getCollection()->getSize();
  }

  /**
   * @return bool
   */
  public function isFirstPage() {
    return $this->getCollection()->getCurPage() == 1;
  }

  /**
   * @return int
   */
  public function getLastPageNum() {
    return $this->getCollection()->getLastPageNumber();
  }

  /**
   * Render pagination HTML
   *
   * @return string
   */
  public function getPagerHtml() {
    $pagerBlock = $this->getChildBlock('lookbook_list_toolbar_pager');

    if ($pagerBlock instanceof \Magento\Framework\DataObject) {
      /* @var $pagerBlock \Magento\Theme\Block\Html\Pager */
      $pagerBlock->setAvailableLimit(ListLookbook::AVAILABLE_LIMIT);
      $pagerBlock->setLimitVarName(ListLookbook::LIMIT_VAR_NAME);

      $pagerBlock->setUseContainer(
        false
      )->setShowPerPage(
        false
      )->setShowAmounts(
        false
      )->setFrameLength(
        $this->_scopeConfig->getValue(
          'design/pagination/pagination_frame',
          \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        )
      )->setJump(
        $this->_scopeConfig->getValue(
          'design/pagination/pagination_frame_skip',
          \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        )
      )->setLimit(
        $this->getLimit()
      )->setCollection(
        $this->getCollection()
      );

      return $pagerBlock->toHtml();
    }

    return '';
  }

  public function getLimitVarName() {
    return ListLookbook::LIMIT_VAR_NAME;
  }

  /**
   * Retrieve widget options in json format
   *
   * @param array $customOptions Optional parameter for passing custom selectors from template
   * @return string
   */
  public function getWidgetOptionsJson(array $customOptions = []) {
    $defaultMode = $this->_lookbookListHelper->getDefaultViewMode($this->getModes());
    $options = [
      'mode' => ToolbarModel::MODE_PARAM_NAME,
      'direction' => ToolbarModel::DIRECTION_PARAM_NAME,
      'order' => ToolbarModel::ORDER_PARAM_NAME,
      'limit' => ListLookbook::LIMIT_VAR_NAME,
      'modeDefault' => $defaultMode,
      'directionDefault' => 'asc',//$this->_direction ?: LookbookList::DEFAULT_SORT_DIRECTION,
      'orderDefault' => 'position', //$this->_lookbookListHelper->getDefaultSortField(),
      'limitDefault' => $this->_lookbookListHelper->getDefaultLimitPerPageValue($defaultMode), //ListLookbook::DEFAULT_ITEMS_PER_PAGE
      'url' => $this->getPagerUrl(),
    ];
    $options = array_replace_recursive($options, $customOptions);
    return json_encode(['productListToolbarForm' => $options]);
  }

  /**
   * Get order field
   *
   * @return null|string
   */
  protected function getOrderField() {
    if ($this->_orderField === null) {
      // $this->_orderField = $this->_lookbookListHelper->getDefaultSortField();
      $this->_orderField = ListLookbook::DEFAULT_ORDER;
    }
    return $this->_orderField;
  }

  /**
   * Load Available Orders
   *
   * @return $this
   */
  private function loadAvailableOrders() {
    if ($this->_availableOrder === null) {
      //$this->_availableOrder = $this->_catalogConfig->getAttributeUsedForSortByArray();
      $this->_availableOrder = [
        'position' => __('Position'),
        'name' => __('Name'),
        'created_at' => __('Date')
      ];
    }
    return $this;
  }
}