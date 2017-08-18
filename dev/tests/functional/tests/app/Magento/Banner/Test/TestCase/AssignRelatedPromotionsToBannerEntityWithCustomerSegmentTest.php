<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Banner\Test\TestCase;

use Magento\Mtf\ObjectManager;
use Magento\Mtf\TestCase\Injectable;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Cms\Test\Fixture\CmsPage;
use Magento\Banner\Test\Fixture\BannerWidget;
use Magento\Banner\Test\Fixture\Banner;
use Magento\Banner\Test\Page\Adminhtml\BannerNew;
use Magento\Banner\Test\Page\Adminhtml\BannerIndex;
use Magento\Customer\Test\Fixture\Customer;
use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\SalesRule\Test\Fixture\SalesRule;
use Magento\CustomerSegment\Test\Fixture\CustomerSegment;

/**
 * Preconditions:
 * 1. Create customer.
 * 2. Create CustomerSegment.
 * 3. Create CMS Page.
 * 4. Create widget type - Banner Rotator.
 * 5. Create Cart Price Rule.
 * 6. Create Catalog Price Rule.
 * 7. Create banner.
 *
 * Steps:
 * 1. Open Backend.
 * 2. Go to Content->Banners.
 * 3. Open created banner from preconditions.
 * 4. Related Cart and Catalog Rules to banner.
 * 5. Perform all assertions.
 *
 * @group Banner
 * @ZephyrId MAGETWO-27159
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AssignRelatedPromotionsToBannerEntityWithCustomerSegmentTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    const TEST_TYPE = 'extended_acceptance_test';
    const SEVERITY = 'S1';
    const STABLE = 'no';
    /* end tags */

    /**
     * BannerIndex page.
     *
     * @var BannerIndex
     */
    protected $bannerIndex;

    /**
     * BannerNew page.
     *
     * @var BannerNew
     */
    protected $bannerNew;

    /**
     * Fixture Factory.
     *
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * Inject pages.
     *
     * @param BannerIndex $bannerIndex
     * @param BannerNew $bannerNew
     * @param FixtureFactory $fixtureFactory
     * @return void
     */
    public function __inject(
        BannerIndex $bannerIndex,
        BannerNew $bannerNew,
        FixtureFactory $fixtureFactory
    ) {
        $this->bannerIndex = $bannerIndex;
        $this->bannerNew = $bannerNew;
        $this->fixtureFactory = $fixtureFactory;
    }

    /**
     * Creation for assign Related Cart and Catalog Rules to BannerEntity test.
     *
     * @param Banner $banner
     * @param Customer|string $customer
     * @param CustomerSegment|string $customerSegment
     * @param CmsPage $cmsPage
     * @param string $catalogPriceRule
     * @param string $cartPriceRule
     * @param string $widget
     * @return array
     */
    public function test(
        Banner $banner,
        CmsPage $cmsPage,
        $catalogPriceRule,
        $cartPriceRule,
        $customer,
        $customerSegment,
        $widget
    ) {
        // Preconditions
        $customer = $this->createCustomer($customer);
        $customerSegment = $this->createCustomerSegment($customerSegment);
        $cmsPage->persist();
        $product = $this->createProduct();
        $banner = $this->createBanner($customerSegment, $banner);
        $this->createWidget($widget, $banner);

        $rules = $this->createRules($cartPriceRule, $catalogPriceRule);
        $filter = ['banner' => $banner->getName()];

        // Steps
        $this->bannerIndex->open();
        $this->bannerIndex->getGrid()->searchAndOpen($filter);
        $this->bannerNew->getBannerForm()->openTab('related_promotions');
        /** @var \Magento\Banner\Test\Block\Adminhtml\Banner\Edit\Tab\RelatedPromotions $tab */
        $tab = $this->bannerNew->getBannerForm()->getTab('related_promotions');
        if (!empty($rules['banner_sales_rules'])) {
            $tab->getCartPriceRulesGrid()->searchAndSelect(['id' => $rules['banner_sales_rules']]);
        }
        if (!empty($rules['banner_catalog_rules'])) {
            $tab->getCatalogPriceRulesGrid()->searchAndSelect(['id' => $rules['banner_catalog_rules']]);
        }
        $this->bannerNew->getPageMainActions()->save();

        return [
            'product' => $product,
            'category' => $product->getDataFieldConfig('category_ids')['source']->getCategories()[0],
            'banner' => $banner,
            'customer' => $customer,
            'customerSegment' => $customerSegment,
        ];
    }

    /**
     * Create Cart and Catalog Rules.
     *
     * @param string $cartPriceRule
     * @param string $catalogPriceRule
     * @return array
     */
    protected function createRules($catalogPriceRule, $cartPriceRule)
    {
        $rules = [];
        if ($catalogPriceRule !== "-") {
            $catalogPriceRule = $this->fixtureFactory->createByCode('catalogRule', ['dataset' => $catalogPriceRule]);
            $catalogPriceRule->persist();
            $rules['banner_catalog_rules'] = $catalogPriceRule->getId();
        }
        if ($cartPriceRule !== "-") {
            $cartPriceRule = $this->fixtureFactory->createByCode('salesRule', ['dataset' => $cartPriceRule]);
            $cartPriceRule->persist();
            $rules['banner_sales_rules'] = $cartPriceRule->getRuleId();
        }

        return $rules;
    }

    /**
     * Create Customer.
     *
     * @param string $customer
     * @return Customer|null
     */
    protected function createCustomer($customer)
    {
        if ($customer !== '-') {
            $customer = $this->fixtureFactory->createByCode('customer', ['dataset' => $customer]);
            $customer->persist();

            return $customer;
        }

        return null;
    }

    /**
     * Create Customer Segment.
     *
     * @param string $customerSegment
     * @return CustomerSegment|null
     */
    protected function createCustomerSegment($customerSegment)
    {
        if ($customerSegment !== '-') {
            $customerSegment = $this->fixtureFactory->createByCode('customerSegment', ['dataset' => $customerSegment]);
            $customerSegment->persist();

            return $customerSegment;
        }

        return null;
    }

    /**
     * Create Product.
     *
     * @return CatalogProductSimple
     */
    protected function createProduct()
    {
        $product = $this->fixtureFactory->createByCode('catalogProductSimple', ['dataset' => 'product_with_category']);
        $product->persist();

        return $product;
    }

    /**
     * Create banner.
     *
     * @param Banner $banner
     * @param CustomerSegment|string $customerSegment
     * @return Banner
     */
    protected function createBanner($customerSegment, Banner $banner)
    {
        if ($customerSegment !== null) {
            $banner = $this->fixtureFactory->createByCode(
                'banner',
                [
                    'dataset' => 'default',
                    'data' => [
                        'customer_segment_ids' => [$customerSegment->getSegmentId()],
                    ]
                ]
            );
        }
        $banner->persist();

        return $banner;
    }

    /**
     * Create Widget.
     *
     * @param string $widget
     * @param Banner $banner
     * @return BannerWidget
     */
    protected function createWidget($widget, Banner $banner)
    {
        $widget = $this->fixtureFactory->create(
            \Magento\Banner\Test\Fixture\BannerWidget::class,
            [
                'dataset' => $widget,
                'data' => [
                    'parameters' => [
                        'entities' => [$banner],
                        'display_mode' => 'Specified Banners',
                    ],
                ]
            ]
        );
        $widget->persist();

        return $widget;
    }

    /**
     * Deleted cart price rules and catalog price rules.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->objectManager->create(\Magento\Widget\Test\TestStep\DeleteAllWidgetsStep::class)->run();
        $this->objectManager->create(\Magento\CatalogRule\Test\TestStep\DeleteAllCatalogRulesStep::class)->run();
        $this->objectManager->create(\Magento\SalesRule\Test\TestStep\DeleteAllSalesRuleStep::class)->run();
    }
}
