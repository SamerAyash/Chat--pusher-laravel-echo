<?php

namespace App\Http\Controllers;

use App\Message;
use App\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts=User::all();
        return response()->json($contacts);
    }
    public function getMessagesFor($id){
        $messages=Message::where('from',$id)->orWhere('to',$id)->get();
        return response()->json($messages);
    }
    public function sendMessage(Request $request)
    {
        $message=Message::create([
            'from'=>auth()->id(),
            'to'=>$request->contact_id,
            'text'=>$request->text
        ]);
        return response()->json($message);
    }
}
