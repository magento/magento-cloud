<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCardAccount\Test\Constraint;

use Magento\Cms\Test\Page\CmsIndex;
use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\CustomerAccountIndex;

/**
 * Class AssertGiftCardAccountNotRedeemableOnFrontend
 * Assert that gift card is not redeemable on frontend
 */
class AssertGiftCardAccountNotRedeemableOnFrontend extends AbstractAssertGiftCardAccountOnFrontend
{
    /**
     * Assert that gift card is not redeemable on frontend
     *
     * @param CustomerAccountIndex $customerAccountIndex
     * @param CmsIndex $cmsIndex
     * @param Customer $customer
     * @param string $code
     * @return void
     */
    public function processAssert(
        CustomerAccountIndex $customerAccountIndex,
        CmsIndex $cmsIndex,
        Customer $customer,
        $code
    ) {
        $this->login($customer);
        $cmsIndex->getLinksBlock()->openLink('My Account');
        $customerAccountIndex->getAccountMenuBlock()->openMenuItem('Gift Card');
        $customerAccountIndex->getRedeemBlock()->redeemGiftCard($code);

        \PHPUnit_Framework_Assert::assertTrue(
            $customerAccountIndex->getMessages()->assertErrorMessage(),
            'Gift card is redeemable on frontend'
        );
    }

    /**
     * Text that gift card is not redeemable on frontend
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift card is not redeemable on frontend';
    }
}
