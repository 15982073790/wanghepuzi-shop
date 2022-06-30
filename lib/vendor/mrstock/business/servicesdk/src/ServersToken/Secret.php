<?php
namespace MrStock\Business\ServiceSdk\ServersToken;

class Secret
{
    
    private $cipherKey = '';
    
    private $cipherMethod = 'AES-256-CBC';
    
    private $cipherVi = '';
    
    public function __construct($cipher_key = '',$cipher_vi='')
    {

        if ($cipher_key) {
            $this->cipherKey = $cipher_key;
        }
        if($cipher_vi)
        {
            $this->cipherVi = $cipher_vi;
        }
    }
    
    public function encrypt($data)
    {
        $data = openssl_encrypt($data, $this->cipherMethod, $this->cipherKey, 0, $this->cipherVi);
        
        $data = base64_encode($data);
        return $data;
    }
    
    
    public function decrypt($data)
    {
        $data = base64_decode($data);
        
        $data = openssl_decrypt($data, $this->cipherMethod, $this->cipherKey, 0, $this->cipherVi);
        
        return $data;
    }
}