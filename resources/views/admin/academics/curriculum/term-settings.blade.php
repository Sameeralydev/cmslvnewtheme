@extends('admin.layouts.app')
@section('title','Term Settings')
@section('content') @include('admin.academics.curriculum._form-list',['kind'=>'term-settings']) @endsection
@include('admin.academics.curriculum._sort-arrows')
