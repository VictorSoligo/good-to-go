#!/bin/bash

(cd backend && composer install)

tmux new-session -d -s backend 'cd backend && php -S 0.0.0.0:8080 index.php'
tmux new-session -d -s consumer 'cd backend/src/Queue/Consumers && php send-mail-consumer.php'

clear

echo "🚀 Backend running at http://0.0.0.0:8080"
echo "🚀 Send mail consumer running"