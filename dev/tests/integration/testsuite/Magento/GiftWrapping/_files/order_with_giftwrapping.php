<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile //
require __DIR__ . '/../../../Magento/Sales/_files/default_rollback.php';
require __DIR__ . '/../../../Magento/Catalog/_files/product_simple.php';
/** @var \Magento\Catalog\Model\Product $product */
$addressData = include __DIR__ . '/../../../Magento/Sales/_files/address_data.php';
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$billingAddress = $objectManager->create(\Magento\Sales\Model\Order\Address::class, ['data' => $addressData]);
$billingAddress->setAddressType('billing');
$shippingAddress = clone $billingAddress;$shippingAddress->setId(null)->setAddressType('shipping');
$payment = $objectManager->create(\Magento\Sales\Model\Order\Payment::class);$payment->setMethod('checkmo');
/** @var \Magento\Sales\Model\Order\Item $orderItem */
$orderItem = $objectManager->create(\Magento\Sales\Model\Order\Item::class);
$orderItem->setProductId($product->getId())->setQtyOrdered(2);
$orderItem->setBasePrice($product->getPrice());
$orderItem->setPrice($product->getPrice());
$orderItem->setRowTotal($product->getPrice());
$orderItem->setProductType('simple');
$orderItem->setGwId(10);
$orderItem->setGwBasePrice(10);
$orderItem->setGwPrice(10);
$orderItem->setGwBaseTaxAmount(10);
$orderItem->setGwTaxAmount(10);
$orderItem->setGwBasePriceInvoiced(10);
$orderItem->setGwPriceInvoiced(10);
$orderItem->setGwBaseTaxAmountInvoiced(10);
$orderItem->setGwTaxAmountInvoiced(10);
$orderItem->setGwBasePriceRefunded(10);
$orderItem->setGwPriceRefunded(10);
$orderItem->setGwBaseTaxAmountRefunded(10);
$orderItem->setGwTaxAmountRefunded(10);
/** @var \Magento\Sales\Model\Order $order */
$order = $objectManager->create(\Magento\Sales\Model\Order::class);
$order->setIncrementId('100000001')->setState(\Magento\Sales\Model\Order::STATE_PROCESSING)->setStatus(
    $order->getConfig()->getStateDefaultStatus(\Magento\Sales\Model\Order::STATE_PROCESSING)
)->setSubtotal(100)->setGrandTotal(100)->setBaseSubtotal(100)->setBaseGrandTotal(100)->setCustomerIsGuest(
    true
)->setCustomerEmail('customer@null.com')->setBillingAddress($billingAddress)->setShippingAddress(
    $shippingAddress
)->setStoreId($objectManager->get(\Magento\Store\Model\StoreManagerInterface::class)->getStore()->getId())->addItem(
    $orderItem
)->setPayment($payment);
$order->setGwId(10);
$order->setGwAllowGiftReceipt(10);
$order->setGwAddCard(10);
$order->setGwBasePrice(10);
$order->setGwPrice(10);
$order->setGwItemsBasePrice(10);
$order->setGwItemsPrice(10);
$order->setGwCardBasePrice(10);
$order->setGwCardPrice(10);
$order->setGwBaseTaxAmount(10);
$order->setGwTaxAmount(10);
$order->setGwItemsBaseTaxAmount(10);
$order->setGwItemsTaxAmount(10);
$order->setGwCardBaseTaxAmount(10);
$order->setGwCardTaxAmount(10);
$order->setGwBasePriceInclTax(10);
$order->setGwPriceInclTax(10);

$order->setGwItemsBasePriceInclTax(10);
$order->setGwItemsPriceInclTax(10);
$order->setGwCardBasePriceInclTax(10);
$order->setGwCardPriceInclTax(10);
$order->setGwBasePriceInvoiced(10);
$order->setGwPriceInvoiced(10);
$order->setGwItemsBasePriceInvoiced(10);
$order->setGwItemsPriceInvoiced(10);
$order->setGwCardBasePriceInvoiced(10);
$order->setGwCardPriceInvoiced(10);
$order->setGwBaseTaxAmountInvoiced(10);
$order->setGwTaxAmountInvoiced(10);
$order->setGwItemsBaseTaxInvoiced(10);
$order->setGwItemsTaxInvoiced(10);
$order->setGwCardBaseTaxInvoiced(10);
$order->setGwCardTaxInvoiced(10);
$order->setGwBasePriceRefunded(10);
$order->setGwPriceRefunded(10);
$order->setGwItemsBasePriceRefunded(10);
$order->setGwItemsPriceRefunded(10);
$order->setGwCardBasePriceRefunded(10);
$order->setGwCardPriceRefunded(10);
$order->setGwBaseTaxAmountRefunded(10);
$order->setGwTaxAmountRefunded(10);
$order->setGwItemsBaseTaxRefunded(10);
$order->setGwItemsTaxRefunded(10);
$order->setGwCardBaseTaxRefunded(10);
$order->setGwCardTaxRefunded(10);
$order->save();
/** @var \Magento\Sales\Model\Order\CreditmemoFactory $creditmemoFactory */
$creditmemoFactory = $objectManager->get(\Magento\Sales\Model\Order\CreditmemoFactory::class);
$creditmemo = $creditmemoFactory->createByOrder($order, $order->getData());
$creditmemo->setOrder($order);
$creditmemo->setState(Magento\Sales\Model\Order\Creditmemo::STATE_OPEN);
$creditmemo->setIncrementId('100000001');
$creditmemo->setGwBasePrice(10);
$creditmemo->setGwPrice(10);
$creditmemo->setGwItemsBasePrice(10);
$creditmemo->setGwItemsPrice(10);
$creditmemo->setGwCardBasePrice(10);
$creditmemo->setGwCardPrice(10);
$creditmemo->setGwBaseTaxAmount(10);
$creditmemo->setGwTaxAmount(10);
$creditmemo->setGwItemsBaseTaxAmount(10);
$creditmemo->setGwItemsTaxAmount(10);
$creditmemo->setGwCardBaseTaxAmount(10);
$creditmemo->setGwCardTaxAmount(10);
$creditmemo->save();
$orderService = \Magento\TestFramework\ObjectManager::getInstance()->create(\Magento\Sales\Api\InvoiceManagementInterface::class
);
$invoice = $orderService->prepareInvoice($order);
$invoice->setGwBasePrice(10);
$invoice->setGwPrice(10);
$invoice->setGwItemsBasePrice(10);
$invoice->setGwItemsPrice(10);
$invoice->setGwCardBasePrice(10);
$invoice->setGwCardPrice(10);
$invoice->setGwBaseTaxAmount(10);
$invoice->setGwTaxAmount(10);
$invoice->setGwItemsBaseTaxAmount(10);
$invoice->setGwItemsTaxAmount(10);
$invoice->setGwCardBaseTaxAmount(10);
$invoice->setGwCardTaxAmount(10);
$invoice->register();
$order = $invoice->getOrder();
$order->setIsInProcess(
    true
);
$transactionSave = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Framework\DB\Transaction::class
);
$transactionSave->addObject($invoice)->addObject($order)->save();
