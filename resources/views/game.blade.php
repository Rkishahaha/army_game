<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>army game</title>
</head>
<body style="display: flex; align-items:center; justify-content:center">
    <form style="margin-top: 10%;" method="POST" action="{{ route('game-start') }}">
        @csrf
        <div>
            <h3>Army 1 number of soldiers</h3>
            <input required name="soldiers1" type="number" min="0">

            <h3>Army 1 number of tanks</h3>
            <input required name="tanks1" type="number" min="0">    
        </div>
        <br>
        <br>
        <div>
            <h3>Army 2 number of soldiers</h3>
            <input required name="soldiers2" type="number" min="0">  
            
            <h3>Army 2 number of tanks</h3>
            <input required name="tanks2" type="number" min="0">          
        </div>
        <br>

        <button type="submit"> Start game </button>
    </form>
</body>
</html>