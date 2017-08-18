<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\VersionsCms\Test\Constraint;

use Magento\Cms\Test\Page\CmsPage;
use Magento\PageCache\Test\Page\Adminhtml\AdminCache;
use Magento\Cms\Test\Page\CmsIndex;
use Magento\Widget\Test\Fixture\Widget;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Check that Hierarchy node can be opened on Home page and is present on CMS page.
 */
class AssertWidgetHierarchyNodeLink extends AbstractConstraint
{
    /**
     * Assert that Hierarchy node can be opened on Home page and is present on CMS page.
     *
     * @param CmsIndex $cmsIndex
     * @param Widget $widget
     * @param AdminCache $adminCache
     * @param CmsPage $frontCmsPage
     * @return void
     */
    public function processAssert(
        CmsIndex $cmsIndex,
        Widget $widget,
        AdminCache $adminCache,
        CmsPage $frontCmsPage
    ) {
        // Flush cache
        $adminCache->open();
        $adminCache->getActionsBlock()->flushMagentoCache();
        $adminCache->getMessagesBlock()->waitSuccessMessage();

        $cmsIndex->open();

        /** @var \Magento\VersionsCms\Test\Fixture\CmsHierarchy $cmsHierarchyEntity */
        $cmsHierarchyEntity = $widget->getParameters()['entities'][0];
        $nodesData = $cmsHierarchyEntity->getData('nodes_data');

        $cmsIndex->getTopmenu()->selectCategoryByName($nodesData[0]['identifier']);

        foreach ($nodesData as $nodeData) {
            \PHPUnit_Framework_Assert::assertContains(
                $nodeData['identifier'],
                $frontCmsPage->getCmsPageBlock()->getPageContent(),
                'Wrong content is displayed.'
            );
        }
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Hierarchy node can be opened on Home page and is present on CMS page.';
    }
}
