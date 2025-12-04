<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\NewLeadSubclass;
use App\Models\NewLeadVisaType;
use Illuminate\Http\Request;

class SubclassController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.subclass';
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
        return view('visa-type-settings.create-subclass-modal', $this->data);
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
            'visa_type_id' => 'required|exists:new_lead_visa_type,id',
            'name' => 'required|string|max:255'
        ]);

        $subclass = new NewLeadSubclass();
        $subclass->company_id = company()->id;
        $subclass->visa_type_id = $request->visa_type_id;
        $subclass->name = $request->name;
        $subclass->save();

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
        $this->subclass = NewLeadSubclass::findOrFail($id);
        $this->visaTypes = NewLeadVisaType::all();
        return view('visa-type-settings.edit-subclass-modal', $this->data);
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
            'visa_type_id' => 'required|exists:new_lead_visa_type,id',
            'name' => 'required|string|max:255'
        ]);

        $subclass = NewLeadSubclass::findOrFail($id);
        $subclass->visa_type_id = $request->visa_type_id;
        $subclass->name = $request->name;
        $subclass->save();

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
        NewLeadSubclass::destroy($id);

        return Reply::success(__('messages.deleteSuccess'));
    }
}

