<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\GiftCardAccount\Model;

class PoolTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\GiftCardAccount\Model\Pool
     */
    protected $_model;

    protected function setUp()
    {
        $this->_model = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\GiftCardAccount\Model\Pool::class
        );
    }

    /**
     * @magentoDataFixture Magento/GiftCardAccount/_files/codes_pool.php
     */
    public function testShift()
    {
        $this->_model->setExcludedIds(['fixture_code_2']);
        $result = $this->_model->shift();
        // Only free non-excluded code should be selected
        $this->assertSame('fixture_code_3', $result);
    }

    /**
     * @expectedException \Magento\Framework\Exception\LocalizedException
     * @expectedExceptionMessage No codes left in the pool
     */
    public function testShiftNoCodeLeft()
    {
        $this->_model->shift();
    }
}
