<?php

namespace Eloom\Lookbook\Ui\Component\Listing\Column;

class LookbookActions extends \Eloom\Lookbook\Ui\Component\Listing\Column\AbstractActions {
	/** Url path */
	protected $_editUrl = 'lookbookpro/lookbook/edit';
	/**
	 * @var string
	 */
	protected $_deleteUrl = 'lookbookpro/lookbook/delete';
	/**
	 * @var string
	 */
	protected $_primary = 'entity_id';

	protected $_preview = true;
}

