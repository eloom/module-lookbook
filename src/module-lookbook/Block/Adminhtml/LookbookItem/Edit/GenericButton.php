<?php
/**
 * Copyright © 2018 Eloom. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Eloom\Lookbook\Block\Adminhtml\LookbookItem\Edit;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;

/**
 * Class GenericButton
 */
class GenericButton {
	/**
	 * @var Context
	 */
	protected $context;

	/**
	 * @var Registry
	 */
	protected $_coreRegistry;

	/**
	 * @param Context $context
	 * @param Registry $coreRegistry
	 */
	public function __construct(
		Context  $context,
		Registry $coreRegistry
	) {
		$this->context = $context;
		$this->_coreRegistry = $coreRegistry;
	}

	/**
	 * Return Lookbook Category ID
	 *
	 * @return int|null
	 */
	public function getEntityId() {
		try {
			if ($this->_coreRegistry->registry('lookbook_lookbook_item')) {
				return $this->_coreRegistry->registry('lookbook_lookbook_item')->getId();
			}
		} catch (NoSuchEntityException $e) {
		}
		return null;
	}

	/**
	 * Generate url by route and parameters
	 *
	 * @param string $route
	 * @param array $params
	 * @return  string
	 */
	public function getUrl($route = '', $params = []) {
		return $this->context->getUrlBuilder()->getUrl($route, $params);
	}
}

