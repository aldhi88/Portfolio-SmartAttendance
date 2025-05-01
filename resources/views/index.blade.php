@extends('components.app_layout', ['data' => $data])

@section('content')
    @livewire($data['lw'], ['pass'=>$data])
@endsection
