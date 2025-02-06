<?php
/**
 * Copyright © 2018 Eloom. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Eloom\Lookbookpro\Block\Adminhtml\LookbookCategory\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class ResetButton
 */
class ResetButton implements ButtonProviderInterface {
  /**
   * @return array
   */
  public function getButtonData() {
    return [
      'label' => __('Reset'),
      'class' => 'reset',
      'on_click' => 'location.reload();',
      'sort_order' => 30
    ];
  }
}

