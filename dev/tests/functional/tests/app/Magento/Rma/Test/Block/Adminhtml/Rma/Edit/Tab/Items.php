<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Test\Block\Adminhtml\Rma\Edit\Tab;

use Magento\Mtf\Client\Locator;
use Magento\Mtf\Client\Element\SimpleElement;
use Magento\Rma\Test\Block\Adminhtml\Rma\Edit\Tab\Items\Item;

/**
 * Items block on edit rma backend page.
 */
class Items extends \Magento\Backend\Test\Block\Widget\Tab
{
    /**
     * Locator for item row in grid.
     *
     * @var string
     */
    protected $rowItem = './/*[@id="magento_rma_item_edit_grid_table"]/tbody/tr';

    /**
     * Locator for search item row by name.
     *
     * @var string
     */
    protected $rowItemByName = ".//tr[contains(normalize-space(td/text()),'%s')]";

    /**
     * Fill data to fields on tab.
     *
     * @param array $fields
     * @param SimpleElement|null $element
     * @return $this
     */
    public function setFieldsData(array $fields, SimpleElement $element = null)
    {
        $items = isset($fields['items']['value']) ? $fields['items']['value'] : [];
        $context = $element ? $element : $this->_rootElement;

        foreach ($items as $item) {
            $itemElement = $context->find(sprintf($this->rowItemByName, $item['product']), Locator::SELECTOR_XPATH);
            $this->getItemRow($itemElement)->fillRow($item);
        }

        $this->setFields['items'] = $fields['items']['value'];
        return $this;
    }

    /**
     * Get data of tab.
     *
     * @param array|null $fields
     * @param SimpleElement|null $element
     * @return array
     */
    public function getFieldsData($fields = null, SimpleElement $element = null)
    {
        if (null === $fields || isset($fields['items'])) {
            $context = $element ? $element : $this->_rootElement;
            $rows = $context->getElements($this->rowItem, Locator::SELECTOR_XPATH);
            $data = [];

            foreach ($rows as $row) {
                $data[] = $this->getItemRow($row)->getRowData();
            }

            return ['items' => $data];
        }
        return [];
    }

    /**
     * Return item row form.
     *
     * @param SimpleElement $element
     * @return Item
     */
    protected function getItemRow(SimpleElement $element)
    {
        return $this->blockFactory->create(
            \Magento\Rma\Test\Block\Adminhtml\Rma\Edit\Tab\Items\Item::class,
            ['element' => $element]
        );
    }
}
