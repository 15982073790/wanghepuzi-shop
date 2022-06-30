<?php
namespace MrStock\Business\ServiceSdk\Aliyun\Core\Regions;
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

use MrStock\Business\ServiceSdk\Aliyun\Core\RpcAcsRequest;


class DescribeEndpointRequest extends RpcAcsRequest
{
    /**
     * DescribeEndpointRequest constructor.
     *
     * @param $id
     * @param $serviceCode
     * @param $endPointType
     */
    public function __construct($id, $serviceCode, $endPointType)
    {
        parent::__construct(LOCATION_SERVICE_PRODUCT_NAME,
                            LOCATION_SERVICE_VERSION,
                            LOCATION_SERVICE_DESCRIBE_ENDPOINT_ACTION);

        $this->queryParameters['Id']          = $id;
        $this->queryParameters['ServiceCode'] = $serviceCode;
        $this->queryParameters['Type']        = $endPointType;
        $this->setRegionId(LOCATION_SERVICE_REGION);

        $this->setAcceptFormat('JSON');
    }
}