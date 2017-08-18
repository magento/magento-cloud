<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Test\Block\Adminhtml\Rma\Edit\Tab;

use Magento\Mtf\Client\Element\SimpleElement;

/**
 * General information tab on rma edit page(backend).
 */
class General extends \Magento\Backend\Test\Block\Widget\Tab
{
    /**
     * Locator for comment list.
     *
     * @var string
     */
    protected $commentHistory = '.rma-comments-history .note-list > li';

    /**
     * Locator for text of single comment.
     *
     * @var string
     */
    protected $commentText = '.note-list-comment';

    /**
     * Get data of tab.
     *
     * @param array|null $fields
     * @param SimpleElement|null $element
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getFieldsData($fields = null, SimpleElement $element = null)
    {
        $context = $element ? $element : $this->_rootElement;
        return array_merge($this->getRequestDetails($context), ['comment' => $this->getCommentData()]);
    }

    /**
     * Return request details.
     *
     * @param SimpleElement $context
     * @return array
     */
    protected function getRequestDetails(SimpleElement $context)
    {
        $mapping = $this->dataMapping();
        $mappingDetails = $mapping['details']['value'];
        $data = [];

        unset($mappingDetails['composite']);
        foreach ($mappingDetails as $fieldName => $locator) {
            $element = $context->find($locator['selector'], $locator['strategy']);
            if ($element->isVisible()) {
                $data[$fieldName] = trim($element->getText());
            }
        }

        if (isset($data['entity_id'])) {
            $data['entity_id'] = str_replace('#', '', $data['entity_id']);
        }
        if (isset($data['order_id'])) {
            $data['order_id'] = str_replace('#', '', $data['order_id']);
        }

        return $data;
    }

    /**
     * Return comments data.
     *
     * @return array
     */
    protected function getCommentData()
    {
        $comments = $this->_rootElement->getElements($this->commentHistory);
        $data = [];

        foreach ($comments as $comment) {
            $data[] = [
                'comment' => trim($comment->find($this->commentText)->getText()),
            ];
        }

        return $data;
    }
}
