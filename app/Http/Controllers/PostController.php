<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function fetchAll()
    {
        $posts = Post::all();
        $output = '';
        if ($posts->count() > 0) {
            $output .= '<table class="table table-striped table-sm text-center align-middle">
            <thead>
              <tr>
                <th>ID</th>
                <th>Avatar</th>
                <th>Name</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>';
            foreach ($posts as $post) {
                $output .= '<tr>
                <td>' . $post->id . '</td>
                <td><img src="' . Storage::url($post->image) . '" width="50" class="img-thumbnail rounded-circle"></td>
                <td>' . $post->title . '</td>
                <td>
                  <a href="#" id="' . $post->id . '" class="text-success mx-1 editIcon" data-bs-toggle="modal" data-bs-target="#editTitle"><i class="bi-pencil-square h4"></i></a>

                  <a href="#" id="' . $post->id . '" class="text-danger mx-1 deleteIcon"><i class="bi-trash h4"></i></a>
                </td>
              </tr>';
            }
            $output .= '</tbody></table>';
            echo $output;
        } else {
            echo '<h1 class="text-center text-secondary my-5">No record present in the database!</h1>';
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'image' => 'required'
        ]);


        if ($validator->fails()) {
            return response()->json(['status' => 0,  'error' => $validator->errors()->toArray()]);
        } else {
            $post = new Post($validator->validated());
            if ($request->hasFile('image')) {
                $image = $request->file('image')->store('images');
                $post->image = $image;
                if ($image) {
                    // $post->setAttribute('image', $image);
                }
            }
            $post->save();
            return response()->json([
                'status' => 200,
                'post' => $post
            ]);
        }
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $post = Post::find($id);
        return response()->json([
            'post' => $post
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);


        if ($validator->fails()) {
            return response()->json(['status' => 0,  'error' => $validator->errors()->toArray()]);
        } else {
            $post = Post::find($request->e_id);
            // dd($post);
            if ($request->hasFile('image')) {
                Storage::delete($post->image);
                $image = $request->file('image')->store('images');
                $post->image = $image;
                // if ($image) {
                // Storage::delete($image);
                // $post->setAttribute('image', $image);
                // }
            } else {
                $post->image = $request->image_id;
            }
            $post->update($validator->validated());
            return response()->json([
                'status' => 200,
                'post' => $post
            ]);
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $post = Post::find($id);
        if ($post) {
            Storage::delete($post->image);
            $post->delete();
        }
    }
}
