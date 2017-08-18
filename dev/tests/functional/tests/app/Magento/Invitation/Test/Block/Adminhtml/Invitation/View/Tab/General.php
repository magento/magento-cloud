<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Invitation\Test\Block\Adminhtml\Invitation\View\Tab;

use Magento\Backend\Test\Block\Widget\Tab;
use Magento\Mtf\Client\Element\SimpleElement;

/**
 * Tab for general invitation information.
 */
class General extends Tab
{
    /**
     * Get data of tab
     *
     * @param array|null $fields
     * @param SimpleElement|null $element
     * @return array
     */
    public function getFieldsData($fields = null, SimpleElement $element = null)
    {
        $data = $this->dataMapping($fields);
        $dataFields = [];
        $context = ($element === null) ? $this->_rootElement : $element;
        foreach ($data as $key => $field) {
            $element = $this->getElement($context, $field);
            if ($this->mappingMode || $element->isVisible()) {
                $dataFields[$key] = $element->getText();
            }
        }

        return $dataFields;
    }
}
