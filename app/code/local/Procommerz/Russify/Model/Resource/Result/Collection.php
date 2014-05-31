<?php
/**
 * Russify Result Model
 *
 * @package    Procommerz_Russify
 * @author     Denis Kolesnichenko <denis@mobiquest.ru>
 */

class Procommerz_Russify_Model_Resource_Result_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{

    protected function _construct()
    {
        $this->_init('procommerz_russify/result');
    }


    /**
     * Get stored validation Information by OrderId
     *
     * @param $orderId
     * @return array
     */
    public function getInfoByOrderId($orderId)
    {
        $this->addFieldToFilter('order_id', $orderId);
        $result = array();
        foreach ($this->getItems() as $info) {
            $result[$info->getOrderId()] = $info;
        }

        return $result;
    }

}