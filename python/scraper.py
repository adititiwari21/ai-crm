import requests
from bs4 import BeautifulSoup

url = "https://example.com"

response = requests.get(url, timeout=10)

print("Status Code:", response.status_code)

soup = BeautifulSoup(response.text, "html.parser")

title = soup.title

if title:
    print("Page Title:", title.get_text(strip=True))
else:
    print("No title found")

print("Beautiful Soup is working successfully!")
