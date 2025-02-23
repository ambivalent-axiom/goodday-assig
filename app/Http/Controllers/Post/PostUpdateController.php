<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class PostUpdateController extends Controller
{

    public function index($id)
    {
        $type = request()->route('type');
        if (!Auth::user()
            ->can(
                PostHelpers::getPermissionName($type, 'edit')
            ))
        {
            Log::info(
                "Create: Cannot Edit {$type}",
                ['user_id' => Auth::id()]
            );
            return redirect()
                ->back()
                ->with('error', "You are not authorized to manage {$type} posts.");
        }

        try {
            $modelClass = PostHelpers::getModelClass($type);
            $post = $modelClass::findOrFail($id);

            if (!Auth::user()->hasRole('admin') && $post->user_id != Auth::id()) {
                Log::info(
                    "$type Edit: Hacking attempt",
                    ['id' => $id, 'user_id' => Auth::id()]
                );
                abort(403, 'Unauthorized action.');
            }
        } catch (ModelNotFoundException $e) {
            return back()->with('error', ucfirst($type) . ' post not found.');
        } catch (Exception $e) {
            Log::error("Exception during {$type} post edit", [
                'id' => $id,
                'error' => $e->getMessage(),
                'type' => get_class($e)
            ]);
            return back()->with('error', "Failed to retrieve {$type} post.");
        }

        return Inertia::render('Dashboard/index', [
            'template' => 'edit',
            'post' => $post,
            'mode' => 'edit',
            'type' => $type,
        ]);
    }

    public function update(Request $request, $id)
    {
        $type = request()->route('type');
        try {
            if (!auth()->user()->hasPermissionTo(PostHelpers::getPermissionName($type, 'edit'))) {
                return back()->with('error', "You do not have permission to edit {$type} posts.");
            }

            $modelClass = PostHelpers::getModelClass($type);
            $post = $modelClass::findOrFail($id);

            $validated = $request->validate([
                'title' => 'required|string|max:100',
                'description' => 'required|string|max:255',
                'text' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($request->hasFile('image')) {
                if ($post->image) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $post->image));
                }
                $imagePath = '/storage/' . $request->file('image')->store(PostHelpers::getStoragePath($type), 'public');
                $validated['image'] = $imagePath;
            }

            $post->update($validated);
            return redirect()->route("$type.index")->with('success', ucfirst($type) . ' post updated successfully.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->with('error', 'Please check your input.');
        } catch (Exception $e) {
            Log::error("$type update failed: " . $e->getMessage());
            return back()->with('error', "Failed to update $type post.");
        }
    }
}
