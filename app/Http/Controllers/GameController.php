<?php

namespace App\Http\Controllers;

use App\Core\Game;
use App\Models\BattleLog;
use Illuminate\Http\Request;

class GameController extends Controller
{

    public function index()
    {
        return view('game');
    }

    public function result(Request $request)
    {
        $battleLog = BattleLog::where('army1_id', $request->army1)->where('army2_id', $request->army2)->first();

        return view('result', [
            'log' => $battleLog
        ]);
    }

    public function start(Request $request)
    {
        $validated = $request->validate([
            'soldiers1' => 'required|integer',
            'soldiers2' => 'required|integer',
            'tanks1' => 'required|integer',
            'tanks2' => 'required|integer',
        ]);
        
        $game = new Game($validated);

        $game->start();
    }
}
