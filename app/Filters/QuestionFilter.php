<?php


namespace App\Filters;


use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class QuestionFilter
{
    protected $request;
    protected $queryBuilder;
    protected $filters = ['by','popularity','unanswered'];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param Builder $builder
     * @return mixed
     */
    public function apply(Builder $builder)
    {
        $this->queryBuilder = $builder;
        $filters = array_filter($this->request->only($this->filters));

        foreach ($filters as $filter => $value) {
            if (method_exists($this,$filter)) {
                $this->$filter($value);
            }
        }

        return $this->queryBuilder;
    }

    protected function by(string $username)
    {
        $user = User::where('name',$username)->firstOrFail();
        return $this->queryBuilder->where('user_id',$user->id);
    }

    protected function popularity()
    {
        $this->queryBuilder->orderBy('answers_count','desc');
    }

    public function unanswered()
    {
        $this->queryBuilder->where('answers_count','=',0);
    }
}