<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VersionsCms\Test\Constraint;

use Magento\VersionsCms\Test\Fixture\VersionsCmsPage as CmsPage;
use Magento\Cms\Test\Page\Adminhtml\CmsPageIndex;
use Magento\Cms\Test\Page\Adminhtml\CmsPageNew;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that initial CMS page version can be found on CMS page Versions tab in grid.
 */
class AssertCmsPageInitialVersionInGrid extends AbstractConstraint
{
    /**
     * CmsPageNew Page.
     *
     * @var CmsPageNew
     */
    protected $cmsPageNew;

    /**
     * Cms Index Page.
     *
     * @var CmsPageIndex
     */
    protected $cmsPageIndex;

    /**
     * Search version by filter and perform assert.
     *
     * @param CmsPage $cms
     * @param array $results
     * @return void
     */
    protected function searchVersion(CmsPage $cms, array $results)
    {
        if (!isset($results['quantity'])) {
            preg_match('/\d+/', $results['revision'], $matches);
            $quantity = $matches[0];
        } else {
            $quantity = $results['quantity'];
        }
        $filter = [
            'label' => isset($results['label']) ? $results['label'] : $cms->getTitle(),
            'owner' => $results['owner'],
            'access_level' => $results['access_level'],
            'quantity' => $quantity,
        ];
        \PHPUnit_Framework_Assert::assertTrue(
            $this->cmsPageNew->getPageVersionsForm()->getTab('versions')->getVersionsGrid()->isRowVisible(
                $filter
            ),
            'CMS Page Version with '
            . 'label \'' . $filter['label'] . '\', '
            . 'owner \'' . $filter['owner'] . '\', '
            . 'access level \'' . $filter['access_level'] . '\', '
            . 'quantity \'' . $filter['quantity'] . '\', '
            . 'is absent in CMS Page Versions grid.'
        );
    }

    /**
     * Assert that initial CMS page version can be found on CMS page Versions tab in grid via:
     * Version label, Owner, Quantity, Access Level.
     *
     * @param CmsPage $cms
     * @param CmsPageNew $cmsPageNew
     * @param CmsPageIndex $cmsPageIndex
     * @param array $results
     * @param CmsPage $cmsInitial[optional]
     * @return void
     */
    public function processAssert(
        CmsPage $cms,
        CmsPageNew $cmsPageNew,
        CmsPageIndex $cmsPageIndex,
        array $results,
        CmsPage $cmsInitial = null
    ) {
        $this->cmsPageIndex = $cmsPageIndex;
        $this->cmsPageNew = $cmsPageNew;
        $filter = ['title' => $cms->getTitle()];
        $this->cmsPageIndex->open();
        $this->cmsPageIndex->getCmsPageGridBlock()->searchAndOpen($filter);
        $this->cmsPageNew->getPageVersionsForm()->openTab('versions');
        $this->searchVersion($cmsInitial, $results);
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'CMS Page Initial Version is present in grid.';
    }
}
