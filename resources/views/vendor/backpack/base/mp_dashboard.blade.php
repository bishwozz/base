@extends(backpack_view('blank'))

<style>
    .card {
        border-radius: 10px;
        border: none;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        min-height:90%;
        background: linear-gradient(#c3f0d2 20%, #f4f0a8 60%);

    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: none;
        padding: 10px 20px;
    }

    .card-header h5 {
        font-size: 20px;
        margin-bottom: 0;
    }

    .card-body {
        padding: 20px;
    }

    h5 {
        font-family: Kalimati;
        color: black;
        font-weight: bold !important;
    }

    .box {
        text-align: center;
        padding: 30px;
        background-color: #f8f9fa;
        border-radius: 10px;
        font-family: "kalimati", sans-serif !important;
    }

    .box a:hover {
        text-decoration: none;
    }

    .box-1 {
        background: #74ebd5;
        /* fallback for old browsers */
        background: -webkit-linear-gradient(to top, #74ebd5, #acb6e5);
        /* Chrome 10-25, Safari 5.1-6 */
        background: linear-gradient(to top, #74ebd5, #acb6e5);
        border-radius: 4px;
        box-shadow: 0px 0px 30px rgb(0 0 0 / 5%);
        margin: 0px 0px 25px;
        padding: 13px 0px 25px 5px;
        text-align: center;
    }

    .box-3 {
        background: #a7bfe8;
        background: -moz-linear-gradient(top, #a7bfe8 20%, #6190e8 80%);
        background: -webkit-linear-gradient(top, #a7bfe8 20%, #6190e8 80%);
        background: linear-gradient(#a7bfe8 20%, #6190e8 80%);
        border-radius: 4px;
        box-shadow: 0px 0px 30px rgb(0 0 0 / 5%);
        margin: 0px 0px 25px;
        padding: 13px 0px 25px 5px;
        text-align: center;
    }

    .box-2 {
        background: #ef3b36;
        /* fallback for old browsers */
        background: -webkit-linear-gradient(to top, #ef3b36, #fce9e9);
        /* Chrome 10-25, Safari 5.1-6 */
        background: linear-gradient(to top, #ef3b36, #ffffff);
        border-radius: 4px;
        box-shadow: 0px 0px 30px rgb(0 0 0 / 5%);
        margin: 0px 0px 25px;
        padding: 13px 0px 25px 5px;
        text-align: center;
    }


    .circle-tile-description {
        font-size: 20px;
        margin-top: 10px;
        color: black;
        font-weight: bold;

    }

    .box-icon {
        margin-top: 20px;
    }

    .circle-tile-number {
        display: inline-block;
        font-size: 40px;
        color: black;
        font-weight: bold;

    }
    .avatar {
        border-radius: 50%;
        width: 100px !important; 
        height: 100px !important;
        object-fit: cover;
    }
    .user-details p{
        line-height: .7rem;
    }
</style>

@section('content')
  
<div class="card mt-2">
    <div class="card-header">
        <h5>ड्यासबोर्ड</h5>
        <div class="row mt-3">
            <div class="col-sm-3 col-md-2">
              <img src="{{url('/storage/uploads/'.backpack_user()->mp->photo_path)}}" alt="User Avatar" class="avatar" >
            </div>
            <div class="col-sm-9 col-md-6">
              <div class="user-details">
                <p><strong>नाम :</strong> {{backpack_user()->name}}</p>
                <p><strong>मन्त्रालय :</strong> {{backpack_user()->ministry->name_lc}}</p>
                <p><strong>ई-मेल :</strong> {{backpack_user()->mp->email}}</p>
                <p><strong>फोन :</strong> {{backpack_user()->mp->mobile_number}}</p>
              </div>
            </div>
          </div>
    </div>

    <div class="card-body">
        <section class="parallax-section">
            <div class=" bootstrap snippet" id="dashboardData">
                <div id="dashboard" class="card-body form-row px-5">
                    <div class="col-sm-12 col-md-6 col-lg-4 p-2 px-3">
                        <a href='{{ backpack_url('agenda') }}'>
                            <div class="box-1 box">
                                <div class="circle-tile-description">
                                    प्रस्ताव
                                </div>
                                <div class="box-icon">
                                    <span class="circle-tile-number"><i class='nav-icon la la-cogs'></i></span>
                                </div>
                        </a>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4 p-2 px-3">
                    <a href='{{ backpack_url('ec-meeting-request') }}'>
                        <div class="box-2 box">
                            <div class="circle-tile-description">
                                कार्यसूची
                            </div>
                            <div class="box-icon">
                                <span class="circle-tile-number"><i class='nav-icon la la-file'></i></span>
                            </div>
                    </a>   
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 p-2 px-3">
                <a href='{{ backpack_url('meeting-minute-detail') }}'>
                    <div class="box-3 box">
                        <div class="circle-tile-description">
                            माइनुट विवरण
                        </div>
                        <div class="box-icon">
                            <span class="circle-tile-number"><i class='nav-icon la la-book'></i></span>
                        </div>
                </a>
            </div>
    </div>
</div>
</div>
</section>
</div>
</div>

@endsection