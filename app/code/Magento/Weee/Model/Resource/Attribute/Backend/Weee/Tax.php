<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Weee\Model\Resource\Attribute\Backend\Weee;

/**
 * Catalog product WEEE tax backend attribute model
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Tax extends \Magento\Framework\Model\Resource\Db\AbstractDb
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @param \Magento\Framework\Model\Resource\Db\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param string|null $resourcePrefix
     */
    public function __construct(
        \Magento\Framework\Model\Resource\Db\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        $resourcePrefix = null
    ) {
        $this->_storeManager = $storeManager;
        parent::__construct($context, $resourcePrefix);
    }

    /**
     * Defines main resource table and table identifier field
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('weee_tax', 'value_id');
    }

    /**
     * Load product data
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param \Magento\Eav\Model\Entity\Attribute\AbstractAttribute $attribute
     * @return array
     */
    public function loadProductData($product, $attribute)
    {
        $select = $this->_getReadAdapter()->select()->from(
            $this->getMainTable(),
            ['website_id', 'country', 'state', 'value']
        )->where(
            'entity_id = ?',
            (int)$product->getId()
        )->where(
            'attribute_id = ?',
            (int)$attribute->getId()
        );
        if ($attribute->isScopeGlobal()) {
            $select->where('website_id = ?', 0);
        } else {
            $storeId = $product->getStoreId();
            if ($storeId) {
                $select->where(
                    'website_id IN (?)',
                    [0, $this->_storeManager->getStore($storeId)->getWebsiteId()]
                );
            }
        }
        return $this->_getReadAdapter()->fetchAll($select);
    }

    /**
     * Delete product data
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param \Magento\Eav\Model\Entity\Attribute\AbstractAttribute $attribute
     * @return $this
     */
    public function deleteProductData($product, $attribute)
    {
        $where = ['entity_id = ?' => (int)$product->getId(), 'attribute_id = ?' => (int)$attribute->getId()];

        $adapter = $this->_getWriteAdapter();
        if (!$attribute->isScopeGlobal()) {
            $storeId = $product->getStoreId();
            if ($storeId) {
                $where['website_id IN(?)'] = [0, $this->_storeManager->getStore($storeId)->getWebsiteId()];
            }
        }
        $adapter->delete($this->getMainTable(), $where);
        return $this;
    }

    /**
     * Insert product data
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param array $data
     * @return $this
     */
    public function insertProductData($product, $data)
    {
        $data['entity_id'] = (int)$product->getId();
        $this->_getWriteAdapter()->insert($this->getMainTable(), $data);
        return $this;
    }
}
