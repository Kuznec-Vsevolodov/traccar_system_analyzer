<!DOCTYPE html>
<html>
<head>
    <title>Information report</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Rubik:ital@1&display=swap');
        .information_text{
            font-size: 20px;
            font-family: 'Rubik', sans-serif;
            font-weight: bold;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div style="width:1000px;">
        <div style="background-image: url({{ asset('/images/first_page.png') }}); width: 100%; height: 900px; background-repeat: no-repeat; margin-top: 70px; margin-left: 50px">
            <p class="information_text" style="position: absolute; margin-top: 760px; margin-left: 180px;">{{$fun_fact[0]}}</p>
        </div>
        <div style="background-image: url({{ asset('/images/last_page.png') }}); width: 100%; height: 1200px; background-repeat: no-repeat; margin-top: 40px; margin-left: 40px">

            <img style="position: absolute; margin-left: 67px; margin-top: 157px; z-index: 1000;" src="https://maps.googleapis.com/maps/api/staticmap?size=505x350&path=color:0xff0000ff|weight:5|{{$final_str}}&key=AIzaSyAC9RuigAHsx7QDLc-t41UhtGBCkoyrxDE" alt="">
            <p class="information_text" style="position: absolute; margin-left: 280px; margin-top: 600px; color: #ffffff">{{ $instructor['first_name'] }} {{ $instructor['last_name'] }}</p>
            <p class="information_text" style="position: absolute; margin-left: 120px; margin-top: 709px;">{{ $lesson['database_trip_id'] }}</p>
            <P class="information_text" style="position: absolute; margin-left: 190px; margin-top: 759px;">{{ date("H.i", strtotime($lesson['lesson_start'])) }} - {{ date("H.i", strtotime($lesson['lesson_end'])) }}</P>
            <p class="information_text" style="position: absolute; margin-left: 240px; margin-top: 817px;">{{ $distance }}m</p>

        </div>
        
    </div>
    
</body>
</html>