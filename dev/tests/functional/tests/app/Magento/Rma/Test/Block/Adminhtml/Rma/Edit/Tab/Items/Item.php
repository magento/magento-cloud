<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Test\Block\Adminhtml\Rma\Edit\Tab\Items;

/**
 * Item row form on edit rma backend page.
 */
class Item extends \Magento\Mtf\Block\Form
{
    /**
     * Fill item row form.
     *
     * @param array $fields
     * @return void
     */
    public function fillRow(array $fields)
    {
        $mapping = $this->dataMapping($fields);
        foreach ($mapping as $field) {
            $elementType = isset($field['input']) ? $field['input'] : 'input';
            $element = $this->_rootElement->find(
                $field['selector'] . ' ' . $elementType,
                $field['strategy'],
                $field['input']
            );

            if ($element->isVisible()) {
                $element->setValue($field['value']);
            }
        }
    }

    /**
     * Return item row data.
     *
     * @return array
     */
    public function getRowData()
    {
        $mapping = $this->dataMapping();
        $data = [];

        foreach ($mapping as $columnName => $locator) {
            $elementType = isset($locator['input']) ? $locator['input'] : 'input';
            $element = $this->_rootElement->find(
                $locator['selector'] . ' ' . $elementType,
                $locator['strategy'],
                $locator['input']
            );
            $value = null;

            if ($element->isVisible()) {
                $value = $element->getValue();
            } else {
                $value = $this->_rootElement->find($locator['selector'], $locator['strategy'])->getText();
            }

            $data[$columnName] = trim($value);
        }

        return $data;
    }
}
