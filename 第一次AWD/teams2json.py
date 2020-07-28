import json
import sys
import hashlib

try:
    challenge_id = int(sys.argv[1])
    team_id_start = int(sys.argv[2])
    team_file = sys.argv[3]
    awd_no = sys.argv[4]
except:
    print('python3 teams.py [challenge_id] [team_id_start] [team_file] [awd_no]')
    sys.exit()

with open(team_file, 'r') as f:
    contents = f.readlines()
teams = []
all_server = []
for i in range(len(contents)):
    team = {}
    team_tmp = json.loads(contents[i].strip())
    token = hashlib.md5(team_tmp['team'].encode('utf8')).hexdigest()[0:10] + awd_no
    team['ChallengeID'] = challenge_id
    team['TeamID'] = team_id_start
    team['IP'] = team_tmp['ip']
    team['Port'] = team_tmp['Server']
    team['SSHPort'] = team_tmp['ssh']
    team['SSHUser'] = 'root'
    team['SSHPassword'] = team_tmp['rootpwd']
    team['Description'] = "你的环境：\n    Server: {}\n    SSH: {}\n    User: {}\n    Pass: {}\n可攻击目标：\n{}".format(
        team_tmp['Server'],
        team_tmp['ssh'],
        team_tmp['user'],
        team_tmp['pass'],
        'http://47.112.209.24:8000/?token=' + token
    )
    all_server.append(team_tmp['ip'] + ':' + team_tmp['Server'])
    teams.append(team)
    team_id_start += 1

print(json.dumps(teams))
