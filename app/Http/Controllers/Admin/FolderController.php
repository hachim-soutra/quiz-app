<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuizTheme;
use App\Models\Quiz;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function index()
    {
        $folders = QuizTheme::with("quizzes")->orderBy('label')->get();
        $quiz = Quiz::all();

        return view('admin.Folder.index', compact('folders', 'quiz'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'label' => ['required', 'unique:folders,label'],
        ]);

        QuizTheme::create([
            'label' => $request->label,
        ]);

        return redirect()->back()->with('status', 'Your folder had been added');
    }
    public function update(Request $request, QuizTheme $folder)
    {
        $request->validate([
            'label' => ['required', 'unique:folders,label,' . $folder->id],
        ]);
        $folder->quizzes()->update(['folder_id' => 9999]);
        if ($request->select_quizzes) {
            Quiz::whereIn("id", array_map('intval', $request->select_quizzes))->update(['folder_id' => $folder->id]);
        }
        $folder->update([
            'label' => $request->label,
        ]);

        return redirect()->back()->with('status', 'Folder updated Successfully');
    }
    public function destroy(QuizTheme $folder)
    {
        $folder->quizzes()->where('folder_id', $folder->id)->delete();
        $folder->delete();
        return redirect()->back()->with('status', 'folder deleted Successfully');
    }
}
