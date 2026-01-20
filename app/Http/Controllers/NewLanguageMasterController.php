<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\NewLanguageMaster;
use Illuminate\Http\Request;

class NewLanguageMasterController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.newLanguageMaster';
        $this->activeSettingMenu = 'new_language_master';
        $this->middleware(function ($request, $next) {
            abort_403(!in_array('admin', user_roles()));
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->languages = NewLanguageMaster::all();

        return view('new-language-master.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('new-language-master.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $language = new NewLanguageMaster();
        $language->company_id = company()->id;
        $language->name = $request->name;

        $language->save();

        return Reply::success(__('messages.recordSaved'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->language = NewLanguageMaster::findOrFail($id);
        return view('new-language-master.edit', $this->data);
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
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $language = NewLanguageMaster::findOrFail($id);
        $language->name = $request->name;
        $language->save();

        return Reply::success(__('messages.updateSuccess'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $language = NewLanguageMaster::findOrFail($id);
        $language->delete();

        return Reply::success(__('messages.deleteSuccess'));
    }
}
