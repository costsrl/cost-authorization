<?php
/**
 * Created by PhpStorm.
 * User: renato
 * Date: 19/10/18
 * Time: 15.36
 */

return [
    'controller'=>[
        "application"           =>  Application\Controller\IndexController::class,
        "authe-index"           =>  CostAuthentication\Controller\IndexController::class,
        "authe-reg"             =>  CostAuthentication\Controller\Registration::class,
        "autho-index"           =>  CostAuthorization\Controller\IndexController::class,
        "admin-index"           =>  CostAdmin\Controller\IndexController::class,
        "translation-index"     =>  CostTranslation\Controller\IndexController::class
    ]
];