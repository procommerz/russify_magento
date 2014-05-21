<?php
/**
 * Russify Helper
 *
 * @package    Procommerz_Russify
 * @author     Denis Kolesnichenko <denis@mobiquest.ru>
 */
class Procommerz_Russify_Helper_Data extends Mage_Core_Helper_Abstract
{
    const DEFAULT_COUNTRY = 'Russia';


    /** Format Shopping Address for Russify template
     *
     * @param $address
     * @return string
     */
    public function getFormattedAddress($address)
    {
        return Mage::app()->getLocale()->getCountryTranslation($address->getCountryId())
        . ", <br >" . $address->getPostcode()
        . ", " . $address->getRegion()
        . ", " .  $address->getCity()
        . ", <br >" . $address->getData('street');
    }


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
}