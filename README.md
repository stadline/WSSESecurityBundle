Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require stadline/WSSESecurityBundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding the following line in the `app/AppKernel.php`
file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new Stadline\WSSESecurityBundle\StadlineWSSESecurityBundle(),
        );

        // ...
    }

    // ...
}
```

Step 3: Update your config.yml
-------------------------

```
    stadline_wssesecurity: ~
```

Step 4: Update your database
-------------------------

```
    app/console doctrine:migrations:diff
    app/console doctrine:migrations:migrate
```

Step 5: Add provider to your security.yml
-------------------------

```
    providers:
        api_partner:
            id: api.partner.user.provider

    firewalls:
        api:
            pattern:            ^/api
            anonymous:          true
            wsse:               true
            provider:           api_partner

```

Usage
============

Start by creating a new Partner

```
    app/console console wsse:partner:create api-test api-test ROLE_API
```

Test your API with real X-WSSE Header

```
    app/console console wsse:wsse-generator api-test
    > UsernameToken Username="api-test", PasswordDigest="MgLLsYsJ3hg/p/dnXCm1H8DkJNA=", Nonce="unjRzavW6hCCTv5zI/YXWA==", Created="2015-05-29T15:46:15Z"
```