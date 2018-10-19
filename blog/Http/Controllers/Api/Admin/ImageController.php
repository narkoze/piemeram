<?php

namespace Blog\Http\Controllers\Api\Admin;

use Intervention\Image\Facades\Image as Img;
use Illuminate\Support\Facades\Storage;
use Blog\Repositories\ImageRepository;
use Illuminate\Http\Request;
use Blog\Image;

class ImageController extends Controller
{
    protected $rules = [
        'name' => [
            'required',
            'max:255',
        ],
        'image' => 'image|max:5120',
    ];

    public function index(ImageRepository $imageRepo, Request $request)
    {
        $params = $request->all() + $imageRepo->params();

        $images = $imageRepo->images($params)->paginate(10);

        return response()->json(compact('images', 'params'));
    }

    public function store(Request $request)
    {
        $request->validate($this->rules);

        $image = new Image();
        $image->fill($request->all());
        $image->author()->associate($request->user());

        $file = $request->file('image');
        $image->filename = $file->store(null, 'public');
        $image->size = $file->getSize();

        $img = Img::make(storage_path("app/public/$image->filename"))->orientate();

        $exif = $img->exif();
        if ($exif) {
            $model = [];
            if (isset($exif['Make'])) {
                $model[] = $exif['Make'];
            }
            if (isset($exif['Model'])) {
                $model[] = $exif['Model'];
            }
            if ($model) {
                $image->model = implode(" ", $model);
            }
        }

        Storage::disk('public')->put(
            "blog/original/$image->filename",
            (string) $img->stream()
        );
        Storage::disk('public')->put(
            "blog/medium/$image->filename",
            (string) $img->fit(640, 360)->stream()
        );
        Storage::disk('public')->put(
            "blog/small/$image->filename",
            (string) $img->fit(100, 100)->stream()
        );
        $img->destroy();

        Storage::disk('public')->delete($image->filename);

        $imgSize = getimagesize(storage_path("app/public/blog/original/$image->filename"));
        $image->width = $imgSize[0];
        $image->height = $imgSize[1];

        $type = null;
        switch ($imgSize['mime']) {
            case "image/gif": $type = 'GIF'; break;
            case "image/jpeg": $type = 'JPEG'; break;
            case "image/png": $type = 'PNG'; break;
            case "image/bmp": $type = 'BMP'; break;
        }
        if ($type) {
            $image->type = $type;
        }
        $image->save();

        return response()->json($image->json());
    }

    public function update(Request $request, Image $image)
    {
        $request->validate($this->rules);

        $image->fill($request->all());
        $image->save();

        return response()->json($image->json());
    }

    public function destroy(Image $image)
    {
        $image->delete();

        Storage::disk('public')->delete("blog/original/$image->filename");
        Storage::disk('public')->delete("blog/medium/$image->filename");
        Storage::disk('public')->delete("blog/small/$image->filename");

        return response()->json();
    }
}
