<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Test\Constraint;

use Magento\Rma\Test\Fixture\Rma;
use Magento\Sales\Test\Fixture\OrderInjectable;
use Magento\Mtf\Constraint\AbstractAssertForm;

/**
 * Base assert that displayed rma on frontend is correct.
 */
abstract class AbstractAssertRmaOnFrontend extends AbstractAssertForm
{
    /**
     * Default status of rma item.
     */
    const ITEM_DEFAULT_STATUS = 'Pending';

    /**
     * Get rma items.
     *
     * @param Rma $rma
     * @return array
     */
    protected function getRmaItems(Rma $rma)
    {
        $rmaItems = $rma->getItems();
        /** @var OrderInjectable $order */
        $order = $rma->getDataFieldConfig('order_id')['source']->getOrder();
        $orderItems = $order->getEntityId()['products'];

        foreach ($rmaItems as $productKey => $productData) {
            $key = str_replace('product_key_', '', $productKey);
            $product = $orderItems[$key];

            $productData['product'] = $product->getName();
            $productData['qty'] = $productData['qty_requested'];
            if (!isset($productData['status'])) {
                $productData['status'] = self::ITEM_DEFAULT_STATUS;
            }
            unset($productData['reason']);
            unset($productData['reason_other']);

            $rmaItems[$productKey] = $productData;
        }

        return $rmaItems;
    }
}
