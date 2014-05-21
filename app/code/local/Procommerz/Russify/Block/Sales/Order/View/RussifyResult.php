<?php
/**
 * Result Russify block
 *
 * @package    Procommerz_Russify
 * @author     Denis Kolesnichenko <denis@mobiquest.ru>
 */

class Procommerz_Russify_Block_Sales_Order_View_RussifyResult extends Mage_Adminhtml_Block_Template
{

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('procommerz_russify/sales/order/view/russify_result.phtml');

        return $this;
    }
}