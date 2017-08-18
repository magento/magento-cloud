<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Test\Constraint;

use Magento\Customer\Test\Fixture\Customer;
use Magento\Rma\Test\Fixture\Rma;
use Magento\Rma\Test\Page\Adminhtml\RmaIndex;
use Magento\Sales\Test\Fixture\OrderInjectable;
use Magento\Mtf\Constraint\AbstractAssertForm;

/**
 * Base assert that displayed rma on backend is correct.
 */
abstract class AbstractAssertRmaOnBackend extends AbstractAssertForm
{
    /**
     * Rma request success save message.
     */
    const SUCCESS_SAVE_MESSAGE = 'You saved the RMA request.';

    /**
     * Array skipped fields.
     *
     * @var array
     */
    protected $skippedFields = [
        'status',
        'comment',
        'items',
    ];

    /**
     * Open rma page view.
     *
     * @param Rma $rma
     * @param RmaIndex $rmaIndex
     * @return void
     */
    protected function open(Rma $rma, RmaIndex $rmaIndex)
    {
        $rmaId = sprintf("%s", $rma->getEntityId());
        $filter = [
            'rma_id' => $rmaId,
        ];

        $rmaIndex->open();
        $rmaIndex->getRmaGrid()->searchAndOpen($filter);
    }

    /**
     * Assert that displayed rma items on edit page equals initial data.
     *
     * @param array $fixtureItems
     * @param array $pageItems
     * @return void
     */
    protected function verifyItems(array $fixtureItems, array $pageItems)
    {
        foreach ($pageItems as $key => $pageItem) {
            $pageItems[$key]['product'] = preg_replace('/ \(.+\)$/', '', $pageItem['product']);
        }

        $fixtureItems = $this->sortDataByPath($fixtureItems, '::product');
        $pageItems = $this->sortDataByPath($pageItems, '::product');
        foreach ($pageItems as $key => $pageItem) {
            $pageItems[$key] = array_intersect_key($pageItem, $fixtureItems[$key]);
        }
        \PHPUnit_Framework_Assert::assertEquals(
            $fixtureItems,
            $pageItems,
            'Displayed rma items on edit page does not equals initial data.'
        );
    }
}
