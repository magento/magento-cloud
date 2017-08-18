<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\TestStep;

use Magento\CustomerCustomAttributes\Test\Fixture\CustomerAddressAttribute;
use Magento\CustomerCustomAttributes\Test\Page\Adminhtml\CustomerAddressAttributeEdit;
use Magento\CustomerCustomAttributes\Test\Page\Adminhtml\CustomerAddressAttributeIndex;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Mtf\TestStep\TestStepInterface;

/**
 * Create customer custom attribute step.
 */
class DeleteCustomerAddressAttributeStep implements TestStepInterface
{
    /**
     * Customer Custom Attribute.
     *
     * @var CustomerAddressAttribute
     */
    private $customAttribute;

    /**
     * Customer address attribute index page.
     *
     * @var CustomerAddressAttributeIndex
     */
    private $customerAddressAttributeIndex;

    /**
     * Customer attribute edit page.
     *
     * @var CustomerAddressAttributeEdit
     */
    private $customerAddressAttributeEdit;

    /**
     * @constructor
     * @param CustomerAddressAttribute $customAttribute
     * @param CustomerAddressAttributeIndex $customerAddressAttributeIndex
     * @param CustomerAddressAttributeEdit $customerAddressAttributeEdit
     */
    public function __construct(
        CustomerAddressAttribute $customAttribute,
        CustomerAddressAttributeIndex $customerAddressAttributeIndex,
        CustomerAddressAttributeEdit $customerAddressAttributeEdit
    ) {
        $this->customAttribute = $customAttribute;
        $this->customerAddressAttributeIndex = $customerAddressAttributeIndex;
        $this->customerAddressAttributeEdit = $customerAddressAttributeEdit;
    }

    /**
     * Create customer account.
     *
     * @return void
     */
    public function run()
    {
        $this->customerAddressAttributeIndex->open();
        $this->customerAddressAttributeIndex->getCustomerAddressAttributesGrid()
            ->searchAndOpen(['attribute_code' => $this->customAttribute->getAttributeCode()]);
        $this->customerAddressAttributeEdit->getFormPageActions()->delete();
    }
}
