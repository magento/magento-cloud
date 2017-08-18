<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VersionsCms\Test\Handler\CmsHierarchy;

use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Handler\Curl as AbstractCurl;
use Magento\Mtf\Util\Protocol\CurlTransport;
use Magento\Mtf\Util\Protocol\CurlTransport\BackendDecorator;

/**
 * Class Curl
 * Curl handler for creating CMS Hierarchy
 */
class Curl extends AbstractCurl implements CmsHierarchyInterface
{
    /**
     * Mapping values for data.
     *
     * @var array
     */
    protected $mappingData = [
        'menu_brief' => [
            'Neighbours and Children' => 0,
        ],
        'top_menu_visibility' => [
            'No' => 0,
        ],
        'pager_visibility' => [
            'Yes' => 1,
        ],
        'meta_cs_enabled' => [
            'Yes' => 1,
        ],
        'meta_first_last' => [
            'Yes' => 1,
        ],
        'meta_next_previous' => [
            'Yes' => 1,
        ],
        'meta_chapter_section' => [
            'Both' => 'both',
        ],
        'menu_visibility' => [
            'Yes' => 1,
        ],
        'menu_ordered' => [
            'Ordered' => 1,
        ],
    ];

    /**
     * Post request for creating Cms Hierarchy
     *
     * @param FixtureInterface $fixture
     * @return array
     * @throws \Exception
     */
    public function persist(FixtureInterface $fixture = null)
    {
        $data = $this->replaceMappingData($fixture->getData());
        if (isset($data['nodes_data'])) {
            $data['nodes_data'] = $this->prepareNodes($data['nodes_data']);
            $data['nodes_data'] = json_encode($data['nodes_data']);
        }
        $url = $_ENV['app_backend_url'] . 'admin/cms_hierarchy/save/';
        $curl = new BackendDecorator(new CurlTransport(), $this->_configuration);
        $curl->write($url, $data);
        $response = $curl->read();
        $curl->close();
        if (!strpos($response, 'data-ui-id="messages-message-success"')) {
            throw new \Exception("Cms Hierarchy creation was not successful! Response: $response");
        }
        preg_match(
            '/.*node_id":"(\d+).*' . $fixture->getIdentifier() . '/',
            $response,
            $matches
        );
        $id = isset($matches[1]) ? $matches[1] : null;

        return ['id' => $id];
    }

    /**
     * @param array $nodes
     * @return array
     */
    protected function prepareNodes($nodes)
    {
        $nodeData = [];
        foreach ($nodes as $key => $node) {
            $newKey = '_' . $key;
            $nodeData[$newKey] = $node;
            foreach ($node as $nodeKey => $value) {
                $nodeData[$newKey][$nodeKey] = ($nodeKey == 'node_id') ? '_' . $value : $value;
                $nodeData[$newKey][$nodeKey] = ($nodeKey == 'parent_node_id' && $value !== '') ?
                    '_' . $value :
                    $nodeData[$newKey][$nodeKey];
            }
        }

        return $nodeData;
    }
}
