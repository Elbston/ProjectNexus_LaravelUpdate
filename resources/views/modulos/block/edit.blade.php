{{--
    Importação inicial do projeto.
    Serve pra pegar o layout da página principal, e aplicar em cada view em que será extendido.
--}}
@extends('layouts.main')
@section('title', 'Editar Bloco '.$db->name.' do item '.$item->name)
@section('content')

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10 col-12">
            <div class="card custom-card">
                <div class="card-header custom-header text-center fs-4 py-4">
                    <strong>Formulário de Edição de Conteúdos</strong>
                </div>
                <div class="card-body p-5">
                    <form action="{{route('content_update', ['nickname' => $nickname, 'category_name_slug' => $category_name_slug])}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="id" value="{{$id}}">
                        <input type="hidden" name="item_name_slug" value="{{$item_name_slug}}">
                        <input type="hidden" name="idblock" value="{{$db->id}}">

                        <div class="mb-4">
                            <label for="name" class="form-label">Nome</label>
                            <input name="name" type="text" class="form-control custom-input"
                                value="{{$db->name}}" placeholder="ex: Introdução" required>
                            @error('name')
                            <div class="text-danger">{{$message}}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">Descrição</label>
                            <textarea name="description" rows="4" id="autoTextarea" style="overflow:hidden; resize: none;" class="form-control custom-input"
                                placeholder="ex: O jogo conta a história de Arthur Morgan, membro da gangue Van Der Linde que está fugindo das autoridades devido a um assalto que fizeram em Blackwater..." required>{{$db->description}}</textarea>
                            @error('description')
                            <div class="text-danger">{{$message}}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="image" class="form-label">Alterar Imagem</label>
                            <input name="image" type="file" class="form-control custom-input">
                            @if ($db->image)
                            <div class="mt-2">
                                <img src="/images/{{$nickname}}/categories/{{$id}}/item/{{$db->item_id}}/{{$db->image}}" alt="Imagem Atual" class="img-fluid"
                                    style="max-width: 200px;">
                            </div>
                            @endif
                            @error('image')
                            <div class="text-danger">{{$message}}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="switch" class="form-label">Remover Imagem</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="switch" name="remove_image"
                                    value="true">
                                <label class="form-check-label" for="switch">Marque para remover</label>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <button type="submit" class="btn custom-btn">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection