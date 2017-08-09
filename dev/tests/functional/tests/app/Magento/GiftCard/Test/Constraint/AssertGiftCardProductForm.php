<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCard\Test\Constraint;

use Magento\Catalog\Test\Constraint\AssertProductForm;

/**
 * Class AssertGiftCardProductForm
 */
class AssertGiftCardProductForm extends AssertProductForm
{
    /**
     * Sort fields for fixture and form data
     *
     * @var array
     */
    protected $sortFields = [
        'giftcard_amounts::price',
    ];

    /**
     * @inheritdoc
     */
    protected function prepareFixtureData(array $data, array $sortFields = [])
    {
        if (isset($data['giftcard_amounts']) && $data['giftcard_amounts'] == 'none') {
            unset($data['giftcard_amounts']);
            array_push($this->skippedFixtureFields, 'giftcard_amounts');
        }

        return parent::prepareFixtureData($data, $sortFields);
    }
}
