<?php

namespace AlibabaCloud\EcsInc\V20160314;

use AlibabaCloud\ApiResolverTrait;

/**
 * Find the specified Api of the EcsInc based on the method name as the Api name.
 *
 * @package   AlibabaCloud\EcsInc\V20160314
 *
 * @method AddSystemTag addSystemTag(array $options = [])
 * @method AddSystemTags addSystemTags(array $options = [])
 * @method CancelAgreement cancelAgreement(array $options = [])
 * @method CancelCopyImageAtOrigin cancelCopyImageAtOrigin(array $options = [])
 * @method CancelSystemEvent cancelSystemEvent(array $options = [])
 * @method CheckImageSupportCloudinit checkImageSupportCloudinit(array $options = [])
 * @method CheckIsDefaultVpcUser checkIsDefaultVpcUser(array $options = [])
 * @method CheckOrderNotPaid checkOrderNotPaid(array $options = [])
 * @method CopyImageAtTarget copyImageAtTarget(array $options = [])
 * @method CopySystemImage copySystemImage(array $options = [])
 * @method CopySystemImageAtTarget copySystemImageAtTarget(array $options = [])
 * @method CopyToCenterAtOrigin copyToCenterAtOrigin(array $options = [])
 * @method CreateSystemEvent createSystemEvent(array $options = [])
 * @method DescribeAccountAttributes describeAccountAttributes(array $options = [])
 * @method DescribeAdvisorCheckItems describeAdvisorCheckItems(array $options = [])
 * @method DescribeAdvisorCheckSummaries describeAdvisorCheckSummaries(array $options = [])
 * @method DescribeAdvisorChecks describeAdvisorChecks(array $options = [])
 * @method DescribeAvailableResource describeAvailableResource(array $options = [])
 * @method DescribeAvailableResourceForModify describeAvailableResourceForModify(array $options = [])
 * @method DescribeBandwidthLimitation describeBandwidthLimitation(array $options = [])
 * @method DescribeBazaarInstances describeBazaarInstances(array $options = [])
 * @method DescribeCopyProgressAtOrigin describeCopyProgressAtOrigin(array $options = [])
 * @method DescribeDangerGroupAcl describeDangerGroupAcl(array $options = [])
 * @method DescribeDangerGroupAclStat describeDangerGroupAclStat(array $options = [])
 * @method DescribeDangerGroupInfo describeDangerGroupInfo(array $options = [])
 * @method DescribeEipPrice describeEipPrice(array $options = [])
 * @method DescribeElasticUpgradeInfo describeElasticUpgradeInfo(array $options = [])
 * @method DescribeEventDetail describeEventDetail(array $options = [])
 * @method DescribeEvents describeEvents(array $options = [])
 * @method DescribeGroupHighRiskAcl describeGroupHighRiskAcl(array $options = [])
 * @method DescribeGroupHighRiskStat describeGroupHighRiskStat(array $options = [])
 * @method DescribeHighRiskGroupAcl describeHighRiskGroupAcl(array $options = [])
 * @method DescribeHighRiskGroupAclStat describeHighRiskGroupAclStat(array $options = [])
 * @method DescribeHighRiskGroupInfo describeHighRiskGroupInfo(array $options = [])
 * @method DescribeInstanceTypesInner describeInstanceTypesInner(array $options = [])
 * @method DescribePrice describePrice(array $options = [])
 * @method DescribeRenewalPrice describeRenewalPrice(array $options = [])
 * @method DescribeResourceCreationCapacity describeResourceCreationCapacity(array $options = [])
 * @method DescribeResourceFilterAttributes describeResourceFilterAttributes(array $options = [])
 * @method DescribeResourceModificationCapacity describeResourceModificationCapacity(array $options = [])
 * @method DescribeResourceRecommendFilters describeResourceRecommendFilters(array $options = [])
 * @method DescribeResources describeResources(array $options = [])
 * @method DescribeResourcesBySystemTag describeResourcesBySystemTag(array $options = [])
 * @method DescribeResourcesByTagVsw describeResourcesByTagVsw(array $options = [])
 * @method DescribeResourcesModification describeResourcesModification(array $options = [])
 * @method DetailCenterResourceAtOrigin detailCenterResourceAtOrigin(array $options = [])
 * @method FiveDaysRefund fiveDaysRefund(array $options = [])
 * @method GdprCheckResource gdprCheckResource(array $options = [])
 * @method GdprLogicalDeleteResource gdprLogicalDeleteResource(array $options = [])
 * @method GdprPhysicalDeleteResource gdprPhysicalDeleteResource(array $options = [])
 * @method GetActiveRegions getActiveRegions(array $options = [])
 * @method GetCommodity getCommodity(array $options = [])
 * @method GetCommodityProxy getCommodityProxy(array $options = [])
 * @method InnerAddEntityConstraints innerAddEntityConstraints(array $options = [])
 * @method InnerAntInstanceConvertToPrepaid innerAntInstanceConvertToPrepaid(array $options = [])
 * @method InnerBatchAttachClassicLinkVpc innerBatchAttachClassicLinkVpc(array $options = [])
 * @method InnerBatchAttchClassicLinkVpc innerBatchAttchClassicLinkVpc(array $options = [])
 * @method InnerCheckEniBindEip innerCheckEniBindEip(array $options = [])
 * @method InnerCheckEniEipOperate innerCheckEniEipOperate(array $options = [])
 * @method InnerCheckEniUnbindEip innerCheckEniUnbindEip(array $options = [])
 * @method InnerCheckIsDefaultVpcUser innerCheckIsDefaultVpcUser(array $options = [])
 * @method InnerCheckOpenSnapshotService innerCheckOpenSnapshotService(array $options = [])
 * @method InnerCheckProduce innerCheckProduce(array $options = [])
 * @method InnerConstraintDataPush innerConstraintDataPush(array $options = [])
 * @method InnerCreateEniQosGroup innerCreateEniQosGroup(array $options = [])
 * @method InnerCreateNcExpression innerCreateNcExpression(array $options = [])
 * @method InnerDeleteEniQosGroup innerDeleteEniQosGroup(array $options = [])
 * @method InnerDeleteEntityConstraints innerDeleteEntityConstraints(array $options = [])
 * @method InnerDeleteNcExpression innerDeleteNcExpression(array $options = [])
 * @method InnerDeleteTags innerDeleteTags(array $options = [])
 * @method InnerDescribeEni innerDescribeEni(array $options = [])
 * @method InnerDescribeEniBdf innerDescribeEniBdf(array $options = [])
 * @method InnerDescribeEniQosGroupByEni innerDescribeEniQosGroupByEni(array $options = [])
 * @method InnerDescribeEniQosGroupByInstance innerDescribeEniQosGroupByInstance(array $options = [])
 * @method InnerDescribeEniQosGroupInfo innerDescribeEniQosGroupInfo(array $options = [])
 * @method InnerDescribeInstanceTypes innerDescribeInstanceTypes(array $options = [])
 * @method InnerDescribeNcExpression innerDescribeNcExpression(array $options = [])
 * @method InnerDescribeSnapshotBusinessStatus innerDescribeSnapshotBusinessStatus(array $options = [])
 * @method InnerDescribeTags innerDescribeTags(array $options = [])
 * @method InnerDetailInstanceFamilyDefine innerDetailInstanceFamilyDefine(array $options = [])
 * @method InnerDiskFindDiskByDiskId innerDiskFindDiskByDiskId(array $options = [])
 * @method InnerDiskQueryByParam innerDiskQueryByParam(array $options = [])
 * @method InnerDiskQueryByParamForConsole innerDiskQueryByParamForConsole(array $options = [])
 * @method InnerDiskQueryUserDiskSummary innerDiskQueryUserDiskSummary(array $options = [])
 * @method InnerDiskReset innerDiskReset(array $options = [])
 * @method InnerDiskResizeByParam innerDiskResizeByParam(array $options = [])
 * @method InnerEcsCountInRegion innerEcsCountInRegion(array $options = [])
 * @method InnerEcsDescribeDangerAcl innerEcsDescribeDangerAcl(array $options = [])
 * @method InnerEcsDescribeDangerGroupAcl innerEcsDescribeDangerGroupAcl(array $options = [])
 * @method InnerEcsDescribeDangerGroupVmCount innerEcsDescribeDangerGroupVmCount(array $options = [])
 * @method InnerEcsDescribeIpsInGroup innerEcsDescribeIpsInGroup(array $options = [])
 * @method InnerEcsDescribeVPortInfo innerEcsDescribeVPortInfo(array $options = [])
 * @method InnerEcsExpireRegionQuery innerEcsExpireRegionQuery(array $options = [])
 * @method InnerEcsFindById innerEcsFindById(array $options = [])
 * @method InnerEcsGetBflagByBidAndUid innerEcsGetBflagByBidAndUid(array $options = [])
 * @method InnerEcsInstanceDetail innerEcsInstanceDetail(array $options = [])
 * @method InnerEcsInstanceDetailForConsole innerEcsInstanceDetailForConsole(array $options = [])
 * @method InnerEcsInstanceQueryByParam innerEcsInstanceQueryByParam(array $options = [])
 * @method InnerEcsInstanceQueryByParam4QT innerEcsInstanceQueryByParam4QT(array $options = [])
 * @method InnerEcsInstanceQueryRegionNoFilter innerEcsInstanceQueryRegionNoFilter(array $options = [])
 * @method InnerEcsInstanceQueryRegions innerEcsInstanceQueryRegions(array $options = [])
 * @method InnerEcsIsChannelMerchant innerEcsIsChannelMerchant(array $options = [])
 * @method InnerEcsIsClassicLinkVpcUser innerEcsIsClassicLinkVpcUser(array $options = [])
 * @method InnerEcsQueryByHpcClusterId innerEcsQueryByHpcClusterId(array $options = [])
 * @method InnerEcsQueryByInstanceId innerEcsQueryByInstanceId(array $options = [])
 * @method InnerEcsQueryByInternetIp innerEcsQueryByInternetIp(array $options = [])
 * @method InnerEcsQueryByIntranetIp innerEcsQueryByIntranetIp(array $options = [])
 * @method InnerEcsQueryByIp innerEcsQueryByIp(array $options = [])
 * @method InnerEcsQueryByParam innerEcsQueryByParam(array $options = [])
 * @method InnerEcsQueryBySerialNumber innerEcsQueryBySerialNumber(array $options = [])
 * @method InnerEcsQueryIpThreshold innerEcsQueryIpThreshold(array $options = [])
 * @method InnerEcsQueryNcInfoByInstanceId innerEcsQueryNcInfoByInstanceId(array $options = [])
 * @method InnerEcsQuerySecurity innerEcsQuerySecurity(array $options = [])
 * @method InnerEcsRegionQueryActive innerEcsRegionQueryActive(array $options = [])
 * @method InnerEcsRegionQueryAll innerEcsRegionQueryAll(array $options = [])
 * @method InnerEcsRegionQueryByBid innerEcsRegionQueryByBid(array $options = [])
 * @method InnerEcsReleaseByDriver innerEcsReleaseByDriver(array $options = [])
 * @method InnerEcsResourceGroupQueryByResources innerEcsResourceGroupQueryByResources(array $options = [])
 * @method InnerEcsRiskControlPunish innerEcsRiskControlPunish(array $options = [])
 * @method InnerEcsRiskControlPunishRemove innerEcsRiskControlPunishRemove(array $options = [])
 * @method InnerEcsRiskControlQuery innerEcsRiskControlQuery(array $options = [])
 * @method InnerEcsSnapshotQueryAllSnapshotsByEcsId innerEcsSnapshotQueryAllSnapshotsByEcsId(array $options = [])
 * @method InnerEcsTransitionModify innerEcsTransitionModify(array $options = [])
 * @method InnerEcsTransitionQuery innerEcsTransitionQuery(array $options = [])
 * @method InnerGetInstanceTypeModelByType innerGetInstanceTypeModelByType(array $options = [])
 * @method InnerGetZoneVendibleDataAndStatusById innerGetZoneVendibleDataAndStatusById(array $options = [])
 * @method InnerGroupAuthorize innerGroupAuthorize(array $options = [])
 * @method InnerGroupCreate innerGroupCreate(array $options = [])
 * @method InnerGroupDetail innerGroupDetail(array $options = [])
 * @method InnerGroupFindDefaultSystemGroup innerGroupFindDefaultSystemGroup(array $options = [])
 * @method InnerGroupJoin innerGroupJoin(array $options = [])
 * @method InnerGroupLeave innerGroupLeave(array $options = [])
 * @method InnerGroupQuery innerGroupQuery(array $options = [])
 * @method InnerGroupQueryVm innerGroupQueryVm(array $options = [])
 * @method InnerGroupRemove innerGroupRemove(array $options = [])
 * @method InnerGroupRevoke innerGroupRevoke(array $options = [])
 * @method InnerImageConvert2Product innerImageConvert2Product(array $options = [])
 * @method InnerImageDetail innerImageDetail(array $options = [])
 * @method InnerImageKeepUsing innerImageKeepUsing(array $options = [])
 * @method InnerImageModify innerImageModify(array $options = [])
 * @method InnerImageModifyProductCapacity innerImageModifyProductCapacity(array $options = [])
 * @method InnerImageQueryAvailableImgs innerImageQueryAvailableImgs(array $options = [])
 * @method InnerImageQueryImgsByParam innerImageQueryImgsByParam(array $options = [])
 * @method InnerImageQueryNeedKeepUsing innerImageQueryNeedKeepUsing(array $options = [])
 * @method InnerImageQueryProductQuota innerImageQueryProductQuota(array $options = [])
 * @method InnerImageQueryUserImages innerImageQueryUserImages(array $options = [])
 * @method InnerInstallCloudAssistant innerInstallCloudAssistant(array $options = [])
 * @method InnerInstanceDetail innerInstanceDetail(array $options = [])
 * @method InnerInstanceDisableSLBFlow innerInstanceDisableSLBFlow(array $options = [])
 * @method InnerInstanceEnableSLBFlow innerInstanceEnableSLBFlow(array $options = [])
 * @method InnerInstanceGetInstanceTypeModelByType innerInstanceGetInstanceTypeModelByType(array $options = [])
 * @method InnerInstanceQueryByParam innerInstanceQueryByParam(array $options = [])
 * @method InnerInstanceQueryByParamBackyard innerInstanceQueryByParamBackyard(array $options = [])
 * @method InnerInstanceQueryEcsByImgPc innerInstanceQueryEcsByImgPc(array $options = [])
 * @method InnerInstanceSetEndTime innerInstanceSetEndTime(array $options = [])
 * @method InnerIpLoad innerIpLoad(array $options = [])
 * @method InnerIzQueryForVmSale innerIzQueryForVmSale(array $options = [])
 * @method InnerJoinEniQosGroup innerJoinEniQosGroup(array $options = [])
 * @method InnerLeaveEniQosGroup innerLeaveEniQosGroup(array $options = [])
 * @method InnerListClusterFlowCtrls innerListClusterFlowCtrls(array $options = [])
 * @method InnerMaliceEcsLock innerMaliceEcsLock(array $options = [])
 * @method InnerMaliceEcsUnlock innerMaliceEcsUnlock(array $options = [])
 * @method InnerMarketplaceImageExpire innerMarketplaceImageExpire(array $options = [])
 * @method InnerModifyEniQosGroup innerModifyEniQosGroup(array $options = [])
 * @method InnerModifyEntityConstraints innerModifyEntityConstraints(array $options = [])
 * @method InnerModifyInstanceChargeType innerModifyInstanceChargeType(array $options = [])
 * @method InnerModifyPublicIpAddress innerModifyPublicIpAddress(array $options = [])
 * @method InnerModifySnapshotBusinessStatus innerModifySnapshotBusinessStatus(array $options = [])
 * @method InnerMonitor innerMonitor(array $options = [])
 * @method InnerMonitorDataDescribeDisk innerMonitorDataDescribeDisk(array $options = [])
 * @method InnerMonitorDataDescribeInstance innerMonitorDataDescribeInstance(array $options = [])
 * @method InnerNetworkModifyValidation innerNetworkModifyValidation(array $options = [])
 * @method InnerOpenSnapshotService innerOpenSnapshotService(array $options = [])
 * @method InnerProduce innerProduce(array $options = [])
 * @method InnerQueryConstraints innerQueryConstraints(array $options = [])
 * @method InnerQueryCopyImageSupportRegions innerQueryCopyImageSupportRegions(array $options = [])
 * @method InnerQueryEcsCountByCondition innerQueryEcsCountByCondition(array $options = [])
 * @method InnerQueryEcsPermit innerQueryEcsPermit(array $options = [])
 * @method InnerQueryEniQosGroupByEni innerQueryEniQosGroupByEni(array $options = [])
 * @method InnerQueryEniQosGroupByInstance innerQueryEniQosGroupByInstance(array $options = [])
 * @method InnerQueryExplanation innerQueryExplanation(array $options = [])
 * @method InnerQueryImageBindByInstance innerQueryImageBindByInstance(array $options = [])
 * @method InnerQueryInstanceCreatedByProduct innerQueryInstanceCreatedByProduct(array $options = [])
 * @method InnerQueryLazyLoadProgress innerQueryLazyLoadProgress(array $options = [])
 * @method InnerQueryRetainVcpu innerQueryRetainVcpu(array $options = [])
 * @method InnerRefundVcpuCallBack innerRefundVcpuCallBack(array $options = [])
 * @method InnerRefundVcpuQuery innerRefundVcpuQuery(array $options = [])
 * @method InnerRegionSupportInstancetypes innerRegionSupportInstancetypes(array $options = [])
 * @method InnerReleaseDedicatedHost innerReleaseDedicatedHost(array $options = [])
 * @method InnerReleasePublicIpAddress innerReleasePublicIpAddress(array $options = [])
 * @method InnerRemedyRenewInstance innerRemedyRenewInstance(array $options = [])
 * @method InnerRenewInstance innerRenewInstance(array $options = [])
 * @method InnerSendMessage innerSendMessage(array $options = [])
 * @method InnerSnapshotDescribeMountedSnapshots innerSnapshotDescribeMountedSnapshots(array $options = [])
 * @method InnerSnapshotIsUserAutoSnapshotPause innerSnapshotIsUserAutoSnapshotPause(array $options = [])
 * @method InnerSnapshotQueryUserSnapshots innerSnapshotQueryUserSnapshots(array $options = [])
 * @method InnerSnapshotSecurityMount innerSnapshotSecurityMount(array $options = [])
 * @method InnerSnapshotSecurityUnmount innerSnapshotSecurityUnmount(array $options = [])
 * @method InnerStockListUsedVms innerStockListUsedVms(array $options = [])
 * @method InnerUpdateEntityConstraints innerUpdateEntityConstraints(array $options = [])
 * @method InnerVncQueryPasswd innerVncQueryPasswd(array $options = [])
 * @method InstanceOwnershipTransfer instanceOwnershipTransfer(array $options = [])
 * @method KeepUsing keepUsing(array $options = [])
 * @method LaunchBazaarInstance launchBazaarInstance(array $options = [])
 * @method ListAllIzMap listAllIzMap(array $options = [])
 * @method ListBandwidthHistory listBandwidthHistory(array $options = [])
 * @method ListBoundMarketImage listBoundMarketImage(array $options = [])
 * @method ListEcsInstanceOrderInfo listEcsInstanceOrderInfo(array $options = [])
 * @method ListImageBinding listImageBinding(array $options = [])
 * @method ModifyInstanceAutoRenewAttributeInner modifyInstanceAutoRenewAttributeInner(array $options = [])
 * @method ModifySystemEventAttribute modifySystemEventAttribute(array $options = [])
 * @method ModifySystemEventPlanTime modifySystemEventPlanTime(array $options = [])
 * @method NotifyRefund notifyRefund(array $options = [])
 * @method OpsDescribeAccountAttributes opsDescribeAccountAttributes(array $options = [])
 * @method QueryAvailableRegion queryAvailableRegion(array $options = [])
 * @method QueryEcsElasticUpgradeInfo queryEcsElasticUpgradeInfo(array $options = [])
 * @method QueryEcsInstanceOrderInfo queryEcsInstanceOrderInfo(array $options = [])
 * @method QueryImageByImageId queryImageByImageId(array $options = [])
 * @method QueryImageCopyProgress queryImageCopyProgress(array $options = [])
 * @method QueryImageIdByRegion queryImageIdByRegion(array $options = [])
 * @method QueryInstanceInfo queryInstanceInfo(array $options = [])
 * @method QueryMarketImageCategory queryMarketImageCategory(array $options = [])
 * @method QueryMarketImages queryMarketImages(array $options = [])
 * @method QueryNeedKeepUsing queryNeedKeepUsing(array $options = [])
 * @method QueryRecommendInstanceType queryRecommendInstanceType(array $options = [])
 * @method QueryResourceModify queryResourceModify(array $options = [])
 * @method QueryResourceTransit queryResourceTransit(array $options = [])
 * @method QueryUsableSnapshots queryUsableSnapshots(array $options = [])
 * @method QueryUserInfo queryUserInfo(array $options = [])
 * @method ReInitDisks reInitDisks(array $options = [])
 * @method RemoveSystemTags removeSystemTags(array $options = [])
 * @method ReopenInstance reopenInstance(array $options = [])
 * @method ResourceOwnershipTransfer resourceOwnershipTransfer(array $options = [])
 * @method SignAgreement signAgreement(array $options = [])
 * @method TagResourceVSwitch tagResourceVSwitch(array $options = [])
 * @method TerminateBazaarInstance terminateBazaarInstance(array $options = [])
 * @method UploadSystemImageAtOrigin uploadSystemImageAtOrigin(array $options = [])
 */
class EcsIncApiResolver
{
    use ApiResolverTrait;
}
