<?php

namespace App\Http\Controllers;

use App\Events\NewPost;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth', ['except' => ['show', 'posts']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $posts = Post::orderBy('created_at', 'desc')->paginate(10);

      return view('posts.index')->withPosts($posts);
    }

    public function posts()
    {
      $posts = Post::with('user')->orderBy('created_at', 'desc')->paginate(10);

      return response()->json($posts);
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
    public function store(Request $request)
    {
      $this->validate($request, [
        'title' => 'required|max:255',
        'content' => 'required',
      ]);

      $user = Auth::user();
      $slug = Str::slug($request->title).'.'.uniqid();

      $post = $user->posts()->create([
        'title' => $request->title,
        'content' => $request->content,
        'slug' => $slug,
        'published' => $request->has('published')
      ]);

      $post = Post::where('id', $post->id)->with('user')->first();
      broadcast(new NewPost ($post))->toOthers();

      return redirect()->route('posts.show', $post->slug);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($idorslug)
    {
      $post = Post::where('slug', $idorslug)->orWhere('id', $idorslug)->firstorfail();
      return view('posts.show')->withPost($post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($idorslug)
    {
      $post = Post::where('slug', $idorslug)->orWhere('id', $idorslug)->firstorfail();
      return view('posts.edit')->withPost($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $this->validate($request, [
        'title' => 'required|max:255',
        'content' => 'required',
      ]);

      $post = Post::findOrFail($id);
      $post->title = $request->title;
      $post->content = $request->content;
      $post->published = ($request->has('published') ? true : false);
      $post->save();

      return redirect()->route('posts.show', $post->slug);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      Post::destroy($id);
    }
}
