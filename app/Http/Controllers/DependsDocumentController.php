<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\NewLeadDependsDocument;
use Illuminate\Http\Request;

class DependsDocumentController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.dependsDocuments';
        $this->activeSettingMenu = 'lead_document_settings';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->dependsDocuments = NewLeadDependsDocument::all();
        return view('lead-document-settings.create-depends-document-modal', $this->data);
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

        $dependsDocument = new NewLeadDependsDocument();
        $dependsDocument->company_id = company()->id;
        $dependsDocument->name = $request->name;
        $dependsDocument->save();

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
        $this->dependsDocument = NewLeadDependsDocument::findOrFail($id);
        return view('lead-document-settings.edit-depends-document-modal', $this->data);
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

        $dependsDocument = NewLeadDependsDocument::findOrFail($id);
        $dependsDocument->name = $request->name;
        $dependsDocument->save();

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
        NewLeadDependsDocument::destroy($id);

        return Reply::success(__('messages.deleteSuccess'));
    }
}
