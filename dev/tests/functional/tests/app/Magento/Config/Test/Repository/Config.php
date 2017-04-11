<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

namespace Magento\Config\Test\Repository;

use Magento\Mtf\Repository\AbstractRepository;

/**
 * Magento configuration settings
 */
class Config extends AbstractRepository
{
    /**
     * Configuration option constants
     */
    const EXCLUDING_TAX = 1;
    const NO_VALUE = 0;
    const YES_VALUE = 1;

    /**
     * Config repository constructor
     *
     * @param array $defaultConfig
     * @param array $defaultData
     */
    public function __construct(array $defaultConfig = [], array $defaultData = [])
    {
        $this->_data['default'] = [
            'config' => $defaultConfig,
            'data' => $defaultData,
        ];
        // General
        $this->_data['general_store_information'] = $this->getGeneralStoreGermany();
        // Currency Setup
        $this->_data['currency_usd'] = $this->_getCurrencyUSD();
        //Tax
        $this->_data['default_tax_config'] = $this->_getDefaultTax();
        $this->_data['us_tax_config'] = $this->_getUsTax();
        $this->_data['display_price'] = $this->_getPriceDisplay();
        $this->_data['display_shopping_cart'] = $this->_getShoppingCartDisplay();
        //Payment methods
        $this->_data['paypal_express'] = $this->_getPaypalExpress();
        $this->_data['paypal_direct'] = $this->_getPaypalDirect();
        $this->_data['paypal_disabled_all_methods'] = $this->_getPaypalDisabled();
        $this->_data['disable_all_payment_methods'] = $this->_getDisableAllPayment();
        $this->_data['paypal_payflow_link'] = $this->_getPaypalPayFlowLink();
        $this->_data['paypal_payflow_pro'] = $this->_getPaypalPayFlowPro();
        $this->_data['paypal_advanced'] = $this->_getPaypalAdvanced();
        $this->_data['paypal_standard'] = $this->_getPaypalStandard();
        $this->_data['authorizenet_disable'] = $this->_getAuthorizeNetDisable();
        $this->_data['authorizenet'] = $this->_getAuthorizeNet();
        $this->_data['authorizenet_direct_post'] = $this->_getAuthorizeNetDirectPost();
        $this->_data['paypal_payflow'] = $this->_getPayPalPayflow();
        $this->_data['zero_subtotal_checkout'] = $this->_getZeroSubtotalCheckout();
        $this->_data['cash_on_delivery'] = $this->_getCashOnDelivery();
        $this->_data['bank_transfer_payment'] = $this->_getBankTransferPayment();
        $this->_data['purchase_order'] = $this->_getPurchaseOrder();
        //Shipping settings
        $this->_data['shipping_origin_us'] = $this->_getShippingOriginUS();
        //Shipping methods
        $this->_data['flat_rate'] = $this->_getFlatRate();
        $this->_data['free_shipping'] = $this->_getFreeShipping();
        $this->_data['shipping_disable_all_carriers'] = $this->_disableAllShippingCarriers();
        $this->_data['shipping_carrier_dhl_eu'] = $this->_getShippingCarrierDhlEU();
        $this->_data['shipping_carrier_dhl_uk'] = $this->_getShippingCarrierDhlUK();
        $this->_data['shipping_carrier_fedex'] = $this->_getShippingCarrierFedex();
        $this->_data['shipping_carrier_ups'] = $this->_getShippingCarrierUps();
        $this->_data['shipping_carrier_usps'] = $this->_getShippingCarrierUsps();
        //Catalog
        $this->_data['enable_mysql_search'] = $this->_getMysqlSearchEnabled();
        $this->_data['check_money_order'] = $this->getCheckmo();
        $this->_data['show_out_of_stock'] = $this->_getShowOutOfStock();
        $this->_data['enable_product_flat'] = $this->_getProductFlatEnabled();
        $this->_data['disable_product_flat'] = $this->_getProductFlatDisabled();
        //Sales
        $this->_data['enable_map_config'] = $this->_getMapEnabled();
        $this->_data['disable_secret_key'] = $this->_getSecretKeyEnabled();
        $this->_data['disable_map_config'] = $this->_getMapDisabled();
        $this->_data['enable_rma'] = $this->_getRmaEnabled();
        //Customer
        $this->_data['address_template'] = $this->_getAddressTemplate();
        $this->_data['customer_disable_group_assign'] = $this->getDisableGroupAssignData();
        //Currency Setup
        $this->_data['allowed_currencies'] = $this->_getAllowedCurrencies();
        // Startup Page
        $this->_data['startup_page_dashboard'] = $this->_getStartupPage('Magento_Backend::dashboard');
        $this->_data['startup_page_products'] = $this->_getStartupPage('Magento_Catalog::catalog_products');
        // Application state1 configuration. Content Management
        $this->_data['app_state1_configuration'] = $this->_getAppState1Configuration();
    }

    /**
     * Set Purchase Order
     *
     * @return array
     */
    protected function _getPurchaseOrder()
    {
        return [
            'data' => [
                'sections' => [
                    'payment' => [
                        'section' => 'payment',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'purchaseorder' => [
                                'fields' => [
                                    'title' => [
                                        'value' => 'Purchase Order',
                                    ],
                                    'active' => [
                                        'value' => self::YES_VALUE,
                                    ],
                                    'order_status' => [
                                        'value' => 'pending',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * Set Bank Transfer Payment
     *
     * @return array
     */
    protected function _getBankTransferPayment()
    {
        return [
            'data' => [
                'sections' => [
                    'payment' => [
                        'section' => 'payment',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'banktransfer' => [
                                'fields' => [
                                    'title' => [
                                        'value' => 'Bank Transfer Payment',
                                    ],
                                    'active' => [
                                        'value' => self::YES_VALUE,
                                    ],
                                    'order_status' => [
                                        'value' => 'pending',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * Set Cash On Delivery Payment
     *
     * @return array
     */
    protected function _getCashOnDelivery()
    {
        return [
            'data' => [
                'sections' => [
                    'payment' => [
                        'section' => 'payment',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'cashondelivery' => [
                                'fields' => [
                                    'title' => [
                                        'value' => 'Cash On Delivery',
                                    ],
                                    'active' => [
                                        'value' => self::YES_VALUE,
                                    ],
                                    'order_status' => [
                                        'value' => 'pending',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * Set Zero Subtotal Checkout
     *
     * @return array
     */
    protected function _getZeroSubtotalCheckout()
    {
        return [
            'data' => [
                'sections' => [
                    'payment' => [
                        'section' => 'payment',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'free' => [
                                'fields' => [
                                    'title' => [
                                        'value' => 'No Payment Information Required',
                                    ],
                                    'active' => [
                                        'value' => self::YES_VALUE,
                                    ],
                                    'order_status' => [
                                        'value' => 'pending',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * Set Currency to value passed in.
     *
     * @return array
     */
    protected function _getCurrencyUSD()
    {
        return [
            'data' => [
                'sections' => [
                    'currency' => [
                        'section' => 'currency',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'options' => [
                                'fields' => [
                                    'allow' => [ //Allowed Currencies
                                        'value' => ['USD'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * Set Shipping Settings Origin to US.
     *
     * @return array
     */
    protected function _getShippingOriginUS()
    {
        return [
            'data' => [
                'sections' => [
                    'shipping' => [
                        'section' => 'shipping',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'origin' => [
                                'fields' => [
                                    'country_id' => [ //Country
                                        'value' => 'US',
                                    ],
                                    'region_id' => [ //Region/State
                                        'value' => '12' //California
                                    ],
                                    'postcode' => [ //Zip/Postal Code
                                        'value' => '90232',
                                    ],
                                    'city' => [ //City
                                        'value' => 'Culver City',
                                    ],
                                    'street_line1' => [ //Street Address
                                        'value' => '10441 Jefferson Blvd',
                                    ],
                                    'street_line2' => [ //Street Address Line 2
                                        'value' => 'Suite 200',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    protected function _getFreeShipping()
    {
        return [
            'data' => [
                'sections' => [
                    'carriers' => [
                        'section' => 'carriers',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'freeshipping' => [ //Free Shipping
                                'fields' => [
                                    'active' => [ //Enabled
                                        'value' => self::YES_VALUE,
                                    ],
                                    'free_shipping_subtotal' => [ //Minimum Order Amount
                                        'value' => 10,
                                    ],
                                    'sallowspecific' => [ //Ship to Applicable Countries
                                        'value' => 1 //Specific Countries
                                    ],
                                    'specificcountry' => [ //Ship to Applicable Countries
                                        'value' => 'US' //United States
                                    ],
                                    'showmethod' => [ //Show Method if Not Applicable
                                        'value' => self::YES_VALUE,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    protected function _getFlatRate()
    {
        return [
            'data' => [
                'sections' => [
                    'carriers' => [
                        'section' => 'carriers',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'flatrate' => [ //Flat Rate
                                'fields' => [
                                    'active' => [ //Enabled
                                        'value' => self::YES_VALUE,
                                    ],
                                    'price' => [ //Price
                                        'value' => 5,
                                    ],
                                    'type' => [ //Type
                                        'value' => 'I' //Per Item
                                    ],
                                    'sallowspecific' => [ //Ship to Applicable Countries
                                        'value' => 0 //All Allowed Countries
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * DHL International shipping method configuration.
     * Specifically for testing shipping origin of EA (Europe) Region.
     * Shipping origin is specifically set to Switzerland with currency of Swiss Franc.
     *
     * @return array
     */
    protected function _getShippingCarrierDhlEU()
    {
        return [
            'data' => [
                'sections' => [
                    'carriers' => [
                        'section' => 'carriers',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'dhl' => [
                                'fields' => [
                                    'active' => [ //Enabled for Checkout
                                        'value' => self::YES_VALUE,
                                    ],
                                    'gateway_url' => [ //Gateway URL
                                        'value' => 'https://xmlpitest-ea.dhl.com/XMLShippingServlet',
                                    ],
                                    'id' => [ //Access ID
                                        'value' => 'EvgeniyDE',
                                    ],
                                    'password' => [ //Password
                                        'value' => 'aplNb6Rop',
                                    ],
                                    'account' => [ //Account Number
                                        'value' => '152691811',
                                    ],
                                    'showmethod' => [ //Show Method if Not Applicable
                                        'value' => self::YES_VALUE,
                                    ],
                                    'debug' => [ //Debug
                                        'value' => self::YES_VALUE,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'shipping' => [
                        'section' => 'shipping',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'origin' => [
                                'fields' => [
                                    'country_id' => [ //Country
                                        'value' => 'CH' //Switzerland
                                    ],
                                    'region_id' => [ //Region/State
                                        'value' => '107' //Bern
                                    ],
                                    'postcode' => [ //Zip/Postal Code
                                        'value' => '3005',
                                    ],
                                    'city' => [ //City
                                        'value' => 'Bern',
                                    ],
                                    'street_line1' => [ //Street Address
                                        'value' => 'Weinbergstrasse 4',
                                    ],
                                    'street_line2' => [ //Street Address Line 2
                                        'value' => 'Suite 1',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'currency' => [
                        'section' => 'currency',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'options' => [
                                'fields' => [
                                    'allow' => [ //Allowed Currencies
                                        'value' => ['USD', 'CHF'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * DHL International shipping method configuration.
     * Specifically for testing domestic shipping within the UK.
     *
     * @return array
     */
    protected function _getShippingCarrierDhlUK()
    {
        return [
            'data' => [
                'sections' => [
                    'carriers' => [
                        'section' => 'carriers',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'dhl' => [
                                'fields' => [
                                    'active' => [ //Enabled for Checkout
                                        'value' => self::YES_VALUE,
                                    ],
                                    'gateway_url' => [ //Gateway URL
                                        'value' => 'https://xmlpitest-ea.dhl.com/XMLShippingServlet',
                                    ],
                                    'id' => [ //Access ID
                                        'value' => 'EvgeniyDE',
                                    ],
                                    'password' => [ //Password
                                        'value' => 'aplNb6Rop',
                                    ],
                                    'account' => [ //Account Number
                                        'value' => '152691811',
                                    ],
                                    'showmethod' => [ //Show Method if Not Applicable
                                        'value' => self::YES_VALUE,
                                    ],
                                    'debug' => [ //Debug
                                        'value' => self::YES_VALUE,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'shipping' => [
                        'section' => 'shipping',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'origin' => [
                                'fields' => [
                                    'country_id' => [ //Country
                                        'value' => 'GB' //United Kingdom
                                    ],
                                    'region_id' => [ //Region/State
                                        'value' => 'London',
                                    ],
                                    'postcode' => [ //Zip/Postal Code
                                        'value' => 'SE10 8SE',
                                    ],
                                    'city' => [ //City
                                        'value' => 'London',
                                    ],
                                    'street_line1' => [ //Street Address
                                        'value' => '89 Royal Hill',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'currency' => [
                        'section' => 'currency',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'options' => [
                                'fields' => [
                                    'allow' => [ //Allowed Currencies
                                        'value' => ['USD', 'GBP'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * Fedex shipping method configuration as well as a real US address set in specified
     * in shipping settings origin.
     *
     * @return array
     */
    protected function _getShippingCarrierFedex()
    {
        return [
            'data' => [
                'sections' => [
                    'carriers' => [
                        'section' => 'carriers',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'fedex' => [
                                'fields' => [
                                    'active' => [ //Enabled for Checkout
                                        'value' => self::YES_VALUE,
                                    ],
                                    'account' => [ //Account ID
                                        'value' => '510087801',
                                    ],
                                    'meter_number' => [ //Meter Number
                                        'value' => '100047915',
                                    ],
                                    'key' => [ //Key
                                        'value' => 'INdxa6ug7qZ2KD7y',
                                    ],
                                    'password' => [ //Password
                                        'value' => 'pTfh4K0nkHcHVginelU4HmJkA',
                                    ],
                                    'sandbox_mode' => [ //Sandbox Mode
                                        'value' => self::YES_VALUE,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'shipping' => [
                        'section' => 'shipping',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'origin' => [
                                'fields' => [
                                    'country_id' => [ //Country
                                        'value' => 'US',
                                    ],
                                    'region_id' => [ //Region/State
                                        'value' => '12' //California
                                    ],
                                    'postcode' => [ //Zip/Postal Code
                                        'value' => '90024',
                                    ],
                                    'city' => [ //City
                                        'value' => 'Los Angeles',
                                    ],
                                    'street_line1' => [ //Street Address
                                        'value' => '1419 Westwood Blvd',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    protected function _getShippingCarrierUps()
    {
        return [
            'data' => [
                'sections' => [
                    'carriers' => [
                        'section' => 'carriers',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'ups' => [
                                'fields' => [
                                    'active' => [ //Enabled for Checkout
                                        'value' => self::YES_VALUE,
                                    ],
                                    'active_rma' => [ //Enabled for RMA
                                        'value' => self::NO_VALUE,
                                    ],
                                    'type' => [ //UPS Type
                                        'value' => 'UPS_XML' //United Parcel Service XML
                                    ],
                                    'is_account_live' => [ //Live account
                                        'value' => self::NO_VALUE,
                                    ],
                                    'password' => [ //Password
                                        'value' => 'magento200',
                                    ],
                                    'username' => [ //User ID
                                        'value' => 'magento',
                                    ],
                                    'mode_xml' => [ //Mode
                                        'value' => 0 //Development
                                    ],
                                    'gateway_xml_url' => [ //Gateway XML URL
                                        'value' => 'https://wwwcie.ups.com/ups.app/xml/Rate',
                                    ],
                                    'origin_shipment' => [ //Origin of the Shipment
                                        'value' => 'Shipments Originating in United States',
                                    ],
                                    'access_license_number' => [ //Access License Number
                                        'value' => 'ECAB751ABF189ECA',
                                    ],
                                    'negotiated_active' => [ //Enable Negotiated Rates
                                        'value' => self::NO_VALUE,
                                    ],
                                    'shipper_number' => [ //Shipper Number
                                        'value' => '207W88',
                                    ],
                                    'container' => [ //Container
                                        'value' => 'CP' //Customer Packaging
                                    ],
                                    'dest_type' => [ //Destination Type
                                        'value' => 'RES' //Residential
                                    ],
                                    'tracking_xml_url' => [ //Tracking XML URL
                                        'value' => 'https://wwwcie.ups.com/ups.app/xml/Track',
                                    ],
                                    'unit_of_measure' => [ //Weight Unit
                                        'value' => 'LBS',
                                    ],
                                    'allowed_methods' => [ //Allowed Methods
                                        //Select all
                                        'value' => ['11', '12', '14', '54', '59', '65', '01', '02', '03', '07', '08'],
                                    ],
                                    'sallowspecific' => [ //Ship to Applicable Countries
                                        'value' => 0 //All Allowed Countries
                                    ],
                                    'showmethod' => [ //Show Method if Not Applicable
                                        'value' => self::NO_VALUE,
                                    ],
                                    'debug' => [ //Debug
                                        'value' => self::YES_VALUE,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    protected function _getShippingCarrierUsps()
    {
        return [
            'data' => [
                'sections' => [
                    'carriers' => [
                        'section' => 'carriers',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'usps' => [
                                'fields' => [
                                    'active' => [ //Enabled for Checkout
                                        'value' => self::YES_VALUE,
                                    ],
                                    'gateway_url' => [ //Gateway URL
                                        'value' => 'http://production.shippingapis.com/ShippingAPI.dll',
                                    ],
                                    'gateway_secure_url' => [ //Secure Gateway URL
                                        'value' => 'https://secure.shippingapis.com/ShippingAPI.dll',
                                    ],
                                    'userid' => [ //User ID
                                        'value' => '721FRAGR6267',
                                    ],
                                    'password' => [ //Password
                                        'value' => '326ZL84XF990',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * Disable all shipping carriers
     *
     * @return array
     */
    protected function _disableAllShippingCarriers()
    {
        return [
            'data' => [
                'sections' => [
                    'carriers' => [
                        'section' => 'carriers',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'ups' => [
                                'fields' => [
                                    'active' => [ //Enabled for Checkout
                                        'value' => self::NO_VALUE,
                                    ],
                                ],
                            ],
                            'usps' => [
                                'fields' => [
                                    'active' => [ //Enabled for Checkout
                                        'value' => self::NO_VALUE,
                                    ],
                                ],
                            ],
                            'fedex' => [
                                'fields' => [
                                    'active' => [ //Enabled for Checkout
                                        'value' => self::NO_VALUE,
                                    ],
                                ],
                            ],
                            'dhl' => [
                                'fields' => [
                                    'active' => [ //Enabled for Checkout
                                        'value' => self::NO_VALUE,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    protected function _getAuthorizeNet()
    {
        return [
            'data' => [
                'sections' => [
                    'payment' => [
                        'section' => 'payment',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'authorizenet' => [ //Credit Card (Authorize.net)
                                'fields' => [
                                    'active' => [ //Enabled
                                        'value' => self::YES_VALUE,
                                    ],
                                    'login' => [ //API Login ID
                                        'value' => '36sCtGS8w',
                                    ],
                                    'payment_action' => [ //Payment Action
                                        'value' => 'authorize',
                                    ],
                                    'trans_key' => [ //Transaction Key
                                        'value' => '"67RY59y59p25JQsZ"',
                                    ],
                                    'cgi_url' => [ //Gateway URL
                                        'value' => 'https://test.authorize.net/gateway/transact.dll',
                                    ],
                                    'test' => [ //Test Mode
                                        'value' => self::NO_VALUE,
                                    ],
                                    'cctypes' => [ //Card Types
                                        'value' => 'AE,VI,MC,DI' //American Express, Visa, MasterCard, Discover
                                    ],
                                    'useccv' => [ //Credit Card Verification
                                        'value' => self::YES_VALUE,
                                    ],
                                    'debug' => [ // Debug Mode
                                        'value' => self::YES_VALUE,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    protected function _getAuthorizeNetDirectPost()
    {
        return [
            'data' => [
                'sections' => [
                    'payment' => [
                        'section' => 'payment',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'authorizenet_directpost' => [ //authorize.net Direct Post
                                'fields' => [
                                    'active' => [ //Enabled
                                        'value' => self::YES_VALUE,
                                    ],
                                    'login' => [ //API Login ID
                                        'value' => '72nUK46WmnG',
                                    ],
                                    'payment_action' => [ //Payment Action
                                        'value' => 'authorize',
                                    ],
                                    'trans_key' => [ //Transaction Key
                                        'value' => '"2gvHY854P9P634tJ"',
                                    ],
                                    'trans_md5' => [
                                        'value' => 'mperfqa',
                                    ],
                                    'cgi_url' => [ //Gateway URL
                                        'value' => 'https://test.authorize.net/gateway/transact.dll',
                                    ],
                                    'test' => [ //Test Mode
                                        'value' => self::NO_VALUE,
                                    ],
                                    'cctypes' => [ //Card Types
                                        'value' => 'AE,VI,MC,DI' //American Express, Visa, MasterCard, Discover
                                    ],
                                    'useccv' => [ //Credit Card Verification
                                        'value' => self::YES_VALUE,
                                    ],
                                    'debug' => [ // Debug Mode
                                        'value' => self::YES_VALUE,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    protected function _getDisableAllPayment()
    {
        return [
            'data' => [
                'sections' => [
                    'payment' => [
                        'section' => 'payment',
                        'website' => null,
                        'store' => null,
                        'groups' => array_merge(
                            [
                                'cashondelivery' => [
                                    'fields' => [
                                        'active' => [
                                            'value' => self::NO_VALUE,
                                        ],
                                    ],
                                ],
                                'banktransfer' => [
                                    'fields' => [
                                        'active' => [
                                            'value' => self::NO_VALUE,
                                        ],
                                    ],
                                ],
                                'purchaseorder' => [
                                    'fields' => [
                                        'active' => [
                                            'value' => self::NO_VALUE,
                                        ],
                                    ],
                                ],
                                'authorizenet' => [
                                    'fields' => [
                                        'active' => [
                                            'value' => self::NO_VALUE,
                                        ],
                                    ],
                                ],
                                'authorizenet_directpost' => [
                                    'fields' => [
                                        'active' => [
                                            'value' => self::NO_VALUE,
                                        ],
                                    ],
                                ],
                                'free' => [
                                    'fields' => [
                                        'active' => [
                                            'value' => self::NO_VALUE,
                                        ],
                                    ],
                                ],
                                'checkmo' => [
                                    'fields' => [
                                        'active' => [
                                            'value' => self::NO_VALUE,
                                        ],
                                    ],
                                ],
                            ],
                            $this->_getPaypalDisabled()['data']['sections']['payment']['groups']
                        ),
                    ],
                ],
            ]
        ];
    }

    protected function _getPaypalDisabled()
    {
        return [
            'data' => [
                'sections' => [
                    'payment' => [
                        'section' => 'payment',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'paypal_express' => [
                                'fields' => [
                                    'active' => [
                                        'value' => self::NO_VALUE,
                                    ],
                                ],
                            ],
                            'paypal_standard' => [
                                'fields' => [
                                    'active' => [
                                        'value' => self::NO_VALUE,
                                    ],
                                ],
                            ],
                            'paypal_direct' => [
                                'fields' => [
                                    'active' => [
                                        'value' => self::NO_VALUE,
                                    ],
                                ],
                            ],
                            'payflowpro' => [
                                'fields' => [
                                    'active' => [
                                        'value' => self::NO_VALUE,
                                    ],
                                ],
                            ],
                            'payflow_express' => [
                                'fields' => [
                                    'active' => [
                                        'value' => self::NO_VALUE,
                                    ],
                                ],
                            ],
                            'payflow_advanced' => [
                                'fields' => [
                                    'active' => [
                                        'value' => self::NO_VALUE,
                                    ],
                                ],
                            ],
                            'payflow_link' => [
                                'fields' => [
                                    'active' => [
                                        'value' => self::NO_VALUE,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    protected function _getPaypalDirect()
    {
        return [
            'data' => [
                'sections' => [
                    'payment' => [
                        'section' => 'payment',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'paypal_group_all_in_one' => [ //PayPal All-in-One Payment Solutions
                                'groups' => [
                                    'wpp_usuk' => [ //Payments Pro (Includes Express Checkout)
                                        'groups' => [
                                            'wpp_required_settings' => [ //Required PayPal Settings
                                                'groups' => [
                                                    'wpp_and_express_checkout' => [ //Payments Pro and Express Checkout
                                                        'fields' => [
                                                            'business_account' => [ //Email Associated with PayPal
                                                                'value' => 'mtf_bussiness_pro@example.net',
                                                            ],
                                                            'api_authentication' => [ //API Authentication Methods
                                                                'value' => 0 //API Signature
                                                            ],
                                                            'api_username' => [ //API Username
                                                                'value' => 'mtf_bussiness_pro_api1.example.net',
                                                            ],
                                                            'api_password' => [ //API Password
                                                                'value' => '1396336783',
                                                            ],
                                                            'api_signature' => [ //API Signature
                                                                'value' => 'Ai4aunchzf-e-FeWoRkUYBBHvZciAXN6kt7.wD1oGG-uZPAcDD1wcP4Y',
                                                            ],
                                                            'sandbox_flag' => [ //Sandbox Mode
                                                                'value' => self::YES_VALUE,
                                                            ],
                                                            'use_proxy' => [ //API Uses Proxy
                                                                'value' => self::NO_VALUE,
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                                'fields' => [
                                                    'enable_wpp' => [ //Enable this Solution
                                                        'value' => self::YES_VALUE,
                                                    ],
                                                ],
                                            ],
                                            'wpp_settings' => [ //Basic Settings - PayPal Payments Pro
                                                'fields' => [
                                                    'payment_action' => [ //Payment Action
                                                        'value' => 'Authorization' //Authorization
                                                    ],
                                                ],
                                                'groups' => [
                                                    'wpp_settings_advanced' => [
                                                        'fields' => [
                                                            'debug' => [ // Debug Mode
                                                                'value' => self::YES_VALUE,
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'paypal_express' => [
                                'fields' => [
                                    'active' => [
                                        'value' => self::YES_VALUE,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    protected function _getPaypalAdvanced()
    {
        return [
            'data' => [
                'sections' => [
                    'payment' => [
                        'section' => 'payment',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'paypal_group_all_in_one' => [ //PayPal All-in-One Payment Solutions
                                'groups' => [
                                    'payflow_advanced' => [ //PayPal Payments Advanced (Includes Express Checkout)
                                        'groups' => [
                                            'required_settings' => [ //Required PayPal Settings
                                                'groups' => [
                                                    'payments_advanced' => [ //Payments Pro and Express Checkout
                                                        'fields' => [
                                                            'business_account' => [ //Email Associated with PayPal
                                                                'value' => 'rlus_1349181941_biz@ebay.com',
                                                            ],
                                                            'partner' => [ //Partner
                                                                'value' => 'PayPal',
                                                            ],
                                                            'vendor' => [ //Vendor
                                                                'value' => 'mpiteamadvanced123',
                                                            ],
                                                            'user' => [ //User
                                                                'value' => 'mpiteamadvanced123',
                                                            ],
                                                            'pwd' => [ //Password
                                                                'value' => 'Temp1234',
                                                            ],
                                                            'sandbox_flag' => [ //Test Mode
                                                                'value' => self::YES_VALUE,
                                                            ],
                                                            'use_proxy' => [ //Use Proxy
                                                                'value' => self::NO_VALUE,
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                                'fields' => [
                                                    'enable_payflow_advanced' => [ //Enable this Solution
                                                        'value' => self::YES_VALUE,
                                                    ],
                                                ],
                                            ],
                                            'settings_payments_advanced' => [ //Basic Settings - PayPal Payments Pro
                                                'fields' => [
                                                    'payment_action' => [ //Payment Action
                                                        'value' => 'Authorization' //Authorization
                                                    ],
                                                ],
                                            ],
                                            'settings_express_checkout' => [
                                                'groups' => [
                                                    'settings_express_checkout_advanced' => [ // Advanced Settings
                                                        'fields' => [
                                                            'debug' => [
                                                                'value' => self::YES_VALUE, // Debug Mode
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'paypal_express' => [
                                'fields' => [
                                    'active' => [
                                        'value' => self::YES_VALUE,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    protected function _getPaypalStandard()
    {
        return [
            'data' => [
                'sections' => [
                    'payment' => [
                        'section' => 'payment',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'paypal_group_all_in_one' => [ //PayPal All-in-One Payment Solutions
                                'groups' => [
                                    'wps_usuk' => [ //Payments Standard
                                        'groups' => [
                                            'wps_required_settings' => [ //Required PayPal Settings
                                                'fields' => [
                                                    'business_account' => [ //Email Associated with PayPal Merchant Account
                                                        'value' => 'rlus_1349181941_biz@ebay.com',
                                                    ],
                                                    'enable_wps' => [ //Enable this Solution
                                                        'value' => 1 //Yes
                                                    ],
                                                ],
                                            ],
                                            'settings_payments_standart' => [ //Basic Settings - PayPal Payments Standard
                                                'fields' => [
                                                    'payment_action' => [ //Payment Action
                                                        'value' => 'Sale' //Sale
                                                    ],
                                                ],
                                                'groups' => [
                                                    'settings_payments_standart_advanced' => [
                                                        'fields' => [
                                                            'allowspecific' => [ // Payment Applicable From
                                                                'value' => 'All Allowed Countries',
                                                            ],
                                                            'sandbox_flag' => [ //Sandbox Mode
                                                                'value' => 1 //Yes
                                                            ],
                                                            'line_items_enabled' => [ //Transfer Cart Line Items
                                                                'value' => 1 //Yes
                                                            ],
                                                            'verify_peer' => [ //Enable SSL verification
                                                                'value' => 0 //No
                                                            ],
                                                            'debug' => [ // Debug Mode
                                                                'value' => self::YES_VALUE,
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    protected function _getPaypalExpress()
    {
        return [
            'data' => [
                'sections' => [
                    'payment' => [
                        'section' => 'payment',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'paypal_alternative_payment_methods' => [ //PayPal Express Checkout
                                'groups' => [
                                    'express_checkout_us' => [ //Express Checkout
                                        'groups' => [
                                            'express_checkout_required' => [ //Required PayPal Settings
                                                'groups' => [
                                                    'express_checkout_required_express_checkout' => [ //Express Checkout
                                                        'fields' => [
                                                            'business_account' => [ //Email Associated with PayPal
                                                                'value' => 'paymentspro@biz.com',
                                                            ],
                                                            'api_authentication' => [ //API Authentication Methods
                                                                'value' => 0 //API Signature
                                                            ],
                                                            'api_username' => [ //API Username
                                                                'value' => 'paymentspro_api1.biz.com',
                                                            ],
                                                            'api_password' => [ //API Password
                                                                'value' => '1369911703',
                                                            ],
                                                            'api_signature' => [ //API Signature
                                                                'value' => 'AOolWQExAt2k.RZzqZ6i6hWlSW4vAnkvVXvL8r1P-kXgqaV7sfD.ftNQ',
                                                            ],
                                                            'sandbox_flag' => [ //Sandbox Mode
                                                                'value' => self::YES_VALUE,
                                                            ],
                                                            'use_proxy' => [ //API Uses Proxy
                                                                'value' => self::NO_VALUE,
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                                'fields' => [
                                                    'enable_express_checkout' => [ //Enable this Solution
                                                        'value' => self::YES_VALUE,
                                                    ],
                                                ],
                                            ],
                                            'settings_ec' => [ //Basic Settings - PayPal Payments Pro
                                                'fields' => [
                                                    'payment_action' => [ //Payment Action
                                                        'value' => 'Authorization' //Authorization
                                                    ],
                                                ],
                                                'groups' => [
                                                    'settings_ec_advanced' => [
                                                        'fields' => [
                                                            'debug' => [ // Debug Mode
                                                                'value' => self::YES_VALUE,
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * Get Configuration Settings for PayPal Payflow Link Payment Method
     *
     * @return array
     */
    protected function _getPaypalPayFlowLink()
    {
        return [
            'data' => [
                'sections' => [
                    'payment' => [
                        'section' => 'payment',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'paypal_payment_gateways' => [ // PayPal Payment Gateways
                                'groups' => [
                                    'payflow_link_us' => [ // Payflow Link(Includes Express Checkout)
                                        'groups' => [
                                            'payflow_link_required' => [ // Required Paypal Settings
                                                'groups' => [
                                                    'payflow_link_payflow_link' => [ // Payflow Link and Express Checkout
                                                        'fields' => [
                                                            'business_account' => [ // Email Associated with PayPal Merchant Account
                                                                'value' => 'mtf_payflowlink@ebay.com',
                                                            ],
                                                            'partner' => [ // Partner
                                                                'value' => 'PayPal',
                                                            ],
                                                            'user' => [ // API User
                                                                'value' => 'mtfpayflowlink',
                                                            ],
                                                            'vendor' => [ // Vendor
                                                                'value' => 'mtfpayflowlink',
                                                            ],
                                                            'pwd' => [ // API Password
                                                                'value' => '123123mtf',
                                                            ],
                                                            'sandbox_flag' => [ // Test Mode
                                                                'value' => self::YES_VALUE,
                                                            ],
                                                            'use_proxy' => [ // Use Proxy
                                                                'value' => self::NO_VALUE,
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                                'fields' => [
                                                    'enable_payflow_link' => [ // Enable this solution
                                                        'value' => self::YES_VALUE,
                                                    ],
                                                    'enable_express_checkout' => [ // Enable this solution
                                                        'value' => self::YES_VALUE,
                                                    ],
                                                ],
                                            ],
                                            'settings_payflow_link' => [ // Basic Settings - PayPal Payflow Link
                                                'fields' => [
                                                    'payment_action' => [ // Payment Action
                                                        'value' => 'Authorization',
                                                    ],
                                                ],
                                                'groups' => [
                                                    'settings_payflow_link_advanced' => [
                                                        'fields' => [
                                                            'debug' => [ // Debug Mode
                                                                'value' => self::YES_VALUE,
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                            'settings_payflow_link_express_checkout' => [ // Basic Settings - PayPal Express Checkout
                                                'fields' => [
                                                    'payment_action' => [ // Payment Action
                                                        'value' => 'Authorization',
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * Get Configuration Settings for PayPal Payflow Pro Payment Method
     *
     * @return array
     */
    protected function _getPaypalPayFlowPro()
    {
        return [
            'data' => [
                'sections' => [
                    'payment' => [
                        'section' => 'payment',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'paypal_payment_gateways' => [ // PayPal Payment Gateways
                                'groups' => [
                                    'paypal_payflowpro_with_express_checkout' => [ // Payflow Pro (Includes Express Checkout)
                                        'groups' => [
                                            'paypal_payflow_required' => [ // Required Paypal Settings
                                                'groups' => [
                                                    'paypal_payflow_api_settings' => [ // Payflow Pro and Express Checkout
                                                        'fields' => [
                                                            'business_account' => [ // Email Associated with PayPal Merchant Account
                                                                'value' => 'pro_em_1350644409_biz@ebay.com',
                                                            ],
                                                            'partner' => [ // Partner
                                                                'value' => 'PayPal',
                                                            ],
                                                            'user' => [ // API User
                                                                'value' => 'empayflowpro',
                                                            ],
                                                            'vendor' => [ // Vendor
                                                                'value' => 'empayflowpro',
                                                            ],
                                                            'pwd' => [ // API Password
                                                                'value' => 'Temp1234',
                                                            ],
                                                            'sandbox_flag' => [ // Test Mode
                                                                'value' => self::YES_VALUE,
                                                            ],
                                                            'use_proxy' => [ // Use Proxy
                                                                'value' => self::NO_VALUE,
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                                'fields' => [
                                                    'enable_paypal_payflow' => [ //Enable this Solution
                                                        'value' => self::YES_VALUE,
                                                    ],
                                                ],
                                            ],
                                            'settings_paypal_payflow' => [ // Basic Settings - PayPal Payflow Pro
                                                'fields' => [
                                                    'payment_action' => [ // Payment Action
                                                        'value' => 'Authorization',
                                                    ],
                                                ],
                                                'groups' => [
                                                    'settings_paypal_payflow_advanced' => [
                                                        'fields' => [
                                                            'debug' => [ // Debug Mode
                                                                'value' => self::YES_VALUE,
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'payflow_express' => [
                                'fields' => [
                                    'active' => [
                                        'value' => self::YES_VALUE,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * Provide Configuration for Default Tax settings
     *
     * @return array
     */
    protected function _getDefaultTax()
    {
        return [
            'data' => [
                'sections' => [
                    'tax' => [
                        'section' => 'tax',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'calculation' => [
                                'fields' => [
                                    'algorithm' => [ //Tax Calculation Method Based On
                                        'value' => 'TOTAL_BASE_CALCULATION' //Total
                                    ],
                                    'based_on' => [ //Tax Calculation Based On
                                        'value' => 'shipping' //Shipping Address
                                    ],
                                    'price_includes_tax' => [ //Catalog Prices
                                        'value' => 0 //Excluding Tax
                                    ],
                                    'apply_after_discount' => [ //Apply Customer Tax
                                        'value' => 0 //Before Discount
                                    ],
                                    'discount_tax' => [ //Apply Discount On Prices
                                        'value' => 0 //Excluding Tax
                                    ],
                                    'apply_tax_on' => [ //Apply Tax On
                                        'value' => 0 //Custom Price if available
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * Provides Price Display Configuration
     *
     * @return array
     */
    protected function _getPriceDisplay()
    {
        return [
            'data' => [
                'sections' => [
                    'tax' => [
                        'section' => 'tax',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'display' => [ // Price Display Settings
                                'fields' => [
                                    'type' => [ // Display Product Prices In Catalog
                                        'value' => self::EXCLUDING_TAX,
                                    ],
                                    'shipping' => [ // Display Shipping Prices
                                        'value' => self::EXCLUDING_TAX,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * Provides Shopping Cart Display Configuration
     *
     * @return array
     */
    protected function _getShoppingCartDisplay()
    {
        return [
            'data' => [
                'sections' => [
                    'tax' => [
                        'section' => 'tax',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'cart_display' => [ // Shipping Cart Display Settings
                                'fields' => [
                                    'price' => [ // Display Prices
                                        'value' => self::EXCLUDING_TAX,
                                    ],
                                    'subtotal' => [ // Display Subtotal
                                        'value' => self::EXCLUDING_TAX,
                                    ],
                                    'shipping' => [ // Display Shipping Amount
                                        'value' => self::EXCLUDING_TAX,
                                    ],
                                    'gift_wrapping' => [ // Display Gift Wrapping Prices
                                        'value' => self::EXCLUDING_TAX,
                                    ],
                                    'printed_card' => [ // Display Printed Card Prices
                                        'value' => self::EXCLUDING_TAX,
                                    ],
                                    'grandtotal' => [ // Include Tax In Grand Total
                                        'value' => self::NO_VALUE,
                                    ],
                                    'full_summary' => [ // Display Full Tax Summary
                                        'value' => self::NO_VALUE,
                                    ],
                                    'zero_tax' => [ // Display Zero Tax Subtotal
                                        'value' => self::NO_VALUE,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * Provides taxes for US configuration based on default tax configuration
     *
     * @return array
     */
    protected function _getUsTax()
    {
        return $this->_getDefaultTax();
    }

    /**
     * Data for PayPal Payflow Edition method
     */
    protected function _getPayPalPayflow()
    {
        return [
            'data' => [
                'sections' => [
                    'payment' => [
                        'section' => 'payment',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'paypal_payment_gateways' => [
                                'groups' => [
                                    'paypal_payflowpro_with_express_checkout' => [
                                        'groups' => [
                                            'paypal_payflow_required' => [
                                                'groups' => [
                                                    'paypal_payflow_api_settings' => [
                                                        'fields' => [
                                                            'business_account' => [
                                                                'value' => 'pro_em_1350644409_biz@ebay.com',
                                                            ],
                                                            'partner' => [
                                                                'value' => 'PayPal',
                                                            ],
                                                            'user' => [
                                                                'value' => 'empayflowpro',
                                                            ],
                                                            'vendor' => [
                                                                'value' => 'empayflowpro',
                                                            ],
                                                            'pwd' => [
                                                                'value' => 'Temp1234',
                                                            ],
                                                            'sandbox_flag' => [
                                                                'value' => 1,
                                                            ],
                                                            'enable_paypal_payflow' => [
                                                                'value' => 1,
                                                            ],
                                                            'use_proxy' => [
                                                                'value' => 0,
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                            'settings_paypal_payflow' => [
                                                'groups' => [
                                                    'fields' => [
                                                        'payment_action' => [
                                                            'value' => 'Authorization',
                                                        ],
                                                    ],
                                                    'settings_paypal_payflow_advanced' => [
                                                        'fields' => [
                                                            'useccv' => [
                                                                'value' => 1,
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * Disable authorizenet payment
     *
     * @return array
     */
    protected function _getAuthorizeNetDisable()
    {
        return [
            'data' => [
                'sections' => [
                    'payment' => [
                        'section' => 'payment',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'authorizenet' => [ //Credit Card (Authorize.net)
                                'fields' => [
                                    'active' => [ //Enabled
                                        'value' => self::NO_VALUE,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * Enable Mysql search
     *
     * @return array
     */
    protected function _getMysqlSearchEnabled()
    {
        return [
            'data' => [
                'sections' => [
                    'catalog' => [
                        'section' => 'catalog',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'search' => [
                                'fields' => [
                                    'engine' => [
                                        //MySql Fulltext
                                        'value' => 'mysql',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * Enable Check/Money order
     *
     * @return array
     */
    protected function getCheckmo()
    {
        return [
            'data' => [
                'sections' => [
                    'payment' => [
                        'section' => 'payment',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'checkmo' => [ //Credit Card (Authorize.net)
                                'fields' => [
                                    'active' => [
                                        'value' => self::YES_VALUE,
                                    ],
                                    'order_status' => [
                                        'value' => 'pending', //New Order Status
                                    ],
                                    'allowspecific' => [
                                        'value' => 0, //All Allowed Counries
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Enable MAP
     *
     * @return array
     */
    protected function _getMapEnabled()
    {
        return [
            'data' => [
                'sections' => [
                    'sales' => [
                        'section' => 'sales',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'msrp' => [ //Minimum Advertised Price)
                                'fields' => [
                                    'enabled' => [ //Enabled
                                        'value' => 1 //Yes
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * Disable MAP
     *
     * @return array
     */
    protected function _getMapDisabled()
    {
        return [
            'data' => [
                'sections' => [
                    'sales' => [
                        'section' => 'sales',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'msrp' => [ //Minimum Advertised Price)
                                'fields' => [
                                    'enabled' => [ //Disabled
                                        'value' => 0 //No
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * Data for address template configuration
     *
     * @return array
     */
    protected function _getAddressTemplate()
    {
        return [
            'data' => [
                'sections' => [
                    'customer' => [
                        'section' => 'customer',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'address_templates' => [
                                'fields' => [
                                    'oneline' => [
                                        'value' => '{{var firstname}} {{var lastname}}, {{var street}}, {{var city}},'
                                            . ' {{var region}} {{var postcode}}, {{var country}}',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * Disable Secret Key
     *
     * @return array
     */
    protected function _getSecretKeyEnabled()
    {
        return [
            'data' => [
                'sections' => [
                    'admin' => [
                        'section' => 'admin',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'security' => [
                                'fields' => [
                                    'use_form_key' => [
                                        'value' => '0',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * General store and country options settings
     *
     * @return array
     */
    public function getGeneralStoreGermany()
    {
        return [
            'data' => [
                'sections' => [
                    'general' => [
                        'section' => 'general',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'store_information' => [
                                'fields' => [
                                    'name' => [
                                        'value' => 'Test',
                                    ],
                                    'phone' => [
                                        'value' => '630-371-7008',
                                    ],
                                    'country_id' => [
                                        'value' => 'DE',
                                    ],
                                    'region_id' => [
                                        'value' => 82,
                                    ],
                                    'postcode' => [
                                        'value' => '10789',
                                    ],
                                    'city' => [
                                        'value' => 'Berlin',
                                    ],
                                    'street_line1' => [
                                        'value' => 'Augsburger Strabe 41',
                                    ],
                                    'merchant_vat_number' => [
                                        'value' => '111607872',
                                    ],
                                ],
                            ],
                            'country' => [
                                'fields' => [
                                    'eu_countries' => [
                                        'value' => ['FR', 'DE', 'GB'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Get data for disable automatic assignment customer to customer group
     *
     * @return array
     */
    public function getDisableGroupAssignData()
    {
        return [
            'data' => [
                'sections' => [
                    'customer' => [
                        'section' => 'customer',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'create_account' => [
                                'fields' => [
                                    'auto_group_assign' => [
                                        'value' => self::NO_VALUE,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Set allowed currency
     *
     * @return array
     */
    protected function _getAllowedCurrencies()
    {
        return [
            'data' => [
                'sections' => [
                    'currency' => [
                        'section' => 'currency',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'options' => [
                                'fields' => [
                                    'allow' => [
                                        'value' => ['EUR', 'USD'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * Enable RMA
     *
     * @return array
     */
    protected function _getRmaEnabled()
    {
        return [
            'data' => [
                'sections' => [
                    'sales' => [
                        'section' => 'sales',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'magento_rma' => [ //RMA
                                'fields' => [
                                    'enabled' => [ //Enabled
                                        'value' => 1 //Yes
                                    ],
                                    'enabled_on_product' => [ //Enabled
                                        'value' => 1 //Yes
                                    ],
                                    'use_store_address' => [ //Disabled
                                        'value' => 0 //No
                                    ],
                                    'store_name' => [
                                        'value' => 'Return Store',
                                    ],
                                    'address' => [
                                        'value' => 'Main Street 1',
                                    ],
                                    'city' => [ //New York
                                        'value' => 'New York',
                                    ],
                                    'region_id' => [ // New York
                                        'value' => '43',
                                    ],
                                    'zip' => [
                                        'value' => '10010',
                                    ],
                                    'country_id' => [ // United States
                                        'value' => 'US',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * Enable product flat
     *
     * @return array
     */
    protected function _getProductFlatEnabled()
    {
        return [
            'data' => [
                'sections' => [
                    'catalog' => [
                        'section' => 'catalog',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'frontend' => [ //Frontend
                                'fields' => [
                                    'flat_catalog_product' => [ //Enabled
                                        'value' => self::YES_VALUE,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * Disable product flat
     *
     * @return array
     */
    protected function _getProductFlatDisabled()
    {
        return [
            'data' => [
                'sections' => [
                    'catalog' => [
                        'section' => 'catalog',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'frontend' => [ //Frontend
                                'fields' => [
                                    'flat_catalog_product' => [ //Disabled
                                        'value' => self::NO_VALUE,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * Enable 'Display out of Stock' option for catalog
     *
     * @return array
     */
    protected function _getShowOutOfStock()
    {
        return [
            'data' => [
                'sections' => [
                    'cataloginventory' => [
                        'section' => 'cataloginventory',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'options' => [ //Stock Options
                                'fields' => [
                                    'show_out_of_stock' => [
                                        'value' => 1, //Yes
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Change Startup Page for admin user
     *
     * @param string $page
     *
     * @return array
     */
    protected function _getStartupPage($page)
    {
        return [
            'data' => [
                'sections' => [
                    'admin' => [
                        'section' => 'admin',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'startup' => [
                                'fields' => [
                                    'menu_item_id' => [
                                        'value' => $page,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * Configuration settings for Application State Profile #1
     *
     * @return array
     */
    protected function _getAppState1Configuration()
    {
        return [
            'data' => [
                'sections' => [
                    'cms' => [
                        'section' => 'cms',
                        'website' => null,
                        'store' => null,
                        'groups' => [
                            'wysiwyg' => [
                                'fields' => [
                                    'enabled' => [
                                        'value' => 'disabled',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }
}
