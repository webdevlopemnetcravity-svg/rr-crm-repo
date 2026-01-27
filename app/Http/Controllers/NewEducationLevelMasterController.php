<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\NewEducationLevelMaster;
use Illuminate\Http\Request;

class NewEducationLevelMasterController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.newEducationLevelMaster';
        $this->activeSettingMenu = 'new_education_level_master';
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
        $this->educationLevels = NewEducationLevelMaster::all();

        return view('new-education-level-master.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('new-education-level-master.create');
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

        $educationLevel = new NewEducationLevelMaster();
        $educationLevel->company_id = company()->id;
        $educationLevel->name = $request->name;

        $educationLevel->save();

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
        $this->educationLevel = NewEducationLevelMaster::findOrFail($id);
        return view('new-education-level-master.edit', $this->data);
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

        $educationLevel = NewEducationLevelMaster::findOrFail($id);
        $educationLevel->name = $request->name;
        $educationLevel->save();

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
        $educationLevel = NewEducationLevelMaster::findOrFail($id);
        $educationLevel->delete();

        return Reply::success(__('messages.deleteSuccess'));
    }
}
