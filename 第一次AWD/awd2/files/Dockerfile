FROM ubuntu:14.04

COPY html/ /var/www/html/

RUN sed -i 's/http:\/\/archive.ubuntu.com\/ubuntu\//http:\/\/mirrors.tuna.tsinghua.edu.cn\/ubuntu\//g' /etc/apt/sources.list && \
    sed -i '/security/d' /etc/apt/sources.list && \
    apt-get update -y && \
    apt-get -yqq install supervisor mariadb-server apache2 php5 libapache2-mod-php5 \
    php5-mysql php5-gd php5-mcrypt ssh && \
    # config (不允许查看目录)
    sed -i "154s/Indexes//" /etc/apache2/apache2.conf && \
    sed -i "165s/Indexes//" /etc/apache2/apache2.conf && \
    /etc/init.d/apache2 start && \
    # mysql
    rm -rf /var/lib/mysql && \
    mysql_install_db --user=mysql --datadir=/var/lib/mysql && \
    ## 启动mysql
    sh -c 'mysqld_safe &' && \
    sleep 5s  && \
    mysqladmin -uroot password 'CDUT' && \
    mysql -e "source /var/www/html/sqlgunnews.sql;" -uroot -pCDUT
    # supervisor
RUN mkdir -p /var/log/supervisor && \
    mv /var/www/html/supervisord.conf /etc/ && \
    # 权限控制
    chown -R www-data:www-data /var/www/html/ && \
    chmod -R 755 /var/www/html/ && \
    # 题目相关设置
    rm /var/www/html/sqlgunnews.sql /var/www/html/index.html
    # 允许root远程登录
RUN sed -ri 's/^#?PermitRootLogin\s+.*/PermitRootLogin yes/' /etc/ssh/sshd_config
RUN useradd -g www-data ctf -m && \
    # 设置ctf用户密码
    password_ctf=$(openssl passwd -1 -salt 'abcdefg' 'ctfpwd') && \
    password_ctf=`echo $password_ctf|sed 's/\//\\\\\//g'` &&\
    sed -i 's/^ctf:!/ctf:'$password_ctf'/g' /etc/shadow    &&\
    # 设置root用户密码
    password_root=$(openssl passwd -1 -salt 'abcdefg' 'rootpwd') && \
    password_root=`echo $password_root|sed 's/\//\\\\\//g'`  &&\
    sed -i 's/^root:\*/root:'$password_root'/g' /etc/shadow

RUN chmod -R 777 /var/www/html

RUN mkdir /var/run/sshd

RUN echo 'flag{this_is_flag}' > /flag

EXPOSE 80
EXPOSE 22

ENTRYPOINT ["supervisord", "-n"]
