<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet">
    <style>
        @font-face {
            font-family: 'Kalimati';
            font-style: normal;
            font-weight: normal;
            src: url({#asset /Kalimati.ttf @encoding=dataURI});
            format('truetype');
        }

        @media print {
            @page {
              size: A4 portrait;
              margin: 0;
              padding: 0;
              margin-top: 0.5in;
              font-size: 0.916em !important;
            }

            .content {
              margin: 0.5in 1in 0 1.5in;
            }

            .headercontent {
                margin: 0in 1in 0 1.5in;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            
            
            .content p{
              margin: 0px !important;

            }

            

        }

        body {
            margin: 0 !important;
            padding: 0 !important;
            font-family: 'Kalimati';
            font-size: 0.916em;
        }


        .text {
            display: flex;
            justify-content: center;
            margin-top: -5px !important;
            text-align: center;
            font-size: 0.916em !important;
        }

        .content {
            text-align: justify;
            justify-content: start;
        }


        .sachibname{
          margin-top:50px;
        }

        
/* 
        span{
          font-weight: bold;
        } */


        .font-size-18 {
            font-size: 17px;
            font-weight: bold;
            line-height:2.0rem;
        }
        .font-size-16 {
            font-size: 15px;
            font-weight: bold;
            line-height:1.5rem;
            margin-left:20%;
        }

        .head-body{
            text-align:center;
            width:80%;
            line-height:1.5;
            font-family:'Kalimati';
        }
        .img-left{
            height: 80px;
            width: 100px;
        }
        .letter_head{
            /* height:160px; */
            text-align: center !important;
            margin-left: -0.25in;

        }
        .body{
            font-family: Kalimati;
            text-align: justify;
        }
      
    </style>    
    </head>

    <body>
        <div class="container">
            <div class="headercontent">
                <img src="{#asset /live/image/govtLogo.png @encoding=dataURI}" class="img-left">
                <div class="letter_head">
                    <span  class="font-size-18">{{ isset($app_setting->letter_head_title_1) ? $app_setting->letter_head_title_1 : ''}}</span><br/>
                    <span  class="font-size-18">{{$agenda->ministry_entity->name_lc}}</span><br/>
                    <span  class="font-size-18">{{ isset($app_setting->letter_head_title_3) ? $app_setting->letter_head_title_3 : ''}}</span><br/>
                </div>
                <div style="width: 100px;"></div>
            </div>
            
            <div class="content">

                <p>
                  <div> <b>विषय:</b> {{ $agenda->agenda_title }}</div> 
                </p>
                <br>
            
                <p> प्रस्ताव पेश गर्न माननीय {{($agenda->ministry_id == 1) ? 'मुख्यमन्त्री': 'मन्त्री' }}बाट स्वीकृत प्राप्त मिति: {{$agenda->minister_approval_date_bs}}</p>

                <br>

                <p>
                    <b>१. विषयको संक्षिप्त व्यहोरा:</b>
                </p>

                <p> {{ $agenda->agenda_description }}</p>
                <br>

                <p>
                    <b>२. प्राप्त परामर्श तथा अन्य प्रासङ्गिक कुरा:</b>
                </p>
                <p> {{ $agenda->paramarsha_and_others }}</p>

                <br>
                <p>
                    <b>३. प्रस्ताव पेश गर्नु पर्नाको कारण र मन्त्रालयको सिफारिस:</b>
                </p>
                <p> {{ $agenda->agenda_reason_and_ministry_sipharis }}</p>
                <br>

                <p>
                    <b>४. निर्णय हुनुपर्ने व्यहोरा:</b>
                </p>
                <p> {{ $agenda->decision_reason }} 
                </p>

                <div class="sachibname">
                  <div style="display: flex;justify-content:space-between;">
                    <span>मितिः {{ $agenda->getLatestDateBs() }} </span>
                    <span>{{ $agenda->getMinistryApproverName() }} <br><div style="text-align: center">{{$agenda->getMinistryApproverDesignation()}}</div></span>
                  </div>
                </div>
            </div>
          </div>
  </body>
</html>
