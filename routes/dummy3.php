@extends('master')


{{--External Style Section--}}
@section('style')
	{!! Html::style("assets/libs/bootstrap3-wysihtml5-bower/bootstrap3-wysihtml5.min.css") !!}
	{!! Html::style("assets/libs/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css") !!}
	{!! Html::style("assets/libs/data-table/datatables.min.css") !!}
@endsection


<style>
select , label{
	width:100%;
}
select{
	min-height:40px;
}
</style>
@section('content')

    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Bur Form</h2>
        </div>
        
        <div class="p-30 p-t-none p-b-none">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#personal_details" aria-controls="home" role="tab" data-toggle="tab">{{language_data('Personal Details')}}</a></li>

                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content panel p-20">


                        {{--Personal Details--}}

                        <div role="tabpanel" class="tab-pane active" id="personal_details">
                            <form role="form" method="post" action="{{url('employees/generateburform')}}" >
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="row">
                                    <div class="col-md-6">
									
										
										
										
										
										
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" class="form-control"   value="{{$employee->fname }}" name="name">
                                        </div>
										
										
										<div class="form-group">
											<label>Company Address</label>
											<span class="help"></span>
											<input type="text" class="form-control"   value="{{$employee->company_address }}" name="company_address">
										</div> 
										
										<div class="form-group">
                                            <label>Name Of Employer</label>
                                            <input type="text" class="form-control" value="{{$employee->name_of_employer}}" name="noe">
											{{----}}
                                        </div>
										<div class="form-group">
                                            <label>Address Of Employer</label>
                                            <input type="text" class="form-control"    value="{{$employee->address_of_employer}}" name="aoe">
                                        </div>

										
										<div class="form-group">
                                            <label>Fullname</label>
                                            <input type="text" class="form-control" value="{{$employee->name }}" name="fullname">
                                        </div>
										<div class="form-group">
                                            <label>Name in Chines</label>
                                            <input type="text" class="form-control" value="{{$employee->chinese_name}}" name="chinesname">
											{{----}}
                                        </div>



										 <div class="form-group">
											<label for="el3">Current Nationality</label>
											<select class="selectpicker form-control" data-live-search="true" name="nationality" id="">
												<option>Country of Origin</option>
												<?php
												foreach($countries as $c){
													 ?>
													<option value="<?php echo $c->country_name; ?>"> <?php echo $c->country_name ?></option>
												<?php } ?>
											</select>
										</div>

                                   

														
									   
										<div class="form-group">
											<label>Sex</label>
											<select class="jantina" name="sex">
											   <?php if( $employee->gender == "Male"){ $selected = "1"; } ?>
											   <?php if( $employee->gender == "female"){ $selected = "2"; } ?>
												
												<option value="FEMALE" <?php if(isset($selected) && $selected == 2){ echo "selected"; } ?>>FEMALE</option>
												<option value="MALE" <?php if(isset($selected) && $selected == 1){ echo "selected"; } ?>>MALE</option>
												
											</select>
										</div>
										   
										<div class="form-group">
											<label>Date And Place Of Birth</label>
											<span class="help"></span>
											<input type="text" class="form-control"  value=" {{$employee->dob}}  {{$employee->place_of_birth}}" name="dapob">
										</div>  
											
										<div class="form-group">
											<label>Present Address</label>
											<span class="help"></span>
											<input type="text" class="form-control"   value="{{$employee->pre_address}} " name="presentaddress">
										</div>  
										
							
										
										<div class="form-group">
											<label>Present Occupation</label>
											<span class="help"></span>
											<input type="text" class="form-control" value="{{$employee->designation_name->designation}}" name="po">
											{{----}}
										</div>  
											
											  
										 <div class="form-group">
											<label>Nature of employement offered</label>
											<span class="help"></span>
											<input type="text" class="form-control"   value="{{$employee->nature_employment_offered}}" name="noeo">
										</div> 
										
										 <div class="form-group">
											<label>Qualificaion and experience</label>
											<span class="help"></span>
											<input type="text" class="form-control"   value="{{$employee->quali_exp}}" name="qae">
										</div>
										 <div class="form-group">
											<label>period of employment offered</label>
											<span class="help"></span>
											<input type="text" class="form-control"   value="{{$employee->period_employment}}" name="poeo">
										</div> 
										 <div class="form-group">
											<label>Cash wages per mensal</label>
											<span class="help"></span>
											<input type="text" class="form-control"   value="{{$employee->cash_wages}}" name="cwpm">
										</div> 
											  
				 </div>
				 <div class="col-sm-6">
				 
					<div class="form-group">
						<label>Passport and travel Doc</label>
						<span class="help"></span>
						<input type="text" class="form-control" value="{{$employee->travel_document}}" name="pptr">
					</div> 
					<div class="form-group">
						<label>Number</label>
						<span class="help"></span>
						<input type="text" class="form-control" value="{{$employee->passport_no}}" name="number">
					</div>
					<div class="form-group">
						<label>Date Of Issue</label>
						<span class="help"></span>
						<input type="date" class="form-control" value="{{$employee->doa}}" name="doi">
					</div>
					<div class="form-group">
						<label>Place Of Issue</label>
						<span class="help"></span>
						<input type="text" class="form-control" value="{{$employee->passport_place_isse}}" name="poi">
					</div>
					<div class="form-group">
						<label>Type of Document</label>
						<span class="help"></span>
						<input type="text" class="form-control" value="{{$employee->type_of_document}}" name="tod">
					</div>
					<div class="form-group">
						<label>Valid for rentry date</label>
						<span class="help"></span>
						<input type="text" class="form-control" value="{{$employee->valid_re_entry}}" name="vfrt">
					</div>
					<div class="form-group">
						<label>Valid Until</label>
						<span class="help"></span>
						<input type="date" class="form-control" value="{{$employee->validity_visa}}" name="validuntil">
					</div>
					<hr>
					<div class="form-group">
						<label>NRIC number</label>
						<span class="help"></span>
						<input type="text" class="form-control" value="{{$employee->i_c_no}}" name="nric">
					</div>
					<div class="form-group">
						<label>Bottom date</label>
						<span class="help"></span>
						<input type="date" class="form-control" value="{{$employee->bottom_date}}" name="bottomdate">
						{{----}}
					</div>
					
					<div class="form-group">
						<label>Bottom date of issue</label>
						<span class="help"></span>
						<input type="date" class="form-control" value="{{$employee->bottom_date_issue}}" name="bottomdoi">
						{{----}}
					</div>
					<div class="form-group">
						<label>Bottom place of issue</label>
						<span class="help"></span>
						<input type="text" class="form-control" value="{{$employee->bottom_place}}" name="bottompoi">
						{{----}}
					</div>
					
					
					
				</div>
								 
				
			 <div class="col-sm-12">
			     <input type="submit" class="btn btn-success" value="Generate Visa PDF">
			 </div>
							   </div>


                            </form>

                        </div>


                    </div>
                </div>
            </div> 
        </div>
    </section>
	

@endsection

{{--External Style Section--}}
@section('script')
	{!! Html::script("assets/libs/handlebars/handlebars.runtime.min.js")!!}
	{!! Html::script("assets/libs/moment/moment.min.js")!!}
	{!! Html::script("assets/libs/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js")!!}
	{!! Html::script("assets/libs/wysihtml5x/wysihtml5x-toolbar.min.js")!!}
	{!! Html::script("assets/libs/bootstrap3-wysihtml5-bower/bootstrap3-wysihtml5.min.js")!!}
	{!! Html::script("assets/libs/data-table/datatables.min.js")!!}
	{!! Html::script("assets/js/form-elements-page.js")!!}
	{!! Html::script("assets/js/bootbox.min.js")!!}
@endsection
