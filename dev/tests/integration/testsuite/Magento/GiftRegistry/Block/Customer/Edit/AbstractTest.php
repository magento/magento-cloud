<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Test class for \Magento\GiftRegistry\Block\Customer\Edit\AbstractEdit
 */
namespace Magento\GiftRegistry\Block\Customer\Edit;

class AbstractTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Stub class name
     */
    const STUB_CLASS = 'Magento_GiftRegistry_Block_Customer_Edit_AbstractEdit_Stub';

    public function testGetCalendarDateHtml()
    {
        $this->getMockForAbstractClass(
            \Magento\GiftRegistry\Block\Customer\Edit\AbstractEdit::class,
            [],
            self::STUB_CLASS,
            false
        );
        \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(\Magento\Framework\App\State::class)
            ->setAreaCode('frontend');
        /** @var \Magento\GiftRegistry\Block\Customer\Edit\AbstractEdit $block */
        $block = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Framework\View\LayoutInterface::class
        )->createBlock(
            self::STUB_CLASS
        );

        $date = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Framework\Stdlib\DateTime\TimezoneInterface::class
        )->date(strtotime(null), null, null, false);
        $formatType = \IntlDateFormatter::MEDIUM;

        $html = $block->getCalendarDateHtml('date_name', 'date_id', $date, $formatType);

        $dateFormat = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Framework\Stdlib\DateTime\TimezoneInterface::class
        )->getDateFormat(
            $formatType
        );
        $value = $block->formatDate($date, $formatType);

        $this->assertContains('dateFormat: "' . $dateFormat . '",', $html);
        $this->assertContains('value="' . $value . '"', $html);
    }
}
