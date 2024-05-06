<?php
namespace controllers;


use components\App;
use components\exceptions\BadRequestHttpException;

class MainController extends \components\Controller
{
    
    public function balance($args) {
        
        if (empty($args['id'])) {
            throw new BadRequestHttpException();
        }
        
        $balance = App::$app->db->query('
                select DATE_FORMAT(t.trdate,"%m-%Y") date, sum(t.amount) sum from users u
                  join user_accounts a on a.user_id = u.id
                  join transactions t on t.account_to = a.id
                where u.id = :id
                group by date;
            ', [':id' => intval($args['id'])])
         ->fetchAll(\PDO::FETCH_KEY_PAIR);
        
         $balanceFrom = App::$app->db->query('
                select DATE_FORMAT(t.trdate,"%m-%Y") date, sum(t.amount) sum from users u
                  join user_accounts a on a.user_id = u.id
                  join transactions t on t.account_from = a.id
                where u.id = :id
                group by date;
            ', [':id' => intval($args['id'])])
          ->fetchAll(\PDO::FETCH_KEY_PAIR);
            
        
        foreach ($balanceFrom as $date => $sum) {
            if (!isset($balance[$date])) {
                $balance[$date] = 0;
            }
            $balance[$date] -= $sum;
        }
        uksort($balance, function ($a, $b) {
            return str_replace('-', '', $b) - str_replace('-', '', $a);
        });
        
        echo json_encode($balance);
    }
    
    public function default() {
        $this->render('main/default', ['users' => '']);
    }
    
    public function users() {
        $users = App::$app->db->query('
                select u.id, u.name from users u
                 join user_accounts a on a.user_id = u.id
                 join transactions t on t.account_to = a.id
                group by u.id
                order by name;
         ')
         ->fetchAll(\PDO::FETCH_KEY_PAIR);
        
          echo json_encode($users);
    }
    
}