<?php
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 1);

// $http_host  = "https://$_SERVER[HTTP_HOST]";
// $php_self   = explode(".", $_SERVER['PHP_SELF'])[0];
// $http_model = "https://$_SERVER[HTTP_HOST]/model/update/create?action=";
// $http_delete = "https://$_SERVER[HTTP_HOST]/model/delete/index?";

// define('admin_uri', $http_host."/dashboard");
// define('admin_url', $http_host."/dashboard/");
// define('model_url', $http_model);
// define('base_uri', "https://psychx.io/");
// define('base_url', "https://psychx.io");
// define('manager_url', "https://psychx.io/manager/");
// define('consult_url', "https://psychx.io/consult/");
// define('manager_uri', "https://psychx.io/manager");
// define('creator_uri', "https://vesencomputing.com/");
// define('delete_url', "$http_delete");

// define('therapist_url', "https://psychx.io/therapist/");
// define('therapist_uri', "https://psychx.io/therapist");

// define('client_url', "https://psychx.io/client/");
// define('client_uri', "https://psychx.io/client");


// define('cookie_domain', "$_SERVER[HTTP_HOST]");

// define('ROOT_PATH', realpath(dirname(__FILE__)) . '/');
// define('MODEL_PATH', realpath(dirname(__FILE__)) . '/model/');

// define('file_url', 'https://uploads.psychx.io/images/');


// define('TARGET_DIR', '/home/psychx/uploads.psychx.io/');


// define('LOG_DIR', '/home/psychx/log.psychx.io/');

// LOCAL
$http_host  = "https://$_SERVER[HTTP_HOST]";
$php_self   = explode(".", $_SERVER['PHP_SELF'])[0];
$http_model = "https://$_SERVER[HTTP_HOST]/jambo/dashboard/model/update/create?action=";
$http_delete = "https://$_SERVER[HTTP_HOST]/jambo/dashboard/model/delete/index?";

define('admin_uri', $http_host . "/jambo/dashboard");
define('admin_url', $http_host . "/jambo/dashboard/");
define('model_url', $http_model);
define('base_uri', "https://localhost/jambo/dashboard/");
define('base_url', "https://localhost/jambo/dashboard");

define('creator_uri', "https://vesencomputing.com/");
define('delete_url', "$http_delete");

define('client_url', "https://localhost/jambo/dashboard/client/");
define('client_uri', "https://localhost/jambo/dashboard/client");

define('cookie_domain', "$_SERVER[HTTP_HOST]");

define('ROOT_PATH', realpath(dirname(__FILE__)) . '/');
define('MODEL_PATH', realpath(dirname(__FILE__)) . '/model/');

define('TARGET_DIR', 'C:/xampp/htdocs/jambo/uploads/');
define('LOG_DIR', 'C:/xampp/htdocs/jambo/log/');


define('file_url', "$http_host/jambo/uploads/images/");
define('logo_url', $http_host . "/jambo/dashboard/assets/img/logos/logo.png");
