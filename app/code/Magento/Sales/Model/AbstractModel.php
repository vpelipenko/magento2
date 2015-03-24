<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Sales\Model;

use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Model\AbstractExtensibleModel;

/**
 * Sales abstract model
 * Provide date processing functionality
 */
abstract class AbstractModel extends AbstractExtensibleModel
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $_localeDate;

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    protected $dateTime;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory
     * @param AttributeValueFactory $customAttributeFactory
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
     * @param \Magento\Framework\Stdlib\DateTime $dateTime
     * @param \Magento\Framework\Model\Resource\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\Db $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        \Magento\Framework\Model\Resource\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\Db $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $resource,
            $resourceCollection,
            $data
        );
        $this->_localeDate = $localeDate;
        $this->dateTime = $dateTime;
    }

    /**
     * Get object store identifier
     *
     * @return int | string | \Magento\Store\Model\Store
     */
    abstract public function getStore();

    /**
     * Get object created at date affected current active store timezone
     *
     * @return \DateTime
     */
    public function getCreatedAtDate()
    {
        return $this->_localeDate->date(new \DateTime($this->getCreatedAt()));
    }

    /**
     * Get object created at date affected with object store timezone
     *
     * @return \DateTime
     */
    public function getCreatedAtStoreDate()
    {
        return $this->_localeDate->scopeDate(
            $this->getStore(),
            $this->getCreatedAt(),
            true
        );
    }

    /**
     * Returns _eventPrefix
     *
     * @return string
     */
    public function getEventPrefix()
    {
        return $this->_eventPrefix;
    }

    /**
     * Returns _eventObject
     *
     * @return string
     */
    public function getEventObject()
    {
        return $this->_eventObject;
    }
}
