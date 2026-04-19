<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "admin_controller";
$route['confirmation'] = "parent_controller/show_confirmation";
$route['change_pass'] = 'admin_controller/change_pass';

# admin panel setup
$route['admin'] = 'admin_controller';
$route['login'] = 'admin_controller/login';
$route['logout'] = 'admin_controller/logout';
$route['admin_confirmation'] = "admin_controller/show_confirmation";

# user authentication
// $route['login'] = 'parent_controller/login';
// $route['logout'] = 'parent_controller/logout';
$route['change_password'] = 'admin_controller/change_password';
$route['change_password/:any'] = 'admin_controller/change_password';
$route['forgot_password'] = 'parent_controller/forgot_password';

# user management
$route['create_user']='master_controller/create_user';
$route['manage_user']='master_controller/manage_user';
$route['manage_user/:any']='master_controller/manage_user';
$route['create_role']='master_controller/create_role';
$route['manage_role']='master_controller/manage_role';
$route['manage_role/:any']='master_controller/manage_role';


# company
$route['create_company']='settings_controller/create_company';
$route['manage_company']='settings_controller/manage_company';
$route['manage_company/:any']='settings_controller/manage_company';



// ajax data load setup
$route['class_wise_section_list'] = "parent_controller/class_wise_section_list";
$route['class_wise_subject_list'] = "parent_controller/class_wise_subject_list";
$route['class_wise_student_list'] = "parent_controller/class_wise_student_list";
$route['section_wise_student_list'] = "parent_controller/section_wise_student_list";
$route['section_wise_student_list_acc'] = "parent_controller/section_wise_student_list_acc";
$route['class_wise_payment_list'] = "parent_controller/class_wise_payment_list";
$route['student_list_search'] = "parent_controller/student_list_search";
$route['house_wise_student_list'] = "parent_controller/house_wise_student_list";
$route['teacher_wise_salary_list'] = "parent_controller/teacher_wise_salary_list";
$route['update_calendar_data'] = "calendar_controller/update_calendar_data";
$route['group_wise_head_name'] = "parent_controller/group_wise_head_name";
$route['search_account_head'] = "parent_controller/search_account_head";
$route['teacher_salary_check'] = "parent_controller/teacher_salary_check";
$route['student_salary_check'] = "parent_controller/student_salary_check";



// accounts
$route['create_account_head'] = "accounts_controller/create_account_head";
$route['manage_account_head'] = "accounts_controller/manage_account_head";
$route['manage_account_head/:any'] = "accounts_controller/manage_account_head";
$route['create_sub_head'] = "accounts_controller/create_sub_head";
$route['manage_sub_head'] = "accounts_controller/manage_sub_head";
$route['manage_sub_head/:any'] = "accounts_controller/manage_sub_head";
$route['create_journal'] = "accounts_controller/create_journal";
$route['manage_journal'] = "accounts_controller/manage_journal";
$route['manage_journal/:any'] = "accounts_controller/manage_journal";
$route['create_teacher_payment'] = "accounts_controller/create_teacher_payment";
$route['manage_teacher_payment'] = "accounts_controller/manage_teacher_payment";
$route['manage_teacher_payment/:any'] = "accounts_controller/manage_teacher_payment";
$route['create_voucher'] = "accounts_controller/create_voucher";
$route['manage_voucher'] = "accounts_controller/manage_voucher";
$route['manage_voucher/:any'] = "accounts_controller/manage_voucher";
$route['create_credit_voucher'] = "accounts_controller/create_credit_voucher";
$route['manage_credit_voucher'] = "accounts_controller/manage_credit_voucher";
$route['manage_credit_voucher/:any'] = "accounts_controller/manage_credit_voucher";
$route['accounts'] = "accounts_controller/accounts";
$route['student_statement'] = "accounts_controller/student_statement";
$route['teacher_statement'] = "accounts_controller/teacher_statement";
$route['initialize_student_data'] = "accounts_controller/initialize_student_data";
$route['initialize_teacher_data'] = "accounts_controller/initialize_teacher_data";
$route['ledger'] = "accounts_controller/ledger";
$route['trial_balance'] = "accounts_controller/trial_balance";
$route['journal'] = "accounts_controller/journal";
$route['balance_sheet'] = "accounts_controller/balance_sheet";