<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Tools\Di\Test\Unit\Compiler\Config;

use \Magento\Tools\Di\Compiler\Config\ModificationChain;

class ModificationChainTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $modificationsList = [];
        $modificationsList[] = $this->getMockBuilder('Magento\Tools\Di\Compiler\Config\ModificationInterface')
            ->getMock();
        $modificationsList[] = $this->getMockBuilder('Magento\Tools\Di\Compiler\Config\ModificationInterface')
            ->getMock();

        new ModificationChain($modificationsList);
    }

    public function testConstructorException()
    {
        $this->setExpectedException('InvalidArgumentException', 'Wrong modifier provided');
        $modificationsList = [];
        $modificationsList[] = $this->getMockBuilder('Magento\Tools\Di\Compiler\Config\ModificationInterface')
            ->getMock();
        $modificationsList[] = $this->getMockBuilder('Magento\Tools\Di\Compiler\Config\ModificationInterface')
            ->getMock();
        $modificationsList[] = 'banana';

        new ModificationChain($modificationsList);
    }

    public function testModify()
    {
        $inputArray = [
            'data' => [1, 2, 3]
        ];

        $expectedArray1 = [
            'data' => [1, 2, 3, 1]
        ];

        $expectedArray2 = [
            'data' => [1, 2, 3, 1, 1]
        ];

        $modifier1 = $this->getMockBuilder('Magento\Tools\Di\Compiler\Config\ModificationInterface')
            ->getMock();
        $modifier2 = $this->getMockBuilder('Magento\Tools\Di\Compiler\Config\ModificationInterface')
            ->getMock();

        $modificationsList = [$modifier1, $modifier2];

        $modifier1->expects($this->once())
            ->method('modify')
            ->with($inputArray)
            ->willReturn($expectedArray1);

        $modifier2->expects($this->once())
            ->method('modify')
            ->with($expectedArray1)
            ->willReturn($expectedArray2);

        $chain = new ModificationChain($modificationsList);

        $this->assertEquals($expectedArray2, $chain->modify($inputArray));
    }
}
