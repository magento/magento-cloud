<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Cybersource\Test\TestStep;

use Magento\Cybersource\Test\Page\Sandbox\CustomerResponse;
use Magento\Cybersource\Test\Page\Sandbox\SecureAcceptance;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Mtf\TestStep\TestStepInterface;
use Magento\Cybersource\Test\Fixture\SandboxCustomer;
use Magento\Cybersource\Test\Page\Sandbox\AccountLogin;

/**
 * Configure Cybersource test account.
 */
class ConfigureCybersourceAccountStep implements TestStepInterface
{
    /**
     * Cybersource Sandbox customer fixture.
     *
     * @var SandboxCustomer
     */
    private $sandboxCustomer;

    /**
     * Cybersource Sandbox account login page.
     *
     * @var AccountLogin
     */
    private $login;

    /**
     * Browser instance.
     *
     * @var BrowserInterface
     */
    private $browser;

    /**
     * Secure Acceptance page.
     *
     * @var SecureAcceptance
     */
    private $secureAcceptance;

    /**
     * Customer Response Pages page.
     *
     * @var CustomerResponse
     */
    private $customerResponse;

    /**
     * Payment method.
     *
     * @var array
     */
    private $payment;

    /**
     * @param SandboxCustomer $sandboxCustomer
     * @param AccountLogin $login
     * @param BrowserInterface $browser
     * @param SecureAcceptance $secureAcceptance
     * @param CustomerResponse $customerResponse
     * @param array $payment
     */
    public function __construct(
        SandboxCustomer $sandboxCustomer,
        AccountLogin $login,
        BrowserInterface $browser,
        SecureAcceptance $secureAcceptance,
        CustomerResponse $customerResponse,
        array $payment
    ) {
        $this->sandboxCustomer = $sandboxCustomer;
        $this->login = $login;
        $this->browser = $browser;
        $this->secureAcceptance = $secureAcceptance;
        $this->customerResponse = $customerResponse;
        $this->payment = $payment;
    }

    /**
     * Configure Cybersource sandbox account.
     *
     * @return void
     */
    public function run()
    {
        if ($this->payment['method'] === 'cybersource') {
            $this->login->open();
            $this->login->getLoginBlock()->resetLoginForm();
            $this->login->getLoginBlock()->fill($this->sandboxCustomer);
            $this->login->getLoginBlock()->sandboxLogin();
            $this->browser->acceptAlert();
            $this->secureAcceptance->open();
            $this->secureAcceptance->getProfileBlock()->selectProfile($this->sandboxCustomer->getUsername());
            $this->secureAcceptance->getProfileEntryBlock()->openEditableVersion();
            $this->secureAcceptance->getProfileEntryBlock()->goToCustomerResponsePages();
            $this->customerResponse->getTransactionResponsePagesBlock()->setResponseUrl(
                $_ENV['app_frontend_url'] . 'cybersource/SilentOrder/TokenResponse'
            );
            $this->customerResponse->getTransactionResponsePagesBlock()->save();
            $this->secureAcceptance->getProfileEntryBlock()->activateProfile();
        }
    }
}
