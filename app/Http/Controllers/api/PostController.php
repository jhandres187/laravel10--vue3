<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PutRequest;
use App\Http\Requests\Post\StoreRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {
        $allPost = Post::with('category')->paginate(3);
        return response()->json($allPost, 200);
    }

    public function all(): JsonResponse
    {
        // if(Cache::has('post_all')){
            // return response()->json(Cache::get('post_all'));
        // }else{
            $allPosts = Post::with('category')->get();
            // Cache::put('post_all', $allPosts);
            return response()->json($allPosts, 200);
        // }
    }

    // public function slug($slug)
    // {
    //     // $post = Post::where("slug", $slug)
    //     // ->firstOrFail();
    //     // $post->category;
    //     $post = Post::with("category")
    //     ->where("slug", $slug)
    //     ->firstOrFail();
    //     return response()->json($post, 200);
    // }
    public function slug(Post $post): JsonResponse
    {
        $post->category;
        return response()->json($post, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request): JsonResponse
    {
        return response()->json(Post::create($request->validated()));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post): JsonResponse
    {
        return response()->json($post, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(PutRequest $request, Post $post): JsonResponse
    {
        $post->update($request->validated());
        return response()->json($post);
    }

    public function upload(Request $request, Post $post): JsonResponse
    {
        $request->validate([
            'image' => 'required|mimes:jpeg,jpg,png|max:10240'
        ]);
        Storage::disk("public_upload")->delete("image/otro/".$post->image);
        // if(isset($data["image"])){
            $fileName = time().'.'.$request["image"]->extension();
            $data["image"]=$fileName;
            $request->image->move(public_path("image/otro"), $fileName);
        // }
        $post->update($data);
        return response()->json($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post): JsonResponse
    {
        $post->delete();
        return response()->json("Post Eliminado correctamente");
    }
}
