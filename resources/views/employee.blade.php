@extends('layouts.app')

@section('content')
<employee-component :languages="{{ json_encode($languages) }}"></employee-component>
@endsection
