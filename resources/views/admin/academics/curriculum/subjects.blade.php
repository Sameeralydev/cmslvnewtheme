@extends('admin.layouts.app')
@section('title','Subjects')
@section('content') @include('admin.academics.curriculum._form-list',['kind'=>'subjects']) @endsection
@include('admin.academics.curriculum._sort-arrows')
