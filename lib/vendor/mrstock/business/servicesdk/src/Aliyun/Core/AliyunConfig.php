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
namespace MrStock\Business\ServiceSdk\Aliyun\Core;

//config http proxy
use MrStock\Business\ServiceSdk\Aliyun\Core\Regions\EndpointConfig;

/**
 *
 */
defined('ENABLE_HTTP_PROXY') or define('ENABLE_HTTP_PROXY', false);
/**
 *
 */
defined('HTTP_PROXY_IP') or define('HTTP_PROXY_IP', '127.0.0.1');
/**
 *
 */
defined('HTTP_PROXY_PORT') or define('HTTP_PROXY_PORT', '8888');

/**
 *
 */
defined('LOCATION_SERVICE_PRODUCT_NAME') or define('LOCATION_SERVICE_PRODUCT_NAME', 'Location');
/**
 *
 */
defined('LOCATION_SERVICE_DOMAIN') or define('LOCATION_SERVICE_DOMAIN', 'location.aliyuncs.com');
/**
 *
 */
defined('LOCATION_SERVICE_VERSION') or define('LOCATION_SERVICE_VERSION', '2015-06-12');
/**
 *
 */
defined('LOCATION_SERVICE_DESCRIBE_ENDPOINT_ACTION') or define('LOCATION_SERVICE_DESCRIBE_ENDPOINT_ACTION', 'DescribeEndpoints');
/**
 *
 */
defined('LOCATION_SERVICE_REGION') or define('LOCATION_SERVICE_REGION', 'cn-hangzhou');
/**
 *
 */
defined('CACHE_EXPIRE_TIME') or define('CACHE_EXPIRE_TIME', 3600);

class AliyunConfig
{
    public static function init()
    {
        EndpointConfig::init();
    }
}

