<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PostIndexController extends Controller
{

    public function index()
    {
        $type = request()->route('type');
        if (!Auth::user()
            ->can(
                PostHelpers::getPermissionName($type)
            ))
        {
            Log::info(
                "$type: Cannot View Posts",
                ['user_id' => Auth::id()]
            );
            return redirect()
                ->back()
                ->with('error', "You are not authorized to view $type posts.");
        }

        $modelClass = PostHelpers::getModelClass($type);

        $posts = $modelClass::query()
            ->when(!Auth::user()->hasRole('admin'), function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->with('user')
            ->latest()
            ->paginate(10);

        return Inertia::render('Dashboard/index', [
            'posts' => $posts,
            'type' => $type,
            'template' => 'list',
        ]);
    }
}
