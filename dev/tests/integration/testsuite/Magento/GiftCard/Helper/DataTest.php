<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\GiftCard\Helper;

use Magento\TestFramework\Helper\Bootstrap;

class DataTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Data
     */
    private $helper;

    protected function setUp()
    {
        $this->helper = Bootstrap::getObjectManager()->create(Data::class);
    }

    public function testGetEmailGeneratedItemsBlock()
    {
        $result = $this->helper->getEmailGeneratedItemsBlock()
            ->getUrl('magento_giftcardaccount/customer', ['giftcard' => '0V6YN8ZUBIZF']);

        $this->assertContains('/giftcard/customer/index/giftcard/0V6YN8ZUBIZF/', $result);
    }
}
