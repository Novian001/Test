<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Exception;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    use ResponseFormatter;

    public function index(Request $request){
        return $this->responseFormatterWithMeta($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], Post::select('id', 'title', 'slug', 'body', 'image', 'user_id', 'created_at')->orderBy('created_at', 'DESC')->cursorPaginate($request->input('limit', 15)));
    }

    public function store(CreatePostRequest $request){
        try{
            $user_id = Auth::id();
            if($request->hasFile('image')){
                $path = $request->file('image')->store('public/images');
            }

            $post = Post::create([
                'title' => $request->title,
                'slug' => $request->slug,
                'body' => $request->body,
                'image' => isset ($path) ? $path : null,
                'user_id' => $user_id
            ]);

            if(!$post){
                throw new Exception('Failed to create post');
            }

            return $this->responseFormatter($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], $post);

        }catch(Exception $e){
            return $this->responseFormatter($this->httpCode['StatusInternalServerError'], $this->httpMessage['StatusInternalServerError'], $e->getMessage());
        }
    }


    public function update(UpdatePostRequest $request, $id){
        try{
            $post = Post::findOrFail($id);
            $user_id = Auth::user()->id;
            if($request->hasFile('image')){
                $path = $request->file('image')->store('public/images');
            }

            $post->update([
                'title' => $request->title,
                'slug' => $request->slug,
                'body' => $request->body,
                'image' => isset ($path) ? $path : null,
                'user_id' => $user_id
            ]);

            if(!$post){
                throw new Exception('Failed to update post');
            }

            return $this->responseFormatter($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], $post);
        }catch(Exception $e){
            return $this->responseFormatter($this->httpCode['StatusInternalServerError'], $this->httpMessage['StatusInternalServerError'], $e->getMessage());
        }
    }

    public function destroy($id){
        try{
            $post = Post::findOrFail($id);
            $post->delete();

            if(!$post){
                throw new Exception('Failed to delete post');
            }

            return $this->responseFormatter($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], $post);
        }catch(Exception $e){
            return $this->responseFormatter($this->httpCode['StatusInternalServerError'], $this->httpMessage['StatusInternalServerError'], $e->getMessage());
        }
    }

    public function list(){
        return $this->responseFormatter($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], Post::select('id', 'title', 'slug', 'body', 'image', 'user_id', 'created_at')->orderBy('created_at', 'desc')->get());
    }

    public function show($id)
    {
        try {
            $post = Post::findOrFail($id);
            return $this->responseFormatter($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], $post);
        } catch(Exception $e){
            return $this->responseFormatter($this->httpCode['StatusInternalServerError'], $this->httpMessage['StatusInternalServerError'], $e->getMessage());
        }
    }

}
