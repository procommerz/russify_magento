<?php
/**
 * Order Russify block
 *
 * @package    Procommerz_Russify
 * @author     Denis Kolesnichenko <denis@mobiquest.ru>
 */

class  Procommerz_Russify_Block_Sales_Order_View_Russify extends Mage_Adminhtml_Block_Template
{

    /**
     * Set template
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('procommerz_russify/sales/order/view/russify.phtml');

        return $this;
    }

    /**
     * Retrieve available order
     *
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        if ($this->hasOrder()) {
            return $this->getData('order');
        }
        if (Mage::registry('current_order')) {
            return Mage::registry('current_order');
        }
        if (Mage::registry('order')) {
            return Mage::registry('order');
        }
        Mage::throwException(Mage::helper('sales')->__('Cannot get order instance'));
    }

}