<?php

namespace Anhskohbo\AccountKit;

class Config
{
    const ACCESS_TOKEN_URL = 'https://graph.accountkit.com/v1.1/access_token';
    const USER_DATA_URL = 'https://graph.accountkit.com/v1.1/me';

    /**
     * The Facebook app ID.
     *
     * @var string
     */
    private $appId;

    /**
     * The "Account Kit" app secret.
     *
     * @var string
     */
    private $appSecret;

    /**
     * Constructor.
     *
     * @see https://developers.facebook.com/apps/
     *
     * @param string $appId
     * @param string $appSecret
     */
    public function __construct($appId, $appSecret)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
    }

    /**
     * Gets the access token url.
     *
     * @return string
     */
    public function getAccessTokenUrl()
    {
        return self::ACCESS_TOKEN_URL;
    }

    /**
     * Gets the user data url.
     *
     * @return string
     */
    public function getUserDataUrl()
    {
        return self::USER_DATA_URL;
    }

    /**
     * Gets the app ID.
     *
     * @return string
     */
    public function getAppId()
    {
        return $this->appId;
    }

    /**
     * Gets the app secret.
     *
     * @return string
     */
    public function getAppSecret()
    {
        return $this->appSecret;
    }
}
