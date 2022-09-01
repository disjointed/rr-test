<?php

namespace App\Http\Controllers;

use App\Ad;
use Illuminate\Http\Request;
use App\Http\Requests\AdRequest;
use App\Http\Resources\AdResource;

class AdController extends Controller
{
    public function index(Request $request)
    {
        $ads = Ad::query()
            ->orderBy(
                $request->query('sortBy') ?? 'id',
                $request->query('sortDir') ?? 'desc',
            )
            ->paginate(10);

        return AdResource::collection($ads);
    }

    public function store(AdRequest $request): AdResource
    {
        $ad = new Ad;

        $ad->fill($request->only('name', 'description', 'price', 'photos'));

        $ad->saveOrFail();

        return new AdResource($ad);
    }

    public function show(Ad $ad): AdResource
    {
        return new AdResource($ad);
    }

    public function update(AdRequest $request, Ad $ad): AdResource
    {
        $ad->fill($request->only('name', 'description', 'price', 'photos'));

        $ad->saveOrFail();

        return new AdResource($ad);
    }

    public function destroy(Ad $ad): AdResource
    {
        $ad->delete();

        return new AdResource($ad);
    }
}
