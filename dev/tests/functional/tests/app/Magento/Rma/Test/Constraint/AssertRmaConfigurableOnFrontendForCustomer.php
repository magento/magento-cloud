<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Test\Constraint;

use Magento\ConfigurableProduct\Test\Fixture\ConfigurableProduct;
use Magento\Rma\Test\Fixture\Rma;
use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Sales\Test\Fixture\OrderInjectable;

/**
 * Assert that rma with item as configurable product is correct display on frontend (MyAccount - My Returns).
 */
class AssertRmaConfigurableOnFrontendForCustomer extends AssertRmaOnFrontendForCustomer
{
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
            $productData['sku'] = $this->prepareProductSku($product);
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

    /**
     * Prepare product sku.
     *
     * @param FixtureInterface $product
     * @return string
     */
    public function prepareProductSku(FixtureInterface $product)
    {
        /** @var ConfigurableProduct $product */
        $checkoutData = $product->getCheckoutData();
        $checkoutOptions = isset($checkoutData['options']['configurable_options'])
            ? $checkoutData['options']['configurable_options']
            : [];
        $configurableAttributesData = $product->getConfigurableAttributesData();
        $matrixKey = [];

        foreach ($checkoutOptions as $checkoutOption) {
            $matrixKey[] = $checkoutOption['title'] . ':' . $checkoutOption['value'];
        }
        $matrixKey = implode(' ', $matrixKey);

        return $configurableAttributesData['matrix'][$matrixKey]['sku'];
    }
}
