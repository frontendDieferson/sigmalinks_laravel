@extends('admin.page')

@section('body')

    <form method="POST">
        @csrf
        <label>
            Status: <br/>
            <select name="status">
                <option value="1">Link Ativo</option>
                <option value="0">Link Inativo</option>
            </select>
        </label>
        <label>
            Título do link: <br/>
            <input type="text" name="title" />
        </label>
        <label>
            Url do link: <br/>
            <input type="text" name="href" />
        </label>
        <label>
            Cor do fundo: <br/>
            <input type="color" name="op_bg_color" value="#FFF" />
        </label>
        <label>
            Cor do texto: <br/>
            <input type="color" name="op_text_color" value="#000" />
        </label>
        <label>
            Tipo da Borda: <br/>
            <select name="op_border_type">
                <option value="square">Quadrado</option>
                <option value="rounded">Arrendodado</option>
            </select>
        </label>
        <label>
            <input type="submit" value="Salvar" />
        </label>
    </form>

@endsection
