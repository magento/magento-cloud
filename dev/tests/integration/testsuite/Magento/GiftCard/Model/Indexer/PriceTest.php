<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCard\Model\Indexer;

class PriceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\Helper\Bootstrap
     */
    private $objectManager;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    private $productCollectionFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product
     */
    private $productResource;

    protected function setUp()
    {
        $this->objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $this->productCollectionFactory =
            $this->objectManager->create(\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory::class);
        $this->productResource = $this->objectManager->get(\Magento\Catalog\Model\ResourceModel\Product::class);
    }

    /**
     * @magentoDataFixture Magento/GiftCard/_files/gift_card_with_amount.php
     * @magentoAppIsolation enabled
     */
    public function testPriceForGiftCardWithTwoAmounts()
    {
        $giftCardId = $this->productResource->getIdBySku('gift-card-with-amount');
        $priceInfoFromIndexer = $this->productCollectionFactory->create()
            ->addIdFilter([$giftCardId])
            ->addPriceData()
            ->load()
            ->getFirstItem();

        $this->assertEquals(7, $priceInfoFromIndexer->getMinimalPrice());

        $this->assertNull($priceInfoFromIndexer->getMaxPrice());
    }

    /**
     * @magentoDataFixture Magento/GiftCard/_files/gift_card_with_open_amount.php
     * @magentoAppIsolation enabled
     */
    public function testPriceForGiftCardWithOpenAmounts()
    {
        $giftCardId = $this->productResource->getIdBySku('gift-card-with-open-amount');
        $priceInfoFromIndexer = $this->productCollectionFactory->create()
            ->addIdFilter([$giftCardId])
            ->addPriceData()
            ->load()
            ->getFirstItem();

        $this->assertEquals(100, $priceInfoFromIndexer->getMinimalPrice());

        $this->assertNull($priceInfoFromIndexer->getMaxPrice());
    }
}
