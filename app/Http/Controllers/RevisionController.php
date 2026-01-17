<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use App\Models\Revision;

class RevisionController extends Controller
{
    public function store(Request $request, Note $note)
    {
        $request->validate([
            'revision_text' => 'required|string'
        ]);

        $revision = $note->revisions()->create([
            'revision_text' => $request->revision_text,
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'message' => 'Revision added successfully',
            'revision' => $revision
        ], 201);
    }

    public function index(Note $note)
    {
        $revisions = $note->revisions()->with('user')->latest()->get();
        return response()->json([
            'note' => $note,
            'revisions' => $revisions
        ]);
    }
}
