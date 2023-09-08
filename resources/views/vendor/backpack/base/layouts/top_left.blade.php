<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}" dir="{{ config('backpack.base.html_direction') }}">

<head>
  @include(backpack_view('inc.head'))

  <style>
      .fancybox-content {
      max-width: 80%;
      max-height:90%;
  }

  </style>

</head>

<body class="{{ config('backpack.base.body_class') }}">

  @include(backpack_view('inc.main_header'))

  <div class="app-body">

    @include(backpack_view('inc.sidebar'))

    <main class="main pt-2">

       @yield('before_breadcrumbs_widgets')

       @includeWhen(isset($breadcrumbs), backpack_view('inc.breadcrumbs'))

       @yield('after_breadcrumbs_widgets')

       @yield('header')

        <div class="container-fluid animated fadeIn">

          @yield('before_content_widgets')
          {{-- <div class="setting-tab">
            <i class="la la-cog" aria-hidden="true" onclick="openSettingTab()" style="font-size: 30px;"></i>
          </div> --}}
          <div class="setting-tab-container">
            {{--<h5 class="text-center font-weight-bold">Theme colors</h5>
            <div class="mx-auto" id="color-span-container">
              <span class="badge" style="background-color: #32579F;" id="1b2a4e" onclick="setThemeColor('#32579F')"></span>
              <span class="badge" style="background-color: #f60808;" id="f60808" onclick="setThemeColor('#f60808')"></span>
              <span class="badge" style="background-color: #CF5A13;" id="CF5A13" onclick="setThemeColor('#CF5A13')"></span>
              <span class="badge" style="background-color: #0A6B52;" id="0A6B52" onclick="setThemeColor('#0A6B52')"></span>
              <span class="badge" style="background-color: #000000;" id="000000" onclick="setThemeColor('#000000')"></span>
            </div>
            <hr class="my-2">--}}
            <h5 class="text-center font-weight-bold">Language</h5>
            @php
              use App\Models\Ui;
              $ui = Ui::where('user_id',backpack_user()->id)->first();
              if($ui){
                $color = $ui->background;
                $lang = $ui->lang;
                if($lang=="en"){
                  $en=true;
                }else{
                  $en=false;
                }
              }else{
                $color = '#32579F';
                if(App::getLocale()=="en"){
                  $en=true;
                }else{
                  $en=false;
                }
              }
            @endphp
            <div class="d-flex justify-content-around">
              <div style="cursor: pointer">
                <a class="{{$en?'':'active_lang'}}" id="np_flag"><img style="max-width:25px;" src="{{asset('img/flags/np_flag.png')}}" alt="Nepali"></a>
              </div>
              <div style="max-width:40px; cursor: pointer">
                <a class="{{$en?'active_lang':''}}" id="us_flag"><img style="max-width:25px;" src="{{asset('img/flags/us_flag.png')}}" alt="English"></a>
              </div>
            </div>
          </div>
          <div class="setting-overlay" onclick="openSettingTab()">
          </div>
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

          <script>

            // function to open setting tab
            function openSettingTab(){
              let settingContainer = document.querySelector(".setting-tab-container");
              let settingOverlay = document.querySelector(".setting-overlay");
              settingContainer.classList.toggle("open");
              settingOverlay.classList.toggle("open");
            }
            function setThemeColor(color) {
              // remove active class
              const colorSpanContainer = document.getElementById('color-span-container');
              const colorSpans = colorSpanContainer.childNodes;
              colorSpans.forEach(child => {
                if(child.classList) {
                  if(child.classList.contains('active')) child.classList.remove('active')
                }
              });

              var header = {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                      };

             

              // ajax call to store color to database
              // $.ajax({
              //   url:'/admin/change-theme',
              //   type:'get',
              //   data:{
              //     colorCode:color
              //   },
              //   success: response => {
              //     // set active class and change theme
              //     $(`${color}`).addClass('active');
              //     $('.sidebar-pills, .app-header').css("background-color", color);
              //   }
              // });
            }
            // call function onload
            // let color = '<?php echo $color ?>';
            // $(`${color}`).addClass('active');
            // $('.sidebar-pills, .app-header').css("background-color", color);
            
            // language change on flag click();
            // $('#np_flag').on('click', function() {
            //     $.get("/admin/change_localization", {
            //             value: "np",
            //         },
            //         function(data, status) {
            //             location.reload();
            //         });
            // })

            // $('#us_flag').on('click', function() {
            //     $.get("/admin/change_localization", {
            //             value: "en",
            //         },
            //         function(data, status) {
            //             location.reload();
            //         });
            // })
        </script>
          @yield('content')
          
          @yield('after_content_widgets')

        </div>

    </main>

  </div><!-- ./app-body -->

  <footer class="{{ config('backpack.base.footer_class') }}">
    @include(backpack_view('inc.footer'))
  </footer>

  @yield('before_scripts')
  @stack('before_scripts')

  @include(backpack_view('inc.scripts'))

  @yield('after_scripts')
  @stack('after_scripts')
</body>
</html>