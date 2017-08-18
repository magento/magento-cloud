<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Test class for \Magento\GiftRegistry\Block\Form\Element
 */
namespace Magento\GiftRegistry\Block\Form;

use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\View\LayoutInterface;
use Magento\TestFramework\Helper\Bootstrap;

class ElementTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Element
     */
    private $block;

    protected function setUp()
    {
        $this->block = Bootstrap::getObjectManager()->get(LayoutInterface::class)
            ->createBlock(Element::class);
    }

    /**
     * @magentoAppArea frontend
     */
    public function testGetCalendarDateHtml()
    {
        $value = null;
        $formatType = \IntlDateFormatter::FULL;
        $html = $this->block->getCalendarDateHtml('date_name', 'date_id', $value, $formatType);
        $dateFormat = Bootstrap::getObjectManager()->get(TimezoneInterface::class)
            ->getDateFormat($formatType);
        $this->assertContains('dateFormat: "' . $dateFormat . '",', $html);
        $this->assertContains('value=""', $html);
    }

    /**
     * @magentoAppArea frontend
     */
    public function testGetCountryHtmlSelect()
    {
        $result = $this->block->getCountryHtmlSelect('name', 'id', 'US');
        $this->assertStringMatchesFormat(
            "%sselect name=\"name\" id=\"id\"%s<option value=\"US\" selected=\"selected\" >United States</option>%s",
            $result
        );
    }
}
