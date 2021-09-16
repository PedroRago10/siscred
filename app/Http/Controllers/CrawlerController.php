<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CrawlerController extends Controller
{
    protected $expense;
    protected $title;

    public function __construct()
    {
        $this->title = 'Crawler';
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $filename = "https://sp.olx.com.br/imoveis";
        $content = file_get_contents($filename);
        $pattern = '/\<ul id="ad-list"[^>]*>(.*?)\<\/ul\>/is';
        preg_match_all($pattern, $content, $match);

        return view('admin.crawler.index')->with(['olx' => $match]);
    }

}
