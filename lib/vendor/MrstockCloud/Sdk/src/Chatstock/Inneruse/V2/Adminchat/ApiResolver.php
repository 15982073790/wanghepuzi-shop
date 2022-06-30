<?php

namespace MrstockCloud\Chatstock\Inneruse\V2\Adminchat;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Customerlist customerlist(array $options = []) whatFor="我的群客户列表",menuName="客户列表",codeMonkey="" 
 * @method Messagelist messagelist(array $options = []) whatFor="对话框消息列表",menuName="对话框消息列表",codeMonkey="" 
 * @method Chatinfo chatinfo(array $options = []) whatFor="对话框查看消息记录",menuName="查看聊天记录",codeMonkey="" 
 * @method Top top(array $options = []) whatFor="置顶/取消置顶",menuName="置顶聊天",codeMonkey="" 
 * @method Boxread boxread(array $options = [])  
 * @method Retract retract(array $options = []) whatFor="消息撤回",menuName="撤回",codeMonkey="" 
 * @method Send send(array $options = []) whatFor="消息发送",menuName="确认发送",codeMonkey="" 
 * @method Receipt receipt(array $options = [])  
 * @method Endchat endchat(array $options = []) whatFor="结束对话",menuName="结束对话",codeMonkey="" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
