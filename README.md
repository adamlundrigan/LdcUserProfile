LdcUserProfile
=================

---
[![Latest Stable Version](https://poser.pugx.org/adamlundrigan/ldc-user-profile/v/stable.svg)](https://packagist.org/packages/adamlundrigan/ldc-user-profile) [![Total Downloads](https://poser.pugx.org/adamlundrigan/ldc-user-profile/downloads.svg)](https://packagist.org/packages/adamlundrigan/ldc-user-profile) [![Latest Unstable Version](https://poser.pugx.org/adamlundrigan/ldc-user-profile/v/unstable.svg)](https://packagist.org/packages/adamlundrigan/ldc-user-profile) [![License](https://poser.pugx.org/adamlundrigan/ldc-user-profile/license.svg)](https://packagist.org/packages/adamlundrigan/ldc-user-profile)
[![Build Status](https://travis-ci.org/adamlundrigan/LdcUserProfile.svg?branch=master)](https://travis-ci.org/adamlundrigan/LdcUserProfile)
[![Code Coverage](https://scrutinizer-ci.com/g/adamlundrigan/LdcUserProfile/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Ocramius/Instantiator/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/adamlundrigan/LdcUserProfile/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/adamlundrigan/LdcUserProfile/?branch=master)

---

## What?


LdcUserProfile is an extensible user profile system for [ZfcUser](https://github.com/ZF-Commons/ZfcUser).  It allows the authenticated user to modify their own account profile.

## How?

1. Install the [Composer](https://getcomposer.org/) package:

    ```
    composer require adamlundrigan/ldc-user-profile:1.*@stable
    ```

2. Enable the module (`LdcUserProfile`) in your ZF2 application.

3. Profit!  The user profile page is mounted on the URL path `/user/profile` by default. 

## Show me!

If you're fortunate enough to be on a *nix system with PHP >=5.4 and `pdo_sqlite`, pop into the `demo` folder and run the setup script (`run.sh`).  This will build the demo application, install the [example profile extension module](demo/ExtensionModule), and start a webserver.  Once that's all done just open your browser and:
 - Navigate to `http://localhost:8080/user/register`
 - Create an account
 - Navigate to `http://localhost:8080/user/profile`

---

## Changing module configuration

You can override the configuration of LdcUserProfile by copying [the dist config file](config/ldc-user-profile.global.php.dist) into the `config/autoload` folder of your ZF2 application and dropping the `.dist` suffix.  Now you can modify the configuration variables within to change the behavior of `LdcUserProfile`!

### Disabling editing of fields

Using the configuration override you can specify which fields in each extension are editable.  For example, to allow only the user to change their display name and password in the ZfcUser extension you would put this in the `ldc-user-profile.global.php.dist` file:

```
'validation_group_override' => array(
    'zfcuser' => array(
        'display_name
        'password',
        'passwordVerify',
    ),
),
```

The structure provided to `validation_group_override` is fed directly into [`Form::setValidationGroup`](http://framework.zend.com/manual/2.3/en/modules/zend.form.quick-start.html#validation-groups) to enable only the specified form fields. 

## Adding custom extensions

The bundled demo module [`ExtensionModule`](demo/ExtensionModule) provides a succinct example of how to implement your own profile extension.  

