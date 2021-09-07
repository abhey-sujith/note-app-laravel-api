<?php

namespace App\Http\Controllers;

use App\Models\Notes;
use App\Http\Requests\NoteRequest;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Http\Request;



 /**
 * @OA\Get(
 * path="/api/v1/notes",
 * summary="get notes for user",
 * description="pagination to get notes of user",
 * operationId="getNotes",
 * tags={"notes"},
 * security={ {"sanctum": {} }},
 * @OA\RequestBody(
 *    required=true,
 *    description="pass params page = pagenumber example:- http://localhost/api/v1/notes?page=1",
 *    @OA\JsonContent(
 *    ),
 * ),
 *    @OA\Response(
 *     response=200,
 *     description="Success",
 *     @OA\JsonContent(
 *       @OA\Property(property="current_page", type="integer", example="1"),
 *       @OA\Property(
 *              property="data",
 *              type="array",
 *              @OA\Items(
 *                 type="object",
 *                 ref="#/components/schemas/Notes",
 *                 example={ "The title field is required."},
 *              ),
 *           ),
 *       @OA\Property(property="first_page_url", type="string", example="http://localhost/api/v1/notes?page=1"),
 *       @OA\Property(property="from", type="integer", example="1"),
 *       @OA\Property(property="last_page", type="integer", example="3"),
 *       @OA\Property(property="last_page_url", type="string", example="http://localhost/api/v1/notes?page=3"),
 *       @OA\Property(property="next_page_url", type="string", example="http://localhost/api/v1/notes?page=2"),
 *       @OA\Property(property="path", type="string", example="http://localhost/api/v1/notes"),
 *       @OA\Property(property="per_page", type="integer", example="15"),
 *       @OA\Property(property="prev_page_url", type="string", example="null"),
 *       @OA\Property(property="to", type="integer", example="15"),
 *       @OA\Property(property="total", type="integer", example="34"),
 *     )
 *  ),
 * )
 * 
 */



 /**
 * @OA\Post(
 * path="/api/v1/notes",
 * summary="create note for a user",
 * description="create note",
 * operationId="createNote",
 * tags={"notes"},
 * security={ {"sanctum": {} }},
 * @OA\RequestBody(
 *    required=true,
 *    description="Pass user credentials",
 *    @OA\JsonContent(
 *       required={"email","password"},
 *       @OA\Property(property="title", type="string", example="title 1"),
 *       @OA\Property(property="description", type="string", example="description 100"),
 *    ),
 * ),
 *    @OA\Response(
 *     response=201,
 *     description="Success",
 *     @OA\JsonContent(
 *       @OA\Property(property="message", type="string", example="note has been created"),
 *     )
 *  ),
  * @OA\Response(
 *    response=422,
 *    description="Validation Error",
 *    @OA\JsonContent(
 *    @OA\Property(property="message", type="string", example="The given data was invalid."),
 *        @OA\Property(
 *           property="errors",
 *           type="object",
 *           @OA\Property(
 *              property="title",
 *              type="array",
 *              collectionFormat="multi",
 *              @OA\Items(
 *                 type="string",
 *                 example={ "The title field is required."},
 *              )
 *           )
 *        )
 *        )
 *     )
 * )
 * 
 */



 /**
 * @OA\Put(
 * path="/api/v1/notes/{id}",
 * summary="Edit note",
 * description="edit a note of user by id",
 * operationId="editNote",
 * tags={"notes"},
 * security={ {"sanctum": {} }},
 * @OA\Parameter(
 *    description="ID of note",
 *    in="path",
 *    name="id",
 *    required=true,
 *    example="1",
 *    @OA\Schema(
 *       type="integer",
 *       format="int64"
 *    )
 * ),
 * @OA\RequestBody(
 *    required=true,
 *    description="Pass user credentials",
 *    @OA\JsonContent(
 *       @OA\Property(property="title", type="string", example="title 1"),
 *       @OA\Property(property="description", type="string", example="description 100"),
 *    ),
 * ),
 *    @OA\Response(
 *     response=200,
 *     description="Success",
 *     @OA\JsonContent(
 *       @OA\Property(property="message", type="string", example="note has been updated"),
 *     )
 *  ),
 * @OA\Response(
 *    response=422,
 *    description="Validation Error",
 *    @OA\JsonContent(
 *    @OA\Property(property="message", type="string", example="The given data was invalid."),
 *        @OA\Property(
 *           property="errors",
 *           type="object",
 *           @OA\Property(
 *              property="title",
 *              type="array",
 *              collectionFormat="multi",
 *              @OA\Items(
 *                 type="string",
 *                 example={ "The title field is required."},
 *              )
 *           )
 *        )
 *        )
 *     )
 * )
 * 
 */

  /**
 * @OA\Delete(
 * path="/api/v1/notes/{id}",
 * summary="Edit note",
 * description="edit a note of user by id",
 * operationId="editNote",
 * tags={"notes"},
 * security={ {"sanctum": {} }},
 * @OA\Parameter(
 *    description="ID of note",
 *    in="path",
 *    name="id",
 *    required=true,
 *    example="1",
 *    @OA\Schema(
 *       type="integer",
 *       format="int64"
 *    )
 * ),
 *    @OA\Response(
 *     response=200,
 *     description="Success",
 *     @OA\JsonContent(
 *       @OA\Property(property="message", type="string", example="The note has been deleted"),
 *     )
 *  ),
 * )
 * 
 */
class NotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Notes::where('user_id','=',$request->user()->id)->paginate(15);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NoteRequest $request)
    {
        $note = Notes::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'description' => $request->description
        ]);

        return response(['message' => 'note has been created'],201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(NoteRequest $request,$id)
    {
        $data = $request->only('title', 'description');
         $note = Notes::where('id', '=',$id)->where('user_id', '=',$request->user()->id)->get();
         if($note->isEmpty()){
         return response(['message' => 'note not found'], 400);

         }
         $note = Notes::where('id', '=',$id)->where('user_id', '=',$request->user()->id);
         $note ->update($data);
         return response(['message' => 'note has been updated'], 200);

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notes  $notes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $note = Notes::where('id', '=',$id)->where('user_id', '=',$request->user()->id);
        $note->delete();
        return response(['message' => 'The note has been deleted'], 200);
    }
}
