<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\Constraint;

use Magento\GiftRegistry\Test\Fixture\GiftRegistry;
use Magento\GiftRegistry\Test\Page\GiftRegistryEdit;
use Magento\GiftRegistry\Test\Page\GiftRegistryIndex;
use Magento\Mtf\Constraint\AbstractAssertForm;

/**
 * Class AssertGiftRegistryForm
 * Assert that saved GiftRegistry Data matched existed
 */
class AssertGiftRegistryForm extends AbstractAssertForm
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Skipped fields for verify data
     *
     * @var array
     */
    protected $skippedFields = [
        'type_id',
        'event_date',
    ];

    /**
     * Assert that displayed Gift Registry data on edit page equals passed from fixture
     *
     * @param GiftRegistryIndex $giftRegistryIndex
     * @param GiftRegistryEdit $giftRegistryEdit
     * @param GiftRegistry $giftRegistry
     * @param GiftRegistry $giftRegistryOrigin [optional]
     * @return void
     */
    public function processAssert(
        GiftRegistryIndex $giftRegistryIndex,
        GiftRegistryEdit $giftRegistryEdit,
        GiftRegistry $giftRegistry,
        GiftRegistry $giftRegistryOrigin = null
    ) {
        $fixtureData = $giftRegistryOrigin != null
            ? array_merge($giftRegistryOrigin->getData(), $giftRegistry->getData())
            : $giftRegistry->getData();
        unset($fixtureData['type_id']);
        $giftRegistry = $this->objectManager->create(
            \Magento\GiftRegistry\Test\Fixture\GiftRegistry::class,
            ['data' => $fixtureData]
        );
        $giftRegistryIndex->open();
        $giftRegistryIndex->getGiftRegistryGrid()->eventAction($giftRegistry->getTitle(), 'Edit');
        $formData = $giftRegistryEdit->getCustomerEditForm()->getData($giftRegistry);
        $errors = $this->verifyData($giftRegistry->getData(), $formData);
        \PHPUnit_Framework_Assert::assertEmpty($errors, $errors);
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift registry data on edit page equals data from fixture.';
    }
}
