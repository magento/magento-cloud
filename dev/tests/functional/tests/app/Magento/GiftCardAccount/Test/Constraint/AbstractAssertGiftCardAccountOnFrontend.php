<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCardAccount\Test\Constraint;

use Magento\Checkout\Test\Page\CheckoutCart;
use Magento\Cms\Test\Page\CmsIndex;
use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\CustomerAccountIndex;
use Magento\Customer\Test\Page\CustomerAccountLogin;
use Magento\Customer\Test\Page\CustomerAccountLogout;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Mtf\ObjectManager;
use Magento\Mtf\System\Event\EventManagerInterface;

/**
 * Assert that created gift card account can be verified on the frontend.
 */
abstract class AbstractAssertGiftCardAccountOnFrontend extends AbstractConstraint
{
    /**
     * Customer login page.
     *
     * @var CustomerAccountLogin
     */
    protected $customerAccountLogin;

    /**
     * Cms index page.
     *
     * @var CmsIndex
     */
    protected $cmsIndex;

    /**
     * Customer log out page.
     *
     * @var CustomerAccountLogout
     */
    protected $customerAccountLogout;

    /**
     * @constructor
     * @param ObjectManager $objectManager
     * @param EventManagerInterface $eventManager
     * @param CustomerAccountLogin $customerAccountLogin
     * @param CmsIndex $cmsIndex
     * @param CustomerAccountLogout $customerAccountLogout
     */
    public function __construct(
        ObjectManager $objectManager,
        EventManagerInterface $eventManager,
        CustomerAccountLogin $customerAccountLogin,
        CmsIndex $cmsIndex,
        CustomerAccountLogout $customerAccountLogout
    ) {
        parent::__construct($objectManager, $eventManager);
        $this->customerAccountLogin = $customerAccountLogin;
        $this->cmsIndex = $cmsIndex;
        $this->customerAccountLogout = $customerAccountLogout;
    }

    /**
     * Login on the frontend.
     *
     * @param Customer $customer
     * @return void
     */
    protected function login(Customer $customer)
    {
        $this->objectManager->create(
            \Magento\Customer\Test\TestStep\LoginCustomerOnFrontendStep::class,
            ['customer' => $customer]
        )->run();
    }

    /**
     * Prepare data.
     *
     * @param array $data
     * @param CustomerAccountIndex|CheckoutCart $page
     * @return array
     */
    protected function prepareData(array $data, $page)
    {
        $fixtureData = [
            'code' => $data['code'],
            'balance' => $data['balance'],
            'date_expires' => $data['date_expires'],
        ];
        $pageData = $page->getCheckBlock()->getGiftCardAccountData($fixtureData);

        return ['fixtureData' => $fixtureData, 'pageData' => $pageData];
    }
}
