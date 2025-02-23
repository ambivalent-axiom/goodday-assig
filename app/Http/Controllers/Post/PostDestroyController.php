<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostDestroyController extends Controller
{
    public function destroy($id)
    {
        $type = request()->route('type');
        try {
            if (!Auth::user()
                ->can(
                    PostHelpers::getPermissionName(
                        $type,
                        'edit')
                ))
            {
                Log::info(
                    "$type Destroy: Cannot Edit Posts",
                    ['id' => $id, 'user_id' => Auth::id()]
                );
                abort(403, 'Unauthorized action.');
            }

            $modelClass = POstHelpers::getModelClass($type);
            $post = $modelClass::findOrFail($id);

            if (!Auth::user()->hasRole('admin') && $post->user_id != Auth::id()) {
                Log::info(
                    "$type Destroy: Hacking attempt",
                    ['id' => $id, 'user_id' => Auth::id()]
                );
                abort(403, 'Unauthorized action.');
            }

            $post->delete();
            Log::info("$type post deleted successfully", ['id' => $id]);
            return redirect()->route("$type.index")
                ->with('success', ucfirst($type) . ' post deleted successfully.');

        } catch (ModelNotFoundException $e) {
            return back()->with('error', ucfirst($type) . ' post not found.');
        } catch (Exception $e) {
            Log::error("Exception during $type post deletion", [
                'id' => $id,
                'error' => $e->getMessage(),
                'type' => get_class($e)
            ]);
            return back()->with('error', "Failed to delete $type post.");
        }
    }
}
