<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Award;
use App\Models\CastMember;
use App\Models\Producer;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    // POST /api/admin/projects — Create new project
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title'         => 'required|string|max:255',
            'title_km'      => 'nullable|string',
            'poster_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'banner_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $data = $request->except(['poster_image', 'banner_image']);

        // Generate unique slug
        $data['slug'] = Str::slug($request->title) . '-' . uniqid();

        // Handle poster image upload
        if ($request->hasFile('poster_image')) {
            $data['poster_image'] = $request->file('poster_image')
                ->store('posters', 'public');
        }

        // Handle banner image upload
        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $request->file('banner_image')
                ->store('banners', 'public');
        }

        // Convert is_featured string to boolean
        $data['is_featured'] = filter_var($request->is_featured ?? false, FILTER_VALIDATE_BOOLEAN);

        $project = Project::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Project created successfully',
            'data'    => $project,
        ], 201);
    }

    // PUT /api/admin/projects/{id} — Update project
    public function update(Request $request, int $id): JsonResponse
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json(['success' => false, 'message' => 'Project not found'], 404);
        }

        $data = $request->except(['poster_image', 'banner_image']);

        // Handle poster image upload (delete old one first)
        if ($request->hasFile('poster_image')) {
            if ($project->poster_image && !str_starts_with($project->poster_image, 'http')) {
                Storage::disk('public')->delete($project->poster_image);
            }
            $data['poster_image'] = $request->file('poster_image')
                ->store('posters', 'public');
        }

        // Handle banner image upload (delete old one first)
        if ($request->hasFile('banner_image')) {
            if ($project->banner_image && !str_starts_with($project->banner_image, 'http')) {
                Storage::disk('public')->delete($project->banner_image);
            }
            $data['banner_image'] = $request->file('banner_image')
                ->store('banners', 'public');
        }

        // Convert is_featured
        if (isset($data['is_featured'])) {
            $data['is_featured'] = filter_var($data['is_featured'], FILTER_VALIDATE_BOOLEAN);
        }

        $project->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Project updated successfully',
            'data'    => $project,
        ]);
    }

    // DELETE /api/admin/projects/{id} — Delete project
    public function destroy(int $id): JsonResponse
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json(['success' => false, 'message' => 'Project not found'], 404);
        }

        // Delete images from storage
        if ($project->poster_image && !str_starts_with($project->poster_image, 'http')) {
            Storage::disk('public')->delete($project->poster_image);
        }
        if ($project->banner_image && !str_starts_with($project->banner_image, 'http')) {
            Storage::disk('public')->delete($project->banner_image);
        }

        $project->delete();

        return response()->json([
            'success' => true,
            'message' => 'Project deleted successfully',
        ]);
    }

    // POST /api/admin/projects/{id}/cast — Add cast member
    public function addCast(Request $request, int $id): JsonResponse
    {
        $project = Project::find($id);
        if (!$project) {
            return response()->json(['success' => false, 'message' => 'Project not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'      => 'required|string',
            'role_name' => 'nullable|string',
            'photo'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = [
            'project_id' => $id,
            'name'       => $request->name,
            'role_name'  => $request->role_name,
        ];

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('cast', 'public');
        }

        $castMember = CastMember::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Cast member added',
            'data'    => $castMember,
        ], 201);
    }

    // POST /api/admin/projects/{id}/awards — Add award
    public function addAward(Request $request, int $id): JsonResponse
    {
        $project = Project::find($id);
        if (!$project) {
            return response()->json(['success' => false, 'message' => 'Project not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'festival_name' => 'required|string',
            'award_title'   => 'nullable|string',
            'year'          => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $award = Award::create([
            'project_id'    => $id,
            'festival_name' => $request->festival_name,
            'award_title'   => $request->award_title,
            'year'          => $request->year,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Award added',
            'data'    => $award,
        ], 201);
    }

    // POST /api/admin/projects/{id}/producers — Add producer
    public function addProducer(Request $request, int $id): JsonResponse
    {
        $project = Project::find($id);
        if (!$project) {
            return response()->json(['success' => false, 'message' => 'Project not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'  => 'required|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = [
            'project_id' => $id,
            'name'       => $request->name,
        ];

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('producers', 'public');
        }

        $producer = Producer::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Producer added',
            'data'    => $producer,
        ], 201);
    }
    // DELETE /api/admin/cast/{id}
public function deleteCast(int $id): JsonResponse
{
    $cast = \App\Models\CastMember::find($id);
    if (!$cast) return response()->json(['success' => false, 'message' => 'Not found'], 404);
    if ($cast->photo) Storage::disk('public')->delete($cast->photo);
    $cast->delete();
    return response()->json(['success' => true, 'message' => 'Cast member deleted']);
}

// DELETE /api/admin/awards/{id}
public function deleteAward(int $id): JsonResponse
{
    $award = \App\Models\Award::find($id);
    if (!$award) return response()->json(['success' => false, 'message' => 'Not found'], 404);
    $award->delete();
    return response()->json(['success' => true, 'message' => 'Award deleted']);
}

// DELETE /api/admin/producers/{id}
public function deleteProducer(int $id): JsonResponse
{
    $producer = \App\Models\Producer::find($id);
    if (!$producer) return response()->json(['success' => false, 'message' => 'Not found'], 404);
    if ($producer->photo) Storage::disk('public')->delete($producer->photo);
    $producer->delete();
    return response()->json(['success' => true, 'message' => 'Producer deleted']);
}
}