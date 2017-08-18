<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdminGws\Test\Constraint;

use Magento\Catalog\Test\Page\Adminhtml\CatalogProductIndex;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\User\Test\Fixture\User;
use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Mtf\TestStep\TestStepFactory;
use Magento\Mtf\Fixture\FixtureFactory;

/**
 * Create 2 products in different websites, login with custom admin user
 * and verify that products are visible/not visible in grid according to AdminGws role settings.
 */
class AssertAdminGwsProducts extends AbstractConstraint
{
    /**
     * Asserts products visibility in products grid.
     *
     * @param TestStepFactory $testStepFactory
     * @param FixtureFactory $fixtureFactory
     * @param CatalogProductIndex $catalogProductIndex
     * @param User $customAdmin
     * @param CatalogProductSimple $product
     * @param string $visibleProduct
     * @return void
     */
    public function processAssert(
        TestStepFactory $testStepFactory,
        FixtureFactory $fixtureFactory,
        CatalogProductIndex $catalogProductIndex,
        User $customAdmin,
        CatalogProductSimple $product,
        $visibleProduct
    ) {
        $product->persist();
        $adminGwsRole = $customAdmin->getDataFieldConfig('role_id')['source']->getRole();
        $website = $adminGwsRole->getDataFieldConfig('gws_stores')['source']->getWebsites()[0];
        $visibleProduct = $fixtureFactory->createByCode(
            'catalogProductSimple',
            [
                'dataset' => $visibleProduct,
                'data' => [
                    'website_ids' => [
                        [
                            'websites' => [$website]
                        ]
                    ],
                ]
            ]
        );
        $visibleProduct->persist();
        $testStepFactory->create(
            \Magento\User\Test\TestStep\LoginUserOnBackendStep::class,
            ['user' => $customAdmin]
        )->run();
        $catalogProductIndex->open();
        \PHPUnit_Framework_Assert::assertFalse(
            $catalogProductIndex->getProductGrid()->isRowVisible(['sku' => $product->getSku()]),
            'Product ' . $product->getName() . ' is present in products grid.'
        );
        \PHPUnit_Framework_Assert::assertTrue(
            $catalogProductIndex->getProductGrid()->isRowVisible(['sku' => $visibleProduct->getSku()]),
            'Product ' . $visibleProduct->getName() . ' is absent in products grid.'
        );
    }

    /**
     * Return string representation of object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Products are visible in products grid according to AdminGws role settings.';
    }
}
