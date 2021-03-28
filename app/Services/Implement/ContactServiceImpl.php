<?php


namespace App\Services\Implement;


use App\Models\ContactsModel;
use App\Services\Contracts\ContactService;

class ContactServiceImpl implements ContactService
{

    function create(array $c): int
    {
        $contact = new ContactsModel();
        $contact->contacts_type_id = $c['contacts_type_id'];
        $contact->users_id = $c['users_id'];
        $contact->text = $c['text'];
        $contact->date = date("Y-m-d");
        $contact->time = date("H:i:s");
        $contact->save();

        return $contact->id;
    }

    function delete(int $id): void
    {
        ContactsModel::where('id', $id)->delete();
    }

    function deleteSeveral(array $ids)
    {
        ContactsModel::whereIn('id', $ids)->delete();
    }

    function getByType(int $type_id)
    {
        return ContactsModel::where('contacts_type_id', $type_id)->get();
    }

    function get(int $id)
    {
        return ContactsModel::find($id);
    }
}
