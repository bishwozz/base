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
            size: A4 landscape;
            margin-top: 10px;
            margin-left: 40px;
            margin-right: 40px;
        }
    }
    .header-mid {
        text-align: center;
        line-height:1.7em;
        font-family: Kalimati;
    }
    .row {
        display: flex;
        flex-wrap: wrap;
    }

    .col-small {
        flex-basis: 10%;
    }
    .col-big {
        flex-basis: 70%;
    }
    .col-small-one {
        flex-basis: 10%;
    }
    .main-header{
        margin-left: 5em;
        padding-bottom:1em;
    }
</style>
<div class="container-fluid main-header">
    <div class="row">
        <div class="col col-small">
            <img src="{#asset /live/image/govt-logo-latest.png @encoding=dataURI}" style="height:90px;width:90px;" />
        </div>
        <div class="col col-big header-mid">
            <span style="font-size:20px;"> लुम्बिनी प्रदेश सरकार </span><br>
            <span style="font-size:25px;"> मुख्यमन्त्री तथा मन्त्रिपरिषदको कार्यालय </span><br>
            <span style="font-size:20px;"> राप्ती उपत्यका (देउखुरी), नेपाल </span><br>
            <span style="font-size:20px;"> (शासकीय सुधार तथा समन्वय महाशाखा) </span><br>
        </div>
        <div class="col col-small-one">
        </div>
    </div>
</div>
