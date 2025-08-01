{{--
    Importação inicial do projeto.
    Serve pra pegar o layout da página principal, e aplicar em cada view em que será extendido.
--}}
@extends('layouts.main')
@section('title', 'Editando o item '.$title)
@section('content')
<div class="container py-4">

    <div class="row mt-5">
        <div class="col">
            <h2 class="mb-3">Conteúdo(s) de {{$title}}</h2>

            {{--$_COOKIE
                Verifica se o dono da página já adicionou algum conteúdo praquele item
            --}}
            @if ($db_url->isEmpty())
            <h2 class="text-center text-dark">Sem Conteúdos para editar!</h2>
            @else
            @foreach ($db_url as $f)

            <!-- ESTRUTURA -->
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">
                        {{$f->name}}
                    </h4>
                    <div class="d-flex justify-content-end gap-2">
                        <form action="{{route('content_edit', ['nickname' => $nickname, 'category_name_slug' => $category_name_slug, 'item_name_slug' => $item_name_slug, 'idblock' => $f->id])}}"
                            method="get">
                            @csrf
                            <button type="submit" class="btn btn-warning">Editar</button>
                        </form>
                        <form action="{{route('content_destroy', ['nickname' => $nickname, 'category_name_slug' => $category_name_slug])}}"
                            method="post">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="item_name_slug" value="{{$item_name_slug}}">
                            <input type="hidden" name="idblock" value="{{$f->id}}">
                            <button type="submit" class="btn btn-danger">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</div>
@endsection