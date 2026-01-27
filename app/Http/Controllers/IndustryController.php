<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\NewIndustryMaster;
use Illuminate\Http\Request;

class IndustryController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.industry';
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

        return view('industry-settings.create-industry-modal', $this->data);
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

        $industry = new NewIndustryMaster();
        $industry->company_id = company()->id;
        $industry->name = $request->name;
        $industry->save();

        $allIndustries = NewIndustryMaster::where(function ($query) {
            $query->where('company_id', company()->id)
                ->orWhereNull('company_id');
        })->orderBy('name')->get();

        $select = '<option value="">' . __('app.select') . '</option>';
        foreach ($allIndustries as $ind) {
            $select .= '<option value="' . $ind->id . '">' . $ind->name . '</option>';
        }

        return Reply::successWithData(__('messages.recordSaved'), ['optionData' => $select]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->industry = NewIndustryMaster::findOrFail($id);
        return view('industry-settings.edit-industry-modal', $this->data);
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

        $industry = NewIndustryMaster::findOrFail($id);
        $industry->name = $request->name;
        $industry->save();

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
        NewIndustryMaster::destroy($id);
        return Reply::success(__('messages.deleteSuccess'));
    }
}
