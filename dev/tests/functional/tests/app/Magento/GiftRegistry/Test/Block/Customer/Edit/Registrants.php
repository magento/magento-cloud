<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\Block\Customer\Edit;

use Magento\Mtf\Client\Locator;
use Magento\Mtf\Client\Element\SimpleElement;

/**
 * Class Registrant
 * Recipients Information
 */
class Registrants extends SimpleElement
{
    /**
     * Add registrant button selector
     *
     * @var string
     */
    protected $addRegistrant = '#add-registrant-button';

    /**
     * Registrant block selector
     *
     * @var string
     */
    protected $registrant = '[id="registrant:%d"]';

    /**
     * Recipient fields selectors
     *
     * @var array
     */
    protected $recipient = [
        'firstname' => [
            'selector' => '[name$="[firstname]"]',
            'input' => null,
        ],
        'lastname' => [
            'selector' => '[name$="[lastname]"]',
            'input' => null,
        ],
        'email' => [
            'selector' => '[name$="[email]"]',
            'input' => null,
        ],
        'role' => [
            'selector' => '[name$="[role]"]',
            'input' => 'select',
        ],
    ];

    /**
     * Set recipients information
     *
     * @param array $value
     * @return void
     */
    public function setValue($value)
    {
        foreach ($value as $key => $recipient) {
            $registrant = $this->find(sprintf($this->registrant, $key));
            if ($key !== 0) {
                $this->find($this->addRegistrant)->click();
            }
            foreach ($recipient as $field => $value) {
                $registrant->find(
                    $this->recipient[$field]['selector'],
                    Locator::SELECTOR_CSS,
                    $this->recipient[$field]['input']
                )->setValue($value);
            }
        }
    }

    /**
     * Get recipients information
     *
     * @return array
     */
    public function getValue()
    {
        $recipients = [];
        $key = 0;
        $registrant = $this->find(sprintf($this->registrant, $key));
        while ($registrant->isVisible()) {
            foreach ($this->recipient as $field => $locator) {
                $element = $registrant->find(
                    $locator['selector'],
                    Locator::SELECTOR_CSS,
                    $locator['input']
                );
                if ($element->isVisible()) {
                    $recipients[$key][$field] = $element->getValue();
                }
            }
            $registrant = $this->find(sprintf($this->registrant, ++$key));
        }

        return $recipients;
    }
}
