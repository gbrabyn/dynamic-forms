<?php
namespace GBrabyn\DynamicForms\FieldValidator;

use \GBrabyn\DynamicForms\Error;

/**
 * TODO - unit tests + test in live environment
 * Description of ReCaptchaV2
 *
 * @author GBrabyn
 */
class ReCaptchaV2 extends FieldValidatorAbstract
{
    /**
     *
     * @var Error 
     */
    private $error;
    
    /**
     *
     * @var string
     */
    private $secret;

    /**
     * @var bool
     */
    private $sendUsersIpAddress = false;
    
    
    private $apiUrl = 'https://www.google.com/recaptcha/api/siteverify';
    
    /**
     * 
     * @param string $secret
     * @param Error $error
     */
    public function __construct($secret, ?Error $error=null, array $options=[])
    {
        $this->secret = $secret;
        $this->error = $error;
        $this->setOptions($options);
    }

    private function setOptions(array $options)
    {
        if(\array_key_exists('sendUsersIpAddress', $options)){
            $this->sendUsersIpAddress = (bool)$options['sendUsersIpAddress'];
        }
    }
    
    /**
     * 
     * @return boolean
     */
    public function useWhenEmpty()
    {
        return true;
    }
    
    /**
     * 
     * @return bool
     */
    public function isValid()
    {
        $response = $this->getApiResponse($this->value);

        if(! empty($response['error-codes'])){
            throw new \Error('Error encountered in API response: '.print_r($response['error-codes'], true));
        }
        
        return ($response->success == true);
    }
    
    /**
     * 
     * @param string $responseFromForm
     * @return stdObject
     */
    private function getApiResponse($responseFromForm)
    {
        $data = ['secret'=>$this->secret, 'response'=>$responseFromForm];

        if($this->sendUsersIpAddress === true){
            $data['remoteip'] = $this->getUserIpAddr();
        }

        $query = \http_build_query($data);

        $header = array(
            "Content-Type: application/x-www-form-urlencoded",
            "Content-Length: ".\strlen($query)
        );

        $options = [
                'http' => [
                    'method' => 'POST',
                    'header' => \implode("\r\n", $header),
                    'content' => \http_build_query($query),
                ]
        ];

        $context  = \stream_context_create($options);
        $verify = \file_get_contents($this->apiUrl, false, $context);

        return \json_decode($verify);
    }

    private function getUserIpAddr()
    {
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            //ip from share internet
            return $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            //ip pass from proxy
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        return $_SERVER['REMOTE_ADDR'];
    }
    
    /**
     * 
     * @return Error
     */
    public function getError()
    {
        if($this->error){
            return $this->error;
        }
        
        return new Error('Verify you are human', 'inputReCaptchaFail', []);
    }
}
