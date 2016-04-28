<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\VersionsCms\Model;

class IncrementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\VersionsCms\Model\Increment
     */
    protected $_model;

    protected function setUp()
    {
        $this->_model = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\VersionsCms\Model\Increment'
        );
    }

    /**
     * @magentoDbIsolation enabled
     */
    public function testGetNewIncrementId()
    {
        $this->assertEmpty($this->_model->getId());
        $this->assertEmpty($this->_model->getIncrementType());
        $this->assertEmpty($this->_model->getIncrementNode());
        $this->assertEmpty($this->_model->getIncrementLevel());
        $this->_model->getNewIncrementId(\Magento\VersionsCms\Model\Increment::TYPE_PAGE, 1, 1);
        $this->assertEquals(\Magento\VersionsCms\Model\Increment::TYPE_PAGE, $this->_model->getIncrementType());
        $this->assertEquals(1, $this->_model->getIncrementNode());
        $this->assertEquals(1, $this->_model->getIncrementLevel());
        $this->assertNotEmpty($this->_model->getId());
    }
}
