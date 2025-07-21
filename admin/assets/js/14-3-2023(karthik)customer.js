$(document).ready(function() {
    
    



	var path =  url_params();
	 
    /*document.querySelector('#firstname,#address1,#address2,#address3').addEventListener('input', evt => {
        evt.target.value = evt.target.value.replace(/[^a-z\s]/ig, '').replace(/\s{2,}/g, ' ');
    });
    */
    
    $("#firstname").on('keyup', function (event) {
        this.value = this.value.replace(/[^a-z\s]/ig, '').replace(/\s{2,}/g, ' ');
    }); 
    
    
    $("#gst_number").on('change', function (event) {
        
        var inputvalues = $('#gst_number').val(); 
                
        var tdr_regex = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/; 

           if(inputvalues.length == 15){  
             if (tdr_regex.test(inputvalues) == false)   
             {  
               alert( 'Your GST number ' + inputvalues + ' is not in the correct format!');  
               $('#gst_number').val('');
             }
           }else{  
             alert( 'You have not entered valid GST number!'); 
             $('#gst_number').val('');
           } 
   
    });          


     if($('#cus_type:checked').val()==1)
 	{
 	    $('#cus_name').html('First Name');
        $('#last_name').css("display","block");
        $('#gstno').css("display","none");
        //$('#pan_no').css("display","none"); 
        $('#gst_number').prop('required',false);
        $('#pan').prop('required',false);
 	}else{
 	    $('#last_name').css("display","none");
        $('#gstno').css("display","block");
        //('#pan_no').css("display","block");
        $('#cus_name').html('Company Name');
        $('#gst_number').prop('required',true);
        $('#pan').prop('required',true);
 	}

	 $("#firstname,#lastname").on('keypress', function (event) {
          var regex = new RegExp("^[a-zA-Z ]*$");
          var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
          if (!regex.test(key)) {
             event.preventDefault();
             return false;
          }
       });
       
       $("#firstname,#lastname").bind("cut copy paste",function(e) {
            e.preventDefault();
       });
	   
	   $('#cus_create').submit(function(e)  {
		
		$('#save').prop('disabled', true);
		
		});

		$('#branch_select').select2().on("change", function(e) { 
		if(this.value!='')
		{
		$("#id_branch").val(this.value);
		var id_branch=$('#id_branch').val();
		get_customer_list('','',id_branch)
		}

		});      



	    if(path.route=='customer')



	    { 



	        get_customer_list();

            get_village();

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



              startDate: moment().subtract(29, 'days'),



              endDate: moment()



            },



        function (start, end) {



          $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));



                     



             get_customer_list(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'))



          }



        ); 



	    } 
		
var pathArray = window.location.pathname.split( 'php/' );

var ctrl_page = pathArray[1].split('/');

var export_list = [];

$(document).ready(function(){
 	
		if(ctrl_page[1]=='withoutAccount')
		{
			
			get_without_acc_cus();
		}
		
		if(ctrl_page[1]=='add' || ctrl_page[1]=='edit')
	    {
	        
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
    			jpeg_quality: 90
	    	});
            
            Webcam.attach( '#my_camera' );
        
            $("#upload_img").setShortcutKey(17 , 73 , function() {
                take_snapshot('pre_images');
            });
		//webcam upload ends...    

	    }
}); 
	
if(ctrl_page[1]=='cus_profile')
{
    ej.base.enableRipple(true);
    var datepicker = new ej.calendars.DatePicker(
            {
              format: 'yyyy-MM-dd'
            }
        );
    var datepicker1 = new ej.calendars.DatePicker( {
              format: 'yyyy-MM-dd'
            });
    datepicker.appendTo('#datepicker');
    datepicker1.appendTo('#datepicker1');
    
    $(".dob,.wedding").keydown(function(event) { 
        return false;
    });
 
}	
	
		

 $('.profile').initial({height:25,width:25,fontSize:10,fontWeight:700}); 



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



$('#lastname').on('blur',function() {



		if($(this).val()!=""){

			var regexp =  /^[a-zA-Z]+$/;

			if(!regexp.test($(this).val()))

			{

			$(this).val("");

			alert("Last name can have only alphabets");

			}

		}

});



/*-- / Coded by ARVK --*/



//Image validation



$('#cus_image').on('change',function() {



		validateImage(this);



});



$('#pan_proof').on('change',function() {



		validateImage(this);



});



$('#voterid_proof').on('change',function() {



		validateImage(this);



});



$('#rationcard_proof').on('change',function() {



		validateImage(this);



});



$("#pan").on('blur onchange',function(event) {



		event.preventDefault();



	validate_pan(this);	



	});



$("#pincode").on('blur onchange',function(event) {



		event.preventDefault();



	validate_pincode(this);	



	});







$('#passwd').on('blur change',function() {



	



		if($.trim($("#passwd").val()).length < 8)



			{



				$("#passwd").val("");



				$("#passwd").attr('placeholder','Password must be of minimum 8 characters.');



				$("#passwd").focus();



				return false;



			}



	});



		



if($('#country').length>0)



{



    	get_country();	
    	
    	get_village();



}



$('#country').select2().on('change', function() {

        
        if(this.value!='')
        {
             get_state(this.value);
             
             $('#city').empty();
             
             $('#cityval').empty();
			 
			 
             $('#select2-city-container').empty();
			
             
             $("#city option:selected").text();
        }
       
 	   
 	    if(ctrl_page[0]=='customer' && ctrl_page[1]=='cus_profile')
            {
                calculate_profile_percentage();
            }
 	    



	}); 



	



$('#state').select2().on('change', function() {

    
        if(this.value!='')
        {
            get_city(this.value);
        }

 	     if(ctrl_page[0]=='customer' && ctrl_page[1]=='cus_profile')
            {
                calculate_profile_percentage();
            }
	});

    $('#city').select2().on('change', function() {
        if(this.value!='')
        {

             if(ctrl_page[0]=='customer' && ctrl_page[1]=='cus_profile')
            {
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
					  placeholder: "Enter Village",
					    allowClear: true
	});	
	
	
	$("#village_select").select2({
					  placeholder: "Enter Village",
					    allowClear: true
	});	
	



if($('#username').length>0)



{



   $('#username').on('blur onchange',function(){



   	   if(this.value.length >=6)



   	   {



   	   	  checkUserNameExists(this.value);



	   }



	   else



	   {



	   	 $(this).val('');



	   	 $(this).attr('placeholder', 'Required atleast 8 characters')



	   }



   	



   }); 	



}



if($('#mobile').length>0)



{



	$("#mobile").keypress(function (e){



		  var charCode = (e.which) ? e.which : e.keyCode;



		  if (charCode > 31 && (charCode < 48 || charCode > 57)) 



		  {



			return false;



		  }



    });



   $('#mobile').on('blur onchange',function(){



   	   if(this.value.length == mob_no_len)



   	   {



   	   	  checkMobileAvail(this.value);



	   }



	   else



	   {



	   	 $(this).val('');



	   	 $(this).attr('placeholder', 'Enter valid mobile number');

	   	 

	   	 $(this).focus();



	   }



   	



   }); 



   $('#nominee_mobile').on('blur onchange',function(){



   	   if(this.value.length != mob_no_len)



   	   {



   	   	 $(this).val('');



	   	 $(this).attr('placeholder', 'Enter valid mobile number');

	   	 

	   	 /*$(this).focus();*/



	   }





   }); 	



}







if($('#email').length>0)



{



	



   $('#email').on('blur onchange',function(){



   	   var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;



	     if (this.value.search(emailRegEx) == -1) 



	     {



		 	$(this).val('');



		   	 $(this).attr('placeholder', 'Enter valid email id')



		 }



   	  



	   else



	   {



	   	  checkEmailAvail(this.value);



	   }



   	



   }); 	



}	







if($('#date_of_birth').length>0)



{



	$('#date_of_birth').datepicker({



               format: 'dd/mm/yyyy'



            })



            .on('changeDate', function(ev){



            	



              $('#age').val(_calculateAge(this.value));



            $(this).datepicker('hide');



        });



            



	



}



if($('#date_of_wed').length>0)



{



	$('#date_of_wed').datepicker({



               format: 'dd/mm/yyyy'



            })



            .on('changeDate', function(ev){



 	            $(this).datepicker('hide');



        });



            



	



}



if($('#age').length>0 || $('#date_of_birth').val())



{



	_calculateAge($('#date_of_birth').val());



}



 $('#select_all').click(function(event) {

		$("tbody tr td input[type='checkbox']").prop('checked', $(this).prop('checked'));

      event.stopPropagation();

    });



 

//send SMS to selected customers  list

   $("#send-login-sms").click(function(){

   

	   	var data = { 'id_customer[]' : []};

		$("input[name='id_customer[]']:checked").each(function() {

		  data['id_customer[]'].push($(this).val());

		});

		 $("div.overlay").css("display", "block"); 

				$.ajax({

					      type: "POST",

						  url: base_url+"index.php/sms/login",

						  data: data,

						  sync:false,

						  success: function(data){

						 

						  	 $("div.overlay").css("display", "none"); 

							  	 $('#alert_msg').html(data);

							  	 $(".alert").css("display","block"); 

												 

						 }				 

			   });

   }); 

   //send Email to selected customers  list

   $("#send-login-email").click(function(){

   

	   	 var data = { 'id_customer[]' : []};

		$("input[name='id_customer[]']:checked").each(function() {

		  data['id_customer[]'].push($(this).val());

		});

		 $("div.overlay").css("display", "block"); 

				$.ajax({

					      type: "POST",

						  url: base_url+"index.php/sms/login_email",

						  data: data,

						  sync:false,

						  success: function(data){

						 

						  	 $("div.overlay").css("display", "none"); 

							  	 $('#alert_msg').html(data);

							  	 $(".alert").css("display","block"); 

							 

						 }				 

			   });

   }); 

	



});







function validate_pan()



{



	



  if($("#pan").val() != '')



  {



  	



	var regexp = /^[A-Z]{5}\d{4}[A-Z]{1}$/;



	if(!regexp.test($("#pan").val()))



	{



		 $('#pan').val('');



		 $('#pan').attr('placeholder', 'Enter Valid Pan No')



		 $("#pan").focus();



	}



  }



  



}



function validate_pincode()



 {



	 



	  if($("#pincode").val() != '')



	  {



		 var regexp = /^([0-9]{6})?$/;



		if(!regexp.test($("#pincode").val()))



		{



			 $("#pincode").val("");



			 $('#pincode').attr('placeholder', 'Not a valid Pincode.');



			 $("#pincode").focus();



		}



	  }



  }



		  



function validateImage()



 {



				if(arguments[0].id == 'cus_image')



				{



					var preview = $('#cus_img_preview');



				}



				else if(arguments[0].id == 'pan_proof')



				{



					var preview = $('#pan_proof_preview');



				}



				else if(arguments[0].id == 'voterid_proof')



				{



					var preview = $('#voterid_proof_preview');



				}



				else 



				{



					var preview = $('#rationcard_proof_preview');



				}



				if(arguments[0].files[0].size > 1048576)



				{



				  alert('File size cannot be greater than 1 MB');



				  arguments[0].value = "";



				  preview.css('display','none');



				}



				else



				{



					var fileName =arguments[0].value;



					var ext = fileName.substring(fileName.lastIndexOf('.') + 1);



					ext = ext.toLowerCase();



					if(ext != "jpg" && ext != "png" && ext != "jpeg")



					{



						alert("Upload JPG or PNG Images only");



						arguments[0].value = "";



						preview.css('display','none');



					}



					else



					{



						var file    = arguments[0].files[0];



						var reader  = new FileReader();



						  reader.onloadend = function () {



							preview.prop('src',reader.result);



						  }					



						  if (file)



						  {



						 	reader.readAsDataURL(file);



							preview.css('display','');



						  }



						  else



						  {



						  	preview.prop('src','');



							preview.css('display','none');



						  }



						  



  



					}



				}



				}



			



function get_country()



{



$('.overlay').css('display','block');



	$.ajax({



	  type: 'GET',



	  url:  base_url+'index.php/settings/company/getcountry',



	  dataType: 'json',



	  success: function(country) {



	  //	console.log(country);



		$.each(country, function (key, country) {



				  	



		  



			$('#country').append(



				$("<option></option>")



				  .attr("value", country.id)



				  .text(country.name)



				  



			);



			



		});



		



				



		$("#country").select2("val", ($('#countryval').val()!=null?$('#countryval').val():''));

	//console.log($('#countryval').val());
	

			var selectid=$('#countryval').val();

			

			if(selectid!=null && selectid > 0)



			{



				



				$('#country').val(selectid);
				

				$('.overlay').css('display','block');



				get_state(selectid);



			}



		$('.overlay').css('display','none');



		},



	  	 error:function(error)  



					  {



						 $("div.overlay").css("display", "none"); 



					  }



	});



	}



	







	











function get_state(id)



{



	$('.overlay').css('display','block');



	$('#state option').remove();







	$.ajax({



	  type: 'POST',



	   data:{'id_country':id },



	  url:  base_url+'index.php/settings/company/getstate',



	  dataType: 'json',



	  success: function(state) {



	  	



		$.each(state, function (key, state) {



				   



		  



			$('#state').append(



				$("<option></option>")



				  .attr("value", state.id)



				  .text(state.name)



			);



		});



			



				



		$("#state").select2("val", ($('#stateval').val()!=null?$('#stateval').val():''));



		



		var selectid=$('#stateval').val();



		    console.log(selectid);



		if(selectid!=null && selectid>0)



		{



			$('#state').val(selectid);



			



		    get_city(selectid);



	    }



		$('.overlay').css('display','none');



	



	  },



	  	 error:function(error)  



					  {



						 $("div.overlay").css("display", "none"); 



					  }



	});



}







function get_city(id)



{  


	$('.overlay').css('display','block');

	$('#city option').remove();

	
		  	
	$("#city").css("display", "block");





	$.ajax({



	  type: 'POST',



	  data:{'id_state':id },



	  url:  base_url+'index.php/settings/company/getcity',



	  dataType: 'json',



	  success: function(city) {



	  



		$.each(city, function (key, city) {






		  



			$('#city').append(



				$("<option></option>")



				  .attr("value", city.id)



				  .text(city.name)



			);



		});



		$("#city").select2("val", ($('#cityval').val()!=null?$('#cityval').val():''));


		var selectid=$('#cityval').val();



		if(selectid!=null && selectid>0)



		{



			$('#city').val(selectid);



			



		   



	    }



	    $('.overlay').css('display','none');
		
		




	  },



	  	 error:function(error)  



					  {



						 $("div.overlay").css("display", "none"); 



					  }



	});







}







function checkUserNameExists(username)



{



	$.ajax({



	  type: 'GET',



	  url:  base_url+'index.php/customer/check_username/'+username,



	  dataType: 'json',



	  success: function(avail) {



	  		



	   		 if(avail==1)



		   	 {



		   	 	$('#username').val('');



			 	 $('#username').attr('placeholder', 'Username already exists')



			 }  



	  	},



	  	 error:function(error)  



					  {



						 $("div.overlay").css("display", "none"); 



					  }



	  });	



}







function checkEmailAvail(email)



{



	$("div.overlay").css("display", "block");



	



	$.ajax({



	  type: 'POST',



	   data:{'email':email, 'id_customer':(cust_id != "" ? cust_id : "") },



	  url:  base_url+'index.php/customer/check_email/',



	  dataType: 'json',



	  success: function(avail) {



	  		



	   		 if(avail==1)



		   	 {



		   	 	$('#email').val('');



			 	 $('#email').attr('placeholder', 'Email already exists')



			 }  



			  $("div.overlay").css("display", "none");



	  	},



	  	 error:function(error)  



					  {



						 $("div.overlay").css("display", "none"); 



					  }



	  	



	  });	



}







function checkMobileAvail(mobile)



{ 



$("div.overlay").css("display", "block");



	



	$.ajax({



	  type: 'POST',



	  data:{'mobile':mobile, 'id_customer': (cust_id != "" ? cust_id : "") },



	  url:  base_url+'index.php/customer/check_mobile',



	  dataType: 'json',



	  success: function(avail) {



	  
	   		 if(avail==1)



		   	 {



		   	 	$('#mobile').val('');



			 	$('#mobile').attr('placeholder', 'mobile already exists')



			 }else{
			     if(path.route=='customer/add'){
			        $('#passwd').val(mobile);
			     }
			 }



			  $("div.overlay").css("display", "none");  



	  	},



	  	 error:function(error)  



					  {



						 $("div.overlay").css("display", "none"); 



					  }



	  	



	  });	



}







 //preview selected images



function readURL(input, preview) {



 



    if (input.files && input.files[0]) {



        var reader = new FileReader();







        reader.onload = function (e) {



            $('#'+ preview).attr('src', e.target.result);



        }







        reader.readAsDataURL(input.files[0]);



    }



}  







function get_customer_list(from_date="",to_date="",id_branch="",id_village="")



{



	my_Date = new Date();

	var type=$('#date_Select').find(":selected").val();

	 $("div.overlay").css("display", "block"); 



	$.ajax({



			  url:base_url+"index.php/customer/ajax_list?nocache=" + my_Date.getUTCSeconds(),



			 data: (from_date !='' || id_branch!='' || to_date !='' || id_village!=''? {'from_date':from_date,'to_date':to_date,'id_branch':id_branch,'id_village':id_village,'date_type':type}: ''),



			 dataType:"JSON",



			 type:"POST",



			 success:function(data){



			   			set_customer_list(data);



			   			 $("div.overlay").css("display", "none"); 



					  },



					  error:function(error)  



					  {



						 $("div.overlay").css("display", "none"); 



					  }	 



			      });



}







function set_customer_list(data)	



{

   export_list    = [];

   var customer = data.customer;



   var access = data.access;



   var oTable = $('#customer_list').DataTable();



   $("#total_customers").text(customer.length);



    if(access.add == '0')



	 {



		$('#add_customer').attr('disabled','disabled');



	 }



	 oTable.clear().draw();



   	 if (customer!= null && customer.length > 0)



	 {

		export_list    = customer;	


	 	oTable = $('#customer_list').dataTable({



				                "bDestroy": true,



				                "bInfo": true,



				                "bFilter": true,



				                "bSort": true,

				                

				                 "dom": 'lBfrtip',
           		                "buttons" : ['excel','print'],
						        "tableTools": { "buttons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } } ] },



				                "aaData": customer,



				                "order": [[ 0, "desc" ]],



				               "aoColumns": [{ "mDataProp":function (row,type,val,meta){

												id=row.id_customer;

												return "<label class='checkbox-inline'><input type='checkbox' class='flat-red' name='id_customer[]' value='"+row.id_customer+"' /> "+id+" </label>";

															} },



					                { "mDataProp":function (row,type,val,meta){

												var title = 	row.title!=null?row.title+". ":'';
												return title+""+row.name;

									} },



					                { "mDataProp": "mobile" },



					                { "mDataProp": "accounts" },



					                { "mDataProp": function ( row, type, val, meta ){
	                	               if(row.edit_custom_entry_date==0){
	                               	return row.date_add+' </br> '+row.custom_entry_date;
	                	               }
	                	              else{
	                                     	return row.custom_entry_date+'</br> '+row.date_add;
	                	                 }
	                                  }},				                



					                { "mDataProp": function ( row, type, val, meta ){



					                	    active_url =base_url+"index.php/admin_customer/customer_status/"+(row.active==1?0:1)+"/"+row.id_customer; 



					                		return "<a href='"+active_url+"'><i class='fa "+(row.active==1?'fa-check':'fa-remove')+"' style='color:"+(row.active==1?'green':'red')+"'></i></a>"



					                	}



					                },



					                { "mDataProp": function ( row, type, val, meta ){



					                	    profile_url =base_url+"index.php/customer/profile/status/"+(row.profile_complete==1?0:1)+"/"+row.id_customer; 



					                		return "<a href='"+profile_url+"'><i class='fa "+(row.profile_complete==1?'fa-thumbs-o-up':'fa-thumbs-o-down')+"' style='color:"+(row.profile_complete==1?'green':'red')+"'></i></a>"



					                	}



					                },
                                    
                                     { "mDataProp": "agent_name"},


					               { "mDataProp": function ( row, type, val, meta ) {

					                	

					                	return (row.added_by=='0'?"Web":(row.added_by=='1'?"Admin":(row.added_by=='2'?"Mobile":(row.added_by=='3'?"Admin App":(row.added_by=='4'?"Retail":(row.added_by=='5'?"Sync":(row.added_by=='6'?"Import":"-")))))));



					                	}},

 									{ "mDataProp": function ( row, type, val, meta ) {



					                	 id= row.id_customer;



					                	 edit_url=(access.edit=='1' ? base_url+'index.php/customer/edit/'+id : '#' );



					                	 delete_url=(access.delete=='1' ? base_url+'index.php/customer/delete/'+id : '#' );



					                	 delete_confirm= (access.delete=='1' ?'#confirm-delete':'');



					                	 action_content='<div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button><ul class="dropdown-menu">'+



					    '<li><a href="'+edit_url+'" class="btn-edit"><i class="fa fa-edit" ></i> Edit</a></li>'+



					    '<li><a href="#" class="btn-del" data-href="'+delete_url+'" data-toggle="modal" data-target="'+delete_confirm+'"  ><i class="fa fa-trash"></i> Delete</a></li></ul></div>';



					                	return action_content;



					                	}



					               



					            }] 







				            });	



	 }  



}
//get_without_acc_cus details

function get_without_acc_cus()
{
	my_Date = new Date();

	 $("div.overlay").css("display", "block"); 

	$.ajax({

			 url:base_url+"index.php/customer/without_acc_details?nocache=" + my_Date.getUTCSeconds(),

			 dataType:"JSON",

			 type:"GET",

			 success:function(data){

			 console.log(data);
			 
			   			set_without_acc_cuslist(data);

			   			 $("div.overlay").css("display", "none"); 

					  },

					  error:function(error)  

					  {

						 $("div.overlay").css("display", "none"); 

					  }	 

			      });
	
	
	
}
function set_without_acc_cuslist(data)	
{
   var customer = data;
   
   
   //alert(customer);
   var oTable = $('#sch_acc_list').DataTable();
	 
	 oTable.clear().draw();

   	 if (customer!= null && customer.length > 0)

	 {

	 	oTable = $('#sch_acc_list').dataTable({

				                "bDestroy": true,

				                "bInfo": true,

				                "bFilter": true,

				                "bSort": true,
				                
				                 "dom": 'T<"clear">lfrtip',
				                
				                "tableTools": { "aButtons": [ { "sExtends": "xls", "oSelectorOpts": { page: 'current' } },{ "sExtends": "pdf", "oSelectorOpts": { page: 'current' } }] },

				                "aaData": customer,

				                "order": [[ 0, "desc" ]],

				               "aoColumns": [{ "mDataProp":function (row,type,val,meta){
												id=row.id_customer;
												return "<label class='checkbox-inline'><input type='checkbox' class='flat-red' name='id_customer[]' value='"+row.id_customer+"' /> "+id+" </label>";
															} },

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

function get_village()
{
    $('.overlay').css('display','block');
	$.ajax({
	  type: 'GET',
	  url:  base_url+'index.php/admin_settings/ajax_village_list',
	  dataType: 'json',
	  success: function(data) {
var id_village=$('#id_village').val();
		$.each(data, function (key, data) {
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
	
			if(ctrl_page[1]=='edit'||ctrl_page[1]=='add')
		{
		    $("#Village").select2("val",(id_village!=''?id_village:''));
		}
	
		if(ctrl_page[0]=='customer' && ctrl_page[1]!='cus_profile')
		{
		    	$("#village_select").select2("val",(id_village!=''?id_village:''));
		}
		var selectid=$('#id_village').val();
    	if(selectid!=null && selectid > 0)
    	{
				$('#Village').val(selectid);
				$('.overlay').css('display','block');
		}
		$('.overlay').css('display','none');
		},
	  	 error:function(error)  
					  {
                        $("div.overlay").css("display", "none"); 

					  }
	});
	}

$('#Village').select2().on("change", function(e) {
if(this.value!='')
{  
    var id=$(this).val();
    $('#id_village').val(id);
    get_village_list(id);
    if(ctrl_page[1]=='cus_profile')
    {
        calculate_profile_percentage();
    }

}
});

$('#village_select').select2().on("change", function(e) {
if(this.value!='')
{  
var id=$(this).val();
$('#id_village').val(id);
get_customer_list('','','',id)
}
});
	
	function get_village_list(id_village)
	{
	   $.ajax({
	  type: 'POST',
	  data:{'id_village':id_village},
	  url:  base_url+'index.php/admin_settings/ajax_village_list',
	  dataType: 'json',
	  success: function(data) {
	        $('#post_office').val(data.post_office);
	        $('#taluk').val(data.taluk);
	         $('#pincode').val(data.pincode);
	
		}
	       
	   });
	}
	
	//Customer Profile Updation

 $("#search_customer").on("keyup",function(e){ 
		var customer = $("#search_customer").val();
		if(customer.length >= 5) { 
			getSearchCustomers(customer);
		}
	}); 
	
	
	function getSearchCustomers(searchTxt){
	my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/admin_customer/cus_profile/edit?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data: {'searchTxt': searchTxt}, 
        success: function (data) {
			$( "#search_customer" ).autocomplete(
			{
				source: data,
				select: function(e, i)
				{ 
					e.preventDefault();
					var firstname=i.item.firstname;
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
				    if(i.item.send_promo_sms==1)
				    {
				        $('#show_gift_article').bootstrapSwitch('state', true);
				    }
				    if(i.item.gender==0)
				    {
				        $('#gender_male').prop('checked', true);
				    }else if(i.item.gender==1)
				    {
				        $('#gender_female').prop('checked', true);
				    }else{
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
				response: function(e, i) {
		            // ui.content is the array that's about to be sent to the response callback.
		            if(searchTxt != ""){
						if (i.content.length === 0) {
						   $("#customerAlert").html('<p style="color:red">Enter a valid customer name / mobile</p>');
						}else{
						   $("#customerAlert").html('');
						} 
					}else{
					}
		        },
				 minLength: 3,
			});
        }
     });
}

$('#firstname,#lastname,#email,#address1,#address2,#address3,#pincode').on('keyup',function(){
    if(ctrl_page[1]=='cus_profile')
    {
        calculate_profile_percentage();
    }
});


$('#datepicker1,#datepicker1').on('keyup',function(){
    calculate_profile_percentage();
});

$('#religion_select').on('change',function(){
    if(ctrl_page[1]=='cus_profile')
    {
        calculate_profile_percentage();
    }
});

function calculate_profile_percentage()
{
    var sum=0;
    if($('#firstname').val()!='' && $('#firstname').val()!=null)
    {
        sum=sum+10;
    }
    
    if($('#lastname').val()!='' && $('#lastname').val()!=null)
    {
        sum=sum+10;
    }
    
    if($('#email').val()!='' && $('#email').val()!=null)
    {
        sum=sum+10;
    }
    if($('#country').val()!='' && $('#country').val()!=null)
    {
        sum=sum+10;
    }
    
    if($('#state').val()!='' && $('#state').val()!=null)
    {
        sum=sum+10;
    }
    
    if($('#city').val()!='' && $('#city').val()!=null)
    {
        sum=sum+10;
    }
    
    if($('#address1').val()!='' && $('#address1').val()!=null)
    {
        sum=sum+5;
    }
    
    if($('#address2').val()!='' && $('#address2').val()!=null)
    {
        sum=sum+5;
    }
    
    if($('#address3').val()!='' && $('#address3').val()!=null)
    {
        sum=sum+5;
    }
    
     if($('#Village').val()!='' && $('#Village').val()!=null)
    {
        sum=sum+10;
    }
    
     if($('#pincode').val()!='' && $('#pincode').val()!=null)
    {
        sum=sum+5;
    }
    
     if($('#religion_select').val()!='' && $('#religion_select').val()!=null)
    {
        sum=sum+5;
    }
    
     if($('#datepicker').val()!='' && $('#datepicker').val()!=null)
    {
        sum=sum+5;
    }
    
     if($('#datepicker1').val()!='' && $('#datepicker1').val()!=null)
    {
        sum=sum+5;
    }
     $('#progress_bar').css('display','block');
     $('.progress-bar').css('width', sum+'%').attr('aria-valuenow', sum);  
     $('.progress').attr("aria-valuenow",sum);
     $('.skill').html(sum+'%');
    
}

$('#update_profile').on('click',function(){
 
    if($('#id_customer').val()=='')
    {
         msg='<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Please Select Customer</div>';
	     $('#chit_alert1').html(msg);
    }
    else if($('#firstname').val()=='')
    {
         msg='<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Please Enter  First Name </div>';
	     $('#chit_alert1').html(msg);
    }
    else if($('#country').val()=='')
    {
         msg='<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Please Select  Country </div>';
	     $('#chit_alert1').html(msg);
    }
    
    else if($('#state').val()=='')
    {
         msg='<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Please Select  State </div>';
	     $('#chit_alert1').html(msg);
    }
    else if($('#city').val()=='')
    {
         msg='<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Please Select  City </div>';
	     $('#chit_alert').html(msg);
    }
    else if($('#address1').val()=='')
    {
         msg='<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Please Enter The Address </div>';
	     $('#chit_alert1').html(msg);
    }
    else if($('#id_village').val()=='')
    {
         msg='<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Please Select Village </div>';
	     $('#chit_alert1').html(msg);
    }
    else if($('#datepicker').val()=='')
    {
         msg='<div class = "alert alert-danger"><a href = "#" class = "close" data-dismiss = "alert">&times;</a><strong>Warning!</strong> Please Select  Date of Birth </div>';
	     $('#chit_alert1').html(msg);
    }
    else{
       
    $(".overlay").css("display", "block"); 
    var form_data=$('#cus_profile').serialize();
    my_Date = new Date();
	$.ajax({
        url: base_url+'index.php/customer/cus_profile/update/'+$('#id_customer').val()+'/?nocache=' + my_Date.getUTCSeconds(),             
        dataType: "json", 
        method: "POST", 
        data:form_data,
        success: function (data) {
	        window.location.reload();
        }
     });
     $(".overlay").css("display", "none"); 
    }
    
});


//Customer Profile Updation



$("input[name='customer[cus_type]']:radio").on('change',function(){
    if(this.value==1)
    {
        $('#cus_name').html('First Name');
        $('#last_name').css("display","block");
        $('#gstno').css("display","none");
        $('#pan_no').css("display","none");
        $('#gst_number').prop('required',false);
        $('#pan').prop('required',false);
    }else{
        $('#last_name').css("display","none");
        $('#gstno').css("display","block");
        $('#pan_no').css("display","block");
        $('#cus_name').html('Company Name');
        $('#gst_number').prop('required',true);
        $('#pan').prop('required',true);
    }
});


function fnCusItemsExcelReport(export_type)

{

	if(export_list.length >= 1) {

		   var htmls = "";

		  	htmls +='<table class="table table-bordered table-striped text-center"><thead><tr class="bg-teal"><th colspan=8 style="background-color:#39cccc;text-align:center;"> Customer List</th></tr>'

		  	        +'<tr><th width="10%;">ID</th>'

					+'<th width="10%;">Name</th>'

					+'<th width="10%;">Mobile</th>'

					+'<th width="10%;">Account</th>'

					+'<th width="10%;">Member Since</th>'

					+'<th width="10%;">Status</th>'

					+'<th width="10%;">Profile</th>'

					+'<th width="10%;">Created Through</th>'

					+'</tr></thead><tbody>';

			var textRange; var j=0;

			$.each(export_list, function (index, val) {

				var datas           = "";

				var title = val.title!=	null?val.title+". ":'';
                title =  title+""+val.name;

				if(val.edit_custom_entry_date==0) {
					var date_add =  val.date_add+' </br> '+val.custom_entry_date;
				} else{
					var date_add =  val.custom_entry_date+'</br> '+val.date_add;
				}

				var active = val.active==1 ? "Active" : "InActive";

				var profile_complete = val.profile_complete == 1 ? "Complete" : "Incomplete";

				var added_by = val.added_by == 0 ? "Web" : (val.added_by == 1 ? "Admin" : "Mobile");

				htmls +='<div class="innerDetails" style="margin-bottom: 20px;"><table class="table table-responsive table-bordered text-center table-sm"><tr class="prod_det_btn"><td style="width: 8%;color:red;">'+val.id_customer+'</td><td style="width: 8%;">'+title+'</td><td style="width: 8%;">'+val.mobile+'</td><td style="width: 8%;">'+val.accounts+'</td><td style="width: 8%;">'+date_add+'</td><td style="width: 8%;">'+active+'</td><td style="width: 8%;">'+profile_complete+'</td><td style="width: 8%;">'+added_by+'</td></tr>';

			});

		  htmls+='</tbody><tfoot></tfoot></table></div>';

		  if(export_type == '1') {

			var uri = 'data:application/vnd.ms-excel;base64,';

            var template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'; 

            var base64 = function(s) {

                return window.btoa(unescape(encodeURIComponent(s)))

            };

            var format = function(s, c) {

                return s.replace(/{(\w+)}/g, function(m, p) {

                    return c[p];

                })

            };

			   var ctx = {

                worksheet : 'Worksheet',

                table : htmls

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


    $("#bulk_allocate_agent").click(function(){
    
        var data = {'id_customer[]' : []};
        
        $("input[name='id_customer[]']:checked").each(function() {
        
        data['id_customer[]'].push($(this).val());
        
        });
        
        if(data['id_customer[]'].length > 0){
            get_agent_select();
            $('#allocate_agent_modal').modal('show');
           
        }else{
            $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br> Select customer to allocate..."});
        }
    });
    
     $("#allocate_agent_btn").click(function(){
         
        var id_agent = $('#agent_select').val();
    
        var data = {'id_customer[]' : [],'id_agent':id_agent};
        
        $("input[name='id_customer[]']:checked").each(function() {
        
        data['id_customer[]'].push($(this).val());
        
        });
    
        
        if(id_agent == '' || id_agent == null || id_agent == undefined){
            $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br> Select Agent to allocate..."});
        }else{
            $.ajax({
        
            type: "POST",
            
            url: base_url+"index.php/admin_customer/allocate_agent_toCuctomers",
            
            data: data,
            
            dataType: "json", 
            
            sync:false,
            
            success: function(data){
            
                $("div.overlay").css("display", "none"); 
                
                $('#allocate_agent_modal').modal('hide');
                
                //$('input[name='id_customer[]']').prop('checked', false);
                
                if(data.status == 1){
                    $.toaster({ priority : 'success', title : 'Success!', message : ''+"</br> Agent allocated successfully for " +data.total+ "..."});
                }else if(data.status == 2){
                    $.toaster({ priority : 'success', title : 'Success!', message : ''+"</br> Agent allocated successfully for " +data.total+ " only..."});
                    $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br> Agent not allocated for " +data.not_allocated+ "..."});
                }else{
                    $.toaster({ priority : 'danger', title : 'Warning!', message : ''+"</br> Unable to update agent for the selected customers..."});
                }  
                
                window.location.reload();
            
            }				 
        });
        }
        
    });
    
    
    
    function get_agent_select(){
        
    	$('#agent_select option').remove();

    	$.ajax({
    
    	  type: 'GET',
    
    	  url:  base_url+'index.php/admin_customer/ajax_getAllActiveAgents',
    
    	  dataType: 'json',
    
    	  success: function(data) {
    	      
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
        
        		$("#agent_select").select2("val", ($('#agent_select').val()!=null?$('#agent_select').val():''));
        
        		var selectid=$('#agent_select').val();
        
        		if(selectid!=null && selectid>0){
        			$('#id_agent').val(selectid);
        	    }
        
        	   
    
    	    },
    
    	  	error:function(error){
    
    			$("div.overlay").css("display", "none"); 
    
    		}
    
    	});
 
        
    }
    
    
    // agent allocation ends...
    
    
    function get_profession(){
	$('.overlay').css('display','block');
	
		$.ajax({
	
		  type: 'GET',

		  url:  base_url+'index.php/settings/company/getprofession',
	
		  dataType: 'json',
	
		  success: function(data) {

			$.each(data, function (key,data ) {
	
				$('#profession').append(
	
					$("<option></option>")
	
					  .attr("value", data.id_profession)
	
					  .text(data.name)
				);	
			});
	
			$("#profession").select2("val", ($('#professionval').val()!=null?$('#professionval').val():''));
	
			$('.overlay').css('display','none');
	
			},
	     error:function(error)  {
	
			$("div.overlay").css("display", "none"); 

	     }
		});
	
		}
    
//webcam upload, #AD On:20-12-2022,Cd:CLin,Up:AB  -->starts    
    function take_snapshot(type){
    	//Snap Shots Disables
		var pre_img_resource=[];
		var pre_img_files=[];
    	$('#snap_shots').prop('disabled',true);
    		if(type == 'pre_images'){
    			var preview = 'uploadArea_p_stn';
    		}
            Webcam.snap( function(data_uri) {
               $(".image-cust").val(data_uri);
    			pre_img_resource.push({'src':data_uri,'name':(Math.floor(100000 + Math.random() * 900000))+'jpg','is_default':"0"});
    			pre_img_files.push(data_uri); 
    			alert("Your Webcam Images Take Snap Shot Successfullys.");
            } );
			$('#customer_images').val(encodeURIComponent(JSON.stringify(pre_img_resource)));
		    console.log(pre_img_resource);
	     	var show = $('#cus_img_preview');
			 show.prop('src',pre_img_resource[0].src);
			 show.css('display','block');	
    		
    	setTimeout(function(){      	 	
    		var resource = [];				
    		$('#'+preview+' div').remove();				
    		if(type == 'pre_images'){  				    
    			resource = pre_img_resource;			
    		}			
    		$.each(resource,function(key,item){		  	
    			if(item){  		   			
    			var div = document.createElement("div");			
    			div.setAttribute('class','images'); 			
    			div.setAttribute('id',+key); 			
    			param = {"key":key,"preview":preview,"stone_type":type};					
    			div.innerHTML+= "<span style='float:left;'><a onclick='remove_stn_img("+JSON.stringify(param)+")' style='cursor:pointer;color:inherit;' data-toggle='tooltip' data-placement='bottom' title='Delete This Images'>&nbsp;<i class='fa fa-trash'></i></a></span>&nbsp;&nbsp;<img class='thumbnail' src='" + item.src + "'" +				"style='width: 100px;height: 100px;'/>";  					
    			$('#'+preview).append(div);		   	}		   
    			$('#lot_img_upload').css('display',''); 				
    		});
			
			
    		$('#snap_shots').prop('disabled',false);			
    	
    	},100); 
    }
    
    function remove_stn_img(param)
	 {     	  
		 var current_status   = $(".tag_default_"+param.key).is(':checked');
		  $('#'+param.preview+' #'+param.key).remove();	
		  $('#customer_images').val();	
		  var preview = $('#cus_img_preview');
		  preview.prop('src','');
		  preview.css('display','none');		

		$('#customer_images').val();

	 }
//webcam upload ends...





     