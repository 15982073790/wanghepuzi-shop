<?php

namespace MrstockCloud\Im\Inneruse\V2\Liveroom;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Enterroom enterroom(array $options = []) whatFor="进入直播间",menuName="直播间相关",codeMonkey="" 
 * @method Leaveroom leaveroom(array $options = []) whatFor="离开直播间",menuName="直播间相关",codeMonkey="" 
 * @method Onlinenumber onlinenumber(array $options = []) whatFor="直播间在线人数",menuName="直播间相关",codeMonkey="" 
 * @method Messagesend messagesend(array $options = []) whatFor="直播间消息发送",menuName="直播间相关",codeMonkey="" 
 * @method Messagelist messagelist(array $options = []) whatFor="聊天室消息列表",menuName="直播间相关",codeMonkey="" 
 * @method Stickmessage stickmessage(array $options = []) whatFor="聊天室消息置顶",menuName="直播间相关",codeMonkey="" 
 * @method Getstickmessage getstickmessage(array $options = []) whatFor="置顶消息详情",menuName="直播间相关",codeMonkey="" 
 * @method Retractmsg retractmsg(array $options = []) whatFor="直播消息撤回",menuName="直播间相关",codeMonkey="" 

 */
class ApiResolver
{
    use ApiResolverTrait;
}
