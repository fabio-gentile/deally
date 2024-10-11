<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDealRequest;
use App\Http\Requests\UpdateDealRequest;
use App\Models\CategoryDeal;
use App\Models\CommentDeal;
use App\Models\Deal;
use App\Models\ImageDeal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DealController extends Controller
{
    /**
     *  Load all replies for a comment.
     */
    protected function loadAllReplies($comment): void
    {
        $comment->replies->each(function ($reply) {
            // Load the user corresponding to the 'answer_to' field
            $reply->load(['replies' => function ($query) {
                $query->orderBy('created_at', 'desc'); // Sort replies by created_at
            }, 'answerToUser', 'user']); // Load the user referenced by 'answer_to'

            $this->loadAllReplies($reply); // Recursively load sub-replies
        });
    }

    /**
     * Display the specified deal.
     */
    public function show(Request $request, string $slug): \Inertia\Response
    {
        $deal = Deal::where('slug', $slug)->with('user')->firstOrFail();
        $user = auth()->user();
//        dd($deal);
        if ($user) {
            // associate the voteDeals to the deal
            $deal->user_vote = $deal->voteDetails->first();
        }

        $similarDeals = Deal::where('category_deal_id', $deal->category_deal_id)
            ->with('images')
            ->where('id', '!=', $deal->id)
            ->where('expiration_date', '>', now())
            ->where('votes', '>', -5)
            ->inRandomOrder()
            ->limit(10)
            ->get();

        $allComments = $deal->comments()
            ->with([
                'replies' => function ($query) {
                    $query->orderBy('created_at', 'desc'); // Sort direct replies by created_at
                },
                'user', // Load the user for each comment
                'replies.user', // Load the user for each reply,
                'replies.answerToUser' // Load the 'answer_to' user for each reply
            ])
            ->whereNull('parent_id') // Only get top-level comments
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($allComments as $comment) {
            $this->loadAllReplies($comment); // Recursively load replies
        }

//        dd($allComments);

        return Inertia::render('Deal/Show', [
            'deal' => $deal,
            'userDealsCount' => $deal->user->deals->count(),
            'images' => $deal->images,
            'category' => CategoryDeal::where('id', $deal->category_deal_id)->first()->name,
            'similarDeals' => $similarDeals ?? [],
            'allComments' => $allComments,
            'allCommentsCount' => CommentDeal::where('deal_id', $deal->id)->count(),
            'isExpired' => $deal->isExpired(),
        ]);
    }
    /**
     * Store a newly created deal.
     */
    public function store(Request $request, StoreDealRequest $storeDealRequest): \Illuminate\Http\RedirectResponse
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

        if ($validated['images']) {
            $this->storeImage($validated['images'], $deal);
        }
//        TODO: Add redirect with success message to the deal page
        return redirect()->route('home.index');
    }

    /**
     * Display the form for creating a new deal.
     */
    public function create(Request $request): \Inertia\Response
    {
        return Inertia::render('Deal/Create', [
            'categories' => CategoryDeal::all(),
        ]);
    }

    /**
     * Display the specified deal form.
     */
    public function edit(Request $request, string $slug): \Inertia\Response
    {
        $deal = Deal::where('slug', $slug)->firstOrFail();
        Gate::authorize('update', $deal);

        return Inertia::render('Deal/Edit', [
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
     * Update the specified deal.
     */
    public function update(Request $request, int $id, UpdateDealRequest $updateDealRequest): \Illuminate\Http\RedirectResponse
    {
        $deal = Deal::where('id', $id)->firstOrFail();
        Gate::authorize('update', $deal);
        $validated = $updateDealRequest->validated();

        $deal->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'original_price' => $validated['original_price'],
            'price' => $validated['price'],
            'expiration_date' => $validated['expiration_date'],
            'start_date' => $validated['start_date'],
            'promo_code' => $validated['promo_code'],
            'deal_url' => $validated['deal_url'],
            'category_deal_id' => CategoryDeal::where('name', $validated['category'])->first()->id,
        ]);

        if ($validated['images']) {
            $this->storeImage($validated['images'], $deal);
        }

        //        TODO: Add redirect with success message
        return redirect()->route('home.index');
    }

    /**
     * Delete the specified deal image.
     */
    public function deleteImage(Request $request, string $filename): \Illuminate\Http\JsonResponse
    {
        $image = ImageDeal::where('filename', $filename)->firstOrFail();
        Gate::authorize('delete', $image);

        Storage::delete($image->path . $image->filename);
        $image->delete();
        $request->session()->flash('success', 'Image supprimée avec succès.');

        return response()->json(['success' => 'Image supprimée avec succès.']);
    }

    /**
     * Remove the specified deal.
     */
    public function destroy(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $deal = Deal::where('id', $id)->firstOrFail();
        Gate::authorize('delete', $deal);

        $images = ImageDeal::where('deal_id', $deal->id)->get();
        foreach ($images as $image) {
            Storage::delete($image->path . $image->filename);
            $image->delete();
        }

        $deal->delete();

        $request->session()->flash('success', 'Deal supprimé avec succès.');
        return redirect()->route('home.index');
    }

    /**
     * Store the image for the specified deal.
     * @param $images
     * @param $deal
     * @return void
     */
    public function storeImage($images, $deal): void
    {
        foreach ($images as $image) {
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
    }
}
