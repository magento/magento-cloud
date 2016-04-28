<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Rma\Model\Item;

class FormTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Rma\Model\Item\Form
     */
    protected $_model;

    protected function setUp()
    {
        $this->_model = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\Rma\Model\Item\Form'
        );
        $this->_model->setFormCode('default');
    }

    public function testGetAttributes()
    {
        $attributes = $this->_model->getAttributes();
        $this->assertInternalType('array', $attributes);
        $this->assertNotEmpty($attributes);
    }
}
