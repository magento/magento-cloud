<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Sales\Test\Fixture;

use Magento\Mtf\Factory\Factory;

/**
 * Class PaypalPayflowLinkOrder
 * Guest checkout using PayPal Payflow Link method
 *
 */
class PaypalPayflowLinkOrder extends OrderCheckout
{
    /**
     * Prepare data for guest checkout using Paypal Payflow Link.
     */
    protected function _initData()
    {
        $this->checkoutFixture = Factory::getFixtureFactory()->getMagentoCheckoutGuestPaypalPayflowLink();
        //Verification data
        $this->_data = [
            'totals' => [
                'grand_total' => '168.72',
            ],
        ];
    }

    /**
     * Override persist to capture credit card data for Paypal Payflow Link payment method.
     */
    public function persist()
    {
        parent::persist();

        /** @var \Magento\Paypal\Test\Block\Form\PayflowAdvanced\CcLink $formBlock */
        $formBlock = Factory::getPageFactory()->getCheckoutOnepage()->getPayflowLinkCcBlock();
        $formBlock->fill($this->checkoutFixture->getCreditCard());
        $formBlock->pressContinue();
        Factory::getClientBrowser()->selectWindow();

        $checkoutOnePageSuccess = Factory::getPageFactory()->getCheckoutOnepageSuccess();
        $this->orderId = $checkoutOnePageSuccess->getSuccessBlock()->getOrderId($this);
    }
}
