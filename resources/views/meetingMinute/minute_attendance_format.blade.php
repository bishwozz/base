<style>

    table#pdf_table {
        width: 100%;
        border-spacing: 0;
        table-layout: fixed;
        margin-bottom:25px;
        text-align: left;
        font-family:'Kalimati';
    }
    table#pdf_table tr{
        border-spacing: 0;
    }
    table#pdf_table th{
        border:1px solid black;
        border-spacing: 0;
        padding: 5px 5px;
        font-size:14px;
    }
    table#pdf_table tr td{
        font-size:14px;
        border:1px solid black;
        border-spacing: 0;
        padding: 5px 5px;
        font-family:'Kalimati';
    }
</style>
<p>
    आज मिति  {{$today}}  गतेको दिन यस मुख्यमन्त्री तथा मन्त्रिपरिषद्को कार्यालयमा माननीय मुख्यमन्त्री  श्री त्रिलोचन भट्ट को अध्यक्षतामा बैठक बसी तपसिल अनुसारको माननीय मन्त्रीज्यूहरुको उपस्थितिमा तपसिल अनुसारको निर्णयहरु गरियो |
</p>
<h5><u>उपस्थिति:</u></h5>
<table id="pdf_table">
    <colgroup width="9%"></colgroup>
    <colgroup width="27%"></colgroup>
    <colgroup width="13%"></colgroup>
    <colgroup width="15%"></colgroup>
    <colgroup width="42%"></colgroup>
    <tr>
        <th>क्र. सं.</th>
        <th>नाम, थर</th>
        <th>पद</th>
        <th>उपस्थिति</th>
        <th>कार्य विभाजन</th>
    </tr>
    @foreach($mps as $mp)
        <tr>
            <td>{{convertToNepaliNumber($loop->iteration)}}</td>
            <td>{{$mp['name']}}</td>
            <td>{{$mp['post']}}</td>
            <td>{{$mp['att_stat']}}</td>
            <td>{{$mp['ministry']}}</td>
        </tr>
    @endforeach
</table><br>