<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Test\Constraint;

use Magento\Bundle\Test\Fixture\BundleProduct;
use Magento\Rma\Test\Fixture\Rma;
use Magento\Sales\Test\Fixture\OrderInjectable;
use Magento\Mtf\Fixture\FixtureInterface;

/**
 * Assert that rma with item as bundle product is correct display on frontend (MyAccount - My Returns).
 */
class AssertRmaBundleOnFrontendForCustomer extends AssertRmaOnFrontendForCustomer
{
    /**
     * Get items of rma.
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
        $result = [];

        foreach ($rmaItems as $productKey => $productData) {
            $key = str_replace('product_key_', '', $productKey);
            $product = $orderItems[$key];
            $productItems = $this->getProductItems($product);

            foreach ($productItems as $data) {
                $itemData = $productData;
                $itemData['sku'] = $data['sku'];
                $itemData['product'] = $data['name'];
                $itemData['qty'] = $productData['qty_requested'];
                if (!isset($itemData['status'])) {
                    $itemData['status'] = 'Pending';
                }
                unset($itemData['reason']);

                $result[] = $itemData;
            }
        }

        return $result;
    }

    /**
     * Get items of bundle product.
     *
     * @param FixtureInterface $product
     * @return array
     */
    protected function getProductItems(FixtureInterface $product)
    {
        /** @var BundleProduct $product */
        $bundleSelections = $product->getBundleSelections();
        $checkoutData = $product->getCheckoutData();
        $checkoutOptions = isset($checkoutData['options']['bundle_options'])
            ? $checkoutData['options']['bundle_options']
            : [];
        $result = [];

        foreach ($checkoutOptions as $option) {
            foreach ($bundleSelections['products'] as $optionProducts) {
                foreach ($optionProducts as $productItem) {
                    if (false !== strpos($productItem->getName(), $option['value']['name'])) {
                        $result[] = [
                            'sku' => $productItem->getSku(),
                            'name' => $productItem->getName()
                        ];
                    }
                }
            }
        }

        return $result;
    }
}
