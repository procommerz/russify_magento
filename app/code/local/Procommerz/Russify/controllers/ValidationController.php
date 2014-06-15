<?php
/**
 * Validation Russify controller
 *
 * @package    Procommerz_Russify
 * @author     Denis Kolesnichenko <denis@mobiquest.ru>
 */
class Procommerz_Russify_ValidationController extends Mage_Adminhtml_Controller_Action
{


    public function checkAction()
    {
        $address = json_decode(Mage::app()->getRequest()->getParam('address'));
        $orderId = Mage::app()->getRequest()->getParam('orderId');
        $response = Mage::getModel('procommerz_russify/russify')->validate($address, $orderId);
        $this->loadLayout();

        $block = $this->getLayout()->createBlock('procommerz_russify/status');
        $block->setOrderId($orderId);

        if($block) {
            $alternatives = (array)$response->alternatives;
            $block->setResult($alternatives);
            $response = array();
            $response['result'] = $block->toHtml();
            $this->getResponse()->setHeader('Content-Type', 'application/json', true);
            $this->getResponse()->setBody(Zend_Json::encode($response));
        }

        return $this;
    }

    public function updateAction()
    {
        $data = unserialize(Mage::app()->getRequest()->getParam('data'));
        $orderId = Mage::app()->getRequest()->getParam('orderId');
        $shippingAddress = Mage::getModel('sales/order')->load($orderId)->getShippingAddress();
        foreach ($data as $key => $value) {
            if (isset($value)) {
                if ($key == 'zipcode') {
                    $shippingAddress->setPostcode($value);
                } elseif($key == 'address')  {
                    $shippingAddress->setStreet($value);
                } else {
                    $shippingAddress->setData($key, $value);
                }
            }
        }
        $shippingAddress->save();
        $russifyInfo = Mage::getModel('procommerz_russify/result')->getCollection()->getInfoByOrderId($orderId);
        $russifyInfo[$orderId]->setIsValidated(TRUE)->save();

        $this->loadLayout();
        $block = $this->getLayout()->createBlock('procommerz_russify/status');
        $block->setOrderId($orderId);
        if($block) {
            $response = array();
            $response['result'] = $block->toHtml();
            $response['address'] = $shippingAddress->getFormated(true);
            $this->getResponse()->setHeader('Content-Type', 'application/json', true);
            $this->getResponse()->setBody(Zend_Json::encode($response));
        }

        return $this;
    }
}