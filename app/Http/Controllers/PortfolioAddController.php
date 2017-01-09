<?php

namespace App\Http\Controllers;

use App\Portfolio;
use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;


class PortfolioAddController extends Controller
{
    public function execute(Request $request){
        if($request->isMethod('post')){
            $input = $request->only('id','name','filter','images');
            $messages =[
                'required'=>'Поле :attribute должно быть заполнено',
                'unique'=>'Поле :attribute должно быть уникальным'
            ];

            $validator = Validator::make($input,[
                'name'=>'required|max:255',
                'filter'=>'required|max:255',
            ],$messages);

            if($validator->fails()){
                return redirect()->route('portfolioAdd')->withErrors($validator)->withInput();
            }

            if($request->hasFile('images')){
                $file = $request->file('images');
                $input['images'] = $file->getClientOriginalName();
                $file->move(public_path() . '/assets/img', $input['images']);
            }

            $portfolio = new Portfolio();
            $portfolio->fill($input);
            if($portfolio->save()){
                return redirect('admin')->with('status','Работа успешно добавлена');
            }


        }

        if(view()->exists('admin.content_portfolios_add')){
            $data =[
                'title'=>'Новая работа'
            ];
            return view('admin.portfolios_add',$data);
        }
        abort(404);

    }
}
