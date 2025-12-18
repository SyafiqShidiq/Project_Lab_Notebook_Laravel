<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Note;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $r)
    {
        return Note::where('user_id', $r->user()->id)
               ->orderByDesc('id')
               ->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $r)
    {
        $data = $r->validate([
            'title' => 'required|string',
            'category' => 'required|string',
            'objective' => 'required|string',
            'content' => 'required|string',
        ]);
        $data['user_id'] = $r->user()->id;
        return Note::create($data);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return Note::where('id', $id)
               ->where('user_id', auth()->id())
               ->firstOrFail();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $r, $id)
    {
        $note = Note::where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

        $r->validate([
            'revision_content' => 'required|string',
        ]);

        $time = now()->format('Y-m-d H:i:s');

        $cleanRevision = trim($r->revision_content);

        $revision = "\n\n========================\n"
        . "[REVISI | $time]\n"
        . $cleanRevision . "\n"
        . "========================\n";

        $note->content = $note->content . $revision;
        $note->save();

        return response()->json([
            'message' => 'Berhasil menambahkan revisi'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $note = Note::where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();
                
        $note->delete();
        return response()->json([
            'message' => 'Note dipindahkan ke Archive sampah'
        ]);
    }
    public function trash()
    {
        $notes = Note::onlyTrashed()->where('user_id', auth()->id())->orderByDesc('deleted_at')->get();

        return response()->json($notes);
    }
    public function trashed($id)
    {
        $note = Note::onlyTrashed()->where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        return response()->json($note);
    }
    public function restore($id)
    {
        $note = Note::onlyTrashed()->where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $note->restore();

        return response()->json([
            'message' => 'Note dipulihkan dari Archive Sampah'
        ]);
    }
    public function forceDelete($id)
    {
        $note = Note::onlyTrashed()->where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $note->forceDelete();

        return response()->json([
            'message' => 'Note dihapus permanen'
        ]);
    }
}
