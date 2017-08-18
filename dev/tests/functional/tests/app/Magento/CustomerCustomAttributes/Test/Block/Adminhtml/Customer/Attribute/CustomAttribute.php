<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\Block\Adminhtml\Customer\Attribute;

use Magento\Mtf\Client\Element\SimpleElement;
use Magento\Mtf\Client\Locator;

/**
 * Catalog product custom attribute element.
 */
class CustomAttribute extends SimpleElement
{
    /**
     * Attribute input selector.
     *
     * @var string
     */
    private $inputSelector = 'input[name="customer[%s]"]';

    /**
     * Uploaded filename container selector.
     *
     * @var string
     */
    private $fileNameContainerSelector = "div.file-uploader-filename";

    /**
     * Set attribute value.
     *
     * @param array|string $data
     * @return void
     */
    public function setValue($data)
    {
        if (!isset($data['code']) || !isset($data['value'])) {
            return;
        }
        $this->eventManager->dispatchEvent(['set_value'], [__METHOD__, $this->getAbsoluteSelector()]);
        $this->find(
            sprintf($this->inputSelector, $data['code']),
            Locator::SELECTOR_CSS,
            'upload'
        )->setValue(MTF_TESTS_PATH . $data['value']);
    }

    /**
     * Get custom attribute value.
     *
     * @return string
     */
    public function getValue()
    {
        $this->eventManager->dispatchEvent(['get_value'], [__METHOD__, $this->getAbsoluteSelector()]);
        return $this->find($this->fileNameContainerSelector)->getText();
    }
}
