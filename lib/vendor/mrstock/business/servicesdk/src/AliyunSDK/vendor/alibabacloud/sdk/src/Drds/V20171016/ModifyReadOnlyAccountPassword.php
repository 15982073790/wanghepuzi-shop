<?php

namespace AlibabaCloud\Drds\V20171016;

use AlibabaCloud\Client\Request\RpcRequest;

/**
 * Request of ModifyReadOnlyAccountPassword
 *
 * @method string getNewPasswd()
 * @method string getDbName()
 * @method string getAccountName()
 * @method string getOriginPassword()
 * @method string getDrdsInstanceId()
 */
class ModifyReadOnlyAccountPassword extends RpcRequest
{

    /**
     * @var string
     */
    public $product = 'Drds';

    /**
     * @var string
     */
    public $version = '2017-10-16';

    /**
     * @var string
     */
    public $action = 'ModifyReadOnlyAccountPassword';

    /**
     * @var string
     */
    public $method = 'POST';

    /**
     * @deprecated deprecated since version 2.0, Use withNewPasswd() instead.
     *
     * @param string $newPasswd
     *
     * @return $this
     */
    public function setNewPasswd($newPasswd)
    {
        return $this->withNewPasswd($newPasswd);
    }

    /**
     * @param string $newPasswd
     *
     * @return $this
     */
    public function withNewPasswd($newPasswd)
    {
        $this->data['NewPasswd'] = $newPasswd;
        $this->options['query']['NewPasswd'] = $newPasswd;

        return $this;
    }

    /**
     * @deprecated deprecated since version 2.0, Use withDbName() instead.
     *
     * @param string $dbName
     *
     * @return $this
     */
    public function setDbName($dbName)
    {
        return $this->withDbName($dbName);
    }

    /**
     * @param string $dbName
     *
     * @return $this
     */
    public function withDbName($dbName)
    {
        $this->data['DbName'] = $dbName;
        $this->options['query']['DbName'] = $dbName;

        return $this;
    }

    /**
     * @deprecated deprecated since version 2.0, Use withAccountName() instead.
     *
     * @param string $accountName
     *
     * @return $this
     */
    public function setAccountName($accountName)
    {
        return $this->withAccountName($accountName);
    }

    /**
     * @param string $accountName
     *
     * @return $this
     */
    public function withAccountName($accountName)
    {
        $this->data['AccountName'] = $accountName;
        $this->options['query']['AccountName'] = $accountName;

        return $this;
    }

    /**
     * @deprecated deprecated since version 2.0, Use withOriginPassword() instead.
     *
     * @param string $originPassword
     *
     * @return $this
     */
    public function setOriginPassword($originPassword)
    {
        return $this->withOriginPassword($originPassword);
    }

    /**
     * @param string $originPassword
     *
     * @return $this
     */
    public function withOriginPassword($originPassword)
    {
        $this->data['OriginPassword'] = $originPassword;
        $this->options['query']['OriginPassword'] = $originPassword;

        return $this;
    }

    /**
     * @deprecated deprecated since version 2.0, Use withDrdsInstanceId() instead.
     *
     * @param string $drdsInstanceId
     *
     * @return $this
     */
    public function setDrdsInstanceId($drdsInstanceId)
    {
        return $this->withDrdsInstanceId($drdsInstanceId);
    }

    /**
     * @param string $drdsInstanceId
     *
     * @return $this
     */
    public function withDrdsInstanceId($drdsInstanceId)
    {
        $this->data['DrdsInstanceId'] = $drdsInstanceId;
        $this->options['query']['DrdsInstanceId'] = $drdsInstanceId;

        return $this;
    }
}
