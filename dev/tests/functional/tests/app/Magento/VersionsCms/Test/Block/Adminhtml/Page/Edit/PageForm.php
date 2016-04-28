<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VersionsCms\Test\Block\Adminhtml\Page\Edit;

use Magento\Mtf\Client\Locator;

/**
 * Backend Cms Page edit page.
 */
class PageForm extends \Magento\Cms\Test\Block\Adminhtml\Page\Edit\PageForm
{
    /**
     * Current published revision link selector.
     *
     * @var string
     */
    protected $currentlyPublishedRevision = '#page_published_revision_link';

    /**
     * Get 'Currently Published Revision' link text
     *
     * @return string
     */
    public function getCurrentlyPublishedRevisionText()
    {
        return $this->_rootElement->find($this->currentlyPublishedRevision)->getText();
    }

    /**
     * Click on 'Currently Published Revision' link.
     *
     * @return void
     */
    protected function clickCurrentlyPublishedRevision()
    {
        $this->_rootElement->find($this->currentlyPublishedRevision)->click();
    }

    /**
     * Open tab.
     *
     * @param string $tabName
     * @return $this
     */
    public function openTab($tabName)
    {
        $selector = $this->tabs[$tabName]['selector'];
        $strategy = isset($this->tabs[$tabName]['strategy'])
            ? $this->tabs[$tabName]['strategy']
            : Locator::SELECTOR_CSS;
        $tab = $this->_rootElement->find($selector, $strategy);
        if ($tabName == 'content') {
            if (!$tab->isVisible()) {
                $this->openTab('page_information');
                $this->clickCurrentlyPublishedRevision();
            } else {
                $tab->click();
            }
            $this->toggleEditor();
        } else {
            $tab->click();
        }

        return $this;
    }
}
