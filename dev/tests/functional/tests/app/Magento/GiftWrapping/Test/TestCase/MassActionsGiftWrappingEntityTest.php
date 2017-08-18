<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftWrapping\Test\TestCase;

use Magento\GiftWrapping\Test\Fixture\GiftWrapping;
use Magento\GiftWrapping\Test\Page\Adminhtml\GiftWrappingIndex;
use Magento\GiftWrapping\Test\Page\Adminhtml\GiftWrappingNew;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestCase\Injectable;

/**
 * Test Creation for MassActionsGiftWrappingEntity
 *
 * Test Flow:
 * Preconditions:
 * 1. Gift Wrapping entities should be created according to each row in DS
 *
 * Steps:
 * 1. Login as admin to backend
 * 2. Navigate to Stores > Other Settings > Gift Wrapping
 * 3. Select gift wrappers according to DS
 * 4. Select a mass action according to DS and execute any depended operation if any
 * 5. Click 'Submit' button
 * 6. Perform all asserts
 *
 * @group Gift_Wrapping
 * @ZephyrId MAGETWO-27896
 */
class MassActionsGiftWrappingEntityTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    const STABLE = 'no';
    const SEVERITY = 'S3';
    /* end tags */

    /**
     * Gift Wrapping grid page
     *
     * @var GiftWrappingIndex
     */
    protected $giftWrappingIndexPage;

    /**
     * Gift Wrapping new/edit page
     *
     * @var GiftWrappingNew
     */
    protected $giftWrappingNewPage;

    /**
     * Fixture factory
     *
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * Injection data
     *
     * @param GiftWrappingIndex $giftWrappingIndexPage
     * @param GiftWrappingNew $giftWrappingNewPage
     * @param FixtureFactory $fixtureFactory
     * @return void
     */
    public function __inject(
        GiftWrappingIndex $giftWrappingIndexPage,
        GiftWrappingNew $giftWrappingNewPage,
        FixtureFactory $fixtureFactory
    ) {
        $this->giftWrappingIndexPage = $giftWrappingIndexPage;
        $this->giftWrappingNewPage = $giftWrappingNewPage;
        $this->fixtureFactory = $fixtureFactory;
    }

    /**
     * Mass actions for Gift Wrapping entity test
     *
     * @param string $giftWrappings
     * @param string $giftWrappingsIndexToSelect
     * @param string $action
     * @param string $status
     * @param string $giftWrappingsIndexToStay
     * @return array
     */
    public function test($giftWrappings, $giftWrappingsIndexToSelect, $action, $status, $giftWrappingsIndexToStay)
    {
        // Preconditions
        $this->objectManager->create(
            \Magento\Config\Test\TestStep\SetupConfigurationStep::class,
            ['configData' => 'enable_gift_message']
        )->run();
        $giftWrappingsInitial = explode(",", $giftWrappings);
        $giftWrappings = $this->createGiftWrappings($giftWrappingsInitial);

        // Steps
        $giftWrappingsIndexToSelect = explode(",", $giftWrappingsIndexToSelect);
        $giftWrappingsToSelect = $this->prepareGiftWrappingsToSelect($giftWrappingsIndexToSelect, $giftWrappings);
        $giftWrappingsToModify = $this->prepareGiftWrappingsToModify($giftWrappingsIndexToSelect, $giftWrappings);
        $giftWrappingsIndexToStay = explode(",", $giftWrappingsIndexToStay);
        $giftWrappingsToStay = $this->prepareGiftWrappingsToStay($giftWrappingsIndexToStay, $giftWrappings);
        $this->giftWrappingIndexPage->open();
        $this->giftWrappingIndexPage->getGiftWrappingGrid()->massaction(
            $giftWrappingsToSelect,
            [$action => $status],
            ($action == 'Delete' ? true : false)
        );

        return [
            'giftWrapping' => $giftWrappingsToModify,
            'giftWrappingsToStay' => $giftWrappingsToStay,
        ];
    }

    /**
     * Create Gift Wrappings
     *
     * @param array $giftWrappingsInitial
     * @return array
     */
    protected function createGiftWrappings(array $giftWrappingsInitial)
    {
        $giftWrappings = [];
        foreach ($giftWrappingsInitial as $giftWrappingInitial) {
            $giftWrappingInitial = $this->fixtureFactory->createByCode(
                'giftWrapping',
                ['dataset' => $giftWrappingInitial]
            );
            $giftWrappingInitial->persist();
            $giftWrappings[] = $giftWrappingInitial;
        }

        return $giftWrappings;
    }

    /**
     * Prepare Gift Wrapping names to select in mass action
     *
     * @param array $giftWrappingsIndexToSelect
     * @param array $giftWrappings
     * @return array
     */
    protected function prepareGiftWrappingsToSelect(array $giftWrappingsIndexToSelect, array $giftWrappings)
    {
        $giftWrappingsToSelect = [];
        foreach ($giftWrappingsIndexToSelect as $giftWrappingIndex) {
            $giftWrappingsToSelect[] = ['design' => $giftWrappings[$giftWrappingIndex-1]->getDesign()];
        }

        return $giftWrappingsToSelect;
    }

    /**
     * Prepare array of Gift Wrapping fixtures to be modified by mass action
     *
     * @param array $giftWrappingsIndexToSelect
     * @param array $giftWrappings
     * @return array
     */
    protected function prepareGiftWrappingsToModify(array $giftWrappingsIndexToSelect, array $giftWrappings)
    {
        $giftWrappingsToModify = [];
        foreach ($giftWrappingsIndexToSelect as $giftWrappingIndex) {
            $giftWrappingsToModify[] = $giftWrappings[$giftWrappingIndex-1];
        }

        return $giftWrappingsToModify;
    }

    /**
     * Prepare Gift wrappings that stayed after mass action
     *
     * @param array $giftWrappingsIndexToStay
     * @param array $giftWrappings
     * @return array
     */
    protected function prepareGiftWrappingsToStay(array $giftWrappingsIndexToStay, array $giftWrappings)
    {
        $giftWrappingsToStay = [];
        foreach ($giftWrappingsIndexToStay as $giftWrappingIndex) {
            $giftWrappingsToStay[] = $giftWrappingIndex !== '-' ? $giftWrappings[$giftWrappingIndex-1] : null;
        }

        return $giftWrappingsToStay;
    }
}
