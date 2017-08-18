<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Test\Block\Returns;

use Magento\Mtf\Block\Form;
use Magento\Mtf\Client\Element\SimpleElement;
use Magento\Mtf\Client\Locator;
use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Rma\Test\Block\Returns\Create\Item;
use Magento\Rma\Test\Fixture\Rma;
use Magento\Sales\Test\Fixture\OrderInjectable;

/**
 * Return Item form block.
 */
class Create extends Form
{
    /**
     * Return item block selector.
     *
     * @var string
     */
    protected $item = '#row%d';

    /**
     * Add Item to Return button selector.
     *
     * @var string
     */
    protected $addItemToReturnButtonSelector = 'add-item-to-return';

    /**
     * Return button selector.
     *
     * @var string
     */
    protected $returnButtonSelector = 'submit.save';

    /**
     * Fill the return form.
     *
     * @param FixtureInterface $fixture
     * @param SimpleElement|null $element
     * @return $this
     */
    public function fill(FixtureInterface $fixture, SimpleElement $element = null)
    {
        /** @var Rma $fixture */
        $data = $fixture->getData();
        $mapping = $this->dataMapping($data);
        $items = $this->getItems($fixture);

        $this->fillItems($items);
        parent::_fill($mapping, $element);

        return $this;
    }

    /**
     * Get rma items data.
     *
     * @param Rma $rma
     * @return array
     */
    protected function getItems(Rma $rma)
    {
        /** @var OrderInjectable $order */
        $order = $rma->getDataFieldConfig('order_id')['source']->getOrder();
        $products = $order->getEntityId()['products'];
        $items = $rma->getItems();

        foreach (array_keys($items) as $index) {
            $product = $products[$index];
            $items[$index]['entity_id'] = $product->getName();
        }

        return $items;
    }

    /**
     * Fill rma items form.
     *
     * @param array $items
     * @return void
     */
    protected function fillItems(array $items)
    {
        $itemsAmount = count($items);
        $count = 0;

        foreach ($items as $item) {
            $itemBlock = $this->getItemBlock($count);
            $mapping = $itemBlock->dataMapping($item);
            $itemBlock->_fill($mapping);

            $count++;
            if ($count < $itemsAmount) {
                 $this->submitAddItemToReturn();
            }
        }
    }

    /**
     * Get return item block.
     *
     * @param int $index
     * @return Item
     */
    protected function getItemBlock($index)
    {
        $itemBlockLocator = sprintf($this->item, $index);
        return $this->blockFactory->create(
            \Magento\Rma\Test\Block\Returns\Create\Item::class,
            ['element' => $this->_rootElement->find($itemBlockLocator)]
        );
    }

    /**
     * Submit add item to return.
     *
     * @return void
     */
    public function submitAddItemToReturn()
    {
        $this->_rootElement->find($this->addItemToReturnButtonSelector, Locator::SELECTOR_ID)->click();
    }

    /**
     * Submit return.
     *
     * @return void
     */
    public function submitReturn()
    {
        $this->_rootElement->find($this->returnButtonSelector, Locator::SELECTOR_ID)->click();
    }
}
