<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\BannerCustomerSegment\Model\ResourceModel;

class BannerSegmentLinkTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\BannerCustomerSegment\Model\ResourceModel\BannerSegmentLink
     */
    private $_resourceModel;

    protected function setUp()
    {
        $this->_resourceModel = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\BannerCustomerSegment\Model\ResourceModel\BannerSegmentLink::class
        );
    }

    protected function tearDown()
    {
        $this->_resourceModel = null;
    }

    /**
     * @magentoDataFixture Magento/Banner/_files/banner.php
     * @magentoDataFixture Magento/CustomerSegment/_files/segment_developers.php
     * @magentoDataFixture Magento/BannerCustomerSegment/_files/banner_40_percent_off_on_graphic_editor.php
     * @dataProvider saveLoadBannerSegmentsDataProvider
     * @param string $bannerName
     * @param mixed $segmentNames
     */
    public function testSaveLoadBannerSegments($bannerName, $segmentNames)
    {
        $bannerId = $this->_getBannerId($bannerName);
        $segmentIds = $segmentNames ? $this->_getSegmentIds($segmentNames) : [];

        $this->_resourceModel->saveBannerSegments($bannerId, $segmentIds);

        $actualSegmentIds = $this->_resourceModel->loadBannerSegments($bannerId);
        $this->assertEquals($segmentIds, $actualSegmentIds, '', 0, 10, true); // ignore order
    }

    public function saveLoadBannerSegmentsDataProvider()
    {
        $bannerForSegment = 'Get 40% Off on Graphic Editors';
        return [
            'initial add single' => ['Test Banner', ['Designers']],
            'initial add multiple' => ['Test Banner', ['Developers', 'Designers']],
            'override all' => [$bannerForSegment, ['Developers']],
            'add missing' => [$bannerForSegment, ['Designers', 'Developers']],
            'remove all - empty array' => [$bannerForSegment, []],
            'remove all - empty string' => [$bannerForSegment, ''],
            'remove all - null' => [$bannerForSegment, null]
        ];
    }

    /**
     * @magentoDataFixture Magento/Banner/_files/banner_disabled_40_percent_off.php
     * @magentoDataFixture Magento/Banner/_files/banner_enabled_40_to_50_percent_off.php
     * @magentoDataFixture Magento/BannerCustomerSegment/_files/banner_50_percent_off_on_ide.php
     * @magentoDataFixture Magento/BannerCustomerSegment/_files/banner_40_percent_off_on_graphic_editor.php
     * @dataProvider addBannerSegmentFilterDataProvider
     * @param array $segmentNames
     * @param array $expectedBannerNames
     */
    public function testAddBannerSegmentFilter(array $segmentNames, array $expectedBannerNames)
    {
        $expectedBannerIds = [];
        foreach ($expectedBannerNames as $bannerName) {
            $expectedBannerIds[] = $this->_getBannerId($bannerName);
        }

        /** @var \Magento\Banner\Model\ResourceModel\Salesrule\Collection $collection */
        $collection = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Banner\Model\ResourceModel\Salesrule\Collection::class
        );
        $select = $collection->getSelect();
        $initialSql = (string)$select;

        $this->_resourceModel->addBannerSegmentFilter($select, $this->_getSegmentIds($segmentNames));

        $this->assertNotEquals($initialSql, (string)$select, 'Query is expected to be modified.');
        $actualBannerIds = $select->getConnection()->fetchCol($select);
        $this->assertEquals($expectedBannerIds, $actualBannerIds, '', 0, 10, true); // ignore order
    }

    public function addBannerSegmentFilterDataProvider()
    {
        return [
            'only banners for everybody' => [[], ['Get from 40% to 50% Off on Large Orders']],
            'banners for everybody + for specific segment' => [
                ['Developers'],
                ['Get from 40% to 50% Off on Large Orders', 'Get 50% Off on Development IDEs'],
            ],
            'banners for everybody + for specific segments' => [
                ['Developers', 'Designers'],
                [
                    'Get from 40% to 50% Off on Large Orders',
                    'Get 50% Off on Development IDEs',
                    'Get 40% Off on Graphic Editors'
                ],
            ]
        ];
    }

    /**
     * Retrieve banner ID by its name
     *
     * @param string $bannerName
     * @return int|null
     */
    protected function _getBannerId($bannerName)
    {
        /** @var \Magento\Banner\Model\Banner $banner */
        $banner = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Banner\Model\Banner::class
        );
        $banner->load($bannerName, 'name');
        return $banner->getId();
    }

    /**
     * Retrieve segment IDs by names
     *
     * @param array $segmentNames
     * @return array
     */
    protected function _getSegmentIds(array $segmentNames)
    {
        $result = [];
        foreach ($segmentNames as $segmentName) {
            /** @var $segment \Magento\CustomerSegment\Model\Segment */
            $segment = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
                \Magento\CustomerSegment\Model\Segment::class
            );
            $segment->load($segmentName, 'name');
            $result[] = $segment->getId();
        }
        return $result;
    }
}
