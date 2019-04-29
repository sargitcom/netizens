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
                                while ($data['pc']->valid()) {
                                    $photo = $data['pc']->current();
                                    ?>
                                    <tr>
                                        <td><?= $photo->getId(); ?></td>
                                        <td><?= $photo->getTitle(); ?></td>
                                        <td><a href="{{ url('/photo/edit/' . $photo->getId() ) }}">Edytuj</a></td>
                                    </tr>
                                    <?php
                                    $data['pc']->next();
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
