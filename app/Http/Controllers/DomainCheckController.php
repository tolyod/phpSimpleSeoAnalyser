<?php

namespace App\Http\Controllers;

use DiDom\Document;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class DomainCheckController extends Controller
{
    private function buildDomainCheckData($name, $id)
    {
            $httpResponse = Http::get($name);
            $status = $httpResponse->status();
            $body = $httpResponse->body();
            $document = new Document($body);
            $h1Element = $document->first('h1');
            $h1 = optional($h1Element)->text();
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id)
    {
        $domain = DB::table('domains')->find($id);
        try {
            $domainData = $this->buildDomainCheckData($domain->name, $id);
            DB::table('domain_checks')->insert($domainData);
            flash(
                __('flashes.domain.checked', ['name' => $domain->name])
            )->success();
        } catch (ConnectionException | RequestException $cathedException) {
            flash($cathedException->getMessage())->error();
        }
        return redirect()
            ->route('domains.show', $id);
    }
}
