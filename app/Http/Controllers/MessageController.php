<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Message;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts=User::where('id','!=',auth()->id())->get();
        $unreadIds=Message::select(DB::raw("`from` as sender_id ,count(`from`) as messages_count"))
            ->where('read',false)
            ->where('to',auth()->id())
            ->groupBy('from')
            ->get();
        $contacts= $contacts->map(function ($contact) use ($unreadIds){
            $contactUnread = $unreadIds->where('sender_id',$contact->id)->first();
            $contact->unread=$contactUnread ? $contactUnread->messages_count : 0;
            return $contact;
        });
        return response()->json($contacts);
    }
    public function getMessagesFor($id){

        Message::where('from',$id)->where('to',Auth::id())->update(['read'=>true]);
        $messages =Message::where(function ($q) use ($id){
            $q->where('from',Auth::id());
            $q->where('to',$id);
        })
            ->orWhere(function ($q) use ($id){
            $q->where('from',$id);
            $q->where('to',Auth::id());
        })
        ->get();
        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $message=Message::create([
            'from'=>auth()->id(),
            'to'=>$request->contact_id,
            'text'=>$request->text
        ]);
        broadcast(new NewMessage($message));
        return response()->json($message);
    }
}
