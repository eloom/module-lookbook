<?php
/**
 * Copyright © 2018 Eloom. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Eloom\Lookbookpro\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class LookbookItemThumbnail extends \Magento\Ui\Component\Listing\Columns\Column {
  protected $_editUrl = 'lookbookpro/lookbookitem/edit';

  protected $_primary = 'entity_id';

  protected $_imageHelper;

  protected $_basePath = 'eloom/lookbook/item_element';

  protected $_collectionClass = 'Eloom\Lookbookpro\Model\ResourceModel\LookbookItem\Collection';

  public function __construct(
    ContextInterface                $context,
    UiComponentFactory              $uiComponentFactory,
    \Magento\Framework\UrlInterface $urlBuilder,
    array                           $components = [],
    array                           $data = []
  ) {
    parent::__construct($context, $uiComponentFactory, $components, $data);
    $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $this->_imageHelper = $this->_objectManager->get('Eloom\Lookbookpro\Helper\Image');
    $this->_urlBuilder = $urlBuilder;
  }

  public function prepareDataSource(array $dataSource) {
    if (isset($dataSource['data']['items'])) {
      $objectManager = $this->_objectManager;
      $mediaUrl = $objectManager->get('\Magento\Store\Model\StoreManagerInterface')->getStore()
        ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

      $repository = $objectManager->get('Magento\Framework\View\Asset\Repository');

      $fieldName = $this->getData('name');

      foreach ($dataSource['data']['items'] as & $item) {
        if (isset($item['item_object'])) {
          $model = $item['item_object'];
        } else {
          $model = $objectManager->create($this->_collectionClass)
            ->setStoreId(0)
            ->addFieldToFilter($this->_primary, $item[$this->_primary])
            ->addAttributeToSelect(['name', 'item_data'])
            ->getFirstItem();
          $item['item_object'] = $model;
        }
        $data = json_decode($model->getData('item_data'), true);

        if (!empty($data['image'])) {
          $imagePath = $this->_basePath . $data['image'];
          $thumbnail = $this->_imageHelper->init($imagePath)->resize(75, 75)->__toString();
          $original = $mediaUrl . $imagePath;
        } else {
          $thumbnail = $this->_imageHelper->init('eloom/lookbook/placeholder_thumbnail.jpg')->resize(75, 75)->__toString();
          $original = $this->_imageHelper->init('eloom/lookbook/placeholder_thumbnail.jpg')->resize(500, 500)->__toString();
        }

        $item[$fieldName . '_src'] = $thumbnail;
        $item[$fieldName . '_alt'] = $model->getName();
        $item[$fieldName . '_link'] = $this->_urlBuilder->getUrl($this->_editUrl, [$this->_primary => $item[$this->_primary]]);
        $item[$fieldName . '_orig_src'] = $original;
      }
    }
    return $dataSource;
  }

}