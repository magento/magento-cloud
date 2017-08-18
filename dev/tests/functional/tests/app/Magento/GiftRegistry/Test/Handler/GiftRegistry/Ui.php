<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\Handler\GiftRegistry;

use Magento\Cms\Test\Page\CmsIndex;
use Magento\Customer\Test\Page\CustomerAccountIndex;
use Magento\GiftRegistry\Test\Page\GiftRegistryAddSelect;
use Magento\GiftRegistry\Test\Page\GiftRegistryEdit;
use Magento\GiftRegistry\Test\Page\GiftRegistryIndex;
use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Handler\Ui as AbstractUi;

/**
 * Class Ui
 * Ui handler for creating gift registry
 */
class Ui extends AbstractUi implements GiftRegistryInterface
{
    /**
     * Cms index page
     *
     * @var CmsIndex
     */
    protected $cmsIndex;

    /**
     * Customer account index page
     *
     * @var CustomerAccountIndex
     */
    protected $customerAccountIndex;

    /**
     * Gift Registry index page
     *
     * @var GiftRegistryIndex
     */
    protected $giftRegistryIndex;

    /**
     * Gift Registry select type page
     *
     * @var GiftRegistryAddSelect
     */
    protected $giftRegistryAddSelect;

    /**
     * Gift Registry edit type page
     *
     * @var GiftRegistryEdit
     */
    protected $giftRegistryEdit;

    /**
     * @constructor
     * @param CmsIndex $cmsIndex
     * @param CustomerAccountIndex $customerAccountIndex
     * @param GiftRegistryIndex $giftRegistryIndex
     * @param GiftRegistryAddSelect $giftRegistryAddSelect
     * @param GiftRegistryEdit $giftRegistryEdit
     */
    public function __construct(
        CmsIndex $cmsIndex,
        CustomerAccountIndex $customerAccountIndex,
        GiftRegistryIndex $giftRegistryIndex,
        GiftRegistryAddSelect $giftRegistryAddSelect,
        GiftRegistryEdit $giftRegistryEdit
    ) {
        $this->cmsIndex = $cmsIndex;
        $this->customerAccountIndex = $customerAccountIndex;
        $this->giftRegistryIndex = $giftRegistryIndex;
        $this->giftRegistryAddSelect = $giftRegistryAddSelect;
        $this->giftRegistryEdit = $giftRegistryEdit;
    }

    /**
     * Create gift registry
     *
     * @param FixtureInterface $fixture [optional]
     * @return mixed|void
     */
    public function persist(FixtureInterface $fixture = null)
    {
        $this->cmsIndex->getLinksBlock()->openLink("My Account");
        $this->customerAccountIndex->getAccountMenuBlock()->openMenuItem("Gift Registry");
        $this->giftRegistryIndex->getActionsToolbar()->addNew();
        $this->giftRegistryAddSelect->getGiftRegistryTypeBlock()->selectGiftRegistryType($fixture->getTypeId());
        $this->giftRegistryEdit->getCustomerEditForm()->fill($fixture);
        $this->giftRegistryEdit->getActionsToolbarBlock()->save();
        $this->giftRegistryIndex->getMessagesBlock()->waitSuccessMessage();
    }
}
