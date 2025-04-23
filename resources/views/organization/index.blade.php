@extends('components.app_layout', ['data' => $data])

@section('content')

    @livewire($data['lw'], ['data'=>$data])

    @if ($data['lw'] == 'organization.organization-data')
        @livewire('organization.organization-create')
        @livewire('organization.organization-delete')
        @livewire('organization.organization-edit')
    @endif

@endsection
