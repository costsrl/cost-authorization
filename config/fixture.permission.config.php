<?php
/**
 * Created by PhpStorm.
 * User: renato
 * Date: 19/10/18
 * Time: 15.36
 */

return [
    "Application_index"=>[
        "privilege"=>"*",
        "permission_allow"=>1,
        "assert_class"=>"",
        "role"=>"guest",
        "resource"=>"application"
    ],
    "Translation_index"=>[
        "privilege"=>"*",
        "permission_allow"=>1,
        "assert_class"=>"",
        "role"=>"guest",
        "resource"=>"translation-index"
    ],
    "CostAuthentication_Login"=>[
        "privilege"=>"login",
        "permission_allow"=>1,
        "assert_class"=>"",
        "role"=>"guest",
        "resource"=>"authe-index"
    ],
    "CostAuthentication_Logout"=>[
        "privilege"=>"logout",
        "permission_allow"=>1,
        "assert_class"=>"",
        "role"=>"member",
        "resource"=>"authe-index"
    ],
    "CostAuthentication_Register"=>[
        "privilege"=>"*",
        "permission_allow"=>1,
        "assert_class"=>"",
        "role"=>"guest",
        "resource"=>"authe-reg"
    ],
    "CostAuthentication_Register_Edit"=>[
        "privilege"=>"*",
        "permission_allow"=>1,
        "assert_class"=>"",
        "role"=>"member",
        "resource"=>"authe-reg"
    ],
    "Cost_admin_dashboard"=>[
        "privilege"=>"*",
        "permission_allow"=>1,
        "assert_class"=>"",
        "role"=>"admin",
        "resource"=>"admin-index"
    ],
    "CostAuthentication_Login_Denie"=>[
        "privilege"=>"*",
        "permission_allow"=>1,
        "assert_class"=>"",
        "role"=>"member",
        "resource"=>"authe-index"
    ]
];