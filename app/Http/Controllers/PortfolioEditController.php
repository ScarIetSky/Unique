<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Portfolio;
use App\Http\Requests;
use Validator;

class PortfolioEditController extends Controller
{
   public function execute(Request $request, Portfolio $portfolio)
   {
       if ($request->isMethod('delete')) {
           $portfolio->delete();
           return redirect('admin')->with('status', 'Работа удалена');
       }

       if ($request->isMethod('post')) {
           $input = $request->all();
           $validator = Validator::make($input, [
               'name' => 'required|max:255',
               'filter' => 'required|max:255'
           ]);

           if ($validator->fails()) {
               return redirect('portfolioEdit', ['portfolio' => $input['id']])->withErrors($validator);
           }

           if ($request->hasFile('images')) {
               $file = $request->file('images');
               $file->move(public_path() . '/assets/img', $file->getClientOriginalName());
               $input['images'] = $file->getClientOriginalName();
           } else {
               $input['images'] = $input['old_images'];
           }

           unset($input['old_images']);
           $portfolio->fill($input);
           if ($portfolio->update()) {
               return redirect('admin')->with('status', 'Страница обновлена');
           }
       }
           $old = $portfolio->toArray();
           if (view()->exists('admin.portfolios_edit')) {
               $data = [
                   'title' => 'Редактирование работы - ' . $old['name'],
                   'data' => $old
               ];
               return view('admin.portfolios_edit', $data);
           }
   }


}
