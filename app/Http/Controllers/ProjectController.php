<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteRequest;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Tenant\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProjectController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $projects = Project::with([
            'media',
            'users',
            'sprints',
            'sprints.stages',
            'sprints.stages.tasks',
            'sprints.stages.tasks.assignedBy',
            'sprints.stages.tasks.users',
            'sprints.stages.tasks.sprintStage',
        ])
            ->orderByDesc('id')
            ->get();

        $stats = [
            'all' => $projects->count(),
            'pending' => $projects->where('status', 'pending')->count(),
            'in_progress' => $projects->where('status', 'in_progress')->count(),
            'complete' => $projects->where('status', 'complete')->count(),
            'late' => $projects->where('status', 'late')->count(),
        ];

        if ($request->filled('status')) {
            $statuses = explode(',', $request->status);

            $projects = $projects->filter(function ($project) use ($statuses) {
                return in_array($project->status, $statuses);
            })->values();
        }

        return response()->json([
            'data' => ProjectResource::collection($projects),
            'stats' => $stats,
        ], 200);
    }

    public function store(ProjectRequest $request): JsonResponse
    {
        $project = Project::create($request->validated());

        $sprint = $project->sprints()->create([
            'name' => 'المرحلة الأساسية',
            'start_date' => $project->start_date,
            'end_date' => $project->end_date,
        ]);

        $sprint->stages()->createMany(
            [
                [
                    'order' => 1,
                    'name' => 'قيد الانتظار',
                ],
                [
                    'order' => 2,
                    'name' => 'قيد التنفيذ',
                ],
                [
                    'order' => 3,
                    'name' => 'قيد المراجعة',
                ],
                [
                    'order' => 4,
                    'name' => 'مكتملة',
                ],
            ]
        );

        $project->load([
            'sprints',
            'sprints.stages',
            'sprints.stages.tasks',
            'sprints.stages.tasks.assignedBy',
            'sprints.stages.tasks.users',
            'sprints.stages.tasks.sprintStage',
        ]);

        return response()->json([
            'data' => new ProjectResource($project),
            'message' => trans('crud.created'),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $project = Project::with([
            'media',
            'users',
            'sprints',
            'sprints.stages',
            'sprints.stages.tasks',
            'sprints.stages.tasks.assignedBy',
            'sprints.stages.tasks.sprintStage',
        ])->findOrFail($id);

        return response()->json([
            'data' => new ProjectResource($project),
        ], 200);
    }

    public function update(ProjectRequest $request, string $id): JsonResponse
    {
        $project = Project::findOrFail($id);

        $project->update($request->validated());

        $project->load([
            'sprints',
            'sprints.stages',
            'sprints.stages.tasks',
            'sprints.stages.tasks.assignedBy',
            'sprints.stages.tasks.sprintStage',
        ]);

        return response()->json([
            'data' => new ProjectResource($project),
            'message' => trans('crud.updated'),
        ], 200);
    }

    public function destroy(DeleteRequest $request): JsonResponse
    {
        $ids = $request->input('ids');

        Project::whereIn('id', $ids)->delete();

        return response()->json([
            'message' => trans('crud.deleted'),
        ], 200);
    }

    public function uploadFiles(Request $request, string $id): JsonResponse
    {
        
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'files' => ['required', 'array'],
            'files.*' => ['file', 'max:10240'],
        ]);

        foreach ($validated['files'] as $file) {
            $project
                ->addMedia($file)
                ->toMediaCollection('project_files');
        }

        return response()->json([
            'message' => trans('crud.created'),
        ], 201);
    }

    public function deleteFile(DeleteRequest $request, string $id): JsonResponse
    {
        $project = Project::findOrFail($id);

        $mediaId = $request->input('ids')[0];

        $media = $project->media()
            ->where('collection_name', 'project_files')
            ->findOrFail($mediaId);

        $media->delete();

        return response()->json([
            'message' => trans('crud.deleted'),
        ]);
    }

    public function downloadFile(string $projectId, string $mediaId): BinaryFileResponse
    {
        $project = Project::findOrFail($projectId);

        $media = $project->media()
            ->where('collection_name', 'project_files')
            ->findOrFail($mediaId);

        return response()->download($media->getPath(), $media->file_name);
    }
}
