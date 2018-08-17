<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Response;
use App\Models\Film;
use App\Models\Image;
use App\Models\Schedule;

class FilmController extends ApiController
{
    const TICKET_PRICE = 60000;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $films = Film::with(['images', 'schedules.tickets' => function ($query) { 
                $query->orderBy('price', config('define.dir_desc'));
            }])->whereIn('id', function($query) {
                $query->select('film_id')
                      ->from('schedules');
            })->orderBy('id', config('define.dir_desc'))->take(config('define.film.limit_rows'))->get();            
    
            foreach ($films as $film) {
                $film['image_path'] = empty($film['images'][0]) ? ' ' : $film['images'][0]['path'];
                $film['price_formated'] = empty($film['schedules'][0]['tickets'][0]) ? $this->TICKET_PRICE : number_format($film['schedules'][0]['tickets'][0]['price']);
            }
            return $this->showAll($films, Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NO_CONTENT);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function feature()
    {
        try {
            $films = Film::with(['images', 'schedules.tickets' => function ($query) { 
                $query->orderBy('price', config('define.dir_desc'));
            }])->whereIn('id', function($query) {
                $query->select('film_id')
                      ->from('schedules');
            })->take(config('define.film.limit_rows'))->get();            
    
            foreach ($films as $film) {
                $film['image_path'] = empty($film['images'][0]) ? ' ' : $film['images'][0]['path'];
                $film['price_formated'] = empty($film['schedules'][0]['tickets'][0]) ? $this->TICKET_PRICE : number_format($film['schedules'][0]['tickets'][0]['price']);
            }
            return $this->showAll($films, Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NO_CONTENT);
        }
    }
}
