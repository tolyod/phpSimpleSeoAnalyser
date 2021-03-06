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
        $domains = DB::table('domains')->select(['id', 'name'])->paginate();
        $lastDomainChecks = DB::table('domain_checks')
                    ->select('domain_id', 'status_code', DB::raw('MAX(domain_checks.updated_at) as last_check'))
                    ->groupBy('domain_id', 'status_code')
                    ->get()
                    ->keyBy('domain_id');
        return view('domains.index', compact('domains', 'lastDomainChecks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        ['name' => $domainName] = $request->validate([
            'name' => 'required|active_url|max:255'
        ]);

        $uniqueDomain = Validator::make(['name' => $domainName], [
            'name' => 'unique:domains'
        ]);

        if ($uniqueDomain->fails()) {
            $id = DB::table('domains')
                ->where('name', $domainName)
                ->value('id');
            flash($uniqueDomain->errors()->first('name'))->info();
            return redirect()->route('domains.show', $id);
        }

        $id = DB::table('domains')->insertGetId([
                'name' => $domainName,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
        ]);
        flash(
            __('flashes.domain.added', ['name' => $domainName])
        )->success();
        return redirect()->route('domains.show', $id);
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
        if (!$domain) {
            abort('404');
        }
        $domain_checks = DB::table('domain_checks')
            ->where('domain_id', '=', $id)
            ->paginate(10);

        return view('domains.show', compact('domain', 'domain_checks'));
    }
}
