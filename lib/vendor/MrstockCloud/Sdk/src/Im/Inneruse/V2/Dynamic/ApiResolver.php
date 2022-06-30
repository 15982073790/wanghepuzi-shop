<?php

namespace MrstockCloud\Im\Inneruse\V2\Dynamic;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Create create(array $options = []) whatFor="发送股圈消息",menuName="消息相关",codeMonkey="" 
 * @method Notice notice(array $options = []) whatFor="股圈通知消息",menuName="通知消息相关",codeMonkey="" 
 * @method Pucomment pucomment(array $options = []) whatFor="股圈公开评论",menuName="股圈公开评论",codeMonkey="" 
 * @method Userlist userlist(array $options = []) whatFor="用户股圈列表",menuName="用户股圈列表",codeMonkey="" 
 * @method Msglist msglist(array $options = []) whatFor="老师股圈列表",menuName="老师股圈列表",codeMonkey="" 
 * @method Msginfo msginfo(array $options = []) whatFor="股圈详情",menuName="股圈详情",codeMonkey="" 
 * @method Deldynamic deldynamic(array $options = []) whatFor="删除股圈",menuName="删除股圈",codeMonkey="" 
 * @method Commentmsgs commentmsgs(array $options = []) whatFor="老师股圈评论数",menuName="老师股圈评论数",codeMonkey="" 
 * @method Readcomment readcomment(array $options = []) whatFor="股圈评论已读",menuName="股圈评论已读",codeMonkey="" 
 * @method Replyretract replyretract(array $options = []) whatFor="老师回复消息撤回",menuName="消息相关",codeMonkey="" 
 * @method Newnotice newnotice(array $options = []) whatFor="私股圈新消息状态",menuName="私股圈新消息状态",codeMonkey="" 
 * @method Newnoticelist newnoticelist(array $options = []) whatFor="私股圈新消息列表",menuName="私股圈新消息列表",codeMonkey="" 
 * @method Newnoticedexunlist newnoticedexunlist(array $options = []) whatFor="德证通股圈新消息列表",menuName="德证通股圈新消息列表",codeMonkey="" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
