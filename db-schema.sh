#!/bin/bash

docker exec mysql /usr/bin/mysqldump --no-data -u root --password=123456 goodtogo > ./database/schema.sql