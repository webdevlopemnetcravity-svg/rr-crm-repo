<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\NewInstitutionTypeMaster;
use Illuminate\Http\Request;

class NewInstitutionTypeMasterController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.newInstitutionTypeMaster';
        $this->activeSettingMenu = 'new_institution_type_master';
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
        $this->institutionTypes = NewInstitutionTypeMaster::all();

        return view('new-institution-type-master.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('new-institution-type-master.create');
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

        $institutionType = new NewInstitutionTypeMaster();
        $institutionType->company_id = company()->id;
        $institutionType->name = $request->name;

        $institutionType->save();

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
        $this->institutionType = NewInstitutionTypeMaster::findOrFail($id);
        return view('new-institution-type-master.edit', $this->data);
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

        $institutionType = NewInstitutionTypeMaster::findOrFail($id);
        $institutionType->name = $request->name;
        $institutionType->save();

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
        $institutionType = NewInstitutionTypeMaster::findOrFail($id);
        $institutionType->delete();

        return Reply::success(__('messages.deleteSuccess'));
    }
}
