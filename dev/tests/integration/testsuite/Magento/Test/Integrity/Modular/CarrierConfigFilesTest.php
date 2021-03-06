<?php
/**
 * Test configuration of Online Shipping carriers
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Test\Integrity\Modular;

class CarrierConfigFilesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Config\Model\Config\Structure\Reader
     */
    protected $_reader;

    protected function setUp()
    {
        $moduleReader = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\Framework\Module\Dir\Reader'
        );
        $schemaFile = $moduleReader->getModuleDir('etc', 'Magento_Config') . '/system.xsd';
        $this->_reader = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\Config\Model\Config\Structure\Reader',
            ['perFileSchema' => $schemaFile, 'isValidated' => true]
        );
    }

    /**
     * Tests that all source_models used in shipping are valid
     */
    public function testValidateShippingSourceModels()
    {
        $config = $this->_reader->read('adminhtml');

        $carriers = $config['config']['system']['sections']['carriers']['children'];
        foreach ($carriers as $carrier) {
            foreach ($carrier['children'] as $field) {
                if (isset($field['source_model'])) {
                    $model = $field['source_model'];
                    \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create($model);
                }
            }
        }
    }
}
