<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Test\Block\Adminhtml\Rma\NewRma\Tab;

use Magento\Mtf\Client\Locator;
use Magento\Mtf\Client\Element\SimpleElement;
use Magento\Backend\Test\Block\Template;
use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Rma\Test\Block\Adminhtml\Rma\NewRma\Tab\Items\Grid as ItemsGrid;
use Magento\Rma\Test\Block\Adminhtml\Rma\NewRma\Tab\Items\Item\AddDetails;
use Magento\Rma\Test\Block\Adminhtml\Rma\NewRma\Tab\Items\Order\Grid as OrderItemsGrid;

/**
 * Items product tab.
 */
class Items extends \Magento\Rma\Test\Block\Adminhtml\Rma\Edit\Tab\Items
{
    /**
     * Selector for "Add Products" button.
     *
     * @var string
     */
    protected $addProducts = '#rma-items-block .action-add';

    /**
     * Selector for "Add Selected Product(s) to returns" button.
     *
     * @var string
     */
    protected $addSelectedProducts = '#select-order-items-block .action-add';

    /**
     * Locator item row by name.
     *
     * @var string
     */
    protected $rowByName = './/tbody/tr[./td[contains(.,"%s")]]';

    /**
     * Locator for order items grid.
     *
     * @var string
     */
    protected $orderItemsGrid = '#select-order-items-block';

    /**
     * Locator for rma items grid.
     *
     * @var string
     */
    protected $rmaItemsGrid = '#rma_items_grid';

    /**
     * Locator for template block.
     *
     * @var string
     */
    protected $templateBlock = './/ancestor::body';

    /**
     * Fill data to fields on tab.
     *
     * @param array $fields
     * @param SimpleElement|null $element
     * @return $this
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setFieldsData(array $fields, SimpleElement $element = null)
    {
        $items = isset($fields['items']['value']) ? $fields['items']['value'] : [];

        if (!empty($items)) {
            $this->clickAddProducts();
            foreach ($items as $item) {
                $this->getOrderItemsGrid()->selectItem($item['product']);
            }

            $this->clickAddSelectedProducts();
            foreach ($items as $item) {
                $this->fillItem($item);
            }

            $this->setFields['items'] = $items;
        }

        return $this;
    }

    /**
     * Fill details modal window
     *
     * @param array $fields
     * @param SimpleElement $element
     * @return array
     */
    protected function fillDetailsForm(array $fields, SimpleElement $element)
    {
        if (isset($fields['additional_attributes'])) {
            $this->blockFactory->create(
                AddDetails::class,
                ['element' => $element]
            )->fillDetails($fields['additional_attributes']);
            unset($fields['additional_attributes']);
        }

        return $fields;
    }

    /**
     * Click "Add Products" button.
     *
     * @return void
     */
    protected function clickAddProducts()
    {
        $this->_rootElement->find($this->addProducts)->click();
        $this->waitForElementVisible($this->orderItemsGrid);
    }

    /**
     * Click "Add Selected Product(s) to returns" button.
     *
     * @return void.
     */
    protected function clickAddSelectedProducts()
    {
        $this->_rootElement->find($this->addSelectedProducts)->click();
        $this->getTemplateBlock()->waitLoader();
        $this->getTemplateBlock()->waitLoader();
    }

    /**
     * Return chooser order items grid.
     *
     * @return OrderItemsGrid
     */
    protected function getOrderItemsGrid()
    {
        return $this->blockFactory->create(
            \Magento\Rma\Test\Block\Adminhtml\Rma\NewRma\Tab\Items\Order\Grid::class,
            ['element' => $this->_rootElement->find($this->orderItemsGrid)]
        );
    }

    /**
     * Return items rma grid.
     *
     * @return ItemsGrid
     */
    protected function getItemsGrid()
    {
        return $this->blockFactory->create(
            \Magento\Rma\Test\Block\Adminhtml\Rma\NewRma\Tab\Items\Grid::class,
            ['element' => $this->_rootElement->find($this->rmaItemsGrid)]
        );
    }

    /**
     * Fill item product in rma items grid.
     *
     * @param array $itemData
     * @return void
     */
    protected function fillItem(array $itemData)
    {
        /** @var CatalogProductSimple $product */
        $product = $itemData['product'];
        $productConfig = $product->getDataConfig();
        $productType = isset($productConfig['type_id']) ? ucfirst($productConfig['type_id']) : '';
        $productItemsClass = 'Magento\Rma\Test\Block\Adminhtml\Rma\NewRma\Tab\\' . $productType . 'Items';

        $productGrid = $this->blockFactory->create($productItemsClass, ['element' => $this->_rootElement]);
        $productGrid->fillItem($itemData);
    }

    /**
     * Return template block.
     *
     * @return Template
     */
    protected function getTemplateBlock()
    {
        return $this->blockFactory->create(
            \Magento\Backend\Test\Block\Template::class,
            ['element' => $this->_rootElement->find($this->templateBlock, Locator::SELECTOR_XPATH)]
        );
    }
}
