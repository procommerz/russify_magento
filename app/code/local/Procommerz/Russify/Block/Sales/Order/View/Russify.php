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
        $this->setTemplate('procommerz_russify/sales/order/view/russify.phtml');
    }

    protected function _prepareLayout()
    {
        $onclick = "submitAndReloadArea($('order_history_block').parentNode, '".$this->getSubmitUrl()."')";
        $button = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(array(
                'label'   => Mage::helper('sales')->__('Do Action'),
                'class'   => 'save',
                'onclick' => $onclick
            ));
        $this->setChild('submit_button', $button);
        return parent::_prepareLayout();
    }

    public function getSubmitUrl()
    {
        $url = Mage :: getModel('adminhtml/url')->getUrl('russify/validation/check', array(
            '_secure' => true,
        ));

        return $url;
    }
}