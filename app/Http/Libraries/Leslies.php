<?php
namespace App\Http\Libraries;

use Config;

class Leslies {
	
	/**
	 * @param array $params
	 *
	 * Leslies API curl interface
	 * $params accepts productid for now
	 *
	 * @return array|mixed
	 */
    public static function call($params = []) {
        $retval = [];

        $param_str = '';
        if(!empty($params)) {
            $param_str = '?' . http_build_query($params);
        }

        $resource = curl_init();
        curl_setopt($resource, CURLOPT_URL, Config::get('api.leslies.url') . $param_str);
        curl_setopt($resource, CURLOPT_HTTPHEADER, ['authkey: ' . Config::get('api.leslies.authkey')]);
        curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($resource);
        if(!empty($res)) {
            $data = json_decode($res, true);
            if(!empty($data)) {
                $retval = $data;
            }
        }

        return $retval;
    }

    /**
     *
     * Get All Products by performing a search with no query.
     * TODO:: Implement scroll/pagination behavior by searching with scroll_id
     * TODO:: Refactor using elastic search interface
     *
     * @return array
     */
    public static function getProducts() {
        $retval = [];
        $resource = curl_init();
        curl_setopt($resource, CURLOPT_URL, 'localhost:9200/products/prods/_search?scroll=1m&size=500');
        curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($resource);


        if(!empty($response)) {
            $response = json_decode($response, true);

            if(!empty($response['hits'])) {
                $retval = $response['hits']['hits'];
            }

        }

        return $retval;

    }
	
	
	/**
	 * @param $id
	 *
	 * @return array
	 */
    public static function getProduct($id) {
        $retval = [];
        $resource = curl_init();
        curl_setopt($resource, CURLOPT_URL, 'localhost:9200/products/prods/_search?scroll=1m&size=500');
        curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($resource, CURLOPT_POSTFIELDS, json_encode(['query' => ['terms' => ['_id' => [$id]]]]));
        $response = curl_exec($resource);


        if(!empty($response)) {
            $response = json_decode($response, true);

            if(!empty($response['hits'])) {
                $retval = $response['hits']['hits'];
            }

        }

        return $retval;

    }

    /**
     * @param $query
     *
     * TODO:: Make Search better using A Synonyms Algorithm and some Word Stemming,
     * TODO:: maybe some word vectors (Utilize these for word tagging and then prioritize then over description search "tags^1.5")
     *
     * @return array
     */
    public static function searchProducts($query) {
        $retval = [];
        $resource = curl_init();
        curl_setopt($resource, CURLOPT_URL, 'localhost:9200/products/prods/_search?scroll=1m&size=500');
        curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($resource, CURLOPT_POSTFIELDS, json_encode(
            [
                'query' => [
                    'multi_match' => [
                        'query' => $query,
                        'fields' => [
                            'name^2',
                            'description^1'
                        ]
                    ]
                ]
            ]
        ));
        $response = curl_exec($resource);


        if(!empty($response)) {
            $response = json_decode($response, true);

            if(!empty($response['hits'])) {
                $retval = $response['hits']['hits'];
            }

        }

        return $retval;
    }
	
	
	/**
	 * @param array $data
	 *
	 * Indexes document to elastic (products index)
	 *
	 */
    public static function indexDocument($data = []) {

        if(!empty($data['id'])) {
            $id = $data['id'];

            $resource = curl_init();
            curl_setopt($resource, CURLOPT_URL, 'localhost:9200/products/prods/' . $id);
            curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($resource, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($resource, CURLOPT_POSTFIELDS, json_encode($data));

            $response = curl_exec($resource);
            var_dump($response);
        }

    }
	
	
	/**
	 * Creates products index with mappings for search and things
	 */
    public static function createIndex() {
        $data = [
            'settings' => [
                'number_of_shards' => 1
            ],
            'mappings' => [
                '_default_' => [
                    'properties' => [
                        'name' => ['type' => 'string'],
                        'id' => ['type' => 'integer'],
                        'description' => ['type' => 'text']
                    ]
                ]
            ]
        ];
        $resource = curl_init();
        curl_setopt($resource, CURLOPT_URL, 'localhost:9200/products');
        curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($resource, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($resource, CURLOPT_POSTFIELDS,http_build_query($data));

        $response = curl_exec($resource);
        var_dump($response);
    }

    /**
     * @param string $measurement
     * @param array $fields
     * @param int $value
     *
     * Logs pageviews (or other actions) to influxDB
     * $fields relate to tags on influxDB
     *
     */
    public static function logAction($measurement = 'pv', $fields = [], $value = 1) {

        $data = $measurement;
        $fields_transform = [];
        foreach($fields as $name => $v) {
            $fields_transform[] = $name.'='.urlencode($v);
        }

        if(!empty($fields_transform)) {
            $data .= ',' . implode(',', $fields_transform);
        }
        $data .= ' value='.$value;

        $resource = curl_init();
        curl_setopt($resource, CURLOPT_URL, 'http://localhost:8086/write?db=leslies');
        curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($resource, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($resource, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($resource);
    }

}