<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #318c07;
            padding: 8px;
        }

        th {
            background: #318c07;
            color: white;
            text-align: left;
        }
    </style>
</head>

<body>

    <h2 style="color:#318c07;">Deal Summary</h2>
    <p><b>Customer Name:</b> {{ $data['Name'] }}</p>
    <p><b>Email:</b> {{ $data['Email'] }}</p>
    <p><b>Mobile:</b> {{ $data['Mobile'] }}</p>

    <table>
        <tr>
            <th>Item</th>
            <th>Details</th>
        </tr>
        @foreach ($data['DealData'] as $key => $value)
            @if ($key == 'inquiry_id')
                @continue {{-- Skip this row --}}
            @endif
            <tr>
                <td>{{ $key }}</td>
                <td>{{ $value }}</td>
            </tr>
        @endforeach
    </table>

</body>

</html>
