<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\User;
use App\Services\AgendaService;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function __construct(protected AgendaService $service)
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = User::query()->where('email', $_SESSION['email'])->first();

        if(empty($user)){
            $erro = 'error';
            $mensagem = 'Usuário não encontrado!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/');
        }

        $agenda = $user->agenda()->get();
        
        return view('painel.agenda.index', ['user' => $user, 'model' => $agenda]);
    }

    public function show(Request $request, Agenda $agenda)
    {
        dd($this->service->findOne($agenda->id));
        
        return view('painel.agenda.show', ['model' => $agenda, 'ajax' => $request->ajax()]);
    }

    public function create(Request $request)
    {
        return view('painel.agenda.create', ['model' => new Agenda()]);
    }

    public function store(Request $request)
    {
        $agenda = new Agenda();
        $erro = 'success';
        $mensagem = 'Agenda criada com sucesso!';

        $user = User::query()->where('email', $_SESSION['email'])->first();

        if(empty($user)){
            $erro = 'error';
            $mensagem = 'Usuário não encontrado!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/');
        }

        $arrData = $this->checkData($request);

        if (isset($arrData['error'])) {
            return $arrData;
        }

        $agenda->fill($arrData);
        $agenda->user_id = $user->id;

        if(!$agenda->save()){
            $erro = 'error';
            $mensagem = 'Erro ao salvar agendamento!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/painel/agenda/create');
        }
    
        return redirect()->to('/painel/agenda');
    }

    public function edit(Request $request, Agenda $agenda)
    {
        $agenda->user = $agenda->user ? $agenda->user->toArray() : null;

        return view('painel.agenda.edit', ['model' => $agenda]);
    }

    public function update(Request $request, Agenda $agenda)
    {
        $erro = 'success';
        $mensagem = 'Agendamento atualizado com sucesso!';

        $arrData = $this->checkData($request);

        if (isset($arrData['error'])) {
            return $arrData;
        }

        $agenda->fill($arrData);

        if(!$agenda->save()){
            $erro = 'error';
            $mensagem = 'Erro ao salvar agendamento!';

            session()->flash($erro, $mensagem);
            return redirect()->to('/painel/agenda/edit'); 
        }
    
        return redirect()->to('/painel/agenda');
    }

    public function destroy(Request $request, Agenda $agenda)
    {
        $erro = 'success';
        $mensagem = 'Agendamento removido com sucesso!';

        if($agenda->delete()){
            $erro = 'error';
            $mensagem = 'Não foi possivel remover o agendamento!';
        }

        session()->flash($erro, $mensagem);
        return redirect()->to('/painel/agenda');
    }

    private function checkData(Request $request) {
        $rules = [
            'titulo' => 'required|max:120',
            'data_inicio' => 'required',
            'data_fim' => 'nullable',
            'descricao' => 'required|max:255',
        ];

        $feedback = [
            'titulo.required' => 'O campo titulo é obrigatório!',
            'data_inicio.required' => 'O campo data inicial é obrigatório!',
            'data_inicio.date' => 'O campo data inicial precisa ser do tipo data!',
            'data_fim.date' => 'O campo data final precisa ser do tipo data!',
        ];

        return $request->validate($rules, $feedback);
    }
}
