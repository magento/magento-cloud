<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\Constraint;

use Magento\Customer\Test\Page\Adminhtml\CustomerIndexEdit;
use Magento\CustomerCustomAttributes\Test\Fixture\CustomerCustomAttribute;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Cms\Test\Page\CmsIndex;
use Magento\Customer\Test\Page\CustomerAccountCreate;
use Magento\Customer\Test\Page\Adminhtml\CustomerIndex;

/**
 * Assert that customer can upload file during registration on storefront.
 */
class AssertCustomerCanUploadFileOnRegistration extends AbstractConstraint
{
    /**
     * Assert that customer can upload file during registration on storefront.
     *
     * @param FixtureFactory $fixtureFactory
     * @param CustomerIndexEdit $customerIndexEdit
     * @param CustomerCustomAttribute $customerAttribute
     * @param string $filePathToUpload
     * @param CmsIndex $cmsIndex
     * @param CustomerAccountCreate $customerAccountCreate
     * @param CustomerIndex $customerIndexPage
     * @return void
     */
    public function processAssert(
        FixtureFactory $fixtureFactory,
        CustomerIndexEdit $customerIndexEdit,
        CustomerCustomAttribute $customerAttribute,
        CmsIndex $cmsIndex,
        CustomerAccountCreate $customerAccountCreate,
        CustomerIndex $customerIndexPage,
        $filePathToUpload
    ) {
        $customer = $fixtureFactory->createByCode(
            'customer',
            [
                'dataset' => 'default',
                'data' => ['custom_attribute' => ['value' => $filePathToUpload, 'attribute' => $customerAttribute]]
            ]
        );

        $cmsIndex->open();
        $cmsIndex->getLinksBlock()->openLink('Create an Account');

        $customerAccountCreate->getCustomerAttributesRegisterForm()->registerCustomer($customer);
        $customerIndexPage->open();
        $customerIndexPage->getCustomerGridBlock()->searchAndOpen(['email' => $customer->getEmail()]);

        \PHPUnit_Framework_Assert::assertContains(
            pathinfo($customer->getCustomAttribute()['value'])['filename'],
            $customerIndexEdit->getCustomerForm()->getDataCustomer($customer)['customer']['custom_attribute'],
            'Uploaded file name wasn\'t saved.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Customer File Attribute can be uploaded by customer during registration.';
    }
}
