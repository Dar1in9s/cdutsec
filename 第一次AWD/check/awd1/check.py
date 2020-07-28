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
        if not self.check_register():
            log('[*] {}:{} register check filed.'.format(self.ip, self.port))
            return False
        if not self.check_login():
            log('[*] {}:{} login check filed.'.format(self.ip, self.port))
            return False
        log('[-] {}:{} seems ok.'.format(self.ip, self.port))
        return True

    def check_index(self):
        url = 'http://{}:{}/index.php'.format(self.ip, self.port)
        check_str = '<form   class="form-signin" id="test" method="post"  action="./index.php?c=User&a=login">'
        try:
            res = requests.get(url, headers=self.header, timeout=3)
            if res.status_code == 200 and check_str in res.content.decode():
                return True
            return False
        except:
            return False

    def check_register(self):
        url = 'http://{}:{}/index.php?c=User&a=register'.format(
            self.ip, self.port)
        check_post_str = '<strong>Warning!</strong> Something wrong，check again！'
        check_post_str2 = '<strong>successful!</strong>your oprating is ok！'
        data = {
            'username': 'check',
            'password': '$#@check@#$',
            'sex': '1',
            'age': '1'
        }
        try:
            res = requests.post(url, data=data, headers=self.header, timeout=3)
            if res.status_code == 200 and (check_post_str in res.content.decode() or check_post_str2 in res.content.decode()):
                return True
            return False
        except:
            return False

    def check_login(self):
        url_login = 'http://{}:{}/index.php?c=User&a=login'.format(
            self.ip, self.port)
        check_str = '<a href="./index.php?c=User&a=logout">login out</a>'
        data = {
            'username': 'check',
            'password': '$#@check@#$'
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
        time.sleep(random.randint(3*60, Duration))
