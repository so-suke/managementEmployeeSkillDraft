@extends('layouts.app')

@section('content')
<employee-component :languages="{{ json_encode($languages) }}" :frameworks="{{ json_encode($frameworks) }}" :others="{{ json_encode($others) }}">
</employee-component>
@endsection
