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
    private $_orderId;

    public function getPostString()
    {
        $queryString = '';
        if (count($this->_params)) {
            foreach ($this->_params as $key=> $value) {
                $queryString .= "&" . $key . "=". $value ."";
            }
        }

        return substr($queryString, 1);
    }

    protected function call()
    {
        $result = false;
        $accessToken = Mage::getStoreConfig('procommerz/general/russify_api');
        $accessID = Mage::getStoreConfig('procommerz/general/russify_id');
        $apiUrl = 'http://russify.me/api/validate';
        $timestamp = gmdate('c');
        $contentType = 'application/x-www-form-urlencoded';
        $fields = (array)$this->_params;
        foreach ($fields as $key => $value) {
            $fields[$key] = Mage::helper('procommerz_russify')->transliterate($value);
        }
        $postFields = str_replace('+', '%20', http_build_query($fields));

//          country=Russia&region=Moskovskaia%20oblast&city=Moskva&address=ul.%20Aleksandra%20Solzhenitcyna%20d.%2027&zipcode=123123
//            $postFields =str_replace(' ', '%20', $this->getPostString());
//           country=Russia&region=Московская%20область&city=Москва&address=ул.%20Александра%20Солженицына%20д.%2027&zipcode=123123

        $content_MD5 = base64_encode(pack("H*", md5($postFields)));
        $canonicalStr = $contentType .','. $content_MD5 .','. parse_url($apiUrl, PHP_URL_PATH) .','. $timestamp;
        $signature = base64_encode(hash_hmac('sha1', $canonicalStr, $accessToken, true));
        $header = array(
            'Content-Type: ' . $contentType,
            'Content-Length: ' . strlen($postFields),
            'date: ' . $timestamp,
            'content-md5: ' . $content_MD5,
            'authorization: APIAuth ' . $accessID .':'. $signature,
        );

        if ($curl = curl_init($apiUrl)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postFields);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_TIMEOUT, 60);
            try {
                $response = curl_exec($curl);
                $result   = json_decode($response);
                if ($result->status_code === 200) {
                    $russifyInfo = Mage::getModel('procommerz_russify/result')->getCollection()->getInfoByOrderId($this->_orderId);
                    if (empty($russifyInfo)) {
                        Mage::getModel('procommerz_russify/result')
                            ->setOrderId($this->_orderId)
                            ->setResult($response)
                            ->setCreatedAt($timestamp)
                            ->setCount(1)
                            ->save();
                    } else {
                        $validationCount = $russifyInfo[$this->_orderId]->getCount();
                        $russifyInfo[$this->_orderId]->setResult($result)
                            ->setUpdatedAt($timestamp)
                            ->setCount(++$validationCount)
                            ->save();
                    }
                }
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

    public function validate($address, $orderId)
    {
        $this->_command = 'validate';
        $this->_params  = $address;
        $this->_orderId  = $orderId;

        return $this->call();
    }

}