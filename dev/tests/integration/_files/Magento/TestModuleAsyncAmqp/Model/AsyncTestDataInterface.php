<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\TestModuleAsyncAmqp\Model;

interface AsyncTestDataInterface
{
    /**
     * set path to tmp directory.
     *
     * @param string $path
     * @return void
     */
    public function setTextFilePath($path);

    /**
     * @return string
     */
    public function getTextFilePath();

    /**
     * @param string $strValue
     * @return void
     */
    public function setValue($strValue);

    /**
     * @return string
     */
    public function getValue();
}
