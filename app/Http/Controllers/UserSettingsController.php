<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordFormRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserSettingsController extends Controller
{
    protected $user;
    protected $title;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->title = 'Configurações';
        $this->middleware('auth');
    }

    public function edit(){
        $user = User::findOrFail(Auth::user()->id);
        $title = $this->title;
        $subtitle = 'Editar perfil';
        return view('admin.settings.form', compact('user', 'title', 'subtitle'));
    }

    public function update(Request $request)
    {
        $user = User::findOrFail(Auth::user()->id);
        $dataForm = $request->all();
        if ($user->update($dataForm)) {
            return redirect('/relatorios/geral')->with('success', 'Usuário atualizado com sucesso!');
        } else {
            return redirect('/relatorios/geral')->with('fail', 'Falha ao atualizar usuário!');
        }
    }

    public function editPass(){
        $title = $this->title;
        $subtitle = 'Trocar senha';
        $user = User::findOrFail(Auth::user()->id);
        return view('admin.settings.change-password', compact('user','title','subtitle'));
    }

    public function updatePass(PasswordFormRequest $request){
        $user = User::findOrFail(Auth::user()->id);
        $dataForm = $request->all();
        $user->password = $dataForm['password'];
        if($user->save()){
            return redirect('/relatorios/geral')->with('success', 'Senha atualizada com sucesso!');
        }else{
            return redirect('/relatorios/geral')->with('fail', 'Falha ao atualizar a senha');
        }
    }
}
