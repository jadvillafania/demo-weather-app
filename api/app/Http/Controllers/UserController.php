<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use App\Models\User;
use App\Models\Weather;
use Carbon\Carbon;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 5);
        $page = $request->query('page', 1);
        // pagination

        $pagination = User::paginate($perPage, ['*'], 'page', $page);

        // get weather info for each user
        $users = $pagination->items();
        foreach ($users as $user) {
            $weather = WeatherService::weatherFromDB($user->latitude, $user->longitude);
            $user->weather = $weather;
        }

        return $pagination;
    }

    public function userWeather(Request $request, $id)
    {
        $user = User::find($id);

        $weather = WeatherService::getWeather($user->latitude, $user->longitude);

        return $weather;
    }
}
