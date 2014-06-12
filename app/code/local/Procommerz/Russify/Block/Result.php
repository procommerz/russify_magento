<?php
/**
 * Order Russify Result block
 *
 * @package    Procommerz_Russify
 * @author     Denis Kolesnichenko <denis@mobiquest.ru>
 */

class Procommerz_Russify_Block_Result extends Procommerz_Russify_Block_Russify
{

    /**
     * Set template
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('procommerz_russify/sales/order/view/result.phtml');

        return $this;
    }

}