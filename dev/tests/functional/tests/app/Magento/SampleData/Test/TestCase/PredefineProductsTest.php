<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleData\Test\TestCase;

use Magento\Catalog\Test\Fixture\Category;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestCase\Injectable;

/**
 * Class PredefineProductsTest
 * Predefine products
 *
 * @ticketId MTA-404
 */
class PredefineProductsTest extends Injectable
{
    /**
     * Sub category for product
     *
     * @var array
     */
    protected $subCategory = [
        'with_all_custom_option' => 'With Custom Options',
        'bundle_dynamic_product' => 'Dynamic',
        'bundle_fixed_product' => 'Fixed',
    ];

    /**
     * Predefine products
     *
     * @param FixtureFactory $fixtureFactory
     * @param string $products
     * @param Category $category
     * @return void
     */
    public function test(FixtureFactory $fixtureFactory, $products, Category $category)
    {
        $productsData = explode(', ', $products);
        $rootCategoryId = null;
        foreach ($productsData as $value) {
            $product = explode('::', $value);
            if (isset($this->subCategory[$product[1]])) {
                if (!$category->hasData('id')) {
                    $category->persist();
                    $rootCategoryId = $category->getId();
                }
                $category = $fixtureFactory->createByCode(
                    'category',
                    [
                        'dataset' => 'default_anchor_subcategory',
                        'data' => [
                                'name' => $this->subCategory[$product[1]],
                                'parent_id' => $rootCategoryId,
                            ]
                    ]
                );
            }
            $product = $fixtureFactory->createByCode(
                $product[0],
                [
                    'dataset' => $product[1],
                    'data' => [
                        'category_ids' => [
                            'category' => $category,
                        ],
                    ]
                ]
            );
            $product->persist();
            if (!$rootCategoryId) {
                $rootCategoryId = $category->getId();
            }
        }
    }
}
