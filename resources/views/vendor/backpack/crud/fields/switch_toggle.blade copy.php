<style>
    /* CSS to style the toggle switch and labels */
    .custom-switch {
        display: inline-block;
        cursor: pointer;
    }

    .custom-switch-label {
        display: block;
        width: 40px;
        /* Adjust the width as needed */
        height: 20px;
        /* Adjust the height as needed */
        background-color: #ddd;
        border-radius: 20px;
        /* Half of the height to create a circular handle */
        position: relative;
        transition: background-color 0.3s ease;
    }

    .custom-switch-input:checked+.custom-switch-label {
        background-color: #007bff;
        /* Change to the desired color when the switch is On */
    }

    .custom-switch-label::after {
        content: '';
        width: 18px;
        /* Adjust the size of the handle */
        height: 18px;
        /* Adjust the size of the handle */
        background-color: #fff;
        /* Color of the handle */
        border-radius: 50%;
        position: absolute;
        top: 1px;
        left: 2px;
        transition: left 0.3s ease;
    }

    .custom-switch-input:checked+.custom-switch-label::after {
        left: 20px;
        /* Move the handle to the right when the switch is On */
    }

    /* Style for the "On" and "Off" labels */
    .custom-switch-labels {
        display: flex;
        justify-content: space-between;
        margin-top: 5px;
        /* Adjust as needed */
        color: #777;
        /* Color of the labels */
    }

    .hidden {
        display: none !important;
    }
</style>
<div class="{{ $field['wrapper']['class'] ?? 'form-group col-md-4' }}">
    <label for="{{ $field['name'] }}">{!! $field['label'] !!}</label>
    <div class="d-flex align-items-center">
        <span class="mr-2">{{ $field['options'][0] }}</span>
        <label class="custom-control custom-switch mb-0 mr-2">
            <input type="checkbox" class="custom-control-input" id="{{ $field['name'] }}" name="{{ $field['name'] }}"
                value="{{ $field['options'][0] }}" @if ($field['value'] === $field['options'][0] || old(square_brackets_to_dots($field['name'])) === $field['options'][0]) checked @endif>
            <span class="custom-control-label"></span>
        </label>
        <span>{{ $field['options'][1] }}</span>
    </div>
</div>

@push('crud_fields_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const switchToggle = document.querySelector('#{{ $field['name'] }}');
            const nameField = document.querySelector('#mp_id');
            const addressField = document.querySelector('#address');

            function toggleFields() {
                if (switchToggle.checked) {
                    nameField.style.display = "block"; // Show the name field
                    nameField.previousElementSibling.style.display = "inline"; // Show the label

                    addressField.style.display = "none"; // Hide the address field
                    addressField.previousElementSibling.style.display = "none"; // Hide the address field label
                } else {
                    nameField.style.display = "none"; // Hide the name field
                    nameField.previousElementSibling.style.display = "none"; // Hide the label

                    addressField.style.display = "block"; // Show the address field
                    addressField.previousElementSibling.style.display = "inline"; // Show the address field label

                    // Reset the value of the hidden fields when they are hidden
                    nameField.value = '';
                    addressField.value = '';
                }
            }

            toggleFields(); // Initial toggle of the fields based on the switch state

            switchToggle.addEventListener('change', function() {
                toggleFields(); // Toggle the fields based on the switch state change
            });
        });
    </script>
@endpush
