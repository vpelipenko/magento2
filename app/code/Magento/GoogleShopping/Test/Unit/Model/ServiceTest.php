<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\GoogleShopping\Test\Unit\Model;

class ServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $_helper;

    /**
     * @var \Magento\GoogleShopping\Model\Service
     */
    protected $_model;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $_contentMock;

    protected function setUp()
    {
        $this->_helper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->_contentMock = $this->getMockBuilder(
            'Magento\Framework\Gdata\Gshopping\Content'
        )->disableOriginalConstructor()->getMock();
        $contentFactoryMock = $this->getMockBuilder(
            'Magento\Framework\Gdata\Gshopping\ContentFactory'
        )->disableOriginalConstructor()->setMethods(
            ['create']
        )->getMock();
        $contentFactoryMock->expects($this->any())->method('create')->will($this->returnValue($this->_contentMock));

        $coreRegistryMock = $this->getMockBuilder(
            'Magento\Framework\Registry'
        )->disableOriginalConstructor()->setMethods(
            ['registry']
        )->getMock();
        $coreRegistryMock->expects($this->any())->method('registry')->will($this->returnValue(1));

        $arguments = ['contentFactory' => $contentFactoryMock, 'coreRegistry' => $coreRegistryMock];
        $this->_model = $this->_helper->getObject('Magento\GoogleShopping\Model\Service', $arguments);
    }

    public function testGetService()
    {
        $this->assertEquals('Magento\Framework\Gdata\Gshopping\Content', get_parent_class($this->_model->getService()));
    }

    public function testSetService()
    {
        $this->_model->setService($this->_contentMock);
        $this->assertSame($this->_contentMock, $this->_model->getService());
    }
}
