<?php
/**
 * This file is part of the Ray.OAuthModule package
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 */
namespace Ray\OAuthModule;

use Maye\OAuthClient\OAuth2Client;
use Maye\OAuthClient\OAuth2ClientInterface;
use OAuth\Common\Storage\TokenStorageInterface;
use Ray\Di\AbstractModule;

class OAuth2Module extends AbstractModule
{
    /**
     * @var string
     */
    private $serviceName;

    /**
     * @var string
     */
    private $consumerKey;

    /**
     * @var string
     */
    private $consumerSecret;

    /**
     * @var string
     */
    private $callbackUrlPath;

    /**
     * @var array
     */
    private $scopes;

    /**
     * @var array
     */
    private $extraAuthParams;

    /**
     * @var TokenStorageInterface
     */
    private $storage;

    /**
     * @param string                $serviceName     Service name
     * @param string                $consumerKey     Consumer key
     * @param string                $consumerSecret  Consumer secret
     * @param string                $callbackUrlPath Callback url path
     * @param array                 $scopes          Scopes
     * @param array                 $extraAuthParams Extra authorization params
     * @param TokenStorageInterface $storage         Token Storage
     */
    public function __construct(
        $serviceName,
        $consumerKey,
        $consumerSecret,
        $callbackUrlPath,
        array $scopes = [],
        array $extraAuthParams = [],
        TokenStorageInterface $storage = null
    ) {
        $this->serviceName = $serviceName;
        $this->consumerKey = $consumerKey;
        $this->consumerSecret = $consumerSecret;
        $this->callbackUrlPath = $callbackUrlPath;
        $this->scopes = $scopes;
        $this->extraAuthParams = $extraAuthParams;
        $this->storage = $storage;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $qualifier = strtolower($this->serviceName);

        $this->bind(OAuth2ClientInterface::class)
            ->annotatedWith($qualifier)
            ->toConstructor(OAuth2Client::class, [
                'serviceName' => "{$qualifier}_oauth_serviceName",
                'consumerKey' => "{$qualifier}_oauth_consumerKey",
                'consumerSecret' => "{$qualifier}_oauth_consumerSecret",
                'callbackUrlPath' => "{$qualifier}_oauth_callbackUrlPath",
                'scopes' => "{$qualifier}_oauth_scopes",
                'extraParams' => "{$qualifier}_oauth_extraParams",
                'storage' => "{$qualifier}_oauth_storage"
            ]);

        $this->bind()->annotatedWith("{$qualifier}_oauth_serviceName")->toInstance($this->serviceName);
        $this->bind()->annotatedWith("{$qualifier}_oauth_consumerKey")->toInstance($this->consumerKey);
        $this->bind()->annotatedWith("{$qualifier}_oauth_consumerSecret")->toInstance($this->consumerSecret);
        $this->bind()->annotatedWith("{$qualifier}_oauth_callbackUrlPath")->toInstance($this->callbackUrlPath);
        $this->bind()->annotatedWith("{$qualifier}_oauth_scopes")->toInstance($this->scopes);
        $this->bind()->annotatedWith("{$qualifier}_oauth_extraParams")->toInstance($this->extraAuthParams);
        $this->bind(TokenStorageInterface::class)->annotatedWith("{$qualifier}_oauth_storage")->toInstance($this->storage);
    }
}
