<?php

namespace App\Http\Controllers\Post;

use Exception;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
   

    public function index(){

        $posts = Post::orderBy('id','desc')->get();

        return view("post.index", compact('posts'));
    }


    public function store(Request $request){
        /*
        * Nota: Se instalo:
        composer require --with-all-dependencies league/flysystem-aws-s3-v3 "^1.0"
        para poder hacer el registro en el bucket.
        Así lo indica la documentación de laravel 8:
        https://laravel.com/docs/8.x/filesystem#driver-prerequisites
        */
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'image' => 'required|mimes:jpg,png,jpeg|max:1024', // 1 MB
            
          ]);
          try{
            
                //Registrar Post
                $folder = "imagenes"; 

                $post = new Post;
                $post->name = $request->name;
                $post->description = $request->description;
                $image_path = Storage::disk('s3')->put($folder, $request->image, 'public');
                
                $post->image_path = $image_path;
                $post->save();

                return redirect()->route('posts.index')
                ->with('success','Post registrado correctamente!');
           
            }catch(\Exception $e){

                return redirect()->route('posts.index')
                ->with('error','No se pudo registrar el post. Error: '.$e->getMessage());
            }
          
    }

    public function destroy($id){

        try{
             $post = Post::findOrFail($id);
             Storage::disk('s3')->delete($post->image_path);
           
             $post->delete();

            return redirect()->route('posts.index')
            ->with('success','Post eliminado correctamente!');
       
        }catch(\Exception $e){

            return redirect()->route('posts.index')
            ->with('error','No se pudo eliminar el post. Error: '.$e->getMessage());
        }
      

    }


}
