This is just a simple demonstration of implementing "did you mean" with 
Elastic and levenshtein.

You can start it with:
```bash
docker-compose up -d
```

# Elastic

Implemented through simple Query, there is probably a better way.

# Levenshtein

Just to comparison to Elastic, it should be very slow for many (hundrets of thousands) items.

# Prequisities

 - Docker 
 - Docker compose
 
# Adresses

 - [app](http://localhost:888/)
 - [mysql adminer](http://localhost:888/adminer.php)
    - **server:** `mysql`
    - **user:** `root`
    - **password:** `pswd`
    - **database:** `test`


# Notes

Elasticsearch may have promblem with `vm.max_map_count` limit, it may 
need to be [set higher](https://www.elastic.co/guide/en/elasticsearch/reference/5.0/docker.html#docker-cli-run-prod-mode) 
(it will last until next reboot)
