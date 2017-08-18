<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCard\Test\Constraint;

use Magento\Catalog\Test\Page\Product\CatalogProductView;
use Magento\GiftCard\Test\Fixture\GiftCardProduct;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Mtf\Constraint\AbstractAssertForm;

/**
 * Assert that amount, name, email, message data on front-end equals passed from fixture.
 *
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
class AssertGiftCardProductAddToCartForm extends AbstractAssertForm
{
    /**
     * Value for choose custom option
     */
    const CUSTOM_OPTION = 'custom';

    /**
     * Assert that displayed amount, "Sender Name", "Sender Email", "Recipient Name", "Recipient Email", "Message" data
     * on product page(front-end) equals passed from fixture.
     *
     * @param CatalogProductView $catalogProductView
     * @param GiftCardProduct $product
     * @param BrowserInterface $browser
     * @return void
     */
    public function processAssert(
        CatalogProductView $catalogProductView,
        GiftCardProduct $product,
        BrowserInterface $browser
    ) {
        $browser->open($_ENV['app_frontend_url'] . $product->getUrlKey() . '.html');

        $giftcardAmounts = $product->hasData('giftcard_amounts') ? $product->getGiftcardAmounts() : [];
        $amountForm = (1 == count($giftcardAmounts))
            ? [$catalogProductView->getViewBlock()->getPriceBlock()->getPrice()]
            : $catalogProductView->getGiftCardBlock()->getAmountValues();
        $amountFixture = [];

        foreach ($giftcardAmounts as $amount) {
            $amountFixture[] = $amount['value'];
        }

        if (!empty($amountFixture)
            && $product->hasData('allow_open_amount')
            && 'Yes' == $product->getAllowOpenAmount()
        ) {
            \PHPUnit_Framework_Assert::assertContains(
                self::CUSTOM_OPTION,
                $amountForm,
                'Amount data on product page(frontend) not equals to passed from fixture.'
                . 'On product page(frontend) cannot choose custom amount.'
            );

            $amountFixture[] = 'custom';
        }

        $errors = $this->verifyData($amountFixture, $amountForm, true, false);
        \PHPUnit_Framework_Assert::assertEmpty(
            $errors,
            $this->prepareErrors($errors, "Amount data on product page(frontend) not equals to passed from fixture:\n")
        );

        $errors = $this->verifyFields($catalogProductView, $product, $amountFixture);
        \PHPUnit_Framework_Assert::assertEmpty(
            $errors,
            "\nErrors fields: \n" . implode("\n", $errors)
        );
    }

    /**
     * Verify fields for "Add to cart" form
     *
     * @param CatalogProductView $catalogProductView
     * @param GiftCardProduct $product
     * @param array $amountFixture [optional]
     * @return array
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function verifyFields(
        CatalogProductView $catalogProductView,
        GiftCardProduct $product,
        array $amountFixture = []
    ) {
        $giftCard = $catalogProductView->getGiftCardBlock();
        $isAmountSelectVisible = $giftCard->isAmountSelectVisible();
        $isAmountInputVisible = $giftCard->isAmountInputVisible();
        $isAllowOpenAmount = $product->hasData('allow_open_amount') && 'Yes' == $product->getAllowOpenAmount();
        $isShowSelectAmount = $product->hasData('giftcard_amounts')
            && ($isAllowOpenAmount || 1 < count($product->getGiftcardAmounts()));
        $errors = [];

        // Prepare form
        if ($isAmountSelectVisible && $isAllowOpenAmount) {
            $giftCard->selectCustomAmount();
        }

        // Garbage errors
        if (!$isAmountSelectVisible && $isShowSelectAmount) {
            $errors[] = '- select amount is not displayed.';
        }
        if ($isAmountSelectVisible && !$isShowSelectAmount) {
            $errors[] = '- select amount is displayed.';
        }
        if (count($amountFixture) == 0 && $isAllowOpenAmount && !$isAmountInputVisible) {
            $errors[] = '- input amount is not displayed.';
        }
        if (!$isAllowOpenAmount && $isAmountInputVisible) {
            $errors[] = '- input amount is displayed.';
        }
        if (!$giftCard->isSenderNameVisible()) {
            $errors[] = '- "Sender Name" is not displayed.';
        }
        if (!$giftCard->isRecipientNameVisible()) {
            $errors[] = '- "Recipient Name" is not displayed';
        }
        if ('Physical' != $product->getGiftcardType()) {
            if (!$giftCard->isSenderEmailVisible()) {
                $errors[] = '- "Sender Email" is not displayed';
            }
            if (!$giftCard->isRecipientEmailVisible()) {
                $errors[] = '- "Recipient Email" is not displayed';
            }
        }
        if (!$giftCard->isMessageVisible()) {
            $errors[] = '- "Message" is not displayed';
        }

        return $errors;
    }

    /**
     * Text success verify amount data on product page(frontend)
     *
     * @return string
     */
    public function toString()
    {
        return 'Displayed amount data on product page(frontend) equals to passed from fixture.';
    }
}
