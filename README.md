CostAuthorization
=======

**What is CostAuthorization?**

CostAuthorization is a Module for CostAuthorization based on Laminas Framework 2

**What exactly does CostAuthorization?**

Installation
============

Installation via composer is supported, just make sure you've set ```"minimum-stability": "dev"```
in your ```composer.json```file and after that run ```php composer.phar require novigo/Cost-navigation:zf3`

Go to your application configuration in ```./config/application.config.php```and add 'CostNavigation'.
copy Cost-authorization.global.php.dist to ./config/autoload
An example application configuration could look like the following:


open main composer.json and add under auotload key

"autoload" : {
    "psr-0" : {
    }
```

"repositories": [
        {
            "type": "vcs",
        }
    ]


module requirements

depends on:
1) CostAuthentication


```
'modules' => array(
    'Application',
    'CostAuthentication',
    'CostAuthorization'
)
```

CostAuthorization configuration
=============




doctrine generate entity

    1) ./vendor/doctrine/doctrine-module/bin/doctrine-module orm:convert-mapping --namespace="CostAuthorization\\Model\\Entity\\" --filter='Menus' --force  --from-database annotation ./vendor/novigo/Cost-authorization/src/
   

    2) ./vendor/doctrine/doctrine-module/bin/doctrine-module orm:validate-schema


    3) 	./vendor/doctrine/doctrine-module/bin/doctrine-module orm:generate-entities --generate-annotations=true --generate-methods=true ./vendor/novigo/Cost-authorization/src 

    /**
    ./vendor/doctrine/doctrine-module/bin/doctrine-module orm:convert-mapping --namespace="CmsApplication\\Model\\Entity\\" --force  --from-database annotation ./module/CmsApplication/src

    ./vendor/doctrine/doctrine-module/bin/doctrine-module orm:generate-entities --generate-annotations=true --generate-methods=true ./module/CmsApplication/src
    **/

    doctrine create update databse from  entity
    4) ./vendor/doctrine/doctrine-module/bin/doctrine-module orm:schema-tool:update  --dump-sql

    5) ./vendor/doctrine/doctrine-module/bin/doctrine-module schema-tool:update --force


```