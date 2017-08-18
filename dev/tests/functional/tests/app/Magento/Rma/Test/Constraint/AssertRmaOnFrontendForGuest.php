<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Test\Constraint;

use Magento\Rma\Test\Fixture\Rma;
use Magento\Rma\Test\Page\RmaGuestIndex;
use Magento\Rma\Test\Page\RmaGuestView;
use Magento\Sales\Test\Fixture\OrderInjectable;
use Magento\Sales\Test\Page\SalesGuestView;

/**
 * Assert that rma is correct display for guest on frontend (Orders and Returns).
 */
class AssertRmaOnFrontendForGuest extends AbstractAssertRmaOnFrontend
{
    /**
     * Assert that rma is correct display for guest on frontend (Orders and Returns):
     * - status on rma history page
     * - details and items on rma view page
     *
     * @param Rma $rma
     * @param SalesGuestView $salesGuestView
     * @param RmaGuestIndex $rmaGuestIndex
     * @param RmaGuestView $rmaGuestView
     * @return void
     */
    public function processAssert(
        Rma $rma,
        SalesGuestView $salesGuestView,
        RmaGuestIndex $rmaGuestIndex,
        RmaGuestView $rmaGuestView
    ) {
        /** @var OrderInjectable $order */
        $order = $rma->getDataFieldConfig('order_id')['source']->getOrder();
        $this->objectManager->create(
            \Magento\Sales\Test\TestStep\OpenSalesOrderOnFrontendForGuestStep::class,
            ['order' => $order]
        )->run();

        $salesGuestView->getViewBlock()->openLinkByName('Returns');
        $fixtureRmaStatus = $rma->getStatus();
        $pageRmaData = $rmaGuestIndex->getReturnsBlock()->getRmaRow($rma)->getData();
        \PHPUnit_Framework_Assert::assertEquals(
            $fixtureRmaStatus,
            $pageRmaData['status'],
            "\nWrong display status of rma."
            . "\nExpected: " . $fixtureRmaStatus
            . "\nActual: " . $pageRmaData['status']
        );

        $rmaGuestIndex->getReturnsBlock()->getRmaRow($rma)->clickView();
        $fixtureRmaItems = $this->getRmaItems($rma);
        $pageRmaItems = $rmaGuestView->getRmaView()->getRmaItems()->getData();
        $this->verifyItems($fixtureRmaItems, $pageRmaItems);
    }

    /**
     * Assert that displayed rma items on edit page equals passed from fixture.
     *
     * @param array $fixtureItems
     * @param array $pageItems
     * @return void
     */
    protected function verifyItems(array $fixtureItems, array $pageItems)
    {
        foreach ($pageItems as $key => $pageItem) {
            $pageItem['product'] = explode("\n", $pageItem['product'])[0];
            $pageItems[$key] = $pageItem;
        }

        $fixtureItems = $this->sortDataByPath($fixtureItems, '::product');
        $pageItems = $this->sortDataByPath($pageItems, '::product');
        foreach ($pageItems as $productName => $pageItem) {
            $pageItems[$productName] = array_intersect_key($pageItem, $fixtureItems[$productName]);
        }
        \PHPUnit_Framework_Assert::assertEquals(
            $fixtureItems,
            $pageItems,
            'Displayed rma items on edit page does not equals passed from fixture.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return "Correct guest's return request is present on frontend (Orders and Returns).";
    }
}
