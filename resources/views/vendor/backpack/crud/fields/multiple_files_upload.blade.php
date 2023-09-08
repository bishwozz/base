<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
    .row {
        width: 100%;
    }

    .about-section {
        background: #f8f9fa;
        padding: 20px 0;
    }

    .inner-width {
        padding: 0 20px;
        margin: auto;
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

    .select-multiple {
        width: 650px !important;
    }

    .select2-container--open {
        z-index: 100000;
    }

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
        border: none;
        cursor: pointer;
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
@php
    $ministry_id = backpack_user()->ministry_id;
    $ministry = App\Models\Ministry::findOrFail($ministry_id);
    $ministry_code = null;
    if ($ministry) {
        $ministry_code = $ministry->code;
    }
@endphp
<div class=" row">
    <div class="col-md-12 inner-width">
        <div class="page-header font-weight-bold">फाइल अपलोड</div>
        <div class="border"></div>
        <div class="about-form pl-4 pr-4">

            <input type="hidden" name="agenda_id" value="">
            <div id="file-repeater">
                @if (isset($existing_files) && count($existing_files) > 0)
                    @foreach ($existing_files as $file)
                        <div class="file-row row mb-4">

                            <div class="col-md-3">
                                <div>
                                    @if ($file->path)
                                        <div class="existing-file-info">
                                            <a href="{{ asset('storage/uploads/' . $file->path) }}" target='_blank'><i
                                                    class="la la-file-pdf-o fa-2x"
                                                    style="color:red; text-decoration:none;"></i>
                                                {{ $file->name }}</a>
                                        </div>
                                    @endif
                                </div>
                                <input type="file" name="files[]" class="form-control" accept="application/pdf">
                            </div>
                            <div class="col-md-3">
                                <div style="height: 34px;"></div>
                                <select name="file_type_ids[]" id="file_type_id" class="form-control"
                                    onChange="handleInputChange(this)" required>
                                    <option value="0">-</option>
                                    @foreach ($file_types as $key => $type)
                                        <option value="{{ $key }}"
                                            {{ $key == $file->agenda_file_type_id ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div style="height: 34px;"></div>

                                <input type="text" name="names[]" id="name"
                                    placeholder="यदी आफ्नै नाम राख्ने भए यहाँ लेख्नु होस् ।" class="form-control"
                                    value="{{ $file->name }}" required>
                            </div>
                            <div class="col-md-2">
                                <div style="height: 34px;"></div>

                                <button type="button" class="btn btn-danger remove-file" style="float: right;"><i
                                        class="fa fa-trash"></i></button>
                            </div>
                        </div>
                        <hr>
                    @endforeach
                @else
                    <div class="file-row row mb-4">
                        <div class="col-md-4">
                            <input type="file" name="files[]" class="form-control" accept="application/pdf">
                        </div>
                        <div class="col-md-3">
                            <select name="file_type_ids[]" id="file_type_id" required class="form-control"
                                onChange="handleInputChange(this)" required>
                                <option value="0">-</option>
                                @foreach ($file_types as $key => $type)
                                    <option value={{ $key }}>{{ $type }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="names[]" id="name"
                                placeholder="यदी आफ्नै नाम राख्ने भए यहाँ लेख्नु होस् ।" class="form-control">
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger remove-file" style="float: right;"><i
                                    class="fa fa-trash"></i></button>
                        </div>
                    </div>
                @endif
            </div>
            <hr>
            <button type="button" id="add-file" class="btn btn-primary"><i class="fa fa-plus"></i> Add</button>

        </div>
    </div>
</div>
<hr>




<script>
    let i = 1; // Start with the initial value of i
    let ministry_id = {{ $ministry_id }};

    $(document).ready(function() {
        // Initially, hide the delete button on the first file row
        $('.file-row:first-child .remove-file').hide();

        // Track the selected options for the file_type_ids select elements
        const selectedOptions = new Set();

        // Function to add selected option to the Set
        function addSelectedOption(selectedValue) {
            if (selectedValue !== '0') {
                selectedOptions.add(selectedValue);
            }
        }

        // Function to handle adding a new file row
        $('#add-file').click(function() {
            var fileRow = $('.file-row').first().clone();
            fileRow.find('input').val('');

            // Set the default value of the file_type_ids select element to 0
            fileRow.find('select[name="file_type_ids[]"]').val('0');

            // Show the delete button for the new file row
            fileRow.find('.remove-file').show();

            // Reset the existing-file-info block for the new row
            fileRow.find('.existing-file-info').remove();

            // Increment the value of i and synchronize with the PHP variable
            i++;
            fileRow.removeClass().addClass('file-row row repeter-' + i);

            // Append the new file row to the file-repeater
            $('#file-repeater').append(fileRow);

            // Update the selectedOptions Set when a file_type_id is changed
            fileRow.find('select[name="file_type_ids[]"]').on('change', function() {
                var selectedValue = $(this).val();
                debugger;
                // Check if the selected option has been selected in another clone
                if (selectedOptions.has(selectedValue)) {
                    $(this).val('0'); // Set the value back to the default option
                    alert(
                        "This option is already selected in another row. Please select a different option."
                    );
                } else {
                    // Add the selected option to the selectedOptions Set
                    selectedOptions.add(selectedValue);
                }
            });

            // Remove the selected option from the selectedOptions Set when a file row is removed
            fileRow.find('.remove-file').on('click', function() {
                var selectElement = $(this).closest('.file-row').find(
                    'select[name="file_type_ids[]"]');
                var selectedValue = selectElement.val();
                selectedOptions.delete(selectedValue);
            });
        });


        // Populate selectedOptions Set by checking selected options in existing rows
        $('.file-row select[name="file_type_ids[]"]').each(function() {
            addSelectedOption($(this).val());
        });
        // Function to handle removing a file row
        $(document).on('click', '.remove-file', function() {
            // Check if there is only one file row, and if so, hide the delete button
            if ($('.file-row').length === 1) {
                $('.remove-file').hide();
            }
            $(this).closest('.file-row').remove();
        });
    });


    function handleInputChange(e) {
        // Find the input element with id="name" within the same .file-row
        var nameInput = $(e).closest('.file-row').find('input[id="name"]');
        // Get the value of the selected option
        var selectedOptionValue = e.value;

        // Check if the selected option has a valid value (not equal to 0)
        if (selectedOptionValue !== '0') {
            // If a valid option is selected, add the "required" attribute to the input field
            nameInput.prop('required', true);
        } else {
            // If the default option is selected, remove the "required" attribute from the input field
            nameInput.prop('required', false);
        }

        // Get the text of the selected option
        var selectedOptionText = e.selectedOptions[0].textContent.trim();
        nameInput.val(ministry_id + '_' + selectedOptionText + '.pdf');
    }

    // jQuery code
    // $(document).ready(function() {
    //     // When files are selected in the "files[]" input
    //     $('input[name="files[]"]').on('change', function() {
    //         // Get the selected files
    //         let selectedFiles = this.files;
    //         let fileReaders = [];

    //         // Clear any previously selected files in "upload[]" input
    //         $('input[name="upload[]"]').val('');

    //         // Create FileReader for each selected file
    //         for (let i = 0; i < selectedFiles.length; i++) {
    //             let reader = new FileReader();
    //             reader.onload = function(event) {
    //                 // Add the base64 data to the array
    //                 fileReaders[i] = event.target.result;

    //                 // If all files have been read, add them to the "upload[]" input
    //                 if (fileReaders.length === selectedFiles.length) {
    //                     $('input[name="upload[]"]').val(fileReaders.join(','));
    //                 }
    //             };
    //             reader.readAsDataURL(selectedFiles[i]);
    //         }
    //     });
    // });
</script>
