<?php
namespace MrStock\Business\ServiceSdk\MessageService\GMessage;
use MrStock\Business\ServiceSdk\MessageService\GMessage;


/**
* 站内信服务-短信服务
*/
class SMS extends GMessage
{
    CONST RESOURSE = 'sms/ali_dy';
    
    public function __construct($client)
    {
        parent::__construct($client,self::RESOURSE);
    }

    /**
    * push推送
    * @param array sms_data 短信发送数组
    * @param String sms_data.mobile 发送内容
    * @param String sms_data.content 发送内容
    * @param String sms_data.is_voice 是否是语音，0：文字模板；1：语音模板
    * @param String sms_data.template 文字模板，is_voice为0时必传
    * @param String sms_data.voice_template 语音模板，is_voice为1时必传</span>
    * @return array
    */
    public function send($sms_data)
    {
        $response = $this->post($this->uri, $sms_data);
        return $response;
    }
}