<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        
    </head>
    <body class="font-sans antialiased">
        
    @foreach ($contenu as $contenu)
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{$contenu}}</h1>
            </div>
        </div>
    </div>
    @endforeach 
    <p>test</p>
    </body>
</html>
