import requests
from collections import defaultdict
import os

# 获取环境变量中的 GitHub Token
GITHUB_TOKEN = os.getenv('GITHUB_TOKEN')

# 替换为您的 GitHub 用户名和仓库名
USER = 'YoreJ'
REPO = 'InternetDatabaseDevelopment'

headers = {
    'Accept': 'application/vnd.github.v3+json',
}

if GITHUB_TOKEN:
    headers['Authorization'] = f'token {GITHUB_TOKEN}'

contributors = defaultdict(lambda: {'commits': 0, 'additions': 0, 'deletions': 0})
page = 1
per_page = 100

while True:
    url = f'https://api.github.com/repos/{USER}/{REPO}/commits?per_page={per_page}&page={page}'
    response = requests.get(url, headers=headers)
    if response.status_code != 200:
        print(f"Failed to fetch commits: {response.status_code}")
        break
    data = response.json()

    if not data:
        break

    for commit in data:
        author = commit.get('author')
        if author:
            login = author['login']
            contributors[login]['commits'] += 1
            # 获取每个提交的详细信息以统计添加和删除的行数
            commit_url = commit.get('url')
            commit_response = requests.get(commit_url, headers=headers)
            if commit_response.status_code == 200:
                commit_data = commit_response.json()
                stats = commit_data.get('stats', {})
                contributors[login]['additions'] += stats.get('additions', 0)
                contributors[login]['deletions'] += stats.get('deletions', 0)

    page += 1

# 生成 Markdown 表格
markdown = "| 开发者 | 提交次数 | 添加行数 | 删除行数 |\n|--------|---------|---------|---------|\n"
for contributor, stats in sorted(contributors.items(), key=lambda item: item[1]['commits'], reverse=True):
    avatar_url = f"https://github.com/{contributor}.png?size=50"
    markdown += f"| <img src=\"{avatar_url}\" width=\"30\" /> [{contributor}](https://github.com/{contributor}) | {stats['commits']} | {stats['additions']} | {stats['deletions']} |\n"

# 写入 CONTRIBUTORS_TABLE.md
with open('CONTRIBUTORS_TABLE.md', 'w', encoding='utf-8') as f:
    f.write(markdown)

print("CONTRIBUTORS_TABLE.md 已生成。")
