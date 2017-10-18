FROM mysql:latest

MAINTAINER Rafael Bogaveev <bogaveev.rafael@gmail.com>

ADD sql/db_sql.sql /root/db_sql.sql
ADD start.sh /root/start.sh

CMD ["./root/start.sh"]