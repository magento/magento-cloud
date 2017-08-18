<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\Block\Customer;

use Magento\Mtf\Block\Form;
use Magento\Mtf\Client\Locator;
use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Client\Element\SimpleElement;

/**
 * Form for select gift registry type.
 */
class Edit extends Form
{
    /**
     * Gift registry type input selector.
     *
     * @var string
     */
    protected $giftRegistryType = '[name="type_id"]';

    /**
     * Next button.
     *
     * @var string
     */
    protected $next = ".action.next";

    /**
     * Select gift registry type.
     *
     * @param string $value
     * @return void
     */
    public function selectGiftRegistryType($value)
    {
        $this->_rootElement->find($this->giftRegistryType, Locator::SELECTOR_CSS, 'select')->setValue($value);
        $this->_rootElement->find($this->next)->click();
    }

    /**
     * Check if GiftRegistry type is visible.
     *
     * @param string $value
     * @return bool
     */
    public function isGiftRegistryVisible($value)
    {
        $presentOptions = $this->_rootElement->find($this->giftRegistryType)->getText();
        $presentOptions = explode("\n", $presentOptions);
        return in_array($value, $presentOptions);
    }

    /**
     * Fill the root form.
     *
     * @param FixtureInterface $fixture
     * @param SimpleElement|null $element
     * @return $this
     */
    public function fill(FixtureInterface $fixture, SimpleElement $element = null)
    {
        $data = $fixture->getData();
        unset($data['type_id']);
        $mapping = $this->dataMapping($data);
        $this->_fill($mapping, $element);

        return $this;
    }
}
