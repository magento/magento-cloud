<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\MultipleWishlist\Test\TestStep;

use Magento\Mtf\TestStep\TestStepInterface;
use Magento\Customer\Test\Page\Adminhtml\CustomerIndex as CustomerIndexPage;
use Magento\Customer\Test\Page\Adminhtml\CustomerIndexEdit as CustomerIndexEditPage;
use Magento\Sales\Test\Page\Adminhtml\OrderCreateIndex as OrderCreateIndexPage;
use Magento\Customer\Test\Fixture\Customer;
use Magento\MultipleWishlist\Test\Fixture\MultipleWishlist;
use Magento\Mtf\Fixture\InjectableFixture;

/**
 * Create order for customer using product from customer's multiple wishlist
 */
class CreateOrderForCustomerMultipleWishlistStep implements TestStepInterface
{
    /**
     * @var CustomerIndexPage
     */
    private $customerIndexPage;

    /**
     * @var CustomerIndexEditPage
     */
    private $customerIndexEditPage;

    /**
     * @var OrderCreateIndexPage
     */
    private $orderCreateIndexPage;

    /**
     * @var Customer
     */
    private $customer;

    /**
     * @var MultipleWishlist
     */
    private $multipleWishlist;

    /**
     * @var InjectableFixture
     */
    private $product;

    /**
     * @var string
     */
    private $qtyToMove;

    /**
     * @param CustomerIndexPage $customerIndexPage
     * @param CustomerIndexEditPage $customerIndexEditPage
     * @param OrderCreateIndexPage $orderCreateIndexPage
     * @param Customer $customer
     * @param MultipleWishlist $multipleWishlist
     * @param InjectableFixture $product
     * @param string $qtyToMove
     */
    public function __construct(
        CustomerIndexPage $customerIndexPage,
        CustomerIndexEditPage $customerIndexEditPage,
        OrderCreateIndexPage $orderCreateIndexPage,
        Customer $customer,
        MultipleWishlist $multipleWishlist,
        InjectableFixture $product,
        $qtyToMove
    ) {
        $this->customerIndexPage = $customerIndexPage;
        $this->customerIndexEditPage = $customerIndexEditPage;
        $this->orderCreateIndexPage = $orderCreateIndexPage;
        $this->customer = $customer;
        $this->multipleWishlist = $multipleWishlist;
        $this->product = $product;
        $this->qtyToMove = $qtyToMove;
    }

    /**
     * Create order for customer using product from customer's multiple wishlist
     *
     * @return void
     */
    public function run()
    {
        $this->customerIndexPage->open();
        $this->customerIndexPage->getCustomerGridBlock()->searchAndOpen(['email' => $this->customer->getEmail()]);
        $this->customerIndexEditPage->getPageActionsBlock()->createOrder();
        $this->orderCreateIndexPage->getStoreBlock()->selectStoreView();
        $this->orderCreateIndexPage->getMultipleWishlistBlock()->selectWishlist($this->multipleWishlist->getName());
        $wishlistItemsBlock = $this->orderCreateIndexPage->getMultipleWishlistBlock()->getWishlistItemsBlock();
        $wishlistItemsBlock->selectItemToAddToOrder($this->product, $this->qtyToMove);
    }
}
