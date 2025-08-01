<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Main;
use App\Models\User;
use App\Models\Follower;
use App\Models\Firsttime;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Psy\Output\Theme;

class UserController extends Controller
{
    function index($nickname)
    {

        // tutorial do spray
        if (Auth::check()) {

            $first_time = Firsttime::where('user_id', Auth::id())->first();

            if ($first_time) {

                if ($first_time->main === 1) {

                    $first_time->main = 0;

                    $first_time->save();

                    return view('first_time', [
                        'validate' => 'main',
                        'route' => route('user_index', ['nickname' => $nickname])
                    ]);
                }
            }
        }



        // pegar os dados da pessoa que tem aquele nickname
        $db_main = Main::where('user_nickname', $nickname)->first();
        $user = null;
        $themes_foreach = null;
        if (!$db_main) {
            $db_main = null;
        } else {
            // foreach de todos os temas desse usuario!
            $user = User::where('nickname', $db_main->user_nickname)->first();
            $themes_foreach = $user->categories()->get();
        }

        $user_creator = User::where('nickname', $nickname)->first();

        // contando os temas
        $count_theme = Category::where('user_id', $user_creator->id)->get();
        if (!$count_theme) {
            $count_theme = 0;
        }

        // contando os seguidores
        $count_followers = Follower::where('id_creator', $user_creator->id)->get();
        if (!$count_followers) {
            $count_followers = 0;
        }

        // contando quem o usuario segue
        $count_following = Follower::where('id_user', $user_creator->id)->get();
        if (!$count_following) {
            $count_following = 0;
        }

        //verificando se o usuário autenticado já segue aquele criador
        $is_following = Follower::where('id_creator', $user_creator->id)->where('id_user', Auth::id())->first();


        return view('main', [
            'db_main' => $db_main,
            'nickname' => $nickname,
            'themes_foreach' => $themes_foreach,
            'count_theme' => $count_theme,
            'count_followers' => $count_followers,
            'count_following' => $count_following,
            'is_following' => $is_following
        ]);
    }

    function create($nickname)
    {


        // tutorial do spray
        if (Auth::check()) {

            $first_time = Firsttime::where('user_id', Auth::id())->first();

            if ($first_time) {

                if ($first_time->indexcreator === 1) {

                    $first_time->indexcreator = 0;

                    $first_time->save();

                    return view('first_time', [
                        'validate' => 'indexcreator',
                        'route' => route('user_create', ['nickname' => $nickname])
                    ]);
                }
            }
        }

        return view('indexcreator', [
            'nickname' => $nickname
        ]);
    }

    function store(Request $request, $nickname)
    {
        $request->validate([
            'name' => 'required|string|min:1|max:50',
            'subtitle' =>  'nullable|string|max:100',
            'description' => 'required|string|max:255'
        ], [
            'name.min' => 'Tamanho minimo deve ser 1 caractere',
            'name.max' => 'Tamanho máximo de 50 caracteres excedido',
            'name.string' => 'O conteúdo deve ser um texto',
            'subtitle.max' => 'Tamanho máximo de 100 caracteres excedido',
            'subtitle.string' => 'O conteúdo deve ser um texto',
            'description.max' => 'Tamanho máximo de 255 caracteres excedido',
            'description.string' => 'O conteúdo deve ser um texto'
        ]);
        Main::create([
            'name' => $request->name,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'user_id' => Auth::id(),
            'user_nickname' => Auth::user()->nickname
        ]);
        return Redirect::to(route('user_index', ['nickname' => $nickname]))
            ->with('msg-success', 'Pagina Inicial Criada com Sucesso!');
    }

    function edit($nickname)
    {

        // tutorial do spray
        if (Auth::check()) {

            $first_time = Firsttime::where('user_id', Auth::id())->first();

            if ($first_time) {

                if ($first_time->indexeditor === 1) {

                    $first_time->indexeditor = 0;

                    $first_time->save();

                    return view('first_time', [
                        'validate' => 'indexeditor',
                        'route' => route('user_edit', ['nickname' => $nickname])
                    ]);
                }
            }
        }


        // pega os dados da tela inicial
        $main = Main::where('user_id', Auth::id())->first();

        if (!$main) {
            # code...
            return Redirect::to(route('user_index', ['nickname' => $nickname]))
                ->with('msg-warning', "Página Inicial ainda nao criada!");
        }
        return view('indexeditor', [
            'main' => $main,
            'nickname' => $nickname
        ]);
    }

    function update(Request $request, $nickname)
    {
        $main = Main::where('user_id', Auth::id())->first();
        if (!$main) {
            # code...
            return Redirect::to(route('user_index', ['nickname' => $nickname]))
                ->with('msg-warning', "Página Inicial ainda nao criada!");
        }

        // validação dos dados
        $request->validate([
            'name' => 'required|string|min:1|max:50',
            'subtitle' =>  'nullable|string|max:100',
            'description' => 'required|string|max:255'
        ], [
            'name.min' => 'Tamanho minimo deve ser 1 caractere',
            'name.max' => 'Tamanho máximo de 50 caracteres excedido',
            'name.string' => 'O conteúdo deve ser um texto',
            'subtitle.max' => 'Tamanho máximo de 100 caracteres excedido',
            'subtitle.string' => 'O conteúdo deve ser um texto',
            'description.max' => 'Tamanho máximo de 255 caracteres excedido',
            'description.string' => 'O conteúdo deve ser um texto'
        ]);

        $main->name = $request->name ?? $main->name;
        $main->subtitle = $request->subtitle ?? $main->subtitle;
        $main->description = $request->description ?? $main->description;
        $main->save();
        return Redirect::to(route('user_index', ['nickname' => $nickname]))
            ->with('msg-success', 'Página inicial atualizada com sucesso!');
    }

    function editor($nickname)
    {

        // tutorial do spray
        if (Auth::check()) {

            $first_time = Firsttime::where('user_id', Auth::id())->first();

            if ($first_time) {

                if ($first_time->editor === 1) {

                    $first_time->editor = 0;

                    $first_time->save();

                    return view('first_time', [
                        'validate' => 'editor',
                        'route' => route('user_editor', ['nickname' => $nickname])
                    ]);
                }
            }
        }



        // pega o usuario
        $user = User::find(Auth::id());
        // proximo passo, é pegar as categories em si daquele usuario
        $themes_foreach = $user->categories()->get();




        return view('editor', [
            'themes_foreach' => $themes_foreach,
            'nickname' => $nickname
        ]);
    }

    function follow($nickname)
    {

        $user_creator = User::where('nickname', $nickname)->first();
        $id_creator = $user_creator->id;

        //fazendo o sistema de seguir
        $follower = new Follower();
        $follower->id_user = Auth::id();
        $follower->id_creator = $id_creator;
        $follower->save();


        Notification::create([
            'user_id' => $id_creator,
            'name' => '',
            'theme_name' => '',
            'text' => '',
            'status' => 'new_follower',
            'responser_id' => Auth::id(),
            'route' => ''
        ]);

        return Redirect::to(route('user_index', ['nickname' => $nickname]))
            ->with('msg-success', 'agora você está seguindo ' . $nickname . '!');
    }

    function unfollow($nickname)
    {

        $user_creator = User::where('nickname', $nickname)->first();
        $id_creator = $user_creator->id;

        //fazendo o sistema de seguir
        $follower = Follower::where('id_user', Auth::id())->where('id_creator', $id_creator)->first();
        $follower->delete();

        return Redirect::to(route('user_index', ['nickname' => $nickname]))
            ->with('msg-success', 'você deixou de seguir ' . $nickname . '!');
    }

    function follow_by_seguidores($nickname, Request $request)
    {

        $user_creator = User::where('nickname', $nickname)->first();
        $id_creator = $user_creator->id;

        //fazendo o sistema de seguir
        $follower = new Follower();
        $follower->id_user = Auth::id();
        $follower->id_creator = $id_creator;
        $follower->save();


        Notification::create([
            'user_id' => $id_creator,
            'name' => '',
            'theme_name' => '',
            'text' => '',
            'status' => 'new_follower',
            'responser_id' => Auth::id(),
            'route' => ''
        ]);

        return Redirect::to(route('user_followers', ['nickname' => $request->nickname_atual]))
            ->with('msg-success', 'agora você está seguindo ' . $nickname . '!');
    }

    function unfollow_by_seguidores($nickname, Request $request)
    {

        $user_creator = User::where('nickname', $nickname)->first();
        $id_creator = $user_creator->id;

        //fazendo o sistema de seguir
        $follower = Follower::where('id_user', Auth::id())->where('id_creator', $id_creator)->first();
        $follower->delete();

        return Redirect::to(route('user_followers', ['nickname' => $request->nickname_atual]))
            ->with('msg-success', 'você deixou de seguir ' . $nickname . '!');
    }

    function follow_by_seguindo($nickname, Request $request)
    {

        $user_creator = User::where('nickname', $nickname)->first();
        $id_creator = $user_creator->id;

        //fazendo o sistema de seguir
        $follower = new Follower();
        $follower->id_user = Auth::id();
        $follower->id_creator = $id_creator;
        $follower->save();


        Notification::create([
            'user_id' => $id_creator,
            'name' => '',
            'theme_name' => '',
            'text' => '',
            'status' => 'new_follower',
            'responser_id' => Auth::id(),
            'route' => ''
        ]);

        return Redirect::to(route('user_following', ['nickname' => $request->nickname_atual]))
            ->with('msg-success', 'agora você está seguindo ' . $nickname . '!');
    }

    function unfollow_by_seguindo($nickname, Request $request)
    {

        $user_creator = User::where('nickname', $nickname)->first();
        $id_creator = $user_creator->id;

        //fazendo o sistema de seguir
        $follower = Follower::where('id_user', Auth::id())->where('id_creator', $id_creator)->first();
        $follower->delete();

        return Redirect::to(route('user_following', ['nickname' => $request->nickname_atual]))
            ->with('msg-success', 'você deixou de seguir ' . $nickname . '!');
    }

    function remove_from_followers($nickname)
    {
        $user = User::where('nickname', $nickname)->first();
        $id_user = $user->id;

        //fazendo o sistema de seguir
        $follower = Follower::where('id_user', $id_user)->where('id_creator', Auth::id())->first();
        $follower->delete();

        return Redirect::to(route('user_followers', ['nickname' => Auth::user()->nickname]))
            ->with('msg-success', 'você removeu ' . $nickname . ' de seus seguidores!');
    }

    function notifications()
    {

        $notifications = Notification::where('user_id', Auth::id())->orWhere('user_id', 0)->orderBy('created_at', 'desc')->get();
        if (count($notifications) > 0) {
            foreach ($notifications as $key => $value) {
                if ($value->responser_id != 0) {
                    $responser = User::where('id', $value->responser_id)->first();
                    $value->responser_nickname = $responser->nickname;
                    unset($responser);
                }
                if ($value->status == 'new_comment') {
                    $comment = Comment::where('id', $value->name)->first();
                    $user_r = User::where('id', $comment->id_commenter)->first();
                    $comment->author_name = $user_r->nickname;
                    $comment->route = $value->theme_name;
                    $value->comment = $comment;
                }
                if ($value->status == 'new_comment_2') {
                    $comment = Comment::where('id', $value->name)->first();
                    $user_r = User::where('id', $comment->id_commenter)->first();
                    $comment->author_name = $user_r->nickname;
                    $comment->route = $value->theme_name;
                    $value->comment = $comment;
                }
                if ($value->status == 'new_comment_3') {
                    $comment = Comment::where('id', $value->name)->first();
                    $user_r = User::where('id', $comment->id_commenter)->first();
                    $comment->author_name = $user_r->nickname;
                    $comment->route = $value->theme_name;
                    $value->comment = $comment;
                }
            }
        }

        return view('notifications', [
            'notifications' => $notifications
        ]);
    }

    function notifications_destroy(Request $request)
    {
        $notifications = Notification::where('id', $request->id)->first();

        if ($notifications) {
            $notifications->delete();
        } else {
            return Redirect::to(route('notifications'))
                ->with('msg-danger', 'Houve um erro ao processar a solicitação');
        }

        return Redirect::to(route('notifications'))
            ->with('msg-success', 'Notificação excluída com sucesso!');
    }

    function seguidores($nickname)
    {
        if (Auth::check()) {
            $user = User::where('nickname', $nickname)->first();
            $users_foreach = Follower::where('id_creator', $user->id)->get();

            if ($users_foreach) {
                foreach ($users_foreach as $key => $value) {
                    $user = User::where('id', $value->id_user)->first();
                    if ($user) {

                        $main = Main::where('user_id', $value->id_user)->first();
                        $texto_main = "";
                        if ($main) {
                            $texto_main = $main->name;
                            unset($main);
                        }
                        $value->user_nickname = $user->nickname;
                        $value->id = $user->id;
                        $value->title = $texto_main;
                        unset($user);

                        if ($nickname != Auth::user()->nickname) {
                            //verificando se o usuário autenticado já segue aquele criador
                            $is_following = Follower::where('id_creator', $value->id)->where('id_user', Auth::id())->first();

                            if ($is_following) {
                                $value->is_following = true;
                            }
                        } else {

                            $user = User::where('nickname', $nickname)->first();
                            $is_following = Follower::where('id_creator', $value->id)->where('id_user', $user->id)->first();

                            if ($is_following) {
                                $value->is_following = true;
                            }
                        }
                    }
                }
            } else {
                $users_foreach = 0;
            }

            return view('seguidores', [
                'users_foreach' => $users_foreach,
                'nickname' => $nickname
            ]);
        } else {
            return Redirect::to(route('user_index', ['nickname' => $nickname]))
                ->with('msg-danger', "Você mão tem permissão para acessar essa página!");
        }
    }

    function seguindo($nickname)
    {

        if (Auth::check()) {

            $user = User::where('nickname', $nickname)->first();

            $users_foreach = Follower::where('id_user', $user->id)->get();

            if ($users_foreach) {
                foreach ($users_foreach as $key => $value) {
                    $user = User::where('id', $value->id_creator)->first();
                    if ($user) {

                        $main = Main::where('user_id', $value->id_creator)->first();
                        $texto_main = "";
                        if ($main) {
                            $texto_main = $main->name;
                            unset($main);
                        }

                        $value->user_nickname = $user->nickname;
                        $value->id = $user->id;
                        $value->title = $texto_main;
                        unset($user);

                        if ($nickname != Auth::user()->nickname) {
                            //verificando se o usuário autenticado já segue aquele criador
                            $is_following = Follower::where('id_creator', $value->id)->where('id_user', Auth::id())->first();

                            if ($is_following) {
                                $value->is_following = true;
                            }
                        }
                    }
                }
            } else {
                $users_foreach = 0;
            }

            return view('seguindo', [
                'users_foreach' => $users_foreach,
                'nickname' => $nickname
            ]);
        } else {
            return Redirect::to(route('user_index', ['nickname' => $nickname]))
                ->with('msg-danger', "Você mão tem permissão para acessar essa página!");
        }
    }
}
