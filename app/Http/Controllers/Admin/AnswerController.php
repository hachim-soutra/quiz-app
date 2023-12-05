<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Http\Requests\StoreAnswerRequest;
use App\Http\Requests\UpdateAnswerRequest;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index(Request $request)
    {
        $answers = Answer::whereNotNull('nbr_of_correct')->with('quiz')->latest()->get();


        return view('admin.answer.index', compact('answers'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function deletedAnswers(Request $request)
    {
        $search = $request->input('search');
        $answers = Answer::onlyTrashed()->with('quiz')->latest()
        ->Where(function ($q) use ($search){
            $q->where('email','like', "%{$search}%")
            ->orWhereHas('quiz', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            });
        })
        ->paginate(10);
        return view('admin.deleted_answers.index',compact('answers', 'search'));

    }
    public function restoreAnswer(string $id)
    {
         Answer::withTrashed()->find($id)->restore();

        return redirect()->back()->with('status','deleted answer has been restored');

    }

    public function permanentDelete(string $id)
    {
        $item = Answer::withTrashed()->find($id);
        $item->forceDelete();
        return redirect()->back()->with('status','deleted answer has been permanently deleted');
    }

    public function destroy(Request $request)
    {

        $answer = Answer::whereIn('id', $request->item)->get();
        foreach($answer as $item) {
            $item->delete();
        };
        return response()->json(['message'=>'answers deleted Successfully'],200);
    }

}
