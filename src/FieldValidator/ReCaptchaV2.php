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
    
    
    private $apiUrl = 'https://www.google.com/recaptcha/api/siteverify';
    
    /**
     * 
     * @param string $secret
     * @param Error $error
     */
    public function __construct($secret, Error $error=null)
    {
        $this->secret = $secret;
        $this->error = $error;
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
        $reponse = $this->getApiResponse($this->value);
        
        return ($reponse->success == true);
    }
    
    /**
     * 
     * @param string $responseFromForm
     * @return stdObject
     */
    private function getApiResponse($responseFromForm)
    {
	$options = [
            'http' => [
                'method' => 'POST',
                'content' => \http_build_query(['secret'=>$this->secret, 'response'=>$responseFromForm])
            ]
	];
	$context  = \stream_context_create($options);
	$verify = \file_get_contents($this->apiUrl, false, $context);
        
	return \json_decode($verify);
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
