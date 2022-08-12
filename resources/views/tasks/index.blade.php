@extends('tasks.layout')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        #draggable {
            width: 150px;
            height: 150px;
            padding: 0.5em;
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Task Management</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('tasks.create') }}"> Create New Task</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12 p-3 bg-dark offset-md-1">
                    <ul class="list-group shadow-lg connectedSortable" id="padding-item-drop">
                        @if(!empty($tasks) && $tasks->count())
                            @foreach($tasks as $key => $value)
                                <li class="list-group-item" item-id="{{ $value->id }}">
                                    <div class="row">

                                        <div class="col-md-10">
                                            <p>
                                                {{ $value->name }}
                                            </p>
                                        </div>

                                        <div class="col-md-2">
                                            <form action="{{ route('tasks.destroy',$value->id) }}" method="POST">
                                                <a class="btn btn-primary" href="{{ route('tasks.edit',$value->id) }}">Edit</a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
            integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
            crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(function () {
            $("#padding-item-drop, #complete-item-drop").sortable({
                connectWith: ".connectedSortable",
                opacity: 0.5,
            });
            $(".connectedSortable").on("sortupdate", function (event, ui) {
                var tasks = [];
                $("#padding-item-drop li").each(function (index) {
                    if ($(this).attr('item-id')) {
                        tasks[index] = $(this).attr('item-id');
                    }
                });
                $.ajax({
                    url: "{{ route('update.priority') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {tasks: tasks},
                    success: function (data) {
                        console.log('success');
                    }
                });

            });
        });
    </script>
@endpush
