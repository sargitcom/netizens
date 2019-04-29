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
            <h1>Albumy </h1>
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
                        <input readonly class="form form-control" type="text" name="title" value="<?= $data->getTitle(); ?>" placeholder="Wpisz tytuł..." />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" style="margin-top: 15px;">
                        <select name="photo-id" class="form form-control">
                        <?php
                            while ($pc['pc']->valid()) {
                                $photo = $pc['pc']->current();
                                ?>
                                <option value="<?= $photo->getId(); ?>"><?= $photo->getTitle(); ?></option>
                                <?php
                            $pc['pc']->next();
                            }
                        ?>
                        </select>
                       </div>
                </div>
                <div class="row">
                    <div class="col-md-12" style="margin-top: 15px;">
                        <button type="submit" class="btn btn-primary">Dodaj do albumu</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table" style="margin-top: 15px;">
                <thead>
                    <th>Id</th>
                    <th>Tytuł</th>
                </thead>
                <tbody>
                <?php
                    while ($photos['pc']->valid()) {
                        $photo = $photos['pc']->current();
                        ?>
                        <tr>
                            <td><?= $photo->getId(); ?></td>
                            <td><?= $photo->getTitle(); ?></td>
                        </tr>
                        <?php
                        $photos['pc']->next();
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
