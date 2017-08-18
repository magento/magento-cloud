<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reminder\Test\Constraint;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Catalog\Test\Page\Product\CatalogProductView;
use Magento\Checkout\Test\Page\CheckoutCart;
use Magento\Customer\Test\Fixture\Customer;
use Magento\Reminder\Test\Block\Adminhtml\Reminder\Edit\Customers as TabMatchedCustomers;
use Magento\Reminder\Test\Fixture\Reminder;
use Magento\Reminder\Test\Page\Adminhtml\ReminderIndex;
use Magento\Reminder\Test\Page\Adminhtml\ReminderView;
use Magento\SalesRule\Test\Fixture\SalesRule;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Mtf\ObjectManager;

/**
 * Open created reminder and assert customer in Matched Customers grid.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AssertReminderMatchedCustomers extends AbstractConstraint
{
    /**
     * Browser.
     *
     * @var BrowserInterface
     */
    protected $browser;

    /**
     * Catalog product view page on frontend.
     *
     * @var CatalogProductView
     */
    protected $catalogProductView;

    /**
     * Fixture product.
     *
     * @var CatalogProductSimple
     */
    protected $product;

    /**
     * Fixture matched customer.
     *
     * @var Customer
     */
    protected $matchedCustomer;

    /**
     * Fixture unmatched customer.
     *
     * @var Customer
     */
    protected $unmatchedCustomer;

    /**
     * Open created reminder and assert customer in Matched Customers grid:
     * - email
     * - coupon
     *
     * @param ReminderIndex $reminderIndex
     * @param ReminderView $reminderView
     * @param Reminder $reminder
     * @param BrowserInterface $browser
     * @param CatalogProductView $catalogProductView
     * @param CheckoutCart $checkoutCart
     * @param CatalogProductSimple $product
     * @param Customer $customer1
     * @param Customer $customer2
     * @return void
     */
    public function processAssert(
        ReminderIndex $reminderIndex,
        ReminderView $reminderView,
        Reminder $reminder,
        BrowserInterface $browser,
        CatalogProductView $catalogProductView,
        CheckoutCart $checkoutCart,
        CatalogProductSimple $product,
        Customer $customer1,
        Customer $customer2
    ) {
        $this->browser = $browser;
        $this->catalogProductView = $catalogProductView;
        $this->checkoutCart = $checkoutCart;
        $this->product = $product;
        $this->matchedCustomer = $customer1;
        $this->unmatchedCustomer = $customer2;

        $salesRuleCoupon = $this->getSalesRuleCoupon($reminder);
        $matchedCustomers = $this->prepareCustomers();

        $reminderIndex->open();
        $reminderIndex->getRemindersGrid()->searchAndOpen(['name' => $reminder->getName()]);
        $reminderView->getPageMainActions()->runNow();

        /** @var TabMatchedCustomers $tabCustomers */
        $tabCustomers = $reminderView->getReminderForm()->getTab('matched_customers');
        foreach ($matchedCustomers as $customer) {
            $filter = [
                'email' => $customer['email'],
                'coupon' => $salesRuleCoupon,
            ];

            $tabCustomers->getCusromersGrid()->search($filter);
            $filter['coupon'] = $filter['coupon'] ? $filter['coupon'] : 'N/A';
            \PHPUnit_Framework_Assert::assertTrue(
                $customer['is_matched'] == $tabCustomers->getCusromersGrid()->isRowVisible($filter, false, false),
                'Wrong matched customer'
            );
        }
    }

    /**
     * Prepare customers.
     *
     * @return array
     */
    protected function prepareCustomers()
    {
        $this->addProductToCart($this->matchedCustomer, 2);
        $this->addProductToCart($this->unmatchedCustomer, 1);

        return [
            [
                'email' => $this->matchedCustomer->getEmail(),
                'is_matched' => true,
            ],
            [
                'email' => $this->unmatchedCustomer->getEmail(),
                'is_matched' => false
            ],
        ];
    }

    /**
     * Add product to cart by customer.
     *
     * @param Customer $customer
     * @param int $productQty
     * @return void
     */
    protected function addProductToCart(Customer $customer, $productQty)
    {
        ObjectManager::getInstance()->create(
            \Magento\Customer\Test\TestStep\LoginCustomerOnFrontendStep::class,
            ['customer' => $customer]
        )->run();

        $this->browser->open($_ENV['app_frontend_url'] . $this->product->getUrlKey() . '.html');
        $this->catalogProductView->getViewBlock()->fillOptions($this->product);
        $this->catalogProductView->getViewBlock()->setQty($productQty);
        $this->catalogProductView->getViewBlock()->clickAddToCart();
        $this->catalogProductView->getMessagesBlock()->waitSuccessMessage();
    }

    /**
     * Get coupon code from SalesRule of Reminder.
     *
     * @param Reminder $reminder
     * @return string
     */
    protected function getSalesRuleCoupon(Reminder $reminder)
    {
        if ($reminder->hasData('salesrule_id')) {
            /** @var SalesRule $salesRule */
            $salesRule = $reminder->getDataFieldConfig('salesrule_id')['source']->getSalesRule();
            return $salesRule->getCouponCode();
        }
        return '';
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Matched customers is present in grid.';
    }
}
