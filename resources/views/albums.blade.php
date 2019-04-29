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
                    <h1>Obrazki </h1>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul>
                        <li><a href="{{ url('/') }}">Strona główna</a></li>
                        <li><a href="{{ url('/images-list') }}">Lista zdjęć</a></li>
                        <li><a href="{{ url('albums') }}">Albumy</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                            <th>Id</th>
                            <th>Tytuł</th>
                            <th>Akcja</th>
                        </thead>
                        <tbody>
                            <?php
                                while ($ac->valid()) {
                                    $album = $ac->current();
                                    ?>
                                    <tr>
                                        <td><?= $album->getId(); ?></td>
                                        <td><?= $album->getTitle(); ?></td>
                                        <td><a href="{{ url('/album/edit/' . $album->getId() ) }}">Edytuj</a></td>
                                    </tr>
                                    <?php
                                    $ac->next();
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
