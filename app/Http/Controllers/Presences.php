<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use App\Models\Match;
use App\Models\Player;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\PresenceRequest;
use App\Components\FlashMessages;

class Presences extends Controller
{
    use FlashMessages;

    public $routes = [
        'index'=>'presences',
        'create'=>'presenceCreate',
        'store'=>'presenceStore',
        'show'=>'presenceShow',
        'edit'=>'presenceEdit',
        'update'=>'presenceUpdate',
        'updateConfirm'=>'presenceUpdateConfirm',
        'destroy'=>'presenceDestroy'
    ];

    public $fields = [
        'match_id' => ['type'=>'dataSelect', 'label'=>'Partida', 'enabled'=>'1', 'position'=>4, 'notnull'=>0, 'visible'=>1, 'search'=>'matches',],
        'play_id' => ['type'=>'dataSelect', 'label'=>'Jogador', 'enabled'=>'1', 'position'=>4, 'notnull'=>0, 'visible'=>1, 'search'=>'players'],
        'team_id' => ['type'=>'varchar', 'label'=>'Time', 'enabled'=>'1', 'position'=>4, 'notnull'=>0, 'visible'=>5, 'search'=>'teams'],
        'position' => ['type'=>'varchar', 'label'=>'Posição', 'visible'=>5, 'arraykeyval'=>[1=>'Jogador', 2=>'Goleiro']],
        'confirmed_at' => ['type'=>'datetime', 'label'=>'Data da confirmação', 'enabled'=>'1', 'position'=>2, 'notnull'=>0, 'visible'=>1]
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
                    case 'players':
                        $players = Player::all()->pluck('name', 'id');
                        $result = $players;
                        break;
                    case 'matches':
                        $matches = Match::whereNull('closed_at')->
                        orderby('name', 'ASC')->pluck('name', 'id');
                        $result = $matches;
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
        $data = Presence::all();

        if (count($data) >= 1) {
            foreach ($data as $key => $value) {
                if (!empty($value->confirmed_at)) {
                    $date = explode(' ', $value->confirmed_at);
                    $time = substr($date[1], 0, -3);
                    $date = $date[0];
                    $date = implode('/', array_reverse(explode('-', $date)));
                    $value->confirmed_at_vw = "{$date} {$time}";
                } else { $value->confirmed_at_vw = ''; }
                if ($value->play_id) {
                    $player = Player::where('id', '=', $value->play_id)->select('name','goalkeeper', 'deleted_at')->withTrashed()->get();
                    $value['player'] = $player[0]->name;
                    $value['position'] = $this->fields['position']['arraykeyval'][$player[0]->goalkeeper];
                    if (isSet($player[0]->deleted_at)) $value['play_deleted_at'] = $player[0]->deleted_at;
                } else { $value['player'] = ''; }
                if ($value->match_id) {
                    $match = Match::where('id', '=', $value->match_id)->select('name', 'deleted_at')->withTrashed()->get();
                    $value['match'] = $match[0]->name;
                    if (isSet($match[0]->deleted_at)) $value['match_deleted_at'] = $match[0]->deleted_at;
                } else { $value['match'] = ''; }
                if ($value->team_id) {
                    $team = Team::where('id', '=', $value->team_id)->select('name', 'deleted_at')->withTrashed()->get();
                    $value['team'] = $team[0]->name;
                    if (isSet($team[0]->deleted_at)) $value['team_deleted_at'] = $team[0]->deleted_at;
                } else { $value['team'] = ''; }
                $result[] = $value;
            }
            $data  = $result;
        } else {
            $data = [];
        }

        if ($request->dataReturn == "json") {
            return json_encode(['data' => $data]);
        } else {
            $fields = $this->fields;
            $headTitle = 'Presenças';
            $title = "Lista de participantes da patida";
            $here = 'presence';
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
        $headTitle = 'Presenças';
        $title = "Cadastro de presença na partida";
        $here = 'presence';
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
    public function store(PresenceRequest $request)
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

        $data = Presence::create($request->all());

        if ($data) {
            $created_by = Presence::find($data['id']);
            $created_by->update(['created_by' => Auth::user()->id]);
            $created_by->save();
        }

        $message = 'Presença cirada com sucesso';
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
            return redirect()->route('presences');
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

        $data = Presence::where('id','=',$id)->get();
        $data = (array)json_decode($data[0]);

        $result['name'] = 'Confirmar presença na partida';

        if (!empty($data['confirmed_at'])) {
            $data['confirmed_at'] = str_replace(' ', 'T', $data['confirmed_at']);
        } else {
            $data['confirmed_at'] = date('d/m/Y H:m', time());
        }

        if ($request->dataReturn == "json") {
            $data = [
                'data' => $data,
                'status' => 'success'
            ];
            return $data;
        } else {
            $headTitle = 'Presenças';
            $title = "Editar presença na partida";
            $here = 'presence';
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
    public function update(PresenceRequest $request, $id)
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
            $data = Presence::onlyTrashed()->where('id','=',$id)->get();
            $data[0]->restore();
            $restored_by = Presence::find($id);
            $restored_by->update(['restored_by' => Auth::user()->id]);
            $restored_by->save();
            return redirect()->route('players');
        } elseif ($request->force_delete == 1) {
            $data = Presence::onlyTrashed()->where('id','=',$id)->get();
            $data[0]->forceDelete();
            return redirect()->route('players');
        } else {
            if (isSet($request->confirmed_at)) {
                if ($request->confirmed_at == 'now') {
                    $request->confirmed_at = date('d/m/Y H:m', time());
                }
            }
            $data = Presence::find($id);
            $data->fill($request->all());
            $data->update(['updated_by' => Auth::user()->id]);
            $data->save();

            $message = 'Presença atualizada com sucesso';
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
                return redirect()->route('presences');
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateConfirm(Request $request, $id)
    {
        if (!Auth::user()->admin) {
            $check = Presence::join('players', 'presences.play_id', '=', 'players.id')->where('presences.id', '=', $id)->select('presences.play_id', 'players.user_id')->withTrashed()->get();

            if ($check[0]->user_id <> Auth::user()->id) {
                $message = 'Você só pode confirmar a sua presença.';
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
        }

        $data = Presence::find($id);
        $data->fill([
            'confirmed_at'=>date('Y-m-d H:m:i', time()),
            'updated_by' => Auth::user()->id
        ]);
        $data->save();
        $data['confirmed_at'] = date('Y-m-d H:m', time());
        $data['confirmed_at_vw'] = date('d/m/Y H:m', time());

        $message = 'Presença confirmada com sucesso';
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
            return redirect()->route('presences');
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

        $deleted_by = Presence::find($id);
        $deleted_by->update(['deleted_by' => Auth::user()->id]);
        $deleted_by->save();

        $data = Presence::destroy($id);

        $message = 'Presença apagada com sucesso';
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
            return redirect()->route('presences');
        }
    }
}
