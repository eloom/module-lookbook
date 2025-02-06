<?php
/**
 * Copyright © 2018 Eloom. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Eloom\Lookbookpro\Ui\Component\Listing\Column;

class LookbookCategoryActions extends \Eloom\Lookbookpro\Ui\Component\Listing\Column\AbstractActions {
  /** Url path */
  protected $_editUrl = 'lookbookpro/lookbookcategory/edit';
  /**
   * @var string
   */
  protected $_deleteUrl = 'lookbookpro/lookbookcategory/delete';
  /**
   * @var string
   */
  protected $_primary = 'entity_id';
}

