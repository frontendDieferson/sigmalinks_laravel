@extends('admin.template')

@section('title', 'SigmaLink - Home')

@section('content')

   <header>
    <h2>Suas Páginas</h2>
   </header>
   <table>
    <thead>
        <tr>
            <th>Título</th>
            <th width="20">Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($pages as $page)
            <tr>
                <td>{{$page->op_title}} - ( {{$page->slug}} )</td>
                <td>
                    <a href="{{url('/'.$page->slug)}}" target="_blank">Acessar</a>
                    <a href="{{url('/admin/'.$page->slug.'/links')}}">Links</a>
                    <a href="{{url('/admin/'.$page->slug.'/design')}}">Personalizar</a>
                    <a href="{{url('/admin/'.$page->slug.'/stats')}}">Estastísticas</a>
                </td>
            </tr>
        @endforeach
    </tbody>
   </table>
@endsection
