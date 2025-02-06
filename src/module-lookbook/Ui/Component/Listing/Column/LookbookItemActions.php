<?php

namespace Eloom\Lookbook\Ui\Component\Listing\Column;

class LookbookItemActions extends \Eloom\Lookbook\Ui\Component\Listing\Column\AbstractActions {
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

