<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Model;

/**
 * Item test class.
 */
class ItemTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Framework\App\ObjectManager
     */
    private $objectManager;

    /**
     * @var \Magento\GiftRegistry\Model\Item
     */
    private $model;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->model = $this->objectManager->get(\Magento\GiftRegistry\Model\Item::class);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     * @magentoDataFixture Magento/Catalog/_files/product_simple.php
     * @covers \Magento\GiftRegistry\Model\Item::getBuyRequest()
     */
    public function testGetBuyRequest()
    {
        /** @var \Magento\Catalog\Api\ProductRepositoryInterface $productRepository */
        $productRepository = $this->objectManager->get(\Magento\Catalog\Api\ProductRepositoryInterface::class);
        $product = $productRepository->getById(1);

        /** @var \Magento\GiftRegistry\Model\Item\Option $option */
        $option = $this->objectManager->create(
            \Magento\GiftRegistry\Model\Item\Option::class,
            ['data' => ['code' => 'info_buyRequest', 'value' => '{"qty":23}']]
        );
        $option->setProduct($product);
        $this->model->addOption($option);

        // Assert getBuyRequest method
        $buyRequest = $this->model->getBuyRequest();
        $this->assertEquals($buyRequest->getOriginalQty(), 23);
    }
}
