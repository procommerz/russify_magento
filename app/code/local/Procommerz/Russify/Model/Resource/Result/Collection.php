<?php
/**
 *
 * @package    Procommerz_Russify
 * @author     Denis Kolesnichenko <denis@mobiquest.ru>
 */
class Procommerz_Russify_Model_Resource_Result_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('procommerz_russify/result');
    }
}