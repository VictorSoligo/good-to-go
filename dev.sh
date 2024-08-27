#!/bin/bash

(cd backend && composer install)

tmux new-session -d -s backend 'cd backend && php -S 0.0.0.0:8080 index.php'

clear

echo "🚀 Backend running at http://0.0.0.0:8080"