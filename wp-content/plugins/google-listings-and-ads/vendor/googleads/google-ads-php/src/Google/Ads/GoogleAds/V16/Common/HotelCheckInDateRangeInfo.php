<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/ads/googleads/v16/common/criteria.proto

namespace Google\Ads\GoogleAds\V16\Common;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Criterion for a check-in date range.
 *
 * Generated from protobuf message <code>google.ads.googleads.v16.common.HotelCheckInDateRangeInfo</code>
 */
class HotelCheckInDateRangeInfo extends \Google\Protobuf\Internal\Message
{
    /**
     * Start date in the YYYY-MM-DD format.
     *
     * Generated from protobuf field <code>string start_date = 1;</code>
     */
    protected $start_date = '';
    /**
     * End date in the YYYY-MM-DD format.
     *
     * Generated from protobuf field <code>string end_date = 2;</code>
     */
    protected $end_date = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $start_date
     *           Start date in the YYYY-MM-DD format.
     *     @type string $end_date
     *           End date in the YYYY-MM-DD format.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Ads\GoogleAds\V16\Common\Criteria::initOnce();
        parent::__construct($data);
    }

    /**
     * Start date in the YYYY-MM-DD format.
     *
     * Generated from protobuf field <code>string start_date = 1;</code>
     * @return string
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * Start date in the YYYY-MM-DD format.
     *
     * Generated from protobuf field <code>string start_date = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setStartDate($var)
    {
        GPBUtil::checkString($var, True);
        $this->start_date = $var;

        return $this;
    }

    /**
     * End date in the YYYY-MM-DD format.
     *
     * Generated from protobuf field <code>string end_date = 2;</code>
     * @return string
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * End date in the YYYY-MM-DD format.
     *
     * Generated from protobuf field <code>string end_date = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setEndDate($var)
    {
        GPBUtil::checkString($var, True);
        $this->end_date = $var;

        return $this;
    }

}

