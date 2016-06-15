<?php
/**
 * This file is part of the Ray.OAuthModule package
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 */
namespace Ray\OAuthModule;

use Maye\OAuthClient\OAuth2ClientInterface;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\OAuth2\Service\Facebook;
use Ray\Di\Injector;

class OAuth2ModuleTest extends \PHPUnit_Framework_TestCase
{
    public function testOAuth2Module()
    {
        $module = new OAuth2Module(
            OAuth2Service::FACEBOOK, 'ConsumerKey', 'ConsumerSecret', 'CallbackUrlPath', [Facebook::SCOPE_READ_STREAM], [], new FakeTokenStorage);
        $injector = (new Injector($module, $_ENV['TMP_DIR']));
        /** @var FakeOAuth2Consumer $consumer */
        $consumer = $injector->getInstance(FakeOAuth2Consumer::class);

        $this->assertInstanceOf(OAuth2ClientInterface::class, $consumer->getClient());
        $this->assertEquals(OAuth2Service::FACEBOOK, $consumer->getClient()->getServiceName());
        
        $this->assertInstanceOf(FakeTokenStorage::class, $this->getStorage($consumer->getClient()));
    }

    /**
     * @param OAuth2ClientInterface $client
     *
     * @return TokenStorageInterface
     */
    private function getStorage(OAuth2ClientInterface $client)
    {
        $clazz = new \ReflectionClass($client);
        $prop = $clazz->getProperty('service');
        $prop->setAccessible(true);
        $service = $prop->getValue($client);

        return $service->getStorage();
    }
}
