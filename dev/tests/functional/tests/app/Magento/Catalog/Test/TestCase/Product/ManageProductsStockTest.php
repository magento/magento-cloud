<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Catalog\Test\TestCase\Product;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestCase\Injectable;

/**
 * Preconditions:
 * 1. Set Configuration:
 *      - Display OutOfStock = Yes
 *      - Backorders - Allow Qty below = 0
 * 2. Create products according to dataset
 *
 * Steps:
 * 1. Open product on frontend
 * 2. Add product to cart
 * 3. Perform all assertions
 *
 * @group Inventory_(MX)
 * @ZephyrId MAGETWO-29543
 */
class ManageProductsStockTest extends Injectable
{
    /* tags */
    const MVP = 'yes';
    const DOMAIN = 'MX';
    /* end tags */

    /**
     * Fixture factory.
     *
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * Setup configuration.
     *
     * @param FixtureFactory $fixtureFactory
     * @return void
     */
    public function __inject(FixtureFactory $fixtureFactory)
    {
        $this->fixtureFactory = $fixtureFactory;
        $this->objectManager->create(
            'Magento\Config\Test\TestStep\SetupConfigurationStep',
            ['configData' => "display_out_of_stock,backorders_allow_qty_below"]
        )->run();
    }

    /**
     * Manage products stock.
     *
     * @param CatalogProductSimple $product
     * @return array
     */
    public function test(CatalogProductSimple $product)
    {
        // Preconditions
        $product->persist();

        // Steps
        $this->objectManager->create(
            'Magento\Checkout\Test\TestStep\AddProductsToTheCartStep',
            ['products' => [$product]]
        )->run();

        $cart['data']['items'] = ['products' => [$product]];
        return ['cart' => $this->fixtureFactory->createByCode('cart', $cart)];
    }

    /**
     * Set default configuration.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->objectManager->create(
            'Magento\Config\Test\TestStep\SetupConfigurationStep',
            ['configData' => "display_out_of_stock,backorders_allow_qty_below", 'rollback' => true]
        )->run();
    }
}
