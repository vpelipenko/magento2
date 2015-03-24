<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Ui\Component\Filter\Type;

use Magento\Framework\Locale\ResolverInterface;
use Magento\Framework\View\Element\Template\Context as TemplateContext;
use Magento\Framework\View\Element\UiComponent\ConfigBuilderInterface;
use Magento\Framework\View\Element\UiComponent\ConfigFactory;
use Magento\Framework\View\Element\UiComponent\Context;
use Magento\Ui\Component\Filter\FilterAbstract;
use Magento\Ui\Component\Filter\FilterPool;
use Magento\Ui\ContentType\ContentTypeFactory;
use Magento\Ui\DataProvider\Factory as DataProviderFactory;
use Magento\Ui\DataProvider\Manager;

/**
 * Class Date
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Date extends FilterAbstract
{
    /**
     * Timezone library
     *
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $localeDate;

    /**
     * Scope config
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Locale resolver
     *
     * @var \Magento\Framework\Locale\ResolverInterface
     */
    protected $localeResolver;

    /**
     * Constructor
     *
     * @param TemplateContext $context
     * @param Context $renderContext
     * @param ContentTypeFactory $contentTypeFactory
     * @param ConfigFactory $configFactory
     * @param ConfigBuilderInterface $configBuilder
     * @param DataProviderFactory $dataProviderFactory
     * @param Manager $dataProviderManager
     * @param FilterPool $filterPool
     * @param ResolverInterface $localeResolver
     * @param array $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        TemplateContext $context,
        Context $renderContext,
        ContentTypeFactory $contentTypeFactory,
        ConfigFactory $configFactory,
        ConfigBuilderInterface $configBuilder,
        DataProviderFactory $dataProviderFactory,
        Manager $dataProviderManager,
        FilterPool $filterPool,
        ResolverInterface $localeResolver,
        array $data = []
    ) {
        $this->localeDate = $context->getLocaleDate();
        $this->scopeConfig = $context->getScopeConfig();
        $this->localeResolver = $localeResolver;
        parent::__construct(
            $context,
            $renderContext,
            $contentTypeFactory,
            $configFactory,
            $configBuilder,
            $dataProviderFactory,
            $dataProviderManager,
            $filterPool,
            $data
        );
    }

    /**
     * Get condition by data type
     *
     * @param string|array $value
     * @return array|null
     */
    public function getCondition($value)
    {
        return $this->convertValue($value);
    }

    /**
     * Convert value
     *
     * @param array|string $value
     * @return array|null
     */
    protected function convertValue($value)
    {
        if (!empty($value['from']) || !empty($value['to'])) {
            $locale = $this->localeResolver->getLocale();
            if (!empty($value['from'])) {
                $value['orig_from'] = $value['from'];
                $value['from'] = $this->convertDate(strtotime($value['from']), $locale);
            }
            if (!empty($value['to'])) {
                $value['orig_to'] = $value['to'];
                $value['to'] = $this->convertDate(strtotime($value['to']), $locale);
            }
            $value['datetime'] = true;
            $value['locale'] = $this->localeResolver->getLocale();
        } else {
            $value = null;
        }

        return $value;
    }

    /**
     * Convert given date to default (UTC) timezone
     *
     * @param int $date
     * @param string $locale
     * @return \DateTime|null
     */
    protected function convertDate($date, $locale)
    {
        try {
            $dateObj = $this->localeDate->date(new \DateTime($date), $locale, false);
            $dateObj->setTime(0, 0, 0);
            //convert store date to default date in UTC timezone without DST
            $dateObj->setTimezone(new \DateTimeZone('UTC'));
            return $dateObj;
        } catch (\Exception $e) {
            return null;
        }
    }
}
