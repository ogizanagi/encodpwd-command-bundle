# Encod Password Command Bundle

## Description

Provides a simple command to get encoded password following the security configured encoders.


## Installation

Add the following dependency to your composer.json file:

``` json
{
    "require": {
        "_other_packages": "...",
        "ogizanagi/encodpwd-command-bundle": "dev-master"
    }
}

```

Run composer update for this package, and add the following lines to your `AppKernel.php`:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Ogi\EncodPwdCommandBundle\OgiEncodPwdCommandBundle(),
    );
}
```

## Usage

Considering the following configuration:

``` yaml
#security.yml----------------------
security:
    encoders:
        Acme\UserBundle\Entity\User: sha512
        FOS\UserBundle\Model\UserInterface: sha1
        Symfony\Component\Security\Core\User\User: pbkdf2

```

The `Symfony\Component\Security\Core\User\User` is used for in_memory user provider is the main use case for this command (we don't want a plaintext password and need to generate it at application deployment:

``` yaml
#security.yml----------------------
security:
    encoders:
        ...

    providers:
        fos_users:
            id: fos_user.user_provider.username_email
        admin:
            memory:
                users:
                    - { name: %main_admin_login%, password: %main_admin_password%, roles: ['ROLE_ADMIN', 'ROLE_SUPER_ADMIN'] }
        custom_user:
            id: acme_user.user.provider
```

``` yaml
#parameters.yml----------------------
# This file is auto-generated during the composer install
parameters:
    database_driver: pdo_mysql
    database_host: 127.0.0.1
    ...
    main_admin_login: admin
    main_admin_password: e6P2xrFqA62TIb5E9wwB1+HxE6P/W1Auy7Xx3V8Oy1a8G99NmXz9pg== #'admin' encoded with pbkdf2
    ...

```

You could use the following command to generate an encoded password for given user type, which default is `Symfony\Component\Security\Core\User\User` :  
```
php app/console ogi:pwd_encode {PASSWORD} [--salt|-s {SALT}] [--user-class|-uc {USER_CLASS}]
```

where `{PASSWORD}` is the plaintext password you want to encode.  

The result will be something like: 
```
 Encoding password...  
 Your encoded password: e6P2xrFqA62TIb5E9wwB1+HxE6P/W1Auy7Xx3V8Oy1a8G99NmXz9pg==
```

You can call `--help` option to get more infos: 

>Arguments:  
    - password              Password to encode.  
Options:  
    - --salt (-sa)                  User salt.  
    - --user-class (-uc)            The user class for which we want to generate password. (default: "Symfony\\Component\\Security\\Core\\User\\User")  


## Improvements

The following improvements could be made:

- Improve errors handling.
- Allow to generate a password for a given user and update it in database and/or in configuration for in_memory users ?
- Interactive prompt to chose an user class & co.
- Any other suggested improvements.
