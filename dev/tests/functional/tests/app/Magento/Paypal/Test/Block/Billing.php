<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Paypal\Test\Block;

use Magento\Mtf\Block\Form;

/**
 * Class Billing
 * Billing block on the PayPal page
 *
 */
class Billing extends Form
{
    /**
     * Link to PayPal login page
     *
     * @var string
     */
    protected $loadLogin = '#loadLogin';

    /**
     * Go to PayPal login page
     *
     */
    public function clickLoginLink()
    {
        $loginLink = $this->_rootElement->find($this->loadLogin);
        if ($loginLink->isVisible()) {
            $loginLink->click();
        }
    }
}
