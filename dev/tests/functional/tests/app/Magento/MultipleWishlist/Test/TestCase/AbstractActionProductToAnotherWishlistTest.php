<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\MultipleWishlist\Test\TestCase;

use Magento\MultipleWishlist\Test\Fixture\MultipleWishlist;
use Magento\Mtf\Fixture\InjectableFixture;

/**
 * Abstract class for action product to another wish list tests.
 */
abstract class AbstractActionProductToAnotherWishlistTest extends AbstractMultipleWishlistEntityTest
{
    /**
     * Multiple wish list action type.
     *
     * @var string
     */
    protected $action;

    /**
     * Create product.
     *
     * @param string $product
     * @param int $qty
     * @return InjectableFixture
     */
    protected function createProduct($product, $qty)
    {
        list($fixture, $dataset) = explode('::', $product);
        $data = ($qty !== '-') ? ['checkout_data' => ['qty' => $qty]] : [];
        $product = $this->fixtureFactory->createByCode(
            $fixture,
            ['dataset' => $dataset, 'data' => $data]
        );
        $product->persist();
        return $product;
    }

    /**
     * Add product to multiple wish list.
     *
     * @param InjectableFixture $product
     * @return void
     */
    protected function addProductToWishlist($product)
    {
        $this->browser->open($_ENV['app_frontend_url'] . $product->getUrlKey() . '.html');
        $this->catalogProductView->getViewBlock()->addToWishlist($product);
        $this->catalogProductView->getMessagesBlock()->waitSuccessMessage();
    }

    /**
     * Action product to another wish list.
     *
     * @param MultipleWishlist $multipleWishlist
     * @param InjectableFixture $product
     * @param int $qtyToAction
     * @return void
     */
    protected function actionProductToAnotherWishlist(
        MultipleWishlist $multipleWishlist,
        InjectableFixture $product,
        $qtyToAction
    ) {
        $productBlock = $this->wishlistIndex->getMultipleItemsBlock()->getItemProduct($product);
        if ($qtyToAction !== '-') {
            $productBlock->fillProduct(['qty' => $qtyToAction]);
        }
        $productBlock->actionToWishlist($multipleWishlist, $this->action);
        $this->catalogProductView->getMessagesBlock()->waitSuccessMessage();
    }
}
