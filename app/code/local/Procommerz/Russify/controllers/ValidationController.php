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
        $address = Mage::app()->getRequest()->getParam('address');
        $model = Mage::getModel('procommerz_russify/russify')->validate($address);

        $result = $this->getLayout()->createBlock('procommerz_russify/sales_order_view_russifyResult')->toHtml();

        $this->getResponse()->setHeader('Content-Type', 'application/json', true);
        $this->getResponse()->setBody(Zend_Json::encode($result));

        return $this;
    }

    public function updateAction()
    {

    }
}