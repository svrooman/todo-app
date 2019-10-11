<?php

namespace App\Http\Middleware;

use Closure;
use DateTime;

class ToDoCreateUpdateRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255'
        ]);
        
        $dueDate = (
            !empty($request->input('due_date'))
            ? (new DateTime($request->input('due_date')))->format(env('DUE_DATE_FORMAT', 'Y-m-d 23:59:59'))
            : null
        );

        $request->merge(
            [
                'user_id' => request()->user()->id,
                'due_date' => $dueDate,
            ]
        );

        return $next($request);
    }
}
