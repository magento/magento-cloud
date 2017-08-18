<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Test\TestCase;

use Magento\Mtf\TestStep\TestStepFactory;
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
 * @group RMA
 * @ZephyrId MAGETWO-28571
 */
class CreateRmaEntityOnBackendTest extends AbstractRmaEntityTest
{
    /* tags */
    const MVP = 'no';
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
     * Test step factory.
     *
     * @var TestStepFactory
     */
    protected $testStepFactory;

    /**
     * Additional Rma attributes.
     *
     * @var array
     */
    protected $rmaAttributes = [];

    /**
     * Inject data.
     *
     * @param RmaChooseOrder $rmaChooseOrder
     * @param RmaNew $rmaNew
     * @param TestStepFactory $testStepFactory
     *
     * @return void
     */
    public function __inject(
        RmaChooseOrder $rmaChooseOrder,
        RmaNew $rmaNew,
        TestStepFactory $testStepFactory
    ) {
        $this->rmaChooseOrder = $rmaChooseOrder;
        $this->rmaNew = $rmaNew;
        $this->testStepFactory = $testStepFactory;
    }

    /**
     * Run test create Rma Entity on frontend.
     *
     * @param string $configData
     * @param Rma $rma
     * @param AssertRmaSuccessSaveMessage $assertRmaSuccessSaveMessage
     *
     * @return array
     */
    public function test($configData, Rma $rma, AssertRmaSuccessSaveMessage $assertRmaSuccessSaveMessage)
    {
        if ($rma->hasData('attribute_ids')) {
            $this->rmaAttributes = $rma->getDataFieldConfig('attribute_ids')['source']->getRmaAttributes();
        }
        // Preconditions
        $this->testStepFactory->create(
            \Magento\Config\Test\TestStep\SetupConfigurationStep::class,
            ['configData' => $configData]
        )->run();
        /** @var OrderInjectable $order */
        $order = $rma->getDataFieldConfig('order_id')['source']->getOrder();
        $products = $order->getEntityId()['products'];
        $cart['data']['items'] = ['products' => $products];
        $cart = $this->fixtureFactory->createByCode('cart', $cart);
        $this->testStepFactory->create(
            \Magento\Sales\Test\TestStep\CreateInvoiceStep::class,
            ['order' => $order, 'cart' => $cart]
        )->run();
        $this->testStepFactory->create(
            \Magento\Sales\Test\TestStep\CreateShipmentStep::class,
            ['order' => $order]
        )->run();

        // Steps
        $this->rmaIndex->open();
        $this->rmaIndex->getGridPageActions()->addNew();
        $this->rmaChooseOrder->getOrderGrid()->searchAndOpen(['id' => $rma->getOrderId()]);
        $this->rmaNew->getRmaForm()->fill($rma, null);
        $this->rmaNew->getPageActions()->save();

        $assertRmaSuccessSaveMessage->processAssert($this->rmaIndex);

        $rmaId = $this->getRmaId($rma);
        $rma = $this->createRma($rma, ['entity_id' => $rmaId]);
        return ['rma' => $rma];
    }

    /**
     * Remove rma attributes.
     *
     * @return void
     */
    public function tearDown()
    {
        foreach ($this->rmaAttributes as $rmaAttribute) {
            $this->testStepFactory->create(
                \Magento\Rma\Test\TestStep\DeleteAttributeStep::class,
                ['attribute' => $rmaAttribute]
            )->run();
        }
    }
}
