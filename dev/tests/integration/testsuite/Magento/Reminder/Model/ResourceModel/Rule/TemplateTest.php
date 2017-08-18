<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Reminder\Model\ResourceModel\Rule;

use Magento\Reminder\Model\Rule;

class TemplateTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test creation of reminder rule with custom template.
     */
    public function testTemplate()
    {
        /** @var $store \Magento\Store\Model\Store */
        $store = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Store\Model\StoreManagerInterface::class
        )->getStore();
        $storeId = $store->getId();

        /** @var \Magento\Email\Model\Template $template */
        $template = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Email\Model\Template::class
        );
        $template->setTemplateCode(
            'fixture_tpl'
        )->setTemplateText(
            '<p>Reminder email</p>This is a reminder email'
        )->setTemplateType(
            2
        )->setTemplateSubject(
            'Subject'
        )->setTemplateSenderName(
            'CustomerSupport'
        )->setTemplateSenderEmail(
            'support@example.com'
        )->setTemplateActual(
            1
        )->save();

        $conditions = json_encode([]);

        $ruleCreate = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Reminder\Model\Rule::class
        );

        $ruleCreate->setData(
            [
                'name' => 'My Rule',
                'description' => 'My Rule Desc',
                'conditions_serialized' => $conditions,
                'condition_sql' => 1,
                'is_active' => 1,
                'salesrule_id' => null,
                'schedule' => null,
                'default_label' => null,
                'default_description' => null,
                'from_date' => null,
                'to_date' => null,
                'store_templates' => [$storeId => $template->getId()],
            ]
        )->save();

        $dateModel = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Framework\Stdlib\DateTime\DateTime::class
        );
        $collection = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Reminder\Model\ResourceModel\Rule\Collection::class
        );
        $collection->addDateFilter($dateModel->date());
        $this->assertEquals(1, $collection->count());
        /** @var $rule Rule */
        foreach ($collection as $rule) {
            $this->assertInstanceOf(\Magento\Reminder\Model\Rule::class, $rule);
            $this->assertEquals('My Rule', $rule->getName());
            $storeData = $rule->getStoreData($rule->getId(), $storeId);
            $this->assertNotNull($storeData);
            $this->assertEquals($template->getId(), $storeData['template_id']);

            return;
        }
    }
}
