<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdminGws\Test\Constraint;

use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestStep\TestStepFactory;
use Magento\User\Test\Fixture\User;
use Magento\Catalog\Test\Page\Adminhtml\CatalogCategoryIndex;
use Magento\Catalog\Test\Page\Adminhtml\CatalogCategoryEdit;

/**
 * Assert that user cannot access restricted categories.
 */
class AssertAdminGwsCategoryAccess extends AbstractConstraint
{
    /**
     * Assert that admin user without restricted privileges cannot access restricted categories.
     *
     * @param CatalogCategoryIndex $categoryIndex
     * @param CatalogCategoryEdit $categoryEdit
     * @param TestStepFactory $testStepFactory
     * @param FixtureFactory $fixtureFactory
     * @param User $customAdmin
     * @return void
     */
    public function processAssert(
        CatalogCategoryIndex $categoryIndex,
        CatalogCategoryEdit $categoryEdit,
        TestStepFactory $testStepFactory,
        FixtureFactory $fixtureFactory,
        User $customAdmin
    ) {
        $category = $fixtureFactory->createByCode('category', ['dataset' => 'root_subcategory']);
        $category->persist();
        $rootCategory = $category->getDataFieldConfig('parent_id')['source']->getParentCategory();

        $role = $customAdmin->getDataFieldConfig('role_id')['source']->getRole();
        $storeGroup = $fixtureFactory->createByCode(
            'storeGroup',
            [
                'dataset' => 'custom',
                'data' => [
                    'website_id' => [
                        'fixture' => $role->getDataFieldConfig('gws_websites')['source']->getWebsites()[0]
                    ],
                    'root_category_id' => [
                        'fixture' => $rootCategory
                    ],
                ],
            ]
        );
        $storeGroup->persist();

        $testStepFactory->create(
            \Magento\User\Test\TestStep\LoginUserOnBackendStep::class,
            ['user' => $customAdmin]
        )->run();

        $categoryIndex->open();
        \PHPUnit_Framework_Assert::assertTrue(
            $categoryIndex->getTreeCategories()->isCategoryVisible($rootCategory),
            'Custom root category is missing in backend catalog category tree.'
        );
        \PHPUnit_Framework_Assert::assertTrue(
            $categoryIndex->getTreeCategories()->isCategoryVisible($category),
            'Custom subcategory is missing in backend catalog category tree.'
        );
        $defaultCategory = $fixtureFactory->createByCode('category', ['dataset' => 'default_category']);
        \PHPUnit_Framework_Assert::assertFalse(
            $categoryIndex->getTreeCategories()->isCategoryVisible($defaultCategory),
            'Default root category is displayed in backend catalog category tree.'
        );
        $categoryEdit->open(['id' => $defaultCategory->getId()]);
        \PHPUnit_Framework_Assert::assertEquals(
            $rootCategory->getName(),
            $categoryEdit->getEditForm()->getData($defaultCategory)['name'],
            'Default root category is available by direct link.'
        );
    }

    /**
     * Return string representation of object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Admin user without restricted privileges cannot access restricted categories.';
    }
}
