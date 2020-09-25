<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CRUD Homework</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>

    <script src="{{ asset('js/accountinfo.js') }}" defer></script>
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .error {
            color: red;
            margin-left: 5px;
        }

        label.error {
            display: inline;
        }

        tr.tr-only-hide {
            color: #000000;
        }

        @media (max-width: 736px) {
            .table-rwd{
                min-width: 100%;
            }

            tr.tr-only-hide {
                display: none !important;
            }

            .table-rwd tr{
                display: block;
                border: 1px solid #ddd;
                margin-top: 5px;
            }
            .table-rwd td {
                text-align: left;
                font-size: 15px;
                overflow: hidden;
                width: 100%;
                display: block;
            }
            .table-rwd td:before {
                content: attr(data-th) " : ";
                display: inline-block;
                text-transform: uppercase;
                font-weight: bold;
                margin-right: 10px;
                color: #D20B2A;
            }
            /*Fix border when RWD*/
            .table-rwd.table-bordered td,
            .table-rwd.table-bordered th,
            .table-rwd.table-bordered {
                border:0;
            }
        }
    </style>

</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">CRUD Test Project for Account Info</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item px-md-1">
                <a href="{{ URL::to('/') }}" role="button" class="btn btn-outline-secondary my-2 my-lg-0 px-md-1" id="btn-to-home"> Home </a>
            </li>
            <li class="nav-item px-md-1">
                <button class="btn btn-success my-2 my-lg-0" id="btn-add"> Add Account </button>
            </li>
            <li class="nav-item px-md-1">
                <button class="btn btn-primary my-2 my-lg-0" id="btn-export-excel"> Export Excel </button>
            </li>
            <li class="nav-item px-md-1">
                <button class="btn btn-primary my-2 my-lg-0" id="btn-export-csv"> Export CSV </button>
            </li>
            <li class="nav-item px-md-1">
                <button class="btn btn-warning my-2 my-lg-0" id="btn-import"> Import </button>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0" method="GET">
            <input type="search" class="form-control mr-sm-2" id="filter" name="filter" placeholder="input name to search..." value="{{$filter}}">&nbsp;
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Filter</button>
        </form>
    </div>
</nav>
<div class="p-2">
    <table class="table table-striped table-bordered table-rwd">
        <thead>
        <tr class="tr-only-hide">
            <th></th>
            <th>@sortablelink('id', 'ID')</th>
            <th>@sortablelink('username', 'UserName')</th>
            <th>@sortablelink('name', 'Name')</th>
            <th>@sortablelink('gender', 'Gender')</th>
            <th>@sortablelink('birthday', 'Birthday')</th>
            <th>@sortablelink('email', 'Email')</th>
            <th>Note</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody id="account-list" name="account-list">
        @isset($accountinfo)
            @foreach ($accountinfo as $account)
            <tr id="account{{$account->id}}">
                <td data-th="刪除"><input type='checkbox' id='del_{{$account->id}}' ></td>
                <td data-th="ID">{{$account->id}}</td>
                <td data-th="UserName">{{$account->username}}</td>
                <td data-th="Name">{{$account->name}}</td>
                <td data-th="Gender">{{$account->gender == 1 ? '男' : '女'}} </td>
                <td data-th="Birthday">{{ date('Y年m月d日', strtotime($account->birthday))}}</td>
                <td data-th="email">{{$account->email}}</td>
                <td data-th="Note">{{$account->note}}</td>
                <td data-th="Action">
                    <button data-id="{{$account->id}}" class="btn btn-primary btnEdit">Edit</button>&nbsp;
                    <button data-id="{{$account->id}}" class="btn btn-danger btnDelete">Delete</button>
                </td>
            </tr>
            @endforeach
        @endisset
        </tbody>
    </table>
    <div>
        <button class="btn btn-warning my-2 my-lg-0 btn-bulk-delete"> Bulk Delete </button>
    </div>

    <div class="d-flex justify-content-center">
        {!! $accountinfo->appends(Request::except('page'))->links("pagination::bootstrap-4") !!}
    </div>
    <div class="d-flex justify-content-center">
        Displaying {{$accountinfo->count()}} of {{ $accountinfo->total() }} accounts.
    </div>

    <div class="modal fade" id="formModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="formModalLabel">Create New Account</h4>
                </div>
                <div class="modal-body">
                    <form id="accountForm" name="accountForm" class="form-horizontal">
                        <div class="form-group">
                            <label for="username">帳號</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="name">姓名</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="gender">性別</label>
                            <label class="radio"><input type="radio"  name="genderRadio" value="0">女</label>
                            <label class="radio"><input type="radio" name="genderRadio" value="1" checked>男</label>
                            <input type="hidden" id="gender" name="gender" value="0">
                        </div>
                        <div class="form-group">
                            <label for="birthday">生日</label>
                            <input type="text" class="form-control date" id="birthday" name="birthday" readonly required>
                        </div>
                        <div class="form-group">
                            <label for="email">信箱</label>
                            <input type="text" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="note">備註</label>
                            <input type="text" class="form-control" id="note">
                        </div>
                        <input type="hidden" id="account_id" name="account_id" value="0">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btn-save" value="add"> Create </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmDeleteModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="formModalLabel">Confirm delete</h4>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete?</h5>
                    <form id="accountForm" name="accountForm" class="form-horizontal" novalidate="">
                        <div class="form-group">
                            <label for="username">username</label>
                            <input type="text" class="form-control" id="username" readonly>
                        </div>
                        <div class="form-group">
                            <label for="name">name</label>
                            <input type="text" class="form-control" id="name" readonly>
                        </div>
                        <input type="hidden" id="account_id" name="account_id">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="btn-delete" value="delete"> Delete </button>&nbsp;
                    <button type="button" class="btn" id="btn-cancel" value="Cancel"> Cancel </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmExport" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="formModalLabel">Confirm export</h4>
                </div>
                <div class="modal-body">
                    <h5>Do you want to export accounts?</h5>
                    <form id="exportForm" name="exportForm" class="form-horizontal" novalidate="">
                        <input type="hidden" id="account_id" name="account_id">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btn-export" value="exportxls"> OK </button>&nbsp;
                    <button type="button" class="btn" id="btn-cancel-export" value="Cancel"> Cancel </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="importFile" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="formModalLabel">Import data</h4>
                </div>
                <form id="importForm" name="importForm" class="form-horizontal" novalidate="" enctype="multipart/form-data">
                    <div class="modal-body">
                    <h5>Select CSV or xlsx file to import accounts.</h5>
                    <div class="form-group">
                        <label for="name">Select file</label>
                        <input type="file" class="form-control" id="fileupload" name="fileupload">
                        <input type="hidden" id="account_id" name="account_id" value="123">
                    </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="btn-doimport" value="importfile"> OK </button>&nbsp;
                        <button type="button" class="btn" id="btn-cancel-import" value="Cancel"> Cancel </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmBulkDelete" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="formModalLabel">Confirm Most Delete</h4>
                </div>
                <form id="importForm" name="importForm" class="form-horizontal" novalidate="" enctype="multipart/form-data">
                    <div class="modal-body">
                        <h5 class="bulkmessage"></h5>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary btn-dobulkDelete" id="btn-dobulkDelete" value="BulkDelete"> OK </button>&nbsp;
                        <button type="button" class="btn btn-cancel-bulk-delete" id="btn-cancel-bulk-delete" value="Cancel"> Cancel </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
<script type="text/javascript">
    $('.date').datepicker({ format: 'yyyy-mm-dd' });
</script>
</body>
</html>
