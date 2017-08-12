<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use App\Models\Picture\Picture;
use App\User;
use App\Models\Company;
use App\Models\Inspector;
use App\Models\Citizen;
use App\Models\Authorization;
use File;
use Image;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Storage;




class ImgController extends Controller
{

   public function resize_image($img, $ext, $width, $height)
   {
      
        $img_resize = new Image();      
        //Paso 1: TAMAÑO. Si la imagen es muy grande se hace un resize de ancho y  alto segun parametros como maximo manteniendo su relacion de aspecto.        
        //$width      = 450;
        //$height     = 350;
        $img->resize($width, $height, function ($c) {
          $c->aspectRatio();
          $c->upsize();
        });
        //Paso 2: PESO. Una vez redimensionada si el archivo pesa mas de 500 Kb se baja la calidad al 90%
        if ($img->filesize()>500000)
        {
          $img = $img->encode($ext,95);
        } 
        
        $img_resize = $img;
        
        return $img_resize;
   }

    /*
     * Extracts picture's data from DB and makes an image 
    */ 
    public function showUserAvatar($id)
    {
        $user = User::findOrFail($id);
        $picture = Image::make($user->avatar);
        $response = Response::make($picture->encode('jpg'));
        $response->header('Content-Type', 'image/jpeg');

        return $response;
    }
    
    /*
     * Extracts picture's data from DB and makes an image 
    */ 
    public function showInspectorAvatar($id)
    {
        $inspector = Inspector::findOrFail($id);
        $picture = Image::make($inspector->avatar);
        $response = Response::make($picture->encode('jpg'));
        $response->header('Content-Type', 'image/jpeg');

        return $response;
    }

    /*
     * Extracts picture's data from DB and makes an image 
    */ 
    public function showAuthorizationAvatar($id)
    {
        $authorization = Authorization::findOrFail($id);
        $picture = Image::make($authorization->avatar);
        $response = Response::make($picture->encode('jpg'));
        $response->header('Content-Type', 'image/jpeg');

        return $response;
    }

    /*
     * Extracts picture's data from DB and makes an image 
    */ 
    public function showCitizenAvatar($id)
    {
        $citizen = Citizen::findOrFail($id);
        $picture = Image::make($citizen->avatar);
        $response = Response::make($picture->encode('jpg'));
        $response->header('Content-Type', 'image/jpeg');

        return $response;
    }

    /*
     * Extracts picture's data from DB and makes an image 
    */ 
    public function showCompanyLogo($id)
    {
        $company = Company::findOrFail($id);
        $picture = Image::make($company->logo);
        $response = Response::make($picture->encode('jpg'));
        $response->header('Content-Type', 'image/jpeg');

        return $response;
    }

}

?>