<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Team;
use App\Models\Match;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\TeamRequest;
use App\Components\FlashMessages;

class Teams extends Controller
{
    use FlashMessages;

    public $routes = [
      'index'=>'teams',
      'create'=>'teamCreate',
      'store'=>'teamStore',
      'show'=>'teamShow',
      'edit'=>'teamEdit',
      'update'=>'teamUpdate',
      'destroy'=>'teamDestroy'
    ];

    public $fields = [
      'name' => ['type'=>'varchar', 'label'=>'Nome', 'enabled'=>'1', 'position'=>1, 'notnull'=>0, 'visible'=>1,],
      'match_id' => ['type'=>'dataSelect', 'label'=>'Partida', 'enabled'=>'1', 'position'=>4, 'notnull'=>0, 'visible'=>1, 'search'=>'matches'],
      'attacker' => ['type'=>'dataSelect', 'label'=>'Atacante', 'enabled'=>'1', 'position'=>4, 'notnull'=>0, 'visible'=>1, 'search'=>'players',],
      'midfield_left' => ['type'=>'dataSelect', 'label'=>'Meio de campo esquerdo', 'enabled'=>'1', 'position'=>4, 'notnull'=>0, 'visible'=>1, 'search'=>'players'],
      'midfield_right' => ['type'=>'dataSelect', 'label'=>'Meio de campo direito', 'enabled'=>'1', 'position'=>4, 'notnull'=>0, 'visible'=>1, 'search'=>'players'],
      'wing_left' => ['type'=>'dataSelect', 'label'=>'Ala esquerdo', 'enabled'=>'1', 'position'=>4, 'notnull'=>0, 'visible'=>1, 'search'=>'players'],
      'wing_right' => ['type'=>'dataSelect', 'label'=>'Ala direito', 'enabled'=>'1', 'position'=>4, 'notnull'=>0, 'visible'=>1, 'search'=>'players'],
      'defender' => ['type'=>'dataSelect', 'label'=>'Zagueiro', 'enabled'=>'1', 'position'=>4, 'notnull'=>0, 'visible'=>1, 'search'=>'players'],
      'goalkeeper' => ['type'=>'dataSelect', 'label'=>'Goleiro', 'enabled'=>'1', 'position'=>4, 'notnull'=>0, 'visible'=>1, 'search'=>'players'],
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
                        //fazer JOIN de presence
                        if ($key === 'goalkeeper') {
                            // selecionar players que marcaram presença para a partida
                            $players = Player::where('goalkeeper', '=', 2)->orderBy('name', 'ASC')->pluck('name', 'id');
                        } else {
                            $players = Player::where('goalkeeper', '=', 1)->orderBy('name', 'ASC')->pluck('name', 'id');
                        }
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
        $data = Team::all();

        if (count($data) >= 1) {
            foreach ($data as $key => $value) {
                if ($value->match_id) {
                    $match = Match::where('id', '=', $value->match_id)->select('name', 'deleted_at')->withTrashed()->get();
                    $value['match'] = $match[0]->name;
                    if (isSet($match[0]->deleted_at)) $value['match_deleted_at'] = $match[0]->deleted_at;
                } else { $value['match'] = ''; }
                if ($value->attacker) {
                    $player = Player::where('id', '=', $value->attacker)->select('name', 'deleted_at')->withTrashed()->get();
                    $value['attacker_name'] = $player[0]->name;
                    if (isSet($player[0]->deleted_at)) $value['attacker_deleted_at'] = $player[0]->deleted_at;
                } else { $value['attacker_name'] = ''; }
                if ($value->midfield_left) {
                    $player = Player::where('id', '=', $value->midfield_left)->select('name', 'deleted_at')->withTrashed()->get();
                    $value['midfield_left_name'] = $player[0]->name;
                    if (isSet($player[0]->deleted_at)) $value['midfield_left_deleted_at'] = $player[0]->deleted_at;
                } else { $value['midfield_left_name'] = ''; }
                if ($value->midfield_right) {
                    $player = Player::where('id', '=', $value->midfield_right)->select('name', 'deleted_at')->withTrashed()->get();
                    $value['midfield_right_name'] = $player[0]->name;
                    if (isSet($player[0]->deleted_at)) $value['midfield_right_deleted_at'] = $player[0]->deleted_at;
                } else { $value['midfield_right_name'] = ''; }
                if ($value->wing_left) {
                    $player = Player::where('id', '=', $value->wing_left)->select('name', 'deleted_at')->withTrashed()->get();
                    $value['wing_left_name'] = $player[0]->name;
                    if (isSet($player[0]->deleted_at)) $value['wing_left_deleted_at'] = $player[0]->deleted_at;
                } else { $value['wing_left_name'] = ''; }
                if ($value->wing_right) {
                    $player = Player::where('id', '=', $value->wing_right)->select('name', 'deleted_at')->withTrashed()->get();
                    $value['wing_right_name'] = $player[0]->name;
                    if (isSet($player[0]->deleted_at)) $value['wing_right_deleted_at'] = $player[0]->deleted_at;
                } else { $value['wing_right_name'] = ''; }
                if ($value->defender) {
                    $player = Player::where('id', '=', $value->defender)->select('name', 'deleted_at')->withTrashed()->get();
                    $value['defender_name'] = $player[0]->name;
                    if (isSet($player[0]->deleted_at)) $value['defender_deleted_at'] = $player[0]->deleted_at;
                } else { $value['defender_name'] = ''; }
                if ($value->goalkeeper) {
                    $player = Player::where('id', '=', $value->goalkeeper)->select('name', 'deleted_at')->withTrashed()->get();
                    $value['goalkeeper_name'] = $player[0]->name;
                    if (isSet($player[0]->deleted_at)) $value['goalkeeper_deleted_at'] = $player[0]->deleted_at;
                } else { $value['goalkeeper_name'] = ''; }
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
            $headTitle = 'Times';
            $title = "Lista de times";
            $here = 'team';
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
    public function create()
    {
        #$message = self::message('info', sprintf('Para criar Times utilize a %s%s%s para sortear os times baseados na partida e em quem confirmou a presença para tal.', '<a href="'.route('automations').'">', 'Automatização de tarefas', '</a>'));
        self::message('info', 'Para criar Times utilize a Automatização de tarefas para sortear os times baseados na partida e em quem confirmou a presença para tal.');
        return redirect()->route('autoTeamCreate');

        $result = false;
        $data = false;
        $headTitle = 'Time';
        $title = "Cadastro de time";
        $here = 'team';
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
    public function store(TeamRequest $request)
    {
        $data = Team::create($request->all());

        if ($data) {
            $created_by = Team::find($data['id']);
            $created_by->update(['created_by' => Auth::user()->id]);
            $created_by->save();
        }

        $message = 'Time criado com sucesso';
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
            return redirect()->route('teams');
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
        $message = self::message('warning', 'Operação não permita.');
        return redirect()->route('teams');

        $data = Team::where('id','=',$id)->get();
        $data = (array)json_decode($data[0]);

        $result['name'] = $data['name'];

        if (!empty($data['match_at'])) {
            $data['match_at'] = str_replace(' ', 'T', $data['match_at']);
        }

        if (!empty($data['closed_at'])) {
            $data['closed_at'] = str_replace(' ', 'T', $data['closed_at']);
        }

        if ($request->dataReturn == "json") {
            $data = [
                'data' => $data,
                'status' => 'success'
            ];
            return $data;
        } else {
            $headTitle = 'Time';
            $title = "Editar time";
            $here = 'team';
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
    public function update(TeamRequest $request, $id)
    {
        if ($request->restore == 1) {
            $data = Team::onlyTrashed()->where('id','=',$id)->get();
            $data[0]->restore();
            $restored_by = Team::find($id);
            $restored_by->update(['restored_by' => Auth::user()->id]);
            $restored_by->save();
            return redirect()->route('players');
        } elseif ($request->force_delete == 1) {
            $data = Team::onlyTrashed()->where('id','=',$id)->get();
            $data[0]->forceDelete();
            return redirect()->route('players');
        } else {
            $data = Team::find($id);
            $data->fill($request->all());
            $data->update(['updated_by' => Auth::user()->id]);
            $data->save();

            $message = 'Time atualizado com sucesso';
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
                return redirect()->route('teams');
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
        $deleted_by = Team::find($id);
        $deleted_by->update(['deleted_by' => Auth::user()->id]);
        $deleted_by->save();

        $data = Team::destroy($id);

        $message = 'Time apagado com sucesso';
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
            return redirect()->route('teams');
        }
    }
}
