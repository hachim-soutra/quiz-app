<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuizTheme;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function index()
    {
        $folders = QuizTheme::paginate(10);;

        return view('admin.Folder.index',compact('folders'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'label' => ['required','unique:folders,label'],
        ]);

        QuizTheme::create([
            'label' => $request->label,
        ]);

        return redirect()->back()->with('status', 'Your folder had been added');
    }
    public function update(Request $request,QuizTheme $folder)
    {
        $request->validate([
            'label' => ['required','unique:folders,label'.$folder->id],
        ]);

        $folder->update([
            'label' => $request->label,
        ]);

        return redirect()->back()->with('status', 'Folder updated Successfully');
    }
    public function destroy(QuizTheme $folder)
    {
        $folder->quizzes()->where('folder_id',$folder->id)->delete();
        $folder->delete();
        return redirect()->back()->with('status', 'folder deleted Successfully');
    }
}
