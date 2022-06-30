<?php

namespace MrstockCloud\Leavingmessage\Inneruse\V\Traders;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Index index(array $options = []) whatFor="获取消息列表-时间倒序",menuName="",codeMonkey="杜阳" 
 * @method Asc asc(array $options = []) whatFor="获取消息列表-时间正序",menuName="",codeMonkey="杜阳" 
 * @method Submit submit(array $options = []) whatFor="后台人员发布交易师评论",menuName="",codeMonkey="杜阳" 
 * @method Revoke revoke(array $options = []) whatFor="老师或助理撤回交易师评论",menuName="",codeMonkey="杜阳" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
