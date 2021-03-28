<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\ContactsModel;
use App\Models\ContactsType;
use App\Models\Message;
use Illuminate\Http\Request;

class Contacts extends Controller
{
    public function general()
    {
        $contactsType = ContactsType::where('type', 'general')->first();
        return view('admin.contacts.contacts-list', [
            'contacts' => ContactsModel::where('contacts_type_id', $contactsType->id)->paginate(15),
            'type' => 'general',
            'menuItem' => 'contactsgeneral'
        ]);
    }

    public function access()
    {
        $contactsType = ContactsType::where('type', 'access')->first();
        return view('admin.contacts.contacts-list', [
            'contacts' => ContactsModel::where('contacts_type_id', $contactsType->id)->paginate(15),
            'type' => 'access',
            'menuItem' => 'contactsaccess'
        ]);
    }

    public function advertising()
    {
        $contactsType = ContactsType::where('type', 'adversting')->first();
        return view('admin.contacts.contacts-list', [
            'contacts' => ContactsModel::where('contacts_type_id', $contactsType->id)->paginate(15),
            'type' => 'advertising',
            'menuItem' => 'contactsadvertising'
        ]);
    }

    public function offers()
    {
        $contactsType = ContactsType::where('type', 'offers')->first();
        return view('admin.contacts.contacts-list', [
            'contacts' => ContactsModel::where('contacts_type_id', $contactsType->id)->paginate(15),
            'type' => 'offers',
            'menuItem' => 'contactsoffers'
        ]);
    }

    public function answer(Request $request)
    {
        $contact = ContactsModel::find($request->post('id'));
        return view('admin.contacts.contacts-answer', [
            'contact' => $contact,
            'menuItem' => 'contacts' . $contact->type->type
        ]);
    }

    public function answerSend(Request $request)
    {
        $message = new Message();
        $message->send($request->post('messenger'), $request->post('chat'), $request->post('text'));
        return redirect(route('contacts-' . $request->post('type')));
    }

    public function delete(Request $request)
    {
        $contact = ContactsModel::find($request->post('id'));
        $type = $contact->type;
        $contact->delete();
        return redirect(route('contacts-' . $type->type));
    }

    public function deleteCheck(Request $request)
    {
        ContactsModel::whereIn('id', json_decode($request->post('data'), true))->delete();
        return redirect(route('contacts-' . $request->post('type')));
    }
}
