<?php
/**
 * Validation Russify Model
 *
 * @package    Procommerz_Russify
 * @author     Denis Kolesnichenko <denis@mobiquest.ru>
 */

class Procommerz_Russify_Model_Russify
{
    private $_url = "http://russify.me/api/";
    private $_command;
    private $_params;

    public function getUrl()
    {
        $url = $this->_url . $this->_command ;
        $queryString = '';
        if (count($this->_params)) {
            foreach ($this->_params as $key=> $value) {
                $queryString .= "&" . $key . " = ". $value;
            }
        }
        $url .= "?format=json" .$queryString;

        return $url;
    }

    protected function call()
    {
        if ($curl = curl_init()) {
            $result = false;
            $header = array('Content-Type: text/json', 'Accept: application/json');

            curl_setopt($curl, CURLOPT_URL, $this->getUrl());
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

            try {
                $response = curl_exec($curl);
                $result   = $this->parseResult($response, $curl);
            } catch (Exception $e) {
                Mage::logException($e);
            }

            return $result;
        }

        return false;
    }

    protected function parseResult($response, &$curl)
    {
        try {
            $info = curl_getinfo($curl);
            $code = $info['http_code'];
            switch ($code) {
                case 500:
                    throw new Exception('An unhandled exception occurred on the server');
                    break;
            }
            $data = Mage::helper('core')->jsonDecode($response);
            if (isset($data['ErrorCode'])) {
                throw new Exception($data['Message'], $data['ErrorCode']);
            }
            return $data;
        } catch (Exception $e) {
            $this->_errorCode    = $e->getCode();
            $this->_errorMessage = $e->getMessage();
        }
        curl_close($curl);

        return false;
    }

    public function validate($address)
    {
        $this->_command = 'validate';
        $this->_params  = array(
            'country'   => $address->getCountryCode(),  //not required Russia by default
            'region'    => $address->getRegion(),       //not required
            'city'      => $address->getCity(),
            'address'   => $address->getData('street'),
            'zipcode'   => $address->getPostcode()
        );

        return $this->call();
    }

}