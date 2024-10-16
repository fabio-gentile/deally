<?php

namespace App\Traits;

use App\Models\CategoryDiscussion;
use Illuminate\Support\Facades\Storage;

trait UpdateDiscussion
{
    public function handleUpdateDiscussion($request, $discussion)
    {
        if ($request->get('isThumbnailRemoved')) {
            try {
                Storage::delete($discussion->path . $discussion->thumbnail);
                $discussion->update([
                    'thumbnail' => null,
                    'original_filename' => null,
                    'path' => null,
                ]);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Une erreur est survenue lors de la suppression de l\'image');
            }
        }

        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $extension = $thumbnail->extension();
            $originalName = $thumbnail->getClientOriginalName();
            $filename = uniqid('discussion-', true) . '.' . $extension;
            $path = 'uploads/discussions/';

            $thumbnail->storeAs($path, $filename, 'public');
        }

        $discussion->update([
            'title' => $request->get('title'),
            'content' => $request->get('content'),
            'category_discussion_id' => CategoryDiscussion::where('name', $request->get('category'))->first()->id,
            'thumbnail' => $filename ?? $discussion->thumbnail,
            'original_filename' => $originalName ?? $discussion->original_filename,
            'path' => $path ?? $discussion->path,
        ]);
    }
}
