<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Test\Block\Returns\History;

use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Client\Element\SimpleElement;

/**
 * Rma row in table.
 */
class RmaRow extends \Magento\Mtf\Block\Form
{
    /**
     * Locator for action "View Return".
     *
     * @var string
     */
    protected $actionView = '.view';

    /**
     * Get data of rma row.
     *
     * @param FixtureInterface|null $fixture
     * @param SimpleElement|null $element
     * @return array
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getData(FixtureInterface $fixture = null, SimpleElement $element = null)
    {
        $context = $element ? $element : $this->_rootElement;
        $mapping = $this->dataMapping();
        $data = [];

        foreach ($mapping as $name => $field) {
            $data[$name] = $context->find($field['selector'], $field['strategy'])->getText();
        }

        return $data;
    }

    /**
     * Click in action link "View Return".
     *
     * @return void
     */
    public function clickView()
    {
        $this->_rootElement->find($this->actionView)->click();
    }
}
