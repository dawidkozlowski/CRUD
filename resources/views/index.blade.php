@extends('app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>CRUD </h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('projects.create') }}" title="Create a project"> <i class="fas fa-plus-circle"></i>
                </a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <a href="{{ route('projects.index') }}"><button>ANY</button></a>
    <a href="{{ route('projects.index', ['status' => '0']) }}"><button>INACTIVE</button></a>
    <a href="{{ route('projects.index', ['status' => '1']) }}"><button>ACTIVE</button></a>
    <a href="{{ route('projects.index', ['status' => '2']) }}"><button>IN PROGRESS</button></a>
    <table class="table table-bordered table-responsive-lg">
        <tr>
            <th>No</th>
            <th>Project name</th>
            <th>Project group</th>
            <th>Campaign name</th>
            <th>Website </th>
            <th>Active</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($projects as $project)
            <tr>
                <td>{{ $project->id }}</td>
                <td>{{ $project->name }}</td>
                <td>
                    @foreach($project->projectGroup()->get() as $group)
                        <p>{{ $group->name }}<br></p>
                    @endforeach
                   </td>
                <td>
                    @foreach($project->projectGroup()->get() as $group)
                        @foreach($group->projectGroupCampaign()->get() as $campaign)
                            {{ $campaign->name }} <br>
                        @endforeach
                            <br>
                    @endforeach
                </td>
                <td>{{ $project->website }}</td>
                <td>
                    {{ $project->status() }}
                </td>
                <td>
                    <form action="{{ route('projects.destroy', $project->id) }}" method="POST">

                        <a href="{{ route('projects.show', $project->id) }}" title="show">
                            <i class="fas fa-eye text-success  fa-lg"></i>
                        </a>

                        <a href="{{ route('projects.edit', $project->id) }}">
                            <i class="fas fa-edit  fa-lg"></i>

                        </a>

                        @csrf
                        @method('DELETE')

                        <button type="submit" title="delete" style="border: none; background-color:transparent;">
                            <i class="fas fa-trash fa-lg text-danger"></i>

                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>

    @if(!empty(request()->get('status')) || request()->get('status') == 0)
        {!! $projects->appends(['status' => request()->get('status')])->links() !!}
    @else
        {!! $projects->links() !!}
    @endif

@endsection