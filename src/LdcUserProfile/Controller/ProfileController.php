<?php
/**
 * LdcUserProfile
 *
 * @link      http://github.com/adamlundrigan/LdcUserProfile for the canonical source repository
 * @copyright Copyright (c) 2014 Adam Lundrigan & Contributors
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace LdcUserProfile\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use LdcUserProfile\Options\ModuleOptions;
use LdcUserProfile\Service\ProfileService;
use Zend\Form\Form;
use Zend\View\Model\ViewModel;
use Zend\Http\Response;

class ProfileController extends AbstractActionController
{
    protected $moduleOptions;

    protected $profileService;

    public function indexAction()
    {
        $form = $this->getService()->constructFormForUser($this->user());

        $vm = new ViewModel(array(
            'profileForm' => $form,
            'options' => $this->getModuleOptions(),
        ));

        $prg = $this->prg($this->url()->fromRoute('ldc-user-profile'), true);
        if ($prg instanceof Response) {
            return $prg;
        } elseif ($prg === false) {
            return $vm;
        }

        $fm = $this->flashMessenger()->setNamespace('ldc-user-profile');

        $form->setData($prg);
        if ( ! $form->isValid() ) {
            $fm->addErrorMessage('One or more of the values you provided is invalid.');

            return $vm;
        }

        if ( ! $this->getService()->save($form->getData()) ) {
            $fm->addErrorMessage('There was a problem saving your profile update.');
        }

        $fm->addSuccessMessage('Profile updated successfully!');

        return $this->redirect()->toRoute('ldc-user-profile');
    }

    public function getService()
    {
        if (! $this->profileService instanceof ModuleOptions) {
            $this->profileService = $this->getServiceLocator()->get(
               'ldc-user-profile_service'
            );
        }

        return $this->profileService;
    }

    public function getModuleOptions()
    {
        if (! $this->moduleOptions instanceof ModuleOptions) {
            $this->moduleOptions = $this->getServiceLocator()->get(
               'ldc-user-profile_module_options'
            );
        }

        return $this->moduleOptions;
    }
}
