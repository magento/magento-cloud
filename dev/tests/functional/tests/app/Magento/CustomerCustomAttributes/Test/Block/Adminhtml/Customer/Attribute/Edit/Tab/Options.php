<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\Block\Adminhtml\Customer\Attribute\Edit\Tab;

use Magento\Mtf\Client\Locator;
use Magento\Mtf\Client\Element\SimpleElement;
use Magento\Backend\Test\Block\Widget\Tab;
use Magento\CustomerCustomAttributes\Test\Block\Adminhtml\Customer\Attribute\Edit\Tab\Options\Option;

/**
 * Manage Options form on New Customer Attribute Page.
 */
class Options extends Tab
{
    /**
     * 'Add Option' button.
     *
     * @var string
     */
    protected $addOption = '#add_new_option_button';

    /**
     * Target row for dragAndDrop options to.
     *
     * @var string
     */
    protected $targetElement = '//*[contains(@class,"ui-sortable")]/tr[%d]';

    /**
     * Options value locator.
     *
     * @var string
     */
    protected $optionSelector = '.input-text.required-option';

    /**
     * Locator of draggable column.
     *
     * @var string
     */
    protected $draggableColumn = './../../td[@class="col-draggable"]';

    /**
     * Selector for option row.
     *
     * @var string
     */
    protected $optionRowSelector = '//tbody[@data-role="options-container"]/tr[%d]';

    /**
     * Fill 'Options' tab & drag and drop options.
     *
     * @param array $fields
     * @param SimpleElement|null $element
     * @throws \Exception
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setFieldsData(array $fields, SimpleElement $element = null)
    {
        $fixtureOptions = isset($fields['option']['value']) ? $fields['option']['value'] : [];
        foreach ($fixtureOptions as $key => $option) {
            $row = $this->_rootElement->find(sprintf($this->optionRowSelector, $key + 1), Locator::SELECTOR_XPATH);
            if (!$row->isVisible()) {
                $this->_rootElement->find($this->addOption)->click();
            }
            if (isset($option['order'])) {
                unset($option['order']);
            }
            $this->blockFactory->create(
                Option::class,
                ['element' => $row]
            )->fillOptions($option);
        }

        $optionElements = $this->_rootElement->getElements($this->optionSelector, Locator::SELECTOR_CSS);

        $this->sortOptions($fixtureOptions, $optionElements);

        return $this;
    }

    /**
     * Sort options according to fixture order.
     *
     * @param array $fixtureOptions
     * @param array $optionElements
     * @throws \Exception
     * @return void
     */
    protected function sortOptions(array $fixtureOptions, array $optionElements)
    {
        foreach ($fixtureOptions as $fixtureOption) {
            if ($fixtureOption['order'] > count($optionElements)) {
                throw new \Exception("Order number of options from fixture is greater than form options number.");
            }
            $target = $this->_rootElement->find(
                sprintf($this->targetElement, $fixtureOption['order']),
                Locator::SELECTOR_XPATH
            );
            /** @var SimpleElement $optionElement */
            foreach ($optionElements as $optionElement) {
                if ($optionElement->getValue() === $fixtureOption['admin']) {
                    $optionElement->find($this->draggableColumn, Locator::SELECTOR_XPATH)->dragAndDrop($target);
                }
            }
        }
    }
}
