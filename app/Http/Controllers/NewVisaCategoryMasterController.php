<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\NewVisaCategoryMaster;
use Illuminate\Http\Request;

class NewVisaCategoryMasterController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.newVisaCategoryMaster';
        $this->activeSettingMenu = 'new_visa_category_master';
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
        $this->categories = NewVisaCategoryMaster::all();

        return view('new-visa-category-master.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('new-visa-category-master.create');
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

        $category = new NewVisaCategoryMaster();
        $category->company_id = company()->id;
        $category->name = $request->name;

        $category->save();

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
        $this->category = NewVisaCategoryMaster::findOrFail($id);
        return view('new-visa-category-master.edit', $this->data);
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

        $category = NewVisaCategoryMaster::findOrFail($id);
        $category->name = $request->name;
        $category->save();

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
        $category = NewVisaCategoryMaster::findOrFail($id);
        $category->delete();

        return Reply::success(__('messages.deleteSuccess'));
    }
}
