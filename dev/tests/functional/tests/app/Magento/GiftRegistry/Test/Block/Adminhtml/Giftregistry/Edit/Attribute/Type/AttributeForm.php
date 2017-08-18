<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\Block\Adminhtml\Giftregistry\Edit\Attribute\Type;

use Magento\Mtf\Block\Form;
use Magento\Mtf\Client\Element\SimpleElement;
use Magento\Mtf\Client\Element;

/**
 * Class AttributeForm
 * Responds for filling attribute form
 */
abstract class AttributeForm extends Form
{
    /**
     * Filling attribute form
     *
     * @param array $attributeFields
     * @param SimpleElement $element
     * @return void
     */
    public function fillForm(array $attributeFields, SimpleElement $element = null)
    {
        $element = $element === null ? $this->_rootElement : $element;
        $mapping = $this->dataMapping($attributeFields);
        $this->_fill($mapping, $element);
    }

    /**
     * Getting options data form on the product form
     *
     * @param array $fields
     * @param SimpleElement $element
     * @return $this
     */
    public function getDataOptions(array $fields = null, SimpleElement $element = null)
    {
        $element = $element === null ? $this->_rootElement : $element;
        $mapping = $this->dataMapping($fields);

        return $this->_getData($mapping, $element);
    }
}
