<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDealRequest;
use App\Models\CategoryDeal;
use App\Models\Deal;
use App\Models\ImageDeal;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DealController extends Controller
{
    /**
     * Store a newly created deal.
     */
    public function store(Request $request, StoreDealRequest $storeDealRequest)
    {
        $validated = $storeDealRequest->validated();

        $deal = Deal::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'original_price' => $validated['original_price'],
            'price' => $validated['price'],
            'expiration_date' => $validated['expiration_date'],
            'start_date' => $validated['start_date'],
            'promo_code' => $validated['promo_code'],
            'deal_url' => $validated['deal_url'],
            'category_deal_id' => CategoryDeal::where('name', $validated['category'])->first()->id,
            'user_id' => auth()->id(),
        ]);

        foreach ($validated['images'] as $image) {
            $extension = $image->extension();
            $originalName = $image->getClientOriginalName();
            $filename = uniqid('deal-', true) . '.' . $extension;
            $path = 'uploads/deals/';

            $image->storeAs($path, $filename, 'public');
            ImageDeal::create([
                'deal_id' => $deal->id,
                'path' => $path,
                'filename' => $filename,
                'original_filename' => $originalName,
            ]);
        }

//        TODO: Add redirect with success message to the deal page
        return redirect()->route('home.show');
    }

    /**
     * Display the form for creating a new deal.
     */
    public function create(Request $request)
    {
        return Inertia::render('Deal/Create', [
            'categories' => CategoryDeal::all(),
        ]);
    }
}
