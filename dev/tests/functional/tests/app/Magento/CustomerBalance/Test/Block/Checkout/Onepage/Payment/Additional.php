<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerBalance\Test\Block\Checkout\Onepage\Payment;

use Magento\Mtf\Block\Form;

/**
 * Apply store credit payment.
 */
class Additional extends Form
{
    /**
     * 'Use Store Credit' button selector.
     *
     * @var string
     */
    protected $useStoreCreditButton = '[name="payment[use_customer_balance]"]';

    /**
     * Wait element.
     *
     * @var string
     */
    protected $waitElement = '.loading-mask';

    /**
     * Magento new loader.
     *
     * @var string
     */
    protected $spinner = '[data-role="spinner"]';

    /**
     * Magento loader.
     *
     * @var string
     */
    protected $loader = '[data-role="loader"]';

    /**
     * Magento varienLoader.js loader.
     *
     * @var string
     */
    protected $loaderOld = '#loading-mask #loading_mask_loader';

    /**
     * Apply store credit.
     *
     * @return void
     */
    public function useStoreCredit()
    {
        $this->_rootElement->find($this->useStoreCreditButton)->click();
        $this->waitForElementNotVisible($this->waitElement);
        $this->waitForElementNotVisible($this->spinner);
        $this->waitForElementNotVisible($this->loader);
        $this->waitForElementNotVisible($this->loaderOld);
    }
}
