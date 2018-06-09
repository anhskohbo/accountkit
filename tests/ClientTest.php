<?php

use Anhskohbo\AccountKit\Client;
use Anhskohbo\AccountKit\Config;
use GuzzleHttp\Psr7\Response;

class ClientTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->appId = '1234567890';
        $this->appSecret = 'secret-test-123';
        $this->requestCode = 'AQCaNAXimMpqr2cQoPsrcbmbaDDtjwu5nV';
        $this->guzzle = $this->createMock('GuzzleHttp\Client', ['request']);

        $this->client = new Client(new Config($this->appId, $this->appSecret));
        $this->client->setHttpClient($this->guzzle);
    }

    public function testValidateTokenWithSuccess()
    {
        $tokenResponse = new Response(
            200,
            ['content-type' => 'application/json; charset=UTF-8'],
            json_encode(['access_token' => 'abc-123'])
        );

        $userResponse = new Response(
            200,
            ['content-type' => 'application/json'],
            json_encode([
                "id" => "123456789",
                "phone" => [
                    "number" => "+551190908080",
                    "country_prefix" => "55",
                    "national_number" => "1190908080",
                ]
            ])
        );

        $this->addRequestCallToken($tokenResponse);
        $this->addRequestCallUSer($userResponse);

        $response = $this->client->getUser(
            $this->client->getAccessToken($this->requestCode)
        );

        $this->assertInstanceOf('\Anhskohbo\AccountKit\User', $response);
        $this->assertEquals('55', $response->getCountryPrefix());
        $this->assertEquals('1190908080', $response->getNationalNumber());
        $this->assertEquals('+551190908080', $response->getPhoneNumber());
    }

    private function addRequestCallToken($response, $responseType = 'returnValue')
    {
        $this->guzzle
            ->expects($this->at(0))
            ->method('request')
            ->with(
                'GET',
                Config::ACCESS_TOKEN_URL,
                [
                    'query' => [
                        'grant_type' => 'authorization_code',
                        'code' => $this->requestCode,
                        'access_token' => sprintf(
                            'AA|%s|%s',
                            $this->appId,
                            $this->appSecret
                        ),
                    ]
                ]
            )
            ->will($this->$responseType($response));
    }

    private function addRequestCallUSer($response, $responseType = 'returnValue')
    {
        $this
            ->guzzle
            ->expects($this->at(1))
            ->method('request')
            ->with(
                'GET',
                Config::USER_DATA_URL,
                [
                    'query' => [
                        'appsecret_proof' => hash_hmac(
                            'sha256',
                            'abc-123',
                            'secret-test-123'
                        ),
                        'access_token' => 'abc-123',
                    ]
                ]
            )
            ->will($this->$responseType($response));
    }
}
