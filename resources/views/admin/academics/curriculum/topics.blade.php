@extends('admin.layouts.app')
@section('title','Topics')
@section('content') @include('admin.academics.curriculum._form-list',['kind'=>'topics']) @endsection
@include('admin.academics.curriculum._sort-arrows')
