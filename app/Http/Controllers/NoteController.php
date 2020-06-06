<?php

namespace App\Http\Controllers;

use App\Note;
use App\User;
use App\PersonalAccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
   
    public function index(Note $note, Request $request, User $user)
    {
       
       
        $notes = Note::where('user_id', '=', Auth::user()->id )->orderby('created_at', 'desc')->get();
        return response()->json(['notes' => $notes]);
           
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Note $note)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required',
           
        ]);
        if ($validator->fails()) {
           
            $errors = $validator->errors();

            return response('error : content required', 422);
            
        }
        $note = new Note;
        $note->content = $request->content;
        $note->user_id = Auth::user()->id;
        $note->save();
        return response()->json(['note' => $note]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function show(Note $note, Request $request)
    {
       

           if(Note::where('id', $note->id)->exists()){

                    if(! Note::where('user_id', '=', Auth::user()->id)->exists()){
                        return response()->json(['error: pas votre note id' => 403]);
                    }else{
                     $note = $note->where('id', $note->id)->first();
                    return response()->json(['note' => $note]);
                    }    
                
            }
            else{
              
                return response()->json(['error: pas votre note id' => 404]);
            }
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function edit(Note $note)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request, Note $note)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required',
           
        ]);
        if ($validator->fails()) {
           
            $errors = $validator->errors();

            return response('error : content required', 422);
            
        }
       
        $note = Note::findOrFail($id);
        if($note->user_id != $request->user()->id){
            return response('error', 403);
        }
        $note->content = $request->content;
        $note->save();

        return response()->json(['note' => $note]);
      
     
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Note $note, Request $request)
    {
        
        $note = Note::findOrFail($id);
        if($note->user_id != $request->user()->id){
            return response('error', 403);
        }
        $note->delete();
        return response(null);
       
           
        
    }
    
}
