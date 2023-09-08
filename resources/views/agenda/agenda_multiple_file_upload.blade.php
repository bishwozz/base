
<form method="POST" action="/admin/upload-multiple-files-agends" enctype="multipart/form-data">
    @csrf
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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

<style>
    #file-repeater {
        margin-bottom: 10px;
    }

    .row {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    input[type="file"],
    input[type="text"] {
        padding: 5px;
        margin-right: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .remove-file {
        padding: 5px;
        background-color: transparent;
        border: none;
        cursor: pointer;
    }

    .remove-file i {
        color: #ff5a5f;
        font-size: 18px;
    }

    #add-file {
        padding: 5px 10px;
        background-color: #33cc33;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    button[type="submit"] {
        padding: 5px 10px;
        background-color: #428bca;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
</style>
    
<div class="row about-section pt-2">
    <div class="col-md-12 inner-width">
        <div class="page-header font-weight-bold">फाइल अपलोड</div>
        <div class="border"></div>
        <div class="about-form pl-4 pr-4">
            <form id="time_request_form" name="time_request_form" class="time_request_form" action="{{'/admin/save-direct-agenda'}}" method="POST">
                @csrf
                <input type="hidden" name="agenda_id" value="{{$agenda_id}}">
                <div id="file-repeater">
                    <div class="file-row row">
                        <div class="col-md-4">
                            <input type="file" name="files[]" multiple class="form-control">
                        </div>
                        <div class="col-md-4">
                            <select name="agenda_decision_type_ids[]" class="form-control">
                                <option value="">-</option>
                                @foreach($file_types as $type)
                                <option>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="names[]" placeholder="Enter name" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-danger remove-file">Remove</button>
                        </div>
                    </div>
                </div>
                <button type="button" id="add-file" class="btn btn-primary">+</button>
                <div class="form-group">
                    <button type="submit" id="submit-btn" class="btn btn-success text-white btn-theme-colored btn-flat float-right">
                        <i class="fa fa-save"></i> Save
                    </button>
                </div>
            </form>
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
                
        })
    </script>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#add-file').click(function() {
            var fileRow = $('.file-row').first().clone();
            fileRow.find('input').val('');
            $('#file-repeater').append(fileRow);
        });
        
        $(document).on('click', '.remove-file', function() {
            $(this).closest('.file-row').remove();
        });
    });
</script>
