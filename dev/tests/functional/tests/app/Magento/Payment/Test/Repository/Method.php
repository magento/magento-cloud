<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Payment\Test\Repository;

use Magento\Mtf\Repository\AbstractRepository;

/**
 * Class Method Repository
 * Shipping methods
 *
 */
class Method extends AbstractRepository
{
    /**
     * {inheritdoc}
     */
    public function __construct(array $defaultConfig = [], array $defaultData = [])
    {
        $this->_data['default'] = [
            'config' => $defaultConfig,
            'data' => $defaultData,
        ];

        $this->_data['authorizenet'] = $this->_getAuthorizeNet();
        $this->_data['paypal_express'] = $this->_getPayPalExpress();
        $this->_data['paypal_direct'] = $this->_getPayPalDirect();
        $this->_data['paypal_payflow_pro'] = $this->_getPayPalPayflowPro();
        $this->_data['paypal_payflow_link'] = $this->_getPayPalPayflowLink();
        $this->_data['paypal_payflow_link_express'] = $this->_getPayPalPayflowLinkExpress();
        $this->_data['paypal_advanced'] = $this->_getPayPalAdvanced();
        $this->_data['paypal_standard'] = $this->_getPayPalStandard();
        $this->_data['check_money_order'] = $this->_getCheckMoneyOrder();
    }

    protected function _getAuthorizeNet()
    {
        return [
            'config' => [
                'payment_form_class' => '\\Magento\\Authorizenet\\Test\\Block\\Authorizenet\\Form\\Cc',
            ],
            'data' => [
                'fields' => [
                    'payment_code' => 'authorizenet',
                ],
            ]
        ];
    }

    protected function _getPayPalExpress()
    {
        return [
            'data' => [
                'fields' => [
                    'payment_code' => 'paypal_express',
                ],
            ]
        ];
    }

    protected function _getPayPalDirect()
    {
        return [
            'config' => [
                'payment_form_class' => '\\Magento\\Payment\\Test\\Block\\Form\\Cc',
            ],
            'data' => [
                'fields' => [
                    'payment_code' => 'paypal_direct',
                ],
            ]
        ];
    }

    /**
     * Provides Credit Card Data for PayPal Payflow Pro Method
     *
     * @return array
     */
    protected function _getPayPalPayflowPro()
    {
        return [
            'config' => [
                'payment_form_class' => '\\Magento\\Payment\\Test\\Block\\Form\\Cc',
            ],
            'data' => [
                'fields' => [
                    'payment_code' => 'payflowpro',
                ],
            ]
        ];
    }

    /**
     * Specify PayPal Payflow Link as the payment method.
     *
     * @return array
     */
    protected function _getPayPalPayflowLink()
    {
        return [
            'config' => [],
            'data' => [
                'fields' => [
                    'payment_code' => 'payflow_link',
                ],
            ]
        ];
    }

    /**
     * Provides info for PayPal Payflow Link Express Method
     *
     * @return array
     */
    protected function _getPayPalPayflowLinkExpress()
    {
        return [
            'config' => [],
            'data' => [
                'fields' => [
                    'payment_code' => 'payflow_express',
                ],
            ]
        ];
    }

    /**
     * Provides Credit Card Data for PayPal Payflow Pro Method
     *
     * @return array
     */
    protected function _getPayPalAdvanced()
    {
        return [
            'config' => [],
            'data' => [
                'fields' => [
                    'payment_code' => 'payflow_advanced',
                ],
            ]
        ];
    }

    /**
     * Provides Credit Card Data for PayPal Standard Method
     *
     * @return array
     */
    protected function _getPayPalStandard()
    {
        return [
            'config' => [],
            'data' => [
                'fields' => [
                    'payment_code' => 'paypal_standard',
                    'payment_action' => 'Sale',
                ],
            ]
        ];
    }

    /**
     * Provides Check money order data for the according payment method
     *
     * @return array
     */
    protected function _getCheckMoneyOrder()
    {
        return [
            'data' => [
                'fields' => [
                    'payment_code' => 'checkmo',
                ],
            ]
        ];
    }
}
