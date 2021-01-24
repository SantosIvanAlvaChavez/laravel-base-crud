<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classroom;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Get data from DB
        // $classrooms = Classroom::all();
        // dd($classrooms);
        $classrooms = Classroom::paginate(3);

        //CARBON
        // $dt = Carbon::now();
        // dump($dt->toDateTimeString());

        // dump($dt->format('l d/m/Y'));

        $dt = Carbon::now()->locale('it_IT');
        //dump( $dt->locale() );
        dump( $dt->isoFormat('dddd DD/MM/YYYY') );

        return view('classrooms.index', compact('classrooms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('classrooms.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $data = $request->all();
        //dd($data);

        // VALIDAZIONE
        $request->validate([
            'name' => 'required|unique:classrooms|max:10',
            'description' => 'required'
        ]);

        // SALVARE A DB
        $classroom = new Classroom();
        // $classroom->name = $data['name'];
        // $classroom->description = $data['description'];
        $classroom->fill($data);

        $saved = $classroom->save();
        //dd($saved);

        if($saved) {
            return redirect()->route('classrooms.show', $classroom->id);
        }

    }

    /**
     * Display the specified resource. (DETAIL PAGE)
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $id = $_GET['id'];
        // $obj = new Classroom();
        $classroom = Classroom::find($id);
        //dd($classroom);

        return view('classrooms.show', compact('classroom'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $classroom = Classroom::find($id);

        return view('classrooms.edit', compact('classroom'));
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
        // DATI INVIATI DALLA FORMAT
        $data = $request->all();

        // INSTANZA SPECIFICA
        $classroom = Classroom::find($id);

        // VALIDAZIONE
        $request->validate([
            'name' => [
                'required',
                Rule::unique('classrooms')->ignore($id),
                'max:10'
            ],
            'description' => 'required'
        ]);

        // AGGIORNARE DATI DB
        $updated = $classroom->update($data);

        if($updated) {
            return redirect()->route('classrooms.show', $classroom->id);
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
        $classroom = Classroom::find($id); 

        $ref = $classroom->name;
        $deleted = $classroom->delete();

        if($deleted) {
            return redirect()->route('classrooms.index')->with('deleted', $ref);
        }
    }
}
