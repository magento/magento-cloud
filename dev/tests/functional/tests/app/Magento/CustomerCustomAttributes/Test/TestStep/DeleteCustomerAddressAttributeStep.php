<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\TestStep;

use Magento\CustomerCustomAttributes\Test\Page\Adminhtml\CustomerAddressAttributeIndex;
use Magento\CustomerCustomAttributes\Test\Page\Adminhtml\CustomerAddressAttributeNew;
use Magento\CustomerCustomAttributes\Test\Fixture\CustomerAddressAttribute;
use Magento\Mtf\TestStep\TestStepInterface;

/**
 * Delete Customer Address attribute on backend.
 */
class DeleteCustomerAddressAttributeStep implements TestStepInterface
{
    /**
     * Customer address attributes grid page.
     *
     * @var CustomerAddressAttributeIndex
     */
    protected $customerAttributeIndex;

    /**
     * Customer address attribute new and edit page.
     *
     * @var CustomerAddressAttributeNew
     */
    protected $customerAttributeNew;

    /**
     * Customer address attribute.
     *
     * @var CustomerAddressAttribute
     */
    protected $addressCustomAttribute;

    /**
     * @param CustomerAddressAttributeIndex $customerAttributeIndex
     * @param CustomerAddressAttributeNew $customerAttributeNew
     * @param CustomerAddressAttribute $addressCustomAttribute
     */
    public function __construct(
        CustomerAddressAttributeIndex $customerAttributeIndex,
        CustomerAddressAttributeNew $customerAttributeNew,
        CustomerAddressAttribute $addressCustomAttribute
    ) {
        $this->customerAttributeIndex = $customerAttributeIndex;
        $this->customerAttributeNew = $customerAttributeNew;
        $this->addressCustomAttribute = $addressCustomAttribute;
    }

    /**
     * Delete Customer Address Attribute on backend.
     *
     * @return void
     */
    public function run()
    {
        if ($this->addressCustomAttribute === null) {
            return;
        }

        $this->customerAttributeIndex->open();
        $grid = $this->customerAttributeIndex->getCustomerAddressAttributesGrid();
        $grid->resetFilter();
        $grid->searchAndOpen([
            'attribute_code' => $this->addressCustomAttribute->getAttributeCode()
        ]);
        $this->customerAttributeNew->getFormPageActions()->delete();
        $this->customerAttributeNew->getModalBlock()->acceptAlert();
    }
}
