<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
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
}
