<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\NewLeadMainDocument;
use Illuminate\Http\Request;

class MainDocumentController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.mainDocuments';
        $this->activeSettingMenu = 'lead_document_settings';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->mainDocuments = NewLeadMainDocument::all();
        return view('lead-document-settings.create-main-document-modal', $this->data);
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

        $mainDocument = new NewLeadMainDocument();
        $mainDocument->company_id = company()->id;
        $mainDocument->name = $request->name;
        $mainDocument->save();

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
        $this->mainDocument = NewLeadMainDocument::findOrFail($id);
        return view('lead-document-settings.edit-main-document-modal', $this->data);
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

        $mainDocument = NewLeadMainDocument::findOrFail($id);
        $mainDocument->name = $request->name;
        $mainDocument->save();

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
        NewLeadMainDocument::destroy($id);

        return Reply::success(__('messages.deleteSuccess'));
    }
}
