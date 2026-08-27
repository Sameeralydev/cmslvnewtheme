@extends('admin.layouts.app')
@section('title','Chapters')
@section('content') @include('admin.academics.curriculum._form-list',['kind'=>'chapters']) @endsection
