<?php

namespace MrstockCloud\Love\Inneruse\V2\Attention;

use MrstockCloud\Client\Traits\ApiResolverTrait;

/**
 * Resolve Api based on the method name.
 *
 * @method Attentionlist attentionlist(array $options = []) whatFor="判断是否关注过",menuName="",codeMonkey="lvzc" 
 * @method Getlistbyobject getlistbyobject(array $options = [])  
 * @method Attentionlistforuid attentionlistforuid(array $options = []) whatFor="判断是否关注过",menuName="",codeMonkey="lvzc" 
 * @method Update_attention update_attention(array $options = [])  
 * @method Insertattention insertattention(array $options = [])  
 * @method Getlistall getlistall(array $options = [])  
 * @method Updatetype updatetype(array $options = [])  

 */
class ApiResolver
{
    use ApiResolverTrait;
}
