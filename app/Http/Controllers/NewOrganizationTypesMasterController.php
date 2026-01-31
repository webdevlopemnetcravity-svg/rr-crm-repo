<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\NewOrganizationTypesMaster;
use Illuminate\Http\Request;

class NewOrganizationTypesMasterController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.newOrganizationTypesMaster';
        $this->activeSettingMenu = 'new_organization_types_master';
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
        $this->organizationTypes = NewOrganizationTypesMaster::all();

        return view('new-organization-types-master.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('new-organization-types-master.create');
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

        $organizationType = new NewOrganizationTypesMaster();
        $organizationType->company_id = company()->id;
        $organizationType->name = $request->name;

        $organizationType->save();

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
        $this->organizationType = NewOrganizationTypesMaster::findOrFail($id);
        return view('new-organization-types-master.edit', $this->data);
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

        $organizationType = NewOrganizationTypesMaster::findOrFail($id);
        $organizationType->name = $request->name;
        $organizationType->save();

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
        $organizationType = NewOrganizationTypesMaster::findOrFail($id);
        $organizationType->delete();

        return Reply::success(__('messages.deleteSuccess'));
    }
}
