<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Resources\CommentResource;
use App\Models\Tenant\Comment;
use App\Models\Tenant\Task;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    
  
    public function storeTask(CommentRequest $request,$task)
    {


        $task = Task::find($task);
        $comment = $task->comments()->create($request->validated());

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $comment->addMedia($file)->toMediaCollection('attachment');
            }
        }
        return response()->json([
            'message' => trans('crud.created'),
            'data' => new CommentResource($comment),
        ], 201);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(CommentRequest $request, $id)
    {
        $comment = Comment::with('media')->find($id);
        $comment->update($request->validated());
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $comment->clearMediaCollection('attachment');
                $comment->addMedia($file)->toMediaCollection('attachment');
            }
        }
        return response()->json([
            'data' => new CommentResource($comment),
            'message' => trans('crud.updated'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteRequest $request)
    {
        Comment::whereIn('id', $request->ids)->delete();
        return response()->json([
            'message' => trans('crud.deleted'),
        ]);
    }
}
