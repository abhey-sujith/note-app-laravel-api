<?php

namespace App\Http\Controllers;

use App\Models\Notes;
use App\Http\Requests\NoteRequest;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Http\Request;

class NotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Notes::where('user_id','=',$request->user()->id)->paginate(15);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $note = Notes::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'description' => $request->description
        ]);

        return response([
           'note' => $note
        ],201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(NoteRequest $request,$id)
    {
        $data = $request->only('title', 'description');
         $note = Notes::where('id', '=',$id)->where('user_id', '=',$request->user()->id)->get();
         if($note->isEmpty()){
         return response(['message' => 'note not found'], 400);

         }
         $note = Notes::where('id', '=',$id)->where('user_id', '=',$request->user()->id);
         $note ->update($data);
         return response(['message' => 'note has been updated'], 200);

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notes  $notes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $note = Notes::where('id', '=',$id)->where('user_id', '=',$request->user()->id);
        $note->delete();
        return response(['message' => 'This request has been deleted'], 200);
    }
}
