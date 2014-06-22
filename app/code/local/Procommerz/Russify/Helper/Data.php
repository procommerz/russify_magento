<?php
/**
 * Russify Helper
 *
 * @package    Procommerz_Russify
 * @author     Denis Kolesnichenko <denis@mobiquest.ru>
 */
class Procommerz_Russify_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     *
     */
    const DEFAULT_COUNTRY = 'Russia';

    const STATUS_NOT_VALIDATED  = 'NOT_VALIDATED';
    const STATUS_VALID          = 'VALID';
    const STATUS_INVALID        = 'INVALID';

    /** Prepare Shipping Address for Russify.me
     *
     * @param $shippingAddress
     * @return array
     */
    public function prepareRequestArray($shippingAddress)
    {
        if ($shippingAddress->getCountryCode()) {
            $array['country'] = Mage::app()->getLocale()->getCountryTranslation($shippingAddress->getCountryId());
        } else {
            $array['country'] = self::DEFAULT_COUNTRY;
        }
        if ($shippingAddress->getRegion()) {
            $array['region'] = $shippingAddress->getRegion();
        }
        if ($shippingAddress->getCity()) {
            $array['city'] = $shippingAddress->getCity();
        }
        if ($shippingAddress->getData('street')) {
            $array['address'] = $shippingAddress->getData('street');
        }
        if ($shippingAddress->getPostcode()) {
            $array['zipcode'] = $shippingAddress->getPostcode();
        }

        return $array;
    }

    /** Check Russify validation status
     *
     * @param $orderId
     * @return bool
     */
    public function getRussifyStatus($orderId)
    {
        $russifyInfo = Mage::getModel('procommerz_russify/result')->getCollection()->getInfoByOrderId($orderId);

        if (!empty($russifyInfo)) {

            if($russifyInfo[$orderId]->getIsValidated()) {
                return self::STATUS_VALID;
            } else {
                return self::STATUS_INVALID;
            }
        }

        return self::STATUS_NOT_VALIDATED;
    }
}