<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Projects;
use App\Models\Tasks;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $where = ($request->role != 1) ? ['user_id' => $request->user_id] : [];

            $projects = Projects::when($request->date_init, function ($query) use ($request) {
                return $query->where("date_init", $request->date_init);
            })
            ->when($request->date_finish, function ($query) use ($request) {
                return $query->where("date_finish", $request->date_finish);
            })
            ->where($where)
            ->get();

            return $this->successResponse('Lista de proyectos', $projects);
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error inesperado', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        try {
            $dateInit   = new \DateTime($request['date_init']);
            $dateFinish = new \DateTime($request['date_finish']);

            $project                = new Projects();
            $project->title         = $request['title'];
            $project->detail        = $request['detail'];
            $project->date_init     = $dateInit;
            $project->date_finish   = $dateFinish;
            $project->user_id       = $request->user_id ?? null;
            $project->save();

            return $this->successResponse('Proyecto creado con éxito', $project);
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrio un error inesperado', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        try {
            $project = Projects::findOrFail($id);

            $tasks = Tasks::select('tasks.id', 'tasks.title', 'tasks.detail', 'tasks.state_id', 's.name')
            ->join('state_task as s', 's.id', '=', 'tasks.state_id')
            ->where('tasks.project_id', $id)
            ->when($request->status, function ($query) use ($request) {
                return $query->where('tasks.state_id', $request->status);
            })
            ->get();


            $data = array(
                'project' => $project,
                'tasks' => $tasks,
            );

            return $this->successResponse('Detalles del proyecto', $data);
        } catch (\Exception $e) {
            return $this->errorResponse('Proyecto no encontrado', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $project = Projects::findOrFail($id);
            $project->update($request->all());
            return $this->successResponse('Proyecto actualizado correctamente', $project);
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error inesperado', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Tasks::where('project_id', $id)->delete();
            $project = Projects::findOrFail($id);
            $project->delete();
            return $this->successResponse('Proyecto eliminado correctamente', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error inesperado', 500);
        }
    }

    public function successResponse($message, $data, $id = 0, $statusCode = Response::HTTP_OK): JsonResponse{
        return response()->json(['message' => $message, 'data' => $data, 'id' => $id], $statusCode);
    }

    public function errorResponse($errorMessage, $statusCode): JsonResponse{
        return response()->json(['error' => $errorMessage, 'error_code' => $statusCode], $statusCode);
    }
}
