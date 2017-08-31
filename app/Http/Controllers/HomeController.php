<?php

namespace App\Http\Controllers;

use App\Http\Libraries\Leslies;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Request;
use View;

class HomeController extends Controller {

    /**
     * @return mixed
     */
    public function index() {
        $prods = Leslies::getProducts();
        Leslies::logAction('pv', ['type' => 'h']);
        return View::make('home')->with(['products' => $prods]);
    }

    /**
     *
     * Searches elastic for products
     * If none found, then display some suggested prods
     *
     * @return mixed
     */
    public function search() {
        $query = Request::get('query');
        $prods = Leslies::searchProducts($query);

        $suggested_prods = [];
        if(empty($prods)) {
            // Some Best Sellers Algorithm here...
	        // Grab the top 6 or so
            $suggested_prods = Leslies::searchProducts('float');
        }

        Leslies::logAction('pv', ['type' => 's', 'query' => $query]);
        return View::make('home')->with(['products' => $prods, 'suggested_prods' => $suggested_prods, 'query' => $query]);
    }

    /**
     * @param $id
     *
     * Do a lookup on elastic for $id and return the product page
     *
     * @return mixed
     */
    public function product($id) {
        $product = Leslies::getProduct($id);
        Leslies::logAction('pv', ['type' => 'p', 'id' => $id]);
        return View::make('product')->with(['product' => $product[0]]);
    }

    /**
     * @return mixed
     */
    public function analytics() {

        return View::make('analytics');
    }

    /**
     *
     * Interfaces with influxdb endpoint to get pageview data
     *
     * @return array
     */
    public function analyticsApi() {
        $resource = curl_init();
        curl_setopt($resource, CURLOPT_URL, 'http://localhost:8086/query?epoch=1');
        curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($resource, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($resource, CURLOPT_POSTFIELDS, http_build_query(['db' => 'leslies', 'q' => 'SELECT COUNT(*) FROM pv WHERE time > now() - 1h GROUP BY time(1m), type']));

        $response = curl_exec($resource);
        $data = [];
        $categories = [];
        $conversion = [
            'h' => 'Homepage',
            's' => 'Search',
            'p' => 'Product'
        ];
        if(!empty($response)) {
            $response = json_decode($response, true);

            $start = 0;
            if(!empty($response['results']) && !empty($response['results'][0]['series'])) {
                $series = $response['results'][0]['series'];

                foreach($series as $k => $v) {
                    $type = $v['tags']['type'];
                    if(!empty($conversion[$type])) {
                        $type = $conversion[$type];
                    }
                    $single = ['name' => $type, 'data' => []];
                    foreach($v['values'] as $ok => $ov) {
                        $time = $ov[0];
                        $time = $time / 1000000;
                        $single['data'][] = $ov[1];
                        $categories[$time] = true;
                        if($start == 0) {
                            $start = $time;
                        }
                    }
                    $single['pointStart'] = $start;
                    $single['pointInterval'] = 60;
                    $data[] = $single;

                }

            }
        }

        $categories = array_keys($categories);

        return ['categories' => $categories, 'data' => $data, 'startpoint' => $start, 'interval' => 60];

    }

}
