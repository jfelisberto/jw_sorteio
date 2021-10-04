<?php

namespace App\Http\Controllers;

use App\Models\Match;
use App\Models\Nivel;
use App\Models\Player;
use App\Models\Presence;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Components\FlashMessages;

class Automations extends Controller
{
    use FlashMessages;

    public $routes = [
        'index'=>'automations',
        'storePresence'=>'autoPresenceStore',
        'storeTeam'=>'autoTeamStore',
        'createPresence'=>'autoPresenceCreate',
        'createTeam'=>'autoTeamCreate'
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
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $matches = Match::whereNull('closed_at')->
        orderby('name', 'ASC')->pluck('name', 'id');
        $data = false;
        $fields = false;
        $headTitle = 'Automatizar tarefas';
        $title = "Tarefas";
        $here = 'automate';
        $routes = $this->routes;

        return view('automate.index', compact(['headTitle','title','here','routes','fields','data']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createPresence()
    {
        $matches = Match::whereNull('closed_at')->
        orderby('name', 'ASC')->pluck('name', 'id');

        $fields = [
          'matchesPresence' => [
            'match_id' => ['type'=>'dataSelect', 'label'=>'Partidas', 'enabled'=>'1', 'position'=>1, 'notnull'=>0, 'visible'=>0, 'arraykeyval'=>$matches],
          ],
        ];

        $headTitle = 'Automatizar tarefas';
        $title = "Lista de presença";
        $here = 'automate';
        $step = 'presences';
        $routes = $this->routes;

        return view('automate.create', compact(['headTitle','title','here','step','routes','fields']));
    }

    /**
     * automationPresence a newly created resource presences in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function automationPresence(Request $request)
    {
        $request->validate([
            'match_id' => 'required',
        ]);

        $match = Match::where('id', '=', $request->match_id)->select('name')->get();
        $match = json_decode($match[0]);

        $data = Player::all()->pluck('name','id');
        foreach ($data as $key => $value) {
            $presence = Presence::where('play_id', '=', $key)->where('match_id', '=', $request->match_id)->get();
            if (count($presence) == 0) {
                $fieldSetData = [
                    'match_id' => $request->match_id,
                    'play_id' => $key,
                    'created_by' => Auth::user()->id,
                ];

                $insert = Presence::create($fieldSetData);
            }
            unset($fieldSetData);
            unset($insert);
        }

        $message = "Convites para partida {$match->name} gerados com sucesso";
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createTeam()
    {

        $matches = Match::whereNull('created_team_at')->whereNull('closed_at')->
        orderby('name', 'ASC')->pluck('name', 'id');

        $fields = [
          'matchesTeam' => [
            'match_id' => ['type'=>'dataSelect', 'label'=>'Partidas', 'enabled'=>'1', 'position'=>1, 'notnull'=>0, 'visible'=>0, 'arraykeyval'=>$matches],
            'numPlayer' => ['type'=>'number', 'label'=>'Quantidade de jogadores por time.', 'enabled'=>1, 'position'=>2, 'notnull'=>0, 'visible'=>0, 'min'=>3, 'max'=>6],
          ],
        ];

        $headTitle = 'Automatizar tarefas';
        $title = "Sorteio de times";
        $here = 'automate';
        $step = 'teams';
        $routes = $this->routes;

        return view('automate.create', compact(['headTitle','title','here','step','routes','fields']));
    }

    /**
     * automationTeam a newly created resource teams in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function automationTeam(Request $request)
    {
        $validation = $request->validate([
            'match_id' => 'required',
            'numPlayer' => 'required',
        ]);

        $error = false;
        // Adicionando um goleiro para o time
        $numPlayer = ($request->numPlayer + 1);

        if ($request->numPlayer > 7) {
            $error = true;
            $message = "A quantidade de jogadores por time não pode ser maior do que 7, quantidade informada ({$request->numPlayer}). Obs.: Um goleiro será adcionado a quantidade de jogadores informado.";
        }

        if ($request->numPlayer < 3) {
            $error = true;
            $message = "A quantidade de jogadores por time não pode ser menor do que 3, quantidade informada ({$request->numPlayer}). Obs.: Um goleiro será adcionado a quantidade de jogadores informado.";
        }

        $qntPlayer = $numPlayer * 2;
        $match = Match::where('id', '=', $request->match_id)->select('name')->get();
        $match = json_decode($match[0]);

        $players = Presence::join('players', 'presences.play_id', '=', 'players.id')->
        leftjoin('nivels as n', function($join) {
            $join->on('n.id', '=', 'players.nivel_id');
        })->
        whereNotNull('presences.confirmed_at')->
        whereNull('presences.team_id')->
        where('presences.match_id', '=', $request->match_id)->
        select('presences.play_id','presences.id','presences.confirmed_at','players.name as jogador','players.nivel_id', 'n.name as nivel','players.goalkeeper','players.user_id')->get();
        $players = json_decode($players);

        #print "match_id: {$request->match_id} - {$match->name}<br />numPlayer: enviados-({$request->numPlayer}) +goleiro-{$numPlayer}<br />Confirmados: ".count($players)."<br />Jogadores: {$qntPlayer}<br />";
        #print "<pre>";print_r($players);print "</pre>";

        $teamsFormade = (count($players) / $numPlayer);
        $aroundDownTeams = intval($teamsFormade);
        $aroundUpTeams = ceil($teamsFormade);
        #print "Times a formar: {$teamsFormade} ({$aroundDownTeams}) [{$aroundUpTeams}]<br />";
        if (count($players) < $qntPlayer) {
            $message = "Não é possível realizar o sorteio pois a quantidade de jogadores confirmados para a partida '{$match->name}' não é o susficiente para formar no mínimo dois times";
            $error = true;
        }

        if (!$error) {
            $numPlayers = 0;
            $numGoals = 0;
            foreach ($players as $key => $value) {
                // checa a quantidade de jogadores/goleiros para impedir o sorteio
                if ($value->goalkeeper == 2) {
                    $numGoals++;
                    $goalkeepers[] = $value;
                } else {
                    $numPlayers++;
                    $playersline[] = $value;
                }
            }
            #print "Jogadores numPlayers: {$numPlayers} | Goleiros numGoals: {$numGoals}<br />";
            // se nao tem goleiros suficiente para os times nao faz o sorteio
            if ($numGoals < $aroundUpTeams) {
                $message = "Não é possível realizar o sorteio da partida '{$match->name}', pois não há goleiros sufucientes para formar {$aroundUpTeams} times. Há ({$numPlayers}) Jogadores e ({$numGoals}) Goleiros confirmados.";
                $error = true;
            }
        }

        if ($error) {
            $msg_level = 'danger';
            self::message($msg_level, $message);
            return redirect()->route('autoTeamCreate');
        } else {

            // Sorteado os jogadores para formar times
            $list=[];
            $playSelect = array_rand($playersline, $numPlayers);
            #print "Qtd de Craques: ".array_count_values($playSelect);
            #print "Jogadores sorteados<pre>";print_r($playSelect);print '</pre>';
            foreach ($playSelect as $key => $value) {
                $nivel[$value] = $playersline[$value]->nivel;
                $list[] = [
                    'id' => $playersline[$value]->id,
                    'play_id' => $playersline[$value]->play_id,
                    'jogador' => $playersline[$value]->jogador,
                    'nivel_id' => $playersline[$value]->nivel_id,
                    'nivel' => $playersline[$value]->nivel,
                    'goalkeeper' => $playersline[$value]->goalkeeper
                ];
            }
            #print "Jogadores sorteados: nivel<pre>";print_r($nivel);print '</pre>';
            $playList = count($list);
            #print "Jogadores escolhidos<pre>";print_r($list);print '</pre>';

            // distruibindo os jogadores
            $playTeam = array_chunk($list, ($numPlayer - 1));
            // definindo a quantidade de times
            $teams = count($playTeam);
            #print "Jogadores por time (".($numPlayer - 1).") Times [{$teams}]<pre>";print_r($playTeam);print '</pre>';

            // seleciona os goleiros para os times
            $list=[];
            $goalSelect = array_rand($goalkeepers, $teams);
            #print "goalSelect<pre>";print_r($goalSelect);print '</pre>';
            foreach ($goalSelect as $key => $value) {
                $list[] = [
                    'id' => $goalkeepers[$value]->id,
                    'play_id' => $goalkeepers[$value]->play_id,
                    'jogador' => $goalkeepers[$value]->jogador,
                    'nivel_id' => $goalkeepers[$value]->nivel_id,
                    'nivel' => $goalkeepers[$value]->nivel,
                    'goalkeeper' => $goalkeepers[$value]->goalkeeper
                ];
            }
            $goalTeam = $list;
            #print "goalTeam jogadores(1) Times [{$teams}]<pre>";print_r($goalTeam);print '</pre>';

            // definindo os times
            $list=[];
            $i = 0;
            for ($t=1; $t <= $teams; $t++) {
                $list[$i]['name'] = "Time {$t}";
                $list[$i]['match_id'] = $request->match_id;
                $list[$i]['attacker'] = isSet($playTeam[$i][0]) ? $playTeam[$i][0]['play_id'] : false;
                $list[$i]['midfield_left'] = isSet($playTeam[$i][1]) ? $playTeam[$i][1]['play_id'] : false;
                $list[$i]['wing_left'] = isSet($playTeam[$i][2]) ? $playTeam[$i][2]['play_id'] : false;
                $list[$i]['defender'] = isSet($playTeam[$i][3]) ? $playTeam[$i][3]['play_id'] : false;
                $list[$i]['midfield_right'] = isSet($playTeam[$i][4]) ? $playTeam[$i][4]['play_id'] : false;
                $list[$i]['wing_right'] = isSet($playTeam[$i][5]) ? $playTeam[$i][5]['play_id'] : false;
                $list[$i]['goalkeeper'] = $goalTeam[$i]['play_id'];
                $list[$i]['created_by'] = Auth::user()->id;
                $i++;
            }
            $teams = $list;
            #print "Times <pre>";print_r($teams);print '</pre>';
            // salvando os times
            foreach ($teams as $key => $value) {
                #print "{$value['name']}<br />";
                print "value<pre>";print_r($value);print '</pre>';
                $data = Team::create($value);
                #$data['id'] = ($key + 1);
                if ($data) {
                    // setar para cada jogador presente o ID do time team_id
                    if (isSet($value['attacker'])) {
                        if (!empty($value['attacker'])) {
                            $presenceData = Presence::find($value['attacker']);
                            $presenceFields = [
                                'team_id' => $data['id'],
                                'updated_by' => Auth::user()->id,
                            ];
                            $presenceDbg[$key]['attacker'] = $presenceFields;
                            $presenceDbg[$key]['attacker']['play_id'] = $value['attacker'];
                            $presenceData->fill($presenceFields);
                            $presenceData->save();
                        }
                    }
                    if (isSet($value['midfield_left'])) {
                        if (!empty($value['midfield_left'])) {
                            $presenceData = Presence::find($value['midfield_left']);
                            $presenceFields = [
                                'team_id' => $data['id'],
                                'updated_by' => Auth::user()->id,
                            ];
                            $presenceDbg[$key]['midfield_left'] = $presenceFields;
                            $presenceDbg[$key]['midfield_left']['play_id'] = $value['midfield_left'];
                            $presenceData->fill($presenceFields);
                            $presenceData->save();
                        }
                    }
                    if (isSet($value['wing_left'])) {
                        if (!empty($value['wing_left'])) {
                            $presenceData = Presence::find($value['wing_left']);
                            $presenceFields = [
                                'team_id' => $data['id'],
                                'updated_by' => Auth::user()->id,
                            ];
                            $presenceDbg[$key]['wing_left'] = $presenceFields;
                            $presenceDbg[$key]['wing_left']['play_id'] = $value['wing_left'];
                            $presenceData->fill($presenceFields);
                            $presenceData->save();
                        }
                    }
                    if (isSet($value['defender'])) {
                        if (!empty($value['defender'])) {
                            $presenceData = Presence::find($value['defender']);
                            $presenceFields = [
                                'team_id' => $data['id'],
                                'updated_by' => Auth::user()->id,
                                'play_id'=>$value['defender']
                            ];
                            $presenceDbg[$key]['defender'] = $presenceFields;
                            $presenceDbg[$key]['defender']['play_id'] = $value['defender'];
                            $presenceData->fill($presenceFields);
                            $presenceData->save();
                        }
                    }
                    if (isSet($value['midfield_right'])) {
                        if (!empty($value['midfield_right'])) {
                            $presenceData = Presence::find($value['midfield_right']);
                            $presenceFields = [
                                'team_id' => $data['id'],
                                'updated_by' => Auth::user()->id,
                            ];
                            $presenceDbg[$key]['midfield_right'] = $presenceFields;
                            $presenceDbg[$key]['midfield_right']['play_id'] = $value['midfield_right'];
                            $presenceData->fill($presenceFields);
                            $presenceData->save();
                        }
                    }
                    if (isSet($value['wing_right'])) {
                        if (!empty($value['wing_right'])) {
                            $presenceData = Presence::find($value['wing_right']);
                            $presenceFields = [
                                'team_id' => $data['id'],
                                'updated_by' => Auth::user()->id,
                            ];
                            $presenceDbg[$key]['wing_right'] = $presenceFields;
                            $presenceDbg[$key]['wing_right']['play_id'] = $value['wing_right'];
                            $presenceData->fill($presenceFields);
                            $presenceData->save();
                        }
                    }
                    if (isSet($value['goalkeeper'])) {
                        if (!empty($value['goalkeeper'])) {
                            $presenceData = Presence::find($value['goalkeeper']);
                            $presenceFields = [
                                'team_id' => $data['id'],
                                'updated_by' => Auth::user()->id,
                            ];
                            $presenceDbg[$key]['goalkeeper'] = $presenceFields;
                            $presenceDbg[$key]['goalkeeper']['play_id'] = $value['goalkeeper'];
                            $presenceData->fill($presenceFields);
                            $presenceData->save();
                        }
                    }
                }
            }
            #print "presenceFields<pre>";print_r($presenceDbg);print '</pre>';
            // setar a partida como sorteada created_team_at
            $matchData = Match::find($request->match_id);
            $matchFields = [
                'created_team_at' => date('Y-m-d H:m:i', time()),
                'updated_by' => Auth::user()->id
            ];
            $matchData->fill($matchFields);
            $matchData->save();
            #print "matchFields<pre>";print_r($matchFields);print '</pre>';

            $message = "Times para partida {$match->name} sorteados com sucesso";
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
}
