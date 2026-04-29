<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    // GET /api/projects — Get all projects
    public function index(): JsonResponse
    {
        $projects = Project::with(['castMembers', 'awards', 'producers'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($p) => $this->formatProject($p));

        return response()->json([
            'success' => true,
            'data' => $projects,
        ]);
    }

    // GET /api/projects/featured — Get featured projects only
    public function featured(): JsonResponse
    {
        $projects = Project::with(['castMembers', 'awards', 'producers'])
            ->where('is_featured', true)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($p) => $this->formatProject($p));

        return response()->json([
            'success' => true,
            'data' => $projects,
        ]);
    }

    // GET /api/projects/{slug} — Get single project by slug
    public function show(string $slug): JsonResponse
    {
        $project = Project::with(['castMembers', 'awards', 'producers'])
            ->where('slug', $slug)
            ->first();

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatProject($project),
        ]);
    }

    // Helper: format project for JSON response
    private function formatProject(Project $project): array
    {
        return [
            'id'                   => $project->id,
            'title'                => $project->title,
            'title_km'             => $project->title_km,
            'slug'                 => $project->slug,
            'tagline'              => $project->tagline,
            'tagline_km'           => $project->tagline_km,
            'short_description'    => $project->short_description,
            'short_description_km' => $project->short_description_km,
            'synopsis'             => $project->synopsis,
            'synopsis_km'          => $project->synopsis_km,
            'genre'                => $project->genre,
            'duration'             => $project->duration,
            'release_date'         => $project->release_date,
            'country'              => $project->country,
            'language'             => $project->language,
            'status'               => $project->status,
            'poster_image'         => $project->poster_image_url,
            'banner_image'         => $project->banner_image_url,
            'trailer_url'          => $project->trailer_url,
            'youtube_id'           => $project->youtube_id,
            'rating'               => $project->rating,
            'votes'                => $project->votes,
            'year'                 => $project->year,
            'director'             => $project->director,
            'is_featured'          => $project->is_featured,
            'cast_members' => $project->castMembers->map(fn($c) => [
                'id'        => $c->id,
                'name'      => $c->name,
                'role_name' => $c->role_name,
                'photo'     => $c->photo_url,  // ✅ photo_url មិនមែន photo
            ]),
            'producers' => $project->producers->map(fn($p) => [
                'id'    => $p->id,
                'name'  => $p->name,
                'photo' => $p->photo_url,  // ✅ photo_url
            ]),
            'awards' => $project->awards->map(fn($a) => [
                'id'            => $a->id,
                'festival_name' => $a->festival_name,
                'award_title'   => $a->award_title,
                'year'          => $a->year,
            ]),

            'created_at' => $project->created_at,
            'updated_at' => $project->updated_at,
        ];
    }
}
