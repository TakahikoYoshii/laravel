<?php

namespace Testing\Ext;

use Mockery;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

trait ExtTestMethodTrait
{
    /**
     * Assert that the response contains an exact JSON array.
     *
     * @param  array $data
     * @return $this
     */
    public function seeJsonEqualsExt(array $data)
    {
        $actual = json_encode(array_sort_recursive(
            json_decode($this->response->getContent(), true)
        ), JSON_FORCE_OBJECT);

        $this->assertEquals(json_encode(array_sort_recursive($data), JSON_FORCE_OBJECT), $actual);

        return $this;
    }

    /**
     * Assert that a given where condition exists in the database.
     *
     * @param  string $table
     * @param  array $data
     * @param  string $connection
     * @return $this
     */
    protected function seeInDatabaseExt($table, array $where, array $dates, $isDeleted = false, $connection = null)
    {
        $database = $this->app->make('db');

        $connection = $connection ? : $database->getDefaultConnection();

        $query = $database->connection($connection)->table($table);

        // where句連結
        foreach ($where as $key => $value) {
            $query->where($key, $value);
        }

        // 日付確認用
        if (count($dates) == 0) {
            $now = new \DateTime('now');
            foreach ($dates as $dateColumn => $isNow) {
                if ($isNow) {
                    //now()
                    $query->whereBetween($dateColumn, [$now->modify('-1 minutes')->format('Y-m-d H:i:s'), $now->modify('+1 minutes')->format('Y-m-d H:i:s')]);
                } else {
                    // not now()
                    $query->whereNotBetween($dateColumn, [$now->modify('-1 minutes')->format('Y-m-d H:i:s'), $now->modify('+1 minutes')->format('Y-m-d H:i:s')]);
                }
            }
        }

        // delete flag確認

        if ($isDeleted) {
            $query->whereNotNull('deleted_at');
        } else {
            $query->whereNull('deleted_at');
        }

        $count = $query->count();

        $this->assertGreaterThan(0, $count, sprintf(
            'Unable to find row in database table [%s] that matched attributes [%s].', $table, json_encode($where), 'deleted_at' . $isDeleted
        ));

        return $this;
    }
}
