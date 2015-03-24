<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Tax\Test\Unit\Model\TaxClass\Type;

class CustomerTest extends \PHPUnit_Framework_TestCase
{
    public function testIsAssignedToObjects()
    {
        $objectManagerHelper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $searchResultsMock  = $this->getMockBuilder('Magento\Framework\Api\SearchResults')
            ->setMethods(['getItems'])
            ->disableOriginalConstructor()
            ->getMock();
        $searchResultsMock->expects($this->once())
            ->method('getItems')
            ->will($this->returnValue(['randomValue']));

        $filterBuilder = $objectManagerHelper
            ->getObject('Magento\Framework\Api\FilterBuilder');
        $filterGroupBuilder = $objectManagerHelper
            ->getObject('Magento\Framework\Api\Search\FilterGroupBuilder');
        $searchCriteriaBuilder = $objectManagerHelper->getObject(
            'Magento\Framework\Api\SearchCriteriaBuilder',
            [
                'filterGroupBuilder' => $filterGroupBuilder
            ]
        );
        $expectedSearchCriteria = $searchCriteriaBuilder
            ->addFilter([$filterBuilder->setField('tax_class_id')->setValue(5)->create()])
            ->create();

        $customerGroupServiceMock = $this->getMockBuilder('Magento\Customer\Api\GroupRepositoryInterface')
            ->setMethods(['getList'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $customerGroupServiceMock->expects($this->once())
            ->method('getList')
            ->with($expectedSearchCriteria)
            ->willReturn($searchResultsMock);

        /** @var $model \Magento\Tax\Model\TaxClass\Type\Customer */
        $model = $objectManagerHelper->getObject(
            'Magento\Tax\Model\TaxClass\Type\Customer',
            [
                'customerGroupRepository' => $customerGroupServiceMock,
                'searchCriteriaBuilder' => $searchCriteriaBuilder,
                'filterBuilder' => $filterBuilder,
                'data' => ['id' => 5]
            ]
        );

        $this->assertTrue($model->isAssignedToObjects());
    }
}
