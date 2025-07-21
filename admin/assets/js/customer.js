var paths = url_params();
var ctrl_pages = paths.route.split('/');
var others_click = 0;
var pre_img_pan_resource = [];
var pre_bankimg_files = [];
var pre_panimg_files = [];
var pre_img_adhaar_resource = [];
var pre_adhaarimg_files = [];
$(document).ready(function () {
    var path = url_params();
	// coded by Jothika on 9-7-25 [gender, title update]
	updateGender();
	function updateGender() {
		const selected = $('#title_select').val();
		const maleRadio = $('input[name="customer[gender]"][value = "0"]');
		const femaleRadio = $('input[name="customer[gender]"][value = "1"]');
		const otherRadio = $('input[name="customer[gender]"][value = "3"]');
		femaleRadio.prop('disabled', false);
		maleRadio.prop('disabled', false);
		otherRadio.prop('disabled', false);
		if (selected == "Mr") {
			if (!otherRadio.prop('checked')) {
				maleRadio.prop('checked', true);
				femaleRadio.prop('disabled', true);
			} else {
				femaleRadio.prop('disabled', true);
			}
		} else if (selected == "Ms" || selected == "Mrs") {
			if (!otherRadio.prop('checked')) {
				femaleRadio.prop('checked', true);
				maleRadio.prop('disabled', true);
			} else {
				maleRadio.prop('disabled', true);
			}
		}
	}
	$('#title_select').on('change', updateGender)
    $("#firstname").on('keyup', function (event) {
        this.value = this.value.replace(/[^a-z\s]/ig, '').replace(/\s{2,}/g, ' ');
    });
    $("#gst_number").on('change', function (event) {
        var inputvalues = $('#gst_number').val();
        var tdr_regex = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/;
        if (inputvalues.length == 15) {
            if (tdr_regex.test(inputvalues) == false) {
                alert('Your GST number ' + inputvalues + ' is not in the correct format!');
                $('#gst_number').val('');
            }
        } else {
            alert('You have not entered valid GST number!');
            $('#gst_number').val('');
        }
    });
    if ($('.cus_type:checked').val() == 1) {
        $('#cus_name').html('First Name');
        $('#last_name').css("display", "block");
        $('#gstno').css("display", "none");
        //$('#pan_no').css("display","none");
        $('#gst_number').prop('required', false);
        // $('#gst_number').prop('required',false);
        $('#pan').prop('required', false);
    } else {
        //if condition added by Durga 21-06-2023
        if (ctrl_pages[1] != 'customer_edit') {
            $('#last_name').css("display", "none");
            $('#gstno').css("display", "block");
            //('#pan_no').css("display","block");
            $('#cus_name').html('Company Name');
            $('#gst_number').prop('required', true);
            $('#pan').prop('required', true);
        }
    }
    $("#firstname,#lastname,#nominee_name,#nominee_relationship").on('keypress', function (event) {
        var regex = new RegExp("^[a-zA-Z ]*$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
            event.preventDefault();
            return false;
        }
    });
    $("#firstname,#lastname").bind("cut copy paste", function (e) {
        e.preventDefault();
    });
    $('#cus_create').submit(function (e) {
        $('#save').prop('disabled', true);
    });
    $('#title_select').select2().on("change", function (e) {
        console.log(this.value);
        if (this.value) {
            $("#cus_title").val(this.value);
        }
        var s = $("#cus_title").val();
        console.log(s);
    });
    $('#branch_select').select2().on("change", function (e) {
        if (this.value != '') {
            $("#id_branch").val(this.value);
            var id_branch = $('#id_branch').val();
            get_customer_list('', '', id_branch);
        }
    });
    if (path.route == 'customer') {
        //get_customer_list();
        // get_village();
        var date = new Date();
        // var firstDay  = new Date(date.getFullYear(), date.getMonth(),date.getDate() - 6, 1);
        // var from_date =  firstDay.getFullYear()+'-'+(firstDay.getMonth()+1)+'-'+firstDay.getDate();
        // var to_date=(date.getFullYear()+"-"+(date.getMonth() + 1)+"-"+date.getDate());
        $('#customer_list1').text(moment().startOf('month').format('DD-MM-YYYY'));
        $('#customer_list2').text(moment().endOf('month').format('DD-MM-YYYY'));
        get_customer_list($('#customer_list1').text(), $('#customer_list2').text());
        $('#customer-dt-btn').daterangepicker(
            {
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment().startOf('month'),
                endDate: moment().endOf('month')
            },
            function (start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                //var id_branch=$('#id_branch').val();
                //var id_customer=$('#id_customer').val();
                var id_village = $("#village_select").val();
                get_customer_list(start.format('DD-MM-YYYY'), end.format('DD-MM-YYYY'), '', id_village)
                $('#customer_list1').text(start.format('YYYY-MM-DD'));
                $('#customer_list2').text(end.format('YYYY-MM-DD'));
            }
        );
        get_village();
    }
    var pathArray = window.location.pathname.split('php/');
    var ctrl_page = pathArray[1].split('/');
    var export_list = [];
    $(document).ready(function () {
        if (ctrl_page[1] == 'zone') {
            get_ajaxzone_list();
        }
        //Added by Durga 06-06-2023 starts here
        if (ctrl_page[0] == 'customer' && ctrl_page[1] == 'edit') {
            var religion = $(id_religion).val();
            if (religion > 0) {
                $("#religion_select").val(religion);
            }
            if ($('#pin_code_add').val() != '') {
                get_villages_by_pincode($('#pin_code_add').val());
            }
        }
        //Added by Durga 06-06-2023 ends here
        if (ctrl_page[1] == 'withoutAccount') {
            get_without_acc_cus();
        }
        if (ctrl_page[1] == 'add') {
            $("#pan_proof_img").val('');
            $("#voterid_proof_img").val('');
            $("#rationcard_proof_img").val('');
        }
        if (ctrl_page[1] == 'add' || ctrl_page[1] == 'edit') {
            //kyc code starts
            $("input[name='image_upload_select']:radio").on('change', function () {
                var image_type = $('input[name="image_upload_select"]:checked').val()
                if (image_type == 1) {
                    Webcam.attach('#pan_details');
                    $("#web-cam_pan").css("display", "block");
                    $("#browse_pan_front").css("display", "none");
                    $("#browse_pan_back").css("display", "none");
                    $("#pdf_file").css("display", "none");
                    $("#uploadArea_p_stn_pan").css("display", "block");
                    $("#pan_preview_block").css("display", "none");
                }
                else if (image_type == 2) {
                    Webcam.reset('#pan_details');
                    $("#web-cam_pan").css("display", "none");
                    $("#pdf_file").css("display", "none");
                    $("#uploadArea_p_stn_pan").css("display", "none");
                    $("#pan_preview_block").css("display", "block");
                    var view_type = $('input[name="pancardside_type"]:checked').val()
                    if (view_type == 1) {
                        $("#browse_pan_front").css("display", "block");
                        $("#browse_pan_back").css("display", "none");
                    }
                    else if (view_type == 2) {
                        $("#browse_pan_back").css("display", "block");
                        $("#browse_pan_front").css("display", "none");
                    }
                    else {
                        $("#browse_pan_front").css("display", "none");
                        $("#browse_pan_back").css("display", "none");
                        alert("Select view Type");
                    }
                }
                else if (image_type == 3) {
                    Webcam.reset('#pan_details');
                    $("#web-cam_pan").css("display", "none");
                    $("#browse_pan_front").css("display", "none");
                    $("#browse_pan_back").css("display", "none");
                    $("#pdf_file").css("display", "block");
                    $("#pan_preview_block").css("display", "none");
                    $("#uploadArea_p_stn_pan").css("display", "none");
                }
                else {
                    Webcam.reset('#pan_details');
                    $("#web-cam_pan").css("display", "none");
                    $("#browse_pan_front").css("display", "none");
                    $("#browse_pan_back").css("display", "none");
                    $("#pdf_file").css("display", "none");
                    $("#pan_preview_block").css("display", "none");
                    $("#uploadArea_p_stn_pan").css("display", "none");
                }
            });
            $("input[name='pancardside_type']:radio").on('change', function () {
                var view_type = $('input[name="pancardside_type"]:checked').val();
                var image_type = $('input[name="image_upload_select"]:checked').val();
                if (image_type == 2) {
                    if (view_type == 1) {
                        $("#browse_pan_front").css("display", "block");
                        $("#browse_pan_back").css("display", "none");
                    }
                    else if (view_type == 2) {
                        $("#browse_pan_back").css("display", "block");
                        $("#browse_pan_front").css("display", "none");
                    }
                    else {
                        $("#browse_pan_front").css("display", "none");
                        $("#browse_pan_back").css("display", "none");
                        alert("Select view Type");
                    }
                }
            });
            $("input[name='adhaarcardside_type']:radio").on('change', function () {
                var view_type = $('input[name="adhaarcardside_type"]:checked').val();
                var image_type = $('input[name="aadhar_image_upload_select"]:checked').val();
                if (image_type == 2) {
                    if (view_type == 1) {
                        $("#browse_aadhar_front").css("display", "block");
                        $("#browse_aadhar_back").css("display", "none");
                    }
                    else if (view_type == 2) {
                        $("#browse_aadhar_back").css("display", "block");
                        $("#browse_aadhar_front").css("display", "none");
                    }
                    else {
                        $("#browse_aadhar_front").css("display", "none");
                        $("#browse_aadhar_back").css("display", "none");
                        alert("Select view Type");
                    }
                }
            });
            $("input[name='aadhar_image_upload_select']:radio").on('change', function () {
                var image_type = $('input[name="aadhar_image_upload_select"]:checked').val()
                if (image_type == 1) {
                    Webcam.attach('#aadhar_details');
                    $("#web-cam_aadhar").css("display", "block");
                    $("#browse_aadhar_front").css("display", "none");
                    $("#browse_aadhar_back").css("display", "none");
                    $("#pdf_aadhar_file").css("display", "none");
                    $("#aadhar_preview_block").css("display", "none");
                    $("#uploadArea_p_stn_aadhar").css("display", "block");
                }
                else if (image_type == 2) {
                    Webcam.reset('#aadhar_details');
                    $("#web-cam_aadhar").css("display", "none");
                    $("#pdf_aadhar_file").css("display", "none");
                    $("#aadhar_preview_block").css("display", "block");
                    $("#uploadArea_p_stn_aadhar").css("display", "none");
                    var view_type = $('input[name="adhaarcardside_type"]:checked').val();
                    if (view_type == 1) {
                        $("#browse_aadhar_front").css("display", "block");
                        $("#browse_aadhar_back").css("display", "none");
                    }
                    else if (view_type == 2) {
                        $("#browse_aadhar_back").css("display", "block");
                        $("#browse_aadhar_front").css("display", "none");
                    }
                    else {
                        $("#browse_aadhar_front").css("display", "none");
                        $("#browse_aadhar_back").css("display", "none");
                        alert("Select view Type");
                    }
                }
                else if (image_type == 3) {
                    Webcam.reset('#aadhar_details');
                    $("#web-cam_aadhar").css("display", "none");
                    $("#browse_aadhar_front").css("display", "none");
                    $("#browse_aadhar_back").css("display", "none");
                    $("#pdf_aadhar_file").css("display", "block");
                    $("#aadhar_preview_block").css("display", "none");
                    $("#uploadArea_p_stn_aadhar").css("display", "none");
                }
                else {
                    Webcam.reset('#aadhar_details');
                    $("#web-cam_aadhar").css("display", "none");
                    $("#browse_aadhar_front").css("display", "none");
                    $("#browse_aadhar_back").css("display", "none");
                    $("#pdf_aadhar_file").css("display", "none");
                    $("#aadhar_preview_block").css("display", "none");
                    $("#uploadArea_p_stn_aadhar").css("display", "none");
                }
            });
            //kyc code ends
            $("#profession").select2({
                placeholder: "Select Profession",
                allowClear: true
            });
            get_profession();
            //webcam upload, #AD On:20-12-2022,Cd:CLin,Up:AB
            Webcam.set({
                width: 290,
                height: 190,
                image_format: 'jpg',
                jpeg_quality: 90,
                constraints: {                     //Kyc Tab
                    facingMode: 'environment'
                }
            });
            //kyc tab
            Webcam.attach('#my_camera');
            // Webcam.attach( '#bank_details' );
            //	Webcam.attach( '#pan_details' );
            //	Webcam.attach( '#aadhar_details' );
            Webcam.attach('#dl_details');
            Webcam.attach('#pp_details');
            Webcam.on('error', function (err) {
                console.log('Error accessing webcam:', err);
            });
            $("#upload_img").setShortcutKey(17, 73, function () {
                take_snapshot('pre_images');
            });
            //webcam upload ends...
        }
    });
    if (ctrl_page[1] == 'cus_profile') {
        ej.base.enableRipple(true);
        var datepicker = new ej.calendars.DatePicker(
            {
                format: 'yyyy-MM-dd'
            }
        );
        var datepicker1 = new ej.calendars.DatePicker({
            format: 'yyyy-MM-dd'
        });
        datepicker.appendTo('#datepicker');
        datepicker1.appendTo('#datepicker1');
        $(".dob,.wedding").keydown(function (event) {
            return false;
        });
    }
    $('.profile').initial({ height: 25, width: 25, fontSize: 10, fontWeight: 700 });
    /*$('#lastname').on('blur change',function() {
            var regexp = /^[A-Z]{5}\d{4}[A-Z]{1}$/;
            if(!regexp.test($(this).val()))
            {
            $(this).val("");
            alert("Special characters not allowed");
            //$(this).focus();
            return false;
            }
    });*/
    /*-- Coded by ARVK --*/
    $('#lastname').on('blur', function () {
        if ($(this).val() != "") {
            var regexp = /^[a-zA-Z]+$/;
            if (!regexp.test($(this).val())) {
                $(this).val("");
                alert("Last name can have only alphabets");
            }
        }
    });
    /*-- / Coded by ARVK --*/
    //Image validation
    $('#cus_image').on('change', function () {
        validateImage(this);
    });
    $('#pan_proof').on('change', function () {
        $('#pan_proof_img').val(this);
        validateImage(this);
    });
    $('#voterid_proof').on('change', function () {
        $(voterid_proof_img).val(this);
        validateImage(this);
    });
    $('#rationcard_proof').on('change', function () {
        $("#rationcard_proof_img").val(this);
        validateImage(this);
    });
    $("#pan").on('blur onchange', function (event) {
        event.preventDefault();
        validate_pan(this);
    });
    $("#pin_code_add").on('keyup', function (event) {
        event.preventDefault();
        var pincode_len = this.value.length;
        if (pincode_len == 6) {
            validate_pincode(this);
        }
    });
    $('#passwd').on('blur change', function () {
        if ($.trim($("#passwd").val()).length < 8) {
            $("#passwd").val("");
            $("#passwd").attr('placeholder', 'Password must be of minimum 8 characters.');
            $("#passwd").focus();
            return false;
        }
    });
    if ($('#country').length > 0) {
        get_country();
        // get_village();
    }
    $('#country').select2().on('change', function () {
        if (this.value != '') {
            $('#countryval').val(this.value);
            get_state(this.value);
            $('#city').empty();
            $('#cityval').empty();
            $('#select2-city-container').empty();
            $("#city option:selected").text();
        }
        if (ctrl_page[0] == 'customer' && ctrl_page[1] == 'cus_profile') {
            calculate_profile_percentage();
        }
    });
    $('#state').select2().on('change', function () {
        if (this.value != '') {
            $("#stateval").val(this.val);
            get_city(this.value);
        }
        if (ctrl_page[0] == 'customer' && ctrl_page[1] == 'cus_profile') {
            calculate_profile_percentage();
        }
    });
    $('#city').select2().on('change', function () {
        if (this.value != '') {
            if (ctrl_page[0] == 'customer' && ctrl_page[1] == 'cus_profile') {
                calculate_profile_percentage();
            }
        }
    });
    $("#state").select2({
        placeholder: "Enter State",
        allowClear: true
    });
    $("#city").select2({
        placeholder: "Enter City",
        allowClear: true
    });
    $("#country").select2({
        placeholder: "Enter Country",
        allowClear: true
    });
    $("#Village").select2({
        placeholder: "Enter Area",
        allowClear: true
    });
    $("#village_select").select2({
        placeholder: "Enter Area",
        allowClear: true
    });
    if ($('#username').length > 0) {
        $('#username').on('blur onchange', function () {
            if (this.value.length >= 6) {
                checkUserNameExists(this.value);
            }
            else {
                $(this).val('');
                $(this).attr('placeholder', 'Required atleast 8 characters')
            }
        });
    }
    if ($('#mobile').length > 0) {
        $("#mobile").keypress(function (e) {
            var charCode = (e.which) ? e.which : e.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
        });
        $('#aadharid').on('blur onchange', function () {
            if (this.value.length == 12) {
                validate_adhaar(this.value);
            }
            ///else part commented by Durga 21-06-2023
            /*else
            {
                $(this).val('');
                $(this).attr('placeholder', 'Enter valid aadhar number');
                $(this).focus();
            }*/
        });
        function validate_adhaar(aadhar) {
            var regexp = /^([0-9]{12})?$/;
            if (!regexp.test(aadhar)) {
                $("#aadharid").val('');
                $("#aadharid").attr('placeholder', 'Enter valid aadhar number');
                $("#aadharid").focus();
            }
        }
        $('#mobile').on('blur onchange', function () {
            if (this.value.length == 10) {
                checkMobileAvail(this.value);
            }
            else {
                $(this).val('');
                $(this).attr('placeholder', 'Enter valid mobile number');
                $(this).focus();
            }
        });
    }
    if ($('#email').length > 0) {
        $('#email').on('blur onchange', function () {
            var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
            if (this.value.search(emailRegEx) == -1) {
                $(this).val('');
                $(this).attr('placeholder', 'Enter valid email id')
            }
            else {
                checkEmailAvail(this.value);
            }
        });
    }
    if ($('#date_of_birth').length > 0) {
        const today = new Date();
        const maxDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
        $('#date_of_birth').datepicker({
            format: 'dd/mm/yyyy',
            endDate: maxDate,
            autoclose: true,
            todayHighlight: true
        }).on('changeDate', function (ev) {
            calculateAge(this.value);
        });
    }
    if ($('#date_of_wed').length > 0) {
        $('#date_of_wed').datepicker({
            format: 'dd/mm/yyyy'
        })
            .on('changeDate', function (ev) {
                $(this).datepicker('hide');
            });
    }
    if ($('#age').length > 0 || $('#date_of_birth').val()) {
        calculateAge($('#date_of_birth').val());
    }
    $('#select_all').click(function (event) {
        $("tbody tr td input[type='checkbox']").prop('checked', $(this).prop('checked'));
        event.stopPropagation();
    });
    //send SMS to selected customers  list
    $("#send-login-sms").click(function () {
        var data = { 'id_customer[]': [] };
        $("input[name='id_customer[]']:checked").each(function () {
            data['id_customer[]'].push($(this).val());
        });
        $("div.overlay").css("display", "block");
        $.ajax({
            type: "POST",
            url: base_url + "index.php/sms/login",
            data: data,
            sync: false,
            success: function (data) {
                $("div.overlay").css("display", "none");
                $('#alert_msg').html(data);
                $(".alert").css("display", "block");
            }
        });
    });
    //send Email to selected customers  list
    $("#send-login-email").click(function () {
        var data = { 'id_customer[]': [] };
        $("input[name='id_customer[]']:checked").each(function () {
            data['id_customer[]'].push($(this).val());
        });
        $("div.overlay").css("display", "block");
        $.ajax({
            type: "POST",
            url: base_url + "index.php/sms/login_email",
            data: data,
            sync: false,
            success: function (data) {
                $("div.overlay").css("display", "none");
                if (data == 1) {
                    $.toaster({ priority: 'success', title: 'Success!', message: '' + "</br> Login Password Sent your mail id " });
                } else {
                    $.toaster({ priority: 'danger', title: 'Warning!', message: '' + "</br> Can't able to sent E-Mail Login details . kindly enable sms service" })
                }
                $('#alert_msg').html(data);
                $(".alert").css("display", "block");
            }
        });
    });
});
function validate_pan() {
    if ($("#pan").val() != '') {
        var regexp = /^[A-Z]{5}\d{4}[A-Z]{1}$/;
        if (!regexp.test($("#pan").val())) {
            $('#pan').val('');
            $('#pan').attr('placeholder', 'Enter Valid Pan No')
            $("#pan").focus();
        }
    }
}
function validate_pincode() {
    if ($("#pin_code_add").val() != '') {
        var regexp = /^([0-9]{6})?$/;
        if (!regexp.test($("#pin_code_add").val())) {
            $("#pin_code_add").val("");
            $('#pin_code_add').attr('placeholder', 'Not a valid Pincode.');
            $("#pin_code_add").focus();
        }
    }
}
function validateImage() {
    if (arguments[0].id == 'cus_image') {
        var preview = $('#cus_img_preview');
    }
    else if (arguments[0].id == 'pan_proof') {
        var preview = $('#pan_proof_preview');
    }
    else if (arguments[0].id == 'voterid_proof') {
        var preview = $('#voterid_proof_preview');
    }
    else {
        var preview = $('#rationcard_proof_preview');
    }
    // 1048576//1 mb
    if (arguments[0].files[0].size > 10534243) {
        alert('File size cannot be greater than 1 MB');
        arguments[0].value = "";
        preview.css('display', 'none');
    }
    else {
        var fileName = arguments[0].value;
        var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
        ext = ext.toLowerCase();
        if (ext != "jpg" && ext != "png" && ext != "jpeg") {
            alert("Upload JPG or PNG Images only");
            arguments[0].value = "";
            preview.css('display', 'none');
        }
        else {
            var file = arguments[0].files[0];
            var reader = new FileReader();
            reader.onloadend = function () {
                preview.prop('src', reader.result);
            }
            if (file) {
                reader.readAsDataURL(file);
                preview.css('display', '');
            }
            else {
                preview.prop('src', '');
                preview.css('display', 'none');
            }
        }
    }
}
function get_country() {
    $('.overlay').css('display', 'block');
    $.ajax({
        type: 'GET',
        url: base_url + 'index.php/settings/company/getcountry',
        dataType: 'json',
        success: function (country) {
            $.each(country, function (key, country) {
                $('#country').append(
                    $("<option></option>")
                        .attr("value", country.id)
                        .text(country.name)
                );
            });
            //	$("#country").select2("val",101);
            $("#country").select2("val", ($('#countryval').val() != null && $('#countryval').val() != 0 ? $('#countryval').val() : 101));
            var selectid = $('#countryval').val();
            if (selectid != null && selectid > 0) {
                $('#country').val(selectid);
                $('.overlay').css('display', 'block');
                get_state(selectid);
            }
            $('.overlay').css('display', 'none');
        },
        error: function (error) {
            $("div.overlay").css("display", "none");
        }
    });
}
function get_state(id) {
    $('.overlay').css('display', 'block');
    $('#state option').remove();
    $.ajax({
        type: 'POST',
        data: { 'id_country': id },
        url: base_url + 'index.php/settings/company/getstate',
        dataType: 'json',
        success: function (state) {
            $.each(state, function (key, state) {
                $('#state').append(
                    $("<option></option>")
                        .attr("value", state.id)
                        .text(state.name)
                );
            });
            $("#state").select2("val", ($('#stateval').val() != null && $('#stateval').val() != 0 ? $('#stateval').val() : 35));
            var selectid = $('#stateval').val();
            console.log(selectid);
            if (selectid != null && selectid > 0) {
                $('#state').val(selectid);
                get_city(selectid);
            }
            $('.overlay').css('display', 'none');
        },
        error: function (error) {
            $("div.overlay").css("display", "none");
        }
    });
}
function get_city(id) {
    $('.overlay').css('display', 'block');
    $('#city option').remove();
    $("#city").css("display", "block");
    $.ajax({
        type: 'POST',
        data: { 'id_state': id },
        url: base_url + 'index.php/settings/company/getcity',
        dataType: 'json',
        success: function (city) {
            $.each(city, function (key, city) {
                $('#city').append(
                    $("<option></option>")
                        .attr("value", city.id)
                        .text(city.name)
                );
            });
            $("#city").select2("val", ($('#cityval').val() != null ? $('#cityval').val() : ''));
            var selectid = $('#cityval').val();
            if (selectid != null && selectid > 0) {
                $('#city').val(selectid);
            }
            $('.overlay').css('display', 'none');
        },
        error: function (error) {
            $("div.overlay").css("display", "none");
        }
    });
}
function checkUserNameExists(username) {
    $.ajax({
        type: 'GET',
        url: base_url + 'index.php/customer/check_username/' + username,
        dataType: 'json',
        success: function (avail) {
            if (avail == 1) {
                $('#username').val('');
                $('#username').attr('placeholder', 'Username already exists')
            }
        },
        error: function (error) {
            $("div.overlay").css("display", "none");
        }
    });
}
function checkEmailAvail(email) {
    $("div.overlay").css("display", "block");
    $.ajax({
        type: 'POST',
        data: { 'email': email, 'id_customer': (cust_id != "" ? cust_id : "") },
        url: base_url + 'index.php/customer/check_email/',
        dataType: 'json',
        success: function (avail) {
            if (avail == 1) {
                $('#email').val('');
                $('#email').attr('placeholder', 'Email already exists')
            }
            $("div.overlay").css("display", "none");
        },
        error: function (error) {
            $("div.overlay").css("display", "none");
        }
    });
}
function checkMobileAvail(mobile) {
    $("div.overlay").css("display", "block");
    $.ajax({
        type: 'POST',
        data: { 'mobile': mobile, 'id_customer': (cust_id != "" ? cust_id : "") },
        url: base_url + 'index.php/customer/check_mobile',
        dataType: 'json',
        success: function (avail) {
            if (avail == 1) {
                $('#mobile').val('');
                $('#mobile').attr('placeholder', 'mobile already exists')
            } else {
                if (path.route == 'customer/add') {
                    // $('#passwd').val(mobile);
                }
            }
            $("div.overlay").css("display", "none");
        },
        error: function (error) {
            $("div.overlay").css("display", "none");
        }
    });
}
//preview selected images
function readURL(input, preview) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#' + preview).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
function get_customer_list(from_date = "", to_date = "", id_branch = "", id_village = "", mobile = "") {
    my_Date = new Date();
    var type = $('#date_Select').find(":selected").val();
    var id_religion = $("#religion_select").val();
    $("div.overlay").css("display", "block");
    $("#customer_date_range").text(from_date + " to " + to_date);
    $.ajax({
        url: base_url + "index.php/customer/ajax_list?nocache=" + my_Date.getUTCSeconds(),
		data: (from_date != '' || id_branch != '' || to_date != '' || id_village != '' || mobile != '' ? { 'from_date': from_date, 'to_date': to_date, 'id_branch': id_branch, 'id_village': id_village, 'date_type': type, 'id_religion': id_religion, 'mobile': mobile } : ''),
        dataType: "JSON",
        type: "POST",
        success: function (data) {
            set_customer_list(data);
            $("div.overlay").css("display", "none");
        },
        error: function (error) {
            $("div.overlay").css("display", "none");
        }
    });
}
function set_customer_list(data) {
    export_list = [];
    var customer = data.customer;
    var access = data.access;
    var oTable = $('#customer_list').DataTable();
    $("#total_customers").text(customer.length);
    if (access.add == '0') {
        $('#add_customer').attr('disabled', 'disabled');
    }
    oTable.clear().draw();
    if (customer != null && customer.length > 0) {
        var branch_name = getBranchTitle();
        var title = '';
        title += get_title($('#customer_list1').text(), $('#customer_list2').text(), 'Customer List - ' + branch_name);
        export_list = customer;
        oTable = $('#customer_list').dataTable({
            "bDestroy": true,
            "bInfo": true,
            "bFilter": true,
            "bSort": true,
            "dom": 'lBfrtip',
            // "buttons" : ['print'],
            "buttons": [
                'colvis',
                {
                    extend: 'print',
                    title: '',
                    customize: function (win) {
                        $(win.document.body)
                            .prepend(title);
                        $(win.document.body).find('table')
                            .addClass('compact');
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', '10px')
                            .css('font-family', 'sans-serif');
                        $(win.document.body).find('tr:nth-child(odd) td').each(function (index) {
                            $(this).css('font-weight', 'bold');
                        });
                    },
                    text: 'Print',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excel',
                    text: 'Excel',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ],
            "tableTools": { "buttons": [{ "sExtends": "xls", "oSelectorOpts": { page: 'current' } }, { "sExtends": "pdf", "oSelectorOpts": { page: 'current' } }] },
            "aaData": customer,
            "order": [[0, "desc"]],
            "aoColumns": [{
                "mDataProp": function (row, type, val, meta) {
                    id = row.id_customer;
                    return "<label class='checkbox-inline'><input type='checkbox' class='flat-red' name='id_customer[]' value='" + row.id_customer + "' /> " + id + " </label>";
                }
            },
            {
                "mDataProp": function (row, type, val, meta) {
                    var title = row.title != null ? row.title + ". " : '';
                    return title + "" + row.name;
                }
            },
            { "mDataProp": "mobile" },
            { "mDataProp": "accounts" },
            { "mDataProp": "date_add" },
            { "mDataProp": "custom_entry_date" },
            { "mDataProp": "gender" },
            { "mDataProp": "address1" },
            // { "mDataProp": "address1" },
            { "mDataProp": "city" },
            { "mDataProp": "state" },
            { "mDataProp": "pan" },
            { "mDataProp": "aadharid" },
            { "mDataProp": "email" },
            { "mDataProp": "pincode" },
            { "mDataProp": "date_of_birth" },
            { "mDataProp": "date_of_wed" },
            { "mDataProp": "age" },
            {
                "mDataProp": function (row, type, val, meta) {
                    if (row.religion != '') {
                        if (row.religion == 1) {
                            return 'Hindu';
                        }
                        else if (row.religion == 2) {
                            return 'Muslim';
                        }
                        else if (row.religion == 3) {
                            return 'Christian';
                        }
                        else {
                            return '-';
                        }
                    }
                    else {
                        return '-';
                    }
                }
            },
            /* { "mDataProp": function ( row, type, val, meta ){
                if(row.edit_custom_entry_date==0){
                   return row.date_add+' </br> '+row.custom_entry_date;
                }
               else{
                       return row.custom_entry_date+'</br> '+row.date_add;
                  }
               }},		 */
            {
                "mDataProp": function (row, type, val, meta) {
                    active_url = base_url + "index.php/admin_customer/customer_status/" + (row.active == 1 ? 0 : 1) + "/" + row.id_customer;
                    return "<a href='" + active_url + "'><i class='fa " + (row.active == 1 ? 'fa-check' : 'fa-remove') + "' style='color:" + (row.active == 1 ? 'green' : 'red') + "'></i></a>"
                }
            },
            {
                "mDataProp": function (row, type, val, meta) {
                    profile_url = base_url + "index.php/customer/profile/status/" + (row.profile_complete == 1 ? 0 : 1) + "/" + row.id_customer;
                    return "<a href='" + profile_url + "'><i class='fa " + (row.profile_complete == 1 ? 'fa-thumbs-o-up' : 'fa-thumbs-o-down') + "' style='color:" + (row.profile_complete == 1 ? 'green' : 'red') + "'></i></a>"
                }
            },
            {
                "mDataProp": function (row, type, val, meta) {
                    if (row.kyc != null) {
                        id = row.id_customer;
                        action_content = '<a href="#" id="cus_kyc_del" class="btn-edit" onClick="cuskycdetail(' + id + ')"><i class="fa fa-eye" ></i> Detail</a>'
                    } else {
                        action_content = '-';
                    }
                    return action_content;
                    // 		return "<a id='kyc_cus_del'><i class='fa fa-eye' onClick='cuskycdetail(" + row.id_customer + ");'></i>Detail</a>";
                }
            },
            {
                "mDataProp": function (row, type, val, meta) {
                    if (row.village_name != '') {
                        return convert(row.village_name);
                    }
                    else {
                        return '-';
                    }
                }
            },
            { "mDataProp": "agent_name" },
            {
                "mDataProp": function (row, type, val, meta) {
                    return (row.added_by == '0' ? "Web" : (row.added_by == '1' ? "Admin" : (row.added_by == '2' ? "Mobile" : (row.added_by == '3' ? "Collection App" : (row.added_by == '4' ? "Retail" : (row.added_by == '5' ? "Sync" : (row.added_by == '6' ? "Import" : "-")))))));
                }
            },
            {
                "mDataProp": function (row, type, val, meta) {
                    id = row.id_customer;
                    edit_url = (access.edit == '1' ? base_url + 'index.php/customer/edit/' + id : '#');
                    delete_url = (access.delete == '1' ? base_url + 'index.php/customer/delete/' + id : '#');
                    delete_confirm = (access.delete == '1' ? '#confirm-delete' : '');
                    action_content = '<div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button><ul class="dropdown-menu">' +
                        '<li><a href="' + edit_url + '" class="btn-edit"><i class="fa fa-edit" ></i> Edit</a></li>' +
                        '<li><a href="#" class="btn-del" data-href="' + delete_url + '" data-toggle="modal" data-target="' + delete_confirm + '"  ><i class="fa fa-trash"></i> Delete</a></li></ul></div>';
                    return action_content;
                }
            }]
        });
    }
}
// code by jothika on 11-7-2025 [search using mobile]
$(document).ready(function () {
	console.log("Autocomplete initialized");
	$('#mobilenumber').autocomplete({
		source: function (request, response) {
			const mobile = $('#mobilenumber').val();
			$.ajax({
				url: base_url + "index.php/admin_customer/ajax_get_customers_list",
				dataType: 'json',
				type: 'POST',
				data: { 'mobile': mobile },
				success: function (data) {
					const cust_list = new Array(data.length);
					let i = 0;
					data.forEach(function (cust) {
						const customer = {
							label: cust.mobile + " " + cust.firstname,
							value: cust.mobile
						}
						cust_list[i] = customer;
						i++;
					});
					response(cust_list);
				},
			})
		},
		minLength: 3,
		delay: 300,
		response: function (e, i) {
			if (i.content.length == 0) {
				$.toaster({
					priority: 'danger',
					title: 'Warning!',
					message: 'Customer not available'
				});
				$('#mobilenumber').val('');
			}
		},
		select: function (e, i) {
			e.preventDefault();
			const selectedCustomer = i.item;
			// console.log("i.item keys:", Object.keys(selectedCustomer));              
			const selectedMobile = selectedCustomer.value;
			$('#mobilenumber').val(selectedMobile);
			// console.log("Selected mobile: ", selectedMobile);
			get_customer_list('', '', '', '', selectedMobile);
		}
	})
})
//get_without_acc_cus details
function get_without_acc_cus() {
    my_Date = new Date();
    $("div.overlay").css("display", "block");
    $.ajax({
        url: base_url + "index.php/customer/without_acc_details?nocache=" + my_Date.getUTCSeconds(),
        dataType: "JSON",
        type: "GET",
        success: function (data) {
            console.log(data);
            set_without_acc_cuslist(data);
            $("div.overlay").css("display", "none");
        },
        error: function (error) {
            $("div.overlay").css("display", "none");
        }
    });
}
function set_without_acc_cuslist(data) {
    var customer = data;
    //alert(customer);
    var oTable = $('#sch_acc_list').DataTable();
    oTable.clear().draw();
    if (customer != null && customer.length > 0) {
        oTable = $('#sch_acc_list').dataTable({
            "bDestroy": true,
            "bInfo": true,
            "bFilter": true,
            "bSort": true,
            "dom": 'T<"clear">lfrtip',
            "tableTools": { "aButtons": [{ "sExtends": "xls", "oSelectorOpts": { page: 'current' } }, { "sExtends": "pdf", "oSelectorOpts": { page: 'current' } }] },
            "aaData": customer,
            "order": [[0, "desc"]],
            "aoColumns": [{
                "mDataProp": function (row, type, val, meta) {
                    id = row.id_customer;
                    return "<label class='checkbox-inline'><input type='checkbox' class='flat-red' name='id_customer[]' value='" + row.id_customer + "' /> " + id + " </label>";
                }
            },
            { "mDataProp": "name" },
            { "mDataProp": "mobile" },
            { "mDataProp": "is_new" },
            { "mDataProp": "date_add" },
            { "mDataProp": "reg_by" },
            /* { "mDataProp": "closed_a/c" },
            { "mDataProp": "closing_balance" },
            { "mDataProp": "closing_date" }, */
            { "mDataProp": "profile_complete" },
            { "mDataProp": "active" },
            ]
        });
    }
}
function get_village() {
    $('.overlay').css('display', 'block');
    var from_date = $('#customer_list1').text();
    var to_date = $('#customer_list2').text();
    // console.log('From Date:' ,$from_date);
    $.ajax({
        type: 'GET',
        url: base_url + 'index.php/admin_settings/ajax_village_list?from_date=' + from_date + '&to_date=' + to_date + '',
        //   data: {
        //     from_date: from_date,
        //     to_date: to_date
        // },
        dataType: 'json',
        success: function (data) {
            $('#Village').append(
                $("<option></option>")
                    .attr("value", 0)
                    .text("ALL")
            );
            $('#village_select').append(
                $("<option></option>")
                    .attr("value", 0)
                    .text("ALL")
            );
            var id_village = $('#id_village').val();
            $.each(data.list, function (key, data) {
                $('#Village').append(
                    $("<option></option>")
                        .attr("value", data.id_village)
                        .text(data.village_name)
                );
                $('#village_select').append(
                    $("<option></option>")
                        .attr("value", data.id_village)
                        .text(data.village_name)
                );
            });
            if (ctrl_page[1] == 'edit' || ctrl_page[1] == 'add') {
                $("#Village").select2("val", (id_village != '' ? id_village : ''));
            }
            if (ctrl_page[0] == 'customer' && ctrl_page[1] != 'cus_profile') {
                $("#village_select").select2("val", (id_village != '' ? id_village : ''));
            }
            var selectid = $('#id_village').val();
            if (selectid != null && selectid > 0) {
                $('#Village').val(selectid);
                $('.overlay').css('display', 'block');
            }
            $('.overlay').css('display', 'none');
        },
        error: function (error) {
            $("div.overlay").css("display", "none");
        }
    });
}
$('#Village').select2().on("change", function (e) {
    if (this.value != '') {
        var id = $(this).val();
        $('#id_village').val(id);
        // get_village_list(id);
        if (ctrl_page[1] == 'cus_profile') {
            calculate_profile_percentage();
        }
    }
});
$('#village_select').select2().on("change", function (e) {
    if (this.value != '') {
        var id = $(this).val();
        $('#id_village').val(id);
        var from_date = $("#customer_list1").text();
        var to_date = $("#customer_list2").text();
        get_customer_list(from_date, to_date, '', id)
    }
});
$('#religion_select').on("change", function (e) {
    if (this.value != '') {
        $('#id_religion').val(this.value);
        var from_date = $("#customer_list1").text();
        var to_date = $("#customer_list2").text();
        var id_village = $("#village_select").val();
        get_customer_list(from_date, to_date, '', id_village)
    }
});
function get_village_list(id_village) {
    $.ajax({
        type: 'POST',
        data: { 'id_village': id_village },
        url: base_url + 'index.php/admin_settings/ajax_village_list',
        dataType: 'json',
        success: function (data) {
            $('#post_office').val(data.post_office);
            $('#taluk').val(data.taluk);
            $('#pin_code_add').val(data.pincode);
        }
    });
}
//Customer Profile Updation
$("#search_customer").on("keyup", function (e) {
    var customer = $("#search_customer").val();
    if (customer.length >= 5) {
        getSearchCustomers(customer);
    }
});
function getSearchCustomers(searchTxt) {
    my_Date = new Date();
    $.ajax({
        url: base_url + 'index.php/admin_customer/cus_profile/edit?nocache=' + my_Date.getUTCSeconds(),
        dataType: "json",
        method: "POST",
        data: { 'searchTxt': searchTxt },
        success: function (data) {
            $("#search_customer").autocomplete(
                {
                    source: data,
                    select: function (e, i) {
                        e.preventDefault();
                        var firstname = i.item.firstname;
                        $('#search_customer').val(i.item.label);
                        $('#id_customer').val(i.item.value);
                        $('#lastname').val(i.item.lastname);
                        $('#email').val(i.item.email);
                        $('#countryval').val(i.item.id_country);
                        $('#stateval').val(i.item.id_state);
                        $('#cityval').val(i.item.id_city);
                        $('#id_village').val(i.item.id_village);
                        $('#address1').val(i.item.address1);
                        $('#address2').val(i.item.address2);
                        $('#address3').val(i.item.address3);
                        $('.dob').val(i.item.date_of_birth);
                        $('.wedding').val(i.item.date_of_wed);
                        $('#firstname').val(firstname);
                        $("#religion_select").val(i.item.religion);
                        $("#pin_code_add").val(i.item.pincode);
                        if (i.item.send_promo_sms == 1) {
                            $('#show_gift_article').bootstrapSwitch('state', true);
                        }
                        if (i.item.gender == 0) {
                            $('#gender_male').prop('checked', true);
                        } else if (i.item.gender == 1) {
                            $('#gender_female').prop('checked', true);
                        } else {
                            $('#gender_others').prop('checked', true);
                        }
                        get_country();
                        get_village();
                        calculate_profile_percentage();
                    },
                    change: function (event, ui) {
                        if (ui.item === null) {
                            $(this).val('');
                            $('#bill_cus_name').val('');
                            $("#bill_cus_id").val("");
                            $("#cus_village").html("");
                            $("#cus_info").html("");
                            /*$("#chit_cus").html("");
                            $("#vip_cus").html("");*/
                        }
                    },
                    response: function (e, i) {
                        // ui.content is the array that's about to be sent to the response callback.
                        if (searchTxt != "") {
                            if (i.content.length === 0) {
                                $("#customerAlert").html('<p style="color:red">Enter a valid customer name / mobile</p>');
                            } else {
                                $("#customerAlert").html('');
                            }
                        } else {
                        }
                    },
                    minLength: 3,
                });
        }
    });
}
$('#firstname,#lastname,#email,#address1,#address2,#address3,#pin_code_add').on('keyup', function () {
    if (ctrl_page[1] == 'cus_profile') {
        calculate_profile_percentage();
    }
});
$('#datepicker1,#datepicker1').on('keyup', function () {
    calculate_profile_percentage();
});
$('#religion_select').on('change', function () {
    if (ctrl_page[1] == 'cus_profile') {
        calculate_profile_percentage();
    }
});
function calculate_profile_percentage() {
    var sum = 0;
    if ($('#firstname').val() != '' && $('#firstname').val() != null) {
        sum = sum + 10;
    }
    if ($('#lastname').val() != '' && $('#lastname').val() != null) {
        sum = sum + 10;
    }
    if ($('#email').val() != '' && $('#email').val() != null) {
        sum = sum + 10;
    }
    if ($('#country').val() != '' && $('#country').val() != null) {
        sum = sum + 10;
    }
    if ($('#state').val() != '' && $('#state').val() != null) {
        sum = sum + 10;
    }
    if ($('#city').val() != '' && $('#city').val() != null) {
        sum = sum + 10;
    }
    if ($('#address1').val() != '' && $('#address1').val() != null) {
        sum = sum + 5;
    }
    if ($('#address2').val() != '' && $('#address2').val() != null) {
        sum = sum + 5;
    }
    if ($('#address3').val() != '' && $('#address3').val() != null) {
        sum = sum + 5;
    }
    if ($('#Village').val() != '' && $('#Village').val() != null) {
        sum = sum + 10;
    }
    if ($('#pin_code_add').val() != '' && $('#pin_code_add').val() != null) {
        sum = sum + 5;
    }
    if ($('#religion_select').val() != '' && $('#religion_select').val() != null) {
        sum = sum + 5;
    }
    if ($('#datepicker').val() != '' && $('#datepicker').val() != null) {
        sum = sum + 5;
    }
    if ($('#datepicker1').val() != '' && $('#datepicker1').val() != null) {
        sum = sum + 5;
    }
    $('#progress_bar').css('display', 'block');
    $('.progress-bar').css('width', sum + '%').attr('aria-valuenow', sum);
    $('.progress').attr("aria-valuenow", sum);
    $('.skill').html(sum + '%');
}
$('#update_profile').on('click', function () {
    if ($('#id_customer').val() == '') {
        msg = '<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Please Select Customer</div>';
        $('#chit_alert1').html(msg);
    }
    else if ($('#firstname').val() == '') {
        msg = '<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Please Enter  First Name </div>';
        $('#chit_alert1').html(msg);
    }
    else if ($('#country').val() == '') {
        msg = '<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Please Select  Country </div>';
        $('#chit_alert1').html(msg);
    }
    else if ($('#state').val() == '') {
        msg = '<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Please Select  State </div>';
        $('#chit_alert1').html(msg);
    }
    else if ($('#city').val() == '') {
        msg = '<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Please Select  City </div>';
        $('#chit_alert').html(msg);
    }
    else if ($('#address1').val() == '') {
        msg = '<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Please Enter The Address </div>';
        $('#chit_alert1').html(msg);
    }
    else if ($('#id_village').val() == '') {
        msg = '<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Please Select Village </div>';
        $('#chit_alert1').html(msg);
    }
    else if ($('#datepicker').val() == '') {
        msg = '<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Please Select  Date of Birth </div>';
        $('#chit_alert1').html(msg);
    }
    else if ($('#pin_code_add').val() != '' && $('#pin_code_add').val().length < 6) {
        var pincode = $("#pin_code_add").val();
        $("#pin_code_add").val("");
        $('#pin_code_add').attr('placeholder', 'Not a valid Pincode.');
        $("#pin_code_add").focus();
        msg = '<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Please Enter a valid pincode </div>';
        $('#chit_alert1').html(msg);
    }
    else {
        $(".overlay").css("display", "block");
        var form_data = $('#cus_profile').serialize();
        my_Date = new Date();
        $.ajax({
            url: base_url + 'index.php/customer/cus_profile/update/' + $('#id_customer').val() + '/?nocache=' + my_Date.getUTCSeconds(),
            dataType: "json",
            method: "POST",
            data: form_data,
            success: function (data) {
                window.location.reload();
            }
        });
        $(".overlay").css("display", "none");
    }
});
//Customer Profile Updation
$("input[name='customer[cus_type]']:radio").on('change', function () {
    if (this.value == 1) {
        $('#cus_name').html('First Name');
        $('#last_name').css("display", "block");
        $('#gstno').css("display", "none");
        $('#pan_no').css("display", "none");
        $('#gst_number').prop('required', false);
        $('#pan').prop('required', false);
    } else {
        $('#last_name').css("display", "none");
        $('#gstno').css("display", "block");
        $('#pan_no').css("display", "block");
        $('#cus_name').html('Company Name');
        $('#gst_number').prop('required', true);
        $('#pan').prop('required', true);
    }
});
function fnCusItemsExcelReport(export_type) {
    console.log(export_list);
    if (export_list.length >= 1) {
        var htmls = "";
        htmls += '<table class="table table-bordered table-striped text-center"><thead><tr class="bg-teal"><th colspan=23 style="background-color:#39cccc;text-align:center;"> Customer List</th></tr>' +
            '<tr><th width="5%;">ID</th>' +
            '<th width="10%;">Name</th>' +
            '<th width="10%;">Mobile</th>' +
            '<th width="5%;">Account</th>' +
            '<th width="10%;">Member Since</th>' +
            '<th width="5%;">Status</th>' +
            '<th width="5%;">Profile</th>' +
            '<th width="10%;">Created Through</th>' +
            '<th width="5%;">State</th>' +
            '<th width="10%;">City</th>' +
            '<th width="5%;">Pincode</th>' +
            '<th width="10%;">PAN</th>' +
            '<th width="10%;">Aadhaar</th>' +
            '<th width="10%;">DOB</th>' +
            '<th width="10%;">DOW</th>' +
            '<th width="5%;">Age</th>' +
            '<th width="10%;">Address</th>' +
            '<th width="5%;">Gender</th>' +
            '<th width="10%;">Email</th>' +
            '<th width="10%;">Area Name</th>' +
            '<th width="10%;">Custom Date Entry</th>' +
            '<th width="10%;">Religion</th>' +
            '<th width="10%;">Allocated Agent</th>' +
            '</tr></thead><tbody>';
        $.each(export_list, function (index, val) {
            var datas = "";
            var title = val.title != null ? val.title + ". " : '';
            title = title + "" + val.name;
            var date_add = val.edit_custom_entry_date == 0 ? val.date_add + ' </br> ' + val.custom_entry_date : val.custom_entry_date + '</br> ' + val.date_add;
            var active = val.active == 1 ? "Active" : "Inactive";
            var profile_complete = val.profile_complete == 1 ? "Complete" : "Incomplete";
            var added_by = val.added_by == 0 ? "Web" : (val.added_by == 1 ? "Admin" : "Mobile");
            var state = val.state != null ? val.state : '';
            var city = val.city != null ? val.city : '';
            var pincode = val.pincode != null ? val.pincode : '';
            var pan = val.pan != null ? val.pan : '';
            var aadhaar = val.aadharid != null ? val.aadharid : '';
            var dob = val.date_of_birth != null ? val.date_of_birth : '';
            var dow = val.date_of_wed != null ? val.date_of_wed : '';
            var age = val.age != null ? val.age : '';
            var address1 = val.address1 != null ? val.address1 : '';
            var address2 = val.address2 != null ? val.address2 : '';
            var address3 = val.address3 != null ? val.address3 : '';
            var gender = val.gender != null ? val.gender : '';
            var email = val.email != null ? val.email : '';
            var areaName = val.village_name != null ? val.village_name : '';
            var custom_entry_date = val.custom_entry_date != null ? val.custom_entry_date : '';
            var religion = val.religion != null ? (val.religion == 1 ? 'hindu' : (val.religion == 2 ? 'Muslim' : '')) : 'christian';
            var agent_name = val.agent_name != null ? val.agent_name : '';
            htmls += '<tr><td style="width: 5%;color:red;">' + val.id_customer + '</td>' +
                '<td style="width: 10%;">' + title + '</td>' +
                '<td style="width: 10%;">' + val.mobile + '</td>' +
                '<td style="width: 5%;">' + val.accounts + '</td>' +
                '<td style="width: 10%;">' + date_add + '</td>' +
                '<td style="width: 5%;">' + active + '</td>' +
                '<td style="width: 5%;">' + profile_complete + '</td>' +
                '<td style="width: 10%;">' + added_by + '</td>' +
                '<td style="width: 5%;">' + state + '</td>' +
                '<td style="width: 10%;">' + city + '</td>' +
                '<td style="width: 5%;">' + pincode + '</td>' +
                '<td style="width: 10%;">' + pan + '</td>' +
                '<td style="width: 10%;">' + aadhaar + '</td>' +
                '<td style="width: 10%;">' + dob + '</td>' +
                '<td style="width: 10%;">' + dow + '</td>' +
                '<td style="width: 5%;">' + age + '</td>' +
                '<td style="width: 10%;">' + address1 + ', ' + address2 + ', ' + address3 + '</td>' +
                '<td style="width: 5%;">' + gender + '</td>' +
                '<td style="width: 10%;">' + email + '</td>' +
                '<td style="width: 10%;">' + areaName + '</td>' +
                '<td style="width: 10%;">' + custom_entry_date + '</td>' +
                '<td style="width: 10%;">' + religion + '</td>' +
                '<td style="width: 10%;">' + agent_name + '</td></tr>';
        });
        htmls += '</tbody><tfoot></tfoot></table>';
        if (export_type == '1') {
            var uri = 'data:application/vnd.ms-excel;base64,';
            var template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>';
            var base64 = function (s) {
                return window.btoa(unescape(encodeURIComponent(s)))
            };
            var format = function (s, c) {
                return s.replace(/{(\w+)}/g, function (m, p) {
                    return c[p];
                })
            };
            var ctx = {
                worksheet: 'Worksheet',
                table: htmls
            }
            var link = document.createElement("a");
            link.download = "Customer_list.xls";
            link.href = uri + base64(format(template, ctx));
            link.click();
        }
    }
}
// agent allocation for customer worked for CJ
//bulk allocate agent to customer
$("#bulk_allocate_agent").click(function () {
    var data = { 'id_customer[]': [] };
    $("input[name='id_customer[]']:checked").each(function () {
        data['id_customer[]'].push($(this).val());
    });
    if (data['id_customer[]'].length > 0) {
        get_agent_select();
        $('#allocate_agent_modal').modal('show');
    } else {
        $.toaster({ priority: 'danger', title: 'Warning!', message: '' + "</br> Select customer to allocate..." });
    }
});
$("#allocate_agent_btn").click(function () {
    var id_agent = $('#agent_select').val();
    var data = { 'id_customer[]': [], 'id_agent': id_agent };
    $("input[name='id_customer[]']:checked").each(function () {
        data['id_customer[]'].push($(this).val());
    });
    if (id_agent == '' || id_agent == null || id_agent == undefined) {
        $.toaster({ priority: 'danger', title: 'Warning!', message: '' + "</br> Select Agent to allocate..." });
    } else {
        $.ajax({
            type: "POST",
            url: base_url + "index.php/admin_customer/allocate_agent_toCuctomers",
            data: data,
            dataType: "json",
            sync: false,
            success: function (data) {
                $("div.overlay").css("display", "none");
                $('#allocate_agent_modal').modal('hide');
                //$('input[name='id_customer[]']').prop('checked', false);
                if (data.status == 1) {
                    $.toaster({ priority: 'success', title: 'Success!', message: '' + "</br> Agent allocated successfully for " + data.total + "..." });
                } else if (data.status == 2) {
                    $.toaster({ priority: 'success', title: 'Success!', message: '' + "</br> Agent allocated successfully for " + data.total + " only..." });
                    $.toaster({ priority: 'danger', title: 'Warning!', message: '' + "</br> Agent not allocated for " + data.not_allocated + "..." });
                } else {
                    $.toaster({ priority: 'danger', title: 'Warning!', message: '' + "</br> Unable to update agent for the selected customers..." });
                }
                window.location.reload();
            }
        });
    }
});
function get_agent_select() {
    $('#agent_select option').remove();
    $.ajax({
        type: 'GET',
        url: base_url + 'index.php/admin_customer/ajax_getAllActiveAgents',
        dataType: 'json',
        success: function (data) {
            console.log(data);
            $('#agent_select').append(
                $("<option></option>")
                    .attr("value", '')
                    .text('Select Agent')
            );
            $.each(data, function (key, data) {
                $('#agent_select').append(
                    $("<option></option>")
                        .attr("value", data.id_agent)
                        .text(data.agent_data)
                );
            });
            $("#agent_select").select2("val", ($('#agent_select').val() != null ? $('#agent_select').val() : ''));
            var selectid = $('#agent_select').val();
            if (selectid != null && selectid > 0) {
                $('#id_agent').val(selectid);
            }
        },
        error: function (error) {
            $("div.overlay").css("display", "none");
        }
    });
}
// agent allocation ends...
function get_profession() {
    $('.overlay').css('display', 'block');
    $.ajax({
        type: 'GET',
        url: base_url + 'index.php/settings/company/getprofession',
        dataType: 'json',
        success: function (data) {
            $.each(data, function (key, data) {
                $('#profession').append(
                    $("<option></option>")
                        .attr("value", data.id_profession)
                        .text(data.name)
                );
            });
            $("#profession").select2("val", ($('#professionval').val() != null ? $('#professionval').val() : ''));
            $('.overlay').css('display', 'none');
        },
        error: function (error) {
            $("div.overlay").css("display", "none");
        }
    });
}
/*webcam upload, #AD On:20-12-2022,Cd:CLin,Up:AB  -->starts
updated ON: 21/06/2023 BY:Abi
for kyc tab in customer master to take snapshots...
updated functions take_snapshot,remove_stn_img
*/
function take_snapshot(type) {
    //Snap Shots Disables
    if (type == 'pre_images') {
        var pre_img_resource = [];
        var pre_img_files = [];
        $('#snap_shots').prop('disabled', true);
        var preview = 'uploadArea_p_stn';
        Webcam.snap(function (data_uri) {
            $(".image-cust").val(data_uri);
            pre_img_resource.push({ 'src': data_uri, 'name': (Math.floor(100000 + Math.random() * 900000)) + 'jpg', 'is_default': "0" });
            pre_img_files.push(data_uri);
            alert("Your Webcam Images Take Snap Shot Successfullys.");
        });
        $('#customer_images').val(encodeURIComponent(JSON.stringify(pre_img_resource)));
        console.log(pre_img_resource);
        var show = $('#cus_img_preview');
        show.prop('src', pre_img_resource[0].src);
        show.css('display', 'block');
        setTimeout(function () {
            var resource = [];
            $('#' + preview + ' div').remove();
            if (type == 'pre_images') {
                resource = pre_img_resource;
            }
            $.each(resource, function (key, item) {
                if (item) {
                    var div = document.createElement("div");
                    div.setAttribute('class', 'images');
                    div.setAttribute('id', +key);
                    param = { "key": key, "preview": preview, "stone_type": type };
                    div.innerHTML += "<span style='float:left;'><a onclick='remove_stn_img(" + JSON.stringify(param) + ")' style='cursor:pointer;color:inherit;' data-toggle='tooltip' data-placement='bottom' title='Delete This Images'>&nbsp;<i class='fa fa-trash'></i></a></span>&nbsp;&nbsp;<img class='thumbnail' src='" + item.src + "'" + "style='width: 100px;height: 100px;'/>";
                    $('#' + preview).append(div);
                }
                $('#lot_img_upload').css('display', '');
            });
            $('#snap_shots').prop('disabled', false);
        }, 100);
    }
    else if (type == 'pandetails_images') {
        if ($("input[type='radio'][name='pancardside_type']").is(":checked")) {
            var preview = 'uploadArea_p_stn_pan';
            Webcam.snap(function (data_uri) {
                $(".image-cust").val(data_uri);
                pre_img_pan_resource.push({ 'src': data_uri, 'name': (Math.floor(100000 + Math.random() * 900000)) + 'jpg', 'is_default': "0" });
                pre_panimg_files.push(data_uri);
                alert("Your Webcam Images Take Snap Shot Successfullys.");
            });
            if (pre_img_pan_resource.length <= 2) {
                if ($("#front_side_pan_type").is(":checked")) {
                    $('#pan_images_front').val(encodeURIComponent(JSON.stringify(pre_img_pan_resource[0])));
                }
                if ($("#back_side_pan_type").is(":checked")) {
                    $('#pan_images_back').val(encodeURIComponent(JSON.stringify(pre_img_pan_resource[1])));
                }
                var show = $('#pan_img_preview');
                show.prop('src', pre_img_pan_resource.src);
                show.css('display', 'block');
                setTimeout(function () {
                    // var resource = [];
                    $('#' + preview + ' div').remove();
                    if (type == 'pandetails_images') {
                        resource_pan = pre_img_pan_resource;
                    }
                    var card_radio_value = $('input[name="cardside_type"]:checked').val()
                    $.each(resource_pan, function (key, item) {
                        if (item) {
                            var div = document.createElement("div");
                            div.setAttribute('class', 'images');
                            div.setAttribute('id', +key);
                            param = { "key": key, "preview": preview, "stone_type": type };
                            div.innerHTML += "<span style='float:left;'><a onclick='remove_stn_img(" + JSON.stringify(param) + ")' style='cursor:pointer;color:inherit;' data-toggle='tooltip' data-placement='bottom' title='Delete This Images'>&nbsp;<i class='fa fa-trash'></i></a></span>&nbsp;&nbsp;<img class='thumbnail' src='" + item.src + "'" + "style='width: 100px;height: 100px;'/>";
                            $('#' + preview).append(div);
                        }
                        $('#lot_img_upload').css('display', '');
                    });
                }, 100);
            } else {
                $('#pansnap_shots').prop('disabled', false);
                alert(' upload 2 images Only ')
            }
        } else {
            alert('Select image type')
        }
    }
    else if (type == 'aadhardetails_images') {
        if ($("input[type='radio'][name='adhaarcardside_type']").is(":checked")) {
            var preview = 'uploadArea_p_stn_aadhar';
            Webcam.snap(function (data_uri) {
                $(".image-cust").val(data_uri);
                pre_img_adhaar_resource.push({ 'src': data_uri, 'name': (Math.floor(100000 + Math.random() * 900000)) + 'jpg', 'is_default': "0" });
                pre_adhaarimg_files.push(data_uri);
                alert("Your Webcam Images Take Snap Shot Successfullys.");
            });
            if (pre_img_adhaar_resource.length <= 2) {
                console.log(pre_img_adhaar_resource)
                console.log(pre_adhaarimg_files)
                if ($("#front_side_adhaar_type").is(":checked")) {
                    $('#adhaar_images_front').val(encodeURIComponent(JSON.stringify(pre_img_adhaar_resource[0])));
                }
                if ($("#back_side_adhaar_type").is(":checked")) {
                    $('#adhaar_images_back').val(encodeURIComponent(JSON.stringify(pre_img_adhaar_resource[1])));
                }
                var show = $('#aadhar_img_preview');
                show.prop('src', pre_img_adhaar_resource.src);
                show.css('display', 'block');
                setTimeout(function () {
                    //	var resource_adhaar = [];
                    $('#' + preview + ' div').remove();
                    if (type == 'aadhardetails_images') {
                        resource_adhaar = pre_img_adhaar_resource;
                    }
                    var card_radio_value = $('input[name="adhaarcardside_type"]:checked').val()
                    $.each(resource_adhaar, function (key, item) {
                        if (item) {
                            var div = document.createElement("div");
                            div.setAttribute('class', 'images');
                            div.setAttribute('id', +key);
                            param = { "key": key, "preview": preview, "stone_type": type };
                            div.innerHTML += "<span style='float:left;'><a onclick='remove_stn_img(" + JSON.stringify(param) + ")' style='cursor:pointer;color:inherit;' data-toggle='tooltip' data-placement='bottom' title='Delete This Images'>&nbsp;<i class='fa fa-trash'></i></a></span>&nbsp;&nbsp;<img class='thumbnail' src='" + item.src + "'" + "style='width: 100px;height: 100px;'/>";
                            $('#' + preview).append(div);
                        }
                        $('#lot_img_upload').css('display', '');
                    });
                }, 100);
            } else {
                $('#adhaarsnap_shots').prop('disabled', false);
                alert(' upload 2 images Only ')
            }
        } else {
            alert('Select image Type ')
        }
    }
    else if (type == 'dldetails_images') {
        var pre_img_resource = [];
        var pre_img_files = [];
        $('#snap_shots').prop('disabled', true);
        var preview = 'uploadArea_p_stn_dl';
        Webcam.snap(function (data_uri) {
            $(".image-dl").val(data_uri);
            pre_img_resource.push({ 'src': data_uri, 'name': (Math.floor(100000 + Math.random() * 900000)) + 'jpg', 'is_default': "0" });
            pre_img_files.push(data_uri);
            alert("Your Webcam Images Take Snap Shot Successfullys.");
        });
        $('#dl_images').val(encodeURIComponent(JSON.stringify(pre_img_resource)));
        console.log(pre_img_resource);
        var show = $('#dl_img_preview');
        show.prop('src', pre_img_resource[0].src);
        show.css('display', 'block');
        setTimeout(function () {
            var resource = [];
            $('#' + preview + ' div').remove();
            if (type == 'dldetails_images') {
                resource = pre_img_resource;
            }
            $.each(resource, function (key, item) {
                if (item) {
                    var div = document.createElement("div");
                    div.setAttribute('class', 'images');
                    div.setAttribute('id', +key);
                    param = { "key": key, "preview": preview, "stone_type": type };
                    div.innerHTML += "<span style='float:left;'><a onclick='remove_stn_img(" + JSON.stringify(param) + ")' style='cursor:pointer;color:inherit;' data-toggle='tooltip' data-placement='bottom' title='Delete This Images'>&nbsp;<i class='fa fa-trash'></i></a></span>&nbsp;&nbsp;<img class='thumbnail' src='" + item.src + "'" + "style='width: 100px;height: 100px;'/>";
                    $('#' + preview).append(div);
                }
                $('#lot_img_upload').css('display', '');
            });
            $('#snap_shots').prop('disabled', false);
        }, 100);
    }
    else if (type == 'ppdetails_images') {
        var pre_img_resource = [];
        var pre_img_files = [];
        $('#snap_shots').prop('disabled', true);
        var preview = 'uploadArea_p_stn_pp';
        Webcam.snap(function (data_uri) {
            $(".image-pp").val(data_uri);
            pre_img_resource.push({ 'src': data_uri, 'name': (Math.floor(100000 + Math.random() * 900000)) + 'jpg', 'is_default': "0" });
            pre_img_files.push(data_uri);
            alert("Your Webcam Images Take Snap Shot Successfullys.");
        });
        $('#pp_images').val(encodeURIComponent(JSON.stringify(pre_img_resource)));
        console.log(pre_img_resource);
        var show = $('#pp_img_preview');
        show.prop('src', pre_img_resource[0].src);
        show.css('display', 'block');
        setTimeout(function () {
            var resource = [];
            $('#' + preview + ' div').remove();
            if (type == 'ppdetails_images') {
                resource = pre_img_resource;
            }
            $.each(resource, function (key, item) {
                if (item) {
                    var div = document.createElement("div");
                    div.setAttribute('class', 'images');
                    div.setAttribute('id', +key);
                    param = { "key": key, "preview": preview, "stone_type": type };
                    div.innerHTML += "<span style='float:left;'><a onclick='remove_stn_img(" + JSON.stringify(param) + ")' style='cursor:pointer;color:inherit;' data-toggle='tooltip' data-placement='bottom' title='Delete This Images'>&nbsp;<i class='fa fa-trash'></i></a></span>&nbsp;&nbsp;<img class='thumbnail' src='" + item.src + "'" + "style='width: 100px;height: 100px;'/>";
                    $('#' + preview).append(div);
                }
                $('#lot_img_upload').css('display', '');
            });
            $('#snap_shots').prop('disabled', false);
        }, 100);
    }
}
function remove_stn_img(param) {
    console.log(param)
    if (param.stone_type == 'pre_images') {
        //  var current_status   = $(".tag_default_"+param.key).is(':checked');
        $('#' + param.preview + ' #' + param.key).remove();
        $('#customer_images').val();
        var preview = $('#cus_img_preview');
        preview.prop('src', '');
        preview.css('display', 'none');
        $('#customer_images').val();
    }
    if (param.stone_type == 'bankdetails_images') {
        pre_img_bank_resource.splice(param.key, 1);
        console.log(pre_img_bank_resource)
        $('#' + param.preview + ' #' + param.key).remove();
        $('#uploadArea_p_stn_bank').empty()
        $.each(pre_img_bank_resource, function (key, item) {
            if (item) {
                var div = document.createElement("div");
                div.setAttribute('class', 'images');
                div.setAttribute('id', +key);
                param = { "key": key, "preview": preview, "stone_type": param.stone_type };
                div.innerHTML += "<span style='float:left;'><a onclick='remove_stn_img(" + JSON.stringify(param) + ")' style='cursor:pointer;color:inherit;' data-toggle='tooltip' data-placement='bottom' title='Delete This Images'>&nbsp;<i class='fa fa-trash'></i></a></span>&nbsp;&nbsp;<img class='thumbnail' src='" + item.src + "'" + "style='width: 100px;height: 100px;'/>";
                $('#uploadArea_p_stn_bank').append(div);
                console.log(div)
            }
            $('#lot_img_upload').css('display', '');
        });
        if ($('input[name="bankcardside_type"]:checked').val() == 1) {
            $('#bank_images_front').val('');
        }
        if ($('input[name="bankcardside_type"]:checked').val() == 2) {
            $('#bank_images_back').val('');
        }
    }
    if (param.stone_type == 'pandetails_images') {
        var current_status = $(".tag_default_" + param.key).is(':checked');
        $('#' + param.preview + ' #' + param.key).remove();
        $('#kyc_pan_images').val('');
        // $('#pan_img_data').val('');
        /*var preview = $('#pan_proof_preview');
        preview.prop('src','');
        preview.css('display','none');		*/
        $('#pan_images').val('');
    }
    if (param.stone_type == 'aadhardetails_images') {
        $('#' + param.preview + ' #' + param.key).remove();
        $('#aadhar_images').val('');
        $('#kyc_aadhar_images').val('');
    }
    if (param.stone_type == 'dldetails_images') {
        $('#' + param.preview + ' #' + param.key).remove();
        $('#dl_images').val('');
        $('#kyc_dl_images').val('');
    }
    if (param.stone_type == 'ppdetails_images') {
        $('#' + param.preview + ' #' + param.key).remove();
        $('#ppdetails_images').val('');
        $('#kyc_pp_images').val('');
    }
    /* if (param.stone_type == 'pandetails_images' ){
      pre_img_pan_resource.splice(param.key,1);
           $('#'+param.preview+' #'+param.key).remove();
           $('#uploadArea_p_stn_pan').empty()
          $.each(pre_img_pan_resource,function(key,item){
              if(item){
              var div = document.createElement("div");
              div.setAttribute('class','images');
              div.setAttribute('id',+key);
              param = {"key":key,"preview":preview,"stone_type":param.stone_type};
              div.innerHTML+= "<span style='float:left;'><a onclick='remove_stn_img("+JSON.stringify(param)+")' style='cursor:pointer;color:inherit;' data-toggle='tooltip' data-placement='bottom' title='Delete This Images'>&nbsp;<i class='fa fa-trash'></i></a></span>&nbsp;&nbsp;<img class='thumbnail' src='" + item.src + "'" +				"style='width: 100px;height: 100px;'/>";
              $('#uploadArea_p_stn_pan').append(div);
          console.log(div)		   	}
              $('#lot_img_upload').css('display','');
          });
          if($('input[name="pancardside_type"]:checked').val()==1){
              $('#pan_images_front').val('');
          }
          if($('input[name="pancardside_type"]:checked').val()==2){
              $('#pan_images_back').val('');
          }
   }
   if( param.stone_type == 'aadhardetails_images' )
   {
      pre_img_adhaar_resource.splice(param.key,1);
           $('#'+param.preview+' #'+param.key).remove();
           $('#uploadArea_p_stn_adhaar').empty()
          $.each(pre_img_adhaar_resource,function(key,item){
              if(item){
              var div = document.createElement("div");
              div.setAttribute('class','images');
              div.setAttribute('id',+key);
              param = {"key":key,"preview":preview,"stone_type":param.stone_type};
              div.innerHTML+= "<span style='float:left;'><a onclick='remove_stn_img("+JSON.stringify(param)+")' style='cursor:pointer;color:inherit;' data-toggle='tooltip' data-placement='bottom' title='Delete This Images'>&nbsp;<i class='fa fa-trash'></i></a></span>&nbsp;&nbsp;<img class='thumbnail' src='" + item.src + "'" +				"style='width: 100px;height: 100px;'/>";
              $('#uploadArea_p_stn_adhaar').append(div);
          }
              $('#lot_img_upload').css('display','');
          });
          if($('input[name="adhaarcardside_type"]:checked').val()==1){
              $('#adhaar_images_front').val('');
          }
          if($('input[name="adhaarcardside_type"]:checked').val()==2){
              $('#adhaar_images_back').val('');
          }
   }
   if (param.stone_type == 'licencedetails_images'){
      pre_img_licence_resource.splice(param.key,1);
           $('#'+param.preview+' #'+param.key).remove();
           $('#licencedetails_images').empty()
          $.each(pre_img_licence_resource,function(key,item){
              if(item){
              var div = document.createElement("div");
              div.setAttribute('class','images');
              div.setAttribute('id',+key);
              param = {"key":key,"preview":preview,"stone_type":param.stone_type};
              div.innerHTML+= "<span style='float:left;'><a onclick='remove_stn_img("+JSON.stringify(param)+")' style='cursor:pointer;color:inherit;' data-toggle='tooltip' data-placement='bottom' title='Delete This Images'>&nbsp;<i class='fa fa-trash'></i></a></span>&nbsp;&nbsp;<img class='thumbnail' src='" + item.src + "'" +				"style='width: 100px;height: 100px;'/>";
              $('#licencedetails_images').append(div);
          console.log(div)		   	}
              $('#lot_img_upload').css('display','');
          });
          if($('input[name="licencecardside_type"]:checked').val()==1){
              alert($('input[name="licencecardside_type"]:checked').val())
              $('#licence_images_front').val('');
          }
          if($('input[name="licencecardside_type"]:checked').val()==2){
              $('#licence_images_back').val('');
          }
   }*/
}
//webcam upload ends...
$("#save").on('click', function (event) {
    if ($("#pan").val() != '') {
        validate_pan();
    }
});
$("#aadharid,#mobile,#passwd").bind("cut copy paste", function (e) {
    e.preventDefault();
});
$("#others_tab").on('click', function (event) {
    others_click = 1;
});
$("#save").on('click', function (event) {
    if (others_click == 0) {
        if ($("#pan").val() != '') {
            validate_pan();
        }
    }
    else {
        var nom_name = $("#nominee_name").val();
        if (nom_name != '') {
            others_click = 1;
            var nom_mobile = $("#nominee_mobile").val();
            var pan_proof = $("#pan_proof_img").val();
            var voterId = $("#voterid_proof_img").val();
            var ration = $("#rationcard_proof_img").val();
            console.log(pan_proof);
            console.log(voterId);
            console.log(ration);
            if (pan_proof == '' && voterId == '' && ration == '') {
                $.toaster({ priority: 'danger', title: 'Warning!', message: '' + "</br> Please Link Any one valid proof" });
                return false;
            }
            else if (nom_mobile != '') {
                if (nom_mobile.length == 10) {
                    var regexp = /^([0-9]{10})?$/;
                    if (!regexp.test(nom_mobile)) {
                        $.toaster({ priority: 'danger', title: 'Warning!', message: '' + "</br> Enter Valid Nominee Mobile Number" });
                        $("#nominee_mobile").val("");
                        $("#nominee_mobile").attr('placeholder', 'Enter Valid Nominee Mobile Number');
                        $("#nominee_mobile").focus();
                        return false;
                    }
                }
                else {
                    $.toaster({ priority: 'danger', title: 'Warning!', message: '' + "</br> Enter Valid Nominee Mobile Number" });
                    $("#nominee_mobile").val("");
                    $("#nominee_mobile").attr('placeholder', 'Enter Valid Nominee Mobile Number');
                    $("#nominee_mobile").focus();
                    return false;
                }
            }
            else if (nom_mobile == '') {
                $.toaster({ priority: 'danger', title: 'Warning!', message: '' + "</br> Enter Valid Nominee Mobile Number" });
                $("#nominee_mobile").val("");
                $("#nominee_mobile").attr('placeholder', 'Enter Valid Nominee Mobile Number');
                $("#nominee_mobile").focus();
                return false;
            }
            else {
                others_click = 0;
                return true;
            }
        }
        else {
            others_click = 0;
        }
    }
});
// 			function calculateAge(selectedDate)
// 			{
// 				if(selectedDate)
// 				{
// 					var today = new Date();
// 					var dateParts = selectedDate.split('/');
// 					var dob_year=dateParts[2];
//   					var age = today.getFullYear() - dob_year;
// 					$("#age").val(age);
// 				}
// 			  }
//  calculate age ---14-12-23--- start-- santhosh
function calculateAge(selectedDate) {
    if (selectedDate) {
        // Check for a valid date format (MM/DD/YYYY)
        var dateRegex = /^\d{2}\/\d{2}\/\d{4}$/;
        if (!dateRegex.test(selectedDate)) {
            console.error("Invalid date format. Expected format: MM/DD/YYYY");
            return;
        }
        var today = new Date();
        var selectedDateTime = parseDate(selectedDate, 'dd/mm/yyyy');
        if (selectedDateTime > today) {
            alert("Invalid date ");
            $("#age").val('');
            $('#date_of_birth').val('')
            // You can choose to handle this error in a different way if needed
            return;
        }
        var dateParts = selectedDate.split('/');
        var dob_year = dateParts[2];
        var age = today.getFullYear() - dob_year;
        $("#age").val(age);
    }
}
//  calculate age ---14-12-23--- enf=d-- santhosh
$("#date_Select").select2({
    allowClear: true
});
function parseDate(dateString, format) {
    var parts = dateString.split('/');
    var day = parseInt(parts[0], 10);
    var month = parseInt(parts[1] - 1, 10);
    var year = parseInt(parts[2], 10);
    return new Date(year, month, day);
}
$('#date_Select').select2().on("change", function (e) {
    if (this.value != '') {
        var id_village = $('#village_select').val();
        var from_date = $("#customer_list1").text();
        var to_date = $("#customer_list2").text();
        get_customer_list(from_date, to_date, '', id_village)
    }
});
/*kyc numbers validation in customer master kyc tab..... addedOn : 21/06/2023 By Abi  starts */
function validate_kyc_pan() {
    var kyc_pan = $('#kyc_pan').val();
    var regexp = /^[A-Z]{5}\d{4}[A-Z]{1}$/;
    if (kyc_pan != '' && !regexp.test(kyc_pan)) {
        $('#kyc_pan').val('');
        $('#kyc_pan').attr('placeholder', 'Enter Valid pan No')
    }
}
function validate_aadhaar() {
    var kyc_aadhar = $('#kyc_aadhar').val();
    var regexp = /^\d{12}$/;
    if (kyc_aadhar != '' && !regexp.test(kyc_aadhar)) {
        $('#kyc_aadhar').val('');
        $('#kyc_aadhar').attr('placeholder', 'Enter Valid Aadhaar No')
    }
}
function validate_dl() {
    var kyc_dl = $('#kyc_dl').val();
    var regexp = /^(([A-Z]{2}[0-9]{2})( )|([A-Z]{2}-[0-9]{2}))((19|20)[0-9][0-9])[0-9]{7}$/;
    if (kyc_dl != '' && !regexp.test(kyc_dl)) {
        $('#kyc_dl').val('');
        $('#kyc_dl').attr('placeholder', 'Enter Valid driving license No')
    }
}
function validate_passport() {
    var kyc_passport = $('#kyc_pp').val();
    var regexp = /^[A-Za-z]{3}\d{6}$/;
    if (kyc_passport != '' && !regexp.test(kyc_passport)) {
        $('#kyc_pp').val('');
        $('#kyc_pp').attr('placeholder', 'Enter Valid PassPort No')
    }
}
//kyc validation ends...
$('.add_new_village_chit').on('click', function () {
    if ($('#pin_code_add').val().length == 6) {
        $('#confirm-area').modal('show');
        var pin_code = $('#pin_code_add').val();
        $('#new_pincode').val(pin_code);
    } else {
        $.toaster({ priority: 'danger', title: 'Warning!', message: '' + "</br>" + 'Enter Valid Pin Code' });
    }
});
$('#add_new_area').click(function (event) {
    if ($('#village').val() == '' || $('#village').val() == null) {
        $.toaster({ priority: 'danger', title: 'Warning!', message: '' + "</br>" + 'Enter the Village..' });
        return false;
    }
    else if ($('#new_pincode').val() == '') {
        $.toaster({ priority: 'danger', title: 'Warning!', message: '' + "</br>" + 'Enter the Pincode..' });
        return false;
    }
    else if ($('#new_pincode').val() != '' && ($('#new_pincode').val().length != 6)) {
        $.toaster({ priority: 'danger', title: 'Warning!', message: '' + "</br>" + 'Enter the Valid Pincode..' });
        return false;
    }
    add_new_village($('#village').val(), $('#new_pincode').val());
    $('#village').val('');
    $('#new_pincode').val('');
});
function add_new_village(village, pincode) {
    my_Date = new Date();
    $("div.overlay").css("display", "block");
    $.ajax({
        url: base_url + "index.php/admin_ret_estimation/get_village?nocache=" + my_Date.getUTCSeconds() + '' + my_Date.getUTCMinutes() + '' + my_Date.getUTCHours(),
        data: { 'village_name': village, 'pincode': pincode },
        type: "POST",
        dataType: "JSON",
        async: false,
        success: function (data) {
            console.log(data);
            if (data.status) {
                var ins_id = data.ins_id;
                // $('#pin_code_add').val(pincode);
                var newVillage = village;
                var $newOption = $('<option>', {
                    value: ins_id,
                    text: newVillage
                });
                $.toaster({ priority: 'success', title: 'Success!', message: '' + "</br>" + data.message });
                // $('#sel_village').append($newOption);
                $('#Village').select2("val", (ins_id != '' ? ins_id : ''));
                if (ins_id != '') {
                    // $('#ed_id_village').val(ins_id);
                    $('#id_village').val(ins_id);
                }
                // $('#sel_village').select2("val",(ins_id!='' ? ins_id: ''));
                // $('#sel_village').val(ins_id).trigger('change');
                $('#confirm-area').modal('hide');
                $("div.overlay").css("display", "none");
                if ($('#pin_code_add').val().length == 6) {
                    get_villages_by_pincode($('#pin_code_add').val())
                }
            } else {
                $.toaster({ priority: 'danger', title: 'Warning!', message: '' + "</br>" + data.message, settings: { timeout: 5000 } });
            }
        },
        error: function (error) {
            console.log(error);
            $("div.overlay").css("display", "none");
        }
    });
}
function get_villages_by_pincode(pincode) {
    my_Date = new Date();
    $.ajax({
        url: base_url + "index.php/admin_ret_estimation/get_village_by_pincode?nocache=" + my_Date.getUTCSeconds(),
        dataType: "json",
        type: 'POST',
        data: { 'pin_code': pincode },
        success: function (data) {
            if (data.length > 0) {
                var id_village = $('#id_village').val();
                $('#Village option').remove();
                $("#Village").select2({
                    placeholder: "Select Area",
                    allowClear: true
                });
                $.each(data, function (key, item) {
                    $("#Village").append(
                        $("<option></option>")
                            .attr("value", item.id_village)
                            .text(item.village_name)
                    );
                });
                if ($('#Village').length > 0) {
                    $('#Village').select2("val", (id_village != '' ? id_village : ''));
                }
                if (id_village != '') {
                    $('#Village').select2("val", (id_village != '' ? id_village : ''));
                }
                $("body").on("hidden.bs.modal", function () { // to use multiple model in one page
                    if ($(".modal.in").length > 0) {
                        $("body").addClass("modal-open")
                    }
                });
            } else {
                $.toaster({
                    priority: 'danger',
                    title: 'Warning!',
                    message: 'No area Found For this Pincode',
                    settings: {
                        timeout: 5000,
                    }
                });
                $('#sel_village option').remove();
                // $('#ed_sel_village option').remove();
                $('#Village').empty().trigger('change');
                $('#id_village').val('');
                $('#ed_id_village').val('');
                $("#sel_village,#ed_sel_village").select2({
                    placeholder: "Select Area",
                    allowClear: true
                });
            }
        }
    });
}
$(document).on('keyup', '#pin_code_add', function () {
    if ($("#pin_code_add").val().length == 6) {
        get_villages_by_pincode($("#pin_code_add").val())
    } else {
        $('#id_village').val('');
        $('#Village').select2("val", '');
        $('#Village option').remove();
    }
});
if (ctrl_page[1] == 'add') {
    document.addEventListener('DOMContentLoaded', function () {
        //	handleImageUpload('pan_image');
        //pan functions
        handleImageUpload('pan_img_front');
        handleImageUpload('pan_img_back');
        //aadhar functions
        handleImageUpload('aadhar_img_front');
        handleImageUpload('aadhar_img_back');
        handleImageUpload('pan_back_image');
        handleImageUpload('aadhar_image');
        handleImageUpload('dl_image');
        handleImageUpload('pp_image');
    });
}
function handleImageUpload(inputId) {
    var fileInput = document.getElementById(inputId);
    var previewId = 'prev' + inputId;
    var base64Id = inputId + 's';
    fileInput.addEventListener('change', function (e) {
        var file = e.target.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var previewImage = document.getElementById(previewId);
                var base64Input = document.getElementById(base64Id);
                previewImage.src = e.target.result;
                // Convert base64 to JSON string
                var base64Data = e.target.result.split(',')[1];
                console.log(base64Data);
                var jsonObject = {
                    src: "data:image/png;base64," + base64Data
                };
                // Create an object with your data
                var jsonString = JSON.stringify([jsonObject]);
                console.log(jsonString);
                // Set the value of the input field to the JSON string
                base64Input.value = jsonString;
            };
            reader.readAsDataURL(file);
        }
    });
}
function cuskycdetail(id) {
    var datas = getkycdata_byid(id);
    $('.kyc-detsta').html(datas);
    $('#kyc_modal').modal('show', { backdrop: 'static' });
}
function getkycdata_byid(id) {
    var kyc_data = "";
    $.ajax({
        url: base_url + "index.php/admin_customer/getkycdata_byid",
        dataType: "JSON",
        data: { "cus_id": id },
        type: "POST",
        async: false,
        success: function (data) {
            for (var i = 0; i < data.length; i++) {
                kyc_data += "<h3 style='text-align:center;'>";
                if (data[i].type == 1) {
                    if (data[i].kyc_type == 3) {
                        kyc_data += "Aadhar Details</h3>";
                    } else if (data[i].kyc_type == 2) {
                        kyc_data += "PAN Details</h3>";
                    } else if (data[i].kyc_type == 1) {
                        kyc_data += "Bank Details</h3>";
                    } else {
                        kyc_data += "KYC Details</h3>";
                    }
                }
                else if (data[i].type == 2) {
                    if (data[i].kyc_type == 3) {
                        kyc_data += "Nominee Aadhar Details</h3>";
                    } else if (data[i].kyc_type == 2) {
                        kyc_data += "Nominee PAN Details</h3>";
                    } else if (data[i].kyc_type == 1) {
                        kyc_data += "Nominee Bank Details</h3>";
                    } else {
                        kyc_data += "Nominee KYC Details</h3>";
                    }
                }
                kyc_data += "<br/><div class='row'>" +
                    "<div class='col-md-5' style='margin-left:30px';>" +
                    "<p><strong>Number</strong></p></div>" +
                    "<div class='col-md-6'><p>" + data[i].number + "</p>" +
                    "</div>" +
                    "</div>";
                if (data[i].name != null && data[i].name !== '') {
                    kyc_data += "<br/><div class='row'>" +
                        "<div class='col-md-5' style='margin-left:30px';>" +
                        "<p><strong>Name</strong></p></div>" +
                        "<div class='col-md-6'><p>" + data[i].name + "</p>" +
                        "</div>" +
                        "</div>";
                }
                if (data[i].kyc_type == 1) {
                    kyc_data += "<br/><div class='row'>" +
                        "<div class='col-md-5' style='margin-left:30px';>" +
                        "<p><strong>Bank Branch </strong></p></div>" +
                        "<div class='col-md-6'><p>" + data[i].bank_branch + "</p>" +
                        "</div>" +
                        "</div>";
                    kyc_data += "<br/><div class='row'>" +
                        "<div class='col-md-5' style='margin-left:30px';>" +
                        "<p><strong>Bank IFSC </strong></p></div>" +
                        "<div class='col-md-6'><p>" + data[i].bank_ifsc + "</p>" +
                        "</div>" +
                        "</div>";
                }
                if (data[i].img_url != null && data[i].img_url !== '') {
                    kyc_data += "<br/><div class='row'>" +
                        "<div class='col-md-5' style='margin-left:30px';>" +
                        "<p><strong>Card Front</strong></p></div>" +
                        "<div class='col-md-6'><img class='thumbnail' src='" + data[i].img_url + "'" + "style='width: 250px;height: 150px;'/>" +
                        "</div>" +
                        "</div>";
                }
                if (data[i].back_img_url != null && data[i].back_img_url !== '') {
                    kyc_data += "<br/><div class='row'>" +
                        "<div class='col-md-5' style='margin-left:30px';>" +
                        "<p><strong>Card Back</strong></p></div>" +
                        "<div class='col-md-6'><img class='thumbnail' src='" + data[i].back_img_url + "'" + "style='width: 250px;height: 150px;'/>" +
                        "</div>" +
                        "</div>";
                }
                // Display status based on values
                var statusString = "";
                switch (data[i].status) {
                    case "0":
                        statusString = "Pending";
                        statusColor = "blue"; // You can choose appropriate colors
                        break;
                    case "1":
                        statusString = "In Progress";
                        statusColor = "orange";
                        break;
                    case "2":
                        statusString = "Verified";
                        statusColor = "green";
                        break;
                    case "3":
                        statusString = "Rejected";
                        statusColor = "red";
                        break;
                    default:
                        statusString = "Unknown Status";
                        statusColor = "gray";
                }
                // Display status in the HTML with color
                kyc_data += "<br/><div class='row'>" +
                    "<div class='col-md-5' style='margin-left:30px';>" +
                    "<p><strong>Status</strong></p></div>" +
                    "<div class='col-md-6'><p style='color:" + statusColor + "'>" + statusString + "</p>" +
                    "</div>" +
                    "</div>";
            }
        }
    });
    return kyc_data;
}
function convert(sentence) {
    return sentence.charAt(0).toUpperCase() + sentence.slice(1);
}
//Zone Master
function get_ajaxzone_list() {
    my_Date = new Date();
    $("div.overlay").css("display", "block");
    $.ajax({
        url: base_url + "index.php/admin_customer/zone?nocache=" + my_Date.getUTCSeconds(),
        dataType: "JSON",
        type: "get",
        success: function (data) {
            var oTable = $('#zone_list').DataTable();
            oTable.clear().draw();
            if (data.list != null && data.list.length > 0) {
                oTable = $('#zone_list').dataTable({
                    "bDestroy": true,
                    "bInfo": true,
                    "bFilter": true,
                    "bSort": true,
                    "tableTools": { "buttons": [{ "sExtends": "xls", "oSelectorOpts": { page: 'current' } }, { "sExtends": "pdf", "oSelectorOpts": { page: 'current' } }] },
                    "aaData": data.list,
                    "order": [[0, "desc"]],
                    "aoColumns": [
                        { "mDataProp": "id_zone" },
                        { "mDataProp": "branch_name" },
                        { "mDataProp": "name" },
                        {
                            "mDataProp": function (row, type, val, meta) {
                                id = row.id_zone;
                                edit_target = (data.access.edit == '0' ? "" : "#confirm-edit");
                                delete_confirm = (data.access.delete == '1' ? '#confirm-delete' : '');
                                delete_url = (data.access.delete == '1' ? base_url + 'index.php/admin_customer/zone/delete/' + id : '#');
                                action_content = (data.access.edit == '1' ? '<a href="#" class="btn btn-primary btn-edit" id="edit" role="button" data-toggle="modal" data-id=' + id + '  data-target=' + edit_target + '><i class="fa fa-edit" ></i></a>' : '') + (data.access.delete == '1' ? '<a href="#" class="btn btn-danger btn-del" data-href=' + delete_url + ' data-toggle="modal" data-target="#confirm-delete" ><i class="fa fa-trash"></i></a>' : '')
                                return action_content;
                            }
                        }
                    ]
                });
            }
            $("div.overlay").css("display", "none");
        },
        error: function (error) {
            $("div.overlay").css("display", "none");
        }
    });
}
$('#addnew_zone').on('click', function () {
    if (($('#branch_select').val() == '' || $('#branch_select').val() == null) && $('#branch_settings').val() == 1) {
        msg = '<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Please Select Branch</div>';
        $('#chit_alert').html(msg);
    }
    else if ($('#name').val() == '') {
        msg = '<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Please Enter Zone Name</div>';
        $('#chit_alert').html(msg);
    }
    else {
        $(".overlay").css("display", "block");
        var form_data = $('#zone_form').serialize();
        my_Date = new Date();
        $.ajax({
            url: base_url + 'index.php/admin_customer/zone/add/?nocache=' + my_Date.getUTCSeconds(),
            dataType: "json",
            method: "POST",
            data: form_data,
            success: function (data) {
                if (data.status) {
                    msg = '<div class = "alert alert-success"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong>' + data.message + '</div>';
                } else {
                    msg = '<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong>' + data.message + '</div>';
                }
                $('#chit_alert').html(msg);
                $('#name').val('');
                $('#branch_select').select2("val", '');
                location.reload();
            }
        });
        $(".overlay").css("display", "none");
    }
});
$('#add_zone').on('click', function () {
    if (($('#branch_select').val() == '' || $('#branch_select').val() == null) && $('#branch_settings').val() == 1) {
        msg = '<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Please Select Branch</div>';
        $('#chit_alert').html(msg);
    }
    else if ($('#name').val() == '') {
        msg = '<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Please Enter Zone Name</div>';
        $('#chit_alert').html(msg);
    }
    else {
        $(".overlay").css("display", "block");
        var form_data = $('#zone_form').serialize();
        my_Date = new Date();
        $.ajax({
            url: base_url + 'index.php/admin_customer/zone/add/?nocache=' + my_Date.getUTCSeconds(),
            dataType: "json",
            method: "POST",
            data: form_data,
            success: function (data) {
                if (data.status) {
                    msg = '<div class = "alert alert-success"><a href = "#" class = "close" data-dismiss = "alert">&times;</a>' + data.message + '</div>';
                    $('#chit_alert1').html(msg);
                }
                else {
                    msg = '<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong>' + data.message + '</div>';
                    $('#chit_alert1').html(msg);
                }
                $('#confirm-add').modal('toggle');
                get_ajaxzone_list();
            }
        });
        $(".overlay").css("display", "none");
    }
});
$(document).on('click', "#zone_list a.btn-edit", function (e) {
    $("#ed_name").val('');
    e.preventDefault();
    id = $(this).data('id');
    get_edit_zone(id);
    $("#id_metal_type").val(id);
});
function get_edit_zone() {
    my_Date = new Date();
    $.ajax({
        type: "GET",
        url: base_url + "index.php/admin_customer/zone/edit/" + id + "?nocache=" + my_Date.getUTCSeconds(),
        cache: false,
        dataType: "JSON",
        success: function (data) {
            $('.ed_branch_select').select2("val", data.id_branch);
            $('#ed_name').val(data.name);
            $('#id_zone').val(data.id_zone);
        }
    });
}
$('#update_zone').on('click', function () {
    if (($('.ed_branch_select').val() == '' || $('.ed_branch_select').val() == null) && $('#branch_settings').val() == 1) {
        msg = '<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Please Select Branch</div>';
        $('#chit_alert').html(msg);
    }
    else if ($('#ed_name').val() == '') {
        msg = '<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Please Enter Zone Name</div>';
        $('#chit_alert').html(msg);
    }
    else {
        $(".overlay").css("display", "block");
        var form_data = $('#ed_zone_form').serialize();
        my_Date = new Date();
        $.ajax({
            url: base_url + 'index.php/admin_customer/zone/update/?nocache=' + my_Date.getUTCSeconds(),
            dataType: "json",
            method: "POST",
            data: form_data,
            success: function (data) {
                if (data.status) {
                    msg = '<div class = "alert alert-success"><a href = "#" class = "close" data-dismiss = "alert">&times;</a>' + data.message + '</div>';
                    $('#chit_alert1').html(msg);
                }
                else {
                    msg = '<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong>' + data.message + '</div>';
                    $('#chit_alert1').html(msg);
                }
                $('.ed_branch_select').val('');
                $('#ed_name').val('');
                $('#confirm-edit').modal('toggle');
                get_ajaxzone_list();
            }
        });
        $(".overlay").css("display", "none");
    }
});
//Zone Master
function get_title(from_date, to_date, title) {
    var company_name = $('#company_name').val();
    var company_code = $('#company_code').val();
    var company_address1 = $('#company_address1').val();
    var company_address2 = $('#company_address2').val();
    var company_city = $('#company_city').val();
    var pincode = $('#pincode').val();
    var company_email = $('#company_email').val();
    var company_gst_number = $('#company_gst_number').val();
    var phone = $('#phone').val();
    var select_date = "<div style='text-align: center;'><b><span style='font-size:12pt;'>" + company_code + "</span></b></br>"
        + "<span style='font-size:11pt;'>" + company_address1 + "</span></br>"
        + "<span style='font-size:11pt;'>" + company_address2 + company_city + "-" + pincode + "</span></br>";
    +"<span style='font-size:11pt;'>GSTIN:" + company_gst_number + ", EMAIL:" + company_email + "</span></br>"
    if (company_gst_number != '' && company_gst_number != null) {
        select_date += "<span style='font-size:11pt;'>GSTIN:" + company_gst_number + "</span></br>";
    }
    if (company_email != '') {
        select_date += " EMAIL:" + company_email + "</span></br>";
    }
    if (phone != '') {
        select_date += "<span style='font-size:11pt;'>Contact :" + phone + "</span></br>"
    }
    select_date += "<b><span style='font-size:15pt;'>" + title.toUpperCase() + "</span></b></br>";
    if (from_date != '' && to_date != '') {
        select_date += "<span style=font-size:13pt;>Details &nbsp;&nbsp;From Date&nbsp;:&nbsp;" + from_date + " &nbsp;&nbsp;To Date&nbsp;:&nbsp;" + to_date + "</span><br>";
    }
    select_date += "<span style=font-size:11pt;>Print Taken On : " + moment().format("dddd, MMMM Do YYYY, h:mm:ss a")
        + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
        + "<span style=font-size:11pt;>Print Taken By : " + $('.hidden-xs').html() + "</span></div>";
    return select_date;
}
function getBranchTitle() {
    var login_branch = $("#branch_filter").val();
    var branch_name;
    var selected_branch
    if (isValid(login_branch)) {
        branch_name = $("#login_branch_name").val();
    }
    else {
        if (ctrl_page[1] == 'closed_acc_report') {
            selected_branch = $('#close_branch_select option:selected').toArray().map(item => item.text).join();
        }
        else {
            selected_branch = $('#branch_select option:selected').toArray().map(item => item.text).join();
        }
        if (isValid(selected_branch)) {
            branch_name = selected_branch;
        }
        else {
            branch_name = "All Branch"
        }
    }
    return branch_name;
}
function isValid(value) {
    return value !== null && value !== undefined && !isNaN(value);
}
//Nominee page 
$(document).ready(function () {
    $('#nominee_name').on('input', function () {
        if ($(this).val().trim() !== '') {
            $('#nominee_relationship').prop('disabled', false);
        } else {
            $('#nominee_relationship').prop('disabled', true);
            $('#nominee_mobile').prop('disabled', true);
            $('#panno').prop('disabled', true);
            $('#voterid').prop('disabled', true);
            $('#rationcard').prop('disabled', true);
        }
    });
    $('#nominee_relationship').on('input', function () {
        if ($(this).val().trim() !== '') {
            $('#nominee_mobile').prop('disabled', false);
        } else {
            $('#nominee_mobile').prop('disabled', true);
        }
    });
    $('#nominee_mobile').on('change', function () {
        $mobile = $('#nominee_mobile').val();
        if (validate_mobile($mobile)) {
            $('#panno').prop('disabled', false);
            $('#voterid').prop('disabled', false);
            $('#rationcard').prop('disabled', false);
        } else {
            $('#nominee_mobile').val('');
            $('#nominee_mobile').attr('placeholder', 'Enter Valid mobile No');
            $('#panno').prop('disabled', true);
            $('#voterid').prop('disabled', true);
            $('#rationcard').prop('disabled', true);
        }
    });
    function toggleInputsWithValidation(inputId, dependentInputId, validationFn) {
        $(dependentInputId).prop('disabled', true);
        $(inputId).on('change', function () {
            const inputValue = $(this).val().trim();
            if (inputValue !== '' && validationFn(inputValue)) {
                $(dependentInputId).prop('disabled', false);
            } else {
                $(dependentInputId).prop('disabled', true);
            }
        });
        $(dependentInputId).on('change', function () {
            const fileUploaded = $(this).val().trim();
            if (fileUploaded !== '') {
                $(inputId).prop('disabled', false);
            } else {
                $(inputId).prop('disabled', true);
            }
        });
    }
    // Validation Functions
    function validate_mobile(value) {
        const mobilePattern = /^[0-9]\d{9}$/; // Example pattern for a 10-digit mobile number
        return mobilePattern.test(value);
    }
    function validatePAN() {
        var panValue = $('#panno').val();
        var panPattern = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/; // PAN format: ABCDE1234F
        if (panValue !== '' && !panPattern.test(panValue)) {
            $('#panno').val('');
            $('#panno').attr('placeholder', 'Enter Valid PAN Number (Example:ABCDE1234F)');
        }
    }
    function validateVoterID() {
        var voterID = $('#voterid').val();
        var regexp = /^[A-Z]{3}\d{7}$/; // Pattern: ABC1234567
        if (voterID !== '' && !regexp.test(voterID)) {
            $('#voterid').val('');
            $('#voterid').attr('placeholder', 'Enter Valid Voter ID No (Example:ABC1234567)');
        }
    }
    function validateRationCard(value) {
        const rationCardPattern = /^\d{4}[A-Z]{2}\d{5}$/; // Example: 1234AB12345
        const isValid = rationCardPattern.test(value);
        if (!isValid) {
            $('#rationcard').val('');
            $('#rationcard').attr('placeholder', 'Enter Valid ration card No (Example:1234AB12345');
        }
        return isValid;
    }
    // Attach Validation Functions
    // toggleInputsWithValidation('#nominee_mobile', '#panno', validate_mobile);
    toggleInputsWithValidation('#panno', '#pan_proof', validatePAN);
    toggleInputsWithValidation('#voterid', '#voterid_proof', validateVoterID);
    toggleInputsWithValidation('#rationcard', '#rationcard_proof', validateRationCard);
});
