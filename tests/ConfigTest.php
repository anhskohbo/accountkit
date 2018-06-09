<?php

use Anhskohbo\AccountKit\Config;

class ConfigTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \Anhskohbo\AccountKit\Config
     */
    public function testConfig()
    {
        $config = new Config(123, 'abc123');
        $this->assertEquals(123, $config->getAppId());
        $this->assertEquals('abc123', $config->getAppSecret());
    }

    public function testConfigGetUrlToken()
    {
        $config = new Config(123, 'abc123');
        $this->assertEquals('https://graph.accountkit.com/v1.1/access_token', $config->getAccessTokenUrl());
    }

    /**
     * @covers \Anhskohbo\AccountKit\Config
     */
    public function testConfigGetUrlUser()
    {
        $config = new Config(123, 'abc123');
        $this->assertEquals('https://graph.accountkit.com/v1.1/me', $config->getUserDataUrl());
    }
}
