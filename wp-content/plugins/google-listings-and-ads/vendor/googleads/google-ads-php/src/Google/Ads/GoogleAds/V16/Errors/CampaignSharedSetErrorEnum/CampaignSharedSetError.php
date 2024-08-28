<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/ads/googleads/v16/errors/campaign_shared_set_error.proto

namespace Google\Ads\GoogleAds\V16\Errors\CampaignSharedSetErrorEnum;

use UnexpectedValueException;

/**
 * Enum describing possible campaign shared set errors.
 *
 * Protobuf type <code>google.ads.googleads.v16.errors.CampaignSharedSetErrorEnum.CampaignSharedSetError</code>
 */
class CampaignSharedSetError
{
    /**
     * Enum unspecified.
     *
     * Generated from protobuf enum <code>UNSPECIFIED = 0;</code>
     */
    const UNSPECIFIED = 0;
    /**
     * The received error code is not known in this version.
     *
     * Generated from protobuf enum <code>UNKNOWN = 1;</code>
     */
    const UNKNOWN = 1;
    /**
     * The shared set belongs to another customer and permission isn't granted.
     *
     * Generated from protobuf enum <code>SHARED_SET_ACCESS_DENIED = 2;</code>
     */
    const SHARED_SET_ACCESS_DENIED = 2;

    private static $valueToName = [
        self::UNSPECIFIED => 'UNSPECIFIED',
        self::UNKNOWN => 'UNKNOWN',
        self::SHARED_SET_ACCESS_DENIED => 'SHARED_SET_ACCESS_DENIED',
    ];

    public static function name($value)
    {
        if (!isset(self::$valueToName[$value])) {
            throw new UnexpectedValueException(sprintf(
                    'Enum %s has no name defined for value %s', __CLASS__, $value));
        }
        return self::$valueToName[$value];
    }


    public static function value($name)
    {
        $const = __CLASS__ . '::' . strtoupper($name);
        if (!defined($const)) {
            throw new UnexpectedValueException(sprintf(
                    'Enum %s has no value defined for name %s', __CLASS__, $name));
        }
        return constant($const);
    }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(CampaignSharedSetError::class, \Google\Ads\GoogleAds\V16\Errors\CampaignSharedSetErrorEnum_CampaignSharedSetError::class);

