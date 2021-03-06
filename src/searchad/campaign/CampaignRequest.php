<?php
/**
 * @author Sergey Kubrey <kubrey.work@gmail.com>
 *
 */

namespace searchad\campaign;

use searchad\ApiRequest;


class CampaignRequest extends ApiRequest
{

    const STATUS_ENABLED = 'ENABLED';
    const STATUS_PAUSED = 'PAUSED';

    const SERVING_STATUS_RUNNING = 'RUNNING';
    const SERVING_STATUS_NOT_RUNNING = 'NOT_RUNNING';

    const DISPLAY_STATUS_RUNNING = 'RUNNING';
    const DISPLAY_STATUS_ON_HOLD = 'ON_HOLD';
    const DISPLAY_STATUS_PAUSED = 'PAUSED';
    /**
     * GET /v1/campaigns
     * Get a list of campaigns|one campaign if $id is set -  within a specific org
     * @param int $campaignId
     */
    public function queryCampaigns($campaignId = null)
    {
        $url = $campaignId ? "campaigns/" . $campaignId : "campaigns";
        $this->setRequestType(static::REQUEST_MODE_READ)->setGet()->setUrl($url)->run();
    }

    /**
     * POST /v1/campaigns
     * Create a new campaign within a specific org.
     * @param string $model contain json describing new campaign
     * @throws \Exception
     */
    public function createCampaign($model)
    {
        $this->setRequestType(static::REQUEST_MODE_WRITE)->setPost()->setBody($model)->setUrl("campaigns")->run();
    }

    /**
     * POST /v1/campaigns/find
     * Find a list of campaigns within a specific org using selector json
     * @param string|array $selector
     * @throws \Exception
     */
    public function queryCampaignsBySelector($selector)
    {
        $selector = is_string($selector) ? $selector : json_encode($selector);
        $this->setRequestType(static::REQUEST_MODE_READ)->setPost()->setBody($selector)->setUrl("campaigns/find")->run();
    }

    /**
     * PUT /v1/campaigns/<CAMPAIGN_ID>
     * Update an existing campaign within a specific org.
     * It should return updated campaign object
     * Or http code 400 if update is invalid
     * @param int $campaignId
     * @param string $update json with update fields
     * @throws \Exception
     */
    public function updateCampaign($campaignId, $update)
    {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }
        if(!$update){
            throw new \Exception("Update data is not set");
        }

        $this->setRequestType(static::REQUEST_MODE_WRITE)->setPut()->setBody($update)->setUrl("campaigns/" . $campaignId)->run();
    }

    /**
     * DELETE /v1/campaigns/<CAMPAIGN_ID>
     * Delete an existing campaign within a specific org.
     * It should return updated campaign object
     * Or http code 400 if update is invalid
     * @param int $campaignId
     * @throws \Exception
     */
    public function deleteCampaign($campaignId)
    {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }
        $this->setRequestType(static::REQUEST_MODE_WRITE)->setDelete()->setUrl("campaigns/" . $campaignId)->run();
    }

    /**
     * GET /v1/campaigns/<CAMPAIGN_ID>/adgroups
     * Get a list of adgroups | one adgroup if $adGroupId is set -  within a specific campaign.
     * @param $campaignId
     * @param $adGroupId
     * @throws \Exception
     */
    public function queryCampaignsAdGroups($campaignId, $adGroupId = null)
    {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }

        $url = $adGroupId ? "campaigns/" . $campaignId . "/adgroups/" . $adGroupId : "campaigns/" . $campaignId . "/adgroups";

        $this->setRequestType(static::REQUEST_MODE_READ)->setGet()->setUrl($url)->run();
    }

    /**
     * Find a list of adgroups within a specific campaign.
     * @param int $campaignId
     * @param $selector
     * @throws \Exception
     */
    public function queryCampaignAdGroupsBySelector($campaignId, $selector)
    {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }

        $this->setRequestType(static::REQUEST_MODE_READ)->setPost()->setUrl("campaigns/" . $campaignId . "/adgroups/find")->setBody($selector)->run();
    }

    /**
     * POST /v1/campaigns/<CAMPAIGN_ID>/adgroups
     * Create a new adgroup within a specific campaign.
     * @param $campaignId
     * @param $adGroupData
     * @throws \Exception
     */
    public function createAdGroupInCampaign($campaignId, $adGroupData)
    {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }
        if (!$adGroupData) {
            throw new \Exception("No adGroup data id is set");
        }

        $this->setRequestType(static::REQUEST_MODE_WRITE)->setPost()->setUrl("campaigns/" . $campaignId . "/adgroups")->setBody($adGroupData)->run();
    }

    /**
     * Update an existing adgroup within a specific campaign.
     * PUT /v1/campaigns/<CAMPAIGN_ID>/adgroups/<ADGROUP_ID>
     * @param int $campaignId
     * @param int $adGroupId
     * @param string $updateData
     * @throws \Exception
     */
    public function updateAdGroupInCampaign($campaignId, $adGroupId, $updateData)
    {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }
        if (!$adGroupId) {
            throw new \Exception("No adGroup  id is set");
        }
        if (!$updateData) {
            throw new \Exception("Update data is not set");
        }

        $this->setRequestType(static::REQUEST_MODE_WRITE)
            ->setPut()
            ->setBody($updateData)
            ->setUrl("campaigns/" . $campaignId . "/adgroups/" . $adGroupId)
            ->run();

    }

    /**
     * Delete an existing adgroup within a specific campaign.
     * DELETE /v1/campaigns/<CAMPAIGN_ID>/adgroups/<ADGROUP_ID>
     * @param int $campaignId
     * @param int $adGroupId
     * @throws \Exception
     */
    public function deleteAdGroupInCampaign($campaignId, $adGroupId)
    {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }
        if (!$adGroupId) {
            throw new \Exception("No adGroup  id is set");
        }
        $this->setRequestType(static::REQUEST_MODE_WRITE)
            ->setDelete()
            ->setUrl("campaigns/" . $campaignId . "/adgroups/" . $adGroupId)
            ->run();
    }

    /**
     * Find a list of AdGroupCreativeSets by ad group or campaign id
     *
     * POST /v1/campaigns/{campaignId}/adgroupcreativesets/find
     *
     * Request JSON Representation
     * {
     *  "selector":{
     *       "fields":null,
     *       "conditions":[
     *           {
     *               "field":"adGroupId",
     *               "operator":"EQUALS",
     *               "values":[
     *                   "106595061"
     *               ],
     *               "ignoreCase":false
     *           }
     *       ],
     *       "orderBy":null,
     *       "pagination":{
     *           "offset":0,
     *           "limit":20
     *       }
     *   }
     *}
     *
     * @param int $campaignId
     * @param $selector
     * @throws \Exception
     */
    public function queryAdGroupCreativeSetsByCampaignId($campaignId, $selector)
    {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }
        $this->setRequestType(static::REQUEST_MODE_READ)->setPost()->setUrl("campaigns/" . $campaignId . "/adgroupcreativesets/find")->setBody($selector)->run();
    }

    /**
     * Retrieve all the creative sets in your Account/Campaign Group
     *
     * POST /v1/creativesets/find
     *
     * Request JSON Representation
     * {
     *  "selector":{
     *      "fields":null,
     *      "conditions":[
     *          {
     *              "field":"id",
     *              "operator":"EQUALS",
     *              "values":[
     *                  "106595061"
     *              ]
     *          }
     *      ],
     *      "orderBy":null,
     *      "pagination":{
     *          "offset":0,
     *          "limit":20
     *      }
     *  }
     *}
     *
     * @param $selector
     * @throws \Exception
     */
    public function queryCreativeSets($selector)
    {
        $this->setRequestType(static::REQUEST_MODE_READ)->setPost()->setUrl("creativesets/find")->setBody($selector)->run();
    }

    /**
     * Update the status of a list of AdGroupCreativeSets
     *
     * PUT /v1/campaigns/{campaignId}/adgroup/{adgroupId}/adgroupcreativeset/{adgroupcreativesetId}
     *
     * Request JSON Representation
     * {"status":"PAUSED"}
     *
     * @param $campaignId
     * @param $adGroupId
     * @param $adGroupCreativeSetId
     * @param $selector
     * @throws \Exception
     */
    public function updateAdGroupCreativeSets($campaignId, $adGroupId, $adGroupCreativeSetId, $selector)
    {
        if (!$campaignId) {
            throw new \Exception("No campaign id is set");
        }
        if (!$adGroupId) {
            throw new \Exception("No ad group id is set");
        }
        if (!$adGroupCreativeSetId) {
            throw new \Exception("No ad group creative set id is set");
        }
        $this->setRequestType(static::REQUEST_MODE_WRITE)->setPut()->setUrl("campaigns/" . $campaignId . "/adgroup/" . $adGroupId . "/adgroupcreativeset/" . $adGroupCreativeSetId)->setBody($selector)->run();
    }
}