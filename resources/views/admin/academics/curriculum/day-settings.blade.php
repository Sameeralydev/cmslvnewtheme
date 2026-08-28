@extends('admin.layouts.app')
@section('title','Day Settings')
@section('content') @include('admin.academics.curriculum._form-list',['kind'=>'day-settings']) @endsection
@include('admin.academics.curriculum._sort-arrows')
