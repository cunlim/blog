```bash


#### 리눅스 사용자 목록 확인
# 아이디만 짤라서 보기
cut -f1 -d: /etc/passwd
# USERADD 를 통해 등록된 계정만 보기
grep /bin/bash /etc/passwd
# USERADD 를 통해 등록된 계정만 아이디만 짤라서 보기
grep /bin/bash /etc/passwd | cut -f1 -d:


#### 비밀번호 설정
passwd root




#### chown
# -R : 하위 경로의 소유자를 모두 변경합니다. 
# -f : 소유자 변경이 안 될때 오류 메시지 표출합니다.
# -c : 변경된 파일을 자세히 표출합니다.
# -v : 작업상태를 출력합니다.
chown user:usergroup file.txt		# file
chown -R user:usergroup /dir1/dir2	# dir

chmod -R 707 data




#### du 용량 통계
du -sh .
du -sh ./* | sort -hr
du -sh --exclude={data,} ./*
# exclude={plugin,phpmyadmin,*.sql,*.zip,*.msi,*.tar.gz,.vscode-server,mall,.git}


#### find
find . -name "file_name_starts*"
find . -name  "dir_name_starts*" -type d


#### 현재 폴더의 하위 폴더 개수 및 파일 개수 구하기
# dir   depth = 1
ls -l | grep ^d | wc -l
# file  depth = 1
ls -l | grep ^- | wc -l
# dir   depth = n
find . -type d | wc -l
# file  depth = n
find . -type f | wc -l




#### move, delete
mv ./DW* ./tmp
rm -rf ./tmp


#### 심볼릭 링크
ln -s /home2/goods /home/goods_ss




#### tail 파일 마지막 n줄 추출
tail -n 2000 /opt/mysql/var/slow.log > ./log/temp_slow.log




#### pid로 프로세스 실행 파일 경로 찾기
ls -al /proc/28819 | grep exe

#### port list
netstat -tnlp




#### yum
yum list httpd
yum info httpd




#### docker
(copy .ssh/authorized_keys)
yum update
yum install http://opensource.wandisco.com/centos/7/git/x86_64/wandisco-git-release-7-1.noarch.rpm
yum install git
yum install vim
(install docker)
systemctl status docker
systemctl enable docker
systemctl start docker
git clone https://github.com/gnuboard/gnuboard5.git

docker pull nginx
docker pull centos:latest

netstat -tnlp

docker run -itd --name "nginx" -p 80:80 -v /home/docker/nginx/html:/usr/share/nginx/html -v /home/docker/nginx/nginx.conf:/etc/nginx/nginx.conf nginx:latest /bin/bash
docker run -itd --name "server_01" -p 80:40001 centos:latest /bin/bash



docker images
docker container ls
docker ps -a

docker compose up -d
docker compose down
docker compose start
docker compose stop

docker rm -f $(docker ps -aq)
docker container rm -f $(docker container ls -aq)
docker rmi $(docker images -q)
docker image rm -f $(docker image ls -q)




# DOS 공격 대응
tail -n 10 ./apache/logs/access_log
route del -host 1.23.456.789 reject
route add -host 1.23.456.789 reject



```