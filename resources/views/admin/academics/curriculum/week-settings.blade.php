@extends('admin.layouts.app')
@section('title','Week Settings')
@section('content') @include('admin.academics.curriculum._form-list',['kind'=>'week-settings']) @endsection
@include('admin.academics.curriculum._sort-arrows')
