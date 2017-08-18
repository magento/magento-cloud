<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Test\Block\Returns\View;

use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Block\Form;
use Magento\Mtf\Client\Element\SimpleElement;

/**
 * Rma items.
 */
class RmaItems extends Form
{
    /**
     * Locator for item row.
     *
     * @var string
     */
    protected $itemRow = 'tbody tr';

    /**
     * Get data of rma items.
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
        $items = $context->getElements($this->itemRow);
        $data = [];

        foreach ($items as $key => $item) {
            foreach ($mapping as $name => $locator) {
                $value = $item->find($locator['selector'], $locator['strategy'])->getText();
                $value = explode("\n", $value);
                $data[$key][$name] = trim($value[0]);
            }
        }

        return $data;
    }
}
