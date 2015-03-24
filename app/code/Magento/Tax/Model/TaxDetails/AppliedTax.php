<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Tax\Model\TaxDetails;

use Magento\Framework\Model\AbstractExtensibleModel;
use Magento\Tax\Api\Data\AppliedTaxInterface;

/**
 * @codeCoverageIgnore
 */
class AppliedTax extends AbstractExtensibleModel implements AppliedTaxInterface
{
    /**
     * {@inheritdoc}
     */
    public function getTaxRateKey()
    {
        return $this->getData(AppliedTaxInterface::KEY_TAX_RATE_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function getPercent()
    {
        return $this->getData(AppliedTaxInterface::KEY_PERCENT);
    }

    /**
     * {@inheritdoc}
     */
    public function getAmount()
    {
        return $this->getData(AppliedTaxInterface::KEY_AMOUNT);
    }

    /**
     * {@inheritdoc}
     */
    public function getRates()
    {
        return $this->getData(AppliedTaxInterface::KEY_RATES);
    }

    /**
     * Set tax rate key
     *
     * @param string $taxRateKey
     * @return $this
     */
    public function setTaxRateKey($taxRateKey)
    {
        return $this->setData(AppliedTaxInterface::KEY_TAX_RATE_KEY, $taxRateKey);
    }

    /**
     * Set percent
     *
     * @param float $percent
     * @return $this
     */
    public function setPercent($percent)
    {
        return $this->setData(AppliedTaxInterface::KEY_PERCENT, $percent);
    }

    /**
     * Get amount
     *
     * @param float $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        return $this->setData(AppliedTaxInterface::KEY_AMOUNT, $amount);
    }

    /**
     * Set rates
     *
     * @param \Magento\Tax\Api\Data\AppliedTaxRateInterface[] $rates
     * @return $this
     */
    public function setRates(array $rates = null)
    {
        return $this->setData(AppliedTaxInterface::KEY_RATES, $rates);
    }

    /**
     * {@inheritdoc}
     *
     * @return \Magento\Tax\Api\Data\AppliedTaxExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * {@inheritdoc}
     *
     * @param \Magento\Tax\Api\Data\AppliedTaxExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(\Magento\Tax\Api\Data\AppliedTaxExtensionInterface $extensionAttributes)
    {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
