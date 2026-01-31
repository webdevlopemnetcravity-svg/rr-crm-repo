<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\NewSectorMaster;
use App\Models\NewIndustryMaster;
use Illuminate\Http\Request;

class SectorController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.sector';
        $this->activeSettingMenu = 'industry_settings';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->industries = NewIndustryMaster::where(function ($query) {
            $query->where('company_id', company()->id)
                ->orWhereNull('company_id');
        })->orderBy('name')->get();

        return view('industry-settings.create-sector-modal', $this->data);
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
            'industry_id' => 'required|exists:new_industry_master,id',
            'name' => 'required|string|max:255'
        ]);

        $sector = new NewSectorMaster();
        $sector->company_id = company()->id;
        $sector->industry_id = $request->industry_id;
        $sector->name = $request->name;
        $sector->save();

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
        $this->sector = NewSectorMaster::findOrFail($id);
        $this->industries = NewIndustryMaster::where(function ($query) {
            $query->where('company_id', company()->id)
                ->orWhereNull('company_id');
        })->orderBy('name')->get();

        return view('industry-settings.edit-sector-modal', $this->data);
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
            'industry_id' => 'required|exists:new_industry_master,id',
            'name' => 'required|string|max:255'
        ]);

        $sector = NewSectorMaster::findOrFail($id);
        $sector->industry_id = $request->industry_id;
        $sector->name = $request->name;
        $sector->save();

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
        NewSectorMaster::destroy($id);
        return Reply::success(__('messages.deleteSuccess'));
    }
}
