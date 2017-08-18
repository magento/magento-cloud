<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCard\Test\Block\Adminhtml\Product\Composite;

use Magento\Mtf\Fixture\FixtureInterface;

/**
 * Class Configure
 * Adminhtml gift card product composite configure block
 */
class Configure extends \Magento\Catalog\Test\Block\Adminhtml\Product\Composite\Configure
{
    /**
     * Fill options for the product
     *
     * @param FixtureInterface $product
     * @return void
     */
    public function fillOptions(FixtureInterface $product)
    {
        $data = $this->prepareData($product->getData());
        $this->_fill($data);
    }

    /**
     * Prepare data
     *
     * @param array $fields
     * @return array
     */
    protected function prepareData(array $fields)
    {
        $productOptions = [];
        $checkoutData = $fields['checkout_data']['options'];
        $giftCardAmounts = $fields['giftcard_amounts'];
        if (isset($checkoutData['giftcard_options'])) {
            $productOptions = array_merge($productOptions, $checkoutData['giftcard_options']);
            $keyAmount = str_replace('option_key_', '', $productOptions['giftcard_amount']);
            $productOptions['giftcard_amount'] = $giftCardAmounts[$keyAmount]['value'];
        }

        return $this->dataMapping($productOptions);
    }
}
