<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyLeadRequest;
use App\Http\Requests\StoreLeadRequest;
use App\Http\Requests\UpdateLeadRequest;
use App\Models\Priority;
use App\Models\Status;
use App\Models\Lead;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class LeadsController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Lead::where('status_id', 1)->with(['status', 'category'])
                ->filterLeads($request)
                ->select(sprintf('%s.*', (new Lead)->table))->orderBy('id', 'DESC');
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'lead_show';
                $editGate      = 'lead_edit';
                $deleteGate    = 'lead_delete';
                $crudRoutePart = 'leads';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });

            $table->addColumn('status_name', function ($row) {
                return $row->status ? $row->status->name : '';
            });
            $table->addColumn('status_color', function ($row) {
                return $row->status ? $row->status->color : '#000000';
            });

            $table->addColumn('category_name', function ($row) {
                return $row->category ? $row->category->name : '';
            });
            $table->addColumn('category_color', function ($row) {
                return $row->category ? $row->category->color : '#000000';
            });

            $table->editColumn('name', function ($row) {
                return $row->fname ? $row->fname . ' ' . $row->lname : "";
            });



            $table->addColumn('view_link', function ($row) {
                return route('admin.leads.show', $row->id);
            });

            $table->rawColumns(['actions', 'placeholder', 'status', 'category']);

            return $table->make(true);
        }

        $statuses = Status::all();
        $categories = Category::all();

        return view('admin.leads.index', compact('statuses', 'categories'));
    }

    public function assigned(Request $request)
    {
        if ($request->ajax()) {
            $query = Lead::where('status_id', 2)->with(['status', 'category', 'assigned_to_user'])
                ->filterLeads($request)
                ->select(sprintf('%s.*', (new Lead)->table))->orderBy('id', 'DESC');

            $table = Datatables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'lead_show';
                $editGate      = 'lead_edit';
                $deleteGate    = 'lead_delete';
                $crudRoutePart = 'leads';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });

            $table->addColumn('status_name', function ($row) {
                return $row->status ? $row->status->name : '';
            });
            $table->addColumn('status_color', function ($row) {
                return $row->status ? $row->status->color : '#000000';
            });

            $table->addColumn('category_name', function ($row) {
                return $row->category ? $row->category->name : '';
            });
            $table->addColumn('category_color', function ($row) {
                return $row->category ? $row->category->color : '#000000';
            });

            $table->editColumn('name', function ($row) {
                return $row->fname ? $row->fname . ' ' . $row->lname : "";
            });

            $table->addColumn('assigned_to_user_name', function ($row) {
                return  $row->assigned_to_user ? $row->assigned_to_user->name : '';
            });


            $table->addColumn('view_link', function ($row) {
                return route('admin.leads.show', $row->id);
            });

            $table->rawColumns(['actions', 'placeholder', 'status', 'category', 'assigned_to_user']);

            return $table->make(true);
        }

        $categories = Category::all();
        return view('admin.leads.assigned', compact('categories'));
    }

    public function accepted(Request $request)
    {
        if ($request->ajax()) {
            $query = Lead::where('status_id', 3)->with(['status', 'category', 'assigned_to_user'])
                ->filterLeads($request)
                ->select(sprintf('%s.*', (new Lead)->table))->orderBy('id', 'DESC');

            $table = Datatables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'lead_show';
                $editGate      = 'lead_edit';
                $deleteGate    = 'lead_delete';
                $crudRoutePart = 'leads';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });

            $table->addColumn('status_name', function ($row) {
                return $row->status ? $row->status->name : '';
            });
            $table->addColumn('status_color', function ($row) {
                return $row->status ? $row->status->color : '#000000';
            });

            $table->addColumn('category_name', function ($row) {
                return $row->category ? $row->category->name : '';
            });
            $table->addColumn('category_color', function ($row) {
                return $row->category ? $row->category->color : '#000000';
            });

            $table->editColumn('name', function ($row) {
                return $row->fname ? $row->fname . ' ' . $row->lname : "";
            });

            $table->addColumn('assigned_to_user_name', function ($row) {
                return  $row->assigned_to_user ? $row->assigned_to_user->name : '';
            });


            $table->addColumn('view_link', function ($row) {
                return route('admin.leads.show', $row->id);
            });

            $table->rawColumns(['actions', 'placeholder', 'status', 'category', 'assigned_to_user']);

            return $table->make(true);
        }

        $categories = Category::all();
        return view('admin.leads.accepted', compact('categories'));
    }
    public function rejected(Request $request)
    {
        if ($request->ajax()) {
            $query = Lead::where('status_id', 4)->with(['status', 'category', 'assigned_to_user'])
                ->filterLeads($request)
                ->select(sprintf('%s.*', (new Lead)->table))->orderBy('id', 'DESC');

            $table = Datatables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'lead_show';
                $editGate      = 'lead_edit';
                $deleteGate    = 'lead_delete';
                $crudRoutePart = 'leads';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });

            $table->addColumn('status_name', function ($row) {
                return $row->status ? $row->status->name : '';
            });
            $table->addColumn('status_color', function ($row) {
                return $row->status ? $row->status->color : '#000000';
            });

            $table->addColumn('category_name', function ($row) {
                return $row->category ? $row->category->name : '';
            });
            $table->addColumn('category_color', function ($row) {
                return $row->category ? $row->category->color : '#000000';
            });

            $table->editColumn('name', function ($row) {
                return $row->fname ? $row->fname . ' ' . $row->lname : "";
            });

            $table->addColumn('assigned_to_user_name', function ($row) {
                return  $row->assigned_to_user ? $row->assigned_to_user->name : '';
            });


            $table->addColumn('view_link', function ($row) {
                return route('admin.leads.show', $row->id);
            });

            $table->rawColumns(['actions', 'placeholder', 'status', 'category', 'assigned_to_user']);

            return $table->make(true);
        }

        $categories = Category::all();
        return view('admin.leads.accepted', compact('categories'));
    }
    public function active(Request $request)
    {
        if ($request->ajax()) {
            $query = Lead::where('status_id', 5)->with(['status', 'category', 'assigned_to_user'])
                ->filterLeads($request)
                ->select(sprintf('%s.*', (new Lead)->table))->orderBy('id', 'DESC');

            $table = Datatables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'lead_show';
                $editGate      = 'lead_edit';
                $deleteGate    = 'lead_delete';
                $crudRoutePart = 'leads';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });

            $table->addColumn('status_name', function ($row) {
                return $row->status ? $row->status->name : '';
            });
            $table->addColumn('status_color', function ($row) {
                return $row->status ? $row->status->color : '#000000';
            });

            $table->addColumn('category_name', function ($row) {
                return $row->category ? $row->category->name : '';
            });
            $table->addColumn('category_color', function ($row) {
                return $row->category ? $row->category->color : '#000000';
            });

            $table->editColumn('name', function ($row) {
                return $row->fname ? $row->fname . ' ' . $row->lname : "";
            });

            $table->addColumn('assigned_to_user_name', function ($row) {
                return  $row->assigned_to_user ? $row->assigned_to_user->name : '';
            });


            $table->addColumn('view_link', function ($row) {
                return route('admin.leads.show', $row->id);
            });

            $table->rawColumns(['actions', 'placeholder', 'status', 'category', 'assigned_to_user']);

            return $table->make(true);
        }

        $categories = Category::all();
        return view('admin.leads.active', compact('categories'));
    }

    public function completed(Request $request)
    {
        if ($request->ajax()) {
            $query = Lead::where('status_id', 6)->with(['status', 'category', 'assigned_to_user'])
                ->filterLeads($request)
                ->select(sprintf('%s.*', (new Lead)->table))->orderBy('id', 'DESC');

            $table = Datatables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'lead_show';
                $editGate      = 'lead_edit';
                $deleteGate    = 'lead_delete';
                $crudRoutePart = 'leads';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });

            $table->addColumn('status_name', function ($row) {
                return $row->status ? $row->status->name : '';
            });
            $table->addColumn('status_color', function ($row) {
                return $row->status ? $row->status->color : '#000000';
            });

            $table->addColumn('category_name', function ($row) {
                return $row->category ? $row->category->name : '';
            });
            $table->addColumn('category_color', function ($row) {
                return $row->category ? $row->category->color : '#000000';
            });

            $table->editColumn('name', function ($row) {
                return $row->fname ? $row->fname . ' ' . $row->lname : "";
            });

            $table->addColumn('assigned_to_user_name', function ($row) {
                return  $row->assigned_to_user ? $row->assigned_to_user->name : '';
            });


            $table->addColumn('view_link', function ($row) {
                return route('admin.leads.show', $row->id);
            });

            $table->rawColumns(['actions', 'placeholder', 'status', 'category', 'assigned_to_user']);

            return $table->make(true);
        }

        $categories = Category::all();
        return view('admin.leads.completed', compact('categories'));
    }
    public function canceled(Request $request)
    {
        if ($request->ajax()) {
            $query = Lead::where('status_id', 7)->with(['status', 'category', 'assigned_to_user'])
                ->filterLeads($request)
                ->select(sprintf('%s.*', (new Lead)->table))->orderBy('id', 'DESC');

            $table = Datatables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'lead_show';
                $editGate      = 'lead_edit';
                $deleteGate    = 'lead_delete';
                $crudRoutePart = 'leads';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });

            $table->addColumn('status_name', function ($row) {
                return $row->status ? $row->status->name : '';
            });
            $table->addColumn('status_color', function ($row) {
                return $row->status ? $row->status->color : '#000000';
            });

            $table->addColumn('category_name', function ($row) {
                return $row->category ? $row->category->name : '';
            });
            $table->addColumn('category_color', function ($row) {
                return $row->category ? $row->category->color : '#000000';
            });

            $table->editColumn('name', function ($row) {
                return $row->fname ? $row->fname . ' ' . $row->lname : "";
            });

            $table->addColumn('assigned_to_user_name', function ($row) {
                return  $row->assigned_to_user ? $row->assigned_to_user->name : '';
            });


            $table->addColumn('view_link', function ($row) {
                return route('admin.leads.show', $row->id);
            });

            $table->rawColumns(['actions', 'placeholder', 'status', 'category', 'assigned_to_user']);

            return $table->make(true);
        }

        $categories = Category::all();
        return view('admin.leads.canceled', compact('categories'));
    }

    public function create()
    {
        abort_if(Gate::denies('lead_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $statuses = Status::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $categories = Category::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $assigned_to_users = User::whereHas('roles', function ($query) {
            $query->whereId(2);
        })
            ->pluck('name', 'id')
            ->prepend(trans('global.pleaseSelect'), '');

        return view('admin.leads.create', compact('statuses', 'categories', 'assigned_to_users'));
    }

    public function store(StoreLeadRequest $request)
    {
        $dataToSave = $request->all();
        $dataToSave['questions'] = json_encode($dataToSave['questions']);
        $lead = Lead::create($dataToSave);
        foreach ($request->input('attachments', []) as $file) {
            $lead->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachments');
        }

        return redirect()->route('admin.leads.index');
    }

    public function edit(Lead $lead)
    {
        abort_if(Gate::denies('lead_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $statuses = Status::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $categories = Category::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $assigned_to_users = User::whereHas('roles', function ($query) {
            $query->whereId(2);
        })
            ->pluck('name', 'id')
            ->prepend(trans('global.pleaseSelect'), '');

        $lead->load('status', 'category', 'assigned_to_user');
        $attachments = isset($lead->attachments) ? $lead->attachments : [];
        return view('admin.leads.edit', compact('statuses', 'categories', 'assigned_to_users', 'attachments', 'lead'));
    }

    public function update(UpdateLeadRequest $request, Lead $lead)
    {
        $lead->update($request->all());

        if (count($lead->attachments) > 0) {
            foreach ($lead->attachments as $media) {
                if (!in_array($media->file_name, $request->input('attachments', []))) {
                    $media->delete();
                }
            }
        }

        $media = $lead->attachments->pluck('file_name')->toArray();

        foreach ($request->input('attachments', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $lead->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachments');
            }
        }

        return redirect()->route('admin.leads.index');
    }

    public function show(Lead $lead)
    {
        abort_if(Gate::denies('lead_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $lead->load('status', 'category', 'assigned_to_user', 'comments');

        $questions = json_decode($lead->questions, 1);
        $defindQuestions = $lead->category_id == 2 ? config('product.commercial_questions') : config('product.residential_questions');


        return view('admin.leads.show', compact('lead', 'questions', 'defindQuestions'));
    }

    public function destroy(Lead $lead)
    {
        abort_if(Gate::denies('lead_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lead->delete();

        return back();
    }

    public function massDestroy(MassDestroyLeadRequest $request)
    {
        Lead::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeComment(Request $request, Lead $lead)
    {
        $request->validate([
            'comment_text' => 'required'
        ]);
        $user = auth()->user();
        $comment = $lead->comments()->create([
            'author_name'   => $user->name,
            'author_email'  => $user->email,
            'user_id'       => $user->id,
            'comment_text'  => $request->comment_text
        ]);

        $lead->sendCommentNotification($comment);

        return redirect()->back()->withStatus('Your comment added successfully');
    }
}
