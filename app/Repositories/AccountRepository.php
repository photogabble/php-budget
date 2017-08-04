<?php namespace App\Repositories;

use App\Account;
use App\Statistics;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class AccountRepository extends Repository
{

    /**
     * @var Account|Builder
     */
    protected $model;

    public function __construct(Account $model)
    {
        parent::__construct($model);
    }

    public function all($columns = ['*'])
    {
        $records = new Collection();

        foreach ($this->model->all($columns) as $record){
            // @todo this query can be modified to encompass all accounts, once done it only needs to run once rather than within this loop but for now this will do
            $query = "SELECT `categories`.name, COUNT(`categories`.id) as `c` FROM `categories` INNER JOIN `transactions` ON `transactions`.category_id = `categories`.id AND `transactions`.account_id = ? GROUP BY `categories`.name";
            $categories = DB::select($query, [$record->id]);

            $sum = array_reduce(
                $categories,
                function($previous, $current) {
                    if (is_null($previous)){
                        $previous = 0;
                    }
                    return $previous + $current->c;
                }
            );

            $accountStatistics = new Statistics();

            foreach ($categories as &$category)
            {
                $category->percentage = round((($category->c / $sum) * 100), 3);

            }unset($category);

            $accountStatistics->setStatistic('category.usage', $categories);
            $record->setAccountStatistics($accountStatistics);

            $records->add($record);
        }

        return $records;
    }
}