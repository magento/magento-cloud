<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VersionsCms\Test\Block\Adminhtml\Cms\Page\Revision\Edit\Tab;

use Magento\Mtf\Client\Locator;
use Magento\Mtf\Client\Element\SimpleElement;
use Magento\Backend\Test\Block\Widget\Tab;

/**
 * Backend cms page revision content tab.
 */
class Content extends Tab
{
    /**
     * Locator for Page Content.
     *
     * @var string
     */
    protected $pageContent = "#page_content";

    /**
     * Content Editor toggle button locator.
     *
     * @var string
     */
    protected $toggleButton = "#togglepage_content";

    /**
     * CMS Page Content area.
     *
     * @var string
     */
    protected $contentForm = '[name="content"]';

    /**
     * Locator for Revision number.
     *
     * @var string
     */
    protected $revision = ".cms-revision-info div[class*=title] span[class*=title]";

    /**
     * Locator for Version title.
     *
     * @var string
     */
    protected $version = "//table/tbody/tr[3]/td";

    /**
     * Get page content.
     *
     * @return string
     */
    protected function getPageContent()
    {
        $this->hideEditor();
        return $this->_rootElement->find($this->pageContent)->getText();
    }

    /**
     * Hide WYSIWYG editor.
     *
     * @return void
     */
    protected function hideEditor()
    {
        $content = $this->_rootElement->find($this->contentForm);
        $toggleButton = $this->_rootElement->find($this->toggleButton);
        if (!$content->isVisible() && $toggleButton->isVisible()) {
            $toggleButton->click();
        }
    }

    /**
     * Get Revision number.
     *
     * @return string
     */
    protected function getRevision()
    {
        $revisionLabel = $this->_rootElement->find($this->revision, Locator::SELECTOR_CSS)->getText();
        preg_match('/\d+/', $revisionLabel, $matches);
        return isset($matches[0]) ? $matches[0] : '';
    }

    /**
     * Get Version title.
     *
     * @return string
     */
    protected function getVersion()
    {
        return $this->_rootElement->find($this->version, Locator::SELECTOR_XPATH)->getText();
    }

    /**
     * Get data of Revision Content tab.
     *
     * @return array
     */
    public function getContentData()
    {
        $data['revision'] = $this->getRevision();
        $data['version'] = $this->getVersion();
        $data['content']['content'] = $this->getPageContent();
        return $data;
    }

    /**
     * Fill data to fields on tab.
     *
     * @param array $fields
     * @param SimpleElement|null $element
     * @return $this
     */
    public function fillFormTab(array $fields, SimpleElement $element = null)
    {
        $this->hideEditor();
        parent::fillFormTab($fields, $element);
        return $this;
    }
}
