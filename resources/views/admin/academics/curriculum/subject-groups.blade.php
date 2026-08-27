@extends('admin.layouts.app')
@section('title','Subject Groups')
@section('content') @include('admin.academics.curriculum._form-list',['kind'=>'subject-groups']) @endsection
