<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class DomainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $domains = DB::table('domains')->select(['id', 'name'])->get();
        return view('domains.index', compact('domains'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $urlValidated =  Validator::make($request->all(), [
            'name' => 'required|active_url'
        ]);

        if ($urlValidated->fails()) {
            flash($urlValidated->errors()->first('name'))->error();
            return redirect('/');
        }

        $uniqueDomain = Validator::make($request->all(), [
            'name' => 'required|unique:domains|max:255'
        ]);

        if ($uniqueDomain->fails()) {

            $name = $request->input('name');
            $id = DB::table('domains')
                ->where('name', $name)
                ->value('id');
            flash($uniqueDomain->errors()->first('name'))->error();
            return redirect()->route('domains.show', $id);
        }

        $nameValidate = Validator::make($request->all(), [
            'name' => 'required|max:255'
        ]);

        if ($nameValidate->passes()) {
            $name = $request->input('name');
            $id = DB::table('domains')->insertGetId(
                [
                    'name' => $name,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]
            );
            flash('Url has been added')->success();
            return redirect()
                ->route('domains.show', $id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $domain = DB::table('domains')->where('id', $id)->first();
        return view('domains.show', compact('domain'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
