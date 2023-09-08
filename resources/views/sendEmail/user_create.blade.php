<style type="css">
    .btn {
        display: inline-block;
        font-weight: 400;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        border: 1px solid transparent;
        padding: .375rem .75rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: .25rem;
        transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }

    .btn-primary {
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }
</style>


<div class="row">
    <div class="card text-center">
        <div class="card-body p-2">     
            <div class="row">
                <h2>प्रिय  {{ $name }}</h2>
                </p>तपाईंको खाताको प्रमाणपत्रहरू तल खोज्नुहोस्:</p>
            </div>    
            <div class="row">
                <div class="col-md-12">नाम : {{ $name }}</div>
            </div>
            <div class="row">
                <div class="col-md-12">ईमेल : {{ $email }}</div>
            </div>
            <div class="row">
                <div class="col-md-12">पासवर्ड परिवर्तन लिंक: {{ $link }}</div>
            </div>


            <div class="row">
                
            </div>
            
            <div class="row">
                लगइन गर्नपछि कृपया पासवर्ड बदल्नुहोस्
            </div>
        </div>
    </div>
    </div>
