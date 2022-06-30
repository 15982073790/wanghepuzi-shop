<?php

namespace MrstockCloud\Im\Inneruse\V\Messageextra;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Set set(array $options = []) whatFor="设置某条消息额外数据",menuName="消息额外数据",codeMonkey=""
 * @method Lists lists(array $options = []) whatFor="获取消息记录",menuName="消息额外数据",codeMonkey=""
 * @method Singlelist singlelist(array $options = []) whatFor="团队与某个人的聊天记录",menuName="消息额外数据",codeMonkey=""
 * @method Search search(array $options = []) whatFor="消息搜索",menuName="消息额外数据",codeMonkey=""
 * @method Detail detail(array $options = []) whatFor="消息详情",menuName="消息额外数据",codeMonkey=""

 */
class ApiResolver
{
    use ApiResolverTrait;
}
