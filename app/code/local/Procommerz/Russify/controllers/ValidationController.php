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
        $response = Mage::getModel('procommerz_russify/russify')->validate($address);
        $this->loadLayout();
        $block = $this->getLayout()->createBlock('procommerz_russify/result');

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

    public function updateAction($orderId)
    {
        $address = json_decode(Mage::app()->getRequest()->getParam('address'));
        $orderId = Mage::app()->getRequest()->getParam('orderId');

    }
}