@extends('layouts.app')

@section('content')
<employee-component :languages="{{ json_encode($languages) }}" :frameworks="{{ json_encode($frameworks) }}">
</employee-component>
@endsection
