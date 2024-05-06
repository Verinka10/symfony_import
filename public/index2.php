<?php
#
# https://phpsandbox.io/n/balance-nkcj8#index.php
# author Nikolai
# sql https://pastebin.com/MJ3UJdiF
# 

require 'Db.php';

try {

    $db = new Db('mysql:host=sql11.freemysqlhosting.net;dbname=sql11703782', 'sql11703782', 'AcxRc7gKDm');

    $path = trim($_SERVER['PATH_INFO'], '/');

    // get users
    if ($path == 'users') {
        $users = $db->query('
                select u.id, u.name from users u
                 join user_accounts a on a.user_id = u.id
                 join transactions t on t.account_to = a.id
                group by u.id
                order by name;
         ')->fetchAll(\PDO::FETCH_KEY_PAIR);
        echo json_encode($users);
        exit();
    }

    // get balance
    if ($path == 'balance') {
        if (empty($_GET['id'])) {
            throw new \Exception('Bad request', 400);
        }
        $id = intval($_GET['id']);

        $balance = $db->query('
                select DATE_FORMAT(t.trdate,"%m-%Y") date, sum(t.amount) sum, count(*) count from users u
                  join user_accounts a on a.user_id = u.id
                  join transactions t on t.account_to = a.id
                where u.id = :id
                group by date;
            ', [
                ':id' => $id
            ])->fetchAll(\PDO::FETCH_OBJ);

        $balanceFrom = $db->query('
                select DATE_FORMAT(t.trdate,"%m-%Y") date, -sum(t.amount) sum, count(*) count from users u
                  join user_accounts a on a.user_id = u.id
                  join transactions t on t.account_from = a.id
                where u.id = :id
                group by date;
            ', [
            ':id' => $id
            ])->fetchAll(\PDO::FETCH_OBJ);

         $balance = array_combine(array_column($balance, 'date'), $balance);

         foreach ($balance as $itemTo) {
             foreach ($balanceFrom as $itemFrom) {
                 if (!isset($balance[ $itemFrom->date ])) {
                     $balance[] = $itemFrom;
                 }
                 if ($itemTo->date == $itemFrom->date) {
                     $itemTo->sum += $itemFrom->sum;
                     $itemTo->count += $itemFrom->count;
                 }
             }
         }

        usort($balance, function ($a, $b) {
            return str_replace('-', '', $b->date) - str_replace('-', '', $a->date);
        });

        echo json_encode($balance);
        exit();
    }

} catch (\Throwable $e) {
    print "Error: " . $e->getMessage();
    throw $e;
}

require 'view.php';
