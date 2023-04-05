<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Utsav Somaiya</title>
</head>
<body>
    <script>
        const products = {!! json_encode($products[0]['name']) !!}

        console.log(products);
    </script>
</body>
</html>
