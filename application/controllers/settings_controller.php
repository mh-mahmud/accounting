<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings_controller extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
	}

	public function create_company($data=null) {
		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'create_company');
		$this->webspice->permission_verify('create_company');
		if( !isset($data['edit']) ){
			$data['edit'] = array(
				'COMPANY_ID'=>null,
				'COMPANY_NAME'=>null,
				'COMPANY_DESCRIPTION'=>null,
				'COMPANY_PHONE'=>null,
				'COMPANY_EMAIL'=>null,
				'COMPANY_ADDRESS'=>null
			);
		}
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('company_name', 'company name','required|trim|xss_clean');
		$this->form_validation->set_rules('company_description', 'company description','trim|xss_clean');
		$this->form_validation->set_rules('company_phone', 'company phone','trim|xss_clean');
		if(count($data['edit']['COMPANY_EMAIL'])) {
			$this->form_validation->set_rules('company_email', 'company email','valid_email|trim|xss_clean');
		}
		else {
			$this->form_validation->set_rules('company_email', 'company email','valid_email|is_unique[company.COMPANY_EMAIL]|trim|xss_clean');
		}
		
		$this->form_validation->set_rules('company_address', 'company address','trim|xss_clean');
		
		if( !$this->form_validation->run() ){
			$this->load->view('admin/company/create_company', $data);
			return FALSE;
		}

		# get input post
		$input = $this->webspice->get_input('company_id');

		//dd($input);
		//die();

		#duplicate test
		$this->webspice->db_field_duplicate_test("SELECT * FROM company WHERE COMPANY_NAME=?", array( $input->company_name), 'You are not allowed to enter duplicate company name', 'COMPANY_ID', $input->company_id, $data, 'admin/company/create_company');
		
		# remove cache
		$this->webspice->remove_cache('company');

		# update process
		if( $input->company_id ){

			$sql = "
			UPDATE company SET COMPANY_NAME=?, COMPANY_ADDRESS=?, COMPANY_EMAIL=?, COMPANY_PHONE=?, COMPANY_ADDRESS=?, COMPANY_DESCRIPTION=?, UPDATED_BY=?,UPDATED_DATE=?
			WHERE COMPANY_ID=?";
			$this->db->query($sql, array($input->company_name, $input->company_address, $input->company_email, $input->company_phone, $input->company_address, $input->company_description, $this->webspice->get_user_id(),$this->webspice->now(), $input->company_id));
			$this->webspice->message_board('Record has been updated!');
			$this->webspice->log_me('company_updated - '.$this->webspice->get_user_id()); # log activities
			$this->webspice->force_redirect($url_prefix.'manage_company');
			return false;
		}
		
		#insert category

		$sql = "
		INSERT INTO company
		(COMPANY_NAME, COMPANY_DESCRIPTION, COMPANY_PHONE, COMPANY_EMAIL, COMPANY_ADDRESS, CREATED_BY, CREATED_DATE, STATUS)
		VALUES
		( ?, ?, ?, ?, ?, ?, ?, 7)";
		$this->db->query($sql, array(
				$input->company_name,
				$input->company_description,
				$input->company_phone,
				$input->company_email,
				$input->company_address,
				$this->webspice->get_user_id(),
				$this->webspice->now()
			)
		);

		if( !$this->db->insert_id() ){
			$this->webspice->message_board('We could not execute your request. Please tray again later or report to authority.');
			$this->webspice->force_redirect($url_prefix . 'admin');
			return false;
		}

		$this->webspice->message_board('Company inserted successfully!');
		if($this->webspice->permission_verify('manage_company',TRUE)){
			$this->webspice->force_redirect($url_prefix . 'manage_company');
			return FALSE;
		}
		$this->webspice->force_redirect($url_prefix.'create_company');

	}

	public function manage_company() {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'manage_company');
		$this->webspice->permission_verify('manage_company');

		$this->load->database();
		$orderby = 'ORDER BY company.COMPANY_NAME ASC';
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
		SELECT  * FROM company ";


		# filtering records
		if( $this->input->post('filter') ){
			$result = $this->webspice->filter_generator(
				$TableName = 'company',
				$InputField = array(),
				$Keyword = array('COMPANY_NAME'),
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



				$this->load->view('admin/print_report/settings/print_class',$data);
				return false;
				break;

			case 'pdf':
				if( !isset($_SESSION['sql']) || !$_SESSION['sql'] ){
					$_SESSION['sql'] = $initialSQL . $where . $orderby;
					$_SESSION['filter_by'] = $filter_by;
				}

				$record = $this->db->query( substr($_SESSION['sql'], 0, stripos($_SESSION['sql'],'LIMIT')) );
				$data['get_record'] = $record->result();
				$data['filter_by'] = $_SESSION['filter_by'];
				// dd($data);
				$rand = substr( md5(rand()), 0, 15);
				// dd($rand);
				$pdfFilePath = "$rand.pdf";
				$html = $this->load->view('admin/print_report/settings/print_class', $data, true);
				$this->load->library('pdf');
				$pdf = $this->pdf->load();
				$pdf->SetFooter($_SERVER['HTTP_HOST'].'|{PAGENO}|'.date(DATE_RFC822));
				$pdf->WriteHTML($html); // write the HTML into the PDF
				$pdf->Output($pdfFilePath, 'D');
				// dd($pdfFilePath);

				return false;
			break;

			case 'edit':
				$this->webspice->edit_generator($TableName='company', $KeyField='COMPANY_ID', $key, $RedirectController='Settings_controller', $RedirectFunction='create_company', $PermissionName='manage_company', $StatusCheck=null, $Log='edit_company');
				return false;
				break;

			case 'inactive':
				$this->webspice->action_executer($TableName='company', $KeyField='COMPANY_ID', $key, $RedirectURL='manage_company', $PermissionName='manage_company', $StatusCheck=7, $ChangeStatus=-7, $RemoveCache='company', $Log='inactive_company');
				return false;
				break;

			case 'active':
				$this->webspice->action_executer($TableName='company', $KeyField='COMPANY_ID', $key, $RedirectURL='manage_company', $PermissionName='manage_company', $StatusCheck=-7, $ChangeStatus=7, $RemoveCache='company', $Log='active_company');
				return false;
				break;

			case 'delete':
				$id = $this->webspice->encrypt_decrypt($key, 'decrypt');
				$sql = $this->db->query("DELETE FROM company WHERE COMPANY_ID='".$id."' LIMIT 1");
				if($sql) {
					$this->webspice->force_redirect($url_prefix.'manage_company');
				}
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
			$data['pager'] = $this->webspice->pager( count($count_data), $no_of_record, $page_index, $url_prefix.'manage_company/page/', 10 );	
		}

		$_SESSION['sql'] = $sql;
		$_SESSION['filter_by'] = $filter_by;
		$result = $this->db->query($sql)->result();

		$data['get_record'] = $result;
		$data['filter_by'] = $filter_by;

		$this->load->view('admin/company/manage_company', $data);

	}

	public function create_section($data=null) {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'create_section');
		$this->webspice->permission_verify('create_section');
		if( !isset($data['edit']) ){
			$data['edit'] = array(
				'SECTION_ID'=>null,
				'SECTION_NAME'=>null,
				'CLASS_ID'=>null
			);
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('section_name','section name','required|trim|xss_clean|max_length[200]');
		
		if( !$this->form_validation->run() ){
			$this->load->view('admin/settings/create_section', $data);
			return FALSE;
		}

		# get input post
		$input = $this->webspice->get_input('section_id');
		
		#duplicate test
		$this->webspice->db_field_duplicate_test("SELECT * FROM section WHERE SECTION_NAME=? AND CLASS_ID=?", array( $input->section_name, $input->class_id), 'You are not allowed to enter duplicate section name', 'SECTION_ID', $input->section_id, $data, 'admin/settings/create_section');
		
		# remove cache
		$this->webspice->remove_cache('section');

		# update process
		if( $input->section_id ){

			$sql = "
			UPDATE section SET SECTION_NAME=?, CLASS_ID=?, UPDATED_BY=?,UPDATED_DATE=?
			WHERE SECTION_ID=?";
			$this->db->query($sql, array($input->section_name, $input->class_id, $this->webspice->get_user_id(),$this->webspice->now(), $input->section_id));
			$this->webspice->message_board('Record has been updated!');
			$this->webspice->log_me('section_updated - '.$this->webspice->get_user_id()); # log activities
			$this->webspice->force_redirect($url_prefix.'manage_section');
			return false;
		}
		
		#insert section

		$sql = "
		INSERT INTO section
		(SECTION_NAME, CLASS_ID, CREATED_BY,CREATED_DATE,STATUS)
		VALUES
		( ?, ?, ?, ?, 7)";
		$this->db->query($sql, array($input->section_name,$input->class_id,
			$this->webspice->get_user_id(),$this->webspice->now()));

		if( !$this->db->insert_id() ){
			$this->webspice->message_board('We could not execute your request. Please tray again later or report to authority.');
			$this->webspice->force_redirect($url_prefix . 'admin');
			return false;
		}

		$this->webspice->message_board('Record inserted successfully!');
		if($this->webspice->permission_verify('manage_section',TRUE)){
			$this->webspice->force_redirect($url_prefix . 'manage_section');
			return FALSE;
		}
		$this->webspice->force_redirect($url_prefix.'create_section');

	}

	public function manage_section() {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'manage_section');
		$this->webspice->permission_verify('manage_section');

		$this->load->database();
		$orderby = 'ORDER BY section.SECTION_NAME ASC';
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
		SELECT  * FROM section ";


		# filtering records
		if( $this->input->post('filter') ){
			$result = $this->webspice->filter_generator(
				$TableName = 'section',
				$InputField = array(),
				$Keyword = array('SECTION_NAME'),
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



				$this->load->view('admin/print_report/settings/print_section',$data);
				return false;
				break;

			case 'pdf':
				if( !isset($_SESSION['sql']) || !$_SESSION['sql'] ){
					$_SESSION['sql'] = $initialSQL . $where . $orderby;
					$_SESSION['filter_by'] = $filter_by;
				}

				$record = $this->db->query( substr($_SESSION['sql'], 0, stripos($_SESSION['sql'],'LIMIT')) );
				$data['get_record'] = $record->result();
				$data['filter_by'] = $_SESSION['filter_by'];
				// dd($data);
				$rand = substr( md5(rand()), 0, 15);
				// dd($rand);
				$pdfFilePath = "$rand.pdf";
				$html = $this->load->view('admin/print_report/settings/print_section', $data, true);
				$this->load->library('pdf');
				$pdf = $this->pdf->load();
				$pdf->SetFooter($_SERVER['HTTP_HOST'].'|{PAGENO}|'.date(DATE_RFC822));
				$pdf->WriteHTML($html); // write the HTML into the PDF
				$pdf->Output($pdfFilePath, 'D');
				// dd($pdfFilePath);

				return false;
			break;

			case 'edit':
				$this->webspice->edit_generator($TableName='section', $KeyField='SECTION_ID', $key, $RedirectController='Settings_controller', $RedirectFunction='create_section', $PermissionName='manage_section', $StatusCheck=null, $Log='edit_section');
				return false;
				break;

			case 'inactive':
				$this->webspice->action_executer($TableName='section', $KeyField='SECTION_ID', $key, $RedirectURL='manage_section', $PermissionName='manage_section', $StatusCheck=7, $ChangeStatus=-7, $RemoveCache='section', $Log='inactive_section');
				return false;
				break;

			case 'active':
				$this->webspice->action_executer($TableName='section', $KeyField='SECTION_ID', $key, $RedirectURL='manage_section', $PermissionName='manage_section', $StatusCheck=-7, $ChangeStatus=7, $RemoveCache='section', $Log='active_section');
				return false;
				break;

			case 'delete':
				$id = $this->webspice->encrypt_decrypt($key, 'decrypt');
				$sql = $this->db->query("DELETE FROM section WHERE SECTION_ID='".$id."' LIMIT 1");
				if($sql) {
					$this->webspice->force_redirect($url_prefix.'manage_section');
				}
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
			$data['pager'] = $this->webspice->pager( count($count_data), $no_of_record, $page_index, $url_prefix.'manage_section/page/', 10 );	
		}

		$_SESSION['sql'] = $sql;
		$_SESSION['filter_by'] = $filter_by;
		$result = $this->db->query($sql)->result();

		$data['get_record'] = $result;
		$data['filter_by'] = $filter_by;

		$this->load->view('admin/settings/manage_section', $data);

	}

	public function create_designation($data=null) {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'create_designation');
		$this->webspice->permission_verify('create_designation');
		if( !isset($data['edit']) ){
			$data['edit'] = array(
				'DESIGNATION_ID'=>null,
				'DESIGNATION_NAME'=>null
			);
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('designation_name','designation name','required|trim|xss_clean|max_length[200]');
		
		if( !$this->form_validation->run() ){
			$this->load->view('admin/settings/create_designation', $data);
			return FALSE;
		}

		# get input post
		$input = $this->webspice->get_input('designation_id');
		
		#duplicate test
		$this->webspice->db_field_duplicate_test("SELECT * FROM designation WHERE DESIGNATION_NAME=?", array( $input->designation_name), 'You are not allowed to enter duplicate designation name', 'DESIGNATION_ID', $input->designation_id, $data, 'admin/create_designation');
		
		# remove cache
		$this->webspice->remove_cache('designation');

		# update process
		if( $input->designation_id ){

			$sql = "
			UPDATE designation SET DESIGNATION_NAME=?, UPDATED_BY=?,UPDATED_DATE=?
			WHERE DESIGNATION_ID=?";
			$this->db->query($sql, array($input->designation_name,$this->webspice->get_user_id(),$this->webspice->now(), $input->designation_id));
			$this->webspice->message_board('Record has been updated!');
			$this->webspice->log_me('section_updated - '.$this->webspice->get_user_id()); # log activities
			$this->webspice->force_redirect($url_prefix.'manage_designation');
			return false;
		}
		
		#insert designation

		$sql = "
		INSERT INTO designation
		(DESIGNATION_NAME, CREATED_BY,CREATED_DATE,STATUS)
		VALUES
		( ?, ?, ?, 7)";
		$this->db->query($sql, array($input->designation_name,
			$this->webspice->get_user_id(),$this->webspice->now()));

		if( !$this->db->insert_id() ){
			$this->webspice->message_board('We could not execute your request. Please tray again later or report to authority.');
			$this->webspice->force_redirect($url_prefix . 'admin');
			return false;
		}

		$this->webspice->message_board('Record inserted successfully!');
		if($this->webspice->permission_verify('manage_designation',TRUE)){
			$this->webspice->force_redirect($url_prefix . 'manage_designation');
			return FALSE;
		}
		$this->webspice->force_redirect($url_prefix.'create_designation');

	}

	public function manage_designation() {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'manage_designation');
		$this->webspice->permission_verify('manage_designation');

		$this->load->database();
		$orderby = 'ORDER BY designation.DESIGNATION_NAME ASC';
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
		SELECT  * FROM designation ";


		# filtering records
		if( $this->input->post('filter') ){
			$result = $this->webspice->filter_generator(
				$TableName = 'designation',
				$InputField = array(),
				$Keyword = array('DESIGNATION_NAME'),
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



				$this->load->view('admin/print_report/settings/print_designation',$data);
				return false;
				break;

			case 'pdf':
				if( !isset($_SESSION['sql']) || !$_SESSION['sql'] ){
					$_SESSION['sql'] = $initialSQL . $where . $orderby;
					$_SESSION['filter_by'] = $filter_by;
				}

				$record = $this->db->query( substr($_SESSION['sql'], 0, stripos($_SESSION['sql'],'LIMIT')) );
				$data['get_record'] = $record->result();
				$data['filter_by'] = $_SESSION['filter_by'];
				// dd($data);
				$rand = substr( md5(rand()), 0, 15);
				// dd($rand);
				$pdfFilePath = "$rand.pdf";
				$html = $this->load->view('admin/print_report/settings/print_designation', $data, true);
				$this->load->library('pdf');
				$pdf = $this->pdf->load();
				$pdf->SetFooter($_SERVER['HTTP_HOST'].'|{PAGENO}|'.date(DATE_RFC822));
				$pdf->WriteHTML($html); // write the HTML into the PDF
				$pdf->Output($pdfFilePath, 'D');
				// dd($pdfFilePath);

				return false;
			break;

			case 'edit':
				$this->webspice->edit_generator($TableName='designation', $KeyField='DESIGNATION_ID', $key, $RedirectController='Settings_controller', $RedirectFunction='create_designation', $PermissionName='manage_designation', $StatusCheck=null, $Log='edit_designation');
				return false;
				break;

			case 'inactive':
				$this->webspice->action_executer($TableName='designation', $KeyField='DESIGNATION_ID', $key, $RedirectURL='manage_designation', $PermissionName='manage_designation', $StatusCheck=7, $ChangeStatus=-7, $RemoveCache='designation', $Log='inactive_section');
				return false;
				break;

			case 'active':
				$this->webspice->action_executer($TableName='designation', $KeyField='DESIGNATION_ID', $key, $RedirectURL='manage_designation', $PermissionName='manage_designation', $StatusCheck=-7, $ChangeStatus=7, $RemoveCache='designation', $Log='active_section');
				return false;
				break;

			case 'delete':
				$id = $this->webspice->encrypt_decrypt($key, 'decrypt');
				$sql = $this->db->query("DELETE FROM designation WHERE DESIGNATION_ID='".$id."' LIMIT 1");
				if($sql) {
					$this->webspice->force_redirect($url_prefix.'manage_designation');
				}
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
			$data['pager'] = $this->webspice->pager( count($count_data), $no_of_record, $page_index, $url_prefix.'manage_designation/page/', 10 );	
		}

		$_SESSION['sql'] = $sql;
		$_SESSION['filter_by'] = $filter_by;
		$result = $this->db->query($sql)->result();

		$data['get_record'] = $result;
		$data['filter_by'] = $filter_by;

		$this->load->view('admin/settings/manage_designation', $data);

	}

	public function create_notice($data=null) {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'create_notice');
		$this->webspice->permission_verify('create_notice');
		if( !isset($data['edit']) ){
			$data['edit'] = array(
				'NOTICE_ID'=>null,
				'NOTICE_TITLE'=>null,
				'NOTICE_DETAILS'=>null
			);
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('notice_title','Notice Title','required|trim|xss_clean|max_length[400]');
		$this->form_validation->set_rules('notice_details','Notice Details','required|trim|xss_clean|max_length[200]');
		
		if( !$this->form_validation->run() ){
			$this->load->view('admin/settings/create_notice', $data);
			return FALSE;
		}

		# get input post
		$input = $this->webspice->get_input('notice_id');
		
		#duplicate test
		$this->webspice->db_field_duplicate_test("SELECT * FROM noticeboard WHERE NOTICE_TITLE=?", array( $input->notice_title), 'You are not allowed to enter duplicate notice name', 'NOTICE_ID', $input->notice_id, $data, 'admin/create_notice');
		
		# remove cache
		$this->webspice->remove_cache('noticeboard');

		# update process
		if( $input->notice_id ){

			$sql = "
			UPDATE noticeboard SET NOTICE_TITLE=?, NOTICE_DETAILS=?, UPDATED_BY=?,UPDATED_DATE=?
			WHERE NOTICE_ID=?";
			$this->db->query($sql, array($input->notice_title, $input->notice_details, $this->webspice->get_user_id(),$this->webspice->now(), $input->notice_id));
			$this->webspice->message_board('Record has been updated!');
			$this->webspice->log_me('notice_updated - '.$this->webspice->get_user_id()); # log activities
			$this->webspice->force_redirect($url_prefix.'manage_notice');
			return false;
		}
		
		#insert noticeboard

		$sql = "
		INSERT INTO noticeboard
		(NOTICE_TITLE, NOTICE_DETAILS, CREATED_BY,CREATED_DATE,STATUS)
		VALUES
		( ?, ?, ?, ?, 7)";
		$this->db->query($sql, array($input->notice_title, $input->notice_details,
			$this->webspice->get_user_id(),$this->webspice->now()));

		if( !$this->db->insert_id() ){
			$this->webspice->message_board('We could not execute your request. Please tray again later or report to authority.');
			$this->webspice->force_redirect($url_prefix . 'admin');
			return false;
		}

		$this->webspice->message_board('Record inserted successfully!');
		if($this->webspice->permission_verify('manage_notice',TRUE)){
			$this->webspice->force_redirect($url_prefix . 'manage_notice');
			return FALSE;
		}
		$this->webspice->force_redirect($url_prefix.'create_notice');

	}

	public function manage_notice() {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'manage_notice');
		$this->webspice->permission_verify('manage_notice');

		$this->load->database();
		$orderby = 'ORDER BY noticeboard.NOTICE_TITLE ASC';
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
		SELECT  * FROM noticeboard ";


		# filtering records
		if( $this->input->post('filter') ){
			$result = $this->webspice->filter_generator(
				$TableName = 'noticeboard',
				$InputField = array(),
				$Keyword = array('NOTICE_TITLE'),
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



				$this->load->view('admin/print_report/settings/print_notice',$data);
				return false;
				break;

			case 'pdf':
				if( !isset($_SESSION['sql']) || !$_SESSION['sql'] ){
					$_SESSION['sql'] = $initialSQL . $where . $orderby;
					$_SESSION['filter_by'] = $filter_by;
				}

				$record = $this->db->query( substr($_SESSION['sql'], 0, stripos($_SESSION['sql'],'LIMIT')) );
				$data['get_record'] = $record->result();
				$data['filter_by'] = $_SESSION['filter_by'];
				// dd($data);
				$rand = substr( md5(rand()), 0, 15);
				// dd($rand);
				$pdfFilePath = "$rand.pdf";
				$html = $this->load->view('admin/print_report/settings/print_notice', $data, true);
				$this->load->library('pdf');
				$pdf = $this->pdf->load();
				$pdf->SetFooter($_SERVER['HTTP_HOST'].'|{PAGENO}|'.date(DATE_RFC822));
				$pdf->WriteHTML($html); // write the HTML into the PDF
				$pdf->Output($pdfFilePath, 'D');
				// dd($pdfFilePath);

				return false;
			break;

			case 'edit':
				$this->webspice->edit_generator($TableName='noticeboard', $KeyField='NOTICE_ID', $key, $RedirectController='Settings_controller', $RedirectFunction='create_notice', $PermissionName='manage_notice', $StatusCheck=null, $Log='edit_notice');
				return false;
				break;

			case 'inactive':
				$this->webspice->action_executer($TableName='noticeboard', $KeyField='NOTICE_ID', $key, $RedirectURL='manage_notice', $PermissionName='manage_notice', $StatusCheck=7, $ChangeStatus=-7, $RemoveCache='noticeboard', $Log='inactive_class');
				return false;
				break;

			case 'active':
				$this->webspice->action_executer($TableName='noticeboard', $KeyField='NOTICE_ID', $key, $RedirectURL='manage_notice', $PermissionName='manage_notice', $StatusCheck=-7, $ChangeStatus=7, $RemoveCache='noticeboard', $Log='active_class');
				return false;
				break;

			case 'delete':
				$id = $this->webspice->encrypt_decrypt($key, 'decrypt');
				$sql = $this->db->query("DELETE FROM noticeboard WHERE NOTICE_ID='".$id."' LIMIT 1");
				if($sql) {
					$this->webspice->force_redirect($url_prefix.'manage_notice');
				}
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
			$data['pager'] = $this->webspice->pager( count($count_data), $no_of_record, $page_index, $url_prefix.'manage_notice/page/', 10 );	
		}

		$_SESSION['sql'] = $sql;
		$_SESSION['filter_by'] = $filter_by;
		$result = $this->db->query($sql)->result();

		$data['get_record'] = $result;
		$data['filter_by'] = $filter_by;

		$this->load->view('admin/settings/manage_notice', $data);

	}


	// start

	public function create_subject($data=null) {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'create_subject');
		$this->webspice->permission_verify('create_subject');
		if( !isset($data['edit']) ){
			$data['edit'] = array(
				'SUBJECT_ID'=>null,
				'SUBJECT_NAME'=>null,
				'CLASS_ID'=>null,
				'OPTIONAL'=>null
			);
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('subject_name','Subject name','required|trim|xss_clean|max_length[200]');
		$this->form_validation->set_rules('class_id','class name','required|trim|xss_clean');
		
		if( !$this->form_validation->run() ){
			$this->load->view('admin/settings/create_subject', $data);
			return FALSE;
		}

		# get input post
		$input = $this->webspice->get_input('subject_id');
		// dd($input);
		
		#duplicate test
		$this->webspice->db_field_duplicate_test("SELECT * FROM subject WHERE SUBJECT_NAME=? AND CLASS_ID=?", array( $input->subject_name, $input->class_id), 'You are not allowed to enter duplicate subject name', 'SUBJECT_ID', $input->subject_id, $data, 'admin/settings/create_subject');
		
		# remove cache
		$this->webspice->remove_cache('subject');

		$optional = isset($input->optional) ? 1 : 0;

		# update process
		if( $input->subject_id ){

			$sql = "
			UPDATE subject SET SUBJECT_NAME=?, CLASS_ID=?, OPTIONAL=?, UPDATED_BY=?,UPDATED_DATE=?
			WHERE SUBJECT_ID=?";
			$this->db->query($sql, array($input->subject_name, $input->class_id, $optional, $this->webspice->get_user_id(),$this->webspice->now(), $input->subject_id));
			$this->webspice->message_board('Record has been updated!');
			$this->webspice->log_me('subject_updated - '.$this->webspice->get_user_id()); # log activities
			$this->webspice->force_redirect($url_prefix.'manage_subject');
			return false;
		}
		
		#insert subject

		$sql = "
		INSERT INTO subject
		(SUBJECT_NAME, CLASS_ID, OPTIONAL, CREATED_BY,CREATED_DATE,STATUS)
		VALUES
		( ?, ?, ?, ?, ?, 7)";
		$this->db->query($sql, array($input->subject_name,$input->class_id, $optional, $this->webspice->get_user_id(),$this->webspice->now()));

		if( !$this->db->insert_id() ){
			$this->webspice->message_board('We could not execute your request. Please tray again later or report to authority.');
			$this->webspice->force_redirect($url_prefix . 'admin');
			return false;
		}

		$this->webspice->message_board('Record inserted successfully!');
		if($this->webspice->permission_verify('manage_subject',TRUE)){
			$this->webspice->force_redirect($url_prefix . 'manage_subject');
			return FALSE;
		}
		$this->webspice->force_redirect($url_prefix.'create_subject');

	}

	public function manage_subject() {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'manage_subject');
		$this->webspice->permission_verify('manage_subject');

		$this->load->database();
		$orderby = 'ORDER BY subject.SUBJECT_NAME ASC';
		$groupby = null;
		$where = '';
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
		SELECT  * FROM subject ";


		# filtering records
		if( $this->input->post('filter') ){
			$result = $this->webspice->filter_generator(
				$TableName = 'subject',
				$InputField = array(),
				$Keyword = array('SUBJECT_NAME'),
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



				$this->load->view('admin/print_report/settings/print_subject',$data);
				return false;
				break;

			case 'pdf':
				if( !isset($_SESSION['sql']) || !$_SESSION['sql'] ){
					$_SESSION['sql'] = $initialSQL . $where . $orderby;
					$_SESSION['filter_by'] = $filter_by;
				}

				$record = $this->db->query( substr($_SESSION['sql'], 0, stripos($_SESSION['sql'],'LIMIT')) );
				$data['get_record'] = $record->result();
				$data['filter_by'] = $_SESSION['filter_by'];
				// dd($data);
				$rand = substr( md5(rand()), 0, 15);
				// dd($rand);
				$pdfFilePath = "$rand.pdf";
				$html = $this->load->view('admin/print_report/settings/print_subject', $data, true);
				$this->load->library('pdf');
				$pdf = $this->pdf->load();
				$pdf->SetFooter($_SERVER['HTTP_HOST'].'|{PAGENO}|'.date(DATE_RFC822));
				$pdf->WriteHTML($html); // write the HTML into the PDF
				$pdf->Output($pdfFilePath, 'D');
				// dd($pdfFilePath);

				return false;
			break;

			case 'edit':
				$this->webspice->edit_generator($TableName='subject', $KeyField='SUBJECT_ID', $key, $RedirectController='Settings_controller', $RedirectFunction='create_subject', $PermissionName='manage_subject', $StatusCheck=null, $Log='edit_section');
				return false;
				break;

			case 'inactive':
				$this->webspice->action_executer($TableName='subject', $KeyField='SUBJECT_ID', $key, $RedirectURL='manage_subject', $PermissionName='manage_subject', $StatusCheck=7, $ChangeStatus=-7, $RemoveCache='subject', $Log='inactive_section');
				return false;
				break;

			case 'active':
				$this->webspice->action_executer($TableName='subject', $KeyField='SUBJECT_ID', $key, $RedirectURL='manage_subject', $PermissionName='manage_subject', $StatusCheck=-7, $ChangeStatus=7, $RemoveCache='subject', $Log='active_section');
				return false;
				break;

			case 'delete':
				$id = $this->webspice->encrypt_decrypt($key, 'decrypt');
				$sql = $this->db->query("DELETE FROM subject WHERE SUBJECT_ID='".$id."' LIMIT 1");
				if($sql) {
					$this->webspice->force_redirect($url_prefix.'manage_subject');
				}
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
			$data['pager'] = $this->webspice->pager( count($count_data), $no_of_record, $page_index, $url_prefix.'manage_subject/page/', 10 );	
		}

		$_SESSION['sql'] = $sql;
		$_SESSION['filter_by'] = $filter_by;
		$result = $this->db->query($sql)->result();

		$data['get_record'] = $result;
		$data['filter_by'] = $filter_by;

		$this->load->view('admin/settings/manage_subject', $data);

	}


	public function create_staff($data=null) {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'create_staff');
		$this->webspice->permission_verify('create_staff');
		if( !isset($data['edit']) ){
			$data['edit'] = array(
				'STAFF_ID'=>null,
				'STAFF_NAME'=>null,
				'STAFF_CARD_NO'=>null,
				'PHONE'=>null,
				'SEX'=>null,
				'STAFF_BIRTHDAY'=>null,
				'EMAIL'=>null,
				'IMAGES'=>null,
				'ADDRESS'=>null,
				'PASSWORD'=>null,
				'DESIGNATION_ID'=>null
			);
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name','name','required|trim|xss_clean');
		$this->form_validation->set_rules('designation_id','Designation','required|trim|xss_clean');
		$this->form_validation->set_rules('gender','Gender','required|trim|xss_clean');
		$this->form_validation->set_rules('phone','Phone','required|trim|xss_clean');
		$this->form_validation->set_rules('address','address','required|trim|xss_clean');
		
		if( !$this->form_validation->run() ){
			$this->load->view('admin/create_staff', $data);
			return FALSE;
		}

		// dd($_FILES);

		# get input post
		$input = $this->webspice->get_input('staff_id');
		// dd($input);
		
		#duplicate test
		$this->webspice->db_field_duplicate_test("SELECT * FROM staff WHERE STAFF_CARD_NO=? AND STAFF_NAME=?", array($input->card_no, $input->name), 'You are not allowed to enter duplicate staff', 'STAFF_ID', $input->staff_id, $data, 'admin/create_staff');
		
		# remove cache
		$this->webspice->remove_cache('staff');

		# verify file type
		if( $_FILES['images']['tmp_name'] ){
			$this->webspice->check_file_type(array('jpg','jpeg', 'png', 'gif'), 'images', $data, 'admin/create_staff');
		}

		# update process
		if( $input->staff_id ){

			$sql = "
			UPDATE staff SET STAFF_NAME=?, STAFF_CARD_NO=?, IMAGES=?, ADDRESS=?, DESIGNATION_ID=?, STAFF_BIRTHDAY=?, SEX=?,  PHONE=?, EMAIL=?, PASSWORD=?,     UPDATED_BY=?,UPDATED_DATE=?
			WHERE STAFF_ID=?";
			$this->db->query($sql, array($input->name, $input->card_no, $_FILES['images']['name'], $input->address, $input->designation_id, date("Y-m-d", strtotime($input->birthday)), $input->gender, $input->phone, $input->email, $input->password, $this->webspice->get_user_id(), $this->webspice->now(), $input->staff_id));
			$this->webspice->process_image_single('images',$input->staff_id, 'staff_full', 750, 1000);
			$this->webspice->message_board('Record has been updated!');
			$this->webspice->log_me('product_updated - '.$this->webspice->get_user_id()); # log activities
			$this->webspice->force_redirect($url_prefix.'manage_staff');
			return false;
		}
		
		#insert person
		$sql = "
		INSERT INTO staff
		(STAFF_NAME, STAFF_CARD_NO, IMAGES, ADDRESS, DESIGNATION_ID, STAFF_BIRTHDAY, SEX, PHONE, EMAIL, PASSWORD, CREATED_BY,CREATED_DATE,STATUS)
		VALUES
		(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 7 )";
		$this->db->query($sql, array($input->name, $input->card_no, $_FILES['images']['name'], $input->address, $input->designation_id, date("Y-m-d", strtotime($input->birthday)), $input->gender, $input->phone, $input->email, $input->password, $this->webspice->get_user_id(),$this->webspice->now()));
		$this->webspice->process_image_single('images',$this->db->insert_id(), 'staff_full', 750, 1000);

		if( !$this->db->insert_id() ){
			$this->webspice->message_board('We could not execute your request. Please tray again later or report to authority.');
			$this->webspice->force_redirect($url_prefix . 'admin');
			return false;
		}

		$this->webspice->message_board('Record inserted successfully!');
		if($this->webspice->permission_verify('manage_staff',TRUE)){
			$this->webspice->force_redirect($url_prefix . 'manage_staff');
			return FALSE;
		}
		$this->webspice->force_redirect($url_prefix.'create_staff');

	}

	public function manage_staff() {
		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->load->database();
		$orderby = 'ORDER BY staff.STAFF_NAME ASC';
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
		SELECT  * FROM staff	";


		# filtering records
		if( $this->input->post('filter') ){
			$result = $this->webspice->filter_generator(
				$TableName = 'staff',
				$InputField = array(),
				$Keyword = array('STAFF_NAME'),
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

				$this->load->view('admin/print_report/staff/print_staff',$data);
				return false;
				break;

			case 'pdf':
				if( !isset($_SESSION['sql']) || !$_SESSION['sql'] ){
					$_SESSION['sql'] = $initialSQL . $where . $orderby;
					$_SESSION['filter_by'] = $filter_by;
				}

				$record = $this->db->query( substr($_SESSION['sql'], 0, stripos($_SESSION['sql'],'LIMIT')) );
				$data['get_record'] = $record->result();
				$data['filter_by'] = $_SESSION['filter_by'];
				// dd($data);
				$rand = substr( md5(rand()), 0, 15);
				// dd($rand);
				$pdfFilePath = "$rand.pdf";
				$html = $this->load->view('admin/print_report/staff/print_staff', $data, true);
				$this->load->library('pdf');
				$pdf = $this->pdf->load();
				$pdf->SetFooter($_SERVER['HTTP_HOST'].'|{PAGENO}|'.date(DATE_RFC822));
				$pdf->WriteHTML($html); // write the HTML into the PDF
				$pdf->Output($pdfFilePath, 'D');
				// dd($pdfFilePath);

				return false;
			break;

			case 'edit':
				$this->webspice->edit_generator($TableName='staff', $KeyField='STAFF_ID', $key, $RedirectController='settings_controller', $RedirectFunction='create_staff', $PermissionName='manage_staff', $StatusCheck=null, $Log='edit_staff');
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
				$this->webspice->action_executer($TableName='staff', $KeyField='STAFF_ID', $key, $RedirectURL='manage_staff', $PermissionName='manage_staff', $StatusCheck=7, $ChangeStatus=-7, $RemoveCache='staff', $Log='inactive_staff');
				return false;
				break;

			case 'active':
				$this->webspice->action_executer($TableName='staff', $KeyField='STAFF_ID', $key, $RedirectURL='manage_staff', $PermissionName='manage_staff', $StatusCheck=-7, $ChangeStatus=7, $RemoveCache='staff', $Log='active_staff');
				return false;
				break;

			case 'delete':
				$id = $this->webspice->encrypt_decrypt($key, 'decrypt');
				$sql = $this->db->query("DELETE FROM staff WHERE STAFF_ID='".$id."' LIMIT 1");
				if(!unlink($this->webspice->get_path('person_full').$id.'.jpg')) {
					die($this->webspice->get_path('person_full').$id.'.jpg');
				}
				if($sql) {
					$this->webspice->force_redirect($url_prefix.'manage_staff');
				}
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
			$data['pager'] = $this->webspice->pager( count($count_data), $no_of_record, $page_index, $url_prefix.'manage_staff/page/', 10 );	
		}

		$_SESSION['sql'] = $sql;
		$_SESSION['filter_by'] = $filter_by;
		$result = $this->db->query($sql)->result();

		$data['get_record'] = $result;
		$data['filter_by'] = $filter_by;

		$this->load->view('admin/manage_staff', $data);
	}

	public function create_board($data=null) {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'create_board');
		$this->webspice->permission_verify('create_board');
		if( !isset($data['edit']) ){
			$data['edit'] = array(
				'MEMBER_ID'=>null,
				'MEMBER_NAME'=>null,
				'MEMBER_BIRTHDAY'=>null,
				'SEX'=>null,
				'ADDRESS'=>null,
				'PHONE'=>null,
				'EMAIL'=>null,
				'IMAGES'=>null,
				'OCCUPATION'=>null,
				'AGE'=>null,
				'EDUCATION_BACK'=>null,
				'VOTER_ID'=>null,
				'SESSION_START'=>null,
				'SESSION_END'=>null,
				'MARRIAGE_STATUS'=>null
			);
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name','name','required|trim|xss_clean');
		$this->form_validation->set_rules('gender','Gender','required|trim|xss_clean');
		$this->form_validation->set_rules('phone','Phone','required|trim|xss_clean');
		$this->form_validation->set_rules('address','address','required|trim|xss_clean');
		$this->form_validation->set_rules('voter_id','voter id','required|trim|xss_clean');
		$this->form_validation->set_rules('occupation','occupation','required|trim|xss_clean');
		$this->form_validation->set_rules('session_start','session start','required|trim|xss_clean');
		$this->form_validation->set_rules('session_end','session end','required|trim|xss_clean');
		
		if( !$this->form_validation->run() ){
			$this->load->view('admin/create_board', $data);
			return FALSE;
		}

		// dd($_FILES);

		# get input post
		$input = $this->webspice->get_input('member_id');
		// dd($input);
		
		#duplicate test
		$this->webspice->db_field_duplicate_test("SELECT * FROM member WHERE PHONE=?", array($input->phone), 'You are not allowed to enter duplicate member', 'MEMBER_ID', $input->member_id, $data, 'admin/create_board');
		
		# remove cache
		$this->webspice->remove_cache('member');

		# verify file type
		if( $_FILES['images']['tmp_name'] ){
			$this->webspice->check_file_type(array('jpg','jpeg', 'png', 'gif'), 'images', $data, 'admin/create_board');
		}

		# update process
		if( $input->member_id ){

			$sql = "
			UPDATE member SET MEMBER_NAME=?, IMAGES=?, ADDRESS=?, MEMBER_BIRTHDAY=?, SEX=?,  PHONE=?, EMAIL=?, OCCUPATION=?, AGE=?, EDUCATION_BACK=?, VOTER_ID=?, SESSION_START=?, SESSION_END=?, MARRIAGE_STATUS=?, UPDATED_BY=?,UPDATED_DATE=?
			WHERE MEMBER_ID=?";
			$this->db->query($sql, array($input->name, $_FILES['images']['name'], $input->address, $input->birthday, $input->gender, $input->phone, $input->email, $input->occupation, $input->age, $input->education_back, $input->voter_id, date("Y-m-d", strtotime($input->session_start)), date("Y-m-d", strtotime($input->session_end)), $input->marriage_status, $this->webspice->get_user_id(), $this->webspice->now(), $input->member_id));
			$this->webspice->process_image_single('images',$input->member_id, 'member_full', 750, 1000);
			$this->webspice->message_board('Record has been updated!');
			$this->webspice->log_me('product_updated - '.$this->webspice->get_user_id()); # log activities
			$this->webspice->force_redirect($url_prefix.'manage_board');
			return false;
		}
		
		#insert person
		$sql = "
		INSERT INTO member
		(MEMBER_NAME, IMAGES, ADDRESS, MEMBER_BIRTHDAY, SEX, PHONE, EMAIL, OCCUPATION, AGE, EDUCATION_BACK, VOTER_ID, SESSION_START, SESSION_END, MARRIAGE_STATUS, CREATED_BY,CREATED_DATE,STATUS)
		VALUES
		(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 7 )";
		$this->db->query($sql, array($input->name, $_FILES['images']['name'], $input->address, $input->birthday, $input->gender, $input->phone, $input->email, $input->occupation, $input->age, $input->education_back, $input->voter_id, date("Y-m-d", strtotime($input->session_start)), date("Y-m-d", strtotime($input->session_end)), $input->marriage_status, $this->webspice->get_user_id(),$this->webspice->now()));
		$this->webspice->process_image_single('images',$this->db->insert_id(), 'member_full', 750, 1000);

		if( !$this->db->insert_id() ){
			$this->webspice->message_board('We could not execute your request. Please tray again later or report to authority.');
			$this->webspice->force_redirect($url_prefix . 'admin');
			return false;
		}

		$this->webspice->message_board('Record inserted successfully!');
		if($this->webspice->permission_verify('manage_board',TRUE)){
			$this->webspice->force_redirect($url_prefix . 'manage_board');
			return FALSE;
		}
		$this->webspice->force_redirect($url_prefix.'create_board');

	}

	public function manage_board() {
		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->load->database();
		$orderby = 'ORDER BY member.MEMBER_NAME ASC';
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
		SELECT  * FROM member	";


		# filtering records
		if( $this->input->post('filter') ){
			$result = $this->webspice->filter_generator(
				$TableName = 'member',
				$InputField = array(),
				$Keyword = array('MEMBER_NAME'),
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

				$this->load->view('admin/print_report/board/print_board_member',$data);
				return false;
				break;

			case 'pdf':
				if( !isset($_SESSION['sql']) || !$_SESSION['sql'] ){
					$_SESSION['sql'] = $initialSQL . $where . $orderby;
					$_SESSION['filter_by'] = $filter_by;
				}

				$record = $this->db->query( substr($_SESSION['sql'], 0, stripos($_SESSION['sql'],'LIMIT')) );
				$data['get_record'] = $record->result();
				$data['filter_by'] = $_SESSION['filter_by'];
				// dd($data);
				$rand = substr( md5(rand()), 0, 15);
				// dd($rand);
				$pdfFilePath = "$rand.pdf";
				$html = $this->load->view('admin/print_report/board/print_board_member', $data, true);
				$this->load->library('pdf');
				$pdf = $this->pdf->load();
				$pdf->SetFooter($_SERVER['HTTP_HOST'].'|{PAGENO}|'.date(DATE_RFC822));
				$pdf->WriteHTML($html); // write the HTML into the PDF
				$pdf->Output($pdfFilePath, 'D');
				// dd($pdfFilePath);

				return false;
			break;

			case 'edit':
				$this->webspice->edit_generator($TableName='member', $KeyField='MEMBER_ID', $key, $RedirectController='settings_controller', $RedirectFunction='create_board', $PermissionName='manage_board', $StatusCheck=null, $Log='edit_member');
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
				$this->webspice->action_executer($TableName='member', $KeyField='MEMBER_ID', $key, $RedirectURL='manage_board', $PermissionName='manage_board', $StatusCheck=7, $ChangeStatus=-7, $RemoveCache='member', $Log='inactive_member');
				return false;
				break;

			case 'active':
				$this->webspice->action_executer($TableName='member', $KeyField='MEMBER_ID', $key, $RedirectURL='manage_board', $PermissionName='manage_board', $StatusCheck=-7, $ChangeStatus=7, $RemoveCache='member', $Log='active_member');
				return false;
				break;

			case 'delete':
				$id = $this->webspice->encrypt_decrypt($key, 'decrypt');
				$sql = $this->db->query("DELETE FROM member WHERE MEMBER_ID='".$id."' LIMIT 1");
				if(!unlink($this->webspice->get_path('member_full').$id.'.jpg')) {
					die($this->webspice->get_path('member_full').$id.'.jpg');
				}
				if($sql) {
					$this->webspice->force_redirect($url_prefix.'manage_board');
				}
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
			$data['pager'] = $this->webspice->pager( count($count_data), $no_of_record, $page_index, $url_prefix.'manage_board/page/', 10 );	
		}

		$_SESSION['sql'] = $sql;
		$_SESSION['filter_by'] = $filter_by;
		$result = $this->db->query($sql)->result();

		$data['get_record'] = $result;
		$data['filter_by'] = $filter_by;

		$this->load->view('admin/manage_board', $data);
	}

	public function create_teacher($data=null) {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'create_teacher');
		$this->webspice->permission_verify('create_teacher');
		if( !isset($data['edit']) ){
			$data['edit'] = array(
				'TEACHER_ID'=>null,
				'TEACHER_NAME'=>null,
				'TEACHER_BIRTHDAY'=>null,
				'DESIGNATION_ID'=>null,
				'GENDER'=>null,
				'RELIGION'=>null,
				'BLOOD_GROUP'=>null,
				'PRESENT_ADDRESS'=>null,
				'PERMANENT_ADDRESS'=>null,
				'VOTER_ID'=>null,
				'PHONE'=>null,
				'EMAIL'=>null,
				'EDUCATIONAL_BACK'=>null
			);
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('teacher_name','teacher name','required|trim|xss_clean');
		$this->form_validation->set_rules('teacher_birthday','teacher birth day','required|trim|xss_clean');
		$this->form_validation->set_rules('gender','Gender','required|trim|xss_clean');
		$this->form_validation->set_rules('phone','Phone','required|trim|xss_clean');
		$this->form_validation->set_rules('email','email','required|trim|xss_clean');
		$this->form_validation->set_rules('voter_id','voter id','required|trim|xss_clean');
		$this->form_validation->set_rules('blood_group','blood group','trim|xss_clean');
		$this->form_validation->set_rules('religion','blood group','trim|xss_clean');
		$this->form_validation->set_rules('designation_id','designation id','required|trim|xss_clean');
		$this->form_validation->set_rules('educational_back','educational background','required|trim|xss_clean');
		$this->form_validation->set_rules('present_address','present address','required|trim|xss_clean');
		$this->form_validation->set_rules('permanent_address','permanent address','required|trim|xss_clean');
		
		if( !$this->form_validation->run() ){
			$this->load->view('admin/create_teacher', $data);
			return FALSE;
		}

		// dd($_FILES);

		# get input post
		$input = $this->webspice->get_input('teacher_id');
		//dd($input);
		
		#duplicate test
		$this->webspice->db_field_duplicate_test("SELECT * FROM teacher WHERE VOTER_ID=?", array($input->voter_id), 'You are not allowed to enter duplicate teacher', 'TEACHER_ID', $input->teacher_id, $data, 'admin/create_teacher');
		
		# remove cache
		$this->webspice->remove_cache('teacher');

		# verify file type
		if( $_FILES['images']['tmp_name'] ){
			$this->webspice->check_file_type(array('jpg','jpeg', 'png', 'gif'), 'images', $data, 'admin/create_teacher');
		}

		# update process
		if( $input->teacher_id ){

			$sql = "
			UPDATE teacher SET TEACHER_NAME=?, IMAGES=?, PRESENT_ADDRESS=?, PERMANENT_ADDRESS=?, TEACHER_BIRTHDAY=?, GENDER=?,  PHONE=?, EMAIL=?, DESIGNATION_ID=?, BLOOD_GROUP=?, EDUCATIONAL_BACK=?, VOTER_ID=?, RELIGION=?, UPDATED_BY=?,UPDATED_DATE=?
			WHERE TEACHER_ID=?";
			$this->db->query($sql, array($input->teacher_name, $_FILES['images']['name'], $input->present_address, $input->permanent_address, date("Y-m-d", strtotime($input->teacher_birthday)), $input->gender, $input->phone, $input->email, $input->designation_id, $input->blood_group, $input->educational_back, $input->voter_id, $input->religion, $this->webspice->get_user_id(), $this->webspice->now(), $input->teacher_id));
			$this->webspice->process_image_single('images',$input->teacher_id, 'teacher_full', 750, 1000);
			$this->webspice->message_board('Record has been updated!');
			$this->webspice->log_me('product_updated - '.$this->webspice->get_user_id()); # log activities
			$this->webspice->force_redirect($url_prefix.'manage_teacher');
			return false;
		}
		
		#insert data
		$sql = "
		INSERT INTO teacher
		(TEACHER_NAME, IMAGES, PRESENT_ADDRESS , PERMANENT_ADDRESS, TEACHER_BIRTHDAY, GENDER, PHONE, EMAIL, DESIGNATION_ID, BLOOD_GROUP, EDUCATIONAL_BACK, VOTER_ID, RELIGION, CREATED_BY,CREATED_DATE,STATUS)
		VALUES
		(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 7 )";
		$this->db->query($sql, array($input->teacher_name, $_FILES['images']['name'], $input->present_address, $input->permanent_address, date("Y-m-d", strtotime($input->teacher_birthday)), $input->gender, $input->phone, $input->email, $input->designation_id, $input->blood_group, $input->educational_back, $input->voter_id, $input->religion, $this->webspice->get_user_id(),$this->webspice->now()));
		$this->webspice->process_image_single('images',$this->db->insert_id(), 'teacher_full', 750, 1000);

		$current_id = $this->db->insert_id();

		$sql = "
		INSERT INTO account_name
		(ACC_NAME, 	TEACHER_ID, ACC_HEAD_TYPE, GROUP_HEAD_NAME, SUB_HEAD_ID, OPENING_BALANCE, OPENING_BALANCE_TYPE, CODE, CREATED_BY, CREATED_DATE, STATUS)
		VALUES
		( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 7 )";
		$this->db->query($sql, array("Advance Against Salary", $current_id, "bs", "Asset", "Current Assets", 0, "Debit", "120137", $this->webspice->get_user_id(), $this->webspice->now()));
		$this->db->query($sql, array("Salary Payable", $current_id, "bs", "Liability", "Current Liabilities", 0, "Credit", "220153", $this->webspice->get_user_id(), $this->webspice->now()));

		if( !$this->db->insert_id() ){
			$this->webspice->message_board('We could not execute your request. Please tray again later or report to authority.');
			$this->webspice->force_redirect($url_prefix . 'admin');
			return false;
		}

		$this->webspice->message_board('Record inserted successfully!');
		if($this->webspice->permission_verify('manage_teacher',TRUE)){
			$this->webspice->force_redirect($url_prefix . 'manage_teacher');
			return FALSE;
		}
		$this->webspice->force_redirect($url_prefix.'create_teacher');

	}

	public function manage_teacher() {
		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->load->database();
		$orderby = 'ORDER BY teacher.TEACHER_NAME ASC';
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
		SELECT  * FROM teacher	";


		# filtering records
		if( $this->input->post('filter') ){
			$result = $this->webspice->filter_generator(
				$TableName = 'teacher',
				$InputField = array(),
				$Keyword = array('TEACHER_NAME'),
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

				$this->load->view('admin/print_report/teacher/print_teacher_info',$data);
				return false;
				break;

			case 'pdf':
				if( !isset($_SESSION['sql']) || !$_SESSION['sql'] ){
					$_SESSION['sql'] = $initialSQL . $where . $orderby;
					$_SESSION['filter_by'] = $filter_by;
				}

				$record = $this->db->query( substr($_SESSION['sql'], 0, stripos($_SESSION['sql'],'LIMIT')) );
				$data['get_record'] = $record->result();
				$data['filter_by'] = $_SESSION['filter_by'];
				// dd($data);
				$rand = substr( md5(rand()), 0, 15);
				// dd($rand);
				$pdfFilePath = "$rand.pdf";
				$html = $this->load->view('admin/print_report/teacher/print_teacher_info', $data, true);
				$this->load->library('pdf');
				$pdf = $this->pdf->load();
				$pdf->SetFooter($_SERVER['HTTP_HOST'].'|{PAGENO}|'.date(DATE_RFC822));
				$pdf->WriteHTML($html); // write the HTML into the PDF
				$pdf->Output($pdfFilePath, 'D');
				// dd($pdfFilePath);

				return false;
			break;

			case 'edit':
				$this->webspice->edit_generator($TableName='teacher', $KeyField='TEACHER_ID', $key, $RedirectController='settings_controller', $RedirectFunction='create_teacher', $PermissionName='manage_teacher', $StatusCheck=null, $Log='edit_staff');
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
				$this->webspice->action_executer($TableName='teacher', $KeyField='TEACHER_ID', $key, $RedirectURL='manage_teacher', $PermissionName='manage_teacher', $StatusCheck=7, $ChangeStatus=-7, $RemoveCache='teacher', $Log='inactive_staff');
				return false;
				break;

			case 'active':
				$this->webspice->action_executer($TableName='teacher', $KeyField='TEACHER_ID', $key, $RedirectURL='manage_teacher', $PermissionName='manage_teacher', $StatusCheck=-7, $ChangeStatus=7, $RemoveCache='teacher', $Log='active_staff');
				return false;
				break;

			case 'delete':
				$id = $this->webspice->encrypt_decrypt($key, 'decrypt');
				$sql = $this->db->query("DELETE FROM teacher WHERE TEACHER_ID='".$id."' LIMIT 1");
				if(!unlink($this->webspice->get_path('teacher_full').$id.'.jpg')) {
					die($this->webspice->get_path('teacher_full').$id.'.jpg');
				}
				if($sql) {
					$this->webspice->force_redirect($url_prefix.'manage_teacher');
				}
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
			$data['pager'] = $this->webspice->pager( count($count_data), $no_of_record, $page_index, $url_prefix.'manage_teacher/page/', 10 );	
		}

		$_SESSION['sql'] = $sql;
		$_SESSION['filter_by'] = $filter_by;
		$result = $this->db->query($sql)->result();

		$data['get_record'] = $result;
		$data['filter_by'] = $filter_by;

		$this->load->view('admin/manage_teacher', $data);
	}

	public function assign_teacher($data=null) {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'assign_teacher');
		$this->webspice->permission_verify('assign_teacher');
		if( !isset($data['edit']) ){
			$data['edit'] = array(
				'TEACEHR_INFO_ID'=>null,
				'TEACHER_ID'=>null,
				'CLASS_ID'=>null,
				'SECTION_ID'=>null
			);
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('teacher_id','teacher  name','required|trim|xss_clean|max_length[200]');
		$this->form_validation->set_rules('class_id','class name','required|trim|xss_clean');
		$this->form_validation->set_rules('section_id','section name','required|trim|xss_clean');
		
		if( !$this->form_validation->run() ){
			$this->load->view('admin/assign_teacher', $data);
			return FALSE;
		}


		# get input post
		$input = $this->webspice->get_input('teacher_info_id');
		//dd(date("Y"));
		
		#duplicate test
		$this->webspice->db_field_duplicate_test("SELECT * FROM class_teacher WHERE CLASS_ID=? AND SECTION_ID=? AND YEAR=?", array($input->class_id, $input->section_id, date("Y")), 'You are not allowed to enter duplicate class teacher', 'TEACEHR_INFO_ID', $input->teacher_info_id, $data, 'admin/assign_teacher');
		
		# remove cache
		$this->webspice->remove_cache('class_teacher');

		# update process
		if( $input->teacher_info_id ){

			$sql = "
			UPDATE class_teacher SET TEACHER_ID=?, CLASS_ID=?, SECTION_ID=?, YEAR=?, UPDATED_BY=?,UPDATED_DATE=?
			WHERE TEACEHR_INFO_ID=?";
			$this->db->query($sql, array($input->teacher_id, $input->class_id, $input->section_id, date("Y"), $this->webspice->get_user_id(),$this->webspice->now(), $input->teacher_info_id));
			$this->webspice->message_board('Record has been updated!');
			$this->webspice->log_me('teacher_info_updated - '.$this->webspice->get_user_id()); # log activities
			$this->webspice->force_redirect($url_prefix.'manage_assign_teacher');
			return false;
		}
		
		#insert class_teacher

		$sql = "
		INSERT INTO class_teacher
		(TEACHER_ID, CLASS_ID, SECTION_ID, YEAR, CREATED_BY,CREATED_DATE,STATUS)
		VALUES
		( ?, ?, ?, ?, ?, ?, 7)";
		$this->db->query($sql, array($input->teacher_id,$input->class_id, $input->section_id,  date("Y"),
			$this->webspice->get_user_id(),$this->webspice->now()));

		if( !$this->db->insert_id() ){
			$this->webspice->message_board('We could not execute your request. Please tray again later or report to authority.');
			$this->webspice->force_redirect($url_prefix . 'admin');
			return false;
		}

		$this->webspice->message_board('Record inserted successfully!');
		if($this->webspice->permission_verify('manage_assign_teacher',TRUE)){
			$this->webspice->force_redirect($url_prefix . 'manage_assign_teacher');
			return FALSE;
		}
		$this->webspice->force_redirect($url_prefix.'assign_teacher');

	}

	public function manage_assign_teacher() {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'manage_assign_teacher');
		$this->webspice->permission_verify('manage_assign_teacher');

		$this->load->database();
		$orderby = 'ORDER BY class_teacher.TEACHER_ID ASC';
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
		SELECT  * FROM class_teacher ";


		# filtering records
		if( $this->input->post('filter') ){
			$result = $this->webspice->filter_generator(
				$TableName = 'class_teacher',
				$InputField = array(),
				$Keyword = array('TEACHER_ID'),
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



				$this->load->view('admin/print_report/teacher/print_class_teacher',$data);
				return false;
				break;

			case 'pdf':
				if( !isset($_SESSION['sql']) || !$_SESSION['sql'] ){
					$_SESSION['sql'] = $initialSQL . $where . $orderby;
					$_SESSION['filter_by'] = $filter_by;
				}

				$record = $this->db->query( substr($_SESSION['sql'], 0, stripos($_SESSION['sql'],'LIMIT')) );
				$data['get_record'] = $record->result();
				$data['filter_by'] = $_SESSION['filter_by'];
				// dd($data);
				$rand = substr( md5(rand()), 0, 15);
				// dd($rand);
				$pdfFilePath = "$rand.pdf";
				$html = $this->load->view('admin/print_report/teacher/print_class_teacher', $data, true);
				$this->load->library('pdf');
				$pdf = $this->pdf->load();
				$pdf->SetFooter($_SERVER['HTTP_HOST'].'|{PAGENO}|'.date(DATE_RFC822));
				$pdf->WriteHTML($html); // write the HTML into the PDF
				$pdf->Output($pdfFilePath, 'D');
				// dd($pdfFilePath);

				return false;
			break;

			case 'edit':
				$this->webspice->edit_generator($TableName='class_teacher', $KeyField='TEACEHR_INFO_ID', $key, $RedirectController='Settings_controller', $RedirectFunction='assign_teacher', $PermissionName='manage_assign_teacher', $StatusCheck=null, $Log='edit_teacher_info');
				return false;
				break;

			case 'inactive':
				$this->webspice->action_executer($TableName='class_teacher', $KeyField='TEACEHR_INFO_ID', $key, $RedirectURL='manage_assign_teacher', $PermissionName='manage_assign_teacher', $StatusCheck=7, $ChangeStatus=-7, $RemoveCache='class_teacher', $Log='inactive_teacher_info');
				return false;
				break;

			case 'active':
				$this->webspice->action_executer($TableName='class_teacher', $KeyField='TEACEHR_INFO_ID', $key, $RedirectURL='manage_assign_teacher', $PermissionName='manage_assign_teacher', $StatusCheck=-7, $ChangeStatus=7, $RemoveCache='class_teacher', $Log='active_teacher_info');
				return false;
				break;

			case 'delete':
				$id = $this->webspice->encrypt_decrypt($key, 'decrypt');
				$sql = $this->db->query("DELETE FROM class_teacher WHERE TEACEHR_INFO_ID='".$id."' LIMIT 1");
				if($sql) {
					$this->webspice->force_redirect($url_prefix.'manage_assign_teacher');
				}
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
			$data['pager'] = $this->webspice->pager( count($count_data), $no_of_record, $page_index, $url_prefix.'manage_assign_teacher/page/', 10 );	
		}

		$_SESSION['sql'] = $sql;
		$_SESSION['filter_by'] = $filter_by;
		$result = $this->db->query($sql)->result();

		$data['get_record'] = $result;
		$data['filter_by'] = $filter_by;

		$this->load->view('admin/manage_assign_teacher', $data);

	}

	public function create_parent($data=null) {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'create_parent');
		$this->webspice->permission_verify('create_parent');
		if( !isset($data['edit']) ){
			$data['edit'] = array(
				'PARENT_ID'=>null,
				'PARENT_NAME'=>null,
				'STUDENT_ID'=>null,
				'PHONE'=>null,
				'EMAIL'=>null,
				'NATIONAL_ID_NO'=>null,
				'GENDER'=>null,
				'RELATION_WITH_STU'=>null,
				'ADDRESS'=>null,
				'OCCOPATION' =>null,
				'IMAGES' =>null
			);
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('parent_name','parent name','required|trim|xss_clean');
		$this->form_validation->set_rules('student_id','student','required|trim|xss_clean');
		$this->form_validation->set_rules('phone','phone','required|trim|xss_clean');
		$this->form_validation->set_rules('email','email','required|trim|xss_clean');
		$this->form_validation->set_rules('relation_with_stu','relation with student','required|trim|xss_clean');
		$this->form_validation->set_rules('occopation','occopation','trim|xss_clean');
		$this->form_validation->set_rules('address','address','required|trim|xss_clean');
		
		if( !$this->form_validation->run() ){
			$this->load->view('admin/create_parent', $data);
			return FALSE;
		}

		// dd($_FILES);

		# get input post
		$input = $this->webspice->get_input('parent_id');
		 //dd($input);
		
		#duplicate test
		$this->webspice->db_field_duplicate_test("SELECT * FROM parent WHERE STUDENT_ID=?", array($input->student_id), 'You are not allowed to enter duplicate parent', 'PARENT_ID', $input->parent_id, $data, 'admin/create_parent');
		
		# remove cache
		$this->webspice->remove_cache('parent');

		# verify file type
		if( $_FILES['images']['tmp_name'] ){
			$this->webspice->check_file_type(array('jpg','jpeg', 'png', 'gif'), 'images', $data, 'admin/create_parent');
		}

		# update process
		if( $input->parent_id ){

			$sql = "
			UPDATE parent SET PARENT_NAME=?, STUDENT_ID=?, PHONE=?, IMAGES=?, EMAIL=?, NATIONAL_ID_NO=?, GENDER=?, RELATION_WITH_STU=?, OCCOPATION=?, ADDRESS=?, UPDATED_BY=?,UPDATED_DATE=?
			WHERE PARENT_ID=?";
			$this->db->query($sql, array($input->parent_name, $input->student_id, $input->phone, $_FILES['images']['name'], $input->email, $input->national_card_no, $input->gender, $input->relation_with_stu, $input->occopation, $input->address, $this->webspice->get_user_id(), $this->webspice->now(), $input->parent_id));
			$this->webspice->process_image_single('images',$input->parent_id, 'parent_full', 750, 1000);
			$this->webspice->message_board('Record has been updated!');
			$this->webspice->log_me('product_updated - '.$this->webspice->get_user_id()); # log activities
			$this->webspice->force_redirect($url_prefix.'manage_parent');
			return false;
		}
		
		#insert person
		$sql = "
		INSERT INTO parent
		(PARENT_NAME, STUDENT_ID, PHONE, IMAGES, EMAIL, NATIONAL_ID_NO, GENDER, RELATION_WITH_STU, OCCOPATION, ADDRESS, CREATED_BY,CREATED_DATE,STATUS)
		VALUES
		(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 7 )";
		$this->db->query($sql, array($input->parent_name, $input->student_id, $input->phone, $_FILES['images']['name'], $input->email, $input->national_card_no, $input->gender, $input->relation_with_stu, $input->occopation, $input->address, $this->webspice->get_user_id(),$this->webspice->now()));
		$this->webspice->process_image_single('images',$this->db->insert_id(), 'parent_full', 750, 1000);
		if( !$this->db->insert_id() ){
			$this->webspice->message_board('We could not execute your request. Please tray again later or report to authority.');
			$this->webspice->force_redirect($url_prefix . 'admin');
			return false;
		}

		$this->webspice->message_board('Record inserted successfully!');
		if($this->webspice->permission_verify('manage_parent',TRUE)){
			$this->webspice->force_redirect($url_prefix . 'manage_parent');
			return FALSE;
		}
		$this->webspice->force_redirect($url_prefix.'create_parent');

	}

	public function manage_parent(){

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'manage_parent');
		$this->webspice->permission_verify('manage_parent');

		$this->load->database();
		$orderby = 'ORDER BY parent.PARENT_NAME ASC';
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
		SELECT  * FROM parent ";


		# filtering records
		if( $this->input->post('filter') ){
			$result = $this->webspice->filter_generator(
				$TableName = 'parent',
				$InputField = array(),
				$Keyword = array('PARENT_NAME'),
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



				$this->load->view('admin/print_report/student/print_parent',$data);
				return false;
				break;

			case 'pdf':
				if( !isset($_SESSION['sql']) || !$_SESSION['sql'] ){
					$_SESSION['sql'] = $initialSQL . $where . $orderby;
					$_SESSION['filter_by'] = $filter_by;
				}

				$record = $this->db->query( substr($_SESSION['sql'], 0, stripos($_SESSION['sql'],'LIMIT')) );
				$data['get_record'] = $record->result();
				$data['filter_by'] = $_SESSION['filter_by'];
				// dd($data);
				$rand = substr( md5(rand()), 0, 15);
				// dd($rand);
				$pdfFilePath = "$rand.pdf";
				$html = $this->load->view('admin/print_report/student/print_parent', $data, true);
				$this->load->library('pdf');
				$pdf = $this->pdf->load();
				$pdf->SetFooter($_SERVER['HTTP_HOST'].'|{PAGENO}|'.date(DATE_RFC822));
				$pdf->WriteHTML($html); // write the HTML into the PDF
				$pdf->Output($pdfFilePath, 'D');
				// dd($pdfFilePath);

				return false;
			break;
			
			case 'edit':
				$this->webspice->edit_generator($TableName='parent', $KeyField='PARENT_ID', $key, $RedirectController='Settings_controller', $RedirectFunction='create_parent', $PermissionName='manage_parent', $StatusCheck=null, $Log='edit_parent');
				return false;
				break;

			case 'inactive':
				$this->webspice->action_executer($TableName='parent', $KeyField='PARENT_ID', $key, $RedirectURL='manage_parent', $PermissionName='manage_parent', $StatusCheck=7, $ChangeStatus=-7, $RemoveCache='parent', $Log='inactive_parent');
				return false;
				break;

			case 'active':
				$this->webspice->action_executer($TableName='parent', $KeyField='PARENT_ID', $key, $RedirectURL='manage_parent', $PermissionName='manage_parent', $StatusCheck=-7, $ChangeStatus=7, $RemoveCache='parent', $Log='active_parent');
				return false;
				break;

			case 'delete':
				$id = $this->webspice->encrypt_decrypt($key, 'decrypt');
				$sql = $this->db->query("DELETE FROM parent WHERE 	PARENT_ID='".$id."' LIMIT 1");
				if($sql) {
					$this->webspice->force_redirect($url_prefix.'manage_parent');
				}
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
			$data['pager'] = $this->webspice->pager( count($count_data), $no_of_record, $page_index, $url_prefix.'manage_parent/page/', 10 );	
		}

		$_SESSION['sql'] = $sql;
		$_SESSION['filter_by'] = $filter_by;
		$result = $this->db->query($sql)->result();

		$data['get_record'] = $result;
		$data['filter_by'] = $filter_by;

		$this->load->view('admin/manage_parent', $data);

	}

	public function assign_subject($data=null) {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'assign_subject');
		$this->webspice->permission_verify('assign_subject');
		if( !isset($data['edit']) ){
			$data['edit'] = array(
				'ASSIGN_SUBJECT_ID'=>null,
				'TEACHER_ID'=>null,
				'CLASS_ID'=>null,
				'SECTION_ID'=>null,
				'SUBJECT_ID'=>null,
				'YEAR'=> null
			);
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('teacher_id','teacher  name','required|xss_clean|max_length[200]');
		$this->form_validation->set_rules('class_id','class name','required|xss_clean');
		$this->form_validation->set_rules('section_id','section name','required|xss_clean');
		$this->form_validation->set_rules('subject_id','subject name','required|xss_clean');
		
		if( !$this->form_validation->run() ){
			$this->load->view('admin/assign_subject', $data);
			return FALSE;
		}


		# get input post
		$input = $this->webspice->get_input('assign_subject_id');
		// dd($input);

		$class_list = array_values(array_filter($input->class_id));
		$section_list = array_values(array_filter($input->section_id));
		$subject_list = array_values(array_filter($input->subject_id));
		// dd($input->subject_id);
		// dd($subject_list);
		if( (count($class_list) != count($section_list)) OR (count($class_list) != count($subject_list)) OR (count($section_list) != count($subject_list)) ) {
			$data['id_count_mismatch'] = "Class, Subject or Section data mismatch";
			$this->load->view('admin/assign_subject', $data);
			return false;
		}
		
		#duplicate test
		for($i=0; $i<count($class_list); $i++) {
			$this->webspice->db_field_duplicate_test("SELECT * FROM assign_subject WHERE CLASS_ID=? AND SECTION_ID=? AND SUBJECT_ID=? AND YEAR=?", array($class_list[$i], $section_list[$i], $subject_list[$i], date("Y")), 'You are not allowed to enter duplicate subject for a teacher', 'ASSIGN_SUBJECT_ID', $input->assign_subject_id, $data, 'admin/assign_subject');
		}
		
		# remove cache
		$this->webspice->remove_cache('assign_subject');

		# update process
		if( $input->assign_subject_id ){

			$sql = "
			UPDATE assign_subject SET TEACHER_ID=?, CLASS_ID=?, SECTION_ID=?, SUBJECT_ID=?, UPDATED_BY=?,UPDATED_DATE=?
			WHERE TEACEHR_INFO_ID=?";
			$this->db->query($sql, array($input->teacher_id, $input->class_id, $input->section_id, $input->subject_id, $this->webspice->get_user_id(),$this->webspice->now(), $input->assign_subject_id));
			$this->webspice->message_board('Record has been updated!');
			$this->webspice->log_me('teacher_info_updated - '.$this->webspice->get_user_id()); # log activities
			$this->webspice->force_redirect($url_prefix.'manage_assign_subject');
			return false;
		}
		
		#insert assign_subject
			$sql = "
			INSERT INTO assign_subject
			(TEACHER_ID, CLASS_ID, SECTION_ID, SUBJECT_ID, YEAR, CREATED_BY, CREATED_DATE, STATUS)
			VALUES
			( ?, ?, ?, ?, ?, ?, ?, 7)";
		for($j=0; $j<count($class_list); $j++) {
			
			$this->db->query($sql, array($input->teacher_id, $class_list[$j], $section_list[$j], $subject_list[$j], date("Y"),
				$this->webspice->get_user_id(),$this->webspice->now()));
		}

		if( !$this->db->insert_id()){
			$this->webspice->message_board('We could not execute your request. Please tray again later or report to authority.');
			$this->webspice->force_redirect($url_prefix . 'admin');
			return false;
		}

		$this->webspice->message_board('Record inserted successfully!');
		if($this->webspice->permission_verify('manage_assign_subject',TRUE)){
			$this->webspice->force_redirect($url_prefix . 'manage_assign_subject');
			return FALSE;
		}
		$this->webspice->force_redirect($url_prefix.'assign_subject');

	}

	public function manage_assign_subject() {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'manage_assign_subject');
		$this->webspice->permission_verify('manage_assign_subject');

		$this->load->database();
		$orderby = 'ORDER BY assign_subject.CLASS_ID ASC';
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
		SELECT  * FROM assign_subject ";


		# filtering records
		if( $this->input->post('filter') ){
			$result = $this->webspice->filter_generator(
				$TableName = 'assign_subject',
				$InputField = array(),
				$Keyword = array('TEACHER_ID'),
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



				$this->load->view('admin/print_report/teacher/print_subject_teacher',$data);
				return false;
				break;

			case 'pdf':
				if( !isset($_SESSION['sql']) || !$_SESSION['sql'] ){
					$_SESSION['sql'] = $initialSQL . $where . $orderby;
					$_SESSION['filter_by'] = $filter_by;
				}

				$record = $this->db->query( substr($_SESSION['sql'], 0, stripos($_SESSION['sql'],'LIMIT')) );
				$data['get_record'] = $record->result();
				$data['filter_by'] = $_SESSION['filter_by'];
				// dd($data);
				$rand = substr( md5(rand()), 0, 15);
				// dd($rand);
				$pdfFilePath = "$rand.pdf";
				$html = $this->load->view('admin/print_report/teacher/print_subject_teacher', $data, true);
				$this->load->library('pdf');
				$pdf = $this->pdf->load();
				$pdf->SetFooter($_SERVER['HTTP_HOST'].'|{PAGENO}|'.date(DATE_RFC822));
				$pdf->WriteHTML($html); // write the HTML into the PDF
				$pdf->Output($pdfFilePath, 'D');
				// dd($pdfFilePath);

				return false;
			break;

			case 'edit':
				$this->webspice->edit_generator($TableName='assign_subject', $KeyField='ASSIGN_SUBJECT_ID', $key, $RedirectController='Settings_controller', $RedirectFunction='assign_teacher', $PermissionName='manage_assign_subject', $StatusCheck=null, $Log='edit_teacher_info');
				return false;
				break;

			case 'inactive':
				$this->webspice->action_executer($TableName='assign_subject', $KeyField='ASSIGN_SUBJECT_ID', $key, $RedirectURL='manage_assign_subject', $PermissionName='manage_assign_subject', $StatusCheck=7, $ChangeStatus=-7, $RemoveCache='assign_subject', $Log='inactive_subject');
				return false;
				break;

			case 'active':
				$this->webspice->action_executer($TableName='assign_subject', $KeyField='ASSIGN_SUBJECT_ID', $key, $RedirectURL='manage_assign_subject', $PermissionName='manage_assign_subject', $StatusCheck=-7, $ChangeStatus=7, $RemoveCache='assign_subject', $Log='active_subject');
				return false;
				break;

			case 'delete':
				$id = $this->webspice->encrypt_decrypt($key, 'decrypt');
				$sql = $this->db->query("DELETE FROM assign_subject WHERE ASSIGN_SUBJECT_ID='".$id."' LIMIT 1");
				if($sql) {
					$this->webspice->force_redirect($url_prefix.'manage_assign_subject');
				}
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
			$data['pager'] = $this->webspice->pager( count($count_data), $no_of_record, $page_index, $url_prefix.'manage_assign_subject/page/', 10 );	
		}

		$_SESSION['sql'] = $sql;
		$_SESSION['filter_by'] = $filter_by;
		$result = $this->db->query($sql)->result();

		$data['get_record'] = $result;
		$data['filter_by'] = $filter_by;

		$this->load->view('admin/manage_assign_subject', $data);

	}

	public function create_exam($data=null) {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'create_exam');
		$this->webspice->permission_verify('create_exam');
		if( !isset($data['edit']) ){
			$data['edit'] = array(
				'EXAM_ID'=>null,
				'EXAM_NAME'=>null,
				'EXAM_DATE' => null,
				'EXAM_COMMENT'=>null
			);
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('exam_name','Exam name','required|trim|xss_clean|max_length[400]');
		$this->form_validation->set_rules('exam_date','exam date','required|trim|xss_clean|max_length[200]');
		$this->form_validation->set_rules('exam_comment','exam comment','required|trim|xss_clean|max_length[200]');
		
		if( !$this->form_validation->run() ){
			$this->load->view('admin/create_exam', $data);
			return FALSE;
		}

		# get input post
		$input = $this->webspice->get_input('exam_id');
		
		#duplicate test
		$this->webspice->db_field_duplicate_test("SELECT * FROM exam WHERE EXAM_NAME=?", array( $input->exam_name), 'You are not allowed to enter duplicate exam name', 'EXAM_ID', $input->exam_id, $data, 'admin/create_exam');
		
		# remove cache
		$this->webspice->remove_cache('exam');

		# update process
		if( $input->exam_id ){

			$sql = "
			UPDATE exam SET EXAM_NAME=?, EXAM_DATE=?, EXAM_COMMENT=?, UPDATED_BY=?,UPDATED_DATE=?
			WHERE EXAM_ID=?";
			$this->db->query($sql, array($input->exam_name, date("Y-m-d", strtotime($input->exam_date)), $input->exam_comment, $this->webspice->get_user_id(),$this->webspice->now(), $input->exam_id));
			$this->webspice->message_board('Record has been updated!');
			$this->webspice->log_me('exam_updated - '.$this->webspice->get_user_id()); # log activities
			$this->webspice->force_redirect($url_prefix.'manage_exam_list');
			return false;
		}
		
		#insert exam

		$sql = "
		INSERT INTO exam
		(EXAM_NAME, EXAM_DATE, EXAM_COMMENT, CREATED_BY,CREATED_DATE,STATUS)
		VALUES
		( ?, ?, ?, ?, ?, 7)";
		$this->db->query($sql, array($input->exam_name, date("Y-m-d", strtotime($input->exam_date)), $input->exam_comment,
			$this->webspice->get_user_id(),$this->webspice->now()));

		if( !$this->db->insert_id() ){
			$this->webspice->message_board('We could not execute your request. Please tray again later or report to authority.');
			$this->webspice->force_redirect($url_prefix . 'admin');
			return false;
		}

		$this->webspice->message_board('Record inserted successfully!');
		if($this->webspice->permission_verify('manage_exam_list',TRUE)){
			$this->webspice->force_redirect($url_prefix . 'manage_exam_list');
			return FALSE;
		}
		$this->webspice->force_redirect($url_prefix.'create_exam');

	}

	public function manage_exam_list() {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'manage_exam_list');
		$this->webspice->permission_verify('manage_exam_list');

		$this->load->database();
		$orderby = 'ORDER BY exam.EXAM_NAME ASC';
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
		SELECT  * FROM exam ";


		# filtering records
		if( $this->input->post('filter') ){
			$result = $this->webspice->filter_generator(
				$TableName = 'exam',
				$InputField = array(),
				$Keyword = array('EXAM_NAME'),
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



				$this->load->view('admin/print_exam',$data);
				return false;
				break;

			case 'edit':
				$this->webspice->edit_generator($TableName='exam', $KeyField='	EXAM_ID', $key, $RedirectController='Settings_controller', $RedirectFunction='create_exam', $PermissionName='manage_exam_list', $StatusCheck=null, $Log='edit_notice');
				return false;
				break;

			case 'inactive':
				$this->webspice->action_executer($TableName='exam', $KeyField='	EXAM_ID', $key, $RedirectURL='manage_exam_list', $PermissionName='manage_exam_list', $StatusCheck=7, $ChangeStatus=-7, $RemoveCache='exam', $Log='inactive_class');
				return false;
				break;

			case 'active':
				$this->webspice->action_executer($TableName='exam', $KeyField='	EXAM_ID', $key, $RedirectURL='manage_exam_list', $PermissionName='manage_exam_list', $StatusCheck=-7, $ChangeStatus=7, $RemoveCache='exam', $Log='active_class');
				return false;
				break;

			case 'delete':
				$id = $this->webspice->encrypt_decrypt($key, 'decrypt');
				$sql = $this->db->query("DELETE FROM exam WHERE 	EXAM_ID='".$id."' LIMIT 1");
				if($sql) {
					$this->webspice->force_redirect($url_prefix.'manage_exam_list');
				}
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
			$data['pager'] = $this->webspice->pager( count($count_data), $no_of_record, $page_index, $url_prefix.'manage_exam_list/page/', 10 );	
		}

		$_SESSION['sql'] = $sql;
		$_SESSION['filter_by'] = $filter_by;
		$result = $this->db->query($sql)->result();

		$data['get_record'] = $result;
		$data['filter_by'] = $filter_by;

		$this->load->view('admin/manage_exam_list', $data);

	}

	public function create_grade($data=null) {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'create_grade');
		$this->webspice->permission_verify('create_grade');
		if( !isset($data['edit']) ){
			$data['edit'] = array(
				'GRADE_ID'=>null,
				'GRADE_NAME'=>null,
				'MARK_FROM' => null,
				'MARK_UPTO'=>null,
				'COMMENT' => null
			);
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('grade_name','Grade name','required|trim|xss_clean|max_length[400]');
		$this->form_validation->set_rules('mark_from','mark from','required|trim|xss_clean|max_length[200]');
		$this->form_validation->set_rules('mark_upto','mark upto','required|trim|xss_clean|max_length[200]');
		$this->form_validation->set_rules('grade_comment','comment','required|trim|xss_clean|max_length[200]');
		
		if( !$this->form_validation->run() ){
			$this->load->view('admin/create_grade', $data);
			return FALSE;
		}

		# get input post
		$input = $this->webspice->get_input('grade_id');
		
		#duplicate test
		$this->webspice->db_field_duplicate_test("SELECT * FROM grade WHERE GRADE_NAME=?", array( $input->grade_name), 'You are not allowed to enter duplicate grade name', 'GRADE_ID', $input->grade_id, $data, 'admin/create_grade');
		
		# remove cache
		$this->webspice->remove_cache('grade');

		# update process
		if( $input->grade_id ){

			$sql = "
			UPDATE grade SET GRADE_NAME=?, MARK_FROM=?, MARK_UPTO=?, COMMENT=?, UPDATED_BY=?,UPDATED_DATE=?
			WHERE GRADE_ID=?";
			$this->db->query($sql, array($input->grade_name, $input->mark_from, $input->mark_upto, $input->grade_comment, $this->webspice->get_user_id(),$this->webspice->now(), $input->grade_id));
			$this->webspice->message_board('Record has been updated!');
			$this->webspice->log_me('exam_updated - '.$this->webspice->get_user_id()); # log activities
			$this->webspice->force_redirect($url_prefix.'manage_grade');
			return false;
		}
		
		#insert grade

		$sql = "
		INSERT INTO grade
		(GRADE_NAME, MARK_FROM, MARK_UPTO, COMMENT, CREATED_BY,CREATED_DATE,STATUS)
		VALUES
		( ?, ?, ?, ?, ?, ?, 7)";
		$this->db->query($sql, array($input->grade_name, $input->mark_from, $input->mark_upto, $input->grade_comment,
			$this->webspice->get_user_id(),$this->webspice->now()));

		if( !$this->db->insert_id() ){
			$this->webspice->message_board('We could not execute your request. Please tray again later or report to authority.');
			$this->webspice->force_redirect($url_prefix . 'admin');
			return false;
		}

		$this->webspice->message_board('Record inserted successfully!');
		if($this->webspice->permission_verify('manage_grade',TRUE)){
			$this->webspice->force_redirect($url_prefix . 'manage_grade');
			return FALSE;
		}
		$this->webspice->force_redirect($url_prefix.'create_grade');

	}

	public function manage_grade() {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'manage_grade');
		$this->webspice->permission_verify('manage_grade');

		$this->load->database();
		$orderby = 'ORDER BY grade.GRADE_NAME ASC';
		$groupby = null;
		$where = '';
		$page_index = 0;
		$no_of_record = 20000;
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
		SELECT  * FROM grade ";


		# filtering records
		if( $this->input->post('filter') ){
			$result = $this->webspice->filter_generator(
				$TableName = 'grade',
				$InputField = array(),
				$Keyword = array('GRADE_NAME'),
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



				$this->load->view('admin/print_exam',$data);
				return false;
				break;

			case 'edit':
				$this->webspice->edit_generator($TableName='grade', $KeyField='	GRADE_ID', $key, $RedirectController='Settings_controller', $RedirectFunction='create_grade', $PermissionName='manage_grade', $StatusCheck=null, $Log='edit_grade');
				return false;
				break;

			case 'inactive':
				$this->webspice->action_executer($TableName='grade', $KeyField='	GRADE_ID', $key, $RedirectURL='manage_grade', $PermissionName='manage_grade', $StatusCheck=7, $ChangeStatus=-7, $RemoveCache='grade', $Log='inactive_grade');
				return false;
				break;

			case 'active':
				$this->webspice->action_executer($TableName='grade', $KeyField='	GRADE_ID', $key, $RedirectURL='manage_grade', $PermissionName='manage_grade', $StatusCheck=-7, $ChangeStatus=7, $RemoveCache='grade', $Log='active_grade');
				return false;
				break;

			case 'delete':
				$id = $this->webspice->encrypt_decrypt($key, 'decrypt');
				$sql = $this->db->query("DELETE FROM grade WHERE 	GRADE_ID='".$id."' LIMIT 1");
				if($sql) {
					$this->webspice->force_redirect($url_prefix.'manage_grade');
				}
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
			$data['pager'] = $this->webspice->pager( count($count_data), $no_of_record, $page_index, $url_prefix.'manage_grade/page/', 10 );	
		}

		$_SESSION['sql'] = $sql;
		$_SESSION['filter_by'] = $filter_by;
		$result = $this->db->query($sql)->result();

		$data['get_record'] = $result;
		$data['filter_by'] = $filter_by;

		$this->load->view('admin/manage_grade', $data);

	}

	// end

	public function insert_marks() {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'insert_marks');
		$this->webspice->permission_verify('insert_marks');
		if( !isset($data['edit']) ){
			$data['edit'] = array(
				'CLASS_ID'=>null,
				'SECTION_ID'=>null,
				'SUBJECT_ID'=>null,
				'EXAM_ID' => null
			);
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('class_id','Class name','required|trim|xss_clean');
		$this->form_validation->set_rules('section_id','Section name','required|trim|xss_clean|max_length[200]');
		$this->form_validation->set_rules('subject_id','Subject name','required|trim|xss_clean|max_length[200]');
		$this->form_validation->set_rules('exam_id','Exam name','required|trim|xss_clean|max_length[200]');
		
		if( !$this->form_validation->run() ){
			$this->load->view('admin/insert_marks', $data);
			return FALSE;
		}
		else {

			$input = $this->webspice->get_input('exam_data');
			$class = $input->class_id;
			$section = $input->section_id;
			$subject = $input->subject_id;
			$exam = $input->exam_id;
			$year = date("Y");
			// dd(gettype($year));

			$dublicate = $this->db->query("SELECT * FROM marks WHERE CLASS_ID='".$class."'  AND SECTION_ID='".$section."' AND EXAM_ID='".$exam."' AND SUBJECT_ID='".$subject."' AND YEAR='".$year."'")->result();
			// dd(count($dublicate));
			if(count($dublicate)) {
				$this->webspice->message_board('Sorry, You already insert marks for this subject');
				$this->webspice->force_redirect($url_prefix.'insert_marks');
				return false;
			}

			$this->webspice->force_redirect($url_prefix.'manage_marks/insert_data/'.$class."/".$section."/".$exam."/".$subject );
			return false;
		}

	}

	public function manage_marks() {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'manage_marks');
		$this->webspice->permission_verify('manage_marks');

		$this->load->database();
		$orderby = ' ORDER BY m.CLASS_ID ASC ';
		$groupby = ' GROUP BY m.CLASS_ID, m.SECTION_ID,  m.SUBJECT_ID, m.EXAM_ID, m.YEAR';
		$where = '';
		$page_index = 0;
		$no_of_record = 20000000000000;
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
		SELECT  m.*, c.CLASS_NAME, s.SECTION_NAME, sub.SUBJECT_NAME, e.EXAM_NAME FROM marks AS m
		INNER JOIN class AS c ON c.CLASS_ID=m.CLASS_ID INNER JOIN section As s ON s.SECTION_ID=m.SECTION_ID
		INNER JOIN subject AS sub ON sub.SUBJECT_ID=m.SUBJECT_ID INNER JOIN exam AS e ON e.EXAM_ID=m.EXAM_ID ";


		# filtering records
		if( $this->input->post('filter') ){
			$result = $this->webspice->filter_generator(
				$TableName = 'marks',
				$InputField = array(),
				$Keyword = array('CLASS_ID'),
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



				$this->load->view('admin/print_exam',$data);
				return false;
				break;

			case 'edit':
				$class_id = $this->uri->segment(3);
				$section_id = $this->uri->segment(4);
				$exam_id = $this->uri->segment(5);
				$subject_id = $this->uri->segment(6);

				// dd($class_id);

				$info['class_name'] = $this->db->query("SELECT CLASS_NAME FROM class WHERE CLASS_ID=" . $class_id)->row()->CLASS_NAME;
				$info['section_name'] = $this->db->query("SELECT SECTION_NAME FROM section WHERE SECTION_ID=" . $section_id)->row()->SECTION_NAME;
				$info['subject_name'] = $this->db->query("SELECT SUBJECT_NAME FROM subject WHERE SUBJECT_ID=" . $subject_id)->row()->SUBJECT_NAME;
				$info['exam_name'] = $this->db->query("SELECT EXAM_NAME FROM exam WHERE EXAM_ID=" . $exam_id)->row()->EXAM_NAME;
				
				$info['exam_data'] = $this->db->query("SELECT m.*, si.NAME FROM marks AS m INNER JOIN student_info AS si ON m.STUDENT_ID=si.STUDENT_ID WHERE m.CLASS_ID='".$class_id."' AND m.SECTION_ID='".$section_id."' AND m.EXAM_ID='".$exam_id."' AND m.SUBJECT_ID='".$subject_id."' ORDER BY ROLL_NO ASC")->result();

				$this->load->library('form_validation');
				// $this->form_validation->set_rules('student_data_id[]','Student name','required|trim|xss_clean');
				$this->form_validation->set_rules('mark_obtained[]','Mark obtained','required|trim|xss_clean');
				$this->form_validation->set_rules('comment[]','comment','trim|xss_clean|max_length[300]');
				// dd($info);
				
				if( !$this->form_validation->run() ){
					$this->load->view('admin/edit_insert_data', $info);
					return FALSE;
				}

				# get input post
				$input = $this->webspice->get_input('mark_edit_id');
				// dd($input);
				
				# remove cache
				$this->webspice->remove_cache('mark');

				# update process

				$sql = "
				UPDATE marks SET MARK_OBTAINED=?, COMMENT=?, UPDATED_BY=?,UPDATED_DATE=?
				WHERE MARK_ID=?";
				for($i=0; $i<count($input->mark_id); $i++) {
					$this->db->query($sql, array($input->mark_obtained[$i], $input->comment[$i], $this->webspice->get_user_id(),$this->webspice->now(), $input->mark_id[$i]));
				}
				$this->webspice->message_board('Exam marks updated successfully');
				$this->webspice->log_me('insert_mark_updated - '.$this->webspice->get_user_id()); # log activities
				$this->webspice->force_redirect($url_prefix.'manage_marks');

				return false;
				break;

			case 'inactive':
				$this->webspice->action_executer($TableName='grade', $KeyField='	GRADE_ID', $key, $RedirectURL='manage_marks', $PermissionName='manage_marks', $StatusCheck=7, $ChangeStatus=-7, $RemoveCache='grade', $Log='inactive_grade');
				return false;
				break;

			case 'active':
				$this->webspice->action_executer($TableName='grade', $KeyField='	GRADE_ID', $key, $RedirectURL='manage_marks', $PermissionName='manage_marks', $StatusCheck=-7, $ChangeStatus=7, $RemoveCache='grade', $Log='active_grade');
				return false;
				break;

			case 'delete':
				$id = $this->webspice->encrypt_decrypt($key, 'decrypt');
				$sql = $this->db->query("DELETE FROM grade WHERE 	GRADE_ID='".$id."' LIMIT 1");
				if($sql) {
					$this->webspice->force_redirect($url_prefix.'manage_marks');
				}
				return false;
			break;

			case 'insert_data':
			// dd("Hello World");
				$info = array();
				$info['class'] = $this->uri->segment(3);
				$info['section'] = $this->uri->segment(4);
				$info['subject'] = $this->uri->segment(6);
				$info['exam'] = $this->uri->segment(5);
				$info['class_name'] = $this->db->query("SELECT CLASS_NAME FROM class WHERE CLASS_ID=" . $info['class'])->row()->CLASS_NAME;
				$info['section_name'] = $this->db->query("SELECT SECTION_NAME FROM section WHERE SECTION_ID=" . $info['section'])->row()->SECTION_NAME;
				$info['subject_name'] = $this->db->query("SELECT SUBJECT_NAME FROM subject WHERE SUBJECT_ID=" . $info['subject'])->row()->SUBJECT_NAME;
				$info['exam_name'] = $this->db->query("SELECT EXAM_NAME FROM exam WHERE EXAM_ID=" . $info['exam'])->row()->EXAM_NAME;

				// dd($info);

				if( !isset($info['edit']) ){
					$info['edit'] = array(
						'MARK_ID' => null,
						'STUDENT_DATA_ID'=>null,
						'ROLL_NO'=>null,
						'MARK_OBTAINED'=>null,
						'COMMENT' => null
					);
				}
				$this->load->library('form_validation');
				$this->form_validation->set_rules('student_data_id[]','Student name','required|trim|xss_clean');
				$this->form_validation->set_rules('mark_obtained[]','Mark obtained','required|trim|xss_clean');
				$this->form_validation->set_rules('comment[]','comment','trim|xss_clean|max_length[300]');
				 //dd($info);
				
				if( !$this->form_validation->run() ){
					$this->load->view('admin/insert_data', $info);
					return FALSE;
				}

				# get input post
				$input = $this->webspice->get_input('mark_id');

				// dd($input);
				//die();
				
				# remove cache
				$this->webspice->remove_cache('mark');
				
				#insert grade

				$sql = "
				INSERT INTO marks
				(CLASS_ID, SECTION_ID, STUDENT_ID, ROLL_NO, SUBJECT_ID, EXAM_ID, MARK_OBTAINED, YEAR, COMMENT, CREATED_BY,CREATED_DATE,STATUS)
				VALUES
				( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 7 )";

				for($i=0; $i<count($input->student_data_id); $i++) {
					$this->db->query($sql, array($input->class_id, $input->section_id, $input->student_data_id[$i],  $input->roll_no[$i], $input->subject_id, $input->exam_id, $input->mark_obtained[$i], date("Y"), $input->comment[$i], $this->webspice->get_user_id(),$this->webspice->now()));

					if( !$this->db->insert_id() ){
						$this->webspice->message_board('We could not execute your request. Please tray again later or report to authority.');
						$this->webspice->force_redirect($url_prefix . 'admin');
						return false;
					}
				}

				$this->webspice->message_board('Record inserted successfully!');
				if($this->webspice->permission_verify('manage_marks',TRUE)){
					$this->webspice->force_redirect($url_prefix . 'manage_marks');
					return FALSE;
				}
				$this->webspice->force_redirect($url_prefix.'insert_marks');

				return false;

			break;

			case 'view_details':
				$data = array();
				$section_id = $this->webspice->encrypt_decrypt($this->uri->segment(3), 'decrypt');
				$exam_id = $this->webspice->encrypt_decrypt($this->uri->segment(4), 'decrypt');
				$subject_id = $this->webspice->encrypt_decrypt($this->uri->segment(5), 'decrypt');
				$year = $this->webspice->encrypt_decrypt($this->uri->segment(6), 'decrypt');

				$data['get_record'] = $this->db->query("SELECT  m.*, c.CLASS_NAME, s.SECTION_NAME, sub.SUBJECT_NAME, e.EXAM_NAME, si.NAME, sd.ROLL_NO FROM marks AS m INNER JOIN class AS c ON c.CLASS_ID=m.CLASS_ID INNER JOIN section As s ON s.SECTION_ID=m.SECTION_ID INNER JOIN subject AS sub ON sub.SUBJECT_ID=m.SUBJECT_ID INNER JOIN exam AS e ON e.EXAM_ID=m.EXAM_ID INNER JOIN student_info AS si ON si.STUDENT_ID = m.STUDENT_ID INNER JOIN student_data As sd ON sd.STUDENT_ID = m.STUDENT_ID WHERE m.SECTION_ID='".$section_id."' AND m.EXAM_ID='".$exam_id."' AND m.SUBJECT_ID ='".$subject_id."' AND m.YEAR =". $year)->result();

				$data['get_record'] = $this->db->query("SELECT  m.*, c.CLASS_NAME, s.SECTION_NAME, sub.SUBJECT_NAME, e.EXAM_NAME, si.NAME, sd.ROLL_NO FROM marks AS m INNER JOIN class AS c ON c.CLASS_ID=m.CLASS_ID INNER JOIN section As s ON s.SECTION_ID=m.SECTION_ID INNER JOIN subject AS sub ON sub.SUBJECT_ID=m.SUBJECT_ID INNER JOIN exam AS e ON e.EXAM_ID=m.EXAM_ID INNER JOIN student_data AS sd ON sd.STUDENT_DATA_ID = m.STUDENT_ID INNER JOIN student_info AS si ON si.STUDENT_ID = sd.STUDENT_ID WHERE m.SECTION_ID='".$section_id."' AND m.EXAM_ID='".$exam_id."' AND m.SUBJECT_ID ='".$subject_id."' AND m.YEAR =". $year)->result();

				// dd($data);

				$this->load->view('admin/view_details_marks', $data);
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
			$data['pager'] = $this->webspice->pager( count($count_data), $no_of_record, $page_index, $url_prefix.'manage_marks/page/', 10 );	
		}

		$_SESSION['sql'] = $sql;
		$_SESSION['filter_by'] = $filter_by;
		$result = $this->db->query($sql)->result();

		$data['get_record'] = $result;
		$data['filter_by'] = $filter_by;

		// dd($data);

		$this->load->view('admin/manage_marks', $data);

	}

	public function student_marksheet() {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'student_marksheet');
		$this->webspice->permission_verify('student_marksheet');

		if($this->input->post("filter")){
			$data = array();

			$class_id = $this->input->post("class_id");
			$section_id = $this->input->post("section_id");
			$student_id = $this->input->post("student_id");
			$exam_id = $this->input->post("exam_id");
			$year = $this->input->post("year");

			// dd($_POST);


			//$data['get_record'] = $this->db->query("SELECT m.*, c.CLASS_NAME, si.NAME, s.SECTION_NAME, e.EXAM_NAME, sub.SUBJECT_NAME FROM marks AS m INNER JOIN class AS c ON m.CLASS_ID=c.CLASS_ID INNER JOIN section AS s ON s.SECTION_ID=m.SECTION_ID INNER JOIN exam AS e ON e.EXAM_ID=m.EXAM_ID INNER JOIN subject AS sub ON m.SUBJECT_ID=sub.SUBJECT_ID INNER JOIN student_info AS si ON si.STUDENT_ID=m.STUDENT_ID WHERE m.CLASS_ID='".$class_id."' AND m.SECTION_ID='".$section_id."' AND m.EXAM_ID='".$exam_id."' AND m.STUDENT_ID='".$student_id."' AND YEAR='".$year."'")->result();

			$data['get_record'] = $this->db->query("SELECT m.*, c.CLASS_NAME, si.NAME, s.SECTION_NAME, e.EXAM_NAME, sub.SUBJECT_NAME FROM marks AS m INNER JOIN class AS c ON m.CLASS_ID=c.CLASS_ID INNER JOIN section AS s ON s.SECTION_ID=m.SECTION_ID INNER JOIN exam AS e ON e.EXAM_ID=m.EXAM_ID INNER JOIN subject AS sub ON m.SUBJECT_ID=sub.SUBJECT_ID INNER JOIN student_data AS sd ON sd.STUDENT_DATA_ID=m.STUDENT_ID INNER JOIN student_info AS si ON si.STUDENT_ID=sd.STUDENT_ID WHERE m.CLASS_ID='".$class_id."' AND m.SECTION_ID='".$section_id."' AND m.EXAM_ID='".$exam_id."' AND m.STUDENT_ID='".$student_id."' AND m.YEAR='".$year."'")->result();
			// dd($data);

			// dd($data);
			if(count($data['get_record'])) {
				$data['class_name'] = $data['get_record'][0]->CLASS_NAME;
				$data['section_name'] = $data['get_record'][0]->SECTION_NAME;
				$data['student_name'] = $data['get_record'][0]->NAME;
			}else{
				$data = array();
			}

			$this->load->view('admin/student_marksheet', $data);
			return false;
		}
		else {
			$data = array();
			$this->load->view('admin/student_marksheet', $data);
		}

	}

	public function class_wise_marksheet() {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'class_wise_marksheet');
		$this->webspice->permission_verify('class_wise_marksheet');

		if($this->input->post("filter")){
			$data = array();

			$class_id = $this->input->post("class_id");
			// $section_id = $this->input->post("section_id");
			// $student_id = $this->input->post("student_id");
			$exam_id = $this->input->post("exam_id");
			$year = $this->input->post("year");

			// dd($_POST);

			$students = $this->db->query("SELECT si.NAME, si.STUDENT_ID, sd.STUDENT_DATA_ID, sd.CLASS_ID FROM student_info AS si INNER JOIN student_data AS sd ON si.STUDENT_ID=sd.STUDENT_ID WHERE sd.CLASS_ID='".$class_id."' AND sd.YEAR='".$year."'")->result();
			// dd($students);

			// $subjects = $this->db->query("SELECT SUBJECT_ID, SUBJECT_NAME FROM subject WHERE CLASS_ID='".$class_id."' ORDER BY SUBJECT_ID")->result();
			$subjects = $this->db->query("SELECT m.SUBJECT_ID, s.SUBJECT_NAME, s.SUBJECT_ID FROM marks AS m INNER JOIN subject AS s ON m.SUBJECT_ID=s.SUBJECT_ID WHERE m.CLASS_ID='".$class_id."' AND m.EXAM_ID='".$exam_id."' AND m.YEAR='".$year."' GROUP BY m.CLASS_ID, m.SUBJECT_ID ORDER BY m.SUBJECT_ID")->result();
			// dd($subjects);

			$has_data = $this->db->query("SELECT * FROM marks WHERE CLASS_ID='".$class_id."' AND EXAM_ID='".$exam_id."' AND YEAR=".$year)->result();

			$data = array();

			if(count($has_data)) {
				$data['students'] = $students;
				$data['subjects'] = $subjects;
				$data['exam_id'] = $exam_id;
				$data['class_id'] = $class_id;
				$data['year'] = $year;
			}
			else {
				$data['has_data_err'] = "There is no data to show";
			}

			
			$this->load->view('admin/report/class_wise_marksheet', $data);
			return false;
		}
		else {
			$data = array();
			$this->load->view('admin/report/class_wise_marksheet', $data);
		}

	}

	public function section_wise_marksheet() {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'section_wise_marksheet');
		$this->webspice->permission_verify('section_wise_marksheet');

		if($this->input->post("filter")){
			$data = array();

			$class_id = $this->input->post("class_id");
			$section_id = $this->input->post("section_id");
			$exam_id = $this->input->post("exam_id");
			$year = $this->input->post("year");

			// dd($_POST);

			$students = $this->db->query("SELECT si.NAME, si.STUDENT_ID, sd.STUDENT_DATA_ID, sd.CLASS_ID FROM student_info AS si INNER JOIN student_data AS sd ON si.STUDENT_ID=sd.STUDENT_ID WHERE sd.CLASS_ID='".$class_id."' AND sd.SECTION_ID=".$section_id)->result();
			// dd($students);

			// $subjects = $this->db->query("SELECT SUBJECT_ID, SUBJECT_NAME FROM subject WHERE CLASS_ID='".$class_id."' ORDER BY SUBJECT_ID")->result();
			$subjects = $this->db->query("SELECT m.SUBJECT_ID, s.SUBJECT_NAME, s.SUBJECT_ID FROM marks AS m INNER JOIN subject AS s ON m.SUBJECT_ID=s.SUBJECT_ID WHERE m.CLASS_ID='".$class_id."' GROUP BY m.CLASS_ID, m.SUBJECT_ID ORDER BY m.SUBJECT_ID")->result();
			// dd($subjects);

			$has_data = $this->db->query("SELECT * FROM marks WHERE CLASS_ID='".$class_id."' AND SECTION_ID='".$section_id."' AND EXAM_ID='".$exam_id."' AND YEAR=".$year)->result();

			$data = array();

			if(count($has_data)) {
				$data['students'] = $students;
				$data['subjects'] = $subjects;
				$data['exam_id'] = $exam_id;
				$data['class_id'] = $class_id;

				// data sent for finding height subject mark value
				$data['section_id'] = $section_id;
				$data['year'] = $year;
			}
			else {
				$data['has_data_err'] = "There is no data to show";
			}

			$this->load->view('admin/report/section_wise_marksheet', $data);
			return false;
		}
		else {
			$data = array();
			$this->load->view('admin/report/section_wise_marksheet', $data);
		}

	}

	public function student_name($student_data_id) {
		$name = $this->db->query("SELECT si.NAME FROM student_info AS si INNER JOIN student_data AS sd ON sd.STUDENT_ID=si.STUDENT_ID WHERE student_data_id=".$student_data_id)->row()->NAME;
		return $name;
	}

	public function class_name($id) {
		$class_name = $this->db->query("SELECT CLASS_NAME FROM class WHERE CLASS_ID='".$id."' LIMIT 1")->row()->CLASS_NAME;
		return $class_name;
	}

	public function section_name($id) {
		$section_name = $this->db->query("SELECT SECTION_NAME FROM section WHERE SECTION_ID='".$id."' LIMIT 1")->row()->SECTION_NAME;
		return $section_name;
	}

	// certificates
	public function create_testimonial() {
		// $this->load->view('admin/certificates/create_testimonial_backup');
		// $this->load->view('admin/certificates/create_transfer_certificate');
		// $this->load->view('admin/certificates/create_report_card');
		// $this->load->view('admin/certificates/create_mark_sheet');
		// return false;

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'create_testimonial');
		$this->webspice->permission_verify('create_testimonial');
		if( !isset($data['edit']) ){
			$data['edit'] = array(
				'CLASS_ID'=>null,
				'SECTION_ID'=>null,
				'STUDENT_DATA_ID'=>null,
				'SSC_ROLL_NO'=>null,
				'REGISTRATION_NO'=>null,
				'GPA'=>null,
				'SESSION'=>null,
				'GROUP'=>null
			);
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('class_id','Class name','required|trim|xss_clean');
		$this->form_validation->set_rules('section_id','Section name','required|trim|xss_clean');
		$this->form_validation->set_rules('student_id','Student name','required|trim|xss_clean');
		$this->form_validation->set_rules('group','Group','required|trim|xss_clean');
		$this->form_validation->set_rules('session','Session','required|trim|xss_clean');
		$this->form_validation->set_rules('ssc_roll_no','SSC roll no','required|trim|xss_clean');
		$this->form_validation->set_rules('registration_no','Registration no','required|trim|xss_clean');
		$this->form_validation->set_rules('gpa','GPA','required|trim|xss_clean');
		
		if( !$this->form_validation->run() ){
			$this->load->view('admin/certificates/create_testimonial', $data);
			return FALSE;
		}
		else {
			// dd($data);
			$my_data = array();
			$input = $this->webspice->get_input('certificate_id');
			$class = $input->class_id;
			$section = $input->section_id;
			$student_id = $input->student_id;
			$year = date("Y");
			// dd(gettype($year));
			// dd($input);
			$student_info = $this->db->query("SELECT SI.*, SD.* FROM student_info AS SI INNER JOIN student_data AS SD ON SD.STUDENT_ID=SI.STUDENT_ID WHERE SD.STUDENT_DATA_ID=".$student_id)->row();
			// dd($student_info);
			$my_data['student_name'] = $student_info->NAME;
			$my_data['father_name'] = $student_info->FATHER_NAME;
			$my_data['mother_name'] = $student_info->MOTHER_NAME;
			$my_data['gender'] = $student_info->GENDER;
			$my_data['student_id'] = $student_info->STUDENT_ID;
			$my_data['student_data_id'] = $student_info->STUDENT_DATA_ID;
			$my_data['class_id'] = $student_info->CLASS_ID;
			$my_data['section_id'] = $student_info->SECTION_ID;
			$my_data['roll_no'] = $student_info->ROLL_NO;
			$my_data['ssc_roll_no'] = $input->ssc_roll_no;
			$my_data['registration_no'] = $input->registration_no;
			$my_data['session'] = $input->session;
			$my_data['group'] = $input->group;
			$my_data['gpa'] = $input->gpa;
			$my_data = implode("|", $my_data);
			// dd($my_data);
			// dd($this->webspice->encrypt_decrypt($my_data, 'encrypt'));

			//$dublicate = $this->db->query("SELECT * FROM testimonials WHERE CLASS_ID='".$class."'  AND SECTION_ID='".$section."' AND STUDENT_DATA_ID='".$student_data_id."' AND YEAR='".$year."'")->result();
			// dd(count($dublicate));
			/*if(count($dublicate)) {
				$this->webspice->message_board('Sorry, You already create a testimonial for this student');
				$this->webspice->force_redirect($url_prefix.'create_testimonial');
				return false;
			}*/

			$this->webspice->force_redirect($url_prefix.'manage_testimonial/view_testimonial/'.$this->webspice->encrypt_decrypt($my_data, 'encrypt') );
			return false;
		}

	}

	public function manage_testimonial() {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'manage_marks');
		$this->webspice->permission_verify('manage_marks');

		$this->load->database();
		$orderby = ' ORDER BY m.CLASS_ID ASC ';
		$groupby = ' GROUP BY m.CLASS_ID, m.SECTION_ID,  m.SUBJECT_ID, m.EXAM_ID, m.YEAR';
		$where = '';
		$page_index = 0;
		$no_of_record = 20000000000000;
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
		SELECT  m.*, c.CLASS_NAME, s.SECTION_NAME, sub.SUBJECT_NAME, e.EXAM_NAME FROM marks AS m
		INNER JOIN class AS c ON c.CLASS_ID=m.CLASS_ID INNER JOIN section As s ON s.SECTION_ID=m.SECTION_ID
		INNER JOIN subject AS sub ON sub.SUBJECT_ID=m.SUBJECT_ID INNER JOIN exam AS e ON e.EXAM_ID=m.EXAM_ID ";


		# filtering records
		if( $this->input->post('filter') ){
			$result = $this->webspice->filter_generator(
				$TableName = 'marks',
				$InputField = array(),
				$Keyword = array('CLASS_ID'),
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



				$this->load->view('admin/print_exam',$data);
				return false;
				break;

			case 'edit':
				$class_id = $this->uri->segment(3);
				$section_id = $this->uri->segment(4);
				$exam_id = $this->uri->segment(5);
				$subject_id = $this->uri->segment(6);

				// dd($class_id);

				$info['class_name'] = $this->db->query("SELECT CLASS_NAME FROM class WHERE CLASS_ID=" . $class_id)->row()->CLASS_NAME;
				$info['section_name'] = $this->db->query("SELECT SECTION_NAME FROM section WHERE SECTION_ID=" . $section_id)->row()->SECTION_NAME;
				$info['subject_name'] = $this->db->query("SELECT SUBJECT_NAME FROM subject WHERE SUBJECT_ID=" . $subject_id)->row()->SUBJECT_NAME;
				$info['exam_name'] = $this->db->query("SELECT EXAM_NAME FROM exam WHERE EXAM_ID=" . $exam_id)->row()->EXAM_NAME;
				
				$info['exam_data'] = $this->db->query("SELECT m.*, si.NAME FROM marks AS m INNER JOIN student_info AS si ON m.STUDENT_ID=si.STUDENT_ID WHERE m.CLASS_ID='".$class_id."' AND m.SECTION_ID='".$section_id."' AND m.EXAM_ID='".$exam_id."' AND m.SUBJECT_ID='".$subject_id."' ORDER BY ROLL_NO ASC")->result();

				$this->load->library('form_validation');
				// $this->form_validation->set_rules('student_data_id[]','Student name','required|trim|xss_clean');
				$this->form_validation->set_rules('mark_obtained[]','Mark obtained','required|trim|xss_clean');
				$this->form_validation->set_rules('comment[]','comment','trim|xss_clean|max_length[300]');
				// dd($info);
				
				if( !$this->form_validation->run() ){
					$this->load->view('admin/edit_insert_data', $info);
					return FALSE;
				}

				# get input post
				$input = $this->webspice->get_input('mark_edit_id');
				// dd($input);
				
				# remove cache
				$this->webspice->remove_cache('mark');

				# update process

				$sql = "
				UPDATE marks SET MARK_OBTAINED=?, COMMENT=?, UPDATED_BY=?,UPDATED_DATE=?
				WHERE MARK_ID=?";
				for($i=0; $i<count($input->mark_id); $i++) {
					$this->db->query($sql, array($input->mark_obtained[$i], $input->comment[$i], $this->webspice->get_user_id(),$this->webspice->now(), $input->mark_id[$i]));
				}
				$this->webspice->message_board('Exam marks updated successfully');
				$this->webspice->log_me('insert_mark_updated - '.$this->webspice->get_user_id()); # log activities
				$this->webspice->force_redirect($url_prefix.'manage_marks');

				return false;
				break;

			case 'inactive':
				$this->webspice->action_executer($TableName='grade', $KeyField='	GRADE_ID', $key, $RedirectURL='manage_marks', $PermissionName='manage_marks', $StatusCheck=7, $ChangeStatus=-7, $RemoveCache='grade', $Log='inactive_grade');
				return false;
				break;

			case 'active':
				$this->webspice->action_executer($TableName='grade', $KeyField='	GRADE_ID', $key, $RedirectURL='manage_marks', $PermissionName='manage_marks', $StatusCheck=-7, $ChangeStatus=7, $RemoveCache='grade', $Log='active_grade');
				return false;
				break;

			case 'delete':
				$id = $this->webspice->encrypt_decrypt($key, 'decrypt');
				$sql = $this->db->query("DELETE FROM grade WHERE 	GRADE_ID='".$id."' LIMIT 1");
				if($sql) {
					$this->webspice->force_redirect($url_prefix.'manage_marks');
				}
				return false;
			break;

			case 'print_testimonial':
				$info = array();
				$data = $this->webspice->encrypt_decrypt($key, 'decrypt');
				$data = explode("|", $data);
				// dd($data);
				$info['student_name'] = $data[0];
				$info['father_name'] = $data[1];
				$info['mother_name'] = $data[2];
				$info['gender'] = $data[3];
				$info['student_id'] = $data[4];
				$info['student_data_id'] = $data[5];
				$info['class_id'] = $data[6];
				$info['section_id'] = $data[7];
				$info['roll_no'] = $data[8];
				$info['ssc_roll_no'] = $data[9];
				$info['registration_no'] = $data[10];
				$info['session'] = $data[11];
				$info['group'] = $data[12];
				$info['gpa'] = $data[13];
				$this->load->view('admin/certificates/print_testimonial', $info);
				return false;
			break;

			case 'view_testimonial':
				$info = array();
				$data = $this->webspice->encrypt_decrypt($key, 'decrypt');
				$data = explode("|", $data);
				// dd($data);
				$info['student_name'] = $data[0];
				$info['father_name'] = $data[1];
				$info['mother_name'] = $data[2];
				$info['gender'] = $data[3];
				$info['student_id'] = $data[4];
				$info['student_data_id'] = $data[5];
				$info['class_id'] = $data[6];
				$info['section_id'] = $data[7];
				$info['roll_no'] = $data[8];
				$info['ssc_roll_no'] = $data[9];
				$info['registration_no'] = $data[10];
				$info['session'] = $data[11];
				$info['group'] = $data[12];
				$info['gpa'] = $data[13];
				
				$this->load->view('admin/certificates/testimonial_view', $info);

				return false;

				if( !isset($info['edit']) ){
					$info['edit'] = array(
						'MARK_ID' => null,
						'STUDENT_DATA_ID'=>null,
						'ROLL_NO'=>null,
						'MARK_OBTAINED'=>null,
						'COMMENT' => null
					);
				}
				$this->load->library('form_validation');
				$this->form_validation->set_rules('student_data_id[]','Student name','required|trim|xss_clean');
				$this->form_validation->set_rules('mark_obtained[]','Mark obtained','required|trim|xss_clean');
				$this->form_validation->set_rules('comment[]','comment','trim|xss_clean|max_length[300]');
				 //dd($info);
				
				if( !$this->form_validation->run() ){
					$this->load->view('admin/insert_data', $info);
					return FALSE;
				}

				# get input post
				$input = $this->webspice->get_input('mark_id');

				// dd($input);
				//die();
				
				# remove cache
				$this->webspice->remove_cache('mark');
				
				#insert grade

				$sql = "
				INSERT INTO marks
				(CLASS_ID, SECTION_ID, STUDENT_ID, ROLL_NO, SUBJECT_ID, EXAM_ID, MARK_OBTAINED, YEAR, COMMENT, CREATED_BY,CREATED_DATE,STATUS)
				VALUES
				( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 7 )";

				for($i=0; $i<count($input->student_data_id); $i++) {
					$this->db->query($sql, array($input->class_id, $input->section_id, $input->student_data_id[$i],  $input->roll_no[$i], $input->subject_id, $input->exam_id, $input->mark_obtained[$i], date("Y"), $input->comment[$i], $this->webspice->get_user_id(),$this->webspice->now()));

					if( !$this->db->insert_id() ){
						$this->webspice->message_board('We could not execute your request. Please tray again later or report to authority.');
						$this->webspice->force_redirect($url_prefix . 'admin');
						return false;
					}
				}

				$this->webspice->message_board('Record inserted successfully!');
				if($this->webspice->permission_verify('manage_marks',TRUE)){
					$this->webspice->force_redirect($url_prefix . 'manage_marks');
					return FALSE;
				}
				$this->webspice->force_redirect($url_prefix.'insert_marks');

				return false;

			break;

			case 'view_details':
				$data = array();
				$section_id = $this->webspice->encrypt_decrypt($this->uri->segment(3), 'decrypt');
				$exam_id = $this->webspice->encrypt_decrypt($this->uri->segment(4), 'decrypt');
				$subject_id = $this->webspice->encrypt_decrypt($this->uri->segment(5), 'decrypt');
				$year = $this->webspice->encrypt_decrypt($this->uri->segment(6), 'decrypt');

				$data['get_record'] = $this->db->query("SELECT  m.*, c.CLASS_NAME, s.SECTION_NAME, sub.SUBJECT_NAME, e.EXAM_NAME, si.NAME, sd.ROLL_NO FROM marks AS m INNER JOIN class AS c ON c.CLASS_ID=m.CLASS_ID INNER JOIN section As s ON s.SECTION_ID=m.SECTION_ID INNER JOIN subject AS sub ON sub.SUBJECT_ID=m.SUBJECT_ID INNER JOIN exam AS e ON e.EXAM_ID=m.EXAM_ID INNER JOIN student_info AS si ON si.STUDENT_ID = m.STUDENT_ID INNER JOIN student_data As sd ON sd.STUDENT_ID = m.STUDENT_ID WHERE m.SECTION_ID='".$section_id."' AND m.EXAM_ID='".$exam_id."' AND m.SUBJECT_ID ='".$subject_id."' AND m.YEAR =". $year)->result();

				$data['get_record'] = $this->db->query("SELECT  m.*, c.CLASS_NAME, s.SECTION_NAME, sub.SUBJECT_NAME, e.EXAM_NAME, si.NAME, sd.ROLL_NO FROM marks AS m INNER JOIN class AS c ON c.CLASS_ID=m.CLASS_ID INNER JOIN section As s ON s.SECTION_ID=m.SECTION_ID INNER JOIN subject AS sub ON sub.SUBJECT_ID=m.SUBJECT_ID INNER JOIN exam AS e ON e.EXAM_ID=m.EXAM_ID INNER JOIN student_data AS sd ON sd.STUDENT_DATA_ID = m.STUDENT_ID INNER JOIN student_info AS si ON si.STUDENT_ID = sd.STUDENT_ID WHERE m.SECTION_ID='".$section_id."' AND m.EXAM_ID='".$exam_id."' AND m.SUBJECT_ID ='".$subject_id."' AND m.YEAR =". $year)->result();

				// dd($data);

				$this->load->view('admin/view_details_marks', $data);
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
			$data['pager'] = $this->webspice->pager( count($count_data), $no_of_record, $page_index, $url_prefix.'manage_marks/page/', 10 );	
		}

		$_SESSION['sql'] = $sql;
		$_SESSION['filter_by'] = $filter_by;
		$result = $this->db->query($sql)->result();

		$data['get_record'] = $result;
		$data['filter_by'] = $filter_by;

		// dd($data);

		$this->load->view('admin/manage_marks', $data);

	}

	public function create_transfer_certificate() {
		// $this->load->view('admin/certificates/transfer_view');
		// return false;

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'create_transfer_certificate');
		$this->webspice->permission_verify('create_transfer_certificate');
		if( !isset($data['edit']) ){
			$data['edit'] = array(
				'CLASS_ID'=>null,
				'SECTION_ID'=>null,
				'STUDENT_DATA_ID'=>null,
				'POST_OFFICE'=>null,
				'POLICE_STATION'=>null,
				'DISTRICT'=>null,
				'RELEASE_DATE'=>null
			);
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('class_id','Class name','required|trim|xss_clean');
		$this->form_validation->set_rules('section_id','Section name','required|trim|xss_clean');
		$this->form_validation->set_rules('student_id','Student name','required|trim|xss_clean');
		$this->form_validation->set_rules('post_office','Post office','required|trim|xss_clean');
		$this->form_validation->set_rules('district','District','required|trim|xss_clean');
		$this->form_validation->set_rules('police_station','Police station','required|trim|xss_clean');
		$this->form_validation->set_rules('release_date','Release date','required|trim|xss_clean');
		
		if( !$this->form_validation->run() ){
			$this->load->view('admin/certificates/create_transfer_certificate', $data);
			return FALSE;
		}
		else {
			// dd($data);
			$my_data = array();
			$input = $this->webspice->get_input('certificate_id');
			$class = $input->class_id;
			$section = $input->section_id;
			$student_id = $input->student_id;
			$year = date("Y");
			// dd(gettype($year));
			// dd($input);
			$student_info = $this->db->query("SELECT SI.*, SD.* FROM student_info AS SI INNER JOIN student_data AS SD ON SD.STUDENT_ID=SI.STUDENT_ID WHERE SD.STUDENT_DATA_ID=".$student_id)->row();
			// dd($student_info);
			$my_data['student_name'] = $student_info->NAME;
			$my_data['father_name'] = $student_info->FATHER_NAME;
			$my_data['mother_name'] = $student_info->MOTHER_NAME;
			$my_data['gender'] = $student_info->GENDER;
			$my_data['student_id'] = $student_info->STUDENT_ID;
			$my_data['student_data_id'] = $student_info->STUDENT_DATA_ID;
			$my_data['class_id'] = $student_info->CLASS_ID;
			$my_data['section_id'] = $student_info->SECTION_ID;
			$my_data['roll_no'] = $student_info->ROLL_NO;
			$my_data['address'] = $student_info->ADDRESS;
			$my_data['birthday'] = $student_info->BIRTHDAY;
			$my_data['post_office'] = $input->post_office;
			$my_data['district'] = $input->district;
			$my_data['police_station'] = $input->police_station;
			$my_data['release_date'] = $input->release_date;
			// dd($my_data);
			$my_data = implode("|", $my_data);
			// dd($my_data);
			// dd($this->webspice->encrypt_decrypt($my_data, 'encrypt'));

			//$dublicate = $this->db->query("SELECT * FROM testimonials WHERE CLASS_ID='".$class."'  AND SECTION_ID='".$section."' AND STUDENT_DATA_ID='".$student_data_id."' AND YEAR='".$year."'")->result();
			// dd(count($dublicate));
			/*if(count($dublicate)) {
				$this->webspice->message_board('Sorry, You already create a testimonial for this student');
				$this->webspice->force_redirect($url_prefix.'create_testimonial');
				return false;
			}*/

			$this->webspice->force_redirect($url_prefix.'manage_transfer_certificate/transfer_view/'.$this->webspice->encrypt_decrypt($my_data, 'encrypt') );
			return false;
		}

	}

	public function manage_transfer_certificate() {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'manage_marks');
		$this->webspice->permission_verify('manage_marks');

		$this->load->database();
		$orderby = ' ORDER BY m.CLASS_ID ASC ';
		$groupby = ' GROUP BY m.CLASS_ID, m.SECTION_ID,  m.SUBJECT_ID, m.EXAM_ID, m.YEAR';
		$where = '';
		$page_index = 0;
		$no_of_record = 20000000000000;
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
		SELECT  m.*, c.CLASS_NAME, s.SECTION_NAME, sub.SUBJECT_NAME, e.EXAM_NAME FROM marks AS m
		INNER JOIN class AS c ON c.CLASS_ID=m.CLASS_ID INNER JOIN section As s ON s.SECTION_ID=m.SECTION_ID
		INNER JOIN subject AS sub ON sub.SUBJECT_ID=m.SUBJECT_ID INNER JOIN exam AS e ON e.EXAM_ID=m.EXAM_ID ";


		# filtering records
		if( $this->input->post('filter') ){
			$result = $this->webspice->filter_generator(
				$TableName = 'marks',
				$InputField = array(),
				$Keyword = array('CLASS_ID'),
				$AdditionalWhere = null,
				$DateBetween = null
			);

			$result['where'] ? $where = $result['where'] : $where=$where;
			$result['filter'] ? $filter_by = $result['filter'] : $filter_by=$filter_by;
		}

		# action area
		switch ($criteria) {

			case 'print_tc':
				$info = array();
				$data = $this->webspice->encrypt_decrypt($key, 'decrypt');
				$data = explode("|", $data);
				// dd($data);
				$info['student_name'] = $data[0];
				$info['father_name'] = $data[1];
				$info['mother_name'] = $data[2];
				$info['gender'] = $data[3];
				$info['student_id'] = $data[4];
				$info['student_data_id'] = $data[5];
				$info['class_id'] = $data[6];
				$info['section_id'] = $data[7];
				$info['roll_no'] = $data[8];
				$info['address'] = $data[9];
				$info['birthday'] = $data[10];
				$info['post_office'] = $data[11];
				$info['district'] = $data[12];
				$info['police_station'] = $data[13];
				$info['release_date'] = $data[14];
				$this->load->view('admin/certificates/print_tc', $info);
				return false;
			break;

			case 'transfer_view':
				$info = array();
				$data = $this->webspice->encrypt_decrypt($key, 'decrypt');
				$data = explode("|", $data);
				// dd($data);
				$info['student_name'] = $data[0];
				$info['father_name'] = $data[1];
				$info['mother_name'] = $data[2];
				$info['gender'] = $data[3];
				$info['student_id'] = $data[4];
				$info['student_data_id'] = $data[5];
				$info['class_id'] = $data[6];
				$info['section_id'] = $data[7];
				$info['roll_no'] = $data[8];
				$info['address'] = $data[9];
				$info['birthday'] = $data[10];
				$info['post_office'] = $data[11];
				$info['district'] = $data[12];
				$info['police_station'] = $data[13];
				$info['release_date'] = $data[14];
				
				$this->load->view('admin/certificates/transfer_view', $info);

				return false;

				if( !isset($info['edit']) ){
					$info['edit'] = array(
						'MARK_ID' => null,
						'STUDENT_DATA_ID'=>null,
						'ROLL_NO'=>null,
						'MARK_OBTAINED'=>null,
						'COMMENT' => null
					);
				}
				$this->load->library('form_validation');
				$this->form_validation->set_rules('student_data_id[]','Student name','required|trim|xss_clean');
				$this->form_validation->set_rules('mark_obtained[]','Mark obtained','required|trim|xss_clean');
				$this->form_validation->set_rules('comment[]','comment','trim|xss_clean|max_length[300]');
				 //dd($info);
				
				if( !$this->form_validation->run() ){
					$this->load->view('admin/insert_data', $info);
					return FALSE;
				}

				# get input post
				$input = $this->webspice->get_input('mark_id');

				// dd($input);
				//die();
				
				# remove cache
				$this->webspice->remove_cache('mark');
				
				#insert grade

				$sql = "
				INSERT INTO marks
				(CLASS_ID, SECTION_ID, STUDENT_ID, ROLL_NO, SUBJECT_ID, EXAM_ID, MARK_OBTAINED, YEAR, COMMENT, CREATED_BY,CREATED_DATE,STATUS)
				VALUES
				( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 7 )";

				for($i=0; $i<count($input->student_data_id); $i++) {
					$this->db->query($sql, array($input->class_id, $input->section_id, $input->student_data_id[$i],  $input->roll_no[$i], $input->subject_id, $input->exam_id, $input->mark_obtained[$i], date("Y"), $input->comment[$i], $this->webspice->get_user_id(),$this->webspice->now()));

					if( !$this->db->insert_id() ){
						$this->webspice->message_board('We could not execute your request. Please tray again later or report to authority.');
						$this->webspice->force_redirect($url_prefix . 'admin');
						return false;
					}
				}

				$this->webspice->message_board('Record inserted successfully!');
				if($this->webspice->permission_verify('manage_marks',TRUE)){
					$this->webspice->force_redirect($url_prefix . 'manage_marks');
					return FALSE;
				}
				$this->webspice->force_redirect($url_prefix.'insert_marks');

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
			$data['pager'] = $this->webspice->pager( count($count_data), $no_of_record, $page_index, $url_prefix.'manage_marks/page/', 10 );	
		}

		$_SESSION['sql'] = $sql;
		$_SESSION['filter_by'] = $filter_by;
		$result = $this->db->query($sql)->result();

		$data['get_record'] = $result;
		$data['filter_by'] = $filter_by;

		// dd($data);

		$this->load->view('admin/manage_marks', $data);

	}

	public function student_report_card() {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'student_report_card');
		$this->webspice->permission_verify('student_report_card');
		if( !isset($data['edit']) ){
			$data['edit'] = array(
				'CLASS_ID'=>null,
				'SECTION_ID'=>null,
				'STUDENT_DATA_ID'=>null
			);
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('class_id', 'Class name','required|trim|xss_clean');
		$this->form_validation->set_rules('section_id', 'Section name','required|trim|xss_clean');
		$this->form_validation->set_rules('student_id', 'Student name','required|trim|xss_clean');
		$this->form_validation->set_rules('exam_id', 'Exam name','required|trim|xss_clean');
		$this->form_validation->set_rules('year', 'Year','required|trim|xss_clean');
		
		if( !$this->form_validation->run() ){
			$this->load->view('admin/certificates/student_report_card', $data);
			return FALSE;
		}
		else {

			$data = array();

			$class_id = $this->input->post("class_id");
			$section_id = $this->input->post("section_id");
			$student_id = $this->input->post("student_id");
			$exam_id = $this->input->post("exam_id");
			$year = $this->input->post("year");


			$data['get_record'] = $this->db->query("SELECT m.*, c.CLASS_NAME, si.NAME, s.SECTION_NAME, e.EXAM_NAME, sub.SUBJECT_NAME FROM marks AS m INNER JOIN class AS c ON m.CLASS_ID=c.CLASS_ID INNER JOIN section AS s ON s.SECTION_ID=m.SECTION_ID INNER JOIN exam AS e ON e.EXAM_ID=m.EXAM_ID INNER JOIN subject AS sub ON m.SUBJECT_ID=sub.SUBJECT_ID INNER JOIN student_data AS sd ON sd.STUDENT_DATA_ID=m.STUDENT_ID INNER JOIN student_info AS si ON si.STUDENT_ID=sd.STUDENT_ID WHERE m.CLASS_ID='".$class_id."' AND m.SECTION_ID='".$section_id."' AND m.EXAM_ID='".$exam_id."' AND m.STUDENT_ID='".$student_id."' AND m.YEAR='".$year."'")->result();
			// dd($data);

			// dd($data);
			if(count($data['get_record'])) {
				$data['class_name'] = $data['get_record'][0]->CLASS_NAME;
				$data['section_name'] = $data['get_record'][0]->SECTION_NAME;
				$data['student_name'] = $data['get_record'][0]->NAME;
				$data['roll_no'] = $data['get_record'][0]->ROLL_NO;
				$data['year'] = $year;
				$data['total_sub'] = $this->db->query("SELECT COUNT(*) AS COUNT FROM subject WHERE CLASS_ID=".$class_id)->row()->COUNT;
				
				// additional data for print option
				$data['class_id'] = $class_id;
				$data['section_id'] = $section_id;
				$data['student_id'] = $student_id;
				$data['exam_id'] = $exam_id;
				$data['year'] = $year;

			}else{
				$data = array();
				$data['has_data_err'] = "Sorry, There is no data to show";
				$this->load->view('admin/certificates/student_report_card', $data);
				return false;
			}

			$this->load->view('admin/certificates/student_report_card_view', $data);
			return false;

			$this->webspice->force_redirect($url_prefix.'manage_testimonial/view_testimonial/'.$this->webspice->encrypt_decrypt($my_data, 'encrypt') );
			return false;
		}

	}

	public function print_student_report_card() {
		$info = array();
		$key = $this->uri->segment(3);
		$data = $this->webspice->encrypt_decrypt($key, 'decrypt');
		$data = explode("|", $data);
		// dd($data);
		$info['class_name'] = $data[0];
		$info['section_name'] = $data[1];
		$info['student_name'] = $data[2];
		$info['year'] = $data[3];
		$info['total_sub'] = $data[4];
		$info['class_id'] = $data[5];
		$info['section_id'] = $data[6];
		$info['student_id'] = $data[7];
		$info['exam_id'] = $data[8];
		$info['roll_no'] = $data[9];
		$this->load->view('admin/certificates/print_report_card', $info);
		return false;
	}

	public function student_mark_sheet() {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'student_mark_sheet');
		$this->webspice->permission_verify('student_mark_sheet');
		if( !isset($data['edit']) ){
			$data['edit'] = array(
				'CLASS_ID'=>null,
				'SECTION_ID'=>null,
				'STUDENT_DATA_ID'=>null
			);
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('class_id', 'Class name','required|trim|xss_clean');
		$this->form_validation->set_rules('section_id', 'Section name','required|trim|xss_clean');
		$this->form_validation->set_rules('student_id', 'Student name','required|trim|xss_clean');
		$this->form_validation->set_rules('exam_id', 'Exam name','required|trim|xss_clean');
		$this->form_validation->set_rules('year', 'Year','required|trim|xss_clean');
		
		if( !$this->form_validation->run() ){
			$this->load->view('admin/certificates/student_mark_sheet', $data);
			return FALSE;
		}
		else {

			$data = array();

			$class_id = $this->input->post("class_id");
			$section_id = $this->input->post("section_id");
			$student_id = $this->input->post("student_id");
			$exam_id = $this->input->post("exam_id");
			$year = $this->input->post("year");


			$data['get_record'] = $this->db->query("SELECT m.*, c.CLASS_NAME, si.NAME, s.SECTION_NAME, e.EXAM_NAME, sub.SUBJECT_NAME FROM marks AS m INNER JOIN class AS c ON m.CLASS_ID=c.CLASS_ID INNER JOIN section AS s ON s.SECTION_ID=m.SECTION_ID INNER JOIN exam AS e ON e.EXAM_ID=m.EXAM_ID INNER JOIN subject AS sub ON m.SUBJECT_ID=sub.SUBJECT_ID INNER JOIN student_data AS sd ON sd.STUDENT_DATA_ID=m.STUDENT_ID INNER JOIN student_info AS si ON si.STUDENT_ID=sd.STUDENT_ID WHERE m.CLASS_ID='".$class_id."' AND m.SECTION_ID='".$section_id."' AND m.EXAM_ID='".$exam_id."' AND m.STUDENT_ID='".$student_id."' AND m.YEAR='".$year."'")->result();
			// dd($data);

			// dd($data);
			if(count($data['get_record'])) {
				$data['class_name'] = $data['get_record'][0]->CLASS_NAME;
				$data['section_name'] = $data['get_record'][0]->SECTION_NAME;
				$data['student_name'] = $data['get_record'][0]->NAME;
				$data['roll_no'] = $data['get_record'][0]->ROLL_NO;
				$data['year'] = $year;
				$data['total_sub'] = $this->db->query("SELECT COUNT(*) AS COUNT FROM subject WHERE CLASS_ID=".$class_id)->row()->COUNT;
				
				// additional data for print option
				$data['class_id'] = $class_id;
				$data['section_id'] = $section_id;
				$data['student_id'] = $student_id;
				$data['exam_id'] = $exam_id;
				$data['year'] = $year;

			}else{
				$data = array();
				$data['has_data_err'] = "Sorry, There is no data to show";
				$this->load->view('admin/certificates/student_mark_sheet', $data);
				return false;
			}

			$this->load->view('admin/certificates/student_mark_sheet_view', $data);
			return false;

			$this->webspice->force_redirect($url_prefix.'manage_testimonial/view_testimonial/'.$this->webspice->encrypt_decrypt($my_data, 'encrypt') );
			return false;
		}

	}

	public function print_student_mark_sheet() {
		$info = array();
		$key = $this->uri->segment(3);
		$data = $this->webspice->encrypt_decrypt($key, 'decrypt');
		$data = explode("|", $data);
		// dd($data);
		$info['class_name'] = $data[0];
		$info['section_name'] = $data[1];
		$info['student_name'] = $data[2];
		$info['year'] = $data[3];
		$info['total_sub'] = $data[4];
		$info['class_id'] = $data[5];
		$info['section_id'] = $data[6];
		$info['student_id'] = $data[7];
		$info['exam_id'] = $data[8];
		$info['roll_no'] = $data[9];
		$this->load->view('admin/certificates/print_report_card', $info);
		return false;
	}


	public function average_marksheet() {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'average_marksheet');
		$this->webspice->permission_verify('average_marksheet');

		if($this->input->post("filter")){
			$data = array();

			$class_id = $this->input->post("class_id");
			// $section_id = $this->input->post("section_id");
			// $student_id = $this->input->post("student_id");
			// $exam_id = $this->input->post("exam_id");
			$year = $this->input->post("year");

			// dd($_POST);

			$students = $this->db->query("SELECT si.NAME, si.STUDENT_ID, sd.STUDENT_DATA_ID, sd.CLASS_ID FROM student_info AS si INNER JOIN student_data AS sd ON si.STUDENT_ID=sd.STUDENT_ID WHERE sd.CLASS_ID='".$class_id."' AND sd.YEAR='".$year."'")->result();
			// dd($students);

			// $subjects = $this->db->query("SELECT SUBJECT_ID, SUBJECT_NAME FROM subject WHERE CLASS_ID='".$class_id."' ORDER BY SUBJECT_ID")->result();
			$subjects = $this->db->query("SELECT m.SUBJECT_ID, s.SUBJECT_NAME, s.SUBJECT_ID FROM marks AS m INNER JOIN subject AS s ON m.SUBJECT_ID=s.SUBJECT_ID WHERE m.CLASS_ID='".$class_id."' AND m.YEAR='".$year."' GROUP BY m.CLASS_ID, m.SUBJECT_ID ORDER BY m.SUBJECT_ID")->result();
			// dd($subjects);

			$exam_list = $this->db->query("SELECT DISTINCT EXAM_ID FROM marks WHERE CLASS_ID='".$class_id."' AND YEAR='".$year."'")->result();
			$all_list = array();
			foreach($exam_list as $exam) {
				$all_list[] = $exam->EXAM_ID;
			}
			$all_list_data = implode(",", $all_list);
			// dd($all_list_data);

			$has_data = $this->db->query("SELECT * FROM marks WHERE CLASS_ID='".$class_id."' AND EXAM_ID IN (".$all_list_data.") AND YEAR=".$year)->result();
			// dd($has_data);

			$data = array();

			if(count($has_data)) {
				$data['students'] = $students;
				$data['subjects'] = $subjects;
				$data['exam_id'] = $all_list_data;
				$data['class_id'] = $class_id;
				$data['year'] = $year;
				$data['all_list'] = $all_list;
			}
			else {
				$data['has_data_err'] = "There is no data to show";
			}
			// dd($data);

			
			$this->load->view('admin/report/average_marksheet', $data);
			return false;
		}
		else {
			$data = array();
			$this->load->view('admin/report/average_marksheet', $data);
		}

	}

	public function settings_data() {

		$url_prefix = $this->webspice->settings()->site_url_prefix;
		$this->webspice->user_verify($url_prefix.'login', $url_prefix.'settings_data');
		$this->webspice->permission_verify('settings_data');
		if( !isset($data['edit']) ){
			$data['edit'] = array(
				'CLASS_ID'=>null,
				'CLASS_NAME'=>null
			);
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('class_name','class name','required|trim|xss_clean|max_length[200]|min_length[3]');
		
		if( !$this->form_validation->run() ){
			$this->load->view('admin/settings/create_class', $data);
			return FALSE;
		}

		# get input post
		$input = $this->webspice->get_input('class_id');

		//dd($input);
		//die();

		#duplicate test
		$this->webspice->db_field_duplicate_test("SELECT * FROM class WHERE CLASS_NAME=?", array( $input->class_name), 'You are not allowed to enter duplicate class name', 'CLASS_ID', $input->class_id, $data, 'admin/settings/create_class');
		
		# remove cache
		$this->webspice->remove_cache('class');

		# update process
		if( $input->class_id ){

			$sql = "
			UPDATE class SET CLASS_NAME=?, UPDATED_BY=?,UPDATED_DATE=?
			WHERE CLASS_ID=?";
			$this->db->query($sql, array($input->class_name,$this->webspice->get_user_id(),$this->webspice->now(), $input->class_id));
			$this->webspice->message_board('Record has been updated!');
			$this->webspice->log_me('class_updated - '.$this->webspice->get_user_id()); # log activities
			$this->webspice->force_redirect($url_prefix.'manage_class');
			return false;
		}
		
		#insert category

		$sql = "
		INSERT INTO class
		(CLASS_NAME, CREATED_BY,CREATED_DATE,STATUS)
		VALUES
		( ?, ?, ?, 7)";
		$this->db->query($sql, array($input->class_name,
			$this->webspice->get_user_id(),$this->webspice->now()));

		if( !$this->db->insert_id() ){
			$this->webspice->message_board('We could not execute your request. Please tray again later or report to authority.');
			$this->webspice->force_redirect($url_prefix . 'admin');
			return false;
		}

		$this->webspice->message_board('Record inserted successfully!');
		if($this->webspice->permission_verify('manage_class',TRUE)){
			$this->webspice->force_redirect($url_prefix . 'manage_class');
			return FALSE;
		}
		$this->webspice->force_redirect($url_prefix.'create_class');

	}

}