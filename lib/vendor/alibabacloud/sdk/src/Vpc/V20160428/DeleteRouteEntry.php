<?php

namespace AlibabaCloud\Vpc\V20160428;

use AlibabaCloud\Client\Request\RpcRequest;

/**
 * Request of DeleteRouteEntry
 *
 * @method string getResourceOwnerId()
 * @method string getResourceOwnerAccount()
 * @method string getDestinationCidrBlock()
 * @method string getOwnerAccount()
 * @method string getNextHopId()
 * @method string getOwnerId()
 * @method array getNextHopList()
 * @method string getRouteTableId()
 */
class DeleteRouteEntry extends RpcRequest
{

    /**
     * @var string
     */
    public $product = 'Vpc';

    /**
     * @var string
     */
    public $version = '2016-04-28';

    /**
     * @var string
     */
    public $action = 'DeleteRouteEntry';

    /**
     * @var string
     */
    public $method = 'POST';

    /**
     * @var string
     */
    public $serviceCode = 'vpc';

    /**
     * @deprecated deprecated since version 2.0, Use withResourceOwnerId() instead.
     *
     * @param string $resourceOwnerId
     *
     * @return $this
     */
    public function setResourceOwnerId($resourceOwnerId)
    {
        return $this->withResourceOwnerId($resourceOwnerId);
    }

    /**
     * @param string $resourceOwnerId
     *
     * @return $this
     */
    public function withResourceOwnerId($resourceOwnerId)
    {
        $this->data['ResourceOwnerId'] = $resourceOwnerId;
        $this->options['query']['ResourceOwnerId'] = $resourceOwnerId;

        return $this;
    }

    /**
     * @deprecated deprecated since version 2.0, Use withResourceOwnerAccount() instead.
     *
     * @param string $resourceOwnerAccount
     *
     * @return $this
     */
    public function setResourceOwnerAccount($resourceOwnerAccount)
    {
        return $this->withResourceOwnerAccount($resourceOwnerAccount);
    }

    /**
     * @param string $resourceOwnerAccount
     *
     * @return $this
     */
    public function withResourceOwnerAccount($resourceOwnerAccount)
    {
        $this->data['ResourceOwnerAccount'] = $resourceOwnerAccount;
        $this->options['query']['ResourceOwnerAccount'] = $resourceOwnerAccount;

        return $this;
    }

    /**
     * @deprecated deprecated since version 2.0, Use withDestinationCidrBlock() instead.
     *
     * @param string $destinationCidrBlock
     *
     * @return $this
     */
    public function setDestinationCidrBlock($destinationCidrBlock)
    {
        return $this->withDestinationCidrBlock($destinationCidrBlock);
    }

    /**
     * @param string $destinationCidrBlock
     *
     * @return $this
     */
    public function withDestinationCidrBlock($destinationCidrBlock)
    {
        $this->data['DestinationCidrBlock'] = $destinationCidrBlock;
        $this->options['query']['DestinationCidrBlock'] = $destinationCidrBlock;

        return $this;
    }

    /**
     * @deprecated deprecated since version 2.0, Use withOwnerAccount() instead.
     *
     * @param string $ownerAccount
     *
     * @return $this
     */
    public function setOwnerAccount($ownerAccount)
    {
        return $this->withOwnerAccount($ownerAccount);
    }

    /**
     * @param string $ownerAccount
     *
     * @return $this
     */
    public function withOwnerAccount($ownerAccount)
    {
        $this->data['OwnerAccount'] = $ownerAccount;
        $this->options['query']['OwnerAccount'] = $ownerAccount;

        return $this;
    }

    /**
     * @deprecated deprecated since version 2.0, Use withNextHopId() instead.
     *
     * @param string $nextHopId
     *
     * @return $this
     */
    public function setNextHopId($nextHopId)
    {
        return $this->withNextHopId($nextHopId);
    }

    /**
     * @param string $nextHopId
     *
     * @return $this
     */
    public function withNextHopId($nextHopId)
    {
        $this->data['NextHopId'] = $nextHopId;
        $this->options['query']['NextHopId'] = $nextHopId;

        return $this;
    }

    /**
     * @deprecated deprecated since version 2.0, Use withOwnerId() instead.
     *
     * @param string $ownerId
     *
     * @return $this
     */
    public function setOwnerId($ownerId)
    {
        return $this->withOwnerId($ownerId);
    }

    /**
     * @param string $ownerId
     *
     * @return $this
     */
    public function withOwnerId($ownerId)
    {
        $this->data['OwnerId'] = $ownerId;
        $this->options['query']['OwnerId'] = $ownerId;

        return $this;
    }

    /**
     * @deprecated deprecated since version 2.0, Use getNextHopList() instead.
     *
     * @return array
     */
    public function getNextHopLists()
    {
        return $this->getNextHopList();
    }

    /**
     * @deprecated deprecated since version 2.0, Use withNextHopList() instead.
     *
     * @param array $nextHopLists
     *
     * @return $this
     */
    public function setNextHopLists(array $nextHopLists)
    {
        return $this->withNextHopList($nextHopLists);
    }

    /**
     * @param array $nextHopList
     *
     * @return $this
     */
    public function withNextHopList(array $nextHopList)
    {
        $this->data['NextHopList'] = $nextHopList;
        foreach ($nextHopList as $i => $iValue) {
            $this->options['query']['NextHopList.' . ($i + 1) . '.NextHopId'] = $nextHopList[$i]['NextHopId'];
            $this->options['query']['NextHopList.' . ($i + 1) . '.NextHopType'] = $nextHopList[$i]['NextHopType'];
        }

        return $this;
    }

    /**
     * @deprecated deprecated since version 2.0, Use withRouteTableId() instead.
     *
     * @param string $routeTableId
     *
     * @return $this
     */
    public function setRouteTableId($routeTableId)
    {
        return $this->withRouteTableId($routeTableId);
    }

    /**
     * @param string $routeTableId
     *
     * @return $this
     */
    public function withRouteTableId($routeTableId)
    {
        $this->data['RouteTableId'] = $routeTableId;
        $this->options['query']['RouteTableId'] = $routeTableId;

        return $this;
    }
}
