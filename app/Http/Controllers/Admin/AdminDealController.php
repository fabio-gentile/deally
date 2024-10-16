<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateDealRequest;
use App\Models\CategoryDeal;
use App\Models\Deal;
use App\Models\ImageDeal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AdminDealController extends Controller
{
    use \App\Traits\UpdateDeal;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Inertia\Response
    {
        $deals = Deal::query()->with(['userName', 'categoryDeal']);

        if (!$request->has('created_at')) {
            $deals->orderBy('created_at', 'desc');
        }

        if ($request->has('search') && $request->search !== null) {
            $deals->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('votes') && $request->votes !== null) {
            $deals->orderBy('votes', $request->votes === 'desc' ? 'desc' : 'asc');
        }

        if ($request->has('expiration_date') && $request->expiration_date !== null) {
            $deals->orderBy('expiration_date', $request->expiration_date === 'desc' ? 'desc' : 'asc');
        }

        if ($request->has('title') && $request->title !== null) {
            $deals->orderBy('title', $request->title === 'desc' ? 'desc' : 'asc');
        }

        if ($request->has('created_at') && $request->created_at !== null) {
            $deals->orderBy('created_at', $request->created_at === 'desc' ? 'desc' : 'asc');
        }

        $deals = $deals->paginate($request->per_page ?? 10);

        return Inertia::render('Admin/Deal/List', [
            'deals' => $deals->getCollection()->map(function ($deal) {
                return [
                    'id' => $deal->id,
                    'title' => $deal->title,
                    'price' => $deal->price,
                    'expiration_date' => $deal->expiration_date,
                    'created_at' => $deal->created_at,
                    'slug' => $deal->slug,
                    'category' => $deal->categoryDeal->name,
                    'votes' => $deal->votes,
                ];
            }),
            'pagination' => [
                'current_page' => $deals->currentPage(),
                'last_page' => $deals->lastPage(),
                'per_page' => $deals->perPage(),
                'total' => $deals->total(),
            ],
            'filters' => $request->all(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): \Inertia\Response
    {
        $deal = Deal::where('id', $id)->firstOrFail();

        return Inertia::render('Admin/Deal/Edit', [
            'deal' => $deal,
            'categories' => CategoryDeal::all(),
            'current_category' => CategoryDeal::where('id', $deal->category_deal_id)->first()->name,
            'images' => $deal->images,
            'start_date' => $deal->start_date->format('Y-m-d H:i:s'),
            'expiration_date' => $deal->expiration_date->format('Y-m-d H:i:s'),
            'uploaded_images' => ImageDeal::where('deal_id', $deal->id)->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDealRequest $request, string $id): \Illuminate\Http\RedirectResponse
    {
        $deal = Deal::where('id', $id)->firstOrFail();
        $this->updateDeal($request, $deal);

        return redirect()->route('admin.deals.list')->with('success', 'Le deal a été modifié avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): \Illuminate\Http\RedirectResponse
    {
        $deal = Deal::where('id', $id)->firstOrFail();
        $deal->delete();

        return redirect()->route('admin.deals.list')->with('success', 'Le deal a été supprimé avec succès');
    }

    /**
     * Delete the specified deal image.
     */
    public function deleteImage(Request $request, string $filename): \Illuminate\Http\RedirectResponse
    {
        $image = ImageDeal::where('filename', $filename)->firstOrFail();

        Storage::delete($image->path . $image->filename);
        $image->delete();

        return back()->with('success', 'Image supprimée avec succès.');
    }
}
