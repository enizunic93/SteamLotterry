<?php

namespace App\Http\Controllers;

use App\Helpers\Steam\DotaItem;
use App\Helpers\Steam\Item;
use App\Helpers\Steam\ItemStorage;
use App\Models\Lot;
use App\Repositories\LotRepository;
use App\Repositories\SteamItemRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Helpers\Steam\APIBridge as SteamAPI;
use Illuminate\Support\Facades\Config;

class LotController extends Controller
{
    /**
     * The task repository instance.
     *
     * @var LotRepository
     */
    protected $lots;

    /**
     * The steam item repository instance.
     *
     * @var SteamItemRepository
     */
    protected $webInventory;


    /**
     * The Steam schema instance
     *
     * @var SteamAPI
     */
    protected $steamSchema;

    /**
     * @param LotRepository $lots
     * @param SteamItemRepository $webInventory
     * @param SteamAPI $steamSchema
     */
    public function __construct(LotRepository $lots, SteamItemRepository $webInventory, SteamAPI $steamSchema)
    {
        $this->middleware('auth');

        $this->lots = $lots;
        $this->steamSchema = $steamSchema;
        $this->webInventory = $webInventory;
    }

    /**
     * Display a list of all of the user's task.
     *
     * @param  Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $inventory = $this->steamSchema
		->getPlayerInventory(570, $request->user()->steam_id, [
            'where' => [
                ['key' => 'isTradable', 'operand' => '=', 'value' => true]
            ]
        ]);
        usort($inventory, [ItemStorage::getItemClass(570), 'sortByCharacter']);

        return view('lots.index', [
            'steam_schema' => $this->steamSchema,
            'lots' => Lot::all(),
            'inventory' => $inventory,
            'item_class' => ItemStorage::getItemClass(570),
            'web_inventory' => $this->webInventory->forUser($request->user()),
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
