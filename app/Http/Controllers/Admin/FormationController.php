<?php

namespace App\Http\Controllers\Admin;
;

use App\Enum\PayementTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\FormationRequest;
use App\Models\Formation;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FormationController extends Controller
{
    function index()
    {
        $formations = Formation::with('quizzes')->get();
        return view('admin.formations.index', [ 'formations' => $formations ]);
    }

    function create()
    {
        $quiz = Quiz::where("payement_type", PayementTypeEnum::FREE)->get();
        return view('admin.formations.create', [ 'quiz' => $quiz ]);
    }
    function store(FormationRequest $request)
    {
        $quizzes = $request->select_quizzes;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extention = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extention;
            $file->move('images/', $filename);
        }

        $formation = Formation::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $request->hasFile('image') ? $filename : "blank.png",
            'video' => 'videos/'. $request->video,
        ]);


        $formation->quizzes()->attach($quizzes);

        return redirect()->back()->with('status', 'formationtion created successfully');
    }


    public function edit(Formation $formation)
    {
        $quiz = Quiz::where("payement_type", PayementTypeEnum::FREE)->get();
        return view('admin.formations.update', compact('formation','quiz'));
    }

    function update(FormationRequest $request, Formation $formation)
    {
        $quizzes = $request->select_quizzes;

        if ($request->hasFile('image')) {
            $destination = 'images/' . $formation->image;
            if (File::exists($destination)) {
                File::delete($destination);
            }
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('images/', $filename);
        }

        $formation->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $request->hasFile('image') ? $filename : $formation->image,
            'video' => 'videos/'. $request->video,
        ]);

        $formation->quizzes()->sync($quizzes);

        return  redirect()->back()->with('status', 'Formation updated successfully');

    }

    function delete(Formation $formation)
    {
        $formation->quizzes()->detach();
        $formation->delete();
        return  redirect()->back()->with('status', 'Formation deleted successfully');
    }


    public function show(Formation $formation)
    {
        return view('admin.formations.show', ['formation' => $formation]);
    }

    // show first quiz in the collection
    public function showQuiz($id)
    {
        $formation = Formation::with('quizzes')->findOrFail($id);
        $quiz = $formation->getQuizzesByIndex()->first();
        session(['formation' => $formation]);
        return redirect()->route('quiz', ['slug' => $quiz['quiz']->slug]);
    }

    public function showNextQuiz($id)
    {
        $formation = session('formation');
        $previousQuiz = $formation->getQuiz($id);
        $nextQuiz = $formation->getQuizzesByIndex()->sortBy('index')->filter(function ($item) use ($previousQuiz) {
            return $item['index'] > $previousQuiz['index'];
        })
        ->first();
        if ($nextQuiz)
        {
            return redirect()->route('quiz', ['slug' => $nextQuiz['quiz']->slug]);
        }
            return redirect()->route('formation.index');
    }
}
