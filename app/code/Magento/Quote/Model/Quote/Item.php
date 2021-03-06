<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

namespace Magento\Quote\Model\Quote;

use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Api\MetadataServiceInterface;

/**
 * Sales Quote Item Model
 *
 * @method \Magento\Quote\Model\Resource\Quote\Item _getResource()
 * @method \Magento\Quote\Model\Resource\Quote\Item getResource()
 * @method string getCreatedAt()
 * @method \Magento\Quote\Model\Quote\Item setCreatedAt(string $value)
 * @method string getUpdatedAt()
 * @method \Magento\Quote\Model\Quote\Item setUpdatedAt(string $value)
 * @method \Magento\Quote\Model\Quote\Item setProductId(int $value)
 * @method int getStoreId()
 * @method \Magento\Quote\Model\Quote\Item setStoreId(int $value)
 * @method int getParentItemId()
 * @method \Magento\Quote\Model\Quote\Item setParentItemId(int $value)
 * @method int getIsVirtual()
 * @method \Magento\Quote\Model\Quote\Item setIsVirtual(int $value)
 * @method string getDescription()
 * @method \Magento\Quote\Model\Quote\Item setDescription(string $value)
 * @method string getAdditionalData()
 * @method \Magento\Quote\Model\Quote\Item setAdditionalData(string $value)
 * @method int getFreeShipping()
 * @method \Magento\Quote\Model\Quote\Item setFreeShipping(int $value)
 * @method int getIsQtyDecimal()
 * @method \Magento\Quote\Model\Quote\Item setIsQtyDecimal(int $value)
 * @method int getNoDiscount()
 * @method \Magento\Quote\Model\Quote\Item setNoDiscount(int $value)
 * @method float getWeight()
 * @method \Magento\Quote\Model\Quote\Item setWeight(float $value)
 * @method float getBasePrice()
 * @method \Magento\Quote\Model\Quote\Item setBasePrice(float $value)
 * @method float getCustomPrice()
 * @method float getTaxPercent()
 * @method \Magento\Quote\Model\Quote\Item setTaxPercent(float $value)
 * @method \Magento\Quote\Model\Quote\Item setTaxAmount(float $value)
 * @method \Magento\Quote\Model\Quote\Item setBaseTaxAmount(float $value)
 * @method \Magento\Quote\Model\Quote\Item setRowTotal(float $value)
 * @method \Magento\Quote\Model\Quote\Item setBaseRowTotal(float $value)
 * @method float getRowTotalWithDiscount()
 * @method \Magento\Quote\Model\Quote\Item setRowTotalWithDiscount(float $value)
 * @method float getRowWeight()
 * @method \Magento\Quote\Model\Quote\Item setRowWeight(float $value)
 * @method float getBaseTaxBeforeDiscount()
 * @method \Magento\Quote\Model\Quote\Item setBaseTaxBeforeDiscount(float $value)
 * @method float getTaxBeforeDiscount()
 * @method \Magento\Quote\Model\Quote\Item setTaxBeforeDiscount(float $value)
 * @method float getOriginalCustomPrice()
 * @method \Magento\Quote\Model\Quote\Item setOriginalCustomPrice(float $value)
 * @method string getRedirectUrl()
 * @method \Magento\Quote\Model\Quote\Item setRedirectUrl(string $value)
 * @method float getBaseCost()
 * @method \Magento\Quote\Model\Quote\Item setBaseCost(float $value)
 * @method \Magento\Quote\Model\Quote\Item setPriceInclTax(float $value)
 * @method float getBasePriceInclTax()
 * @method \Magento\Quote\Model\Quote\Item setBasePriceInclTax(float $value)
 * @method \Magento\Quote\Model\Quote\Item setRowTotalInclTax(float $value)
 * @method float getBaseRowTotalInclTax()
 * @method \Magento\Quote\Model\Quote\Item setBaseRowTotalInclTax(float $value)
 * @method int getGiftMessageId()
 * @method \Magento\Quote\Model\Quote\Item setGiftMessageId(int $value)
 * @method string getWeeeTaxApplied()
 * @method \Magento\Quote\Model\Quote\Item setWeeeTaxApplied(string $value)
 * @method float getWeeeTaxAppliedAmount()
 * @method \Magento\Quote\Model\Quote\Item setWeeeTaxAppliedAmount(float $value)
 * @method float getWeeeTaxAppliedRowAmount()
 * @method \Magento\Quote\Model\Quote\Item setWeeeTaxAppliedRowAmount(float $value)
 * @method float getBaseWeeeTaxAppliedAmount()
 * @method \Magento\Quote\Model\Quote\Item setBaseWeeeTaxAppliedAmount(float $value)
 * @method float getBaseWeeeTaxAppliedRowAmnt()
 * @method \Magento\Quote\Model\Quote\Item setBaseWeeeTaxAppliedRowAmnt(float $value)
 * @method float getWeeeTaxDisposition()
 * @method \Magento\Quote\Model\Quote\Item setWeeeTaxDisposition(float $value)
 * @method float getWeeeTaxRowDisposition()
 * @method \Magento\Quote\Model\Quote\Item setWeeeTaxRowDisposition(float $value)
 * @method float getBaseWeeeTaxDisposition()
 * @method \Magento\Quote\Model\Quote\Item setBaseWeeeTaxDisposition(float $value)
 * @method float getBaseWeeeTaxRowDisposition()
 * @method \Magento\Quote\Model\Quote\Item setBaseWeeeTaxRowDisposition(float $value)
 * @method float getHiddenTaxAmount()
 * @method \Magento\Quote\Model\Quote\Item setHiddenTaxAmount(float $value)
 * @method float getBaseHiddenTaxAmount()
 * @method \Magento\Quote\Model\Quote\Item setBaseHiddenTaxAmount(float $value)
 * @method null|bool getHasConfigurationUnavailableError()
 * @method \Magento\Quote\Model\Quote\Item setHasConfigurationUnavailableError(bool $value)
 * @method \Magento\Quote\Model\Quote\Item unsHasConfigurationUnavailableError()
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class Item extends \Magento\Quote\Model\Quote\Item\AbstractItem implements \Magento\Quote\Api\Data\CartItemInterface
{
    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'sales_quote_item';

    /**
     * Parameter name in event
     *
     * In observe method you can use $observer->getEvent()->getObject() in this case
     *
     * @var string
     */
    protected $_eventObject = 'item';

    /**
     * Quote model object
     *
     * @var \Magento\Quote\Model\Quote
     */
    protected $_quote;

    /**
     * Item options array
     *
     * @var array
     */
    protected $_options = [];

    /**
     * Item options by code cache
     *
     * @var array
     */
    protected $_optionsByCode = [];

    /**
     * Not Represent options
     *
     * @var array
     */
    protected $_notRepresentOptions = ['info_buyRequest'];

    /**
     * Flag stating that options were successfully saved
     *
     */
    protected $_flagOptionsSaved;

    /**
     * Array of errors associated with this quote item
     *
     * @var \Magento\Sales\Model\Status\ListStatus
     */
    protected $_errorInfos;

    /**
     * @var \Magento\Framework\Locale\FormatInterface
     */
    protected $_localeFormat;

    /**
     * @var \Magento\Quote\Model\Quote\Item\OptionFactory
     */
    protected $_itemOptionFactory;

    /**
     * @var \Magento\Quote\Model\Quote\Item\Compare
     */
    protected $quoteItemCompare;

    /**
     * @var \Magento\CatalogInventory\Api\StockRegistryInterface
     */
    protected $stockRegistry;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param MetadataServiceInterface $metadataService
     * @param AttributeValueFactory $customAttributeFactory
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     * @param \Magento\Sales\Model\Status\ListFactory $statusListFactory
     * @param \Magento\Framework\Locale\FormatInterface $localeFormat
     * @param Item\OptionFactory $itemOptionFactory
     * @param Item\Compare $quoteItemCompare
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
     * @param \Magento\Framework\Model\Resource\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\Db $resourceCollection
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        MetadataServiceInterface $metadataService,
        AttributeValueFactory $customAttributeFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Sales\Model\Status\ListFactory $statusListFactory,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Quote\Model\Quote\Item\OptionFactory $itemOptionFactory,
        \Magento\Quote\Model\Quote\Item\Compare $quoteItemCompare,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\Framework\Model\Resource\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\Db $resourceCollection = null,
        array $data = []
    ) {
        $this->_errorInfos = $statusListFactory->create();
        $this->_localeFormat = $localeFormat;
        $this->_itemOptionFactory = $itemOptionFactory;
        $this->quoteItemCompare = $quoteItemCompare;
        $this->stockRegistry = $stockRegistry;
        parent::__construct(
            $context,
            $registry,
            $metadataService,
            $customAttributeFactory,
            $productRepository,
            $priceCurrency,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magento\Quote\Model\Resource\Quote\Item');
    }

    /**
     * Quote Item Before Save prepare data process
     *
     * @return $this
     */
    public function beforeSave()
    {
        parent::beforeSave();
        $this->setIsVirtual($this->getProduct()->getIsVirtual());
        if ($this->getQuote()) {
            $this->setQuoteId($this->getQuote()->getId());
        }
        return $this;
    }

    /**
     * Retrieve address model
     *
     * @return \Magento\Quote\Model\Quote\Address
     */
    public function getAddress()
    {
        if ($this->getQuote()->getItemsQty() == $this->getQuote()->getVirtualItemsQty()) {
            $address = $this->getQuote()->getBillingAddress();
        } else {
            $address = $this->getQuote()->getShippingAddress();
        }

        return $address;
    }

    /**
     * Declare quote model object
     *
     * @param   \Magento\Quote\Model\Quote $quote
     * @return $this
     */
    public function setQuote(\Magento\Quote\Model\Quote $quote)
    {
        $this->_quote = $quote;
        $this->setQuoteId($quote->getId());
        $this->setStoreId($quote->getStoreId());
        return $this;
    }

    /**
     * Retrieve quote model object
     *
     * @return \Magento\Quote\Model\Quote
     */
    public function getQuote()
    {
        return $this->_quote;
    }

    /**
     * Prepare quantity
     *
     * @param float|int $qty
     * @return int|float
     */
    protected function _prepareQty($qty)
    {
        $qty = $this->_localeFormat->getNumber($qty);
        $qty = $qty > 0 ? $qty : 1;
        return $qty;
    }

    /**
     * Adding quantity to quote item
     *
     * @param float $qty
     * @return $this
     */
    public function addQty($qty)
    {
        $oldQty = $this->getQty();
        $qty = $this->_prepareQty($qty);

        /**
         * We can't modify quantity of existing items which have parent
         * This qty declared just once during add process and is not editable
         */
        if (!$this->getParentItem() || !$this->getId()) {
            $this->setQtyToAdd($qty);
            $this->setQty($oldQty + $qty);
        }
        return $this;
    }

    /**
     * Declare quote item quantity
     *
     * @param float $qty
     * @return $this
     */
    public function setQty($qty)
    {
        $qty = $this->_prepareQty($qty);
        $oldQty = $this->_getData(self::KEY_QTY);
        $this->setData(self::KEY_QTY, $qty);

        $this->_eventManager->dispatch('sales_quote_item_qty_set_after', ['item' => $this]);

        if ($this->getQuote() && $this->getQuote()->getIgnoreOldQty()) {
            return $this;
        }

        if ($this->getUseOldQty()) {
            $this->setData(self::KEY_QTY, $oldQty);
        }

        return $this;
    }

    /**
     * Retrieve option product with Qty
     *
     * Return array
     * 'qty'        => the qty
     * 'product'    => the product model
     *
     * @return array
     */
    public function getQtyOptions()
    {
        $qtyOptions = $this->getData('qty_options');
        if (is_null($qtyOptions)) {
            $productIds = [];
            $qtyOptions = [];
            foreach ($this->getOptions() as $option) {
                /** @var $option \Magento\Quote\Model\Quote\Item\Option */
                if (is_object($option->getProduct())
                    && $option->getProduct()->getId() != $this->getProduct()->getId()
                ) {
                    $productIds[$option->getProduct()->getId()] = $option->getProduct()->getId();
                }
            }

            foreach ($productIds as $productId) {
                $option = $this->getOptionByCode('product_qty_' . $productId);
                if ($option) {
                    $qtyOptions[$productId] = $option;
                }
            }

            $this->setData('qty_options', $qtyOptions);
        }

        return $qtyOptions;
    }

    /**
     * Set option product with Qty
     *
     * @param array $qtyOptions
     * @return $this
     */
    public function setQtyOptions($qtyOptions)
    {
        return $this->setData('qty_options', $qtyOptions);
    }

    /**
     * Setup product for quote item
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return $this
     */
    public function setProduct($product)
    {
        if ($this->getQuote()) {
            $product->setStoreId($this->getQuote()->getStoreId());
            $product->setCustomerGroupId($this->getQuote()->getCustomerGroupId());
        }
        $this->setData('product', $product)
            ->setProductId($product->getId())
            ->setProductType($product->getTypeId())
            ->setSku($this->getProduct()->getSku())
            ->setName($product->getName())
            ->setWeight($this->getProduct()->getWeight())
            ->setTaxClassId($product->getTaxClassId())
            ->setBaseCost($product->getCost());

        $stockItem = $this->stockRegistry->getStockItem($product->getId(), $product->getStore()->getWebsiteId());
        $this->setIsQtyDecimal($stockItem->getIsQtyDecimal());

        $this->_eventManager->dispatch(
            'sales_quote_item_set_product',
            ['product' => $product, 'quote_item' => $this]
        );

        return $this;
    }

    /**
     * Check product representation in item
     *
     * @param   \Magento\Catalog\Model\Product $product
     * @return  bool
     */
    public function representProduct($product)
    {
        $itemProduct = $this->getProduct();
        if (!$product || $itemProduct->getId() != $product->getId()) {
            return false;
        }

        /**
         * Check maybe product is planned to be a child of some quote item - in this case we limit search
         * only within same parent item
         */
        $stickWithinParent = $product->getStickWithinParent();
        if ($stickWithinParent) {
            if ($this->getParentItem() !== $stickWithinParent) {
                return false;
            }
        }

        // Check options
        $itemOptions = $this->getOptionsByCode();
        $productOptions = $product->getCustomOptions();

        if (!$this->compareOptions($itemOptions, $productOptions)) {
            return false;
        }
        if (!$this->compareOptions($productOptions, $itemOptions)) {
            return false;
        }
        return true;
    }

    /**
     * Check if two options array are identical
     * First options array is prerogative
     * Second options array checked against first one
     *
     * @param array $options1
     * @param array $options2
     * @return bool
     */
    public function compareOptions($options1, $options2)
    {
        foreach ($options1 as $option) {
            $code = $option->getCode();
            if (in_array($code, $this->_notRepresentOptions)) {
                continue;
            }
            if (!isset($options2[$code]) || $options2[$code]->getValue() != $option->getValue()) {
                return false;
            }
        }
        return true;
    }

    /**
     * Compare items
     *
     * @param   \Magento\Quote\Model\Quote\Item $item
     * @return  bool
     */
    public function compare($item)
    {
        return $this->quoteItemCompare->compare($this, $item);
    }

    /**
     * Get item product type
     *
     * @return string
     */
    public function getProductType()
    {
        $option = $this->getOptionByCode(self::KEY_PRODUCT_TYPE);
        if ($option) {
            return $option->getValue();
        }
        $product = $this->getProduct();
        if ($product) {
            return $product->getTypeId();
        }
        // $product should always exist or there will be an error in getProduct()
        return $this->_getData(self::KEY_PRODUCT_TYPE);
    }

    /**
     * Return real product type of item
     *
     * @return string
     */
    public function getRealProductType()
    {
        return $this->_getData(self::KEY_PRODUCT_TYPE);
    }

    /**
     * Convert Quote Item to array
     *
     * @param array $arrAttributes
     * @return array
     */
    public function toArray(array $arrAttributes = [])
    {
        $data = parent::toArray($arrAttributes);

        $product = $this->getProduct();
        if ($product) {
            $data['product'] = $product->toArray();
        }
        return $data;
    }

    /**
     * Initialize quote item options
     *
     * @param array $options
     * @return $this
     */
    public function setOptions($options)
    {
        foreach ($options as $option) {
            $this->addOption($option);
        }
        return $this;
    }

    /**
     * Get all item options
     *
     * @return \Magento\Quote\Model\Quote\Item\Option[]
     */
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * Get all item options as array with codes in array key
     *
     * @return array
     */
    public function getOptionsByCode()
    {
        return $this->_optionsByCode;
    }

    /**
     * Add option to item
     *
     * @param \Magento\Quote\Model\Quote\Item\Option|\Magento\Framework\Object $option
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addOption($option)
    {
        if (is_array($option)) {
            $option = $this->_itemOptionFactory->create()->setData($option)->setItem($this);
        } elseif (
            $option instanceof \Magento\Framework\Object &&
            !$option instanceof \Magento\Quote\Model\Quote\Item\Option
        ) {
            $option = $this->_itemOptionFactory->create()->setData(
                $option->getData()
            )->setProduct(
                $option->getProduct()
            )->setItem(
                $this
            );
        } elseif ($option instanceof \Magento\Quote\Model\Quote\Item\Option) {
            $option->setItem($this);
        } else {
            throw new \Magento\Framework\Exception\LocalizedException(__('We found an invalid item option format.'));
        }

        $exOption = $this->getOptionByCode($option->getCode());
        if ($exOption) {
            $exOption->addData($option->getData());
        } else {
            $this->_addOptionCode($option);
            $this->_options[] = $option;
        }
        return $this;
    }

    /**
     * Can specify specific actions for ability to change given quote options values
     * Example: cataloginventory decimal qty validation may change qty to int,
     * so need to change quote item qty option value.
     *
     * @param \Magento\Framework\Object $option
     * @param int|float|null $value
     * @return $this
     */
    public function updateQtyOption(\Magento\Framework\Object $option, $value)
    {
        $optionProduct = $option->getProduct();
        $options = $this->getQtyOptions();

        if (isset($options[$optionProduct->getId()])) {
            $options[$optionProduct->getId()]->setValue($value);
        }

        $this->getProduct()->getTypeInstance()->updateQtyOption(
            $this->getOptions(),
            $option,
            $value,
            $this->getProduct()
        );

        return $this;
    }

    /**
     *Remove option from item options
     *
     * @param string $code
     * @return $this
     */
    public function removeOption($code)
    {
        $option = $this->getOptionByCode($code);
        if ($option) {
            $option->isDeleted(true);
        }
        return $this;
    }

    /**
     * Register option code
     *
     * @param \Magento\Quote\Model\Quote\Item\Option $option
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _addOptionCode($option)
    {
        if (!isset($this->_optionsByCode[$option->getCode()])) {
            $this->_optionsByCode[$option->getCode()] = $option;
        } else {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('An item option with code %1 already exists.', $option->getCode())
            );
        }
        return $this;
    }

    /**
     * Get item option by code
     *
     * @param   string $code
     * @return  \Magento\Quote\Model\Quote\Item\Option || null
     */
    public function getOptionByCode($code)
    {
        if (isset($this->_optionsByCode[$code]) && !$this->_optionsByCode[$code]->isDeleted()) {
            return $this->_optionsByCode[$code];
        }
        return null;
    }

    /**
     * Checks that item model has data changes.
     * Call save item options if model isn't need to save in DB
     *
     * @return boolean
     */
    protected function _hasModelChanged()
    {
        if (!$this->hasDataChanges()) {
            return false;
        }

        return $this->_getResource()->hasDataChanged($this);
    }

    /**
     * Save item options
     *
     * @return $this
     */
    public function saveItemOptions()
    {
        foreach ($this->_options as $index => $option) {
            if ($option->isDeleted()) {
                $option->delete();
                unset($this->_options[$index]);
                unset($this->_optionsByCode[$option->getCode()]);
            } else {
                $option->save();
            }
        }

        $this->_flagOptionsSaved = true;
        // Report to watchers that options were saved

        return $this;
    }

    /**
     * Mar option save requirement
     *
     * @param bool $flag
     * @return void
     */
    public function setIsOptionsSaved($flag)
    {
        $this->_flagOptionsSaved = $flag;
    }

    /**
     * Were options saved
     *
     * @return bool
     */
    public function isOptionsSaved()
    {
        return $this->_flagOptionsSaved;
    }

    /**
     * Save item options after item saved
     *
     * @return \Magento\Quote\Model\Quote\Item
     */
    public function afterSave()
    {
        $this->saveItemOptions();
        return parent::afterSave();
    }

    /**
     * Clone quote item
     *
     * @return $this
     */
    public function __clone()
    {
        parent::__clone();
        $options = $this->getOptions();
        $this->_quote = null;
        $this->_options = [];
        $this->_optionsByCode = [];
        foreach ($options as $option) {
            $this->addOption(clone $option);
        }
        return $this;
    }

    /**
     * Returns formatted buy request - object, holding request received from
     * product view page with keys and options for configured product
     *
     * @return \Magento\Framework\Object
     */
    public function getBuyRequest()
    {
        $option = $this->getOptionByCode('info_buyRequest');
        $buyRequest = new \Magento\Framework\Object($option ? unserialize($option->getValue()) : []);

        // Overwrite standard buy request qty, because item qty could have changed since adding to quote
        $buyRequest->setOriginalQty($buyRequest->getQty())->setQty($this->getQty() * 1);

        return $buyRequest;
    }

    /**
     * Sets flag, whether this quote item has some error associated with it.
     *
     * @param bool $flag
     * @return \Magento\Quote\Model\Quote\Item
     */
    protected function _setHasError($flag)
    {
        return $this->setData('has_error', $flag);
    }

    /**
     * Sets flag, whether this quote item has some error associated with it.
     * When TRUE - also adds 'unknown' error information to list of quote item errors.
     * When FALSE - clears whole list of quote item errors.
     * It's recommended to use addErrorInfo() instead - to be able to remove error statuses later.
     *
     * @param bool $flag
     * @return $this
     * @see addErrorInfo()
     */
    public function setHasError($flag)
    {
        if ($flag) {
            $this->addErrorInfo();
        } else {
            $this->_clearErrorInfo();
        }
        return $this;
    }

    /**
     * Clears list of errors, associated with this quote item.
     * Also automatically removes error-flag from oneself.
     *
     * @return $this
     */
    protected function _clearErrorInfo()
    {
        $this->_errorInfos->clear();
        $this->_setHasError(false);
        return $this;
    }

    /**
     * Adds error information to the quote item.
     * Automatically sets error flag.
     *
     * @param string|null $origin Usually a name of module, that embeds error
     * @param int|null $code Error code, unique for origin, that sets it
     * @param string|null $message Error message
     * @param \Magento\Framework\Object|null $additionalData Any additional data, that caller would like to store
     * @return $this
     */
    public function addErrorInfo($origin = null, $code = null, $message = null, $additionalData = null)
    {
        $this->_errorInfos->addItem($origin, $code, $message, $additionalData);
        if ($message !== null) {
            $this->setMessage($message);
        }
        $this->_setHasError(true);

        return $this;
    }

    /**
     * Retrieves all error infos, associated with this item
     *
     * @return array
     */
    public function getErrorInfos()
    {
        return $this->_errorInfos->getItems();
    }

    /**
     * Removes error infos, that have parameters equal to passed in $params.
     * $params can have following keys (if not set - then any item is good for this key):
     *   'origin', 'code', 'message'
     *
     * @param array $params
     * @return $this
     */
    public function removeErrorInfosByParams($params)
    {
        $removedItems = $this->_errorInfos->removeItemsByParams($params);
        foreach ($removedItems as $item) {
            if ($item['message'] !== null) {
                $this->removeMessageByText($item['message']);
            }
        }

        if (!$this->_errorInfos->getItems()) {
            $this->_setHasError(false);
        }

        return $this;
    }

    /**
     * @codeCoverageIgnoreStart
     *
     * {@inheritdoc}
     */
    public function getItemId()
    {
        return $this->getData(self::KEY_ITEM_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setItemId($itemID)
    {
        return $this->setData(self::KEY_ITEM_ID, $itemID);
    }

    /**
     * {@inheritdoc}
     */
    public function getSku()
    {
        return $this->getData(self::KEY_SKU);
    }

    /**
     * {@inheritdoc}
     */
    public function setSku($sku)
    {
        return $this->setData(self::KEY_SKU, $sku);
    }

    /**
     * {@inheritdoc}
     */
    public function getQty()
    {
        return $this->getData(self::KEY_QTY);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getData(self::KEY_NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        return $this->setData(self::KEY_NAME, $name);
    }

    /**
     * {@inheritdoc}
     */
    public function getPrice()
    {
        return $this->getData(self::KEY_PRICE);
    }

    /**
     * {@inheritdoc}
     */
    public function setPrice($price)
    {
        return $this->setData(self::KEY_PRICE, $price);
    }

    /**
     * {@inheritdoc}
     */
    public function setProductType($productType)
    {
        return $this->setData(self::KEY_PRODUCT_TYPE, $productType);
    }

    /**
     * {@inheritdoc}
     */
    public function getQuoteId()
    {
        return $this->getData(self::KEY_QUOTE_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setQuoteId($quoteId)
    {
        return $this->setData(self::KEY_QUOTE_ID, $quoteId);
    }
    //@codeCoverageIgnoreEnd
}
