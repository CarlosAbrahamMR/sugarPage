@extends('layouts.admin')
@section('content')
    <div class="st-pusher" id="content">
        <div class="st-content">
            <div class="st-content-inner">
                <div class="container-fluid">
                    <div class="page-section">
                        <div class="row">
                            <div class="col-md-12 col-lg-8 col-md-offset-1 col-lg-offset-2">
                                @if(Auth::user()->roles_id === 3 && !$promocion)
                                    <div class="panel panel-default widget-user-1 text-center">
                                        <div class="panel-heading">
                                            Special Guest Code
                                        </div>
                                        <div class="panel-body">
                                            <form method="post" action="{{route('redeem-code')}}">
                                                {{ csrf_field() }}
                                                <div class="form-group form-control-default">
                                                    <input v-model="codeRedeem" type="text" class="form-control"
                                                           id="codeRedeem" name="codeRedeem" placeholder="Enter code">
                                                </div>
                                                <button class="btn btn-primary" type="submit">Redeem</button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
