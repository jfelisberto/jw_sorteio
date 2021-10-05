<?php

namespace App\Http\Controllers;

use App\Models\Nivel;
use App\Models\Player;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\PlayerRequest;
use App\Components\FlashMessages;

class Players extends Controller
{
    use FlashMessages;
    public $routes = [
        'index'=>'players',
        'create'=>'playerCreate',
        'store'=>'playerStore',
        'show'=>'playerShow',
        'edit'=>'playerEdit',
        'update'=>'playerUpdate',
        'destroy'=>'playerDestroy'
    ];

    public $fields = [
        'user_id' => ['type'=>'dataSelect', 'label'=>'Usuário', 'enabled'=>'1', 'position'=>4, 'notnull'=>1, 'visible'=>0, 'search'=>'users'],
        'name' => ['type'=>'varchar', 'label'=>'Nome de jogador', 'enabled'=>'1', 'position'=>1, 'notnull'=>1, 'visible'=>1],
        'nivel_id' => ['type'=>'select', 'label'=>'Nível de jogabilidade', 'enabled'=>'1', 'position'=>2, 'arraykeyval'=>[1=>'Pereba', 2=>'Ruim', 3=>'Normal', 4=>'Bom', 5=>'Craque'], 'notnull'=>1, 'visible'=>1],
        'goalkeeper' => ['type'=>'select', 'label'=>'Posição', 'enabled'=>'1', 'position'=>3, 'arraykeyval'=>[1=>'Jogador', 2=>'Goleiro'], 'notnull'=>1, 'visible'=>1],
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $routes = &$routes;
        $fields = &$fields;
    }

    /**
     * Proccess Fields to generate array
     *
     * @return array
     */
    public function process_fields($fields) {
        foreach ($fields as $key => $value) {
            if ($value['type'] == 'datalist' || $value['type'] == 'dataSelect') {
                switch ($value['search']) {
                    case 'users':
                        $users  = User::whereNull('play_id')->
                        orderby('name', 'ASC')->pluck('name', 'id');
                        $result = $users;
                        break;
                }
                if ($value['type'] == 'dataSelect') {
                    $value['arraykeyval'] = $result;
                } else {
                    $value = $result;
                }
            }
            $list[$key] = $value;
        }
        return $list;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Player::all();

        if (count($data) >= 1) {
            foreach ($data as $key => $value) {
                if ($value->user_id) {
                    $user = User::where('id', '=', $value->user_id)->select('name', 'deleted_at')->withTrashed()->get();
                    $value['username'] = $user[0]->name;
                    if (isSet($user[0]->deleted_at)) $value['user_deleted_at'] = $user[0]->deleted_at;
                } else { $value['username'] = ''; }
                if ($value->nivel_id) {
                    $value['nivel_data'] = $this->fields['nivel_id']['arraykeyval'][$value->nivel_id];
                } else { $value['nivel_data'] = ''; }
                if ($value->goalkeeper) {
                    $value['position'] = $this->fields['goalkeeper']['arraykeyval'][$value->goalkeeper];
                } else { $value['position'] = ''; }
                $result[] = $value;
            }
            $data  = $result;
        } else {
            $data = [];
        }

        if ($request->dataReturn == "json") {
            return json_encode(['data'=>$data]);
        } else {
            $fields = $this->fields;
            $headTitle = 'Jogadores';
            $title = "Lista de joadores";
            $here = 'player';
            $routes = $this->routes;
            $newReg = true;
            return view('common_pages.ajax_index', compact(['headTitle','title','here','routes','fields','data','newReg']));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!Auth::user()->admin) {
            $message = 'Você não tem acesso para acessar esta area.';
            $status = 'error';
            if ($request->dataReturn == "json") {
                $data = [
                    'data' => false,
                    'message' => $message,
                    'status' => $status
                ];
                return $data;
            } else {
                self::message('danger', $message);
                return redirect()->route('dashboard');
            }
        }

        $result = false;
        $data = false;
        $headTitle = 'Jogador';
        $title = "Cadastro de jogador";
        $here = 'player';
        $routes = $this->routes;
        $method = false;
        $fields = $this->process_fields($this->fields);

        return view('common_pages.create', compact(['headTitle','title','here','routes','fields','method','result','data']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PlayerRequest $request)
    {
        if (!Auth::user()->admin) {
            $message = 'Você não tem acesso para acessar esta area.';
            $status = 'error';
            if ($request->dataReturn == "json") {
                $data = [
                    'data' => false,
                    'message' => $message,
                    'status' => $status
                ];
                return $data;
            } else {
                self::message('danger', $message);
                return redirect()->route('dashboard');
            }
        }

        $data = Player::create($request->all());

        if ($data) {
            $created_by = Player::find($data['id']);
            $created_by->update(['created_by' => Auth::user()->id]);
            $created_by->save();

            $user = User::find($request->user_id);
            $user->fill(['play_id' => $data['id']]);
            $user->save();
        }

        $message = 'Jogador cadastrado com sucesso';
        $status = 'success';
        if ($request->dataReturn == "json") {
            $data = [
                'data' => $data,
                'message' => $message,
                'status' => $status
            ];
            return $data;
        } else {
            self::message($status, $message);
            return redirect()->route('players');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (!Auth::user()->admin) {
            $message = 'Você não tem acesso para acessar esta area.';
            $status = 'error';
            if ($request->dataReturn == "json") {
                $data = [
                    'data' => false,
                    'message' => $message,
                    'status' => $status
                ];
                return $data;
            } else {
                self::message('danger', $message);
                return redirect()->route('dashboard');
            }
        }

        $data = Player::where('id','=',$id)->get();
        $data = (array)json_decode($data[0]);

        $result = User::where('id','=',$data['user_id'])->get();
        $result = (array)json_decode($result[0]);
        #$result = false;

        if ($request->dataReturn == "json") {
            $data = [
                'data' => $data,
                'status' => 'success'
            ];
            return $data;
        } else {
            $headTitle = 'Jogador';
            $title = "Editar jogador";
            $here = 'player';
            $routes = $this->routes;
            $method = 'PUT';
            $fields = $this->process_fields($this->fields);

            return view('common_pages.edit', compact(['headTitle','title','here','routes','fields','method','data','result']));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PlayerRequest $request, $id)
    {
        if (!Auth::user()->admin) {
            $message = 'Você não tem acesso para acessar esta area.';
            $status = 'error';
            if ($request->dataReturn == "json") {
                $data = [
                    'data' => false,
                    'message' => $message,
                    'status' => $status
                ];
                return $data;
            } else {
                self::message('danger', $message);
                return redirect()->route('dashboard');
            }
        }

        if ($request->restore == 1) {
            $data = Player::onlyTrashed()->where('id','=',$id)->get();
            $data[0]->restore();
            $restored_by = Player::find($id);
            $restored_by->update(['restored_by' => Auth::user()->id]);
            $restored_by->save();
            return redirect()->route('players');
        } elseif ($request->force_delete == 1) {
            $data = Player::onlyTrashed()->where('id','=',$id)->get();
            $data[0]->forceDelete();
            return redirect()->route('players');
        } else {
            $data = Player::find($id);
            $data->fill($request->all());
            $data->update(['updated_by' => Auth::user()->id]);
            $data->save();

            $message = 'Jogador atrualizado com sucesso';
            $status = 'success';
            if ($request->dataReturn == "json") {
                $data = [
                    'data' => $data,
                    'message' => $message,
                    'status' => $status
                ];
                return $data;
            } else {
                self::message($status, $message);
                return redirect()->route('players');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if (!Auth::user()->admin) {
            $message = 'Você não tem acesso para acessar esta area.';
            $status = 'error';
            if ($request->dataReturn == "json") {
                $data = [
                    'data' => false,
                    'message' => $message,
                    'status' => $status
                ];
                return $data;
            } else {
                self::message('danger', $message);
                return redirect()->route('dashboard');
            }
        }

        $deleted_by = Player::find($id);
        $deleted_by->update(['deleted_by' => Auth::user()->id]);
        $deleted_by->save();

        $data = Player::destroy($id);

        $message = 'Jogador apagado com sucesso';
        $status = 'success';
        if ($request->dataReturn == "json") {
            $data = [
                'data' => $id,
                'message' => $message,
                'status' => $status
            ];
            return $data;
        } else {
            self::message($status, $message);
            return redirect()->route('players');
        }
    }
}
