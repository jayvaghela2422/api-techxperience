<?php

namespace App\Http\Controllers;

use App\Models\CustomerStory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomerStoryController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = CustomerStory::query();

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('project_title', 'LIKE', "%{$search}%")
                      ->orWhere('client_name', 'LIKE', "%{$search}%")
                      ->orWhere('category', 'LIKE', "%{$search}%");
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $perPage = $request->get('per_page', 9);

            // $stories = $query
            //     ->orderBy('created_at', 'desc')
            //     ->paginate($perPage);
           $stories = $query
                    ->orderByRaw('admin_order = 0, admin_order ASC')
                    ->orderBy('created_at', 'desc')
                    ->paginate($perPage);



            return response()->json([ 
                'success' => true,
                'message' => 'Customer stories fetched successfully',
                'data'    => $stories,   
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching stories',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get single story (edit page)
     */
    public function show($id)
    {
        try {
            $story = CustomerStory::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Customer story fetched successfully',
                'data'    => $story,
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Customer story not found',
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching the story',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create new customer story
     * (New Customer Story form)
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'project_title'     => 'required|string|max:255',
                'category'          => 'nullable|string',
                'client_name'       => 'nullable|string',
                'short_description' => 'nullable|string',
                'challenge'         => 'nullable|string',
                'solution'          => 'nullable|string',
                'key_results'       => 'nullable|string',
                'status'            => 'required|in:published,draft',
                'project_image'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $story = new CustomerStory();
            $story->project_title     = $request->project_title;
            $story->category          = $request->category;
            $story->client_name       = $request->client_name;
            $story->short_description = $request->short_description;
            $story->challenge         = $request->challenge;
            $story->solution          = $request->solution;
            $story->key_results       = $request->key_results;
            $story->status            = $request->status;
            $story->created_by        = auth()->id() ?? 1;

            if ($request->hasFile('project_image')) {
                $imageName = time() . '.' . $request->file('project_image')->extension();
                $destinationPath = public_path('customer-stories');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $request->file('project_image')->move($destinationPath, $imageName);
                $story->image_path = 'customer-stories/' . $imageName;
            }

            $story->save();

            return response()->json([
                'success' => true,
                'message' => 'Customer Story Created Successfully',
                'data'    => $story
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Update existing story
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'project_title'     => 'required|string|max:255',
                'category'          => 'nullable|string',
                'client_name'       => 'nullable|string',
                'short_description' => 'nullable|string',
                'challenge'         => 'nullable|string',
                'solution'          => 'nullable|string',
                'key_results'       => 'nullable|string',
                'status'            => 'required|in:published,draft',
                'project_image'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $story = CustomerStory::findOrFail($id);

            $story->project_title     = $request->project_title;
            $story->category          = $request->category;
            $story->client_name       = $request->client_name;
            $story->short_description = $request->short_description;
            $story->challenge         = $request->challenge;
            $story->solution          = $request->solution;
            $story->key_results       = $request->key_results;
            $story->status            = $request->status;
            $story->updated_by        = auth()->id() ?? 1;

            if ($request->hasFile('project_image')) {
                $imageName = time() . '.' . $request->file('project_image')->extension();
                $destinationPath = public_path('customer-stories');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $request->file('project_image')->move($destinationPath, $imageName);
                $story->image_path = 'customer-stories/' . $imageName;
            }

            $story->save();

            return response()->json([
                'success' => true,
                'message' => 'Customer Story Updated Successfully',
                'data'    => $story
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Delete story
     */
    public function destroy($id)
    {
        try {
            $story = CustomerStory::findOrFail($id);
            $story->delete();

            return response()->json([
                'success' => true,
                'message' => 'Customer story deleted successfully',
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Customer story not found',
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while deleting the story',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }


    public function publicIndex(Request $request)
    {
        try {
            $query = CustomerStory::where('status', 'published');

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('project_title', 'LIKE', "%{$search}%")
                    ->orWhere('client_name', 'LIKE', "%{$search}%")
                    ->orWhere('category', 'LIKE', "%{$search}%");
                });
            }

            // NEWEST STORY FIRST
            //$stories = $query->orderBy('created_at', 'desc')->get();
           $stories = $query
                    ->orderByRaw('admin_order = 0, admin_order ASC')
                    ->orderBy('created_at', 'desc')
                    ->get();



            return response()->json([
                'success' => true,
                'message' => 'Customer stories fetched successfully',
                'data'    => $stories
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching public stories',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    public function publicShow($id)
    {
        try {
            $story = CustomerStory::where('id', $id)
                        ->where('status', 'published')
                        ->firstOrFail();

            return response()->json([
                'success' => true,
                'message' => 'Customer story fetched successfully',
                'data'    => $story
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Story not found or not published'
            ], 404);
        }
    }

    public function reorder(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required|in:admin,public',
                'orders' => 'required|array|min:1',
                'orders.*.id' => 'required|exists:customer_stories,id',
                'orders.*.order' => 'required|integer|min:1',
            ]);

            // Decide which column to update
            $orderColumn = $request->type === 'admin'
                ? 'admin_order'
                : 'public_order';

            foreach ($request->orders as $item) {
                CustomerStory::where('id', $item['id'])
                    ->update([$orderColumn => $item['order']]);
            }

            return response()->json([
                'success' => true,
                'message' => ucfirst($request->type) . ' order updated successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reorder stories',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }


}