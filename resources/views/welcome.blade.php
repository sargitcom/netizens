<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Application</title>

        <link rel="stylesheet" type="text/css" href="{{ url('css/bootstrap.min.css') }}" />

    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <?php
                        var_dump($data);
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <h1>Aplikacja </h1>
                </div>
                <div class="col-md-12">
                    <form method="post" action="/download-images" class="form">
                        @csrf <!-- {{ csrf_field() }} -->
                        <div class="row">
                        <div class="col-md-8">
                            <select name="import-type" class="form form-control">
                                <option value="overwrite">nadpisanie już zaimportowanych danych zdjęć</option>
                                <option value="import-new">zaimportowanie tylko tych zdjęć które nie były wcześniej importowane</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Ściągnij zdjęcia</button>
                        </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul>
                        <li><a href="{{ url('/images-list') }}">Lista zdjęć</a></li>
                        <li><a href="{{ url('albums') }}">Albumy</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </body>
</html>
