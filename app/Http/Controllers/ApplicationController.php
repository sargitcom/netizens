<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidDownloadTypeException;
use App\Http\Entities\PhotoEntity;
use App\Http\Collections\PhotosCollection;
use App\Http\Models\ApplicationModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    /**
     * @var ApplicationModel
     */
    protected $applicationModel;

    public function __construct()
    {
        $this->applicationModel = new ApplicationModel();
    }

    public function index(Request $request)
    {
        $data = [
            'info' => $request->session()->flash('info')
        ];

        return View('welcome', ['data' => $data]);
    }

    public function listImages(Request $request, int $page = 1)
    {
        $pc = $this->applicationModel->getPhotosByPage($page);

        return View('images', ['data' => $pc, 'page' => $page]);
    }

    public function editImage(Request $request, int $id)
    {
        $image = null;

        try {
            $image = $this->applicationModel->getPhotoById($id);
        } catch (\Exception $e) {
            return redirect('/');
        }

        if ($request->isMethod('post')) {
            $title = $request->post('title');
            $description = $request->post('description');
            $author = $request->post('author');

            $this->applicationModel->updatePhotoById($id, $title, $description, $author);

            redirect('/photo/edit/' . $id);
        }

        return View('edit-image', ['data' => $image]);
    }

    public function downloadImages(Request $request)
    {
        $downloadType = $request->post('import-type');

        if (! $this->isPropperDownloadType($downloadType)) {
            throw new InvalidDownloadTypeException();
        }

        $images = $this->getImages("https://jsonplaceholder.typicode.com/photos");

        if (!$images->getTotal()) {
            $request->session()->flash('info', 'Nie ściągnięto obrazków!');
            return redirect('/');
        }

        $albumIds = $this->applicationModel->getPhotosIds();

        switch($downloadType) {
            case 'overwrite':
                $this->overwriteImages($albumIds, $images);
                break;

            case 'import-new':
                $this->importNewImages($albumIds, $images);
                break;
        }

        $request->session()->flash('info', 'Zakońzczono import obrazków!');
        return redirect('/');
    }

    protected function isPropperDownloadType($downloadType)
    {
        return $downloadType == 'overwrite' || $downloadType == 'import-new';
    }

    protected function getImages(string $url) : PhotosCollection
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1) ;

        $result = curl_exec($ch) ;

        curl_close($ch);

        $photosCollecton = new PhotosCollection();

        $jsonDecoded = json_decode($result);

        if (!$jsonDecoded) {
            return $photosCollecton;
        }

        $total = count($jsonDecoded);

        foreach ($jsonDecoded as $image) {
            $imageEntity = \App\Http\Transformers\JsonImageToPhotoEntityTransformer::transformToEntity($image);

            $photosCollecton->append($imageEntity);
        }

        $photosCollecton->setTotal($total);

        $photosCollecton->rewind();

        return $photosCollecton;
    }

    protected function overwriteImages(array $albumIds, PhotosCollection $images)
    {
        $images2Insert = new PhotosCollection();

        $total = 0;

        while ($images->valid()) {
            $image = $images->current();

            if (in_array($image->getId(), $albumIds)) {
                $images2Insert->append($image);
                $total++;
            }

            $images->next();
        }

        $images2Insert->setTotal($total);
        $images2Insert->rewind();

        $this->applicationModel->updatePhoto($images2Insert);
        $images2Insert->rewind();

        $this->applicationModel->insertAlbums($images2Insert);
        $images2Insert->rewind();

        $this->applicationModel->insertImage2AlbumLink($images2Insert);
    }

    protected function importNewImages(array $albumIds, PhotosCollection $images)
    {
        $images2Insert = new PhotosCollection();

        $total = 0;

        while ($images->valid()) {
            $image = $images->current();

            if (!in_array($image->getId(), $albumIds)) {
                $images2Insert->append($image);
                $total++;
            }

            $images->next();
        }

        $images2Insert->setTotal($total);
        $images2Insert->rewind();

        $this->applicationModel->insertPhoto($images2Insert);
        $images2Insert->rewind();

        $this->applicationModel->insertAlbums($images2Insert);
        $images2Insert->rewind();

        $this->applicationModel->insertImage2AlbumLink($images2Insert);
    }
}
