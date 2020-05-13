<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
?>
@extends('errors::layout')

@section('title', '鉴权未通过')

@section('message', $exception->getMessage())

