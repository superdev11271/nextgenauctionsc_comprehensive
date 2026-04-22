cd public_html/auction-market.xtiss.in
git stash
git pull
composer install --ignore-platform-reqs
php artisan storage:link
php artisan optimize:clear
composer dump-autoload
exit
