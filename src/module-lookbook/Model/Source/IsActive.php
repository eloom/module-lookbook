<?php
/**
 * Copyright © 2018 Eloom. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Eloom\Lookbookpro\Model\Source;

class IsActive implements \Magento\Framework\Data\OptionSourceInterface {
  protected $model;

  public function __construct(\Eloom\Lookbookpro\Model\AbstractModel $model) {
    $this->model = $model;
  }

  public function toOptionArray() {
    $options[] = ['label' => '', 'value' => ''];
    $availableOptions = $this->model->getAvailableStatuses();
    foreach ($availableOptions as $key => $value) {
      $options[] = [
        'label' => $value,
        'value' => $key,
      ];
    }
    return $options;
  }
}
