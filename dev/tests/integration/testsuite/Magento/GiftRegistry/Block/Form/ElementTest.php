<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Test class for \Magento\GiftRegistry\Block\Form\Element
 */
namespace Magento\GiftRegistry\Block\Form;

class ElementTest extends \PHPUnit_Framework_TestCase
{
    public function testGetCalendarDateHtml()
    {
        \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get('Magento\Framework\App\State')
            ->setAreaCode('frontend');
        $block = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            'Magento\Framework\View\LayoutInterface'
        )->createBlock(
            'Magento\GiftRegistry\Block\Form\Element'
        );

        $value = null;
        $formatType = \IntlDateFormatter::FULL;

        $html = $block->getCalendarDateHtml('date_name', 'date_id', $value, $formatType);

        $dateFormat = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            'Magento\Framework\Stdlib\DateTime\TimezoneInterface'
        )->getDateFormat(
            $formatType
        );

        $this->assertContains('dateFormat: "' . $dateFormat . '",', $html);
        $this->assertContains('value=""', $html);
    }
}
