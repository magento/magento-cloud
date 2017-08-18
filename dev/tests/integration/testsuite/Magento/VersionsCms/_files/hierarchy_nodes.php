<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
use Magento\Cms\Model\Page;
use Magento\Store\Model\Store;
use Magento\VersionsCms\Model\Hierarchy\Node;

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
/** @var Store $store2 */
$store2 = $objectManager->create(Store::class);
$store2->setWebsiteId(1);
$store2->setName('Second Store');
$store2->setGroupId(1);
$store2->setCode('second_store');
$store2->save();

/** @var Page $page1 */
$page1 = $objectManager->create(Page::class);
$page1->setIdentifier('p-1');
$page1->setTitle('P 1');
$page1->setContent('bla bla bla 1');
$page1->setStoreId([1]);
$page1->save();

/** @var Page $page2 */
$page2 = $objectManager->create(Page::class);
$page2->setIdentifier('p-2');
$page2->setTitle('P 2');
$page2->setStoreId([$store2->getId()]);
$page2->setContent('bla bla bla bla 2');
$page2->save();

$nodes = [
    [
        'page_id' => $page1->getId(),
        'identifier' => 'first',
        'label' => 'First Node 1',
        'level' => 1,
        'sort_order' => 1,
        'xpath' => "//*[@class=test]",
        'scope' => "default",
        'scope_id' => 1
    ],
    [
        'page_id' => $page2->getId(),
        'identifier' => 'second',
        'label' => 'Seciond Node 2',
        'level' => 2,
        'sort_order' => 5,
        'xpath' => "//*[@class=second]",
        'scope' => "default",
        'scope_id' => 1
    ],
    [
        'page_id' => $page1->getId(),
        'identifier' => 'third',
        'label' => 'Third Node 3',
        'level' => 4,
        'sort_order' => 3,
        'xpath' => '//*[@class=test]',
        'scope' => "extended",
        'scope_id' => 2
    ],
    [
        'page_id' => $page2->getId(),
        'identifier' => 'fourth',
        'label' => 'Fourth Node 3',
        'level' => 5,
        'sort_order' => 10,
        'xpath' => "//*[@class=fourth]",
        'scope' => "extended",
        'scope_id' => 2
    ],
];

/** @var array $nodeData */
foreach ($nodes as $nodeData) {
    /** @var Node $bookmark */
    $node = $objectManager->create(Node::class);
    $node
        ->setData($nodeData)
        ->save();
}
