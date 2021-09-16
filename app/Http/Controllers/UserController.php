<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserFormRequest;
use App\Models\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    protected $user;
    protected $title;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->title = 'Usuários';
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);

        $title = $this->title;
        $users = $this->user->paginate(10);
        return view('admin.users.index', compact('title','users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', User::class);

        $title = $this->title;
        $subtitle = "Adicionar usuário";
        return view('admin.users.form', compact('title', 'subtitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserFormRequest $request)
    {
        $this->authorize('create', User::class);

        $dataForm = $request->all();
        $user = $this->user->create($dataForm);

        if ($user) {
            return redirect('/usuarios')->with('success', 'Usuário criado com sucesso!');
        } else {
            return redirect('/news')->with('fail', 'Falha ao criar usuário!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = $this->user->findOrFail($id);

        $this->authorize('update', $user);

        $title = $this->title;
        $subtitle = "Adicionar usuário";
        return view('admin.users.form', compact('title', 'subtitle', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserFormRequest $request, $id)
    {
        $user = $this->user->findOrFail($id);
        $this->authorize('update', $user);

        $dataForm = $request->all();

        if ($user->update($dataForm)) {
            return redirect('/usuarios')->with('success', 'Usuário atualizado com sucesso!');
        } else {
            return redirect('/usuarios')->with('fail', 'Falha ao atualizar usuário!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = $this->user->findOrFail($id);
        $this->authorize('update', $user);

        if ($this->user->destroy($id)) {
            return redirect('/usuarios')->with('success', 'Usuário excluído com sucesso!');
        }

        return redirect('/usuarios')->with('fail', 'Falha ao excluir usuário!');
    }
}
