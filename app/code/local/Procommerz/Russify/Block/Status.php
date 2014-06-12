<?php
/**
 * Order Russify Status block
 *
 * @package    Procommerz_Russify
 * @author     Denis Kolesnichenko <denis@mobiquest.ru>
 */

class  Procommerz_Russify_Block_Status extends Procommerz_Russify_Block_Russify
{
    /**
     * Set template
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('procommerz_russify/sales/order/view/status.phtml');

        return $this;
    }
}