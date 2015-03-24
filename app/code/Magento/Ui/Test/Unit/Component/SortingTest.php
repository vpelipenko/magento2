<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Ui\Test\Unit\Component;

use \Magento\Ui\Component\Sorting;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context as TemplateContext;
use Magento\Framework\View\Element\UiComponent\ConfigBuilderInterface;
use Magento\Framework\View\Element\UiComponent\ConfigFactory;
use Magento\Framework\View\Element\UiComponent\Context;
use Magento\Ui\ContentType\ContentTypeFactory;

class SortingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TemplateContext||\PHPUnit_Framework_MockObject_MockObject
     */
    protected $contextMock;

    /**
     * @var Context|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $renderContextMock;

    /**
     * @var ContentTypeFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $contentTypeFactoryMock;

    /**
     * @var ConfigFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $configFactoryMock;

    /**
     * @var ConfigBuilderInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $configBuilderMock;

    /**
     * @var \Magento\Ui\DataProvider\Factory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $dataProviderFactoryMock;

    /**
     * @var \Magento\Ui\DataProvider\Manager|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $dataProviderManagerMock;

    /**
     * @var Sorting
     */
    protected $view;

    /**
     * Set up
     *
     * @return void
     */
    protected function setUp()
    {
        $this->contextMock = $this->getMock(
            'Magento\Framework\View\Element\Template\Context',
            [],
            [],
            '',
            false
        );
        $this->renderContextMock = $this->getMock(
            'Magento\Framework\View\Element\UiComponent\Context',
            ['getNamespace', 'getStorage', 'getRequestParam'],
            [],
            '',
            false
        );
        $this->contentTypeFactoryMock = $this->getMock(
            'Magento\Ui\ContentType\ContentTypeFactory',
            [],
            [],
            '',
            false
        );
        $this->configFactoryMock = $this->getMock(
            'Magento\Framework\View\Element\UiComponent\ConfigFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->configBuilderMock = $this->getMockForAbstractClass(
            'Magento\Framework\View\Element\UiComponent\ConfigBuilderInterface',
            [],
            '',
            false
        );
        $this->dataProviderFactoryMock = $this->getMock(
            'Magento\Ui\DataProvider\Factory',
            [],
            [],
            '',
            false
        );
        $this->dataProviderManagerMock = $this->getMock(
            'Magento\Ui\DataProvider\Manager',
            [],
            [],
            '',
            false
        );

        $this->view = new Sorting(
            $this->contextMock,
            $this->renderContextMock,
            $this->contentTypeFactoryMock,
            $this->configFactoryMock,
            $this->configBuilderMock,
            $this->dataProviderFactoryMock,
            $this->dataProviderManagerMock
        );
    }

    /**
     * Run test prepare method
     *
     * @return void
     */
    public function testPrepare()
    {
        /**
         * @var \Magento\Framework\View\Element\UiComponent\ConfigInterface
         * |\PHPUnit_Framework_MockObject_MockObject $configurationMock
         */
        $configurationMock = $this->getMockForAbstractClass(
            'Magento\Framework\View\Element\UiComponent\ConfigInterface',
            [],
            '',
            false
        );
        /**
         * @var \Magento\Framework\View\Element\UiComponent\ConfigStorageInterface
         * |\PHPUnit_Framework_MockObject_MockObject $configStorageMock
         */
        $configStorageMock = $this->getMockForAbstractClass(
            'Magento\Framework\View\Element\UiComponent\ConfigStorageInterface',
            ['addComponentsData', 'getDataCollection'],
            '',
            false
        );

        $dataCollectionMock = $this->getMockForAbstractClass(
            'Magento\Framework\Api\CriteriaInterface',
            ['addOrder'],
            '',
            false
        );

        $this->renderContextMock->expects($this->at(0))
            ->method('getNamespace')
            ->will($this->returnValue('namespace'));
        $this->renderContextMock->expects($this->at(1))
            ->method('getNamespace')
            ->will($this->returnValue('namespace'));
        $this->configFactoryMock->expects($this->any())
            ->method('create')
            ->will($this->returnValue($configurationMock));

        $this->renderContextMock->expects($this->any())
            ->method('getStorage')
            ->will($this->returnValue($configStorageMock));

        $configStorageMock->expects($this->once())
            ->method('addComponentsData')
            ->with($configurationMock);

        $configurationMock->expects($this->at(0))
            ->method('getData')
            ->with('field')
            ->will($this->returnValue('field'));

        $configurationMock->expects($this->at(1))
            ->method('getData')
            ->with('direction')
            ->will($this->returnValue('direction'));

        $this->renderContextMock->expects($this->any())
            ->method('getStorage')
            ->will($this->returnValue($configStorageMock));

        $configStorageMock->expects($this->once())
            ->method('getDataCollection')
            ->will($this->returnValue($dataCollectionMock));

        $dataCollectionMock->expects($this->once())
            ->method('addOrder')
            ->with('field', 'FIELD');

        $this->renderContextMock->expects($this->any())
            ->method('getRequestParam')
            ->will($this->returnValue('field'));

        $this->renderContextMock->expects($this->any())
            ->method('getRequestParam')
            ->will($this->returnValue('direction'));

        $this->assertNull($this->view->prepare());
    }
}
