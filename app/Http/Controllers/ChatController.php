<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatGroup;
use App\Models\ChatGroupMessage;
use App\Models\User;
use App\Models\ChatMessage;
use App\Models\PersonalMessage;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat');
    }

    public function fetchMessages()
    {
        $messages = ChatMessage::with('user')
            ->latest()
            ->take(50)
            ->get()
            ->reverse()
            ->values();

        $response = $messages->map(function($msg) {
            return [
                'id' => $msg->id,
                'message' => $msg->message,
                'created_at' => $msg->created_at,
                'user' => [
                    'id' => $msg->user->id,
                    'name' => $msg->user->name,
                ],
            ];
        });

        return response()->json($response);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|max:1000',
        ]);

        $msg = ChatMessage::create([
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        return response()->json([
            'id' => $msg->id,
            'message' => $msg->message,
            'created_at' => $msg->created_at,
            'user' => [
                'id' => $msg->user->id,
                'name' => $msg->user->name,
            ],
        ]);
    }

    public function createGroup(Request $request)
    {
        $request->validate(['name' => 'required|string|max:50']);
        $group = ChatGroup::create([
            'name' => $request->name,
            'admin_id' => auth()->id(),
        ]);
        $group->users()->attach(auth()->id());
        return response()->json(['id' => $group->id, 'name' => $group->name]);
    }

    public function fetchGroups()
    {
        $groups = auth()->user()->chatGroups()->get();
        return response()->json($groups);
    }

    public function groupInfo($id)
    {
        $group = ChatGroup::with('users')->findOrFail($id);
        return response()->json([
            'members' => $group->users->map(fn($u) => ['id' => $u->id, 'name' => $u->name]),
            'admin_id' => $group->admin_id,
            'is_member' => $group->users->contains(auth()->id()),
            'is_admin' => $group->admin_id === auth()->id(),
        ]);
    }

    public function inviteUser(Request $request, $id)
    {
        $group = ChatGroup::findOrFail($id);
        if ($group->admin_id !== auth()->id()) return response()->json(['message'=>'No autorizado'],403);
        $user = User::where('name', $request->username)->first();
        if (!$user) return response()->json(['message'=>'Usuario no encontrado'],404);
        $group->users()->attach($user->id);
        return response()->json(['message'=>'Usuario agregado']);
    }

    public function leaveGroup($id)
    {
        $group = ChatGroup::findOrFail($id);
        $group->users()->detach(auth()->id());
        return response()->json(['message'=>'Saliste del grupo']);
    }

    public function deleteGroup($id)
    {
        $group = ChatGroup::findOrFail($id);
        if ($group->admin_id !== auth()->id()) return response()->json(['message'=>'No autorizado'],403);
        $group->delete();
        return response()->json(['message'=>'Grupo eliminado']);
    }

    public function fetchGroupMessages($id)
    {
        $messages = ChatGroupMessage::where('chat_group_id', $id)
            ->with('user')
            ->latest()
            ->take(50)
            ->get()
            ->reverse()
            ->values();

        $response = $messages->map(function($msg) {
            return [
                'id' => $msg->id,
                'message' => $msg->message,
                'created_at' => $msg->created_at,
                'user' => [
                    'id' => $msg->user->id,
                    'name' => $msg->user->name,
                ],
            ];
        });

        return response()->json($response);
    }

    public function sendGroupMessage(Request $request, $id)
    {
        $request->validate(['message' => 'required|max:1000']);
        $msg = ChatGroupMessage::create([
            'chat_group_id' => $id,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);
        return response()->json([
            'id' => $msg->id,
            'message' => $msg->message,
            'created_at' => $msg->created_at,
            'user' => [
                'id' => $msg->user->id,
                'name' => $msg->user->name,
            ],
        ]);
    }

    public function personalSearch(Request $request)
    {
        $query = $request->input('q');
        $users = User::where('name', 'like', "%$query%")
            ->where('id', '!=', auth()->id())
            ->take(10)->get(['id', 'name']);
        return response()->json($users);
    }

    public function personalMessages($id)
    {
        $myId = auth()->id();
        $messages = PersonalMessage::where(function($q) use ($id, $myId) {
            $q->where('from_id', $myId)->where('to_id', $id);
        })->orWhere(function($q) use ($id, $myId) {
            $q->where('from_id', $id)->where('to_id', $myId);
        })
        ->with('user')
        ->orderBy('created_at')
        ->get();

        $response = $messages->map(function($msg) {
            return [
                'id' => $msg->id,
                'message' => $msg->message,
                'created_at' => $msg->created_at,
                'user' => [
                    'id' => $msg->user->id,
                    'name' => $msg->user->name,
                ],
            ];
        });

        return response()->json($response);
    }

    public function sendPersonalMessage(Request $request, $id)
    {
        $request->validate(['message' => 'required|max:1000']);
        $msg = PersonalMessage::create([
            'from_id' => auth()->id(),
            'to_id' => $id,
            'message' => $request->message,
        ]);
        return response()->json([
            'id' => $msg->id,
            'message' => $msg->message,
            'created_at' => $msg->created_at,
            'user' => [
                'id' => $msg->from_id,
                'name' => auth()->user()->name,
            ],
        ]);
    }

    public function lastChats()
    {
        $myId = auth()->id();
        $userIds = PersonalMessage::where('from_id', $myId)
            ->orWhere('to_id', $myId)
            ->get()
            ->flatMap(function($msg) use ($myId) {
                return [$msg->from_id, $msg->to_id];
            })
            ->unique()
            ->filter(function($id) use ($myId) { return $id != $myId; })
            ->values();

        $users = User::whereIn('id', $userIds)->get(['id', 'name']);
        return response()->json($users);
    }

    public function deletePersonalChat($userId)
    {
        $myId = auth()->id();

        \App\Models\PersonalMessage::where(function($q) use ($userId, $myId) {
            $q->where('from_id', $myId)->where('to_id', $userId);
        })->orWhere(function($q) use ($userId, $myId) {
            $q->where('from_id', $userId)->where('to_id', $myId);
        })->delete();

        return response()->json(['message' => 'Conversación eliminada']);
    }
}
