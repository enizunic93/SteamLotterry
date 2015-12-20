<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameHistory;
use App\Repositories\LotRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Http\Response;

class LotController extends Controller
{
    /**
     * The task repository instance.
     *
     * @var LotRepository
     */
    protected $lots;


    /**
     * @param LotRepository $lots
     * @internal param SteamItemRepository $webInventory
     * @internal param SteamAPI $steamSchema
     */
    public function __construct(LotRepository $lots)
    {
        $this->lots = $lots;
    }

    /**
     * Display a list of all of the user's task.
     *
     * @param  Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        return view('lots.index', [
            'games' => Game::take(20)->get(),
        ]);
    }

    /**
     * @param  Request $request
     * @return Response
     */
    public function history(Request $request)
    {
        return view('lots.history', [
            'history' => GameHistory::with('user', 'game', 'game.lot')->take(10)->get(),
        ]);
    }

    /**
     * Create a new task.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

        $request->user()->tasks()->create([
            'name' => $request->name,
        ]);

        return redirect('/tasks');
    }
}
