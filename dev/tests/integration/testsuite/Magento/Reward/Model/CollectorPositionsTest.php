<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Test positions of the Reward total collectors as compared to other collectors
 */
namespace Magento\Reward\Model;

class CollectorPositionsTest extends \Magento\Sales\Model\AbstractCollectorPositionsTest
{
    /**
     * @return array
     */
    public function collectorPositionDataProvider()
    {
        return [
            'quote collectors' => [
                'reward',
                'quote',
                [],
                ['weee', 'discount', 'tax', 'tax_subtotal', 'grand_total', 'giftcardaccount', 'customerbalance'],
            ],
            'invoice collectors' => [
                'reward',
                'invoice',
                ['giftcardaccount', 'customerbalance'],
                ['grand_total'],
            ],
            'creditmemo collectors' => [
                'reward',
                'creditmemo',
                [],
                ['weee', 'discount', 'tax', 'grand_total', 'customerbalance', 'giftcardaccount'],
            ]
        ];
    }
}
