import time
import requests
import json
import random
import math
from config import *


class Check:
    def __init__(self, ip, port, id):
        self.ip = ip
        self.port = port
        self.id = id
        self.header = {
            'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36',
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Accept-Encoding': 'gzip, deflate',
            'Accept-Language': 'zh-CN,zh;q=0.9'
        }

    def check(self):
        if not self.check_index():
            log('[*] {}:{} index check filed.'.format(self.ip, self.port))
            return False
        if not self.check_search():
            log('[*] {}:{} search check filed.'.format(self.ip, self.port))
            return False
        if not self.check_admin_login():
            log('[*] {}:{} admin_login check filed.'.format(self.ip, self.port))
            return False
        log('[-] {}:{} seems ok.'.format(self.ip, self.port))
        return True

    def check_index(self):
        url = 'http://{}:{}/index.php'.format(self.ip, self.port)
        check_str = '<li><a href="sqlgunclass.php?id=1">人文地理</a></li>'
        check_str2 = '<li><a href="sqlgunclass.php?id=8">宇宙发现</a></li>'
        check_str3 = '<li><a href="sqlgunnews.php?id=32">法国“蜘蛛人”登上261米大厦</a></li>'
        check_str4 = '<li><a href="sqlgunnews.php?id=22">百岁日本锦鲤 价值百万</a></li>'
        try:
            res = requests.get(url, headers=self.header, timeout=3)
            if res.status_code == 200 and check_str in res.content.decode() and check_str2 in res.content.decode():
                if check_str3 in res.content.decode() and check_str4 in res.content.decode():
                    return True
            return False
        except:
            return False

    def check_search(self):
        url = 'http://{}:{}/sqlgunsearch.php'.format(self.ip, self.port)
        check_str = '<li><span>2011-06-09 16:11:53</span><a href="sqlgunnews.php?id=32">法国“蜘蛛人”登上261米大厦</a></li>'
        check_str2 = '<li><a href="sqlgunnews.php?id=32">法国“蜘蛛人”登上261米大厦</a></li>'
        check_str3 = '<li><a href="sqlgunnews.php?id=22">百岁日本锦鲤 价值百万</a></li>'

        data = {'key': '261'}
        try:
            res = requests.post(url, data=data, headers=self.header, timeout=3)
            if res.status_code == 200 and check_str in res.content.decode() and check_str2 in res.content.decode():
                if check_str3 in res.content.decode():
                    return True
            return False
        except:
            return False

    def check_admin_login(self):
        url_login = 'http://{}:{}/sqlgunadmin/login.php?action=login'.format(
            self.ip, self.port)
        check_str = '<frame scrolling="no" src="top.php" />'
        data = {
            'admin': 'check',
            'password': '$#@check@#$',
        }
        try:
            res = requests.post(url_login, data=data,
                                headers=self.header, timeout=3)
            if res.status_code == 200 and check_str in res.content.decode():
                return True
            return False
        except:
            return False


def log(msg=''):
    with open('check.log', 'a') as f:
        f.write(msg+'\n')


def get_all_target():
    targets = []  # [(ip, port, id), ]
    with open('teams.txt', 'r') as f:
        contents = f.readlines()
    for i in range(len(contents)):
        team = json.loads(contents[i])
        ip, port, id = team['ip'], team['Server'], i
        targets.append((ip, port, id))
    return targets


def wait_start(start_time):
    now_time = time.time()
    sleep_time = start_time - now_time
    if sleep_time > 0:
        time.sleep(sleep_time)


if __name__ == "__main__":
    targets = get_all_target()
    checkers = []
    for target in targets:
        checker = Check(target[0], target[1], target[2]+start_id)
        checkers.append(checker)

    end_time = time.mktime(time.strptime(end_time, "%Y-%m-%d %H:%M:%S"))
    start_time = time.mktime(time.strptime(start_time, "%Y-%m-%d %H:%M:%S"))
    wait_start(start_time)
    while True:
        now_time = time.time()
        round = math.ceil((now_time-start_time)/Duration)
        if now_time >= end_time:
            log('*'*15+' End '+'*'*15)
            exit()
        log_time = time.strftime(
            "%Y-%m-%d %H:%M:%S", time.localtime(time.time()))
        log('*'*15+' Round {} '.format(str(round).zfill(3))+'*'*15)
        log('*'*10+' {} '.format(log_time)+'*'*10)
        log('*'*41)
        for checker in checkers:
            if not checker.check():
                GameBoxID = checker.id   # 靶机 ID
                try:
                    resp = requests.post(
                        check_url,
                        json={'GameBoxID': GameBoxID},
                        headers={'Authorization': TOKEN},
                        timeout=3
                    ).json()
                    if resp['error'] != 0:
                        log('    '+resp['msg'])
                except:
                    log('    {}:{} down failed'.format(checker.ip, checker.port))
        log()
        time.sleep(random.randint(2*60, 5*60))
