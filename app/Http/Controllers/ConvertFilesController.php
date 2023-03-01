<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConvertFilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function PDFtoWord()
    {
        return view('Page_Convert.PDFtoWord');
    }
    public function WordtoPDF()
    {
        return view('Page_Convert.WordtoPDF');
    }
    public function PDFtoExcel()
    {
        return view('Page_Convert.PDFtoExcel');
    }
    public function ExceltoPDF()
    {
        return view('Page_Convert.ExceltoPDF');
    }
    public function PDFtoPPT()
    {
        return view('Page_Convert.PDFtoPPT');
    }
    public function PPTtoPDF()
    {
        return view('Page_Convert.PPTtoPDF');
    }
    public function DownLoadFIle()
    {
        return view('download_file');
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
