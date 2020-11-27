<?php

namespace App\Http\Controllers;

use DiDom\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
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
        flash('domain ' . $domainName . ' has been added')->success();
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
        $domain_checks = DB::table('domain_checks')
            ->where('domain_id', '=', $id)
            ->paginate(10);

        return view('domains.show', compact('domain', 'domain_checks'));
    }

    public function buildDomainCheckData($name, $id)
    {
            $data = Http::get($name);
            $status = $data->status();
            $body = $data->body();
            $document = new Document($body);
            $h1 = $document->has('h1') ? $document->first('h1')->text() : null;
            $keywordsElement = $document->first('meta[name=keywords]');
            $keywords = optional($keywordsElement)->getAttribute('content');
            $descriptionElement = $document->first('meta[name=description]');
            $description = optional($descriptionElement)->getAttribute('content');
            $currentDate = Carbon::now()->toDateTimeString();
            return [
                    'domain_id' => $id,
                    'status_code' => $status,
                    'h1' => $h1,
                    'keywords' => $keywords,
                    'description' => $description,
                    'created_at' => $currentDate,
                    'updated_at' => $currentDate
            ];
    }

    public function check($id)
    {
        $domain = DB::table('domains')->find($id);
        try {
            $domainData = $this->buildDomainCheckData($domain->name, $id);
            DB::table('domain_checks')->insert($domainData);
        } catch (HttpException $err) {
            flash($err->getMessage())->error();
        } catch (Throwable $e) {
            abort(404);
        }
        flash('Site ' . $domain->name . ' checked')->success();
        return redirect()
            ->route('domains.show', $id);
    }
}
