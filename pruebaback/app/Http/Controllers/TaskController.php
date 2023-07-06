<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tasks;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;


class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Tasks::all();
        return $this->successResponse('Tareas recuperadas exitosamente', $tasks);
    }

    public function getStatus()
    {
        try {
            $status = \DB::table('state_task')->get();
            return $this->successResponse('Lista de estados', $status);
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error inesperado', 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            $task                = new Tasks();
            $task->title         = $request['title'];
            $task->detail        = $request['detail'];
            $task->state_id      = $request['state'];
            $task->project_id    = $request['project'];
            $task->save();

            return $this->successResponse('Tarea creada con éxito', $task);
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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $task = Tasks::findOrFail($id);
        $task->update($request->all());
        return $this->successResponse('Tarea actualizada exitosamente', $task);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Tasks::findOrFail($id);
        $task->delete();
        return $this->successResponse('Tarea eliminada exitosamente', null);
    }

    public function successResponse($message, $data, $id = 0, $statusCode = Response::HTTP_OK): JsonResponse{
        return response()->json(['message' => $message, 'data' => $data, 'id' => $id], $statusCode);
    }

    public function errorResponse($errorMessage, $statusCode): JsonResponse{
        return response()->json(['error' => $errorMessage, 'error_code' => $statusCode], $statusCode);
    }
}
