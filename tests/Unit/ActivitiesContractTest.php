<?php
namespace Tests\Unit;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait ActivitiesContractTest
{
    /** @test */
    public function has_many_activities()
    {
        $model = $this->getActivityModel();

        create(Activity::class,[
            'user_id' => $model->user_id,
            'subject_id' => $model->id,
            'subject_type' => $model->getMorphClass(),
            'type' => $this->getActivityType(),
        ]);

        self::assertInstanceOf(MorphMany::class,$model->activities());
    }

    abstract protected function getActivityModel();
    abstract protected function getActivityType();
}