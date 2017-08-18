<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Files that excluded from results
 */
return [
  //example  ['Magento_Backend', 'Model/View.php'],
    ['Magento_AdvancedCheckout', 'Test/Unit/Block/Adminhtml/Manage/Accordion/WishlistTest.php'],
    ['Magento_AdvancedCheckout', 'Test/Unit/Model/ImportTest.php'],
    ['Magento_CustomerFinance', 'Test/Unit/Model/Export/Customer/FinanceTest.php'],
    ['Magento_CustomerSegment', 'Test/Unit/Block/Adminhtml/Customersegment/GridTest.php'],
    ['Magento_CustomerSegment', 'Test/Unit/Block/Adminhtml/Customersegment/Grid/ChooserTest.php'],
    ['Magento_ScheduledImportExport', 'Test/Unit/Model/Scheduled/OperationTest.php'],
    ['Magento_Swatches', 'Test/Unit/Helper/MediaTest.php'],
    ['Magento_CustomerFinance', 'Model/Export/Customer/Finance.php'],
    ['Magento_EncryptionKey', 'Controller/Adminhtml/Crypt/Key/Index.php'],
    ['Magento_EncryptionKey', 'Model/ResourceModel/Key/Change.php'],
    ['Magento_EncryptionKey', 'Test/Unit/Model/ResourceModel/Key/ChangeTest.php']
];
