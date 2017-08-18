<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Cybersource\Test\Block\Sandbox;

use Magento\Mtf\Block\Form;
use Magento\Mtf\Client\Locator;

/**
 * Login block.
 */
class Login extends Form
{
    /**
     * Login button on Cybersource side.
     *
     * @var string
     */
    protected $loginButton = '[type=submit]';

    /**
     * Selector for 'change' link on Cybersource login form.
     *
     * @var string
     */
    protected $changeLink = '//td//a[contains(text(), "change")]';

    /**
     * Reset login form if Cybersource Merchant Id is preselected.
     *
     * @return void
     */
    public function resetLoginForm()
    {
        if ($this->_rootElement->find($this->changeLink, Locator::SELECTOR_XPATH)->isVisible()) {
            $this->_rootElement->find($this->changeLink, Locator::SELECTOR_XPATH)->click();
        }
    }

    /**
     * Login to Cybersource Sandbox.
     *
     * @return void
     */
    public function sandboxLogin()
    {
        $this->_rootElement->find($this->loginButton)->click();
    }
}
