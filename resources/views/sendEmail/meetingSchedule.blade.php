<p>
    मन्त्रिपरिषदको बैठक यहि मिति {{$meeting_date_bs}} मा बस्ने निर्णय भएकोले सम्पूर्ण माननीय मन्त्रीज्युहरुलाई उपस्थितिको लागि सादर अनुरोध छ| <br>
    स्थान: मुख्यमन्त्री तथा मन्त्रिपरिषद्को कार्यालय <br>
    समय: {{ $meeting_start_time }}<br>
    प्रस्तावहरु:
    <ul>
        @foreach($meeting_agendas as $agenda)
            <li>{{$agenda->agenda_code}}&emsp;{{$agenda->agenda_title}}</li>
        @endforeach
    </ul>
</p>