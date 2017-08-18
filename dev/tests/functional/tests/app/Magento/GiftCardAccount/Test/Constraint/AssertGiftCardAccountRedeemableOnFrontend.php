<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCardAccount\Test\Constraint;

use Magento\Cms\Test\Page\CmsIndex;
use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\CustomerAccountIndex;
use Magento\GiftCardAccount\Test\Fixture\GiftCardAccount;

/**
 * Class AssertGiftCardAccountRedeemableOnFrontend
 * Assert that gift card is redeemable on frontend
 */
class AssertGiftCardAccountRedeemableOnFrontend extends AbstractAssertGiftCardAccountOnFrontend
{
    /**
     * Text value to be checked
     */
    const SUCCESS_MESSAGE = 'Gift Card "%s" was redeemed.';

    /**
     * Assert that gift card is redeemable on frontend
     *
     * @param CustomerAccountIndex $customerAccountIndex
     * @param CmsIndex $cmsIndex
     * @param Customer $customer
     * @param GiftCardAccount $giftCardAccount
     * @param string $code
     * @return void
     */
    public function processAssert(
        CustomerAccountIndex $customerAccountIndex,
        CmsIndex $cmsIndex,
        Customer $customer,
        GiftCardAccount $giftCardAccount,
        $code
    ) {
        $this->login($customer);
        $cmsIndex->getLinksBlock()->openLink('My Account');
        $customerAccountIndex->getAccountMenuBlock()->openMenuItem('Gift Card');
        $customerAccountIndex->getRedeemBlock()->redeemGiftCard($code);
        $message = $customerAccountIndex->getMessages()->getSuccessMessage();
        $expectMessage = sprintf(self::SUCCESS_MESSAGE, $code);
        \PHPUnit_Framework_Assert::assertEquals(
            $expectMessage,
            $message,
            'Wrong success message is displayed.'
        );
        $customerAccountIndex->getAccountMenuBlock()->openMenuItem('Store Credit');
        \PHPUnit_Framework_Assert::assertTrue(
            $customerAccountIndex->getStoreCreditBlock()->isBalanceChangeVisible($giftCardAccount->getBalance()),
            'Store credit is not change.'
        );
    }

    /**
     * Text that gift card is redeemable on frontend
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift card is redeemable on frontend and success redeemed message is displayed.';
    }
}
