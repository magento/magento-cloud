<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Sales\Test\Fixture;

use Magento\Mtf\Factory\Factory;

/**
 * Class PaypalPaymentsAdvancedOrder
 * Guest checkout using PayPal Payments Advanced method
 *
 */
class PaypalPaymentsAdvancedOrder extends OrderCheckout
{
    /**
     * Prepare data for guest checkout using Paypal Payments Advanced.
     */
    protected function _initData()
    {
        $this->checkoutFixture = Factory::getFixtureFactory()->getMagentoCheckoutGuestPayPalAdvanced();
        //Verification data
        $this->_data = [
            'totals' => [
                'grand_total' => '156.81',
            ],
        ];
    }

    /**
     * Override persist to capture credit card data for Paypal Payments Advanced payment method.
     */
    public function persist()
    {
        parent::persist();

        /** @var \Magento\Paypal\Test\Block\Form\PayflowAdvanced\CcAdvanced $formBlock */
        $formBlock = Factory::getPageFactory()->getCheckoutOnepage()->getPayflowAdvancedCcBlock();
        $formBlock->fill($this->checkoutFixture->getCreditCard());
        $formBlock->pressContinue();
        Factory::getClientBrowser()->selectWindow();

        $checkoutOnePageSuccess = Factory::getPageFactory()->getCheckoutOnepageSuccess();
        $this->orderId = $checkoutOnePageSuccess->getSuccessBlock()->getOrderId($this);
    }
}
