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
use MrStock\Business\ServiceSdk\Aliyun\Core\Http\HttpHelper;

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

class RamRoleArnService
{
    /**
     * @var IClientProfile
     */
    private $clientProfile;
    /**
     * @var null|string
     */
    private $lastClearTime = null;
    /**
     * @var null|string
     */
    private $sessionCredential = null;
    /**
     * @var string
     */
    public static $serviceDomain = STS_DOMAIN;

    /**
     * RamRoleArnService constructor.
     *
     * @param $clientProfile
     */
    public function __construct($clientProfile)
    {
        $this->clientProfile = $clientProfile;
    }

    /**
     * @return Credential|string|null
     * @throws ClientException
     */
    public function getSessionCredential()
    {
        if ($this->lastClearTime != null && $this->sessionCredential != null) {
            $now         = time();
            $elapsedTime = $now - $this->lastClearTime;
            if ($elapsedTime <= ROLE_ARN_EXPIRE_TIME * 0.8) {
                return $this->sessionCredential;
            }
        }

        $credential = $this->assumeRole();

        if ($credential == null) {
            return null;
        }

        $this->sessionCredential = $credential;
        $this->lastClearTime     = time();

        return $credential;
    }

    /**
     * @return Credential|null
     * @throws ClientException
     */
    private function assumeRole()
    {
        $signer               = $this->clientProfile->getSigner();
        $ramRoleArnCredential = $this->clientProfile->getCredential();

        $request =
            new AssumeRoleRequest($ramRoleArnCredential->getRoleArn(), $ramRoleArnCredential->getRoleSessionName());

        $requestUrl = $request->composeUrl($signer, $ramRoleArnCredential, self::$serviceDomain);

        $httpResponse = HttpHelper::curl($requestUrl, $request->getMethod(), null, $request->getHeaders());

        if (!$httpResponse->isSuccess()) {
            return null;
        }

        $respObj = json_decode($httpResponse->getBody());

        $sessionAccessKeyId     = $respObj->Credentials->AccessKeyId;
        $sessionAccessKeySecret = $respObj->Credentials->AccessKeySecret;
        $securityToken          = $respObj->Credentials->SecurityToken;
        return new Credential($sessionAccessKeyId, $sessionAccessKeySecret, $securityToken);
    }
}