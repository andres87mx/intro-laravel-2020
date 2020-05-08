<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Facades\Storage;

use App\Post;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::latest()->get();
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        //salvar
        $post = Post::create([
            'user_id' => auth()->user()->id
        ] + $request->all());

        //imagen
        if($request->file('file')){
            $post->image = $request->file('file')->store('posts','public');
            $post->save();
        }

        //retornar a vista anterior
        return back()->with('status', 'Creado con éxito');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, Post $post)
    {
        $post->update($request->all());
        //eliminar imagen
        if($request->file('file')){
            Storage::disk('public')->delete($post->image);
            $post->image = $request->file('file')->store('posts','public');
            $post->save();
        }

        return back()->with('status', 'Actualizado con éxito!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //eliminacion de la imagen
        Storage::disk('public')->delete($post->image);
        $post->delete();
        return back()->with('status', 'Eliminado con éxito');
    }
}
