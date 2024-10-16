<?php

namespace App\Traits;

use App\Http\Requests\UpdateDealRequest;
use App\Models\CategoryDeal;
use App\Models\Deal;
use App\Models\ImageDeal;

trait UpdateDeal
{
    /**
     * Update the specified deal in storage.
     */
    public function updateDeal(UpdateDealRequest $request, Deal $deal): void
    {
        $validated = $request->validated();

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
        }
    }
}
