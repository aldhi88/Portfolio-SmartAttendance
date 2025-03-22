@extends('components.auth_layout', ['data' => $data])

@section('content')

    @livewire($data['lw'], ['data'=>$data])

@endsection
