<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Paypal\Test\Block;

use Magento\Mtf\Block\Form;

/**
 * Class MainPanel
 * MainPanel block on the PayPal page
 *
 */
class MainPanel extends Form
{
    /**
     * Link back to storefront
     *
     * @var string
     */
    protected $callbackLink = '[name="merchant_return_link"]';

    /**
     * Go back to storefront
     */
    public function clickReturnLink()
    {
        $this->_rootElement->find($this->callbackLink)->click();
    }
}
