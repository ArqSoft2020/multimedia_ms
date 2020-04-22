<?php

namespace App\Http\Controllers\API;

use App\Multimedia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class MultimediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Multimedia::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id, $type)
    {        
        if($request->has('mediafile') && in_array($type,["USER","PUBLICATION"])){
            $media_file = Multimedia::where('type_model_media',$type)->where('id_model_media',$id)->first();            
            if($media_file == null){
                $path_media = $this->request_file_base_64($request, 'mediafile', 'public/'.$type.'/'.$id.'/');
                $media_file = Multimedia::create(["id_model_media"=>$id,"type_model_media"=>$type,"path_media"=>$path_media]);                
                return response()->json($media_file);
            }
            Storage::delete($media_file->path_media);            
            $media_file->path_media = $this->request_file_base_64($request, 'mediafile', 'public/'.$type.'/'.$id.'/');
            $media_file->save();            
            return response()->json($media_file);
        }else
            return response()->json(["mediafile"=>"No ha incluido ningun archivo o Tipo de registro erroneo"], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $media_file = Multimedia::find($id);
        if($media_file == null)
            return response()->json(["mediafile"=>"No se encontro el recurso para ese id"], 400);
        return response()->json($media_file);
    }

    public function show_extended_register($id, $type){
        $media_file = Multimedia::where('type_model_media',$type)->where('id_model_media',$id)->first();
        if($media_file == null)
            return response()->json(["mediafile"=>"No se encontro el recurso para ese id y tipo"], 400);
        return response()->json($media_file);
    }

    public function show_extended_file($id, $type){
        $media_file = Multimedia::where('type_model_media',$type)->where('id_model_media',$id)->first();
        if($media_file == null)
            return response()->json(["mediafile"=>"No se encontro el recurso para ese id y tipo"], 400);
        return Storage::response($media_file->path_media);
    }    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $type)
    {
        if($request->has('mediafile') && in_array($type,["USER","PUBLICATION"])){
            $media_file = Multimedia::where('type_model_media',$type)->where('id_model_media',$id)->first();
            if($media_file == null){
                $path_media = $this->request_file_base_64($request, 'mediafile', 'public/'.$type.'/'.$id.'/');
                $media_file = Multimedia::create(["id_model_media"=>$id,"type_model_media"=>$type,"path_media"=>$path_media]);
                return response()->json($media_file);                
            }
            Storage::delete($media_file->path_media);            
            $media_file->path_media = $this->request_file_base_64($request, 'mediafile', 'public/'.$type.'/'.$id.'/');
            $media_file->save();           
            return response()->json($media_file);         
        }else
            return response()->json(["mediafile"=>"No ha incluido ningun archivo"], 500); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $type)
    {        
        $media_file = Multimedia::where('type_model_media',$type)->where('id_model_media',$id)->first();
        
        if($media_file == null)
            return response()->json(["mediafile"=>"Mediafile Ya Eliminado"], 200); 

        Storage::delete($media_file->path_media);            
        $media_file->delete();
        return response()->json($media_file, 200);     
    }


    public function request_file_base_64($request, $key, $path_store){
        $str_img = $request->input($key);      
        $str_img = str_replace('data:image/png;base64,', '', $str_img);
        $str_img = str_replace('data:image/jpeg;base64,', '', $str_img);
        $str_img = str_replace('data:image/jpg;base64,', '', $str_img);
        $str_img = str_replace('data:image/gif;base64,', '', $str_img);
        $str_img = str_replace(' ', '+', $str_img);        
        Storage::put('/public/foo.png',base64_decode($str_img));        

        $path = Storage::putFile($path_store,new File( storage_path('app/public/foo.png') ) );

        Storage::delete('public/foo.png');        

        return $path;       
    }
}
