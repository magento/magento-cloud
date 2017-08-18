<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCard\Test\Block\Catalog\Product;

use Magento\GiftCard\Test\Block\Catalog\Product\View\Type\GiftCard;
use Magento\Mtf\Fixture\FixtureInterface;

/**
 * Class View
 * Product view block on the product page
 *
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 */
class View extends \Magento\Catalog\Test\Block\Product\View
{
    /**
     * Get Gift Card block
     *
     * @return GiftCard
     */
    public function getGiftCardBlock()
    {
        return $this->blockFactory->create(
            \Magento\GiftCard\Test\Block\Catalog\Product\View\Type\GiftCard::class,
            ['element' => $this->_rootElement]
        );
    }

    /**
     * Add product to shopping cart
     *
     * @param FixtureInterface $product
     * @return void
     */
    public function fillOptions(FixtureInterface $product)
    {
        $this->getGiftCardBlock()->fill($product);
        parent::fillOptions($product);
    }
}
