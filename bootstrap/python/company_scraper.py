@'
import sys
import subprocess
from urllib.parse import urlparse
from bs4 import BeautifulSoup


def scrape_company(url, ip=None):

    parsed = urlparse(url)
    hostname = parsed.hostname

    if not hostname:
        raise Exception("Invalid website URL.")

    print("Hostname:", hostname)

    # IP Laravel se milegi.
    # Testing ke liye example.com ka fallback.
    if not ip:

        if hostname == "example.com":
            ip = "104.20.23.154"
        else:
            raise Exception(
                "IP address was not provided by Laravel."
            )

    print("Using IP:", ip)

    curl_command = [
        "curl.exe",
        "--silent",
        "--show-error",
        "--location",
        "--max-time",
        "30",
        "--resolve",
        f"{hostname}:443:{ip}",
        url
    ]

    print("Starting secure request...")

    result = subprocess.run(
        curl_command,
        capture_output=True,
        text=True,
        encoding="utf-8",
        errors="replace",
        timeout=40
    )

    if result.returncode != 0:
        raise Exception(
            result.stderr.strip()
            or "curl request failed."
        )

    html = result.stdout

    if not html:
        raise Exception(
            "Website returned empty response."
        )

    soup = BeautifulSoup(
        html,
        "html.parser"
    )

    title = (
        soup.title.get_text(strip=True)
        if soup.title
        else "Not found"
    )

    description_tag = soup.find(
        "meta",
        attrs={"name": "description"}
    )

    if description_tag:
        description = description_tag.get(
            "content",
            "Not found"
        )
    else:
        description = "Not found"

    headings = []

    for heading in soup.find_all(
        ["h1", "h2", "h3"]
    ):

        text = heading.get_text(
            " ",
            strip=True
        )

        if text:
            headings.append(text)

    return {
        "title": title,
        "description": description,
        "headings": headings
    }


if __name__ == "__main__":

    if len(sys.argv) < 2:
        print("SCRAPING ERROR:")
        print("Website URL is required.")
        sys.exit(1)

    website = sys.argv[1]

    # Second argument = IP from Laravel
    ip = sys.argv[2] if len(sys.argv) >= 3 else None

    try:

        data = scrape_company(
            website,
            ip
        )

        print()
        print("Company Information")
        print("--------------------")

        print(
            "Title:",
            data["title"]
        )

        print(
            "Description:",
            data["description"]
        )

        print()
        print("Headings:")

        for heading in data["headings"]:
            print("-", heading)

    except Exception as e:

        print()
        print("SCRAPING ERROR:")
        print(str(e))

        sys.exit(1)
'@ | Set-Content .\python\company_scraper.py -Encoding UTF8