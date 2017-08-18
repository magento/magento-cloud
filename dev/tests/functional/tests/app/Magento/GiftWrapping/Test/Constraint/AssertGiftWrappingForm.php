<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftWrapping\Test\Constraint;

use Magento\GiftWrapping\Test\Fixture\GiftWrapping;
use Magento\GiftWrapping\Test\Page\Adminhtml\GiftWrappingIndex;
use Magento\GiftWrapping\Test\Page\Adminhtml\GiftWrappingNew;
use Magento\Mtf\Constraint\AbstractAssertForm;

/**
 * Class AssertGiftWrappingForm
 */
class AssertGiftWrappingForm extends AbstractAssertForm
{
    /**
     * Skipped fields while verifying
     *
     * @var array
     */
    protected $skippedFields = [
        'wrapping_id',
    ];

    /**
     * Assert that Gift Wrapping form was filled correctly
     *
     * @param GiftWrappingIndex $giftWrappingIndexPage
     * @param GiftWrappingNew $giftWrappingNewPage
     * @param GiftWrapping $giftWrapping
     * @param string $status
     * @param GiftWrapping $initialGiftWrapping
     * @return void
     */
    public function processAssert(
        GiftWrappingIndex $giftWrappingIndexPage,
        GiftWrappingNew $giftWrappingNewPage,
        GiftWrapping $giftWrapping,
        $status = '-',
        GiftWrapping $initialGiftWrapping = null
    ) {
        $data = ($initialGiftWrapping !== null)
            ? array_merge($initialGiftWrapping->getData(), $giftWrapping->getData())
            : $giftWrapping->getData();
        $data['base_price'] = number_format($data['base_price'], 2);
        $data['status'] = $status === '-' ? $data['status'] : $status;
        $filter = ['design' => $data['design']];
        $giftWrappingIndexPage->open();
        $giftWrappingIndexPage->getGiftWrappingGrid()->searchAndOpen($filter);
        $formData = $giftWrappingNewPage->getGiftWrappingForm()->getData();
        $errors = $this->verifyData($data, $formData);
        \PHPUnit_Framework_Assert::assertEmpty($errors, $errors);
    }

    /**
     * Text that form was filled correctly
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift Wrapping form was filled correctly.';
    }
}
