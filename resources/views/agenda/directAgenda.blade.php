<style>
    
    .about-section {
        background: #f8f9fa;
        padding: 20px 0;
    }

    .inner-width {
        padding: 0 20px;
        margin: auto;
        width:750px;
    }

    .page-header {
        font-size: 20px !important;
        text-align: center;
    }

    .about-section h1 {
        text-align: center;
    }

    .about-form {
        font-size: 16px;
    }

    .about strong {
        color: blue;
    }

    .message {
        font-size: 20px;
        margin-top: 30vh;
        color: red;
    }


    .border {
        width: 100%;
        height: 3px;
        background: #e74c3c;
        margin: 15px auto;
    }

    .about-section-row {
        display: flex;
        flex-wrap: wrap;
    }

    .about-section-col {
        flex: 50%;
    }

    .about {
        padding-right: 30px;
        font-family: Arial, Helvetica, sans-serif;
    }

    .about p {
        text-align: justify;
        margin-bottom: 20px;
        font-size: 0.97rem !important;
        line-height: 1.5rem;

    }

    .about a {
        display: inline-block;
        color: #e74c3c;
        text-decoration: none;
        border: 2px solid #e74c3c;
        border-radius: 24px;
        padding: 8px 40px;
        transition: 0.4s linear;
    }

    .about a:hover {
        color: #fff;
        background: #e74c3c;
    }

    .time_request_form small {
        color: red;
        font-weight: bold;
        font-size: 15px;
    }

    .select-multiple{
        width:650px  !important;
    }

    .select2-container--open{
    z-index:100000;
    }

 
</style>

<div class="row about-section pt-2">
    <div class="col-md-12 inner-width">
        <div class="page-header font-weight-bold">ठाडो प्रस्ताव</div>
        <div class="border"></div>
        <div class="about-section-row">
            <div class="about-section-col">
                <div class="about-form pl-4 pr-4">
                    <form id="time_request_form" name="time_request_form" class="time_request_form" action="{{'/admin/save-direct-agenda'}}" method="POST">
                        @csrf
                        <input type="hidden" name="ec_meeting_request_id" value="{{$ec_meeting_request_id}}">
                        <input type="hidden" name="fiscal_year_id" value="{{$fiscal_year_id}}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="agenda_code">निर्णय नं.<small>*</small></label>
                                    <input type="text" class="form-control" id="agenda_code" value="{{$agenda_code}}" name="agenda_code" placeholder="निर्णय नं." readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="ministry_id">मन्त्रालय<small>*</small></label>
                                <select name="ministry_id" class="form-control" id="ministry_id">
                                    <option value="">-</option>
                                    @foreach($ministries as $ministry)
                                        <option value="{{ $ministry->id }}"> {{ $ministry->name_lc }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="agenda_type_id">प्रस्तावको प्रकार<small>*</small></label>
                                <select name="agenda_type_id" class="form-control" id="agenda_type_id">
                                    @foreach($agenda_types as $agenda_type)
                                        <option value="{{ $agenda_type->id }}"> {{ $agenda_type->name_lc }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                        </div>
                        
                        <div class="row mt-3 mb-3">
                            <div class="form-group col-md-12">
                                <label for="agenda_title">प्रस्तावको विषय<small>*</small></label>
                                <textarea class="form-control" id="agenda_title" name="agenda_title" placeholder="प्रस्तावको विषय" rows="2"></textarea>

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="agenda_description">प्रस्तावको विवरण <small>*</small></label>
                            <textarea class="form-control" id="agenda_description" name="agenda_description" placeholder="प्रस्तावको विवरण" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <a type="submit" id="submit-btn"
                                class="btn btn-success text-white btn-theme-colored btn-flat float-right fa fa-save"
                                data-loading-text="please wait..."> Save</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>


<script>

    $('#submit-btn').on('click',function(){
        let data = $('form#time_request_form').serializeArray();
        let form_action = $('form#time_request_form').attr('action');
        $.post(form_action,data,function(response){
            if(response.status == 'success'){
                location.reload();
            }else{
                alert('Under Construction')
            }
        })
    });
    
</script>