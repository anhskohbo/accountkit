<?php

namespace Anhskohbo\AccountKit;

use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\ResponseInterface;

class Client
{
    /**
     * The config instance.
     *
     * @var \Anhskohbo\Accountkit\Config
     */
    protected $config;

    /**
     *
     * @var \GuzzleHttp\Client
     */
    protected $guzzle;

    /**
     * Constructor.
     *
     * @param \Anhskohbo\Accountkit\Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Retrive the access token.
     *
     * @param  string $code
     * @return string
     *
     * @throws \Anhskohbo\AccountKit\Exceptions\ResponseException
     */
    public function getAccessToken($code)
    {
        $accessToken = sprintf('AA|%s|%s', $this->config->getAppId(), $this->config->getAppSecret());

        $response = $this->request($this->config->getAccessTokenUrl(), [
            'query' => [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'access_token' => $accessToken,
            ]
        ]);

        $authResponse = $this->parseResponse($response);

        if (!isset($authResponse['access_token'])) {
            throw new Exceptions\ResponseException('Missing the "access_token" in auth response.');
        }

        return $authResponse['access_token'];
    }

    /**
     * Retrive the user data.
     *
     * @param  string $accessToken
     * @return \Anhskohbo\AccountKit\User
     */
    public function getUser($accessToken)
    {
        $hash = hash_hmac('sha256', $accessToken, $this->config->getAppSecret());

        $response = $this->request($this->config->getUserDataUrl(), [
            'query' => [
                'appsecret_proof' => $hash,
                'access_token' => $accessToken,
            ]
        ]);

        $userResponse = $this->parseResponse($response);

        if (!isset($userResponse['id'])) {
            throw new Exceptions\ResponseException('Missing the "id" in user response.');
        }

        if (!isset($userResponse['phone']['number'])) {
            throw new Exceptions\ResponseException('Missing the "phone" in user response.');
        }

        return new User($userResponse['id'], $userResponse);
    }

    /**
     * Parse the http response content.
     *
     * @param  \Psr\Http\Message\ResponseInterface $response
     * @return array
     *
     * @throws \Anhskohbo\AccountKit\Exceptions\ResponseException
     */
    protected function parseResponse(ResponseInterface $response)
    {
        $contentType = $response->getHeaderLine('content-type');

        if (false === preg_match('/application\/json/', $contentType)) {
            throw new Exceptions\ResponseException('Unexpected response format.');
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Send an HTTP request.
     *
     * @param  string $url
     * @param  array $params
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @throws \Anhskohbo\AccountKit\Exceptions\ClientException
     */
    protected function request($url, array $params)
    {
        try {
            return $this->getHttpClient()->request('GET', $url, $params);
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            throw new Exceptions\ClientException('AccountKit transport error.', 400, $e);
        } catch (\Exception $e) {
            throw new Exceptions\UnexpectedException('Unexpected error into AccountKit request.', 500, $e);
        }
    }

    /**
     * Gets the guzzle http client.
     *
     * @return \GuzzleHttp\Client
     */
    public function getHttpClient()
    {
        if (null === $this->guzzle) {
            $this->guzzle = new GuzzleClient(['timeout' => 30]);
        }

        return $this->guzzle;
    }

    /**
     * Sets the guzzle http client.
     *
     * @param \GuzzleHttp\Client $guzzle
     */
    public function setHttpClient(GuzzleClient $guzzle)
    {
        $this->guzzle = $guzzle;
    }
}
