<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait ImageUpload
{
    /**
     * Image Upload Trait
     * @param $query
     * @param $old
     * @return string
     */
    public function imageUploadTrait($query, $old = null): string // Taking input image as parameter
    {

        $allowExt = ['jpeg', 'png', 'jpg', 'gif', 'svg'];
        $ext = strtolower($query->getClientOriginalExtension());

        if ($query->getSize() > 5100000) {
            abort('406', 'max file size:5MB ');
        }

        if (! in_array($ext, $allowExt)) {
            abort('406', 'only allow : jpeg, png, jpg, gif, svg');
        }

        if ($old !== null) {
            $this->delete($old);
        }
        $image_name = Str::random(20);
        $image_full_name = $image_name.'.'.$ext;
        $upload_path = 'assets/images/';    //Creating Sub directory in Assets folder to put image
        $image_url = $upload_path.$image_full_name;
        $success = $query->move($upload_path, $image_full_name);

        return str_replace('assets/images/', '', $image_url); // Just return image
    }

    /**
     * Delete Image
     * @param $path
     * @return void
     */
    protected function delete($path)
    {
        if (file_exists('assets/images/'.$path)) {
            @unlink('assets/images/'.$path);
        }
    }
}
