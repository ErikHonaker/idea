<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use App\Models\Idea;

class IdeaImageController extends Controller
{
    public function destroy(Idea $idea)
    {
        Gate::authorize('workWith', $idea);
        Storage::disk('public')->delete($idea->image_path);
        $idea->update(['image_path' => null]);

        return back();
    }
}
