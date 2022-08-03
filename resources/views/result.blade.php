<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>army game</title>
</head>
<body style="display: flex; align-items:center; justify-content:center">
    <div>

        <h2>{{ $log->firstArmy->name }} VS {{ $log->secondArmy->name }}</h2>

        <h3>The winner is: {{ $log->victor->name }}</h3>
        {!! str_replace(['"'], "", $log->battle_log) !!}
    </div>
</body>
</html>