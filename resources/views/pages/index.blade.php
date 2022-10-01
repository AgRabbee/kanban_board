@extends('layouts.app')
@section('content')
    <div class="col-md-6 mx-auto">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Write your task" id="task"
                   aria-label="Write your task" aria-describedby="submit_task">
            <button class="btn btn-sm btn-success" type="button" id="submit_task">Add</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header text-center" style="background: orangered; color: white;">To Do</div>
                <div class="card-body">
                    <div class="list-group" id="order-left"></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header text-center" style="background: orangered; color: white;">In-progress</div>
                <div class="card-body">
                    <div class="list-group" id="order-mid"></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header text-center" style="background: orangered; color: white;">Done</div>
                <div class="card-body">
                    <div class="list-group" id="order-right"></div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script-section')

    <script>

        let todos_list = [];
        let in_progress_list = [];
        let done_list = [];
        $(document).ready(function (){
            fetch_data();

            $('#submit_task').click(function(){
                let task_title = $('#task').val();
                if(!task_title){
                    alert('Task cannot be empty');
                    return false;
                }
                $.ajax({
                    url: '/api/add',
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        task: task_title
                    },
                    success: function (data) {
                        if (data.responseCode == 1) {
                            $('#task').val('');
                            fetch_data();
                        }
                        else{
                            console.log(data.msg);
                        }
                    }
                });
            });
        });

        new Sortable(document.getElementById('order-left'), {
            group: 'shared',
            animation: 150,
            store: {
                get: function (order) {
                    return todos_list;
                },
                set: function (order) {
                    let prev_count = todos_list.length;
                    todos_list = order.toArray();
                    if(prev_count < todos_list.length){
                        ajax_call();
                    }
                }
            }
        });

        new Sortable(document.getElementById('order-mid'), {
            group: 'shared',
            animation: 150,
            store: {
                get: function (order) {
                    return in_progress_list;
                },
                set: function (order) {
                    let prev_count = in_progress_list.length;
                    in_progress_list = order.toArray();
                    if(prev_count < in_progress_list.length){
                        ajax_call();
                    }
                }
            }
        });

        new Sortable(document.getElementById('order-right'), {
            group: 'shared',
            animation: 150,
            store: {
                get: function (order) {
                    return done_list;
                },
                set: function (order) {
                    let prev_count = done_list.length;
                    done_list = order.toArray();
                    if(prev_count < done_list.length){
                        ajax_call();
                    }
                }
            }
        });

        function ajax_call() {
            $.ajax({
                url: '/api/update',
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    todos_list: todos_list,
                    in_progress_list: in_progress_list,
                    done_list: done_list,
                },
                success: function (data) {
                    if (data.responseCode == 1) {
                        fetch_data();
                    }
                    else{
                        console.log(data.msg);
                    }
                }
            });
        }

        function fetch_data(){
            $.ajax({
                url: '/api/tasks',
                type: "GET",
                success: function (data) {
                    if (data.responseCode == 1) {
                        /* prepare to_do list here */
                        if(typeof data.data.todoArr != 'undefined'){
                            let todos = data.data.todoArr.map((ele)=>{
                                return '<div class="list-group-item" data-id="'+ele.id+'">'+ele.task+'</div>';
                            });
                            $('#order-left').html(todos);
                        }

                        /* prepare in_progress list here */
                        if(typeof data.data.in_progressArr != 'undefined') {
                            let in_progress = data.data.in_progressArr.map((ele) => {
                                return '<div class="list-group-item" data-id="' + ele.id + '">' + ele.task + '</div>';
                            });
                            $('#order-mid').html(in_progress);
                        }

                        /* prepare done list here */
                        if(typeof data.data.doneArr != 'undefined') {
                            let done = data.data.doneArr.map((ele) => {
                                return '<div class="list-group-item" data-id="' + ele.id + '">' + ele.task + '</div>';
                            });
                            $('#order-right').html(done);
                        }
                    }
                    else{
                        console.log(data.msg);
                    }
                }
            });

        }
    </script>
@endsection
