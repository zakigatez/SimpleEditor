<?php

namespace App\Http\Controllers;

use App\Theme;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;


class ThemeController extends Controller
{
    public function index()
    {
        //
    }
    function create()
    {

    }

    public function store()
    {
        $theme = new Theme();

        $validator = Validator::make(\request()->all(), [
            'name' => 'required|unique',
        ]);
        $theme->name = \request()->name;
        $theme->save();
        return response()->json(['success'=>'Record is successfully added'],200);
    }


    public function show($id)
    {
        $theme = Theme::find($id);
        return view("admin","theme");
    }

    public function update(Request $request, Theme $theme)
    {
        //
    }

    public function destroy($id)
    {
        $theme = Theme::find($id);
        $this->destroy();
    }


}
