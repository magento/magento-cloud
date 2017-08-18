<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SalesArchive\Test\Constraint;

use Magento\Sales\Test\Fixture\OrderInjectable;
use Magento\SalesArchive\Test\Page\Adminhtml\ArchiveInvoices;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Invoice with corresponding fixture data is present in Sales Archive Invoices grid
 */
class AssertArchiveInvoiceInGrid extends AbstractConstraint
{
    /**
     * Invoice with corresponding data is present in Sales Archive Invoices grid
     *
     * @param ArchiveInvoices $archiveInvoices
     * @param OrderInjectable $order
     * @param array $ids
     * @return void
     */
    public function processAssert(ArchiveInvoices $archiveInvoices, OrderInjectable $order, array $ids)
    {
        $orderId = $order->getId();
        $archiveInvoices->open();

        foreach ($ids['invoiceIds'] as $invoiceId) {
            $filter = [
                'order_id' => $orderId,
                'invoice_id' => $invoiceId,
            ];

            $errorMessage = implode(', ', $filter);
            \PHPUnit_Framework_Assert::assertTrue(
                $archiveInvoices->getInvoicesGrid()->isRowVisible($filter),
                'Invoice with following data \'' . $errorMessage . '\' is absent in archive invoices grid.'
            );
        }
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Invoice is present in archive invoices grid.';
    }
}
