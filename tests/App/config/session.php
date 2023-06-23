<?php

return [
    // name of the session
    "name" => "Dune",

    // lifetime of session by seconds
    "lifetime" => 3600,

    // http only
    "http_only" => true,

    // secure
    "secure" => false,

    // encrypted sessions, session values will be encrypted
    "encrypt" => false,

    // path of the session , if set to / , session can access to every page
    "path" => "/",

    // domain for the, which domain session can access
    "domain" => env("APP_DOMAIN"),

    // session storage path
    "storage" => PATH . "/storage/session/",
];
