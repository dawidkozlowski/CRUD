<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectGroup;
use App\Models\ProjectGroupCampaign;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        if (!empty($status) || $status == '0') {
            $projects = Project::where('active', $status)->paginate(5);
        } else {
            $projects = Project::paginate(5);
        }

        return view('index', compact('projects'))->with('i', (request()->input('page', 1) - 1) * 5)->with('status', request()->input('status', $status));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = ProjectGroup::all();
        $campaigns = ProjectGroupCampaign::all();
        return view('create', compact('groups', 'campaigns'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'website_url' => 'required',
            'start_date' => 'required'
        ]);


        $project = new Project();
        $project->name = $request->get('name');
        $project->website = $request->get('website_url');
        $project->active = $request->get('status');
        $project->save();

        $this->addGroupsAndCampaigns($request, $project);

        return redirect()->route('projects.index')
            ->with('success', 'Project created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
//        dd($project->projectGroup()->get());
        return view('show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        $groups = ProjectGroup::all();
        $campaigns = ProjectGroupCampaign::all();
        return view('edit', compact('project', 'groups', 'campaigns'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required',
            'website_url' => 'required',
            'start_date' => 'required'
        ]);

        $project = Project::where('id', $request->get('project_id'))->first();
        $project->name = $request->get('name');
        $project->website = $request->get('website_url');
        $project->active = $request->get('status');
        $project->update();

        $this->removeGroupsAndCampaign($project);
        $this->addGroupsAndCampaigns($request, $project);

        return redirect()->route('projects.index')
            ->with('success', 'Project updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project $project
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Project $project)
    {
        $this->removeGroupsAndCampaign($project);
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted.');
    }

    private function removeGroupsAndCampaign(Project $project)
    {
        $groups = ProjectGroup::where('project_id', $project->id)->get();
        if (count($groups) > 0) {
            foreach ($groups as $group) {
                ProjectGroupCampaign::where('project_group_id', $group->id)->delete();
                $group->delete();
            }
        }
    }

    private function addGroupsAndCampaigns(Request $request, Project $project)
    {
        $groups = [];
        foreach ($request->all() as $key => $group) {
//            dump($key);
            $explodedKey = explode('_', $key);
            if($explodedKey[0] == 'group') {
                if ($group != '0') {
                    $groups[$explodedKey[1]] = $group;
                }
            }
        }

        foreach ($groups as $key => $group){
            $g = new ProjectGroup();
            $g->project_id = $project->id;
            $g->name = $group;
            $g->budget = 0;
            $g->save();
            foreach ($request->campaign as $campaignKey => $campaign) {
                if ($campaignKey == $key) {
                    foreach ($campaign as $cmp) {
                        $c = new ProjectGroupCampaign();
                        $c->name = $cmp;
                        $c->status = 0;
                        $c->project_group_id = $g->id;
                        $c->date_start = $request->get('start_date');
                        $c->save();
                    }
                }
            }
        }
    }
}
