<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCard\Test\Constraint;

use Magento\Catalog\Test\Constraint\AssertProductPage;

/**
 * Assert that displayed product data on product page(front-end) equals passed from fixture.
 */
class AssertGiftCardProductPage extends AssertProductPage
{
    /**
     * Verify displayed product price on product page(front-end) equals passed from fixture.
     *
     * @return string|null
     */
    protected function verifyPrice()
    {
        $productData = $this->product->getData();
        $priceBlock = $this->productView->getPriceBlock();
        if (!$priceBlock->isVisible()) {
            return "Price block for '{$this->product->getName()}' product' is not visible.";
        }
        $actualPrice = $priceBlock->getPrice();

        $expectedPrice = null;
        if (isset($productData['giftcard_amounts']) && 1 == count($productData['giftcard_amounts'])) {
            $amount = reset($productData['giftcard_amounts']);
            $expectedPrice = $amount['value'];
        }
        if ($expectedPrice == $actualPrice) {
            return null;
        }
        return "Displayed product price on product page(front-end) not equals passed from fixture. "
        . "Actual: {$actualPrice}, expected: {$expectedPrice}.";
    }
}
