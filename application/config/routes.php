<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['admin/select_website'] = 'admin/AdminLogin/select_website';
$route['admin/select_website/(:any)'] = 'admin/AdminLogin/select_website/$1';

$route['select_website'] = 'welcome/select_website';
$route['select_website/(:any)'] = 'welcome/select_website/$1';
$route['switch_website/(:any)'] = 'welcome/switch_website/$1';

$route['admin'] = 'admin/adminLogin';
$route['admin/login'] = 'admin/AdminLogin/validate';
$route['admin/logout'] = 'admin/AdminLogin/logout';

//
$route['changeAdminCredentials'] = 'admin/set_deadlines/AdminController/changeAdminCredentials';
$route['recoverAdminPassword'] = 'admin/AdminLogin/recoverAdminPassword';
$route['adminRecoverPasswordSendEmail'] = 'admin/AdminLogin/adminRecoverPasswordSendEmail';
//



$route['admin/jury_forms'] = 'admin/jury_forms/HomeController/welcome';
$route['admin/jury_forms/create_new_category'] = 'admin/jury_forms/HomeController/createCategory';
$route['admin/jury_forms/categories'] = 'admin/jury_forms/HomeController/welcome';
$route['admin/jury_forms/dynamic_fields'] = 'admin/jury_forms/HomeController/createField';
$route['admin/jury_forms/settings'] = 'admin/jury_forms/HomeController/settings';
$route['admin/jury_forms/update_category'] = 'admin/jury_forms/HomeController/updateCategory';
$route['admin/jury_forms/deleteSelectedCategories'] = 'admin/jury_forms/HomeController/deleteSelectedCategories';
$route['admin/jury_forms/fieldValidate'] = 'admin/jury_forms/HomeController/fieldValidate';
$route['admin/jury_forms/fieldUpdate'] = 'admin/jury_forms/HomeController/fieldUpdate';
$route['admin/jury_forms/deleteField/(:any)'] = 'admin/jury_forms/HomeController/deleteField/$1';
$route['admin/jury_forms/deleteSelectedFields'] = 'admin/jury_forms/HomeController/deleteSelectedFields';
$route['admin/jury_forms/assignNewDocument/(:any)'] = 'admin/jury_forms/HomeController/assignNewDocument/$1';
$route['admin/jury_forms/assignMoreDocuments'] = 'admin/jury_forms/HomeController/assignMoreDocuments';


$route['signupUser'] = 'user/UserController/signupUser';
$route['loginUser'] = 'user/UserController/loginUser';
$route['registerUser'] = 'user/UserController/registerUser';
$route['validateUser'] = 'user/UserController/validateUser';
$route['userLogout'] = 'user/UserController/userLogout';
$route['recoverPassword'] = 'user/UserController/recoverPassword';
$route['recoverPasswordSendEmail'] = 'user/UserController/recoverPasswordSendEmail';
$route['newPassword'] = 'user/UserController/newPassword';
$route['setNewPassword'] = 'user/UserController/setNewPassword';

$route['jury_forms'] = 'user/jury_forms/HomeController';
$route['user_settings'] = 'user/ProfileController/settings';
$route['changePassword'] = 'user/ProfileController/changePassword';


// SetDeadlines Routes

$route['admin/set_deadlines'] = 'admin/set_deadlines/AdminController/rules';
$route['admin/set_deadlines/users'] = 'admin/set_deadlines/AdminController/users';
$route['admin/set_deadlines/settings'] = 'admin/set_deadlines/AdminController/adminSettings';
$route['admin/set_deadlines/add_rule'] = 'admin/set_deadlines/AdminController/addRule';

$route['set_deadlines'] = 'user/set_deadlines/MainController/importRules';
$route['set_deadlines/import_rules'] = 'user/set_deadlines/MainController/importRules';
$route['set_deadlines/user_cases'] = 'user/set_deadlines/MainController/userCases';
$route['set_deadlines/see_all_events'] = 'user/set_deadlines/MainController/populatedRules';
$route['set_deadlines/user_rules'] = 'user/set_deadlines/MainController/userRules';
$route['set_deadlines/user_rules/userDeadlines/(:any)'] = 'user/set_deadlines/MainController/userDeadlines/$1';
$route['set_deadlines/see_all_events'] = 'user/set_deadlines/MainController/populatedCase';
$route['set_deadlines/settings'] = 'user/set_deadlines/UserProfile';

$route['set_deadlines/addCase'] = 'user/set_deadlines/MainController/addCase';
$route['set_deadlines/editCase'] = 'user/set_deadlines/MainController/editCase';
$route['set_deadlines/deleteSelectedCases'] = 'user/set_deadlines/MainController/deleteSelectedCases';
$route['set_deadlines/listedRules/(:any)'] = 'user/set_deadlines/MainController/listedRules/$1';
$route['set_deadlines/calculateDays'] = 'user/set_deadlines/MainController/calculateDays';
$route['set_deadlines/deleteSavedDeadline/(:any)/(:any)'] = 'user/set_deadlines/MainController/deleteSavedDeadline/$1/$2';
$route['set_deadlines/editUserDeadline'] = 'user/set_deadlines/MainController/editUserDeadline';
$route['set_deadlines/addUserDeadline'] = 'user/set_deadlines/MainController/addUserDeadline';
$route['set_deadlines/deleteSelectedUserDeadlines'] = 'user/set_deadlines/MainController/deleteSelectedUserDeadlines';
$route['set_deadlines/importRule/(:any)'] = 'user/set_deadlines/MainController/importRule/$1';
$route['set_deadlines/populatedRules/(:any)'] = 'user/set_deadlines/MainController/populatedRules/$1';
$route['set_deadlines/deleteSavedCase/(:any)'] = 'user/set_deadlines/MainController/deleteSavedCase/$1';
$route['set_deadlines/deleteHoliday/(:any)'] = 'user/set_deadlines/MainController/deleteHoliday/$1';
$route['set_deadlines/addHoliday'] = 'user/set_deadlines/MainController/addHoliday';


$route['deleteSelectedUserRules'] = 'user/set_deadlines/MainController/deleteSelectedUserRules';
$route['set_deadlines/addUserRule'] = 'user/set_deadlines/MainController/addUserRule';
$route['set_deadlines/editUserRule'] = 'user/set_deadlines/MainController/editUserRule';
$route['listedRules'] = 'user/set_deadlines/MainController/listedRules';
$route['set_deadlines/saveCase'] = 'user/set_deadlines/MainController/saveCase';
$route['set_deadlines/searchCasesForDate'] = 'user/set_deadlines/MainController/searchCasesForDate';
$route['set_deadlines/googleDisconnect'] = 'user/set_deadlines/MainController/googleDisconnect';
$route['signupUser'] = 'user/UserController/signupUser';
$route['registerUser'] = 'user/UserController/registerUser';
$route['policy'] = 'user/UserController/policy';
$route['terms'] = 'user/UserController/terms';

$route['cases'] = 'admin/set_deadlines/AdminController/cases';
$route['deadline'] = 'admin/set_deadlines/AdminController/deadline';
$route['adduser'] = 'admin/set_deadlines/AdminController/adduser';
$route['AddNewCase'] = 'admin/set_deadlines/AdminController/cases';
$route['deleteSelectedRules'] = 'admin/set_deadlines/AdminController/deleteSelectedRules';
$route['editDeadline'] = 'admin/set_deadlines/AdminController/editDeadline';
$route['addDeadline'] = 'admin/set_deadlines/AdminController/addDeadline';
$route['editRule'] = 'admin/set_deadlines/AdminController/editRule';