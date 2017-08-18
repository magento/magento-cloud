<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\Block\Customer\Edit;

use Magento\Mtf\Client\Locator;
use Magento\Mtf\Client\Element\SimpleElement;

/**
 * Class Address
 * Shipping Address
 */
class Address extends SimpleElement
{
    /**
     * Address fields selectors
     *
     * @var array
     */
    protected $address = [
        'firstname' => [
            'selector' => '.shipping_address [name$="[firstname]"]',
            'input' => null,
        ],
        'lastname' => [
            'selector' => '.shipping_address [name$="[lastname]"]',
            'input' => null,
        ],
        'company' => [
            'selector' => '.shipping_address [name$="[company]"]',
            'input' => null,
        ],
        'street' => [
            'selector' => '.shipping_address [name$="[street][]"]',
            'input' => null,
        ],
        'city' => [
            'selector' => '.shipping_address [name$="[city]"]',
            'input' => null,
        ],
        'region_id' => [
            'selector' => '.shipping_address [name$="[region_id]"]',
            'input' => 'select',
        ],
        'postcode' => [
            'selector' => '.shipping_address [name$="[postcode]"]',
            'input' => null,
        ],
        'country_id' => [
            'selector' => '.shipping_address [name$="[country_id]"]',
            'input' => 'select',
        ],
        'telephone' => [
            'selector' => '.shipping_address [name$="[telephone]"]',
            'input' => null,
        ],
    ];

    /**
     * Set shipping address
     *
     * @param array $value
     * @return void
     */
    public function setValue($value)
    {
        foreach ($value as $field => $val) {
            $this->find(
                $this->address[$field]['selector'],
                Locator::SELECTOR_CSS,
                $this->address[$field]['input']
            )->setValue($val);
        }
    }

    /**
     * Get shipping address
     *
     * @return array
     */
    public function getValue()
    {
        $address = [];
        foreach ($this->address as $field => $locator) {
            $address[$field] = $this->find($locator['selector'], Locator::SELECTOR_CSS, $locator['input'])->getValue();
        }

        return $address;
    }
}
