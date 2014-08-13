<?php
/**
 * LdcUserProfile
 *
 * @link      http://github.com/adamlundrigan/LdcUserProfile for the canonical source repository
 * @copyright Copyright (c) 2014 Adam Lundrigan & Contributors
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace LdcUserProfileTest\Controller;

use LdcUserProfile\Controller\ProfileController;
use Zend\ServiceManager\ServiceManager;
use Zend\EventManager\EventManager;
use Zend\EventManager\SharedEventManager;
use Zend\EventManager\StaticEventManager;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\MvcEvent;
use Zend\Http\PhpEnvironment\Request;

class ProfileControllerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->mockUserEntity = new \ZfcUser\Entity\User();
        $this->mockUserEntity->setId(42);

        $this->mockUserService = \Mockery::mock('ZfcUser\Service\User');

        $this->mockForm = \Mockery::mock('Zend\Form\FormInterface');

        $this->mockProfileService = \Mockery::mock('LdcUserProfile\Service\ProfileService');
        $this->mockProfileService->shouldReceive('constructFormForUser')->withArgs(array($this->mockUserEntity))->andReturn($this->mockForm);

        $this->mockModuleOptions = new \LdcUserProfile\Options\ModuleOptions();

        $sl = new ServiceManager();
        $sl->setAllowOverride(true);
        $sl->setService('zfcuser_user_service', $this->mockUserService);
        $sl->setService('ldc-user-profile_service', $this->mockProfileService);
        $sl->setService('ldc-user-profile_module_options', $this->mockModuleOptions);

        $this->events       = new EventManager();
        $this->sharedEvents = new SharedEventManager();
        $this->events->setSharedManager($this->sharedEvents);

        $this->application = new TestAsset\Application();
        $this->application->setEventManager($this->events);

        $this->routeMatch = new RouteMatch(array());
        $this->routeMatch->setMatchedRouteName('ldc-user-profile');

        $this->event = new MvcEvent();
        $this->event->setApplication($this->application);
        $this->event->setRouteMatch($this->routeMatch);
        $this->event->setTarget($this->application);

        $this->module = new \LdcUserProfile\Module();

        $this->controller = new ProfileController();
        $this->controller->setServiceLocator($sl);
        $this->controller->setEvent($this->event);

        $this->mockUserPlugin = \Mockery::mock('ZfcUser\Controller\Plugin\ZfcUserAuthentication[getIdentity,hasIdentity]');
        $this->mockUserPlugin->shouldReceive('getIdentity')->andReturn($this->mockUserEntity);
        $this->mockUserPlugin->shouldReceive('hasIdentity')->andReturn(true);

        $this->mockUrlPlugin = \Mockery::mock('Zend\Mvc\Controller\Plugin\Url[fromRoute]');
        $this->mockUrlPlugin->shouldReceive('fromRoute')->andReturn('/');

        $pm = $this->controller->getPluginManager();
        $pm->setAllowOverride(true);
        $pm->setService('zfcUserAuthentication', $this->mockUserPlugin);
        $pm->setService('Url', $this->mockUrlPlugin);
    }

    public function tearDown()
    {
        // Need to do this to ensure other tests in suite do not get state
        StaticEventManager::resetInstance();
    }

    public function testControllerDispatchedWithEmptyRequestWillRenderForm()
    {
        $result = $this->controller->indexAction();

        $this->assertInstanceOf('Zend\View\Model\ModelInterface', $result);
        $this->assertSame($this->mockForm, $result->getVariable('profileForm'));
        $this->assertSame($this->mockModuleOptions, $result->getVariable('options'));
    }

    public function testControllerDispatchedWithInvalidSubmittedFormDataWillPerformRedirect()
    {
        $this->controller->getRequest()->setMethod(Request::METHOD_POST);
        $this->event->setResponse($this->controller->getResponse());

        $result = $this->controller->indexAction();

        $this->assertInstanceOf('Zend\Http\Response', $result);
        $this->assertTrue($result->isRedirect());
    }

    public function testControllerDispatchedWithValidFormDataWillCompleteAndRedirect()
    {
        $this->event->setResponse($this->controller->getResponse());

        $req = $this->controller->getRequest();
        $req->setMethod(Request::METHOD_POST);
        $req->getPost()->set('foo', array('bar' => 'baz'));
        $req->getPost()->set('zfcuser', array('id' => 42));

        $postData = $req->getPost()->toArray();
        $mockResult = new \stdClass();

        $mockPrg = \Mockery::mock('Zend\Mvc\Controller\Plugin\PostRedirectGet[__invoke]]');
        $mockPrg->shouldReceive('__invoke')->andReturn($postData);
        $pm = $this->controller->getPluginManager();
        $pm->setService('prg', $mockPrg);

        $this->mockForm->shouldReceive('setData')->withArgs(array($postData));
        $this->mockForm->shouldReceive('isValid')->andReturn(true);
        $this->mockForm->shouldReceive('getData')->andReturn($mockResult);

        $this->mockProfileService->shouldReceive('save')->withArgs(array($mockResult))->andReturn(true);

        $result = $this->controller->indexAction();

        $this->assertInstanceOf('Zend\Http\Response', $result);
        $this->assertTrue($result->isRedirect());
    }

    public function testControllerDispatchedWithInvalidFormDataWillRenderForm()
    {
        $this->event->setResponse($this->controller->getResponse());

        $req = $this->controller->getRequest();
        $req->setMethod(Request::METHOD_POST);
        $req->getPost()->set('foo', array('bar' => 'baz'));
        $req->getPost()->set('zfcuser', array('id' => 42));

        $postData = $req->getPost()->toArray();
        $mockResult = new \stdClass();

        $mockPrg = \Mockery::mock('Zend\Mvc\Controller\Plugin\PostRedirectGet[__invoke]]');
        $mockPrg->shouldReceive('__invoke')->andReturn($postData);
        $pm = $this->controller->getPluginManager();
        $pm->setService('prg', $mockPrg);

        $this->mockForm->shouldReceive('setData')->withArgs(array($postData));
        $this->mockForm->shouldReceive('isValid')->andReturn(false);

        $result = $this->controller->indexAction();

        $this->assertInstanceOf('Zend\View\Model\ModelInterface', $result);
        $this->assertSame($this->mockForm, $result->getVariable('profileForm'));
        $this->assertSame($this->mockModuleOptions, $result->getVariable('options'));
    }

    public function testControllerWillRenderFormWhenSaveCallFails()
    {
        $this->event->setResponse($this->controller->getResponse());

        $req = $this->controller->getRequest();
        $req->setMethod(Request::METHOD_POST);
        $req->getPost()->set('foo', array('bar' => 'baz'));
        $req->getPost()->set('zfcuser', array('id' => 42));

        $postData = $req->getPost()->toArray();
        $mockResult = new \stdClass();

        $mockPrg = \Mockery::mock('Zend\Mvc\Controller\Plugin\PostRedirectGet[__invoke]]');
        $mockPrg->shouldReceive('__invoke')->andReturn($postData);
        $pm = $this->controller->getPluginManager();
        $pm->setService('prg', $mockPrg);

        $this->mockForm->shouldReceive('setData')->withArgs(array($postData));
        $this->mockForm->shouldReceive('isValid')->andReturn(true);
        $this->mockForm->shouldReceive('getData')->andReturn($mockResult);

        $this->mockProfileService->shouldReceive('save')->withArgs(array($mockResult))->andReturn(false);

        $result = $this->controller->indexAction();

        $this->assertInstanceOf('Zend\View\Model\ModelInterface', $result);
        $this->assertSame($this->mockForm, $result->getVariable('profileForm'));
        $this->assertSame($this->mockModuleOptions, $result->getVariable('options'));
    }

    public function testGetSetService()
    {
        $this->mockService = \Mockery::mock('LdcUserProfile\Service\ProfileService');

        $this->controller->setService($this->mockService);
        $this->assertSame($this->mockService, $this->controller->getService());
    }

    public function testGetServicePullsFromServiceLocatorWhenNotDefined()
    {
        $this->mockService = \Mockery::mock('LdcUserProfile\Service\ProfileService');

        $serviceLocator = \Mockery::mock('Zend\ServiceManager\ServiceLocatorInterface');
        $serviceLocator->shouldReceive('get')->once()->andReturn($this->mockService);

        $this->controller->setServiceLocator($serviceLocator);
        $this->assertSame($this->mockService, $this->controller->getService());
    }

    public function testGetSetModuleOptions()
    {
        $this->mockOptions = \Mockery::mock('LdcUserProfile\Options\ModuleOptions');

        $this->controller->setModuleOptions($this->mockOptions);
        $this->assertSame($this->mockOptions, $this->controller->getModuleOptions());
    }

    public function testGetModuleOptionsPullsFromServiceLocatorWhenNotDefined()
    {
        $this->mockOptions = \Mockery::mock('LdcUserProfile\Options\ModuleOptions');

        $serviceLocator = \Mockery::mock('Zend\ServiceManager\ServiceLocatorInterface');
        $serviceLocator->shouldReceive('get')->once()->andReturn($this->mockOptions);

        $this->controller->setServiceLocator($serviceLocator);
        $this->assertSame($this->mockOptions, $this->controller->getModuleOptions());
    }

    public function testControllerIsProtectedFromUnauthorizedUsers()
    {
        $this->mockUserPlugin = \Mockery::mock('ZfcUser\Controller\Plugin\ZfcUserAuthentication[getIdentity,hasIdentity]');
        $this->mockUserPlugin->shouldReceive('getIdentity')->andReturn(null);
        $this->mockUserPlugin->shouldReceive('hasIdentity')->andReturn(false);
        $this->controller->getPluginManager()->setService('zfcUserAuthentication', $this->mockUserPlugin);

        $this->event->setResponse($this->controller->getResponse());

        $this->mockProfileService = \Mockery::mock('LdcUserProfile\Service\ProfileService');
        $this->mockProfileService->shouldReceive('constructFormForUser')->never();
        $this->controller->getServiceLocator()->setService('ldc-user-profile_service', $this->mockProfileService);

        $result = $this->controller->indexAction();

        $this->assertInstanceOf('Zend\Http\Response', $result);
        $this->assertTrue($result->isRedirect());
    }
}
