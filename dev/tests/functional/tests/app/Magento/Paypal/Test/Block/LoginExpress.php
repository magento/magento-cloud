<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Paypal\Test\Block;

use Magento\Paypal\Test\Fixture\Customer;
use Magento\Mtf\Block\Form;
use Magento\Mtf\Client\Locator;
use Magento\Mtf\ObjectManager;

/**
 * Class Login
 * Login to paypal account
 */
class LoginExpress extends Form
{
    /**
     * Submit login button
     *
     * @var string
     */
    protected $submitLogin = 'input[type="submit"]';

    /**
     * Login form locator
     *
     * @var string
     */
    protected $loginForm = '#login';

    /**
     * Login form locator
     *
     * @var string
     */
    protected $oldRootLocator = '//*[*[@id="login"] or *[@id="loginBox"]]';

    /**
     * Login form locator
     *
     * @var string
     */
    protected $oldLoginForm = '#loginBox';

    /**
     * Login to Paypal account
     *
     * @param Customer $fixture
     * @return void
     * @SuppressWarnings(PHPMD.ConstructorWithNameAsEnclosingClass)
     */
    public function login(Customer $fixture)
    {
        // Wait for page to load in order to check logged customer
        $element = $this->_rootElement;
        $selector = $this->oldRootLocator;
        $element->waitUntil(
            function () use ($element, $selector) {
                return $element->find($selector, Locator::SELECTOR_XPATH)->isVisible() ? true : null;
            }
        );
        // PayPal returns different login pages due to buyer country
        if (!$this->_rootElement->find($this->loginForm)->isVisible()) {
            $payPalLogin = ObjectManager::getInstance()->create(
                '\Magento\Paypal\Test\Block\Login',
                [
                    'element' => $this->browser->find($this->oldLoginForm)
                ]
            );
            $payPalLogin->login($fixture);
            return;
        }
        $loginForm = $this->_rootElement->find($this->loginForm);
        if (!$loginForm->isVisible()) {
            return;
        }

        $this->fill($fixture);
        $loginForm->find($this->submitLogin, Locator::SELECTOR_CSS)->click();
    }
}
