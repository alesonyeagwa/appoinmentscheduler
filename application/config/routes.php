<?php
defined('BASEPATH') OR exit('No direct script access allowed');


spl_autoload_register(function($classname) {
    if(strpos($classname, 'CI_') !== 0) {
    	$replace_dir = DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR;
        $classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
        $classname = str_replace($replace_dir, DIRECTORY_SEPARATOR, $classname);
		$file = APPPATH . 'libraries/' . $classname . '.php';
		if(file_exists($file) && is_file($file)) {
			@include_once($file);
		}
	}
});
// function __autoload($classname) {
// 	if(strpos($classname, 'CI_') !== 0) {
// 		$file = APPPATH . 'libraries/' . $classname . '.php';
// 		if(file_exists($file) && is_file($file)) {
// 			@include_once($file);
// 		}
// 	}
// }
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'signin';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['admin/agents'] = 'admin/dashboard/agents';
$route['admin/get_agents'] = 'admin/dashboard/get_agents';
$route['admin/view_agent/(:any)'] = 'admin/dashboard/view_agent/$1';
$route['admin/block_agent/(:any)'] = 'admin/dashboard/block_agent/$1';
$route['admin/unblock_agent/(:any)'] = 'admin/dashboard/unblock_agent/$1';
$route['dashboard/book_appointment'] = 'appointment/book_appointment';
$route['dashboard/get_appointments'] = 'appointment/get_appointments';
$route['dashboard/cancel_appointment'] = 'appointment/cancel_appointment';
$route['dashboard/approve_appointment'] = 'appointment/approve_appointment';
$route['dashboard/complete_appointment'] = 'appointment/complete_appointment';
$route['dashboard/get_agent_services'] = 'service/get_agent_services';
$route['dashboard/delete_timing'] = 'service/delete_timing';
$route['dashboard/save_timing'] = 'service/save_timing';
$route['dashboard/update_service_information'] = 'service/update_service_information';
$route['dashboard/delete_service'] = 'service/delete_service';
$route['dashboard/add_service'] = 'service/add_service';
$route['dashboard/save_extra_services'] = 'extraservice/save_extra_services';
$route['dashboard/delete_extra_service'] = 'extraservice/delete_extra_service';
