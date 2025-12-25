FROM mariadb:10.11

# Copy database initialization scripts
COPY db-init/ /docker-entrypoint-initdb.d/

CMD ["mariadbd", "--transaction-isolation=READ-COMMITTED", "--binlog-format=ROW"]
