<?php
/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */
namespace MrStock\Business\ServiceSdk\Aliyun\Core\Auth;
use MrStock\Business\ServiceSdk\Aliyun\Core\RpcAcsRequest;

/**
 *
 */
defined('STS_PRODUCT_NAME') or define('STS_PRODUCT_NAME', 'Sts');
/**
 *
 */
defined('STS_DOMAIN') or define('STS_DOMAIN', 'sts.aliyuncs.com');
/**
 *
 */
defined('STS_VERSION') or define('STS_VERSION', '2015-04-01');
/**
 *
 */
defined('STS_ACTION') or define('STS_ACTION', 'AssumeRole');
/**
 *
 */
defined('STS_REGION') or define('STS_REGION', 'cn-hangzhou');
/**
 *
 */
defined('ROLE_ARN_EXPIRE_TIME') or define('ROLE_ARN_EXPIRE_TIME', 3600);

class AssumeRoleRequest extends RpcAcsRequest
{
    /**
     * AssumeRoleRequest constructor.
     *
     * @param $roleArn
     * @param $roleSessionName
     */
    public function __construct($roleArn, $roleSessionName)
    {
        parent::__construct(STS_PRODUCT_NAME, STS_VERSION, STS_ACTION);

        $this->queryParameters['RoleArn']         = $roleArn;
        $this->queryParameters['RoleSessionName'] = $roleSessionName;
        $this->queryParameters['DurationSeconds'] = ROLE_ARN_EXPIRE_TIME;
        $this->setRegionId(ROLE_ARN_EXPIRE_TIME);
        $this->setProtocol('https');

        $this->setAcceptFormat('JSON');
    }
}
