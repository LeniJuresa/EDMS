#!/bin/bash

php artisan config:clear

echo "Waiting for database..."
until mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" -e "SELECT 1" > /dev/null 2>&1; do
  sleep 2
done

echo "Importing SQL database if tables are missing..."
TABLE_COUNT=$(mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" -N -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='$DB_DATABASE';")

if [ "$TABLE_COUNT" -eq 0 ]; then
  mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" < ed_management_system.sql
  echo "SQL import completed."
else
  echo "Database already has tables. Skipping import."
fi

apache2-foreground
