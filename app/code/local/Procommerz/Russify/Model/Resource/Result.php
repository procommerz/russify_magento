<?php
/**
 * Russify Result Resource Model
 *
 * @package    Procommerz_Russify
 * @author     Denis Kolesnichenko <denis@mobiquest.ru>
 */
class Procommerz_Russify_Model_Resource_Result extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('procommerz_russify/result', 'result_id');
    }
}