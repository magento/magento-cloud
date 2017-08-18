<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Banner\Test\Block\Adminhtml\Banner\Edit\Tab;

use Magento\Mtf\Client\Locator;
use Magento\Mtf\Client\Element\SimpleElement;

/**
 * Banner content per store view edit page.
 */
class Content extends \Magento\Cms\Test\Block\Adminhtml\Page\Edit\Tab\Content
{
    /**
     * Use banner content selector.
     *
     * @var string
     */
    protected $contentsNotUse = '[name="store_contents_not_use[%s]"]';

    /**
     * Banner content selector.
     *
     * @var string
     */
    protected $storeContent = '[name="store_contents[%s]"]';

    /**
     * Specific content context.
     *
     * @var string
     */
    protected $contentContext = '//div[*[@name="store_contents[%s]"]]';

    /**
     * Fill data to content fields on content tab.
     *
     * @param array $fields
     * @param SimpleElement|null $element
     * @return $this
     */
    public function setFieldsData(array $fields, SimpleElement $element = null)
    {
        if (isset($fields['store_contents_not_use'])) {
            foreach ($fields['store_contents_not_use']['value'] as $key => $storeContentUse) {
                $store = explode('_', $key);
                $element->find(sprintf($this->contentsNotUse, $store[1]), Locator::SELECTOR_CSS, 'checkbox')
                    ->setValue($storeContentUse);
            }
        }
        if (isset($fields['store_contents'])) {
            $this->fillStoreContent($fields, $element);
        }

        return $this;
    }

    /**
     * Fill store content data.
     *
     * @param array $fields
     * @param SimpleElement|null $element
     * @return void
     */
    protected function fillStoreContent(array $fields, SimpleElement $element = null)
    {
        foreach ($fields['store_contents']['value'] as $key => $storeContent) {
            $store = explode('_', $key);
            if ($storeContent != "-") {
                if (!is_array($storeContent)) {
                    $element->find(sprintf($this->storeContent, $store[1]))->setValue($storeContent);
                }
                $context = $element->find(sprintf($this->contentContext, $store[1]), Locator::SELECTOR_XPATH);
                if (isset($storeContent['widget']['dataset'])) {
                    foreach ($storeContent['widget']['dataset'] as $widget) {
                        $this->clickInsertWidget($context);
                        $this->getWidgetBlock()->addWidget($widget);
                    }
                }
                if (isset($storeContent['variable'])) {
                    $this->clickInsertVariable($context);
                    $config = $this->getWysiwygConfig();
                    $config->selectVariableByName($storeContent['variable']);
                }
            }
        }
    }

    /**
     * Get data of content tab.
     *
     * @param array|null $fields
     * @param SimpleElement|null $element
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getFieldsData($fields = null, SimpleElement $element = null)
    {
        $storeContent = [];
        $count = 0;
        $field = $this->_rootElement->find(sprintf($this->storeContent, $count), Locator::SELECTOR_CSS, 'checkbox');
        while ($field->isVisible()) {
            $fieldValue = $field->getValue();
            if ($fieldValue != '') {
                $storeContent[$count] = $fieldValue;
            }
            ++$count;
            $field = $this->_rootElement->find(sprintf($this->storeContent, $count), Locator::SELECTOR_CSS, 'checkbox');
        }

        $storeContentUse = [];
        $count = 0;
        $field = $this->_rootElement->find(sprintf($this->contentsNotUse, $count));
        while ($field->isVisible()) {
            $fieldValue = $field->getValue();
            if ($fieldValue != '') {
                $storeContentUse[$count] = $fieldValue;
            }
            ++$count;
            $field = $this->_rootElement->find(sprintf($this->contentsNotUse, $count));
        }

        return ['store_content' => $storeContent, 'store_contents_not_use' => $storeContentUse];
    }
}
