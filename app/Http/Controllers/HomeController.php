<?php

namespace App\Http\Controllers;

use Harishdurga\LaravelQuiz\Models\QuestionsCategorization;
use Harishdurga\LaravelQuiz\Models\Quiz;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $categories = QuestionsCategorization::all();
        $xValues = [];
        $yValues = [];
        $barColors = [];
        foreach ($categories as $categorie) {
            $xValues[] = $categorie->name;
            $barColors[] = $categorie->color;
            $yValues[] = $categorie->questions->count();
        }

        return view('home', compact('xValues', 'yValues', 'barColors'));
    }
}
