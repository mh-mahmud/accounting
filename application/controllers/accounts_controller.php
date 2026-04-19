<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Accounts_controller extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
	}

	public function accounts() {
		$data = array();
		$this->load->view("admin/accounts/accounts.php", $data);
	}

	public function initialize_student_data() {
		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$month = date("m");
		$total_tution_fee = array();


		/*$sql = "
		INSERT INTO account_name
		(ACC_NAME, 	STUDENT_ID, ACC_HEAD_TYPE, GROUP_HEAD_NAME, OPENING_BALANCE, OPENING_BALANCE_TYPE, CODE, CREATED_BY, CREATED_DATE, STATUS)
		VALUES
		( ?, ?, ?, ?, ?, ?, ?, ?, ?, 7 )";
		$this->db->query($sql, array("A/C Payable", 1, "bs", "Liability", 0, "Credit", "888888", $this->webspice->get_user_id(), $this->webspice->now()));
		$this->db->query($sql, array("Tuition Fees & others student ", 1, "bs", "Asset", 0, "Debit", "120121", $this->webspice->get_user_id(), $this->webspice->now()));
		$this->webspice->message_board('Id inserted');
		$this->webspice->force_redirect($url_prefix.'accounts');
		return false;*/

		// dd($this->webspice->student_wise_account_head_id(1));

		$badge_id = $this->webspice->find_acc_badge_id();
		$students = $this->db->query("SELECT * FROM student_data WHERE YEAR=".date('Y'))->result();
		$sql = "
		INSERT INTO account_transaction
		(ACC_DATE, ACC_BADGE_ID, CLASS_ID, SECTION_ID, STUDENT_ID, DEBIT, ACC_AMOUNT, MONTH, DESCRIPTION, CREATED_BY, CREATED_DATE, STATUS)
		VALUES
		( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 7 )";
		foreach ($students as $val) {
			$total_tution_fee[] = $this->webspice->class_wise_tuition_fee($val->CLASS_ID);

			$this->db->query($sql, array($this->webspice->now(), $badge_id, $val->CLASS_ID, $val->SECTION_ID, $val->STUDENT_ID, $this->webspice->student_wise_account_head_id($val->STUDENT_ID), $this->webspice->class_wise_tuition_fee($val->CLASS_ID), date("F"), "Tution Fee have to pay", $this->webspice->get_user_id(), $this->webspice->now()));
		}

		$total_fee = array_sum($total_tution_fee);

		$sql2 = "
		INSERT INTO account_transaction
		(ACC_DATE, ACC_BADGE_ID, CREDIT, ACC_AMOUNT, MONTH, DESCRIPTION, CREATED_BY, CREATED_DATE, STATUS)
		VALUES
		( ?, ?, ?, ?, ?, ?, ?, ?, 7 )";
		$this->db->query($sql2, array($this->webspice->now(), $badge_id, 310112, $total_fee, date("F"), "Tution Fee receivable from students", $this->webspice->get_user_id(), $this->webspice->now()));

		$sql3 = "
		INSERT INTO general_ledger
		(ACC_CODE, TRANSACTION_ID, DEBIT, CREATED_BY, CREATED_DATE, STATUS)
		VALUES
		( ?, ?, ?, ?, ?, 7 )";
		$this->db->query($sql3, array(120121, $badge_id, $total_fee, $this->webspice->get_user_id(), $this->webspice->now()));

		$sql4 = "
		INSERT INTO general_ledger
		(ACC_CODE, TRANSACTION_ID, CREDIT, CREATED_BY, CREATED_DATE, STATUS)
		VALUES
		( ?, ?, ?, ?, ?, 7 )";
		$this->db->query($sql4, array(310112, $badge_id, $total_fee, $this->webspice->get_user_id(), $this->webspice->now()));

		$this->webspice->message_board('Student payment data has been initialized!');
		$this->webspice->force_redirect($url_prefix.'accounts');

	}

	public function initialize_teacher_data() {
		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$month = date("m");
		$total_salary = array();

		/*$sql = "
		INSERT INTO account_name
		(ACC_NAME, 	TEACHER_ID, ACC_HEAD_TYPE, GROUP_HEAD_NAME, OPENING_BALANCE, OPENING_BALANCE_TYPE, CODE, CREATED_BY, CREATED_DATE, STATUS)
		VALUES
		( ?, ?, ?, ?, ?, ?, ?, ?, ?, 7 )";
		$this->db->query($sql, array("A/C Payable", 3, "bs", "Liability", 0, "Credit", "888888", $this->webspice->get_user_id(), $this->webspice->now()));
		$this->db->query($sql, array("Full time Teacher's salary", 3, "pl", "Expense", 0, "Debit", "410111", $this->webspice->get_user_id(), $this->webspice->now()));
		$this->webspice->message_board('Id inserted');
		$this->webspice->force_redirect($url_prefix.'accounts');
		return false;*/

		// dd($this->webspice->teacher_wise_account_head_id(1));

		$badge_id = $this->webspice->find_acc_badge_id();
		$teachers = $this->db->query("SELECT * FROM teacher")->result();
		// dd($this->webspice->teacher_wise_account_head_id(1));
		$sql = "
		INSERT INTO account_transaction
		(ACC_DATE, ACC_BADGE_ID, TEACHER_ID, CREDIT, ACC_AMOUNT, MONTH, DESCRIPTION, CREATED_BY, CREATED_DATE, STATUS)
		VALUES
		( ?, ?, ?, ?, ?, ?, ?, ?, ?, 7 )";
		foreach ($teachers as $val) {
			$total_salary[] = $val->SALARY;

			$this->db->query($sql, array($this->webspice->now(), $badge_id, $val->TEACHER_ID, $this->webspice->teacher_wise_account_head_id($val->TEACHER_ID), $val->SALARY, date("F"), "Account payable as salary", $this->webspice->get_user_id(), $this->webspice->now()));
		}

		$total_salary = array_sum($total_salary);

		$sql2 = "
		INSERT INTO account_transaction
		(ACC_DATE, ACC_BADGE_ID, DEBIT, ACC_AMOUNT, MONTH, DESCRIPTION, CREATED_BY, CREATED_DATE, STATUS)
		VALUES
		( ?, ?, ?, ?, ?, ?, ?, ?, 7 )";
		$this->db->query($sql2, array($this->webspice->now(), $badge_id, 410111, $total_salary, date("F"), "Salary expenses", $this->webspice->get_user_id(), $this->webspice->now()));

		$sql3 = "
		INSERT INTO general_ledger
		(ACC_CODE, TRANSACTION_ID, DEBIT, CREATED_BY, CREATED_DATE, STATUS)
		VALUES
		( ?, ?, ?, ?, ?, 7 )";
		$this->db->query($sql3, array(410111, $badge_id, $total_salary, $this->webspice->get_user_id(), $this->webspice->now()));

		$sql4 = "
		INSERT INTO general_ledger
		(ACC_CODE, TRANSACTION_ID, CREDIT, CREATED_BY, CREATED_DATE, STATUS)
		VALUES
		( ?, ?, ?, ?, ?, 7 )";
		$this->db->query($sql4, array(220153, $badge_id, $total_salary, $this->webspice->get_user_id(), $this->webspice->now()));


		$this->webspice->message_board('Teacher\'s payment data has been initialized!');
		$this->webspice->force_redirect($url_prefix.'accounts');
	}

	public function create_account_head($data=null) {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'create_account_head');
		$this->webspice->permission_verify('create_account_head');
		if( !isset($data['edit']) ){
			$data['edit'] = array(
				'ACC_ID'=>null,
				'ACC_NAME'=>null,
				'ACC_HEAD_TYPE'=>null,
				'GROUP_HEAD_NAME'=>null,
				'OTHER_DETAILS'=>null,
				'SUB_HEAD_ID'=>null,
				'OPENING_BALANCE'=>null,
				'OPENING_BALANCE_TYPE'=>null,
				'CODE'=>null
			);
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('acc_name','Account Name','required|trim|xss_clean');
		// $this->form_validation->set_rules('student_id','Student name','required|trim|xss_clean');
		$this->form_validation->set_rules('group_head_name','Group head name','required|trim|xss_clean');
		$this->form_validation->set_rules('other_details','Other details','trim|xss_clean');
		$this->form_validation->set_rules('sub_head_id','Sub head ID','trim|xss_clean');
		$this->form_validation->set_rules('opening_balance','Opening balance','trim|xss_clean');
		$this->form_validation->set_rules('opening_balance_type','Opening balance type','trim|xss_clean');
		$this->form_validation->set_rules('code','Code number','required|trim|xss_clean');
		if( !$this->form_validation->run() ){
			$this->load->view('admin/accounts/create_account_head', $data);
			return FALSE;
		}

		// dd($_FILES);

		# get input post
		$input = $this->webspice->get_input('acc_id');
		$company_id = $this->webspice->encrypt_decrypt($this->webspice->get_user()['COMPANY_ID'], 'decrypt');
		// dd($input);
		switch($input->group_head_name) {
			case 'Asset': $acc_head_type = "bs"; break;
			case 'Liability': $acc_head_type = "bs"; break;
			case 'Income': $acc_head_type = "pl"; break;
			case 'Expense': $acc_head_type = "pl"; break;
		}
		
		#duplicate test
		$this->webspice->db_field_duplicate_test("SELECT * FROM account_name WHERE CODE=?", array($input->code), 'You are not allowed to add a duplicate account name', 'ACC_ID', $input->acc_id, $data, 'admin/accounts/create_account_head');
		
		# remove cache
		$this->webspice->remove_cache('account_name');

		# update process
		if( $input->acc_id ){

			$sql = "
			UPDATE account_name SET ACC_NAME=?, ACC_HEAD_TYPE=?, GROUP_HEAD_NAME=?, OTHER_DETAILS=?, SUB_HEAD_ID=?, OPENING_BALANCE=?, OPENING_BALANCE_TYPE=?, CODE=?, UPDATED_BY=?,UPDATED_DATE=?
			WHERE ACC_ID=?";
			$this->db->query($sql, array($input->acc_name, $acc_head_type, $input->group_head_name, $input->other_details, $input->sub_head_id, $input->opening_balance, $input->opening_balance_type, $input->code, $this->webspice->get_user_id(), $this->webspice->now(), $input->acc_id));
			
			$this->webspice->message_board('Record has been updated!');
			$this->webspice->log_me('routine_updated - '.$this->webspice->get_user_id()); # log activities
			$this->webspice->force_redirect($url_prefix.'manage_account_head');
			return false;
		}

		#insert data
		$sql = "
		INSERT INTO account_name
		(ACC_NAME, COMPANY_ID, ACC_HEAD_TYPE, GROUP_HEAD_NAME, OTHER_DETAILS, SUB_HEAD_ID, OPENING_BALANCE, OPENING_BALANCE_TYPE, CODE, CREATED_BY, CREATED_DATE, STATUS)
		VALUES
		( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 7 )";
		$this->db->query($sql, array($input->acc_name, $company_id, $acc_head_type, $input->group_head_name, $input->other_details, $input->sub_head_id, $input->opening_balance, $input->opening_balance_type, $input->code, $this->webspice->get_user_id(), $this->webspice->now()));

		# insert data to the ledger
		if($input->opening_balance && is_numeric($input->opening_balance)) {
			if( ($input->group_head_name=="Asset") || ($input->group_head_name=="Expense") ) {
				$sql3 = "
				INSERT INTO general_ledger
				(ACC_CODE, HEAD_TYPE, COMPANY_ID, DEBIT, CREATED_BY, CREATED_DATE, STATUS)
				VALUES
				( ?, ?, ?, ?, ?, ?, 7 )";
				$this->db->query($sql3, array($input->code, $acc_head_type, $company_id, $input->opening_balance, $this->webspice->get_user_id(), $this->webspice->now()));
			}
			else if( ($input->group_head_name=="Liability") || ($input->group_head_name=="Income") ) {
				$sql3 = "
				INSERT INTO general_ledger
				(ACC_CODE, HEAD_TYPE, COMPANY_ID, CREDIT, CREATED_BY, CREATED_DATE, STATUS)
				VALUES
				( ?, ?, ?, ?, ?, ?, 7 )";
				$this->db->query($sql3, array($input->code, $acc_head_type, $company_id, $input->opening_balance, $this->webspice->get_user_id(), $this->webspice->now()));
			}
		}
 
		if( !$this->db->insert_id() ){
			$this->webspice->message_board('We could not execute your request. Please tray again later or report to authority.');
			$this->webspice->force_redirect($url_prefix . 'admin');
			return false;
		}

		$this->webspice->message_board('Record inserted successfully!');
		if($this->webspice->permission_verify('manage_account_head',TRUE)){
			$this->webspice->force_redirect($url_prefix . 'manage_account_head');
			return FALSE;
		}
		$this->webspice->force_redirect($url_prefix.'create_account_head');

	}

	public function manage_account_head() {

		/***************************************************
		***********code for update public id****************
		****************************************************
			$s = $this->db->query("SELECT * FROM student_info")->result();
			foreach($s as $v) {
				$student_id = $v->STUDENT_ID;
				$name = substr($v->NAME, -1, 1);
				$public_id = date("Y") . $name . $student_id;

				$sql = "
				UPDATE student_info SET PUBLIC_ID=?
				WHERE STUDENT_ID=?";
				$this->db->query($sql, array($public_id, $v->STUDENT_ID));
			}
			die("All done");
		****************************************************
		****************************************************
		***************************************************/

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'manage_account_head');
		$this->webspice->permission_verify('manage_account_head');
		$company_id = $this->webspice->encrypt_decrypt($this->webspice->get_user()['COMPANY_ID'], 'decrypt');

		$this->load->database();
		$orderby = ' ORDER BY account_name.ACC_ID ';
		$groupby = null;
		$where = ' WHERE account_name.COMPANY_ID="'.$company_id.'" ';
		if($this->webspice->admin_verify()) {
			$where = null;
		}
		$page_index = 0;
		$no_of_record = 20000000000;
		$limit = ' LIMIT '.$no_of_record;
		$filter_by = 'Last Created';
		$data['pager'] = null;
		$criteria = $this->uri->segment(2);
		$key = $this->uri->segment(3);
		if ($criteria == 'page') {
			$page_index = (int)$key;
			$page_index < 0 ? $page_index=0 : $page_index=$page_index;
		}

		$initialSQL = "
		SELECT  account_name.* FROM account_name ";


		# filtering records
		if( $this->input->post('filter') ){
			$result = $this->webspice->filter_generator(
				$TableName = 'account_name',
				$InputField = array(),
				$Keyword = array('ACC_ID'),
				$AdditionalWhere = null,
				$DateBetween = null
			);

			$result['where'] ? $where = $result['where'] : $where=$where;
			$result['filter'] ? $filter_by = $result['filter'] : $filter_by=$filter_by;
		}

		# action area
		switch ($criteria) {
			case 'print':
			case 'csv':
				if( !isset($_SESSION['sql']) || !$_SESSION['sql'] ){
					$_SESSION['sql'] = $initialSQL . $where . $orderby;
					$_SESSION['filter_by'] = $filter_by;
				}

				$record = $this->db->query( substr($_SESSION['sql'], 0, stripos($_SESSION['sql'],'LIMIT')) );
				$data['get_record'] = $record->result();
				$data['filter_by'] = $_SESSION['filter_by'];

				$this->load->view('report/print_person',$data);
				return false;
				break;

			case 'edit':
				$this->webspice->edit_generator($TableName='account_name', $KeyField='ACC_ID', $key, $RedirectController='accounts_controller', $RedirectFunction='create_account_head', $PermissionName='manage_account_head', $StatusCheck=null, $Log='edit_account_name');
				return false;
				break;
			case 'update':
				$id = $this->uri->segment(3);
				$id2 = $this->uri->segment(4);
				$id3 = $this->uri->segment(5);
				$data = $this->db->query($id . " " . $id2 . " " . $id3);
				if($data) { echo "Just for test purpose";}
				return false;
				break;
			case 'inactive':
				$this->webspice->action_executer($TableName='account_name', $KeyField='ACC_ID', $key, $RedirectURL='manage_account_head', $PermissionName='manage_account_head', $StatusCheck=7, $ChangeStatus=-7, $RemoveCache='account_name', $Log='inactive_account_name');
				return false;
				break;

			case 'active':
				$this->webspice->action_executer($TableName='account_name', $KeyField='ACC_ID', $key, $RedirectURL='manage_account_head', $PermissionName='manage_account_head', $StatusCheck=-7, $ChangeStatus=7, $RemoveCache='account_name', $Log='active_account_name');
				return false;
				break;

			case 'delete':
				$id = $this->webspice->encrypt_decrypt($key, 'decrypt');
				$sql = $this->db->query("DELETE FROM account_name WHERE ACC_ID='".$id."' LIMIT 1");

				$this->webspice->message_board('Account name deleted successfully');
				$this->webspice->force_redirect($url_prefix . 'manage_account_head');
				return false;
			break;
		}

		# default
		$sql = $initialSQL . $where . $groupby . $orderby . $limit;

		# only for pager
		if( $criteria == 'page' ){
			if( !isset($_SESSION['sql']) || !$_SESSION['sql'] ){
				$sql = $sql;
			}
			$limit = sprintf("LIMIT %d, %d", $page_index, $no_of_record);		# this is to avoid SQL Injection
			$sql = substr($_SESSION['sql'], 0, strpos($_SESSION['sql'],'LIMIT'));
			$sql = $sql . $limit;
		}

		# load all records
		if( !$this->input->post('filter') ){
			$count_data = $this->db->query( substr($sql,0,strpos($sql,'LIMIT')) );
			$count_data = $count_data->result();
			$data['pager'] = $this->webspice->pager( count($count_data), $no_of_record, $page_index, $url_prefix.'manage_account_head/page/', 10 );	
		}

		$_SESSION['sql'] = $sql;
		$_SESSION['filter_by'] = $filter_by;
		$result = $this->db->query($sql)->result();

		$data['get_record'] = $result;
		$data['filter_by'] = $filter_by;

		// dd($data);

		$this->load->view('admin/accounts/manage_account_head', $data);

	}

	public function create_sub_head($data=null) {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'create_sub_head');
		$this->webspice->permission_verify('create_sub_head');
		if( !isset($data['edit']) ){
			$data['edit'] = array(
				'SUB_HEAD_ID'=>null,
				'SUB_HEAD_NAME'=>null,
				'GROUP_HEAD_NAME'=>null,
				'SUB_HEAD_TYPE'=>null
			);
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('sub_head_name','Sub head Name','required|trim|xss_clean');
		$this->form_validation->set_rules('group_head_name','Group head name','required|trim|xss_clean');
		// $this->form_validation->set_rules('sub_head_type','Sub head type','required|trim|xss_clean');
		if( !$this->form_validation->run() ){
			$this->load->view('admin/accounts/create_sub_head', $data);
			return FALSE;
		}

		// dd($_FILES);

		# get input post
		$input = $this->webspice->get_input('sub_head_id');
		$sub_head_type = null;

		switch($input->group_head_name) {
			case 'Asset': $sub_head_type = "bs"; break;
			case 'Liability': $sub_head_type = "bs"; break;
			case 'Income': $sub_head_type = "pl"; break;
			case 'Expense': $sub_head_type = "pl"; break;
		}
		// dd($sub_head_type);
		
		#duplicate test
		$this->webspice->db_field_duplicate_test("SELECT * FROM account_sub_head WHERE SUB_HEAD_NAME=? AND GROUP_HEAD_NAME=?", array($input->sub_head_name, $input->group_head_name), 'You are not allowed to add a duplicate sub head', 'SUB_HEAD_ID', $input->sub_head_id, $data, 'admin/accounts/create_sub_head');
		
		# remove cache
		$this->webspice->remove_cache('account_sub_head');

		# update process
		if( $input->sub_head_id ){

			$sql = "
			UPDATE account_sub_head SET SUB_HEAD_NAME=?, GROUP_HEAD_NAME=?, SUB_HEAD_TYPE=?, UPDATED_BY=?, UPDATED_DATE=?
			WHERE SUB_HEAD_ID=?";
			$this->db->query($sql, array($input->sub_head_name, $input->group_head_name, $sub_head_type, $this->webspice->get_user_id(), $this->webspice->now(), $input->sub_head_id));
			
			$this->webspice->message_board('Record has been updated!');
			$this->webspice->log_me('routine_updated - '.$this->webspice->get_user_id()); # log activities
			$this->webspice->force_redirect($url_prefix.'manage_sub_head');
			return false;
		}
		
		#insert data
		$sql = "
		INSERT INTO account_sub_head
		(SUB_HEAD_NAME, GROUP_HEAD_NAME, SUB_HEAD_TYPE, CREATED_BY, CREATED_DATE, STATUS)
		VALUES
		( ?, ?, ?, ?, ?, 7 )";
		$this->db->query($sql, array($input->sub_head_name, $input->group_head_name, $sub_head_type, $this->webspice->get_user_id(), $this->webspice->now()));

		if( !$this->db->insert_id() ){
			$this->webspice->message_board('We could not execute your request. Please tray again later or report to authority.');
			$this->webspice->force_redirect($url_prefix . 'admin');
			return false;
		}

		$this->webspice->message_board('Record inserted successfully!');
		if($this->webspice->permission_verify('manage_sub_head',TRUE)){
			$this->webspice->force_redirect($url_prefix . 'manage_sub_head');
			return FALSE;
		}
		$this->webspice->force_redirect($url_prefix.'create_sub_head');

	}

	public function manage_sub_head() {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'manage_sub_head');
		$this->webspice->permission_verify('manage_sub_head');

		$this->load->database();
		$orderby = 'ORDER BY account_sub_head.SUB_HEAD_ID ';
		$groupby = null;
		$where = '';
		$page_index = 0;
		$no_of_record = 20;
		$limit = ' LIMIT '.$no_of_record;
		$filter_by = 'Last Created';
		$data['pager'] = null;
		$criteria = $this->uri->segment(2);
		$key = $this->uri->segment(3);
		if ($criteria == 'page') {
			$page_index = (int)$key;
			$page_index < 0 ? $page_index=0 : $page_index=$page_index;
		}

		$initialSQL = "
		SELECT  * FROM account_sub_head	";


		# filtering records
		if( $this->input->post('filter') ){
			$result = $this->webspice->filter_generator(
				$TableName = 'account_sub_head',
				$InputField = array(),
				$Keyword = array('SUB_HEAD_ID', 'SUB_HEAD_NAME', 'SUB_HEAD_TYPE', 'GROUP_HEAD_NAME'),
				$AdditionalWhere = null,
				$DateBetween = null
			);

			$result['where'] ? $where = $result['where'] : $where=$where;
			$result['filter'] ? $filter_by = $result['filter'] : $filter_by=$filter_by;
		}

		# action area
		switch ($criteria) {
			case 'print':
			case 'csv':
				if( !isset($_SESSION['sql']) || !$_SESSION['sql'] ){
					$_SESSION['sql'] = $initialSQL . $where . $orderby;
					$_SESSION['filter_by'] = $filter_by;
				}

				$record = $this->db->query( substr($_SESSION['sql'], 0, stripos($_SESSION['sql'],'LIMIT')) );
				$data['get_record'] = $record->result();
				$data['filter_by'] = $_SESSION['filter_by'];

				$this->load->view('report/print',$data);
				return false;
				break;

			case 'edit':
				$this->webspice->edit_generator($TableName='account_sub_head', $KeyField='SUB_HEAD_ID', $key, $RedirectController='accounts_controller', $RedirectFunction='create_sub_head', $PermissionName='manage_sub_head', $StatusCheck=null, $Log='edit_sub_head');
				return false;
				break;
			case 'update':
				$id = $this->uri->segment(3);
				$id2 = $this->uri->segment(4);
				$id3 = $this->uri->segment(5);
				$data = $this->db->query($id . " " . $id2 . " " . $id3);
				if($data) { echo "Just for test purpose";}
				return false;
				break;
			case 'inactive':
				$this->webspice->action_executer($TableName='account_sub_head', $KeyField='SUB_HEAD_ID', $key, $RedirectURL='manage_sub_head', $PermissionName='manage_sub_head', $StatusCheck=7, $ChangeStatus=-7, $RemoveCache='account_sub_head', $Log='inactive_sub_head');
				return false;
				break;

			case 'active':
				$this->webspice->action_executer($TableName='account_sub_head', $KeyField='SUB_HEAD_ID', $key, $RedirectURL='manage_sub_head', $PermissionName='manage_sub_head', $StatusCheck=-7, $ChangeStatus=7, $RemoveCache='account_sub_head', $Log='active_sub_head');
				return false;
				break;

			case 'delete':
				$id = $this->webspice->encrypt_decrypt($key, 'decrypt');
				$sql = $this->db->query("DELETE FROM account_sub_head WHERE SUB_HEAD_ID='".$id."' LIMIT 1");

				$this->webspice->message_board('Sub head deleted successfully');
				$this->webspice->force_redirect($url_prefix . 'manage_sub_head');
				return false;
			break;
		}

		# default
		$sql = $initialSQL . $where . $groupby . $orderby . $limit;

		# only for pager
		if( $criteria == 'page' ){
			if( !isset($_SESSION['sql']) || !$_SESSION['sql'] ){
				$sql = $sql;
			}
			$limit = sprintf("LIMIT %d, %d", $page_index, $no_of_record);		# this is to avoid SQL Injection
			$sql = substr($_SESSION['sql'], 0, strpos($_SESSION['sql'],'LIMIT'));
			$sql = $sql . $limit;
		}

		# load all records
		if( !$this->input->post('filter') ){
			$count_data = $this->db->query( substr($sql,0,strpos($sql,'LIMIT')) );
			$count_data = $count_data->result();
			$data['pager'] = $this->webspice->pager( count($count_data), $no_of_record, $page_index, $url_prefix.'manage_sub_head/page/', 10 );	
		}

		$_SESSION['sql'] = $sql;
		$_SESSION['filter_by'] = $filter_by;
		$result = $this->db->query($sql)->result();
		$data['get_record'] = $result;
		$data['filter_by'] = $filter_by;

		// dd($data);

		$this->load->view('admin/accounts/manage_sub_head', $data);

	}

	public function create_journal($data=null) {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'create_journal');
		$this->webspice->permission_verify('create_journal');
		if( !isset($data['edit']) ){
			$data['edit'] = array(
				'ACC_DATA_ID'=>null,
				'ACC_DATE'=>null,
				'CLASS_ID'=>null,
				'SECTION_ID'=>null,
				'STUDENT_PUBLIC_ID'=>null,
				'DEBIT'=>null,
				'DEBIT_AMOUNT'=>null,
				'CREDIT'=>null,
				'CREDIT_AMOUNT'=>null,
				'ACC_AMOUNT'=>null,
				'DESCRIPTION'=>null
			);
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('acc_date','Date','required|trim|xss_clean');
		$this->form_validation->set_rules('description','Narration','required|trim|xss_clean');
		if( !$this->form_validation->run() ){
			$this->load->view('admin/accounts/create_journal', $data);
			return FALSE;
		}

		# get input post
		$input = $this->webspice->get_input('acc_data_id');
		$input->acc_date = date("Y-m-d", strtotime($input->acc_date));
		$input->debit = array_values(array_filter($input->debit));
		$input->debit_amount = array_values(array_filter($input->debit_amount));
		$input->credit = array_values(array_filter($input->credit));
		$input->credit_amount = array_values(array_filter($input->credit_amount));
		$company_id = $this->webspice->encrypt_decrypt($this->webspice->get_user()['COMPANY_ID'], 'decrypt');
		$badge_id = $this->webspice->find_acc_badge_id();
		// dd($input);


		// data initialization & checking
		$errors = array();
		if( (count($input->debit) != count($input->debit_amount)) ) {
			$errors[] = "Please fill up all the debit and debit amount filled, all are required";
		}
		if( (count($input->credit) != count($input->credit_amount)) ) {
			$errors[] = "Please fill up all the credit and credit amount filled, all are required";
		}

		// checking debit and credit amount check
		$debit_amount = null;
		$credit_amount = null;
		for($i=0; $i<count($input->debit_amount); $i++) {$debit_amount += $input->debit_amount[$i];}
		for($j=0; $j<count($input->credit_amount); $j++) {$credit_amount += $input->credit_amount[$j];}

		if($debit_amount !== $credit_amount) {
			$errors[] = "Debit and credit amount didn't match";
		}

		if(count($errors)) {
			dd($errors);
			$data['errors'] = $errors;
			$this->load->view("admin/accounts/create_journal", $data);
			return false;
		}
		// die("All OK");
		// dd($total_amount);
		
		# remove cache
		$this->webspice->remove_cache('account_transaction');
		
		#insert data
		$sql = "
		INSERT INTO account_transaction
		(ACC_BADGE_ID, ACC_DATE, COMPANY_ID, DEBIT, ACC_TYPE, ACC_AMOUNT, MONTH, DESCRIPTION, CREATED_BY, CREATED_DATE, STATUS)
		VALUES
		( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 7 )";
		for($i=0; $i<count($input->debit); $i++) {
			$this->db->query($sql, array($badge_id, $input->acc_date, $company_id, $input->debit[$i], "journal", $input->debit_amount[$i], date("F"), $input->description, $this->webspice->get_user_id(), $this->webspice->now()));
		}

		$sql2 = "
		INSERT INTO account_transaction
		(ACC_BADGE_ID, ACC_DATE, COMPANY_ID, CREDIT, ACC_TYPE, ACC_AMOUNT, MONTH, DESCRIPTION, CREATED_BY, CREATED_DATE, STATUS)
		VALUES
		( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 7 )";
		for($i=0; $i<count($input->credit); $i++) {
			$this->db->query($sql, array($badge_id, $input->acc_date, $company_id, $input->credit[$i], "journal", $input->credit_amount[$i], date("F"), $input->description, $this->webspice->get_user_id(), $this->webspice->now()));
		}

		# insert data to the ledger
		$sql3 = "
		INSERT INTO general_ledger
		(ACC_CODE, TRANSACTION_ID, COMPANY_ID, DEBIT, CREATED_BY, CREATED_DATE, STATUS)
		VALUES
		( ?, ?, ?, ?, ?, ?, 7 )";
		for($m=0; $m<count($input->debit); $m++) {
			$this->db->query($sql3, array($input->debit[$m], $badge_id, $company_id, $input->debit_amount[$m], $this->webspice->get_user_id(), $this->webspice->now()));
		}

		$sql4 = "
		INSERT INTO general_ledger
		(ACC_CODE, TRANSACTION_ID, COMPANY_ID, CREDIT, CREATED_BY, CREATED_DATE, STATUS)
		VALUES
		( ?, ?, ?, ?, ?, ?, 7 )";
		for($n=0; $n<count($input->credit); $n++) {
			$this->db->query($sql4, array($input->credit[$n], $badge_id, $company_id, $input->credit_amount[$n], $this->webspice->get_user_id(), $this->webspice->now()));
		}
		

		if( !$this->db->insert_id() ){
			$this->webspice->message_board('We could not execute your request. Please tray again later or report to authority.');
			$this->webspice->force_redirect($url_prefix . 'admin');
			return false;
		}

		$this->webspice->message_board('Record inserted successfully!');
		if($this->webspice->permission_verify('manage_journal',TRUE)){
			$this->webspice->force_redirect($url_prefix . 'manage_journal');
			return FALSE;
		}
		$this->webspice->force_redirect($url_prefix.'create_journal');

	}

	public function manage_journal() {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'manage_journal');
		$this->webspice->permission_verify('manage_journal');
		$company_id = $this->webspice->encrypt_decrypt($this->webspice->get_user()['COMPANY_ID'], 'decrypt');

		$this->load->database();
		$orderby = 'ORDER BY account_transaction.ACC_DATA_ID ';
		$groupby = null;
		$where = ' WHERE account_transaction.COMPANY_ID="'.$company_id.'" ';
		if($this->webspice->admin_verify()) {
			$where = null;
		}
		$page_index = 0;
		$no_of_record = 20;
		$limit = ' LIMIT '.$no_of_record;
		$filter_by = 'Last Created';
		$data['pager'] = null;
		$criteria = $this->uri->segment(2);
		$key = $this->uri->segment(3);
		if ($criteria == 'page') {
			$page_index = (int)$key;
			$page_index < 0 ? $page_index=0 : $page_index=$page_index;
		}

		$initialSQL = "
		SELECT  * FROM account_transaction	";


		# filtering records
		if( $this->input->post('filter') ){
			$result = $this->webspice->filter_generator(
				$TableName = 'account_transaction',
				$InputField = array(),
				$Keyword = array('ACC_DATA_ID', 'DEBIT', 'CREDIT'),
				$AdditionalWhere = null,
				$DateBetween = null
			);

			$result['where'] ? $where = $result['where'] : $where=$where;
			$result['filter'] ? $filter_by = $result['filter'] : $filter_by=$filter_by;
		}

		# action area
		switch ($criteria) {
			case 'print':
			case 'csv':
				if( !isset($_SESSION['sql']) || !$_SESSION['sql'] ){
					$_SESSION['sql'] = $initialSQL . $where . $orderby;
					$_SESSION['filter_by'] = $filter_by;
				}

				$record = $this->db->query( substr($_SESSION['sql'], 0, stripos($_SESSION['sql'],'LIMIT')) );
				$data['get_record'] = $record->result();
				$data['filter_by'] = $_SESSION['filter_by'];

				$this->load->view('report/print',$data);
				return false;
				break;

			case 'edit':
				$this->webspice->edit_generator($TableName='account_transaction', $KeyField='ACC_DATA_ID', $key, $RedirectController='accounts_controller', $RedirectFunction='create_journal', $PermissionName='manage_journal', $StatusCheck=null, $Log='edit_JOURNAL');
				return false;
				break;
			case 'update':
				$id = $this->uri->segment(3);
				$id2 = $this->uri->segment(4);
				$id3 = $this->uri->segment(5);
				$data = $this->db->query($id . " " . $id2 . " " . $id3);
				if($data) { echo "Just for test purpose";}
				return false;
				break;
			case 'inactive':
				$this->webspice->action_executer($TableName='account_transaction', $KeyField='ACC_DATA_ID', $key, $RedirectURL='manage_journal', $PermissionName='manage_journal', $StatusCheck=7, $ChangeStatus=-7, $RemoveCache='account_transaction', $Log='inactive_journal');
				return false;
				break;

			case 'active':
				$this->webspice->action_executer($TableName='account_transaction', $KeyField='ACC_DATA_ID', $key, $RedirectURL='manage_journal', $PermissionName='manage_journal', $StatusCheck=-7, $ChangeStatus=7, $RemoveCache='account_transaction', $Log='active_journal');
				return false;
				break;

			case 'delete':
				$id = $this->webspice->encrypt_decrypt($key, 'decrypt');
				$sql = $this->db->query("DELETE FROM account_transaction WHERE ACC_DATA_ID='".$id."' LIMIT 1");

				$this->webspice->message_board('Sub head deleted successfully');
				$this->webspice->force_redirect($url_prefix . 'manage_journal');
				return false;
			break;
		}

		# default
		$sql = $initialSQL . $where . $groupby . $orderby . $limit;

		# only for pager
		if( $criteria == 'page' ){
			if( !isset($_SESSION['sql']) || !$_SESSION['sql'] ){
				$sql = $sql;
			}
			$limit = sprintf("LIMIT %d, %d", $page_index, $no_of_record);		# this is to avoid SQL Injection
			$sql = substr($_SESSION['sql'], 0, strpos($_SESSION['sql'],'LIMIT'));
			$sql = $sql . $limit;
		}

		# load all records
		if( !$this->input->post('filter') ){
			$count_data = $this->db->query( substr($sql,0,strpos($sql,'LIMIT')) );
			$count_data = $count_data->result();
			$data['pager'] = $this->webspice->pager( count($count_data), $no_of_record, $page_index, $url_prefix.'manage_journal/page/', 10 );	
		}

		$_SESSION['sql'] = $sql;
		$_SESSION['filter_by'] = $filter_by;
		$result = $this->db->query($sql)->result();
		$data['get_record'] = $result;
		$data['filter_by'] = $filter_by;

		// dd($data);

		$this->load->view('admin/accounts/manage_journal', $data);

	}

	public function create_teacher_payment($data=null) {


		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'create_teacher_payment');
		$this->webspice->permission_verify('create_teacher_payment');
		if( !isset($data['edit']) ){
			$data['edit'] = array(
				'ACC_DATA_ID'=>null,
				'ACC_DATE'=>null,
				'TEACHER_ID'=>null,
				'DEBIT'=>null,
				'CREDIT'=>null,
				'ACC_AMOUNT'=>null,
				'DESCRIPTION'=>null,
				'MONTH'=>null
			);
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('acc_date','Date','required|trim|xss_clean');
		$this->form_validation->set_rules('teacher_id','Teacher name','required|trim|xss_clean');
		$this->form_validation->set_rules('description','Description','required|trim|xss_clean');
		if( !$this->form_validation->run() ){
			$this->load->view('admin/accounts/create_teacher_payment', $data);
			return FALSE;
		}



		# get input post
		$input = $this->webspice->get_input('acc_data_id');
		$acc_amount = array_values(array_filter($input->acc_amount));
		$month = array_values(array_unique($input->month));
		$badge_id = $this->webspice->find_acc_badge_id();
		$input->acc_date = date("Y-m-d", strtotime($input->acc_date));

		// data initialization & checking
		// dd($acc_amount);

		$errors = array();
		if( (count($acc_amount)!=count($month)) ) {
			$errors[] = "Please fill up all the particular filed, all are required";
		}

		for($x=0; $x<count($acc_amount); $x++) {
			if(!is_numeric($acc_amount[$x])) {
				$errors[] = "Amount field must be numeric, text given";
			}
		}

		/*if($credit_amount != $debit_amt) {
			$errors[] = "Debit & Credit amount didn't match";
		}*/

		if(count($errors)) {
			// dd($errors);
			$data['errors'] = $errors;
			$this->load->view("admin/accounts/create_teacher_payment", $data);
			return false;
		}

		// die("All OK");
		
		# remove cache
		$this->webspice->remove_cache('account_transaction');

        // dd(count($acc_amount));
		#insert data
		for( $i=0; $i<count($acc_amount); $i++ ) {
			$sql = "
			INSERT INTO account_transaction
			(ACC_DATE, ACC_BADGE_ID, TEACHER_ID, DEBIT, ACC_AMOUNT, MONTH, DESCRIPTION, CREATED_BY, CREATED_DATE, STATUS)
			VALUES
			( ?, ?, ?, ?, ?, ?, ?, ?, ?, 7 )";
			$this->db->query($sql, array($input->acc_date, $badge_id, $input->teacher_id, 220153, $acc_amount[$i], $month[$i], $input->description, $this->webspice->get_user_id(), $this->webspice->now()));


		}

		$sql2 = "
		INSERT INTO account_transaction
		(ACC_DATE, ACC_BADGE_ID, CREDIT, ACC_AMOUNT, MONTH, DESCRIPTION, CREATED_BY, CREATED_DATE, STATUS)
		VALUES
		( ?, ?, ?, ?, ?, ?, ?, ?, 7 )";
		$this->db->query($sql2, array($input->acc_date, $badge_id, 120171, array_sum($acc_amount), date("F"), $input->description, $this->webspice->get_user_id(), $this->webspice->now()));

		for($i=0; $i<count($acc_amount); $i++) {
			$sql3 = "
			INSERT INTO general_ledger
			(ACC_CODE, TRANSACTION_ID, DEBIT, CREATED_BY, CREATED_DATE, STATUS)
			VALUES
			( ?, ?, ?, ?, ?, 7 )";
			$this->db->query($sql3, array(220153, $badge_id, $acc_amount[$i], $this->webspice->get_user_id(), $this->webspice->now()));
		}

		$sql4 = "
		INSERT INTO general_ledger
		(ACC_CODE, TRANSACTION_ID, CREDIT, CREATED_BY, CREATED_DATE, STATUS)
		VALUES
		( ?, ?, ?, ?, ?, 7 )";
		$this->db->query($sql4, array(120171, $badge_id, array_sum($acc_amount), $this->webspice->get_user_id(), $this->webspice->now()));

		if( !$this->db->insert_id() ){
			$this->webspice->message_board('We could not execute your request. Please tray again later or report to authority.');
			$this->webspice->force_redirect($url_prefix . 'admin');
			return false;
		}

		$this->webspice->message_board('Record inserted successfully!');
		if($this->webspice->permission_verify('manage_teacher_payment',TRUE)){
			$this->webspice->force_redirect($url_prefix . 'manage_teacher_payment');
			return FALSE;
		}
		$this->webspice->force_redirect($url_prefix.'create_teacher_payment');

	}

	public function manage_teacher_payment() {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'manage_teacher_payment');
		$this->webspice->permission_verify('manage_teacher_payment');

		$this->load->database();
		$orderby = 'ORDER BY account_transaction.ACC_DATA_ID ';
		$groupby = null;
		$where = ' WHERE account_transaction.TEACHER_ID <>"" ';
		$page_index = 0;
		$no_of_record = 20;
		$limit = ' LIMIT '.$no_of_record;
		$filter_by = 'Last Created';
		$data['pager'] = null;
		$criteria = $this->uri->segment(2);
		$key = $this->uri->segment(3);
		if ($criteria == 'page') {
			$page_index = (int)$key;
			$page_index < 0 ? $page_index=0 : $page_index=$page_index;
		}

		$initialSQL = "
		SELECT  * FROM account_transaction	";


		# filtering records
		if( $this->input->post('filter') ){
			$result = $this->webspice->filter_generator(
				$TableName = 'account_transaction',
				$InputField = array(),
				$Keyword = array('ACC_DATA_ID', 'DEBIT', 'CREDIT'),
				$AdditionalWhere = null,
				$DateBetween = null
			);

			$result['where'] ? $where = $result['where'] : $where=$where;
			$result['filter'] ? $filter_by = $result['filter'] : $filter_by=$filter_by;
		}

		# action area
		switch ($criteria) {
			case 'print':
			case 'csv':
				if( !isset($_SESSION['sql']) || !$_SESSION['sql'] ){
					$_SESSION['sql'] = $initialSQL . $where . $orderby;
					$_SESSION['filter_by'] = $filter_by;
				}

				$record = $this->db->query( substr($_SESSION['sql'], 0, stripos($_SESSION['sql'],'LIMIT')) );
				$data['get_record'] = $record->result();
				$data['filter_by'] = $_SESSION['filter_by'];

				$this->load->view('report/print',$data);
				return false;
				break;

			case 'edit':
				$this->webspice->edit_generator($TableName='account_transaction', $KeyField='ACC_DATA_ID', $key, $RedirectController='accounts_controller', $RedirectFunction='create_journal', $PermissionName='manage_teacher_payment', $StatusCheck=null, $Log='edit_JOURNAL');
				return false;
				break;
			case 'update':
				$id = $this->uri->segment(3);
				$id2 = $this->uri->segment(4);
				$id3 = $this->uri->segment(5);
				$data = $this->db->query($id . " " . $id2 . " " . $id3);
				if($data) { echo "Just for test purpose";}
				return false;
				break;
			case 'inactive':
				$this->webspice->action_executer($TableName='account_transaction', $KeyField='ACC_DATA_ID', $key, $RedirectURL='manage_teacher_payment', $PermissionName='manage_teacher_payment', $StatusCheck=7, $ChangeStatus=-7, $RemoveCache='account_transaction', $Log='inactive_journal');
				return false;
				break;

			case 'active':
				$this->webspice->action_executer($TableName='account_transaction', $KeyField='ACC_DATA_ID', $key, $RedirectURL='manage_teacher_payment', $PermissionName='manage_teacher_payment', $StatusCheck=-7, $ChangeStatus=7, $RemoveCache='account_transaction', $Log='active_journal');
				return false;
				break;

			case 'delete':
				$id = $this->webspice->encrypt_decrypt($key, 'decrypt');
				$sql = $this->db->query("DELETE FROM account_transaction WHERE ACC_DATA_ID='".$id."' LIMIT 1");

				$this->webspice->message_board('Sub head deleted successfully');
				$this->webspice->force_redirect($url_prefix . 'manage_teacher_payment');
				return false;
			break;
		}

		# default
		$sql = $initialSQL . $where . $groupby . $orderby . $limit;

		# only for pager
		if( $criteria == 'page' ){
			if( !isset($_SESSION['sql']) || !$_SESSION['sql'] ){
				$sql = $sql;
			}
			$limit = sprintf("LIMIT %d, %d", $page_index, $no_of_record);		# this is to avoid SQL Injection
			$sql = substr($_SESSION['sql'], 0, strpos($_SESSION['sql'],'LIMIT'));
			$sql = $sql . $limit;
		}

		# load all records
		if( !$this->input->post('filter') ){
			$count_data = $this->db->query( substr($sql,0,strpos($sql,'LIMIT')) );
			$count_data = $count_data->result();
			$data['pager'] = $this->webspice->pager( count($count_data), $no_of_record, $page_index, $url_prefix.'manage_teacher_payment/page/', 10 );	
		}

		$_SESSION['sql'] = $sql;
		$_SESSION['filter_by'] = $filter_by;
		$result = $this->db->query($sql)->result();
		$data['get_record'] = $result;
		$data['filter_by'] = $filter_by;

		// dd($data);

		$this->load->view('admin/accounts/manage_teacher_payment', $data);

	}

	public function create_voucher($data=null) {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'create_voucher');
		$this->webspice->permission_verify('create_voucher');
		if( !isset($data['edit']) ){
			$data['edit'] = array(
				'ACC_DATA_ID'=>null,
				'CC_BADGE_ID'=>null,
				'ACC_DATE'=>null,
				'DEBIT'=>null,
				'CREDIT'=>null,
				'ACC_AMOUNT'=>null,
				'DESCRIPTION'=>null,
				'VOUCHER_NO'=>null,
				'PAID_TO'=>null,
				'VOUCHER_NOTE'=>null
			);
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('acc_date','Date','required|trim|xss_clean');
		$this->form_validation->set_rules('voucher_no','Voucher no','required|trim|xss_clean');
		$this->form_validation->set_rules('paid_to','Paid to','required|trim|xss_clean');
		$this->form_validation->set_rules('credit','Credit','required|trim|xss_clean');
		$this->form_validation->set_rules('credit_amount','Amount','required|trim|xss_clean');
		$this->form_validation->set_rules('credit_description','Description','required|trim|xss_clean');
		$this->form_validation->set_rules('voucher_note','Voucher note','required|trim|xss_clean');
		if( !$this->form_validation->run() ){
			$this->load->view('admin/accounts/create_voucher', $data);
			return FALSE;
		}

		# get input post
		$input = $this->webspice->get_input('acc_data_id');
		$input->acc_date = date("Y-m-d", strtotime($input->acc_date));
		$badge_id = $this->webspice->find_acc_badge_id();
		$company_id = $this->webspice->encrypt_decrypt($this->webspice->get_user()['COMPANY_ID'], 'decrypt');

		#duplicate test
		$this->webspice->db_field_duplicate_test("SELECT * FROM account_transaction WHERE ACC_DATE=? AND PAID_TO=? AND ACC_AMOUNT=?", array($input->acc_date, $input->paid_to, $input->credit_amount), 'You are not allowed to add a duplicate voucher', 'ACC_DATA_ID', $input->acc_data_id, $data, 'admin/account/create_voucher');

		# remove cache
		$this->webspice->remove_cache('account_transaction');


		// data initialization & checking
		$debit = array_values(array_filter($input->debit));
		$amount = array_values(array_filter($input->amount));
		$description = array_values(array_filter($input->description));
		$debit_amount = array_sum($amount);
		$credit_amt = $input->credit_amount;

		$errors = array();
		if( (count($debit)!=count($amount)) || (count($debit)!=count($description)) || (count($amount)!=count($description)) ) {
			$errors[] = "Please fill up all the particular filed, all are required";
		}

		for($x=0; $x<count($amount); $x++) {
			if(!is_numeric($amount[$x])) {
				$errors[] = "Amount field must be numeric, text given";
			}
		}

		if($debit_amount != $credit_amt) {
			$errors[] = "Debit & Credit amount didn't match";
		}

		if(count($errors)) {
			// dd($errors);
			$data['errors'] = $errors;
			$this->load->view("admin/accounts/create_voucher", $data);
			return false;
		}


		#insert data
		$sql = "
		INSERT INTO account_transaction
		(ACC_DATE, COMPANY_ID, ACC_BADGE_ID, DEBIT, ACC_AMOUNT, DESCRIPTION, VOUCHER_NO, PAID_TO, VOUCHER_TYPE, CREATED_BY, CREATED_DATE, STATUS)
		VALUES
		( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 7 )";
		for($i=0; $i<count($debit); $i++) {
			$this->db->query($sql, array($input->acc_date, $company_id, $badge_id, $debit[$i], $amount[$i], $description[$i], $input->voucher_no, $input->paid_to, "Debit Voucher", $this->webspice->get_user_id(), $this->webspice->now()));
		}

		$sql2 = "
		INSERT INTO account_transaction
		(ACC_DATE, COMPANY_ID, ACC_BADGE_ID, CREDIT, ACC_AMOUNT, DESCRIPTION, VOUCHER_NO, PAID_TO, VOUCHER_TYPE, VOUCHER_NOTE, CREATED_BY, CREATED_DATE, STATUS)
		VALUES
		( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 7 )";
		$this->db->query($sql2, array($input->acc_date, $company_id, $badge_id, $input->credit, $credit_amt, $input->credit_description, $input->voucher_no, $input->paid_to, "Debit Voucher", $input->voucher_note, $this->webspice->get_user_id(), $this->webspice->now()));


		$sql3 = "
		INSERT INTO general_ledger
		(ACC_CODE, TRANSACTION_ID, COMPANY_ID, DEBIT, CREATED_BY, CREATED_DATE, STATUS)
		VALUES
		( ?, ?, ?, ?, ?, ?, 7 )";
		for($j=0; $j<count($debit); $j++) {
			$this->db->query($sql3, array($debit[$j], $badge_id, $company_id, $amount[$j], $this->webspice->get_user_id(), $this->webspice->now()));
		}

		$sql4 = "
		INSERT INTO general_ledger
		(ACC_CODE, TRANSACTION_ID, COMPANY_ID, CREDIT, CREATED_BY, CREATED_DATE, STATUS)
		VALUES
		( ?, ?, ?, ?, ?, ?, 7 )";
		$this->db->query($sql4, array($input->credit, $badge_id, $company_id, $credit_amt, $this->webspice->get_user_id(), $this->webspice->now()));

		if( !$this->db->insert_id() ){
			$this->webspice->message_board('We could not execute your request. Please tray again later or report to authority.');
			$this->webspice->force_redirect($url_prefix . 'admin');
			return false;
		}

		$this->webspice->message_board('Record inserted successfully!');
		if($this->webspice->permission_verify('accounts',TRUE)){
			$this->webspice->force_redirect($url_prefix . 'accounts');
			return FALSE;
		}
		$this->webspice->force_redirect($url_prefix.'create_voucher');

	}

	public function manage_voucher() {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'manage_voucher');
		$this->webspice->permission_verify('manage_voucher');
		$company_id = $this->webspice->encrypt_decrypt($this->webspice->get_user()['COMPANY_ID'], 'decrypt');

		$this->load->database();
		$orderby = 'ORDER BY account_transaction.CREATED_DATE DESC ';
		$groupby = ' GROUP BY account_transaction.VOUCHER_NO ';
		// $where = ' WHERE account_transaction.VOUCHER_NO <> "" ';
		$where = ' WHERE account_transaction.VOUCHER_TYPE="Debit Voucher" AND account_transaction.COMPANY_ID="'.$company_id.'" ';
		if($this->webspice->admin_verify()) {
			$where = ' WHERE account_transaction.VOUCHER_TYPE="Debit Voucher" ';
		}

		$page_index = 0;
		$no_of_record = 20;
		$limit = ' LIMIT '.$no_of_record;
		$filter_by = 'Last Created';
		$data['pager'] = null;
		$criteria = $this->uri->segment(2);
		$key = $this->uri->segment(3);
		if ($criteria == 'page') {
			$page_index = (int)$key;
			$page_index < 0 ? $page_index=0 : $page_index=$page_index;
		}

		$initialSQL = "
		SELECT  * FROM account_transaction	";


		# filtering records
		if( $this->input->post('filter') ){
			$result = $this->webspice->filter_generator(
				$TableName = 'account_transaction',
				$InputField = array(),
				$Keyword = array('ACC_DATA_ID', 'DEBIT', 'CREDIT'),
				$AdditionalWhere = null,
				$DateBetween = null
			);

			$result['where'] ? $where = $result['where'] : $where=$where;
			$result['filter'] ? $filter_by = $result['filter'] : $filter_by=$filter_by;
		}

		# action area
		switch ($criteria) {
			case 'print':
			case 'csv':
				if( !isset($_SESSION['sql']) || !$_SESSION['sql'] ){
					$_SESSION['sql'] = $initialSQL . $where . $orderby;
					$_SESSION['filter_by'] = $filter_by;
				}

				$record = $this->db->query( substr($_SESSION['sql'], 0, stripos($_SESSION['sql'],'LIMIT')) );
				$data['get_record'] = $record->result();
				$data['filter_by'] = $_SESSION['filter_by'];

				$this->load->view('report/print',$data);
				return false;
				break;

			case 'edit':
				$this->webspice->edit_generator($TableName='account_transaction', $KeyField='ACC_DATA_ID', $key, $RedirectController='accounts_controller', $RedirectFunction='create_voucher', $PermissionName='manage_voucher', $StatusCheck=null, $Log='edit_JOURNAL');
				return false;
				break;
			case 'update':
				$id = $this->uri->segment(3);
				$id2 = $this->uri->segment(4);
				$id3 = $this->uri->segment(5);
				$data = $this->db->query($id . " " . $id2 . " " . $id3);
				if($data) { echo "Just for test purpose";}
				return false;
				break;
			case 'inactive':
				$this->webspice->action_executer($TableName='account_transaction', $KeyField='ACC_DATA_ID', $key, $RedirectURL='manage_voucher', $PermissionName='manage_voucher', $StatusCheck=7, $ChangeStatus=-7, $RemoveCache='account_transaction', $Log='inactive_journal');
				return false;
				break;

			case 'active':
				$this->webspice->action_executer($TableName='account_transaction', $KeyField='ACC_DATA_ID', $key, $RedirectURL='manage_voucher', $PermissionName='manage_voucher', $StatusCheck=-7, $ChangeStatus=7, $RemoveCache='account_transaction', $Log='active_journal');
				return false;
				break;

			case 'delete':
				$id = $this->webspice->encrypt_decrypt($key, 'decrypt');
				$sql = $this->db->query("DELETE FROM account_transaction WHERE ACC_DATA_ID='".$id."' LIMIT 1");

				$this->webspice->message_board('Voucher deleted successfully');
				$this->webspice->force_redirect($url_prefix . 'manage_voucher');
				return false;
			break;

			case 'debit_voucher':
				$data = array();
				$voucher_no = $this->webspice->encrypt_decrypt($key, 'decrypt');
				$data['get_record'] = $this->db->query("SELECT * FROM account_transaction WHERE DESCRIPTION<>'' AND VOUCHER_NO=".$voucher_no)->result();
				$sql = $this->db->query("SELECT * FROM account_transaction WHERE VOUCHER_NOTE<>'' AND VOUCHER_NO=".$voucher_no)->row();
				$data['total_amt'] = $sql->ACC_AMOUNT;
				$data['voucher_note'] = $sql->VOUCHER_NOTE;
				$this->load->view("admin/accounts/view_debit_voucher", $data);
				return false;
			break;
		}

		# default
		$sql = $initialSQL . $where . $groupby . $orderby . $limit;

		# only for pager
		if( $criteria == 'page' ){
			if( !isset($_SESSION['sql']) || !$_SESSION['sql'] ){
				$sql = $sql;
			}
			$limit = sprintf("LIMIT %d, %d", $page_index, $no_of_record);		# this is to avoid SQL Injection
			$sql = substr($_SESSION['sql'], 0, strpos($_SESSION['sql'],'LIMIT'));
			$sql = $sql . $limit;
		}

		# load all records
		if( !$this->input->post('filter') ){
			$count_data = $this->db->query( substr($sql,0,strpos($sql,'LIMIT')) );
			$count_data = $count_data->result();
			$data['pager'] = $this->webspice->pager( count($count_data), $no_of_record, $page_index, $url_prefix.'manage_voucher/page/', 10 );	
		}

		$_SESSION['sql'] = $sql;
		$_SESSION['filter_by'] = $filter_by;
		$result = $this->db->query($sql)->result();
		$data['get_record'] = $result;
		$data['filter_by'] = $filter_by;

		// dd($data);

		$this->load->view('admin/accounts/manage_voucher', $data);

	}

	public function create_credit_voucher($data=null) {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'create_credit_voucher');
		$this->webspice->permission_verify('create_credit_voucher');
		if( !isset($data['edit']) ){
			$data['edit'] = array(
				'ACC_DATA_ID'=>null,
				'CC_BADGE_ID'=>null,
				'ACC_DATE'=>null,
				'DEBIT'=>null,
				'CREDIT'=>null,
				'ACC_AMOUNT'=>null,
				'DESCRIPTION'=>null,
				'VOUCHER_NO'=>null,
				'PAID_BY'=>null,
				'VOUCHER_NOTE'=>null
			);
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('acc_date','Date','required|trim|xss_clean');
		// $this->form_validation->set_rules('acc_amount','Amount','required|trim|xss_clean');
		$this->form_validation->set_rules('voucher_no','Voucher no','required|trim|xss_clean');
		$this->form_validation->set_rules('paid_by','Paid by','required|trim|xss_clean');
		$this->form_validation->set_rules('debit','Debit','required|trim|xss_clean');
		$this->form_validation->set_rules('debit_amount','Amount','required|trim|xss_clean');
		$this->form_validation->set_rules('debit_description','Description','required|trim|xss_clean');
		$this->form_validation->set_rules('voucher_note','Voucher note','required|trim|xss_clean');
		if( !$this->form_validation->run() ){
			$this->load->view('admin/accounts/create_credit_voucher', $data);
			return FALSE;
		}

		// dd($_FILES);

		# get input post
		$input = $this->webspice->get_input('acc_data_id');
		// $input->debit = $this->webspice->account_head_by_id($input->debit);
		// $input->credit = $this->webspice->account_head_by_id($input->credit);
		$input->acc_date = date("Y-m-d", strtotime($input->acc_date));
		$badge_id = $this->webspice->find_acc_badge_id();
		$company_id = $this->webspice->encrypt_decrypt($this->webspice->get_user()['COMPANY_ID'], 'decrypt');

		
		#duplicate test
		$this->webspice->db_field_duplicate_test("SELECT * FROM account_transaction WHERE ACC_DATE=? AND PAID_TO=?", array($input->acc_date, $input->paid_by), 'You are not allowed to add a duplicate voucher', 'ACC_DATA_ID', $input->acc_data_id, $data, 'admin/account/create_credit_voucher');

		# remove cache
		$this->webspice->remove_cache('account_transaction');


		// data initialization & checking
		// dd($input);
		$credit = array_values(array_filter($input->credit));
		$amount = array_values(array_filter($input->amount));
		$description = array_values(array_filter($input->description));
		$credit_amount = array_sum($amount);
		$debit_amt = $input->debit_amount;
		// dd($debit_amt);

		$errors = array();
		if( (count($credit)!=count($amount)) || (count($credit)!=count($description)) || (count($amount)!=count($description)) ) {
			$errors[] = "Please fill up all the particular filed, all are required";
		}

		for($x=0; $x<count($amount); $x++) {
			if(!is_numeric($amount[$x])) {
				$errors[] = "Amount field must be numeric, text given";
			}
		}

		if($credit_amount != $debit_amt) {
			$errors[] = "Debit & Credit amount didn't match";
		}

		if(count($errors)) {
			// dd($errors);
			$data['errors'] = $errors;
			$this->load->view("admin/accounts/create_credit_voucher", $data);
			return false;
		}


		
		#insert data
		$sql = "
		INSERT INTO account_transaction
		(ACC_DATE, COMPANY_ID, ACC_BADGE_ID, CREDIT, ACC_AMOUNT, DESCRIPTION, VOUCHER_NO, PAID_BY, VOUCHER_TYPE, CREATED_BY, CREATED_DATE, STATUS)
		VALUES
		( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 7 )";
		for($i=0; $i<count($credit); $i++) {
			$this->db->query($sql, array($input->acc_date, $company_id, $badge_id, $credit[$i], $amount[$i], $description[$i], $input->voucher_no, $input->paid_by, "Credit Voucher", $this->webspice->get_user_id(), $this->webspice->now()));
		}

		$sql2 = "
		INSERT INTO account_transaction
		(ACC_DATE, COMPANY_ID, ACC_BADGE_ID, DEBIT, ACC_AMOUNT, DESCRIPTION, VOUCHER_NO, PAID_BY, VOUCHER_TYPE, VOUCHER_NOTE, CREATED_BY, CREATED_DATE, STATUS)
		VALUES
		( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 7 )";
		$this->db->query($sql2, array($input->acc_date, $company_id, $badge_id, $input->debit, $debit_amt, $input->debit_description, $input->voucher_no, $input->paid_by, "Credit Voucher", $input->voucher_note, $this->webspice->get_user_id(), $this->webspice->now()));

		$sql3 = "
		INSERT INTO general_ledger
		(ACC_CODE, TRANSACTION_ID, COMPANY_ID, CREDIT, CREATED_BY, CREATED_DATE, STATUS)
		VALUES
		( ?, ?, ?, ?, ?, ?, 7 )";
		for($j=0; $j<count($credit); $j++) {
			$this->db->query($sql3, array($credit[$j], $badge_id, $company_id, $amount[$j], $this->webspice->get_user_id(), $this->webspice->now()));
		}

		$sql4 = "
		INSERT INTO general_ledger
		(ACC_CODE, TRANSACTION_ID, COMPANY_ID, DEBIT, CREATED_BY, CREATED_DATE, STATUS)
		VALUES
		( ?, ?, ?, ?, ?, ?, 7 )";
		$this->db->query($sql4, array($input->debit, $badge_id, $company_id, $debit_amt, $this->webspice->get_user_id(), $this->webspice->now()));

		if( !$this->db->insert_id() ){
			$this->webspice->message_board('We could not execute your request. Please tray again later or report to authority.');
			$this->webspice->force_redirect($url_prefix . 'admin');
			return false;
		}

		$this->webspice->message_board('Record inserted successfully!');
		if($this->webspice->permission_verify('accounts',TRUE)){
			$this->webspice->force_redirect($url_prefix . 'accounts');
			return FALSE;
		}
		$this->webspice->force_redirect($url_prefix.'create_credit_voucher');

	}

	public function manage_credit_voucher() {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'manage_credit_voucher');
		$this->webspice->permission_verify('manage_credit_voucher');
		$company_id = $this->webspice->encrypt_decrypt($this->webspice->get_user()['COMPANY_ID'], 'decrypt');

		$this->load->database();
		$orderby = 'ORDER BY account_transaction.CREATED_DATE DESC ';
		$groupby = ' GROUP BY account_transaction.VOUCHER_NO ';
		// $where = ' WHERE account_transaction.VOUCHER_NO <> "" ';
		$where = ' WHERE account_transaction.VOUCHER_TYPE="Credit Voucher" AND account_transaction.COMPANY_ID="'.$company_id.'" ';
		if($this->webspice->admin_verify()) {
			$where = ' WHERE account_transaction.VOUCHER_TYPE="Credit Voucher" ';
		}

		$page_index = 0;
		$no_of_record = 20;
		$limit = ' LIMIT '.$no_of_record;
		$filter_by = 'Last Created';
		$data['pager'] = null;
		$criteria = $this->uri->segment(2);
		$key = $this->uri->segment(3);
		if ($criteria == 'page') {
			$page_index = (int)$key;
			$page_index < 0 ? $page_index=0 : $page_index=$page_index;
		}

		$initialSQL = "
		SELECT  * FROM account_transaction	";


		# filtering records
		if( $this->input->post('filter') ){
			$result = $this->webspice->filter_generator(
				$TableName = 'account_transaction',
				$InputField = array(),
				$Keyword = array('ACC_DATA_ID', 'DEBIT', 'CREDIT'),
				$AdditionalWhere = null,
				$DateBetween = null
			);

			$result['where'] ? $where = $result['where'] : $where=$where;
			$result['filter'] ? $filter_by = $result['filter'] : $filter_by=$filter_by;
		}

		# action area
		switch ($criteria) {
			case 'print':
			case 'csv':
				if( !isset($_SESSION['sql']) || !$_SESSION['sql'] ){
					$_SESSION['sql'] = $initialSQL . $where . $orderby;
					$_SESSION['filter_by'] = $filter_by;
				}

				$record = $this->db->query( substr($_SESSION['sql'], 0, stripos($_SESSION['sql'],'LIMIT')) );
				$data['get_record'] = $record->result();
				$data['filter_by'] = $_SESSION['filter_by'];

				$this->load->view('report/print',$data);
				return false;
				break;

			case 'edit':
				$this->webspice->edit_generator($TableName='account_transaction', $KeyField='ACC_DATA_ID', $key, $RedirectController='accounts_controller', $RedirectFunction='create_credit_voucher', $PermissionName='manage_credit_voucher', $StatusCheck=null, $Log='edit_JOURNAL');
				return false;
				break;
			case 'update':
				$id = $this->uri->segment(3);
				$id2 = $this->uri->segment(4);
				$id3 = $this->uri->segment(5);
				$data = $this->db->query($id . " " . $id2 . " " . $id3);
				if($data) { echo "Just for test purpose";}
				return false;
				break;
			case 'inactive':
				$this->webspice->action_executer($TableName='account_transaction', $KeyField='ACC_DATA_ID', $key, $RedirectURL='manage_credit_voucher', $PermissionName='manage_credit_voucher', $StatusCheck=7, $ChangeStatus=-7, $RemoveCache='account_transaction', $Log='inactive_journal');
				return false;
				break;

			case 'active':
				$this->webspice->action_executer($TableName='account_transaction', $KeyField='ACC_DATA_ID', $key, $RedirectURL='manage_credit_voucher', $PermissionName='manage_credit_voucher', $StatusCheck=-7, $ChangeStatus=7, $RemoveCache='account_transaction', $Log='active_journal');
				return false;
				break;

			case 'delete':
				$id = $this->webspice->encrypt_decrypt($key, 'decrypt');
				$sql = $this->db->query("DELETE FROM account_transaction WHERE ACC_DATA_ID='".$id."' LIMIT 1");

				$this->webspice->message_board('Voucher deleted successfully');
				$this->webspice->force_redirect($url_prefix . 'manage_credit_voucher');
				return false;
			break;

			case 'credit_voucher':
				$data = array();
				$voucher_no = $this->webspice->encrypt_decrypt($key, 'decrypt');
				$data['get_record'] = $this->db->query("SELECT * FROM account_transaction WHERE DESCRIPTION<>'' AND VOUCHER_NO=".$voucher_no)->result();
				$sql = $this->db->query("SELECT * FROM account_transaction WHERE VOUCHER_NOTE<>'' AND VOUCHER_NO=".$voucher_no)->row();
				$data['total_amt'] = $sql->ACC_AMOUNT;
				$data['voucher_note'] = $sql->VOUCHER_NOTE;
				$this->load->view("admin/accounts/view_credit_voucher", $data);
				return false;
			break;
		}

		# default
		$sql = $initialSQL . $where . $groupby . $orderby . $limit;

		# only for pager
		if( $criteria == 'page' ){
			if( !isset($_SESSION['sql']) || !$_SESSION['sql'] ){
				$sql = $sql;
			}
			$limit = sprintf("LIMIT %d, %d", $page_index, $no_of_record);		# this is to avoid SQL Injection
			$sql = substr($_SESSION['sql'], 0, strpos($_SESSION['sql'],'LIMIT'));
			$sql = $sql . $limit;
		}

		# load all records
		if( !$this->input->post('filter') ){
			$count_data = $this->db->query( substr($sql,0,strpos($sql,'LIMIT')) );
			$count_data = $count_data->result();
			$data['pager'] = $this->webspice->pager( count($count_data), $no_of_record, $page_index, $url_prefix.'manage_credit_voucher/page/', 10 );	
		}

		$_SESSION['sql'] = $sql;
		$_SESSION['filter_by'] = $filter_by;
		$result = $this->db->query($sql)->result();
		$data['get_record'] = $result;
		$data['filter_by'] = $filter_by;

		// dd($data);

		$this->load->view('admin/accounts/manage_credit_voucher', $data);

	}

	public function student_statement() {

		if($this->input->post("filter")){
			$data = array();

			$class_id = $this->input->post("class_id");
			$section_id = $this->input->post("section_id");
			$student_id = $this->input->post("student_id");
			$year = $this->input->post("year");

			// dd($student_id);


			$data['get_record'] = $this->db->query("SELECT * FROM account_transaction WHERE CLASS_ID='".$class_id."' AND SECTION_ID='".$section_id."' AND STUDENT_ID='".$student_id."'")->result();

			// dd($data);
			if(count($data['get_record'])) {
				$data['class_name'] = $this->db->query("SELECT CLASS_NAME FROM class WHERE CLASS_ID='".$class_id."'")->row()->CLASS_NAME;
				$data['section_name'] = $this->db->query("SELECT SECTION_NAME FROM section WHERE SECTION_ID='".$section_id."'")->row()->SECTION_NAME;
				$data['student_name'] = $this->db->query("SELECT NAME FROM student_info WHERE STUDENT_ID='".$student_id."'")->row()->NAME;
			}else{
				$data = array();
			}

			// dd($data);

			$this->load->view('admin/accounts/student_statement', $data);
			return false;
		}
		else {
			$data = array();
			$this->load->view('admin/accounts/student_statement', $data);
		}

	}

	public function teacher_statement() {

		if($this->input->post("filter")){
			$data = array();
			$teacher_id = $this->input->post("teacher_id");
			$year = $this->input->post("year");

			// dd($teacher_id);


			$data['get_record'] = $this->db->query("SELECT * FROM account_transaction WHERE TEACHER_ID='".$teacher_id."'")->result();

			// dd($data);
			if(count($data['get_record'])) {
				$data['teacher_name'] = $this->db->query("SELECT TEACHER_NAME FROM teacher WHERE TEACHER_ID='".$teacher_id."'")->row()->TEACHER_NAME;
			}else{
				$data = array();
			}

			// dd($teacher_id);

			$this->load->view('admin/accounts/teacher_statement', $data);
			return false;
		}
		else {
			$data = array();
			$this->load->view('admin/accounts/teacher_statement', $data);
		}

	}

	public function ledger() {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'ledger');
		$this->webspice->permission_verify('ledger');
		$company_id = $this->webspice->encrypt_decrypt($this->webspice->get_user()['COMPANY_ID'], 'decrypt');

		if($this->input->post("filter")) {
			$data = array();

			$head_name = $this->input->post("head_name");
			$from_date = $this->input->post("from_date");
			$to_date = $this->input->post("to_date");

			if($this->webspice->admin_verify()) {
				if($this->input->post("company_id")) {
					$company_id = $this->input->post("company_id");
				}
				else {
					$company_id = null;
				}
			}

			$errors = array();
			if( empty($head_name) ) {
				$errors[] = "Head name is required";
			}
			if( empty($this->input->post("from_date")) ) {
				$errors[] = "From date is required";
			}
			if( empty($this->input->post("to_date")) ) {
				$errors[] = "To date is required";
			}
			if(strtotime($from_date) > strtotime($to_date)) {
				$errors[] = "From date can not be greater then to date";
			}

			if(count($errors)) {
				// dd($errors);
				$data['errors'] = $errors;
				$this->load->view("admin/accounts/ledger", $data);
				return false;
			}

			$from_date = date("Y-m-d", strtotime($from_date));
			$to_date = date("Y-m-d", strtotime($to_date));
			$head_code = $this->webspice->account_head_by_id($head_name);
			// dd($head_code);

			if($this->webspice->admin_verify() && $this->input->post("company_id")) {
				$data['get_record'] = $this->db->query("SELECT * FROM general_ledger WHERE COMPANY_ID='".$company_id."' AND TRANSACTION_ID IN (SELECT TRANSACTION_ID FROM general_ledger WHERE ACC_CODE='".$head_code."') AND ACC_CODE NOT IN ('".$head_code."') AND CREATED_DATE BETWEEN '".$from_date."' AND '".$to_date."'")->result();

			}
			else if($this->webspice->admin_verify() && !$this->input->post("company_id")) {
				$data['get_record'] = $this->db->query("SELECT * FROM general_ledger WHERE TRANSACTION_ID IN (SELECT TRANSACTION_ID FROM general_ledger WHERE ACC_CODE='".$head_code."') AND ACC_CODE NOT IN ('".$head_code."') AND CREATED_DATE BETWEEN '".$from_date."' AND '".$to_date."'")->result();
			}
			else {
				$data['get_record'] = $this->db->query("SELECT * FROM general_ledger WHERE COMPANY_ID='".$company_id."' AND TRANSACTION_ID IN (SELECT TRANSACTION_ID FROM general_ledger WHERE ACC_CODE='".$head_code."') AND ACC_CODE NOT IN ('".$head_code."') AND CREATED_DATE BETWEEN '".$from_date."' AND '".$to_date."'")->result();
			}

			// dd($data);
			if(count($data['get_record'])) {
				$data['date_from'] = $from_date;
				$data['date_to'] = $to_date;
				$data['head_name'] = $head_name;
				$data['acc_code'] = $head_code;
				$data['company_id'] = $company_id;
			}else{
				$errors[] = "Sorry, no data found";
				$data['errors'] = $errors;
				$data['date_from'] = null;
				$data['date_to'] = null;
				$data['head_name'] = null;
				$data['company_id'] = null;
				// $this->load->view("admin/accounts/ledger", $data);
				// return false;
			}

			// dd($data);

			$this->load->view('admin/accounts/ledger', $data);
			return false;
		}
		else {
			$data = array();
			$this->load->view('admin/accounts/ledger', $data);
		}

	}

	public function trial_balance() {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'trial_balance');
		$this->webspice->permission_verify('trial_balance');
		$company_id = $this->webspice->encrypt_decrypt($this->webspice->get_user()['COMPANY_ID'], 'decrypt');

		if($this->input->post("filter")) {
			$data = array();
			$from_date = $this->input->post("from_date");
			$to_date = $this->input->post("to_date");

			if($this->webspice->admin_verify()) {
				if($this->input->post("company_id")) {
					$company_id = $this->input->post("company_id");
				}
				else {
					$company_id = null;
				}
			}

			$errors = array();
			if( empty($this->input->post("from_date")) ) {
				$errors[] = "From date is required";
			}
			if( empty($this->input->post("to_date")) ) {
				$errors[] = "To date is required";
			}
			if(strtotime($from_date) > strtotime($to_date)) {
				$errors[] = "From date can not be greater then to date";
			}

			if(count($errors)) {
				// dd($errors);
				$data['errors'] = $errors;
				$this->load->view("admin/accounts/trial_balance", $data);
				return false;
			}

			$from_date = date("Y-m-d", strtotime($from_date));
			$to_date = date("Y-m-d", strtotime($to_date));


			// die("Hello World");

			$data['get_record'] = $this->db->query("SELECT * FROM general_ledger WHERE CREATED_DATE BETWEEN '".$from_date."' AND '".$to_date."'")->result();

			if($this->webspice->admin_verify() && $this->input->post("company_id")) {
				$data['get_record'] = $this->db->query("SELECT * FROM general_ledger WHERE COMPANY_ID='".$company_id."' AND CREATED_DATE BETWEEN '".$from_date."' AND '".$to_date."'")->result();
			}
			else if($this->webspice->admin_verify() && !$this->input->post("company_id")) {
				$data['get_record'] = $this->db->query("SELECT * FROM general_ledger WHERE CREATED_DATE BETWEEN '".$from_date."' AND '".$to_date."'")->result();
			}
			else {
				$data['get_record'] = $this->db->query("SELECT * FROM general_ledger WHERE COMPANY_ID='".$company_id."' AND CREATED_DATE BETWEEN '".$from_date."' AND '".$to_date."'")->result();
			}

			// dd($data);
			if(count($data['get_record'])) {
				$data['date_from'] = $from_date;
				$data['date_to'] = $to_date;
				$data['company_id'] = $company_id;
			}else{
				$errors[] = "Sorry, no data found";
				$data['errors'] = $errors;
				$data['date_from'] = null;
				$data['date_to'] = null;
				$data['company_id'] = null;
				// $this->load->view("admin/accounts/ledger", $data);
				// return false;
			}

			// dd($data);

			$this->load->view('admin/accounts/trial_balance', $data);
			return false;
		}
		else {
			$data = array();
			$this->load->view('admin/accounts/trial_balance', $data);
		}

	}

	public function journal() {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'journal');
		$this->webspice->permission_verify('journal');
		$company_id = $this->webspice->encrypt_decrypt($this->webspice->get_user()['COMPANY_ID'], 'decrypt');

		if($this->input->post("filter")){
			$data = array();
			$from_date = $this->input->post("from_date");
			$to_date = $this->input->post("to_date");

			if($this->webspice->admin_verify()) {
				if($this->input->post("company_id")) {
					$company_id = $this->input->post("company_id");
				}
				else {
					$company_id = null;
				}
			}

			$errors = array();
			if( empty($this->input->post("from_date")) ) {
				$errors[] = "From date is required";
			}
			if( empty($this->input->post("to_date")) ) {
				$errors[] = "To date is required";
			}
			if(strtotime($from_date) > strtotime($to_date)) {
				$errors[] = "From date can not be greater then to date";
			}

			if(count($errors)) {
				$data['errors'] = $errors;
				$this->load->view("admin/accounts/journal", $data);
				return false;
			}

			$from_date = date("Y-m-d", strtotime($from_date));
			$to_date = date("Y-m-d", strtotime($to_date));

			if($this->webspice->admin_verify() && $this->input->post("company_id")) {
				$data['get_record'] = $this->db->query("SELECT * FROM account_transaction WHERE COMPANY_ID='".$company_id."' AND ACC_DATE BETWEEN '".$from_date."' AND '".$to_date."' GROUP BY ACC_DATE ORDER BY ACC_DATE ASC ")->result();
			}
			else if($this->webspice->admin_verify() && !$this->input->post("company_id")) {
				$data['get_record'] = $this->db->query("SELECT * FROM account_transaction WHERE ACC_DATE BETWEEN '".$from_date."' AND '".$to_date."' GROUP BY ACC_DATE ORDER BY ACC_DATE ASC ")->result();
			}
			else {
				$data['get_record'] = $this->db->query("SELECT * FROM account_transaction WHERE COMPANY_ID='".$company_id."' AND ACC_DATE BETWEEN '".$from_date."' AND '".$to_date."' GROUP BY ACC_DATE ORDER BY ACC_DATE ASC ")->result();
			}

			// dd($data);

			if(count($data['get_record'])) {
				$data['from_date'] = $from_date;
				$data['company_id'] = $company_id;
				$data['to_date'] = $to_date;
			}else{
				$errors[] = "Sorry, no data found";
				$data['errors'] = $errors;
				$data['from_date'] = null;
				$data['to_date'] = null;
				$data['company_id'] = null;
			}


			$this->load->view('admin/accounts/journal', $data);
			return false;
		}
		else {
			$data = array();
			$this->load->view('admin/accounts/journal', $data);
		}

	}

	public function balance_sheet() {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'balance_sheet');
		$this->webspice->permission_verify('balance_sheet');
		$company_id = $this->webspice->encrypt_decrypt($this->webspice->get_user()['COMPANY_ID'], 'decrypt');

		if($this->input->post("filter")) {
			$data = array();

			$from_date = $this->input->post("from_date");
			$to_date = $this->input->post("to_date");

			if($this->webspice->admin_verify()) {
				if($this->input->post("company_id")) {
					$company_id = $this->input->post("company_id");
				}
				else {
					$company_id = null;
				}
			}

			$errors = array();
			if( empty($this->input->post("from_date")) ) {
				$errors[] = "From date is required";
			}
			if( empty($this->input->post("to_date")) ) {
				$errors[] = "To date is required";
			}
			if(strtotime($from_date) > strtotime($to_date)) {
				$errors[] = "From date can not be greater then to date";
			}

			if(count($errors)) {
				// dd($errors);
				$data['errors'] = $errors;
				$this->load->view("admin/accounts/balance_sheet", $data);
				return false;
			}

			$from_date = date("Y-m-d", strtotime($from_date));
			$to_date = date("Y-m-d", strtotime($to_date));


			// die("Hello World");

			// $get_record = $this->db->query("SELECT * FROM general_ledger WHERE HEAD_TYPE='bs' AND CREATED_DATE BETWEEN '".$from_date."' AND '".$to_date."'")->result();

			if($this->webspice->admin_verify() && $this->input->post("company_id")) {
				$get_record = $this->db->query("SELECT an.* FROM account_name AS an INNER JOIN general_ledger AS gl ON gl.ACC_CODE=an.CODE WHERE gl.HEAD_TYPE='bs' AND gl.COMPANY_ID='".$company_id."'")->result();
			}
			else if($this->webspice->admin_verify() && !$this->input->post("company_id")) {
				$get_record = $this->db->query("SELECT an.* FROM account_name AS an INNER JOIN general_ledger AS gl ON gl.ACC_CODE=an.CODE WHERE gl.HEAD_TYPE='bs'")->result();
			}
			else {
				$get_record = $this->db->query("SELECT an.* FROM account_name AS an INNER JOIN general_ledger AS gl ON gl.ACC_CODE=an.CODE WHERE gl.HEAD_TYPE='bs' AND gl.COMPANY_ID='".$company_id."'")->result();
			}

			$uniq_code = array();
			foreach($get_record as $val) {
				$uniq_code[] = $val->CODE;
			}
			$data['get_record'] = array_unique($uniq_code);
			if(count($data['get_record'])) {
				$data['date_from'] = $from_date;
				$data['date_to'] = $to_date;
				$data['company_id'] = $company_id;
			}else{
				$errors[] = "Sorry, no data found";
				$data['errors'] = $errors;
				$data['date_from'] = null;
				$data['date_to'] = null;
				$data['company_id'] = null;
			}

			// dd($data);

			$this->load->view('admin/accounts/balance_sheet', $data);
			return false;
		}
		else {
			$data = array();
			$this->load->view('admin/accounts/balance_sheet', $data);
		}

	}

}