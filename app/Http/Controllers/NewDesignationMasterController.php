<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\NewDesignationMaster;
use Illuminate\Http\Request;

class NewDesignationMasterController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.newDesignationMaster';
        $this->activeSettingMenu = 'new_designation_master';
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
        $this->designations = NewDesignationMaster::all();

        return view('new-designation-master.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('new-designation-master.create');
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

        $designation = new NewDesignationMaster();
        $designation->company_id = company()->id;
        $designation->name = $request->name;

        $designation->save();

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
        $this->designation = NewDesignationMaster::findOrFail($id);
        return view('new-designation-master.edit', $this->data);
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

        $designation = NewDesignationMaster::findOrFail($id);
        $designation->name = $request->name;
        $designation->save();

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
        $designation = NewDesignationMaster::findOrFail($id);
        $designation->delete();

        return Reply::success(__('messages.deleteSuccess'));
    }
}
