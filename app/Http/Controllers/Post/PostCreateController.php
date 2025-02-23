<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class PostCreateController extends Controller
{

    public function index()
    {
        $type = request()->route('type');
        if (!Auth::user()->can(PostHelpers::getPermissionName($type, 'edit'))) {
            Log::info(
                "Create: Cannot Edit $type",
                ['user_id' => Auth::id()]
            );
            return redirect()
                ->back()
                ->with('error', "You are not authorized to manage $type posts.");
        }

        return Inertia::render('Dashboard/index', [
            'template' => 'edit',
            'type' => $type,
        ]);
    }

    public function store(Request $request)
    {
        $type = request()->route('type');
        try {
            if (!auth()
                ->user()
                ->hasPermissionTo(
                    PostHelpers::getPermissionName($type, 'edit')
                ))
            {
                return back()->with(
                    'error',
                    "You do not have permission to create $type posts."
                );
            }

            $validated = $request->validate([
                'title' => 'required|string|max:100',
                'description' => 'required|string|max:255',
                'text' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($request->hasFile('image')) {
                $imagePath = $request
                    ->file('image')
                    ->store(
                        PostHelpers::getStoragePath($type),
                        'public'
                    );
                $validated['image'] = '/storage/' . $imagePath;
            }

            $modelClass = PostHelpers::getModelClass($type);
            $modelClass::create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'text' => $validated['text'],
                'image' => $validated['image'] ?? null,
                'user_id' => auth()->id(),
            ]);

            return redirect()
                ->route("$type.index")
                ->with('success', ucfirst($type) . ' post created successfully.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->with('error', 'Please check your input.');
        } catch (Exception $e) {
            Log::error("$type creation failed: " . $e->getMessage());
            return back()->with('error', "Failed to create $type post.");
        }
    }
}
