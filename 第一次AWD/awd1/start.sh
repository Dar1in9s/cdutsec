#!/bin/bash

team_num=$1
ip=$2

if [ "$team_num" = "" ] || [ "$ip" = "" ]; then
    echo "sh start.sh [team nums] [ip]"
else
    rm -rf ./run/team*
    cat /dev/null > teams.txt
    sed -n '1,2p' ./run/docker-compose > ./run/docker-compose.yml
    rootpwd=`echo $RANDOM | md5sum | base64 | head -c 10`
    i=1
    while (($i<=$team_num)); do   # while (("$i" <= "$team_num")); do
        # 生成队伍的文件
        cp -r ./files ./run/team${i}

        # 设置密码（写入Dockerfile)
        ctfpwd=`echo $RANDOM | md5sum | base64 | head -c 10`
        sed -i 's/ctfpwd/'$ctfpwd'/g' ./run/team${i}/Dockerfile
        sed -i 's/rootpwd/'$rootpwd'/g' ./run/team${i}/Dockerfile

        # 设置端口（写入docker-compose.yml）
        let sshport=$RANDOM+10000
        let serverport=$sshport+1
        sed -n '3,$p' ./run/docker-compose >> ./run/docker-compose.yml
        sed -i 's/team_/team'$i'/g' ./run/docker-compose.yml
        sed -i 's/serverport/'$serverport'/g' ./run/docker-compose.yml
        sed -i 's/sshport/'$sshport'/g' ./run/docker-compose.yml

        # 将队伍信息存出来
        printf '{"team": "team'$i'", ' >> teams.txt
        printf '"ip": "'$ip'", ' >> teams.txt
        printf '"Server": "'$serverport'", ' >> teams.txt
        printf '"ssh": "'$sshport'", ' >> teams.txt
        printf '"user": "ctf", '  >> teams.txt
        printf '"pass": "'$ctfpwd'", ' >> teams.txt
        printf '"rootpwd": "'$rootpwd'"}\n' >> teams.txt

        let "i++"
    done
    docker-compose -f ./run/docker-compose.yml up -d
fi
