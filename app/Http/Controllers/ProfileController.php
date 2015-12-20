<?php

namespace App\Http\Controllers;

use App\Helpers\Steam\Items\ItemStorage;
use App\Repositories\LotRepository;
use App\Repositories\SteamItemRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Helpers\Steam\APIBridge as SteamAPI;

class ProfileController extends Controller
{
    protected $perPage = 10;

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
     * @param SteamItemRepository $webInventory
     * @param SteamAPI $steamSchema
     */
    public function __construct(SteamItemRepository $webInventory, SteamAPI $steamSchema)
    {
        $this->middleware('auth');

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
        $inventory = $this->fetchInventory(
            $request->user()->steam_id,
            0, $this->perPage
        );

        $result = view('profile.index', [
            'steam_schema' => $this->steamSchema,
            'inventory' => $inventory,
            'web_inventory' => $this->webInventory->forUser($request->user()),
        ]);

        return $result;
    }

    public function updateTradeURL(Request $request) {
        if ($request->isMethod('patch')) {

        }
    }

    /**
     * @param $userId
     * @param $from
     * @param $to
     * @return array
     */
    protected function fetchInventory($userId, $from, $to)
    {
        $inventory = $this->steamSchema
            ->queryPlayerInventory(570, $userId)
            ->where(function ($row) {
                return ($row['tradable']);
            })
            ->slice($from, $to)
            ->get();

        usort($inventory, [ItemStorage::getItemClass(570), 'sortByPrice']);

        return $inventory;
    }

    /**
     * For ajax showing history and lots.
     * @return Response
     */
    public function lots() {
        return view('profile.lots');
    }

    /**
     * TODO: AJAX!
     * @param Request $request
     * @return string
     */
    public function getNextInventory(Request $request)
    {
        $i = $request->inventoryIndex;//inventory index. 0 for first page load, next i+1
        $to = ($i + 1) * $this->perPage; // i = 3; $to = (3+1) * 5 = 20
        $from = $to - $this->perPage; // 20 - 5 = 15. Result: rows in [15, 20]

        return json_encode($this->fetchInventory($request->user()->steam_id, $from, $to));
    }

    /**
     * Создаёт команду в цепочке
     *
     * @param  Request $request
     * @return Response
     */
    public function sendItems(Request $request)
    {
//        $this->validate($request, [
//            'name' => 'required|max:255',
//        ]);

//        $request->user()->items()->create([
//            'name' => $request->name,
//        ]);

        return redirect('/profile');
    }
}
