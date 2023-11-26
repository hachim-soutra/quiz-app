<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuestionsCategorization;
use Illuminate\Http\Request;

class QuestionsCategorizationController extends Controller
{
    public function index(Request $request)
    {
        $categories = QuestionsCategorization::all();

        return view('admin.question_categorization.index', compact('categories'));
    }
    public function create(Request $request)
    {
        return view('admin.question_categorization.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:questions_categorizations,name',
            'color' => 'required|unique:questions_categorizations,color'
        ]);

        QuestionsCategorization::create([
            'name' => $request->name,
            'color' => $request->color,
        ]);
        return redirect()->route('categorie.index')->with('status', 'Your categorie has been added');
    }
    public function edit(Request $request)
    {
    }
    public function update(Request $request, QuestionsCategorization $categorie)
    {
        $request->validate([
            'name' => ['required', 'unique:questions_categorizations,name,' . $categorie->id],
            'color' => ['required', 'unique:questions_categorizations,color,' . $categorie->id]
        ]);

        $categorie->update([
            'name' => $request->name,
            'color' => $request->color,
        ]);
        return redirect()->back()->with('status', 'Categorie updated Successfully');
    }
    public function destroy(QuestionsCategorization $categorie)
    {
        $categorie->questions()->update([
            'categorie_id' => '1'
        ]);
        $categorie->delete();
        return redirect()->back()->with('status', 'categorie deleted Successfully');
    }
}
