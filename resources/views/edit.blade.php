@extends('app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Product</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('projects.index') }}" title="Go back"> <i class="fas fa-backward "></i> </a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('projects.update', $project->id) }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="project_id" value="{{ $project->id }}">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    <input type="text" name="name" value="{{ $project->name }}" class="form-control" placeholder="Name">
                </div>
            </div>
            {{--<div class="col-xs-12 col-sm-12 col-md-12">--}}
                {{--<div class="form-group">--}}
                    {{--<strong>Project groups -> groups campaigns:</strong>--}}
                    {{--@foreach($project->projectGroup()->get() as $group)--}}
                        {{--<input type="text" name="group.{{ $group->id }}" value="{{ $group->name }}" class="form-control" style="margin-top: 20px"><br>--}}
                        {{--<strong>Groups campaigns:</strong>--}}
                        {{--@foreach($group->projectGroupCampaign()->get() as $campaign)--}}
                            {{--<input type="text" name="group.{{ $group->id }}.{{ $campaign->id }}" value="{{ $campaign->name }}" class="form-control" style="margin-left: 20px; margin-right: 20px">--}}
                        {{--@endforeach--}}
                    {{--@endforeach--}}
                {{--</div>--}}
            {{--</div>--}}
            <div class="col-xs-12 col-sm-12 col-md-12 form-group">
            @php
            $i = 100;
            $start_date = null;
            @endphp
                @foreach($project->projectGroup()->get() as $projectGroup)

                    <div class="decrement row">
                        <div class="col-md-5">
                            <select name="{{ 'group_' . $i }}" id="" class="form-control">
                                @foreach($groups as $group)
                                    @if($projectGroup->id == $group->id)
                                        <option selected value="{{ $group->name }}">{{ $group->name }}</option>
                                    @else
                                        <option value="{{ $group->name }}">{{ $group->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <details class="row">
                                <summary>Campaigns</summary>
                                @foreach($campaigns as $campaign)
                                    <div class="col-6">
                                        @if($projectGroup->id == $campaign->project_group_id)
                                            <input name="{{ 'campaign[' . $i . '][]' }}" type="checkbox" checked value="{{ $campaign->name }}">{{ $campaign->name }}
                                            @php
                                                $start_date = $campaign->date_start->format('Y-m-d');
                                            @endphp
                                        @else
                                            <input name="{{ 'campaign[' . $i . '][]' }}" type="checkbox" value="{{ $campaign->name }}">{{ $campaign->name }}
                                        @endif
                                    </div>
                                @endforeach
                            </details>
                        </div>
                        <div class="input-group-btn col-md-2">
                            <button class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
                        </div>
                    </div>
                    @php
                        $i++;
                    @endphp
                @endforeach
                <div class="increment row">
                    <div class="col-md-5">
                        <select name="group_0" id="" class="form-control">
                            <option value="0">Chose group</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <details class="row">
                            <summary>Campaigns</summary>
                            @foreach($campaigns as $campaign)
                                <div class="col-6">
                                    <input name="campaign[0][]" type="checkbox" value="{{ $campaign->id }}">{{ $campaign->name }}
                                </div>
                            @endforeach
                        </details>
                    </div>
                    <div class="input-group-btn col-md-2">
                        <button class="btn btn-success" type="button"><i class="icon icon-plus"></i>Add Group</button>
                    </div>
                </div>

                <div class="clone hide" hidden>
                    <div class="row decrement">
                        <div class="col-md-5">
                            <select name="group" id="" class="form-control">
                                <option value="0">Chose group</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <details class="row">
                                <summary>Campaigns</summary>
                                @foreach($campaigns as $campaign)
                                    <div class="col-6">
                                        <input name="campaign" type="checkbox" value="{{ $campaign->id }}">{{ $campaign->name }}
                                    </div>
                                @endforeach
                            </details>
                        </div>
                        <div class="input-group-btn col-md-2">

                            <button class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Website URL:</strong>
                    <input type="text" name="website_url" class="form-control" placeholder="URL to website"
                           value="{{ $project->website }}">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Status</strong>
                    <select name="status" id="" class="form-control">
                        <option {{ $project->active == 0 ? 'selected' : '' }} value="0">INACTIVE</option>
                        <option {{ $project->active == 1 ? 'selected' : '' }} value="1">ACTIVE</option>
                        <option {{ $project->active == 2 ? 'selected' : '' }} value="2">IN PROGRESS</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Start date:</strong>
                    <input type="date" name="start_date" class="form-control" placeholder=""
                           value="{{ $start_date }}">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>

    </form>

    <script>
        $(document).ready(function () {
            var i = 0;
            $(".btn-success").click(function () {
                i++;
                console.log('clone');
                var html = $(".clone");
                var increment = $(".increment");
                // console.log(increment.children('select'));
                increment.after(html.html());
                increment.next().find("[name='group']").attr('name', 'group_' + i);
                increment.next().find("[name='campaign']").attr('name', 'campaign[' + i + '][]');
            });

            $("body").on("click", ".btn-danger", function () {
                $(this).parents(".decrement").remove();
            });
        });
    </script>
@endsection