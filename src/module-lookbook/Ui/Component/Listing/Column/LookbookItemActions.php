<?php
/**
 * Copyright © 2018 Eloom. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Eloom\Lookbookpro\Ui\Component\Listing\Column;

class LookbookItemActions extends \Eloom\Lookbookpro\Ui\Component\Listing\Column\AbstractActions {
  /** Url path */
  protected $_editUrl = 'lookbookpro/lookbookitem/edit';
  /**
   * @var string
   */
  protected $_deleteUrl = 'lookbookpro/lookbookitem/delete';
  /**
   * @var string
   */
  protected $_primary = 'entity_id';
}

