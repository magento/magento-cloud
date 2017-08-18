<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Test\TestCase;

use Magento\Cms\Test\Page\CmsIndex;
use Magento\Rma\Test\Constraint\AssertRmaSuccessSaveMessageOnFrontend;
use Magento\Rma\Test\Fixture\Rma;
use Magento\Rma\Test\Page\RmaGuestCreate;
use Magento\Sales\Test\Fixture\OrderInjectable;
use Magento\Mtf\ObjectManager;
use Magento\Sales\Test\Page\SalesGuestView;

/**
 * Preconditions:
 * 1. Enable RMA on Frontend (Configuration - Sales - RMA Settings).
 * 2. Create products.
 * 3. Create Order.
 * 4. Create invoice and shipping.
 *
 * Steps:
 * 1. Open order on frontend for guest.
 * 3. Create new return.
 * 4. Fill return items data according to dataset.
 * 5. Submit returns.
 * 6. Perform all assertions.
 *
 * @group RMA
 * @ZephyrId MAGETWO-12432
 */
class CreateRmaEntityOnFrontendTest extends AbstractRmaEntityTest
{
    /* tags */
    const MVP = 'no';
    const TEST_TYPE = 'extended_acceptance_test';
    /* end tags */

    /**
     * Run test create Rma Entity on frontend for guest.
     *
     * @param string $configData
     * @param Rma $rma
     * @param SalesGuestView $salesGuestView
     * @param RmaGuestCreate $rmaGuestCreate
     * @param CmsIndex $cmsIndex,
     * @param AssertRmaSuccessSaveMessageOnFrontend $assertRmaSuccessSaveMessage
     * @return array
     */
    public function test(
        $configData,
        Rma $rma,
        SalesGuestView $salesGuestView,
        RmaGuestCreate $rmaGuestCreate,
        CmsIndex $cmsIndex,
        AssertRmaSuccessSaveMessageOnFrontend $assertRmaSuccessSaveMessage
    ) {
        // Preconditions
        $this->objectManager->create(
            \Magento\Config\Test\TestStep\SetupConfigurationStep::class,
            ['configData' => $configData]
        )->run();
        $order = $rma->getDataFieldConfig('order_id')['source']->getOrder();
        $products = $order->getEntityId()['products'];
        $cart['data']['items'] = ['products' => $products];
        $cart = $this->fixtureFactory->createByCode('cart', $cart);
        $this->objectManager->create(
            \Magento\Sales\Test\TestStep\CreateInvoiceStep::class,
            ['order' => $order, 'cart' => $cart]
        )->run();
        $this->objectManager->create(
            \Magento\Sales\Test\TestStep\CreateShipmentStep::class,
            ['order' => $order]
        )->run();

        // Steps
        $this->objectManager->create(
            \Magento\Sales\Test\TestStep\OpenSalesOrderOnFrontendForGuestStep::class,
            ['order' => $order]
        )->run();
        $salesGuestView->getActionsToolbar()->clickLink('Return');
        $rmaGuestCreate->getReturnForm()->fill($rma);
        $rmaGuestCreate->getReturnForm()->submitReturn();

        $assertRmaSuccessSaveMessage->processAssert($cmsIndex);

        $rmaId = $this->getRmaId($rma);
        $rma = $this->createRma($rma, ['entity_id' => $rmaId]);
        return ['rma' => $rma];
    }
}
