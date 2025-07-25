{{--
    Importação inicial do projeto.
    Serve pra pegar o layout da página principal, e aplicar em cada view em que será extendido.
--}}

@extends('layouts.main')
@section('title', 'Seguidores')
@section('content')

<div class="container">
    {{-- Verifica se existem usuários --}}
    @if (count($users_foreach) > 0)
    <h2 class="mb-4 text-center">
        Seguidores
    </h2>

    <div class="d-flex flex-column gap-3">
        @foreach ($users_foreach as $f)
        @if(Auth::check() && Auth::user()->nickname != $f->user_nickname)
        <div class="card shadow-sm rounded-3 border-0">
            <div class="card-body d-flex align-items-center justify-content-between flex-wrap">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-user-circle fa-2x me-3 text-primary"></i>
                    <div>
                        <a href="{{ route('user_index', ['nickname' => $f->user_nickname]) }}"
                            class="text-decoration-none text-dark fw-bold fs-5">
                            {{ '@'.$f->user_nickname }}
                        </a>
                        <p class="mb-0 text-muted">
                            {{ $f->title != '' ? $f->title : 'Usuário sem descrição' }}
                        </p>
                    </div>
                </div>

                {{-- Botão de seguir/desseguir (opcional) --}}
                @auth
                @if (Auth::user()->nickname == $nickname)
                <div class="mt-3 mt-md-0">
                    <div class="d-flex gap-2">

                        @if ($f->is_following)
                        <form action="{{ route('unfollow_by_seguidores', ['nickname' => $f->user_nickname]) }}" method="post">
                            @csrf
                            <input type="hidden" name="nickname_atual" value="{{$nickname}}">
                            <button class="btn btn-outline-danger">
                                <i class="fas fa-user-minus me-2"></i>Deixar de Seguir
                            </button>
                        </form>
                        @else
                        <form action="{{ route('follow_by_seguidores', ['nickname' => $f->user_nickname]) }}" method="post">
                            @csrf
                            <input type="hidden" name="nickname_atual" value="{{$nickname}}">
                            <button class="btn btn-outline-primary">
                                <i class="fas fa-user-plus me-2"></i>Seguir
                            </button>
                        </form>
                        @endif

                        <form action="{{ route('remove_from_followers', ['nickname' => $f->user_nickname]) }}" method="post">
                            @csrf
                            <input type="hidden" name="nickname_atual" value="{{$nickname}}">
                            <button class="btn btn-outline-warning">
                                <i class="fas fa-user-plus me-2"></i>Excluir
                            </button>
                        </form>



                    </div>


                </div>
                @else
                @if ($f->is_following)
                <form action="{{ route('unfollow_by_seguidores', ['nickname' => $f->user_nickname]) }}" method="post">
                    @csrf
                    <input type="hidden" name="nickname_atual" value="{{$nickname}}">
                    <button class="btn btn-outline-danger">
                        <i class="fas fa-user-minus me-2"></i>Deixar de Seguir
                    </button>
                </form>
                @else
                <form action="{{ route('follow_by_seguidores', ['nickname' => $f->user_nickname]) }}" method="post">
                    @csrf
                    <input type="hidden" name="nickname_atual" value="{{$nickname}}">
                    <button class="btn btn-outline-primary">
                        <i class="fas fa-user-plus me-2"></i>Seguir
                    </button>
                </form>
                @endif
                @endif
                @endauth
            </div>
        </div>
        @endif
        @endforeach
    </div>

    @else
    <h4 class="text-center text-danger mt-4">Nenhum usuário encontrado.</h4>
    @endif
</div>

@endsection