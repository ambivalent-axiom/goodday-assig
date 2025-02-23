<?php


namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class PostLandingController extends Controller
{
    public function index()
    {
        $type = request()->route('type');
        $modelClass = PostHelpers::getModelClass($type);

        $posts = $modelClass::query()
            ->with('user')
            ->latest()
            ->paginate(10);

        return Inertia::render('Landing/index', [
            'posts' => $posts,
            'type' => $type,
        ]);
    }
}
