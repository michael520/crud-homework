<?php

namespace App\Http\Controllers;

use App\Models\AccountInfo;
use Illuminate\Http\Request;
use Response;

class AccountInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter');

        if (!empty($filter)) {
            $accountinfo = AccountInfo::sortable()->where('name', 'like', '%'.$filter.'%')->paginate(7);
        } else {
            $accountinfo = AccountInfo::sortable()->paginate(7);
        }

        return view('accountinfo')->with(compact('accountinfo', 'filter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|max:255',
            'name' => 'required|max:255',
            'gender' => 'required|max:255',
            'birthday' => 'required|max:255',
            'email' => 'required|max:255',
        ]);

        $result = AccountInfo::create([
            'username' => strtolower($data['username']),
            'name' => $data['name'],
            'gender' => $data['gender'],
            'birthday' => $data['birthday'],
            'email' => $data['email'],
            'note' => $request['note'],
        ]);

        return Response::json($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = AccountInfo::where(['id' => $id])->first();
        return Response::json($result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $result = AccountInfo::where(['id' => $id])->first();
        return Response::json($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'username' => 'required|max:255',
            'name' => 'required|max:255',
            'gender' => 'required|max:255',
            'birthday' => 'required|max:255',
            'email' => 'required|max:255',
        ]);

        $info = AccountInfo::find($id);
        $info->username = strtolower($request->post('username'));
        $info->name = $request->post('name');
        $info->gender = $request->post('gender');
        $info->birthday = $request->post('birthday');
        $info->email = $request->post('email');
        $info->note = $request->post('note');
        $info->update();

        return Response::json($info);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = AccountInfo::where('id',$id)->delete();
        return Response::json($result);
    }
}
