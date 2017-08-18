<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\Constraint;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Cms\Test\Page\CmsIndex;
use Magento\Customer\Test\Page\CustomerAccountIndex;
use Magento\GiftRegistry\Test\Fixture\GiftRegistry;
use Magento\GiftRegistry\Test\Page\GiftRegistryIndex;
use Magento\GiftRegistry\Test\Page\GiftRegistryItems;
use Magento\Mtf\Constraint\AbstractAssertForm;

/**
 * Class AssertGiftRegistryItemsForm
 * Assert that updated GiftRegistry items data matched existed
 */
class AssertGiftRegistryItemsForm extends AbstractAssertForm
{
    /**
     * Assert that displayed Gift Registry items data on edit page equals passed from fixture
     *
     * @param GiftRegistryItems $giftRegistryItems
     * @param CatalogProductSimple $product
     * @param GiftRegistry $giftRegistry
     * @param GiftRegistryIndex $giftRegistryIndex
     * @param CmsIndex $cmsIndex
     * @param CustomerAccountIndex $customerAccountIndex
     * @param array $updateOptions
     * @return void
     */
    public function processAssert(
        GiftRegistryItems $giftRegistryItems,
        CatalogProductSimple $product,
        GiftRegistry $giftRegistry,
        GiftRegistryIndex $giftRegistryIndex,
        CmsIndex $cmsIndex,
        CustomerAccountIndex $customerAccountIndex,
        $updateOptions
    ) {
        $cmsIndex->getLinksBlock()->openLink("My Account");
        $customerAccountIndex->getAccountMenuBlock()->openMenuItem('Gift Registry');
        $giftRegistryIndex->getGiftRegistryGrid()->eventAction($giftRegistry->getTitle(), 'Manage Items');
        $formData = $giftRegistryItems->getGiftRegistryItemsBlock()->getItemData($product);
        $errors = $this->verifyData($updateOptions, $formData);
        \PHPUnit_Framework_Assert::assertEmpty($errors, $errors);
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift registry items data on edit page equals data from fixture.';
    }
}
