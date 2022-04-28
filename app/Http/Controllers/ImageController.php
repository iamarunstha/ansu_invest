<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{

    public $sizes = [20, 320, 480, 600, 768, 900, 1024, 1200];
    public $thumbnail_size = 160;
    public $storage_path = '';

    public $storage_paths = [
        'listings'  =>  'listings',
        'user'  =>  'user'
    ];

    public function __construct() {
        $this->storage_path = storage_path().'/app';
        foreach($this->storage_paths as $index => $s)
        {
            $this->storage_paths[$index] = storage_path().'/app/'.$s;
        }
    }

    public function rotateImageOfSizes($filename, $asset_type, $clockwise_value)
    {
        $this->rotateImage($this->storage_paths[$asset_type].'/original/'.$filename, $clockwise_value);
        $this->rotateImage($this->storage_paths[$asset_type].'/thumbnail-images/'.$filename, $clockwise_value);
        
        foreach($this->sizes as $s)
        {
            $this->rotateImage($this->storage_paths[$asset_type].'/'.$s.'/'.$filename, $clockwise_value);
        }
    }

    public function rotateImage($image_path, $clockwise_value)
    {
        $thumbnail_image = \Image::make($image_path);
        $thumbnail_image->rotate(-90 * $clockwise_value);
        $thumbnail_image->save($image_path);
        //$thumbnail_image->rotate(-90 * $clockwise_value);
    }
    
    public function getImageOfAllSizes($asset_type, $filename, $upto_size=NULL, $cancel_cache=false)
    {
        $return = [];
        $cancel_cache = $cancel_cache ? '?v='.str_random(3) : '';
        $return[] = route('get-image-asset-type-filename', \App\Http\Controllers\HelperController::changeNullToX([$asset_type, $filename, 'thumbnail-images'])).$cancel_cache.' '.$this->thumbnail_size.'w';
        foreach($this->sizes as $s)
        {

            if(!is_null($upto_size))
            {
                if($s <= $upto_size)
                {
                    $return[] = route('get-image-asset-type-filename', \App\Http\Controllers\HelperController::changeNullToX([$asset_type, $filename, $s])).$cancel_cache.' '.$s.'w';    
                }
            }
            else
            {
                $return[] = route('get-image-asset-type-filename', \App\Http\Controllers\HelperController::changeNullToX([$asset_type, $filename, $s])).$cancel_cache.' '.$s.'w';    
            }
        }

        return implode(' ,', $return);
    }

    public function uploadAPI($image_object, $upload_directory, $asset_type, $set_sizes = true)
    {
        
        try
        {
            $image_object->store($upload_directory);
            $filename = $image_object->hashName();
            $response['status'] = true;
            $response['message'] = '';
            $response['filename'] = $filename;    
            $response['url'] = route('get-image-asset-type-filename', [$asset_type, $filename]); 

            if($set_sizes) {
                $thumbnail_image = \Image::make($image_object)->orientate()/*->resize(160, 160)*/->encode('jpg', 60);
                $image_height = $thumbnail_image->height();
                $image_width = $thumbnail_image->width();

                if(!\File::isDirectory($this->storage_path.'/'.$upload_directory.'/thumbnail-images')){
                    \File::makeDirectory($this->storage_path.'/'.$upload_directory.'/thumbnail-images', $mode = 0777, true, true);
                }
                
                $ratio = ($image_width / $this->thumbnail_size);
                $thumbnail_image->resize((int) ($image_width/$ratio), (int) ($image_height/$ratio));
                

                $thumbnail_image->save($this->storage_path.'/'.$upload_directory.'/thumbnail-images'.'/'.$filename);   
                foreach($this->sizes as $s)
                {
                    $thumbnail_image = \Image::make($image_object)->orientate()/*->resize(160, 160)*/->encode('jpg', 60);
                    $image_height = $thumbnail_image->height();
                    $image_width = $thumbnail_image->width();

                    $ratio = ($image_width / $s);
                    $thumbnail_image->resize((int) ($image_width/$ratio), (int) ($image_height/$ratio));
                    
                    if(!\File::isDirectory($this->storage_path.'/'.$upload_directory.'/'.$s)){
                        \File::makeDirectory($this->storage_path.'/'.$upload_directory.'/'.$s, $mode = 0777, true, true);
                    }
                    $thumbnail_image->save($this->storage_path.'/'.$upload_directory.'/'.$s.'/'.$filename);
                }    
            }
        }
        catch(\Exception $e)
        {
            $response['status'] = false;
            $response['message'] = $e->getMessage();
            $response['filename'] = NULL;
            $response['url'] = NULL;
        }

        return $response;
    }

    private function getAssetHandlers($asset_type, $filename=NULL, $size=NULL)
    {
        switch($asset_type)
        {
            case 'brand':
                $path = base_path().'/storage/app/images/'.$filename;
                break;

            case 'product':
                $path = base_path().'/storage/app/images/'.$filename;
                break;

            case 'blog':
                $path = !is_null($size) ? base_path().'/storage/app/blog/'.$size.'/'.$filename : base_path().'/storage/app/blog/original/'.$filename;
                break;

            case 'logo':
                $path = !is_null($size) ? base_path().'/storage/app/logo/'.$size.'/'.$filename : base_path().'/storage/app/logo/original/'.$filename;
                break;

            case 'blogs':
                $path = !is_null($size) ? base_path().'/storage/app/blogs/'.$size.'/'.$filename : base_path().'/storage/app/blogs/original/'.$filename;
                break;

            case 'user':
                $path = !is_null($size) ? base_path().'/storage/app/user/'.$size.'/'.$filename : base_path().'/storage/app/user/original/'.$filename;
                break;

            case 'slider':
                $path = !is_null($size) ? base_path().'/storage/app/slider/'.$size.'/'.$filename : base_path().'/storage/app/slider/original/'.$filename;
                break;
            case 'listings':
                $path = !is_null($size) ? base_path().'/storage/app/listings/'.$size.'/'.$filename : base_path().'/storage/app/listings/original/'.$filename;
                break;
            case 'gallery':
                $path = !is_null($size) ? base_path().'/storage/app/gallery/'.$size.'/'.$filename : base_path().'/storage/app/gallery/original/'.$filename;
                break;
            case 'images':
                $path = !is_null($size) ? base_path().'/storage/app/images/'.$size.'/'.$filename : base_path().'/storage/app/images/original/'.$filename;
                break;

            case 'testimonials':
                $path = base_path().'/storage/app/images/'.$filename;
                break;

            case 'block':
                $path = base_path().'/storage/app/images/'.$filename;
                break;

            case $asset_type :
                $path = base_path().'/storage/app/'.$asset_type.'/'.$filename;
                break;

            default:
                $path = base_path().'/storage/app/'.$asset_type.'/original/'.$filename;
                break;

        }

        if(\File::exists($path) && is_file($path) && !is_null($filename))
        {
            
            $handler = new \Symfony\Component\HttpFoundation\File\File($path);    
        }
        else
        {

            
            if($asset_type == 'blog')
            {
                $handler = new \Symfony\Component\HttpFoundation\File\File(base_path().''.DS.'storage'.DS.'app'.DS.'images'.DS.'no-images'.DS.'blog-no-image.png');
                $path = base_path().''.DS.'storage'.DS.'app'.DS.'images'.DS.'no-images'.DS.'blog-no-image.png';    
            }
            elseif($asset_type == 'blogs')
            {
                $handler = new \Symfony\Component\HttpFoundation\File\File(base_path().''.DS.'storage'.DS.'app'.DS.'images'.DS.'no-images'.DS.'listing-no-image.png');
                $path = base_path().''.DS.'storage'.DS.'app'.DS.'images'.DS.'no-images'.DS.'listing-no-image.png';    
            }
            elseif($asset_type == 'user')
            {
                $handler = new \Symfony\Component\HttpFoundation\File\File(base_path().''.DS.'storage'.DS.'app'.DS.'images'.DS.'no-images'.DS.'listing-no-image.png');
                $path = base_path().''.DS.'storage'.DS.'app'.DS.'images'.DS.'no-images'.DS.'listing-no-image.png';    
            }
            elseif($asset_type == 'logo')
            {
                $handler = new \Symfony\Component\HttpFoundation\File\File(base_path().''.DS.'storage'.DS.'app'.DS.'images'.DS.'no-images'.DS.'logo-no-image.png');
                $path = base_path().''.DS.'storage'.DS.'app'.DS.'images'.DS.'no-images'.DS.'logo-no-image.png';    
            }
            elseif($asset_type == 'listings')
            {
                $handler = new \Symfony\Component\HttpFoundation\File\File(base_path().''.DS.'storage'.DS.'app'.DS.'images'.DS.'no-images'.DS.'listing-no-image.png');
                $path = base_path().''.DS.'storage'.DS.'app'.DS.'images'.DS.'no-images'.DS.'listing-no-image.png';    
            }
            elseif($asset_type == 'gallery')
            {
                $handler = new \Symfony\Component\HttpFoundation\File\File(base_path().''.DS.'storage'.DS.'app'.DS.'images'.DS.'no-images'.DS.'listing-no-image.png');
                $path = base_path().''.DS.'storage'.DS.'app'.DS.'images'.DS.'no-images'.DS.'listing-no-image.png';    
            }
            elseif($asset_type == 'slider')
            {
                $handler = new \Symfony\Component\HttpFoundation\File\File(base_path().''.DS.'storage'.DS.'app'.DS.'images'.DS.'no-images'.DS.'listing-no-image.png');
                $path = base_path().''.DS.'storage'.DS.'app'.DS.'images'.DS.'no-images'.DS.'listing-no-image.png';    
            }
            elseif($asset_type == 'images')
            {
                $handler = new \Symfony\Component\HttpFoundation\File\File(base_path().''.DS.'storage'.DS.'app'.DS.'images'.DS.'no-images'.DS.'listing-no-image.png');
                $path = base_path().''.DS.'storage'.DS.'app'.DS.'images'.DS.'no-images'.DS.'no-img-product-brand.png';    
            }
            elseif($asset_type == 'testimonials')
            {
                $handler = new \Symfony\Component\HttpFoundation\File\File(base_path().''.DS.'storage'.DS.'app'.DS.'images'.DS.'no-images'.DS.'user-no-mg.png');
                $path = base_path().''.DS.'storage'.DS.'app'.DS.'images'.DS.'no-images'.DS.'user-no-img.png';    
            }
            else
            {
                $handler = new \Symfony\Component\HttpFoundation\File\File(public_path().''.DS.'frontend'.DS.'images'.DS.'logo.png');
                $path = public_path().''.DS.'frontend'.DS.'images'.DS.'logo.png';    
            }
            
        }

        return ['path' => $path, 'handler' => $handler];
    }

    public function getAsset($asset_type, $filename=NULL, $size=NULL)
    {
        $data = $this->getAssetHandlers($asset_type, $filename, $size);


        $path= $data['path'];
        $handler = $data['handler'];

        $lifetime = 31556926; //'.DS.'/ One year in seconds

        /**
        * Prepare some header variables
        */
        $file_time = $handler->getMTime(); // Get the last modified time for the file (Unix timestamp)

        $header_content_type = $handler->getMimeType();
        $header_content_length = $handler->getSize();
        $header_etag = md5($file_time . $path);
        $header_last_modified = gmdate('r', $file_time);
        $header_expires = gmdate('r', $file_time + $lifetime);

        
        $headers = array(
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Last-Modified' => $header_last_modified,
            'Cache-Control' => 'must-revalidate',
            'Expires' => $header_expires,
            'Pragma' => 'public',
            'Etag' => $header_etag
        );

        /**
        * Is the resource cached?
        */

        $h1 = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $_SERVER['HTTP_IF_MODIFIED_SINCE'] == $header_last_modified;
        $h2 = isset($_SERVER['HTTP_IF_NONE_MATCH']) && str_replace('"', '', stripslashes($_SERVER['HTTP_IF_NONE_MATCH'])) == $header_etag;

        if ($h1 || $h2) {
            return \Response::make('', 304, $headers); // File (image) is cached by the browser, so we don't have to send it again
        }


        $headers = array_merge($headers, array(
            'Content-Type' => $header_content_type,
            'Content-Length' => $header_content_length
        ));

        
        return \Response::make(file_get_contents($path), 200, $headers);
    }
}
