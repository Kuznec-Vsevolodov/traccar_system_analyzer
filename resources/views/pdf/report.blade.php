<!DOCTYPE html>
<html>
<head>
    <title>Information report</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <div style="width: 100%; max-width: 960px; margin: auto">
        <table width="100%">
            <tr style="border-bottom: 1px solid #000000">
                <td><h2>Information</h2></td>
            </tr>
                <td colspan="2">
                    <table width="100%" cellpadding="0" cellspacing="0" border="1">
                        <thead>
                            <tr style="background-color: #eee">
                                <th style="text-align: left; padding: 5px 10px;">ID</th>
                                <th style="text-align: center; padding: 5px 10px;">Max Speed</strong></th>
                                <th style="text-align: center; padding: 5px 10px;">Brakes</th>
                                <th style="text-align: right; padding: 5px 10px;">Accelerations</th>
                                <th style="text-align: right; padding: 5px 10px;">Wide Turns</th>
                                <th style="text-align: right; padding: 5px 10px;">Student</th>
                                <th style="text-align: right; padding: 5px 10px;">Instructor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align: left; padding: 5px 10px;">{{ $lesson['id'] }}</td>
                                <td style="text-align: center; padding: 5px 10px;">{{ $lesson['max_speed'] }}</td>
                                <td style="text-align: center; padding: 5px 10px;">{{ $lesson['harsh_braking_count'] }}</td>
                                <td style="text-align: right; padding: 5px 10px;">{{ $lesson['rapid_acceleration_count'] }}</td>
                                <td style="text-align: right; padding: 5px 10px;">{{ $lesson['wide_turn_count'] }}</td>
                                <td style="text-align: right; padding: 5px 10px;">{{ $student['first_name'] }} {{ $student['last_name'] }}</td>
                                <td style="text-align: right; padding: 5px 10px;">{{ $instructor['first_name'] }} {{ $instructor['last_name'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
        <p>Brakes coordinates</p>
            <table width="100%" cellpadding="0" cellspacing="0" border="1">
                <thead>
                    <tr style="background-color: #eee">
                        <th style="text-align: left; padding: 5px 10px;">Longitude</th>
                        <th style="text-align: center; padding: 5px 10px;">Latitude</strong></th>
                     </tr>
                </thead>
                <tbody>
                    @foreach ($brakes as $brake)
                        <tr>
                            <td style="text-align: left; padding: 5px 10px;">{{ $brake['longitude'] }}</td>
                            <td style="text-align: center; padding: 5px 10px;">{{ $brake['latitude'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        <p>Accelerations coordinates</p>
            <table width="100%" cellpadding="0" cellspacing="0" border="1">
                <thead>
                    <tr style="background-color: #eee">
                        <th style="text-align: left; padding: 5px 10px;">Longitude</th>
                        <th style="text-align: center; padding: 5px 10px;">Latitude</strong></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($accelerations as $acceleration)
                        <tr>
                            <td style="text-align: left; padding: 5px 10px;">{{ $acceleration['longitude'] }}</td>
                            <td style="text-align: center; padding: 5px 10px;">{{ $acceleration['latitude'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        <p>Turns coordinates</p>
            <table width="100%" cellpadding="0" cellspacing="0" border="1">
                <thead>
                    <tr style="background-color: #eee">
                        <th style="text-align: left; padding: 5px 10px;">Longitude</th>
                        <th style="text-align: center; padding: 5px 10px;">Latitude</strong></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($turns as $turn)
                        <tr>
                            <td style="text-align: left; padding: 5px 10px;">{{ $turn['longitude'] }}</td>
                            <td style="text-align: center; padding: 5px 10px;">{{ $turn['latitude'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
    </div>
    
</body>
</html>