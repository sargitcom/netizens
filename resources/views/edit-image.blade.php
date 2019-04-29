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
            <form method="post" action="">
                @csrf

                <div class="row">
                    <div class="col-md-12">
                        <label>Tytuł</label>
                        <input class="form form-control" type="text" name="title" value="<?= $data->getTitle(); ?>" placeholder="Wpisz tytuł..." />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Opis</label>
                        <textarea class="form form-control" name="description" placeholder="Wpisz opis...">
                            <?= $data->getDescription(); ?>
                        </textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Autor</label>
                        <input class="form form-control" type="text" name="author" value="<?= $data->getAuthor(); ?>" placeholder="Wpisz autora..." />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" style="margin-top: 15px;">
                        <button type="submit" class="btn btn-primary">Zaktualizuj</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
