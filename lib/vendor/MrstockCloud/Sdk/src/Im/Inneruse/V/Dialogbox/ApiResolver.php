<?php

namespace MrstockCloud\Im\Inneruse\V\Dialogbox;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Lists lists(array $options = []) whatFor="对话框列表",menuName="对话框相关",codeMonkey="" 
 * @method Messagelist messagelist(array $options = []) whatFor="对话框消息列表",menuName="对话框相关",codeMonkey="" 
 * @method Detail detail(array $options = []) whatFor="对话框详情",menuName="对话框相关",codeMonkey="" 
 * @method Stick stick(array $options = []) whatFor="对话框置顶",menuName="对话框相关",codeMonkey="" 
 * @method Disturb disturb(array $options = []) whatFor="对话框免打扰",menuName="对话框相关",codeMonkey="" 
 * @method Read read(array $options = []) whatFor="对话框已读",menuName="对话框相关",codeMonkey="" 
 * @method Del del(array $options = []) whatFor="对话框删除",menuName="对话框相关",codeMonkey="" 
 * @method Emptymessage emptymessage(array $options = []) whatFor="清空对话框消息",menuName="对话框相关",codeMonkey="" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
