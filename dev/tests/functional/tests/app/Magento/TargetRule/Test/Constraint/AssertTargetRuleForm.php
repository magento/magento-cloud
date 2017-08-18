<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\TargetRule\Test\Constraint;

use Magento\CustomerSegment\Test\Fixture\CustomerSegment;
use Magento\TargetRule\Test\Fixture\TargetRule;
use Magento\TargetRule\Test\Page\Adminhtml\TargetRuleEdit;
use Magento\TargetRule\Test\Page\Adminhtml\TargetRuleIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertTargetRuleForm
 */
class AssertTargetRuleForm extends AbstractConstraint
{
    /**
     * Skipped fields for verify data
     *
     * @var array
     */
    protected $skippedFields = [
        'rule_id',
        'conditions_serialized',
        'actions_serialized',
    ];

    /**
     * Assert that displayed target rule data on edit page(backend) equals passed from fixture
     *
     * @param TargetRuleIndex $targetRuleIndex
     * @param TargetRuleEdit $targetRuleEdit
     * @param TargetRule $targetRule
     * @param CustomerSegment|null $customerSegment
     * @param TargetRule|null $initialTargetRule
     * @return void
     */
    public function processAssert(
        TargetRuleIndex $targetRuleIndex,
        TargetRuleEdit $targetRuleEdit,
        TargetRule $targetRule,
        CustomerSegment $customerSegment = null,
        TargetRule $initialTargetRule = null
    ) {
        $replace = [
            'customer_segment_ids' => [
                '%customer_segment%' => $customerSegment && $customerSegment->hasData()
                    ? $customerSegment->getName()
                    : '',
            ],
        ];

        $targetRuleData = $initialTargetRule
            ? array_replace($initialTargetRule->getData(), $targetRule->getData())
            : $targetRule->getData();
        $targetRuleData = $this->prepareData($targetRuleData, $replace);
        $targetRuleIndex->open();
        $targetRuleIndex->getTargetRuleGrid()->searchAndOpen(['name' => $targetRuleData['name']]);
        $formData = $targetRuleEdit->getTargetRuleForm()->getData();
        $dataDiff = $this->verify($targetRuleData, $formData);
        \PHPUnit_Framework_Assert::assertTrue(
            empty($dataDiff),
            'TargetRule data on edit page(backend) not equals to passed from fixture.'
            . "\nFailed values: " . implode(', ', $dataDiff)
        );
    }

    /**
     * Verify data in form equals to passed from fixture
     *
     * @param array $data
     * @param array $replace
     * @return array
     */
    protected function prepareData(array $data, array $replace)
    {
        foreach ($replace as $key => $pairs) {
            if (isset($data[$key])) {
                $data[$key] = str_replace(
                    array_keys($pairs),
                    array_values($pairs),
                    $data[$key]
                );
            }
        }
        return $data;
    }

    /**
     * Verify data in form equals to passed from fixture
     *
     * @param array $dataFixture
     * @param array $dataForm
     * @return array
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function verify(array $dataFixture, array $dataForm)
    {
        $result = [];
        $dateFields = ['from_date', 'to_date'];

        foreach ($dateFields as $fieldName) {
            if (isset($dataFixture[$fieldName])) {
                $dataFixture[$fieldName] = strtotime($dataFixture[$fieldName]);
            }
            if (isset($dataForm[$fieldName])) {
                $dataForm[$fieldName] = strtotime($dataForm[$fieldName]);
            }
        }
        if (isset($dataFixture['customer_segment_ids']) && !is_array($dataFixture['customer_segment_ids'])) {
            $dataFixture['customer_segment_ids'] = [$dataFixture['customer_segment_ids']];
        }

        $dataFixture = array_diff_key($dataFixture, array_flip($this->skippedFields));
        foreach ($dataFixture as $key => $value) {
            if (!isset($dataForm[$key])) {
                $result[] = "\ntarget rule {$key} is absent in form";
                continue;
            }
            if (is_array($value)) {
                $diff = array_diff($value, $dataForm[$key]);
                if (empty($diff)) {
                    continue;
                }

                $result[] = "\ntarget rule {$key}: \""
                    . implode(', ', $dataForm[$key])
                    . "\" instead of \""
                    . implode(', ', $value)
                    . "\"";
                continue;
            }
            if ($value != $dataForm[$key]) {
                $result[] = "\ntarget rule {$key}: \"{$dataForm[$key]}\" instead of \"{$value}\"";
            }
        }

        return $result;
    }

    /**
     * Text success verify Target Rule form
     *
     * @return string
     */
    public function toString()
    {
        return 'Displayed target rule data on edit page(backend) equals to passed from fixture.';
    }
}
