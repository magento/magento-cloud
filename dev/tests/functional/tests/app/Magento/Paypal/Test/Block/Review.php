<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Paypal\Test\Block;

use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;

/**
 * Class Review
 * Paypal sandbox review block
 */
class Review extends Block
{
    /**
     * Continue button
     *
     * @var string
     */
    protected $continue = 'input[type="submit"]';

    /**
     * Press 'Continue' button
     */
    public function continueCheckout()
    {
        $this->_rootElement->find($this->continue, Locator::SELECTOR_CSS)->click();
    }
}
