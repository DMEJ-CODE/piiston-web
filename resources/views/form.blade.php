<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form action="/result" method="POST">
        @csrf
        <input type="text" name="name" id="">
        <button type="submit">submite</button>
        <p>email , nom , message, ont click sur message ca evoi avec success et son nom</p>
    </form>
</body>
</html>