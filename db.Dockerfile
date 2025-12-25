FROM mariadb:10.11
CMD ["mariadbd", "--transaction-isolation=READ-COMMITTED", "--binlog-format=ROW"]
