<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\NewLeadVisaType;
use Illuminate\Http\Request;

class VisaTypeController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.visaType';
        $this->activeSettingMenu = 'visa_type_settings';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->visaTypes = NewLeadVisaType::all();
        return view('visa-type-settings.create-visa-type-modal', $this->data);
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

        $visaType = new NewLeadVisaType();
        $visaType->company_id = company()->id;
        $visaType->name = $request->name;
        $visaType->save();

        $allVisaTypes = NewLeadVisaType::all();

        $select = '';

        foreach($allVisaTypes as $type){
            $select .= '<option value="'.$type->id.'">'.$type->name.'</option>';
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
        $this->visaType = NewLeadVisaType::findOrFail($id);
        return view('visa-type-settings.edit-visa-type-modal', $this->data);
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

        $visaType = NewLeadVisaType::findOrFail($id);
        $visaType->name = $request->name;
        $visaType->save();

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
        NewLeadVisaType::destroy($id);

        return Reply::success(__('messages.deleteSuccess'));
    }
}

