@extends('layouts.app')

@section('content')
<section class="flex flex-col break-words bg-white sm:border-1 sm:rounded-md sm:shadow-sm sm:shadow-lg">

    <header class="font-semibold bg-gray-200 text-gray-700 py-5 px-6 sm:py-6 sm:px-8 sm:rounded-t-md">
        Status
    </header>

    <div class="w-full p-6 ">



        <status-board :data-all="{{ $statuses }}" ></status-board>
    </div>
</section>
@endsection
