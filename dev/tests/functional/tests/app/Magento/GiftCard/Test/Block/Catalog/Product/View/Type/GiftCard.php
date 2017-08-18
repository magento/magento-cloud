<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCard\Test\Block\Catalog\Product\View\Type;

use Magento\Mtf\Block\Form;
use Magento\Mtf\Client\Locator;
use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Client\Element\SimpleElement;
use Magento\GiftCard\Test\Fixture\GiftCardProduct;

/**
 * Class GiftCard
 * Catalog gift card product info block.
 */
class GiftCard extends Form
{
    /**
     * Selector for price.
     *
     * @var string
     */
    protected $price = '.price-box .regular-price > .price';

    /**
     * Gift Card Amount field.
     *
     * @var string
     */
    protected $amountInput = '[name="custom_giftcard_amount"]';

    /**
     * Selector for giftcard amount.
     *
     * @var string
     */
    protected $amountSelect = '[name="giftcard_amount"]';

    /**
     * Selector for "Custom amount" option of select.
     *
     * @var string
     */
    protected $amountCustomOption = 'Other Amount...';

    /**
     * Gift Card Sender Name field.
     *
     * @var string
     */
    protected $senderName = '[name="giftcard_sender_name"]';

    /**
     * Gift Card Sender Email field.
     *
     * @var string
     */
    protected $senderEmail = '[name="giftcard_sender_email"]';

    /**
     * Gift Card Recipient Name field.
     *
     * @var string
     */
    protected $recipientName = '[name="giftcard_recipient_name"]';

    /**
     * Gift Card Recipient Email field.
     *
     * @var string
     */
    protected $recipientEmail = '[name="giftcard_recipient_email"]';

    /**
     * Selector for Gift card message.
     *
     * @var string
     */
    protected $message = '[name="giftcard_message"]';

    /**
     * Get amount values.
     *
     * @return array
     */
    public function getAmountValues()
    {
        $values = [];
        $giftcardAmount = $this->_rootElement->find($this->amountSelect, Locator::SELECTOR_CSS);
        $priceElement = $this->_rootElement->find($this->price);

        if (!$giftcardAmount->isVisible() && !$priceElement->isVisible()) {
            return $values;
        }

        // Return price if product has one amount
        if (!$giftcardAmount->isVisible()) {
            $price = $priceElement->getText();
            $values[] = floatval(preg_replace('/[^0-9.]/', '', $price));
            return $values;
        }

        /* Skip option #0("Choose amount...") */
        $options = $giftcardAmount->getElements('.//option', Locator::SELECTOR_XPATH);
        for ($i = 1, $length = count($options); $i < $length; $i++) {
            $values[] = $options[$i]->getValue();
        }
        return $values;
    }

    /**
     * Select "Custom amount" option.
     *
     * @return void
     */
    public function selectCustomAmount()
    {
        $amountSelect = $this->_rootElement->find($this->amountSelect, Locator::SELECTOR_CSS, 'select');
        $amountSelect->setValue($this->amountCustomOption);
    }

    /**
     * Verify that text field for Gift Card amount is present.
     *
     * @return bool
     */
    public function isAmountInputVisible()
    {
        return $this->_rootElement->find($this->amountInput)->isVisible();
    }

    /**
     * Verify that select of Gift Card amount is present.
     *
     * @return bool
     */
    public function isAmountSelectVisible()
    {
        return $this->_rootElement->find($this->amountSelect)->isVisible();
    }

    /**
     * Verify that text field for "Sender Name" is present.
     *
     * @return bool
     */
    public function isSenderNameVisible()
    {
        return $this->_rootElement->find($this->senderName)->isVisible();
    }

    /**
     * Verify that text field for "Sender Email" is present.
     *
     * @return bool
     */
    public function isSenderEmailVisible()
    {
        return $this->_rootElement->find($this->senderEmail)->isVisible();
    }

    /**
     * Verify that text field for "Recipient Name" is present.
     *
     * @return bool
     */
    public function isRecipientNameVisible()
    {
        return $this->_rootElement->find($this->recipientName)->isVisible();
    }

    /**
     * Verify that text field for "Recipient Email" is present.
     *
     * @return bool
     */
    public function isRecipientEmailVisible()
    {
        return $this->_rootElement->find($this->recipientEmail)->isVisible();
    }

    /**
     * Verify that text field for "Message" is present.
     *
     * @return bool
     */
    public function isMessageVisible()
    {
        return $this->_rootElement->find($this->message)->isVisible();
    }

    /**
     * Verifying that Gift Card fields on fronted correspond to Gift Card type:
     * Virtual and Combined - Sender Name, Sender Email, Recipient Name, Recipient Email.
     *
     * @return bool
     */
    public function isGiftCardNotPhysical()
    {
        return $this->isSenderNameVisible()
            && $this->isSenderEmailVisible()
            && $this->isRecipientNameVisible()
            && $this->isRecipientEmailVisible();
    }

    /**
     * Fill the GiftCard options.
     *
     * @param FixtureInterface $product
     * @param SimpleElement|null $element
     * @return $this
     */
    public function fill(FixtureInterface $product, SimpleElement $element = null)
    {
        /** @var GiftCardProduct $product */
        $giftcardAmounts = $product->getGiftcardAmounts();
        $checkoutData = $product->getCheckoutData();
        if (isset($checkoutData['options']['giftcard_options'])) {
            $checkoutGiftCardOptions = $checkoutData['options']['giftcard_options'];

            if (!$product->getData('allow_open_amount') || $product->getData('allow_open_amount') != 'Yes') {
                // Replace option key to value
                $amountOptionKey = str_replace('option_key_', '', $checkoutGiftCardOptions['giftcard_amount']);
                $checkoutGiftCardOptions['giftcard_amount'] = $giftcardAmounts[$amountOptionKey]['value'];
            }

            $mapping = $this->dataMapping($checkoutGiftCardOptions);
            $this->_fill($mapping, $element);
        }

        return $this;
    }
}
