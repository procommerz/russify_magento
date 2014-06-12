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

    /** Transliterate given string
     *
     * @param $string
     * @return string
     */
    public  function transliterate($string)
    {
        $replace = array("А"=>"A","а"=>"a","Б"=>"B","б"=>"b","В"=>"V","в"=>"v","Г"=>"G","г"=>"g","Д"=>"D","д"=>"d",
            "Е"=>"E","е"=>"e","Ё"=>"E","ё"=>"e","Ж"=>"Zh","ж"=>"zh","З"=>"Z","з"=>"z","И"=>"I","и"=>"i",
            "Й"=>"I","й"=>"i","К"=>"K","к"=>"k","Л"=>"L","л"=>"l","М"=>"M","м"=>"m","Н"=>"N","н"=>"n","О"=>"O","о"=>"o",
            "П"=>"P","п"=>"p","Р"=>"R","р"=>"r","С"=>"S","с"=>"s","Т"=>"T","т"=>"t","У"=>"U","у"=>"u","Ф"=>"F","ф"=>"f",
            "Х"=>"Kh","х"=>"kh","Ц"=>"Tc","ц"=>"tc","Ч"=>"Ch","ч"=>"ch","Ш"=>"Sh","ш"=>"sh","Щ"=>"Shch","щ"=>"shch",
            "Ы"=>"Y","ы"=>"y","Э"=>"E","э"=>"e","Ю"=>"Iu","ю"=>"iu","Я"=>"Ia","я"=>"ia","ъ"=>"","ь"=>"");


        return iconv("UTF-8","UTF-8//IGNORE",strtr($string,$replace));
    }

}