<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Test\TestCase;

use Magento\Rma\Test\Constraint\AssertRmaSuccessSaveMessage;
use Magento\Rma\Test\Fixture\Rma;
use Magento\Rma\Test\Page\Adminhtml\RmaChooseOrder;
use Magento\Rma\Test\Page\Adminhtml\RmaNew;
use Magento\Sales\Test\Fixture\OrderInjectable;

/**
 * Preconditions:
 * 1. Enable RMA on Frontend (Configuration - Sales - RMA Settings).
 * 2. Create product.
 * 3. Create Order.
 * 4. Create invoice and shipping.
 *
 * Steps:
 * 1. Login to the backend.
 * 2. Navigate to Sales -> Returns.
 * 3. Create new return.
 * 4. Fill data according to dataset.
 * 5. Submit returns.
 * 6. Perform all assertions.
 *
 * @group RMA_(CS)
 * @ZephyrId MAGETWO-28571
 */
class CreateRmaEntityOnBackendTest extends AbstractRmaEntityTest
{
    /* tags */
    const MVP = 'no';
    const DOMAIN = 'CS';
    const TEST_TYPE = 'extended_acceptance_test';
    /* end tags */

    /**
     * Rma choose order page on backend.
     *
     * @var RmaChooseOrder
     */
    protected $rmaChooseOrder;

    /**
     * Rma choose order page on backend.
     *
     * @var RmaNew
     */
    protected $rmaNew;

    /**
     * Inject data.
     *
     * @param RmaChooseOrder $rmaChooseOrder
     * @param RmaNew $rmaNew
     * @return void
     */
    public function __inject(RmaChooseOrder $rmaChooseOrder, RmaNew $rmaNew)
    {
        $this->rmaChooseOrder = $rmaChooseOrder;
        $this->rmaNew = $rmaNew;
    }

    /**
     * Run test create Rma Entity on frontend.
     *
     * @param string $configData
     * @param Rma $rma
     * @param AssertRmaSuccessSaveMessage $assertRmaSuccessSaveMessage
     * @return array
     */
    public function test($configData, Rma $rma, AssertRmaSuccessSaveMessage $assertRmaSuccessSaveMessage)
    {
        // Preconditions
        $this->objectManager->create(
            'Magento\Config\Test\TestStep\SetupConfigurationStep',
            ['configData' => $configData]
        )->run();
        /** @var OrderInjectable $order */
        $order = $rma->getDataFieldConfig('order_id')['source']->getOrder();
        $this->objectManager->create(
            'Magento\Sales\Test\TestStep\CreateInvoiceStep',
            ['order' => $order]
        )->run();
        $this->objectManager->create(
            'Magento\Sales\Test\TestStep\CreateShipmentStep',
            ['order' => $order]
        )->run();

        // Steps
        $this->rmaIndex->open();
        $this->rmaIndex->getGridPageActions()->addNew();
        $this->rmaChooseOrder->getOrderGrid()->searchAndOpen(['id' => $rma->getOrderId()]);
        $this->rmaNew->getRmaForm()->fill($rma);
        $this->rmaNew->getPageActions()->save();

        $assertRmaSuccessSaveMessage->processAssert($this->rmaIndex);

        $rmaId = $this->getRmaId($rma);
        $rma = $this->createRma($rma, ['entity_id' => $rmaId]);
        return ['rma' => $rma];
    }
}
