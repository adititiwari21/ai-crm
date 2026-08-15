import requests
from bs4 import BeautifulSoup

url = "https://example.com"

response = requests.get(url, timeout=10)

soup = BeautifulSoup(response.text, "html.parser")

print("Website Title:")
print(soup.title.get_text(strip=True))

print("\nHeadings:")

for heading in soup.find_all(["h1", "h2", "h3"]):
    print("-", heading.get_text(strip=True))