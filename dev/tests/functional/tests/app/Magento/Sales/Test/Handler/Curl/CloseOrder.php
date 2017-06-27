<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Sales\Test\Handler\Curl;

use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Handler\Curl;
use Magento\Mtf\Util\Protocol\CurlTransport;
use Magento\Mtf\Util\Protocol\CurlTransport\BackendDecorator;

/**
 * Class CloseOrder
 * Closes a sales order
 *
 */
class CloseOrder extends Curl
{
    const PAYMENT_ACTION_SALE = 'Sale';

    /**
     * @var string
     */
    protected $salesOrderUrl = 'sales/order/';

    /**
     * @var string
     */
    protected $startShipmentUrl = 'admin/order_shipment/start/order_id/';

    /**
     * @var string
     */
    protected $submitShiptmentUrl = 'admin/order_shipment/save/order_id/';

    /**
     * @var string
     */
    protected $startInvoiceUrl = 'sales/order_invoice/start/order_id/';

    /**
     * @var string
     */
    protected $submitInvoiceUrl = 'sales/order_invoice/save/order_id/';

    /**
     * @var string
     */
    protected $shipmentFieldsPattern = '/shipment\[items\]\[[a-z0-9]+\]/';

    /**
     * @var string
     */
    protected $invoiceFieldsPattern = '/invoice\[items\]\[[a-z0-9]+\]/';

    /**
     * Executes the CURL command with a given URL and data set
     *
     * @param string $url
     * @param array $data
     * @return string $response
     */
    protected function _executeCurl($url, $data = [])
    {
        $curl = new BackendDecorator(new CurlTransport(), $this->_configuration);
        $curl->addOption(CURLOPT_HEADER, 1);
        $curl->write($url, $data);
        $response = $curl->read();
        $curl->close();
        return $response;
    }

    /**
     * Populate the quantity fields with a value of 1
     *
     * @param array $elements
     * @return array
     */
    protected function _prepareData($elements)
    {
        $data = [];
        foreach ($elements as $element) {
            foreach ($element as $key) {
                $data[$key] = '1';
            }
        }
        return $data;
    }

    /**
     * Close sales order
     *
     * @param FixtureInterface $fixture [optional]
     * @return void
     * @throws \Exception
     */
    public function persist(FixtureInterface $fixture = null)
    {
        //Go to the Sales Order page and find the link for the order id to pass to the url
        $url = $_ENV['app_backend_url'] . $this->salesOrderUrl;
        $data = [];
        $response = $this->_executeCurl($url, $data);

        $searchUrl = '#sales/order/view/order_id/[0-9]+/#';

        preg_match($searchUrl, $response, $orderUrl);
        $urlSubStrings = explode('/', $orderUrl[0]);
        $orderId = $urlSubStrings[count($urlSubStrings)-2];

        //Click Ship button and create a new shipment page
        $url = $_ENV['app_backend_url'] . $this->startShipmentUrl . $orderId;
        $data = [];
        $response = $this->_executeCurl($url, $data);

        //Get the dynamic field names
        preg_match_all($this->shipmentFieldsPattern, $response, $shipmentItems);

        // Fill out the Items to ship quantities and click Submit Shipment
        $data = $this->_prepareData($shipmentItems);

        $url = $_ENV['app_backend_url'] . $this->submitShiptmentUrl . $orderId;
        $response = $this->_executeCurl($url, $data);

        if (!strpos($response, 'data-ui-id="messages-message-success"')) {
            throw new \Exception(
                "URL: $url\n" . "Submitting shipment by curl handler was not successful! Response: $response"
            );
        }

        // Click Invoice button if the payment action is not 'Sale'
        $paymentMethod = $fixture->getPaymentMethod();
        $paymentAction = null;
        if ($paymentMethod !== null) {
            $paymentAction = $paymentMethod->getPaymentAction();
        }

        if (self::PAYMENT_ACTION_SALE !== $paymentAction) {
            //Click Invoice button and create a new invoice page
            $url = $_ENV['app_backend_url'] . $this->startInvoiceUrl . $orderId;
            $data = [];
            $this->_executeCurl($url, $data);

            //Get the dynamic field names
            preg_match_all($this->invoiceFieldsPattern, $response, $invoiceItems);

            // Fill out the Items to invoice quantities and click Submit Invoice
            $data = $this->_prepareData($invoiceItems);

            //Select Capture Online
            $data['invoice[capture_case]'] = 'online';

            $url = $_ENV['app_backend_url'] . $this->submitInvoiceUrl . $orderId;
            $response = $this->_executeCurl($url, $data);

            if (!strpos($response, 'data-ui-id="messages-message-success"')) {
                throw new \Exception("Submitting Invoice by curl handler was not successful! Response: $response");
            }
        }
    }
}
