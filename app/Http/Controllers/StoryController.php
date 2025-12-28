<?php

namespace App\Http\Controllers;

use App\Models\Progress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoryController extends Controller
{
    public function index()
    {
        $userProgress = Progress::where('user_id', Auth::id())->first();
        $level = $userProgress ? $userProgress->level : 1;

        return view('story', compact('level'));
    }

    public function advanceLevel(Request $request)
    {
        $userProgress = Progress::firstOrCreate(
            ['user_id' => Auth::id()],
            ['level' => 1]
        );

        $userProgress->level++;
        $userProgress->save();

        return redirect()->route('story.index');
    }
}
