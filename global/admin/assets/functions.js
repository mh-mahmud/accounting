//<!--
var url_prefix = '/accounting/'; /*should be start and end with / (slash)*/
$(document).ready(function(){
	init();
});



init = function(){
	common_functions();
	
	if( $("#header_container").length ){
		header_footer();
	}
	
	var page = $('#wrapper').find('.page_identifier').attr('id');
	console.log(page);
	switch(page){
		case 'page_index': page_index(); break;
		case 'page_create_success_story': page_create_success_story(); break;
		case 'page_create_upazila': page_create_upazila();break;
		case 'page_create_union': page_create_union();break;
		case 'page_create_news_archive': page_create_news_archive();break;
		case 'page_create_company' : page_create_company();break;
		case 'page_create_notice' : page_create_notice();break;
		case 'page_create_course' : page_create_course();break;
		case 'page_create_user'	: page_create_user();break;
		case 'page_create_training_area'	: page_create_training_area();break;
		case 'page_create_course_material' : page_create_course_material();break;


		// new page added
		case 'page_create_board_member': page_create_board_member(); break;
		case 'page_assign_subject': page_assign_subject(); break;
		case 'page_daily_attendance': page_daily_attendance(); break;
		case 'page_create_class_routine': page_create_class_routine(); break;
		case 'page_insert_marks': page_insert_marks(); break;
		case 'page_student_marksheet': page_student_marksheet(); break;
		case 'page_admin_student' : page_admin_student();break;
		case 'page_assign_teacher' : page_assign_teacher();break;
		case 'page_create_payment' : page_create_payment();break;
		case 'page_payment_report' : page_payment_report();break;
		case 'page_create_parent' : page_create_parent();break;
		case 'page_student_checkin' : page_student_checkin();break;
		case 'page_create_salary_payment' : page_create_salary_payment();break;
		case 'page_my_calendar' : page_my_calendar();break;
		case 'page_sms_to_parents' : page_sms_to_parents();break;
		case 'page_create_account_head' : page_create_account_head();break;
		case 'page_create_journal' : page_create_journal();break;
		case 'page_create_voucher' : page_create_voucher();break;
		case 'page_ledger' : page_ledger();break;


		//ROLL NO
		case 'page_student_wise_roll_no': page_student_wise_roll_no(); break;
		
	}
	
	$("input[type=submit]").click(function(){
		$('.chzn-select,#nic_editor').hide();
	});
};


common_functions = function(){
	/*action confirmation*/
	$('.confirmation').on('click', function(){
		if(confirm('Do you really want to perform this action?')){
			return true;
		}else{
			return false;	
		}
	});
	
	/*chosen select box*/
	if(typeof chosen == 'function'){
		$('.chzn-select').chosen();
	}
	
	/*date picker*/
/*	$('.date_time_picker_24').datetimepicker({
		format:'Y-m-d H:i',
		 allowTimes:[
		  '00:00', '00:30', '01:00', '01:30',
		  '02:00', '02:30', '03:00', '03:30',
		  '04:00', '04:30', '05:00', '05:30',
		  '06:00', '06:30', '07:00', '07:30',
		  '08:00', '08:30', '09:00', '09:30',
		  '10:00', '10:30', '11:00', '11:30',
		  '12:00', '12:30', '13:00', '13:30',
		  '14:00', '14:30', '15:00', '15:30',
		  '16:00', '16:30', '17:00', '17:30',
		  '18:00', '18:30', '19:00', '19:30',
		  '20:00', '20:30', '21:00', '21:30',
		  '22:00', '22:30', '23:00', '23:30'
 		]
	});*/
	
	// $('.date_picker').datetimepicker({timepicker:false,format:'Y-m-d'});
	
	
	/*meassge show and hide after few milli-second*/
	if( $("#message_board").length ){
		$('#message_board').delay(150000).hide('slow');	
	}
	
	$('.refresh').on('click', function(){
		location.href = location.href;
	});
	
	/*razuspopup - js script*/
	$('.razuspopup').each(function(){
		var posDirection = $('.razuspopupbtn', this).attr('data_pos');
		var objWindow = $('.razuspopupwindow', this);
	
		if(posDirection=='left'){
			objWindow.css('left','0px');
		}else if(posDirection=='right'){
			objWindow.css('right','0px');
		}else{
			objWindow.css('left', -(objWindow.width()/2)+'px' );
		}
	});
	$('.razuspopupbtn').click(function(){
		$(this).next().fadeIn('slow');
		return false;
	});
	$('.razuspopupwindow .btn_close').click(function(){
		$(this).parent().parent().fadeOut('slow');
		return false;
	});
	
	/*drop-down box -> for small screen*/
	$('.list_for_small_screen').change(function(){
		var get_title = $(this).val();
		if(get_title){
			location.href = get_title;
		}
	});
	
	/*custom place holder for input box*/
	$('.placeholder .caption').each(function(){
		var pre_obj = $(this).prev();
		var pos = pre_obj.position();
		$(this).css('top', (pos.top+4) );
		$(this).css('padding-top', pre_obj.css('padding-top'));
		$(this).css('padding-right', pre_obj.css('padding-right'));
		$(this).css('padding-bottom', pre_obj.css('padding-bottom'));
		$(this).css('padding-left', pre_obj.css('padding-left'));
	});



};

header_footer = function(){

};

page_index = function(){

};

page_assign_subject = function() {
	$('#add_product').click(function(e){

		e.preventDefault();
		$('.prodcut_price_new').clone(true).insertBefore('#pro_area').attr('class','new_main_product').removeAttr("style");

		$('.new_main_product input[type=select].list_subgroup').attr('required', true);
		$('.new_main_product input[type=text].list_subgroup2').attr('required', true);
		$('.new_main_product input[type=select].list_subgroup3').attr('required', true);
	});

	$("#remove_product").click(function(e) {
		e.preventDefault();
		$(".new_main_product").last().remove();
	});
}

page_daily_attendance = function() {
	$("#class_list").on( 'change', function() {
		var class_id = $(this).val();
		var token = $("#token").val();

		$.ajax({
			type: "POST",
			url: url_prefix + 'class_wise_section_list',
			data: {
				class_id:class_id,
				csrf_webspice_tkn:token
			},
			success: function(data) {
				console.log(data);
				$("#section_list").html(data);
			}
		});
	});

}

page_sms_to_parents = function() {
	$("#class_list").on( 'change', function() {
		var class_id = $(this).val();
		var token = $("#token").val();

		$.ajax({
			type: "POST",
			url: url_prefix + 'class_wise_section_list',
			data: {
				class_id:class_id,
				csrf_webspice_tkn:token
			},
			success: function(data) {
				console.log(data);
				$("#section_list").html(data);
			}
		});
	});

}

page_create_class_routine = function() {
	$("#class_list").on( 'change', function() {

		var class_id = $(this).val();
		var token = $("#token").val();

		// data load for section
		$.ajax({
			type: "POST",
			url: url_prefix + 'class_wise_section_list',
			data: {
				class_id:class_id,
				csrf_webspice_tkn:token
			},
			success: function(data) {
				console.log(data);
				$("#section_list").html(data);
			}
		});

		// data load for subject
		$.ajax({
			type: "POST",
			url: url_prefix + 'class_wise_subject_list',
			data: {
				class_id:class_id,
				csrf_webspice_tkn:token
			},
			success: function(data) {
				console.log(data);
				$("#subject_list").html(data);
			}
		});


	});
}

page_insert_marks = function() {
	$("#class_list").on( 'change', function() {

		var class_id = $(this).val();
		var token = $("#token").val();

		// data load for section
		$.ajax({
			type: "POST",
			url: url_prefix + 'class_wise_section_list',
			data: {
				class_id:class_id,
				csrf_webspice_tkn:token
			},
			success: function(data) {
				console.log(data);
				$("#section_list").html(data);
			}
		});

		// data load for subject
		$.ajax({
			type: "POST",
			url: url_prefix + 'class_wise_subject_list',
			data: {
				class_id:class_id,
				csrf_webspice_tkn:token
			},
			success: function(data) {
				console.log(data);
				$("#subject_list").html(data);
			}
		});


	});
}

page_student_marksheet = function() {
	$("#class_list").on( 'change', function() {
		var class_id = $(this).val();
		var token = $("#token").val();

		// data load for section
		$.ajax({
			type: "POST",
			url: url_prefix + 'class_wise_section_list',
			data: {
				class_id: class_id,
				csrf_webspice_tkn: token
			},
			success: function(data) {
				$("#section_list").html(data);
			}
		});

		// data load for student
		$.ajax({
			type: "POST",
			url: url_prefix + 'class_wise_student_list',
			data: {
				class_id: class_id,
				csrf_webspice_tkn: token
			},
			success: function(data) {
				$("#student_list").html(data);
			}
		});

	});

	$("#section_list").on( 'change', function() {
		var section_id = $(this).val();
		var token = $("#token").val();
		console.log(section_id);

		$.ajax({
			type: "POST",
			url: url_prefix + 'section_wise_student_list',
			data: {
				section_id: section_id,
				csrf_webspice_tkn: token
			},
			success: function(data) {
				$("#student_list").html(data);
			}
		});
	});
}

page_admin_student = function() {
	$("#class_list").on( 'change', function() {
		var class_id = $(this).val();
		var token = $("#token").val();

		// data load for section
		$.ajax({
			type: "POST",
			url: url_prefix + 'class_wise_section_list',
			data: {
				class_id: class_id,
				csrf_webspice_tkn: token
			},
			success: function(data) {
				$("#section_list").html(data);
			}
		});

	});
}

page_assign_teacher = function() {
	$("#class_list").on( 'change', function() {
		var class_id = $(this).val();
		var token = $("#token").val();

		// data load for section
		$.ajax({
			type: "POST",
			url: url_prefix + 'class_wise_section_list',
			data: {
				class_id: class_id,
				csrf_webspice_tkn: token
			},
			success: function(data) {
				$("#section_list").html(data);
			}
		});

	});

}

page_create_payment = function() {
	$("#class_list").on( 'change', function() {
		var class_id = $(this).val();
		var token = $("#token").val();

		// data load for section
		$.ajax({
			type: "POST",
			url: url_prefix + 'class_wise_section_list',
			data: {
				class_id: class_id,
				csrf_webspice_tkn: token
			},
			success: function(data) {
				$("#section_list").html(data);
			}
		});

		$.ajax({
			type: "POST",
			url: url_prefix + 'class_wise_payment_list',
			data: {
				class_id: class_id,
				csrf_webspice_tkn: token
			},
			success: function(data) {
				$("#payment_list").html(data);
			}
		});

	});

	$("#section_list").on( 'change', function() {
		console.log("Hello World");
		var section_id = $(this).val();
		var token = $("#token").val();
		console.log(section_id);

		$.ajax({
			type: "POST",
			url: url_prefix + 'section_wise_student_list',
			data: {
				section_id: section_id,
				csrf_webspice_tkn: token
			},
			success: function(data) {
				$("#student_list").html(data);
			}
		});
	});
}

page_create_account_head = function() {
	$("#group_head").on( 'change', function() {
		var group_name = $(this).val();
		var token = $("#token").val();

		$.ajax({
			type: "POST",
			url: url_prefix + 'group_wise_head_name',
			data: {
				group_name: group_name,
				csrf_webspice_tkn: token
			},
			success: function(data) {
				console.log(data);
				$("#sub_head").html(data);
			}
		});
	});
}

//To select account head name
function selectAccount(val) {
	console.log(val);
	$("#search-box").val(val);
	$("#suggesstion-box").hide();
}

function selectAccount2(val) {
	console.log(val);
	$("#search-box-2").val(val);
	$("#suggesstion-box-2").hide();
}

page_create_voucher = function() {

	// add or remove div
	$('#add_product').click(function(e){
		e.preventDefault();
		$('.prodcut_price_new').clone(true).insertBefore('#pro_area').attr('class','new_main_product').removeAttr("style");
		var bubu= $('select').attr('class');
		console.log(bubu);
	});

	$("#remove_product").click(function(e) {
		e.preventDefault();
		$(".new_main_product").last().remove();
	});

}

page_ledger = function() {

	var token = $("#token").val();
	$("#search-box").on( 'keyup', function() {
		var keyword = $(this).val();
		var id_name = $(this).attr("id");

		$.ajax({
			type: "POST",
			url: url_prefix + 'search_account_head',
			data: {
				keyword: keyword,
				id_name: id_name,
				csrf_webspice_tkn: token
			},
			success: function(data) {
				$("#suggesstion-box").show();
				$("#suggesstion-box").html(data);
				$("#search-box").css("background", "#FFF");
			}
		});
	});

}

page_create_journal = function() {
	$("#class_list").on( 'change', function() {
		var class_id = $(this).val();
		var token = $("#token").val();

		// data load for section
		$.ajax({
			type: "POST",
			url: url_prefix + 'class_wise_section_list',
			data: {
				class_id: class_id,
				csrf_webspice_tkn: token
			},
			success: function(data) {
				$("#section_list").html(data);
			}
		});

	});
	$("#teacher_list").on( 'change', function() {
		var teacher_id = $(this).val();
		var token = $("#token").val();
        
		// data load for section
		$.ajax({
			type: "POST",
			url: url_prefix + 'teacher_salary_check',
			data: {
				teacher_id: teacher_id,
				csrf_webspice_tkn: token
			},
			
			
			success: function(data) {
				console.log(data);
				$(".salary_month").html(data);
			}
		});

	});
	$("#student_list").on( 'change', function() {
		var student_id = $(this).val();
		var token = $("#token").val();
        
		// data load for section
		$.ajax({
			type: "POST",
			url: url_prefix + 'student_salary_check',
			data: {
				student_id: student_id,
				csrf_webspice_tkn: token
			},
			
			
			success: function(data) {
				console.log(data);
				$(".salary_month_student").html(data);
			}
		});

	});

	$("#section_list").on( 'change', function() {
		var section_id = $(this).val();
		var token = $("#token").val();

		$.ajax({
			type: "POST",
			url: url_prefix + 'section_wise_student_list_acc',
			data: {
				section_id: section_id,
				csrf_webspice_tkn: token
			},
			success: function(data) {
				$("#student_list").html(data);
			}
		});
	});

	var token = $("#token").val();
	$("#search-box").on( 'keyup', function() {
		var keyword = $(this).val();
		var id_name = $(this).attr("id");

		$.ajax({
			type: "POST",
			url: url_prefix + 'search_account_head',
			data: {
				keyword: keyword,
				id_name: id_name,
				csrf_webspice_tkn: token
			},
			success: function(data) {
				$("#suggesstion-box").show();
				$("#suggesstion-box").html(data);
				$("#search-box").css("background", "#FFF");
			}
		});
	});

	$("#search-box-2").on( 'keyup', function() {
		var keyword = $(this).val();
		var id_name = $(this).attr("id");

		$.ajax({
			type: "POST",
			url: url_prefix + 'search_account_head',
			data: {
				keyword: keyword,
				id_name: id_name,
				csrf_webspice_tkn: token
			},
			success: function(data) {
				$("#suggesstion-box-2").show();
				$("#suggesstion-box-2").html(data);
				$("#search-box-2").css("background", "#FFF");
			}
		});
	});

	// add or remove div
	$('#add_product').click(function(e){
		e.preventDefault();
		$('.prodcut_price_new').clone(true).insertBefore('#pro_area').attr('class','new_main_product').removeAttr("style");
	});
	$("#remove_product").click(function(e) {
		e.preventDefault();
		$(".new_main_product").last().remove();
	});

	// add or remove div 2
	$('#add_product_2').click(function(e){
		e.preventDefault();
		$('.prodcut_price_2_new').clone(true).insertBefore('#pro_area_2').attr('class','new_main_product_2').removeAttr("style");
	});
	$("#remove_product_2").click(function(e) {
		e.preventDefault();
		$(".new_main_product_2").last().remove();
	});
}


page_payment_report = function() {
	$("#class_list").on( 'change', function() {
		var class_id = $(this).val();
		var token = $("#token").val();

		// data load for section
		$.ajax({
			type: "POST",
			url: url_prefix + 'class_wise_section_list',
			data: {
				class_id: class_id,
				csrf_webspice_tkn: token
			},
			success: function(data) {
				$("#section_list").html(data);
			}
		});

	});

	$("#section_list").on( 'change', function() {
		console.log("Hello World");
		var section_id = $(this).val();
		var token = $("#token").val();
		console.log(section_id);

		$.ajax({
			type: "POST",
			url: url_prefix + 'section_wise_student_list',
			data: {
				section_id: section_id,
				csrf_webspice_tkn: token
			},
			success: function(data) {
				$("#student_list").html(data);
			}
		});
	});
}


page_create_parent = function () {
	$("#search-box").keyup(function(){
		var keyword = $(this).val();
		var token = $("#token").val();
		// console.log(token);

		$.ajax({
			type: "POST",
			url: url_prefix + "student_list_search",
			data: {
				keyword: keyword,
				csrf_webspice_tkn: token
			},
			beforeSend: function(){
				$("#search-box").css("background","#FFF no-repeat 165px");
			},
			success: function(data){
				$("#suggesstion-box").show();
				$("#suggesstion-box").html(data);
				$("#search-box").css("background","#FFF");
			}
		});
	});

}

page_student_checkin = function () {
	$("#hostel_list").on( 'change', function() {
		var house_id = $(this).val();
		var token = $("#token").val();

		$.ajax({
			type: "POST",
			url: url_prefix + 'house_wise_student_list',
			data: {
				house_id:house_id,
				csrf_webspice_tkn:token
			},
			success: function(data) {
				console.log(data);
				$("#student_list").html(data);
			}
		});
	});
}

page_create_salary_payment = function () {
	$("#teacher_list").on( 'change', function() {
		var teacher_id = $(this).val();
		var token = $("#token").val();

		$.ajax({
			type: "POST",
			url: url_prefix + 'teacher_wise_salary_list',
			data: {
				teacher_id:teacher_id,
				csrf_webspice_tkn:token
			},
			success: function(data) {
				console.log(data);
				$("#salary").html(data);
			}
		});
	});
}

page_my_calendar = function () {
    $(".calendar .day").click(function() {
      day_num = $(this).find('.day_num').html();
      day_data = prompt("Enter Stuff", $(this).find(".content").html());
      var location = url_prefix + 'display';

      if(day_data != null) {
        $.ajax({

          url: url_prefix + 'update_calendar_data',
          type: "POST",
          data: {
            day: day_num,
            data: day_data,
            csrf_webspice_tkn:token
          },
          success: function(msg) {
          	console.log(data);
            // location.reload();
          }

        });
      }

    });
}


//To select country name
function selectCountry(val) {
	console.log("data selected");
	$("#search-box").val(val);
	$("#suggesstion-box").hide();
}

page_create_board_member = function() {
	// alert("Hello World");
}

page_signin = function() {
  $('.forgotPassword').click(function() {
    $('#login_panel').hide('slow');
    $('#forgotPassword_panel').show('slow');

    return false;
  });

  $('.backtologin').click(function() {
    $('#forgotPassword_panel').hide('slow');
    $('#login_panel').show('slow');

    return false;
  });

  $('.link_register').click(function() {
    $('#forgot_password_panel').hide('slow');
    $('#login_panel').hide('slow');
    $('#change_password').hide('slow');
    $('#register_panel').show('slow');

    return false;
  });
};

page_create_user=function(){
	$('.user-role').removeClass('hide');
	$('#user_type').on('change',function(){
		var user_type=$('#user_type :selected').val();
			if(user_type=='organizational')
			{	
				$('.user-role').addClass('hide');
				/*find value for role name company_role*/
				var selected_role_value=$('#user_role option').filter(function () { return $(this).html() == "Company Role"; }).val();
				$('#user_role option[value="'+selected_role_value+'"]').attr('selected','selected');
				var selected_company_value=$('#company_id option').filter(function () { return $(this).html() == "Root Company"; }).val();
				$('#company_id option[value="'+selected_company_value+'"]').attr('selected',false);
				$('#company_id option[value="'+selected_company_value+'"]').hide();
			}
			else if(user_type=='authority'){
				var selected_company_value=$('#company_id option').filter(function () { return $(this).html() == "Root Company"; }).val();
				$('#company_id option[value="'+selected_company_value+'"]').attr('selected','selected');
				var selected_role_value=$('#user_role option').filter(function () { return $(this).html() == "Company Role"; }).val();
				$('#user_role option[value="'+selected_role_value+'"]').attr('selected',false);
				$('#user_role option[value="'+selected_role_value+'"]').hide();
				$('.user-role').removeClass('hide');
			}
			else{
				$('.user-role').removeClass('hide');
			}
		});
	
	
}
page_create_success_story = function(){
	$('.delete-img').click(function(){
		var x=confirm("Are you want to delete this item?");
	
		if(x)
		{
			var img_id = $(this).attr('data-id'); 
		  console.log("Hi");
		  var img_id_temp = '#'+$(this).attr('data-id'); 
		  var token = $('#token').val(); 
		  $.ajax({
				type: "post",
				url: url_prefix + "manage_success_story",
				cache:false,
				data: { delete_id: img_id, csrf_webspice_tkn: token },
				success:function(data){
					$(img_id_temp).hide(); 
					alert(data);
				}
		   
		  });
		}
	});
};
page_create_news_archive=function (){
	$('.delete-img').click(function(){
		var x=confirm("Are you want to delete this item?");
	
		if(x)
		{
			
			var notice_id = $(this).attr('data-id'); 
		  var token = $('#token').val(); 
		  $.ajax({
				type: "post",
				url: url_prefix + "manage_news_archive",
				cache:false,
				data: { delete_id: notice_id, csrf_webspice_tkn: token },
				success:function(data){
					alert(data);
					location.reload();
				}
		   
		  });
		}
	});	
	var edit_news=$('#hidden_news_id').val();
	if(edit_news)
	{
		var division_id=$('#division_id :selected').val();
		$('#district_id  option').hide();
		$('#district_id').find("[data-div='" + division_id+ "']").show();
		var district_id=$('#district_id :selected').val();
		$('#upazila_id option').hide();
		$('#upazila_id').find("[data-district='"+district_id+"']").show();
		var upazila_id=$('#upazila_id :selected').val();
		$('#union_id option').hide();
		$('#union_id').find("[data-upazila='"+upazila_id+"']").show();
	}
	else
	{
		$('#district_id option').hide();
		$('#upazila_id option').hide();
		$('#union_id option').hide();
	}	
	$('#division_id').on('change',function(){
		var division_id=$('#division_id :selected').val();
		$('#district_id option').hide();
		$('#district_id').find('option').prop("selected", false);
		$('#district_id').find("[data-div='" + division_id+ "']").show();
		});
	$('#district_id').on('change',function(){
		var district_id=$('#district_id :selected').val();
		$('#upazila_id option').hide();
		$('#upazila_id').find('option').prop("selected", false);
		$('#upazila_id').find("[data-district='"+district_id+"']").show();
		
		});
	$('#upazila_id').on('change',function(){
		var upazila_id=$('#upazila_id :selected').val();
		$('#union_id option').hide();
		$('#union_id').find('option').prop("selected", false);
		$('#union_id').find("[data-upazila='"+upazila_id+"']").show();
		
		});
}

//check the parameter value is number or not
IsNumeric = function(num){
	var ValidChars = "0123456789";
	var IsNumber = true;
	var Char;
	
	for(i = 0; i < num.length && IsNumber == true; i++) { 
		Char = num.charAt(i); 
		if( ValidChars.indexOf(Char) == -1){
			IsNumber = false;
		}
	}
	return IsNumber;
};

IsFloat = function(num){
	var ValidChars = "0123456789.";
	var IsNumber = true;
	var Char;
	
	for(i = 0; i < num.length && IsNumber == true; i++) { 
		Char = num.charAt(i); 
		if( ValidChars.indexOf(Char) == -1){
			IsNumber = false;
		}
	}
	
	if( IsNumber && !isNaN ( parseFloat ( num )) ) {
		return true;
	}
	
	return false;
};

//check email address is valid or not
checkemail = function(val){
	//return (val.indexOf(".") > 2) && (val.indexOf("@") > 0);
	var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
	var address = val;
	if(reg.test(address) == false) {
	  return false;
	}

	return true;
};
page_create_upazila=function(){
	var edit_upazila=$('#hidden_upazila_id').val();
	if(edit_upazila)
	{
		var division_id=$('#division_id :selected').val();
		$('#district_id  option').hide();
		$('#district_id').find("[data-div='" + division_id+ "']").show();
	}
	else
	{
		$('#district_id option').hide();
	}

	$("#division_id").on('change',function(){
		var division_id=$('#division_id :selected').val();
		$('#district_id  option').hide();
		$('#district_id').find('option').prop("selected", false);
		$('#district_id').find("[data-div='" + division_id+ "']").show();
		});
}

page_create_union=function(){
	var edit_union=$('#hidden_union_id').val();
	if(edit_union)
	{
		var division_id=$('#division_id :selected').val();
		$('#district_id  option').hide();
		$('#district_id').find("[data-div='" + division_id+ "']").show();
		var district_id=$('#district_id :selected').val();
		$('#upazila_id option').hide();
		$('#upazila_id').find("[data-district='"+district_id+"']").show();
	}
	else
	{
		$('#district_id option').hide();
		$('#upazila_id option').hide();
	}
	$('#division_id').on('change',function(){
		var division_id=$('#division_id :selected').val();
		$('#district_id option').hide();
		$('#district_id').find('option').prop("selected", false);
		$('#district_id').find("[data-div='" + division_id+ "']").show();
		});
	$('#district_id').on('change',function(){
		var district_id=$('#district_id :selected').val();
		$('#upazila_id option').hide();
		$('#upazila_id').find('option').prop("selected", false);
		$('#upazila_id').find("[data-district='"+district_id+"']").show();
		
		});
}
page_create_company=function(){
	var edit_company=$('#hidden_company_id').val();
	if(edit_company)
	{
		var division_id=$('#division_id :selected').val();
		$('#district_id  option').hide();
		$('#district_id').find("[data-div='" + division_id+ "']").show();
		var district_id=$('#district_id :selected').val();
		$('#upazila_id option').hide();
		$('#upazila_id').find("[data-district='"+district_id+"']").show();
		var upazila_id=$('#upazila_id :selected').val();
		$('#union_id option').hide();
		$('#union_id').find("[data-upazila='"+upazila_id+"']").show();
	}
	else
	{
		$('#district_id option').hide();
		$('#upazila_id option').hide();
		$('#union_id option').hide();
	}	
	$('#division_id').on('change',function(){
		var division_id=$('#division_id :selected').val();
		$('#district_id option').hide();
		$('#district_id').find('option').prop("selected", false);
		$('#district_id').find("[data-div='" + division_id+ "']").show();
		});
	$('#district_id').on('change',function(){
		var district_id=$('#district_id :selected').val();
		$('#upazila_id option').hide();
		$('#upazila_id').find('option').prop("selected", false);
		$('#upazila_id').find("[data-district='"+district_id+"']").show();
		
		});
	$('#upazila_id').on('change',function(){
		var upazila_id=$('#upazila_id :selected').val();
		$('#union_id option').hide();
		$('#union_id').find('option').prop("selected", false);
		$('#union_id').find("[data-upazila='"+upazila_id+"']").show();
		
		});

}

page_create_notice=function(){
	
	$('.delete-img').click(function(){
		var x=confirm("Are you want to delete this item?");
	
		if(x)
		{
			
			var notice_id = $(this).attr('data-id'); 
		  var token = $('#token').val(); 
		  $.ajax({
				type: "post",
				url: url_prefix + "manage_notice",
				cache:false,
				data: { delete_id: notice_id, csrf_webspice_tkn: token },
				success:function(data){
					alert(data);
					location.reload();
				}
		   
		  });
		}
	});

	var edit_notice=$('#hidden_notice_id').val();
	if(edit_notice)
	{
		var division_id=$('#division_id :selected').val();
		$('#district_id  option').hide();
		$('#district_id').find("[data-div='" + division_id+ "']").show();
		var district_id=$('#district_id :selected').val();
		$('#upazila_id option').hide();
		$('#upazila_id').find("[data-district='"+district_id+"']").show();
		var upazila_id=$('#upazila_id :selected').val();
		$('#union_id option').hide();
		$('#union_id').find("[data-upazila='"+upazila_id+"']").show();
	}
	else
	{
		$('#district_id option').hide();
		$('#upazila_id option').hide();
		$('#union_id option').hide();
	}	
	$('#division_id').on('change',function(){
		var division_id=$('#division_id :selected').val();
		$('#district_id option').hide();
		$('#district_id').find('option').prop("selected", false);
		$('#district_id').find("[data-div='" + division_id+ "']").show();
		});
	$('#district_id').on('change',function(){
		var district_id=$('#district_id :selected').val();
		$('#upazila_id option').hide();
		$('#upazila_id').find('option').prop("selected", false);
		$('#upazila_id').find("[data-district='"+district_id+"']").show();
		
		});
	$('#upazila_id').on('change',function(){
		var upazila_id=$('#upazila_id :selected').val();
		$('#union_id option').hide();
		$('#union_id').find('option').prop("selected", false);
		$('#union_id').find("[data-upazila='"+upazila_id+"']").show();
		
		});

}
page_create_course=function(){
	
	var edit_course=$('#hidden_course_id').val();
	if(edit_course)
	{
		var division_id=$('#division_id :selected').val();
		$('#district_id  option').hide();
		$('#district_id').find("[data-div='" + division_id+ "']").show();
		var district_id=$('#district_id :selected').val();
		$('#upazila_id option').hide();
		$('#upazila_id').find("[data-district='"+district_id+"']").show();
		var upazila_id=$('#upazila_id :selected').val();
		$('#union_id option').hide();
		$('#union_id').find("[data-upazila='"+upazila_id+"']").show();
	}
	else
	{
		$('#district_id option').hide();
		$('#upazila_id option').hide();
		$('#union_id option').hide();
	}	
	$('#division_id').on('change',function(){
		var division_id=$('#division_id :selected').val();
		$('#district_id option').hide();
		$('#district_id').find('option').prop("selected", false);
		$('#district_id').find("[data-div='" + division_id+ "']").show();
		});
	$('#district_id').on('change',function(){
		var district_id=$('#district_id :selected').val();
		$('#upazila_id option').hide();
		$('#upazila_id').find('option').prop("selected", false);
		$('#upazila_id').find("[data-district='"+district_id+"']").show();
		
		});
	$('#upazila_id').on('change',function(){
		var upazila_id=$('#upazila_id :selected').val();
		$('#union_id option').hide();
		$('#union_id').find('option').prop("selected", false);
		$('#union_id').find("[data-upazila='"+upazila_id+"']").show();
		
		});

}

page_create_training_area=function(){
		
		$('#frm_create_training_area').on('submit',function(){
			
			var batch_available = $('.batch_available').attr('batch-available');
			var total_batch = $('.total_batches').attr('total-batch');
			var slot_id_edit = $('#slot_id_edit').val();
			var training_area_level = $('.training_level').text();
			
			var values = $( "input[name = 'no_of_batches[]' ]" )
              .map(function(){return $(this).val();}).get();    
			sum = 0;
			
			$.each( values,function(){sum += parseInt(this) || 0;} );
			
			/*for testing duplicate entry*/
			if( training_area_level )
			{		
				var areas=[];
				var fields = document.getElementsByName("area_id[]");
				for(var i = 0; i < fields.length; i++) {
					if(fields[i].value)
					{
						areas.push(fields[i].value);
					}
						 
				}
				/*Make array unique*/
				var unique = $.makeArray($(areas).filter(function(i,itm){ 
				    return i == $.inArray(itm, areas);
				}));
				
				if( unique.length < areas.length )
				{
					alert("You are not allowed to enter duplicate entry! ");
					return false;
				}
				
			}
		
			/*for data editing*/
			
			if( slot_id_edit && sum > total_batch )
			{
				alert("Total input batch is greater than available batch!");
				return false;
			}
			
			/*for data inserting*/
			if( !slot_id_edit && sum > batch_available )
			{
				alert("Total input batch is greater than available batch!");
				return false;
			}
			
			
			
			/*submit form */
			$('#frm_create_training_area').submit();
	
			});
		
}
page_create_course_material=function(){
	$('.delete-img').click(function(){
		var x=confirm("Are you want to delete this item?");
		if(x)
		{
			var material_id = $(this).attr('data-id'); 
		  var token = $('#token').val(); 
		  $.ajax({
				type: "post",
				url: url_prefix + "manage_course_material",
				cache:false,
				data: { delete_id: material_id, csrf_webspice_tkn: token },
				success:function(data){
					alert(data);
					location.reload();
				}
		  });
		}
	});
	
}



//-->