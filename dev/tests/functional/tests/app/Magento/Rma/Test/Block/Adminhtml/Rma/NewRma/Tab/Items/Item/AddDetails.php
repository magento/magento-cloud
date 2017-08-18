<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Test\Block\Adminhtml\Rma\NewRma\Tab\Items\Item;

use Magento\Mtf\Block\Form;
use Magento\Rma\Test\Fixture\RmaAttribute;

/**
 * Add details to item
 */
class AddDetails extends Form
{
    /**
     * Button "Add Details".
     *
     * @var string
     */
    protected $buttonAddDetails = '.col-actions a:last-of-type span';

    /**
     * Modal slide Rma item details.
     *
     * @var string
     */
    protected $rmaItemDetailsModal = '.rma_form_rma_item_details_modal';

    /**
     * Button "OK" on dialog box.
     *
     * @var string
     */
    protected $buttonConfirm = 'button.action-primary';

    /**
     * Mapping codes for frontend input types.
     *
     * @var array
     */
    private $frontendInputMappingData = [
        'Text Field' => 'text',
        'Text Area' => 'textarea',
        'Dropdown' => 'select',
        'Yes/No' => 'boolean',
        'File (attachment)'  => 'file',
        'Image File' => 'media_image',
    ];

    /**
     * Fill values of additional attributes.
     *
     * @param array $attributes
     * @return void
     */
    public function fillDetails(array $attributes)
    {
        $this->openAddDetailsModal();
        $this->updateDataMapping($attributes);
        $fields = $this->prepareData($attributes);
        $this->_fill($this->dataMapping($fields));

        $this->_rootElement->find($this->buttonConfirm)->click();
        $this->waitForElementNotVisible($this->buttonConfirm);
    }

    /**
     * Open Rma item details dialog.
     *
     * @return void
     */
    private function openAddDetailsModal()
    {
        $this->_rootElement->find($this->buttonAddDetails)->click();
        $this->waitForElementVisible($this->rmaItemDetailsModal);
    }

    /**
     * Add data mapping based on selected attributes
     *
     * @param array $attributes
     * @return void
     */
    private function updateDataMapping(array $attributes = [])
    {
        $mapping = [];
        /** @var RmaAttribute $attribute */
        foreach ($attributes as $attribute) {
            $mapping[$attribute->getAttributeCode()] = [
                'selector'  => '[id$="' . $attribute->getAttributeCode() . '"]',
                'input' => $this->getElementCodeByFrontendInputType($attribute->getFrontendInput()),
            ];
        }
        $this->setMapping($mapping);
    }

    /**
     * Prepare fields data based on selected attributes
     *
     * @param array $attributes
     * @return array
     */
    private function prepareData(array $attributes = [])
    {
        $additionalFields = [];
        /** @var RmaAttribute $attribute */
        foreach ($attributes as $attribute) {
            $defaultValue = $attribute->getData('value');
            if ($defaultValue) {
                $additionalFields[$attribute->getAttributeCode()] = $defaultValue;
            };
        }

        return $additionalFields;
    }

    /**
     * Returns input code for frontend type of element
     *
     * @param string $input
     * @return string|null
     */
    private function getElementCodeByFrontendInputType($input)
    {
        return isset($this->frontendInputMappingData[$input])
            ? $this->frontendInputMappingData[$input]
            : null;
    }
}
