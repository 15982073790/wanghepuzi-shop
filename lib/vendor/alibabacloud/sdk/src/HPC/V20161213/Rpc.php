<?php

namespace AlibabaCloud\HPC\V20161213;

use AlibabaCloud\Rpc;

class V20161213Rpc extends Rpc
{
    /** @var string */
    public $product = 'HPC';

    /** @var string */
    public $version = '2016-12-13';

    /** @var string */
    public $method = 'POST';

    /** @var string */
    public $serviceCode = 'hpc';
}

/**
 * @method string getInstanceId()
 * @method $this withInstanceId($value)
 * @method string getForce()
 * @method $this withForce($value)
 * @method string getTOKEN()
 * @method $this withTOKEN($value)
 */
class StopJumpserver extends V20161213Rpc
{
}

/**
 * @method string getInstanceId()
 * @method $this withInstanceId($value)
 * @method string getNewPassword()
 * @method $this withNewPassword($value)
 * @method string getTOKEN()
 * @method $this withTOKEN($value)
 */
class ModifyInstancePassword extends V20161213Rpc
{
}

/**
 * @method string getInstanceId()
 * @method $this withInstanceId($value)
 * @method string getNewPassword()
 * @method $this withNewPassword($value)
 * @method string getTOKEN()
 * @method $this withTOKEN($value)
 */
class ModifyJumpserverPassword extends V20161213Rpc
{
}

/**
 * @method string getInstanceId()
 * @method $this withInstanceId($value)
 * @method string getTOKEN()
 * @method $this withTOKEN($value)
 */
class RebootInstance extends V20161213Rpc
{
}

/**
 * @method string getInstanceId()
 * @method $this withInstanceId($value)
 * @method string getForce()
 * @method $this withForce($value)
 * @method string getTOKEN()
 * @method $this withTOKEN($value)
 */
class RebootJumpserver extends V20161213Rpc
{
}

/**
 * @method string getNicType()
 * @method $this withNicType($value)
 * @method string getSourceIp()
 * @method $this withSourceIp($value)
 * @method string getPriority()
 * @method $this withPriority($value)
 * @method string getTOKEN()
 * @method $this withTOKEN($value)
 * @method string getPolicy()
 * @method $this withPolicy($value)
 */
class RevokeSecurityGroup extends V20161213Rpc
{
}

/**
 * @method string getInstanceId()
 * @method $this withInstanceId($value)
 * @method string getForce()
 * @method $this withForce($value)
 * @method string getTOKEN()
 * @method $this withTOKEN($value)
 */
class StartJumpserver extends V20161213Rpc
{
}

/**
 * @method string getNicType()
 * @method $this withNicType($value)
 * @method string getSourceIp()
 * @method $this withSourceIp($value)
 * @method string getPriority()
 * @method $this withPriority($value)
 * @method string getTOKEN()
 * @method $this withTOKEN($value)
 * @method string getPolicy()
 * @method $this withPolicy($value)
 */
class AuthorizeSecurityGroup extends V20161213Rpc
{
}

/**
 * @method string getPackageId()
 * @method $this withPackageId($value)
 * @method string getTOKEN()
 * @method $this withTOKEN($value)
 */
class CreateInstance extends V20161213Rpc
{
}

/**
 * @method string getInstanceId()
 * @method $this withInstanceId($value)
 * @method string getTOKEN()
 * @method $this withTOKEN($value)
 */
class DeleteInstance extends V20161213Rpc
{
}

/**
 * @method string getInstanceId()
 * @method $this withInstanceId($value)
 * @method string getInstanceType()
 * @method $this withInstanceType($value)
 * @method string getTOKEN()
 * @method $this withTOKEN($value)
 */
class DescribeInstances extends V20161213Rpc
{
}

/**
 * @method string getTOKEN()
 * @method $this withTOKEN($value)
 */
class DescribeInstancesInSecurityGroup extends V20161213Rpc
{
}

/**
 * @method string getNicType()
 * @method $this withNicType($value)
 * @method string getTOKEN()
 * @method $this withTOKEN($value)
 */
class DescribeSecurityGroupAttribute extends V20161213Rpc
{
}
